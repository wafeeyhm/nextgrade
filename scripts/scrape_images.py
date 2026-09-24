"""
NextGrade Image Scraper & Asset Manager
Scrapes and prepares high-quality educational illustrations and images for:
- Bahasa Melayu (Ini & Itu, Kenderaan, Haiwan, Suku Kata)
- Mathematics (Clocks, Shapes, Counters)
- English (Pronouns, Articles, Phonics, Comprehension)
- Science (Astronomy, Marine/Land Animals, Sink/Float, Materials, Pollution, Plants)
- ICT (Peripherals, Storage, Input/Output devices)
"""

import os
import sys
import json
import time
import argparse
import urllib.request
import urllib.parse
from pathlib import Path

# Fix Windows console UTF-8 output
if sys.platform.startswith("win"):
    try:
        sys.stdout.reconfigure(encoding="utf-8")
    except Exception:
        pass

BASE_DIR = Path(__file__).resolve().parent.parent
IMAGES_DIR = BASE_DIR / "images"

# Categories and their items to scrape / generate
ASSET_CATALOG = {
    "ict": {
        "dir": IMAGES_DIR / "ict",
        "items": [
            {"name": "monitor", "keyword": "computer monitor", "title": "Monitor Screen", "color": "#3B82F6", "icon": "🖥️"},
            {"name": "keyboard", "keyword": "computer keyboard", "title": "Keyboard", "color": "#64748B", "icon": "⌨️"},
            {"name": "printer", "keyword": "desktop printer", "title": "Printer", "color": "#06B6D4", "icon": "🖨️"},
            {"name": "headphones", "keyword": "audio headphones", "title": "Headphones", "color": "#8B5CF6", "icon": "🎧"},
            {"name": "scanner", "keyword": "document flatbed scanner", "title": "Scanner", "color": "#10B981", "icon": "📠"},
            {"name": "system_unit", "keyword": "computer desktop case cpu", "title": "System Unit (CPU)", "color": "#475569", "icon": "🗄️"},
            {"name": "pendrive", "keyword": "usb flash drive pendrive", "title": "USB Pendrive", "color": "#F59E0B", "icon": "💾"},
            {"name": "floppy_disk", "keyword": "floppy disk 3.5 inch", "title": "Floppy Disk", "color": "#6366F1", "icon": "💾"},
            {"name": "cd_rom", "keyword": "compact disc cd rom", "title": "CD-ROM Disc", "color": "#EC4899", "icon": "💿"},
            {"name": "memory_card", "keyword": "sd memory card", "title": "Memory Card", "color": "#14B8A6", "icon": "🃏"},
            {"name": "mouse", "keyword": "computer optical mouse", "title": "Computer Mouse", "color": "#84CC16", "icon": "🖱️"},
            {"name": "webcam", "keyword": "computer video webcam", "title": "Webcam", "color": "#F43F5E", "icon": "📹"},
            {"name": "microphone", "keyword": "computer audio microphone", "title": "Microphone", "color": "#A855F7", "icon": "🎙️"},
            {"name": "speakers", "keyword": "computer sound speakers", "title": "Speakers", "color": "#0284C7", "icon": "🔊"}
        ]
    },
    "science": {
        "dir": IMAGES_DIR / "science",
        "items": [
            {"name": "sun", "keyword": "bright sun in sky", "title": "The Sun", "color": "#F59E0B", "icon": "☀️"},
            {"name": "moon", "keyword": "crescent moon night", "title": "The Moon", "color": "#E2E8F0", "icon": "🌙"},
            {"name": "earth", "keyword": "planet earth globe space", "title": "Planet Earth", "color": "#0284C7", "icon": "🌍"},
            {"name": "star", "keyword": "glowing stars night sky", "title": "Twinkling Star", "color": "#FBBF24", "icon": "⭐"},
            {"name": "sink_key", "keyword": "metal brass key in water sink", "title": "Metal Key (Sinks)", "color": "#78716C", "icon": "🔑"},
            {"name": "float_ball", "keyword": "plastic toy ball floating on water", "title": "Plastic Ball (Floats)", "color": "#FB7185", "icon": "⚽"},
            {"name": "float_wood", "keyword": "dry wooden twig branch floating", "title": "Wooden Twig (Floats)", "color": "#B45309", "icon": "🪵"},
            {"name": "sink_stone", "keyword": "stone pebble sinking in water", "title": "Heavy Stone (Sinks)", "color": "#57534E", "icon": "🪨"},
            {"name": "metal_spoon", "keyword": "stainless steel metal spoon", "title": "Metal Spoon", "color": "#94A3B8", "icon": "🥄"},
            {"name": "glass_cup", "keyword": "transparent drinking glass cup", "title": "Glass Cup", "color": "#38BDF8", "icon": "🥛"},
            {"name": "paper_book", "keyword": "open paper reading book", "title": "Paper Book", "color": "#F472B6", "icon": "📖"},
            {"name": "air_pollution", "keyword": "factory chimney black smoke air pollution", "title": "Air Pollution", "color": "#64748B", "icon": "🏭"},
            {"name": "water_pollution", "keyword": "plastic waste river ocean sea pollution", "title": "Water / Sea Pollution", "color": "#0D9488", "icon": "🌊"},
            {"name": "land_pollution", "keyword": "trash dump landfill garbage land pollution", "title": "Land Pollution", "color": "#A16207", "icon": "🗑️"},
            {"name": "plant_parts", "keyword": "botany plant roots stem leaf flower diagram", "title": "Parts of a Plant", "color": "#16A34A", "icon": "🌱"}
        ]
    },
    "english": {
        "dir": IMAGES_DIR / "english",
        "items": [
            {"name": "cat", "keyword": "cute domestic cat pet", "title": "A Cat", "color": "#F97316", "icon": "🐱"},
            {"name": "egg", "keyword": "whole brown chicken egg", "title": "An Egg", "color": "#FEF08A", "icon": "🥚"},
            {"name": "house", "keyword": "cozy family suburban house", "title": "A House", "color": "#3B82F6", "icon": "🏠"},
            {"name": "apple", "keyword": "fresh red delicious apple", "title": "An Apple", "color": "#EF4444", "icon": "🍎"},
            {"name": "octopus", "keyword": "ocean octopus swimming", "title": "An Octopus", "color": "#8B5CF6", "icon": "🐙"},
            {"name": "male_teacher", "keyword": "friendly man teacher cartoon", "title": "He (Man)", "color": "#0EA5E9", "icon": "👨‍🏫"},
            {"name": "little_girl", "keyword": "happy little girl child", "title": "She (Little Girl)", "color": "#EC4899", "icon": "👧"},
            {"name": "little_boy", "keyword": "cheerful little boy child", "title": "He (Little Boy)", "color": "#10B981", "icon": "👦"},
            {"name": "children_group", "keyword": "happy children friends playing together", "title": "They (Children)", "color": "#F59E0B", "icon": "👫"},
            {"name": "toys_bunch", "keyword": "colorful assorted children toys pile", "title": "They (Toys)", "color": "#A855F7", "icon": "🧸"},
            {"name": "palm_tree", "keyword": "tropical green palm tree coconut", "title": "It (Palm Tree)", "color": "#22C55E", "icon": "🌴"},
            {"name": "flower", "keyword": "colorful blooming daisy flower", "title": "It (Flower)", "color": "#F43F5E", "icon": "🌸"},
            {"name": "sports_balls", "keyword": "soccer basketball baseball balls", "title": "They (Sports Balls)", "color": "#EAB308", "icon": "⚽"},
            {"name": "banana", "keyword": "fresh yellow banana ripe", "title": "It (Banana)", "color": "#FACC15", "icon": "🍌"},
            {"name": "clock", "keyword": "round analog wall clock", "title": "It (Clock)", "color": "#6366F1", "icon": "⏰"},
            {"name": "color_pencils", "keyword": "assorted colored pencils drawing", "title": "They (Color Pencils)", "color": "#EC4899", "icon": "✏️"},
            {"name": "lollipop", "keyword": "heart shaped colorful sweet candy lollipop", "title": "Heart Shaped Lollipop", "color": "#FB7185", "icon": "🍭"}
        ]
    },
    "ini_itu": {
        "dir": IMAGES_DIR / "ini-itu",
        "items": [
            {"name": "basikal_dekat", "keyword": "bicycle close near arrow pointer", "title": "Ini Basikal (Dekat)", "color": "#0284C7", "icon": "🚲"},
            {"name": "hadiah_jauh", "keyword": "gift box present distant far arrow", "title": "Itu Hadiah (Jauh)", "color": "#EC4899", "icon": "🎁"},
            {"name": "jus_oren_dekat", "keyword": "fresh orange juice glass close", "title": "Ini Jus Oren (Dekat)", "color": "#F97316", "icon": "🧃"},
            {"name": "gunting_dekat", "keyword": "craft scissors close near", "title": "Ini Gunting (Dekat)", "color": "#64748B", "icon": "✂️"},
            {"name": "bunga_ros_jauh", "keyword": "red rose flower distant far", "title": "Itu Bunga Ros (Jauh)", "color": "#E11D48", "icon": "🌹"}
        ]
    },
    "maths": {
        "dir": IMAGES_DIR / "maths",
        "items": [
            {"name": "clock_3_00", "keyword": "analog clock face 3 o clock", "title": "Jam 3:00", "color": "#3B82F6", "icon": "🕒"},
            {"name": "clock_7_00", "keyword": "analog clock face 7 o clock", "title": "Jam 7:00", "color": "#6366F1", "icon": "🕖"},
            {"name": "clock_9_00", "keyword": "analog clock face 9 o clock", "title": "Jam 9:00", "color": "#8B5CF6", "icon": "🕘"},
            {"name": "clock_6_30", "keyword": "analog clock face 6 thirty", "title": "Jam 6:30", "color": "#06B6D4", "icon": "🕡"}
        ]
    }
}

def generate_svg_illustration(item, output_path):
    """
    Creates a clean, child-friendly, modern SVG educational illustration
    ensuring 100% offline reliability with zero broken images.
    """
    name = item["name"]
    title = item.get("title", name.replace("_", " ").title())
    color = item.get("color", "#4F46E5")
    icon = item.get("icon", "🌟")

    svg_content = f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 320" width="100%" height="100%">
  <defs>
    <linearGradient id="bg-grad-{name}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{color}" stop-opacity="0.15" />
      <stop offset="100%" stop-color="{color}" stop-opacity="0.05" />
    </linearGradient>
    <filter id="shadow-{name}" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="{color}" flood-opacity="0.25" />
    </filter>
  </defs>

  <!-- Background Card -->
  <rect width="400" height="320" rx="32" fill="url(#bg-grad-{name})" stroke="{color}" stroke-width="3" stroke-dasharray="0" />
  
  <!-- Outer Glow Ring -->
  <circle cx="200" cy="130" r="85" fill="{color}" fill-opacity="0.1" />
  <circle cx="200" cy="130" r="70" fill="white" filter="url(#shadow-{name})" />

  <!-- Center Emoji / Icon -->
  <text x="200" y="152" font-size="70" text-anchor="middle" dominant-baseline="middle" font-family="'Segoe UI Emoji', 'Apple Color Emoji', sans-serif">
    {icon}
  </text>

  <!-- Pill Tag -->
  <g transform="translate(200, 245)">
    <rect x="-140" y="-18" width="280" height="36" rx="18" fill="white" stroke="{color}" stroke-width="2" />
    <text x="0" y="5" font-family="'Nunito', 'Segoe UI', sans-serif" font-size="16" font-weight="800" fill="{color}" text-anchor="middle" dominant-baseline="middle">
      {title}
    </text>
  </g>

  <!-- Playful Decorative Dots -->
  <circle cx="45" cy="45" r="8" fill="{color}" fill-opacity="0.4" />
  <circle cx="355" cy="45" r="12" fill="{color}" fill-opacity="0.3" />
  <circle cx="50" cy="275" r="10" fill="{color}" fill-opacity="0.2" />
  <circle cx="350" cy="275" r="6" fill="{color}" fill-opacity="0.5" />
</svg>'''

    with open(output_path, "w", encoding="utf-8") as f:
        f.write(svg_content)
    print(f"  [✓ Generated SVG] {output_path.name}")

def download_image_wikimedia(keyword, output_path):
    """
    Attempts to download a royalty-free image from Wikimedia Commons API.
    Falls back gracefully if network unavailable.
    """
    try:
        url = (
            "https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search"
            f"&gsrsearch={urllib.parse.quote(keyword)}&gsrnamespace=6&gsrlimit=1&prop=imageinfo&iiprop=url|mime"
        )
        req = urllib.request.Request(url, headers={"User-Agent": "NextGradeEducationalApp/1.0 (contact: edudev@nextgrade.local)"})
        with urllib.request.urlopen(req, timeout=5) as resp:
            data = json.loads(resp.read().decode("utf-8"))
            pages = data.get("query", {}).get("pages", {})
            for page_id, page_info in pages.items():
                imageinfo = page_info.get("imageinfo", [{}])[0]
                img_url = imageinfo.get("url")
                if img_url and (img_url.endswith(".jpg") or img_url.endswith(".png") or img_url.endswith(".jpeg")):
                    img_req = urllib.request.Request(img_url, headers={"User-Agent": "NextGradeEducationalApp/1.0"})
                    with urllib.request.urlopen(img_req, timeout=8) as img_resp:
                        with open(output_path, "wb") as out_file:
                            out_file.write(img_resp.read())
                    print(f"  [✓ Scraped Wikimedia] {keyword} -> {output_path.name}")
                    return True
    except Exception as e:
        # Fallback to SVG generator silently
        pass
    return False

def process_catalog(selected_category=None, force_svg=False):
    """Processes asset generation and downloads for catalog."""
    total_processed = 0
    for cat_name, cat_data in ASSET_CATALOG.items():
        if selected_category and selected_category != cat_name:
            continue
        
        target_dir = cat_data["dir"]
        target_dir.mkdir(parents=True, exist_ok=True)
        print(f"\n📂 Processing category: [{cat_name}] -> {target_dir}")

        for item in cat_data["items"]:
            base_name = item["name"]
            svg_path = target_dir / f"{base_name}.svg"
            jpg_path = target_dir / f"{base_name}.jpg"

            # Always ensure SVG fallback exists
            if not svg_path.exists():
                generate_svg_illustration(item, svg_path)
                total_processed += 1

            # Scrape photo if requested and not force_svg
            if not force_svg and not jpg_path.exists():
                success = download_image_wikimedia(item["keyword"], jpg_path)
                if success:
                    total_processed += 1

    print(f"\n🎉 Completed! Assets processed/verified: {total_processed}")

def main():
    parser = argparse.ArgumentParser(description="NextGrade Image Scraper & Educational Asset Generator")
    parser.add_argument("--category", choices=list(ASSET_CATALOG.keys()), help="Target specific category")
    parser.add_argument("--all", action="store_true", help="Process all categories")
    parser.add_argument("--svg-only", action="store_true", help="Generate vector SVG educational illustrations only")
    args = parser.parse_args()

    category = args.category if args.category else None
    process_catalog(selected_category=category, force_svg=args.svg_only)

if __name__ == "__main__":
    main()
