"""
NextGrade Real Image Scraper (CDN Thumbnail Edition)
Downloads authentic photographs via Wikimedia's official 500px thumbnail CDN.
Zero rate limits, super fast, authentic real photography.
Eradicates all answer-revealing SVGs.
"""

import os
import sys
import json
import time
import urllib.request
import urllib.parse
from pathlib import Path

# Fix Windows console UTF-8 output
if sys.platform.startswith("win"):
    try:
        sys.stdout.reconfigure(encoding="utf-8")
        sys.stderr.reconfigure(encoding="utf-8")
    except Exception:
        pass

BASE_DIR = Path(__file__).resolve().parent.parent
IMAGES_DIR = BASE_DIR / "images"

HEADERS = {
    "User-Agent": "NextGradeEdu/2.0 (contact: admin@nextgrade.local) Python/3.13"
}

ITEMS_TO_SCRAPE = {
    "ict": [
        ("monitor", ["computer monitor display", "LCD monitor screen"]),
        ("keyboard", ["computer keyboard", "mechanical keyboard keys"]),
        ("printer", ["inkjet printer", "laser printer office"]),
        ("headphones", ["headphones audio", "studio headphones"]),
        ("scanner", ["flatbed scanner", "document scanner desktop"]),
        ("system_unit", ["computer desktop tower case", "computer chassis"]),
        ("pendrive", ["USB flash drive", "USB thumb drive"]),
        ("floppy_disk", ["floppy disk 3.5", "3.5 inch diskette"]),
        ("cd_rom", ["compact disc CD", "optical disc CD ROM"]),
        ("memory_card", ["SD memory card", "SD card flash"]),
        ("mouse", ["computer optical mouse", "cordless mouse"]),
        ("webcam", ["USB computer webcam", "webcam camera desktop"]),
        ("microphone", ["studio microphone audio", "dynamic microphone"]),
        ("speakers", ["desktop computer speakers", "bookshelf audio speakers"])
    ],
    "science": [
        ("sun", ["bright sun in sky", "bright sun"]),
        ("moon", ["full moon night sky", "crescent moon"]),
        ("earth", ["planet earth space", "planet Earth blue marble"]),
        ("star", ["starry sky night", "night sky stars milky way"]),
        ("sink_key", ["brass metal door key", "metal key"]),
        ("float_ball", ["plastic toy ball", "floating beach ball"]),
        ("float_wood", ["dry wooden stick branch", "wooden log water"]),
        ("sink_stone", ["river pebble stone", "granite rock stone"]),
        ("metal_spoon", ["stainless steel spoon", "metal spoon utensil"]),
        ("glass_cup", ["empty glass cup water", "clear drinking glass"]),
        ("paper_book", ["open book reading pages", "closed hardcover book"]),
        ("air_pollution", ["smoke factory chimney air pollution", "industrial smokestack"]),
        ("water_pollution", ["plastic ocean sea water pollution", "polluted river waste"]),
        ("land_pollution", ["landfill trash garbage dump", "land pollution litter"]),
        ("plant_parts", ["green bean plant roots stem", "young tomato plant roots"]),
        ("flower", ["sunflower yellow bloom", "red rose flower"]),
        ("fruit", ["fresh fruits basket apple", "ripe red apples fruit"])
    ],
    "english": [
        ("apple", ["fresh red apple fruit", "red apple"]),
        ("banana", ["ripe yellow banana fruit", "fresh banana single"]),
        ("cat", ["cute domestic cat portrait", "furry house cat"]),
        ("egg", ["brown chicken egg", "whole farm egg"]),
        ("house", ["cozy family suburban house", "cottage home house"]),
        ("octopus", ["octopus swimming underwater", "common octopus marine"]),
        ("male_teacher", ["man teacher classroom blackboard", "male teacher school"]),
        ("little_girl", ["happy little girl smiling portrait", "schoolgirl portrait"]),
        ("little_boy", ["happy little boy smiling portrait", "schoolboy portrait"]),
        ("children_group", ["group of diverse happy children playing", "children smiling together"]),
        ("toys_bunch", ["pile of colorful children toys", "teddy bear and toys"]),
        ("palm_tree", ["tropical green palm tree blue sky", "coconut palm tree"]),
        ("sports_balls", ["soccer ball and basketball", "sports equipment balls"]),
        ("clock", ["round analog wall clock 12 numbers", "classic wall clock analog"]),
        ("color_pencils", ["colored pencils drawing set", "assorted coloring pencils"]),
        ("lollipop", ["round swirl colorful sweet lollipop candy", "swirl lollipop candy"])
    ],
    "ini-itu": [
        ("basikal_dekat", ["city bicycle parking", "modern bicycle on road"]),
        ("hadiah_jauh", ["wrapped gift box ribbon present", "birthday gift box"]),
        ("jus_oren_dekat", ["fresh orange juice glass citrus", "orange juice glass straw"]),
        ("gunting_dekat", ["stationery office craft scissors", "pair of steel scissors"]),
        ("bunga_ros_jauh", ["single blooming red rose flower", "red rose blossom fresh"])
    ]
}

def search_wikimedia_thumb(query):
    url = (
        "https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search"
        f"&gsrsearch={urllib.parse.quote(query)}&gsrnamespace=6&gsrlimit=3&prop=imageinfo&iiprop=url&iiurlwidth=500"
    )
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=8) as resp:
            data = json.loads(resp.read().decode("utf-8"))
        pages = data.get("query", {}).get("pages", {})
        for pid, page_info in pages.items():
            imageinfo = page_info.get("imageinfo", [{}])[0]
            thumb_url = imageinfo.get("thumburl")
            if thumb_url and not thumb_url.lower().endswith(".svg.png"):
                return thumb_url
    except Exception as e:
        print(f"      [Search error '{query}']: {e}")
    return None

def download_file(url, target_path):
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            content = resp.read()
            if len(content) > 1000:
                with open(target_path, "wb") as f:
                    f.write(content)
                return True
    except Exception as e:
        print(f"      [Download error]: {e}")
    return False

def main():
    print("=== Scraping Authentic Real Photos from Wikimedia Commons ===")
    total_downloaded = 0
    total_skipped = 0

    # Clean out old SVG files from all folders
    for svg_file in IMAGES_DIR.glob("**/*.svg"):
        try:
            svg_file.unlink()
            print(f"  🗑️ Deleted answer-revealing SVG: {svg_file.relative_to(IMAGES_DIR)}")
        except Exception:
            pass

    for category, items in ITEMS_TO_SCRAPE.items():
        cat_dir = IMAGES_DIR / category
        cat_dir.mkdir(parents=True, exist_ok=True)
        print(f"\n📁 Processing category: [{category}] -> {cat_dir}")

        for name, query_list in items:
            jpg_path = cat_dir / f"{name}.jpg"

            if jpg_path.exists() and jpg_path.stat().st_size > 5000:
                print(f"  ✓ Already exists: {jpg_path.name} ({jpg_path.stat().st_size // 1024} KB)")
                total_skipped += 1
                continue

            found_thumb = None
            for query in query_list:
                time.sleep(0.6)
                found_thumb = search_wikimedia_thumb(query)
                if found_thumb:
                    break

            if found_thumb:
                success = download_file(found_thumb, jpg_path)
                if success:
                    print(f"  🎉 [Real Photo Downloaded] {name}.jpg ({jpg_path.stat().st_size // 1024} KB)")
                    total_downloaded += 1
                else:
                    print(f"  ⚠️ Failed downloading {found_thumb}")
            else:
                print(f"  ❌ No suitable photograph found for {name}")

    print(f"\nFinished! Downloaded: {total_downloaded}, Skipped (Already existed): {total_skipped}")

if __name__ == "__main__":
    main()
