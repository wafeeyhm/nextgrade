"""
Scraper for Science Sink or Float Real Photographs
Downloads authentic photographs via Wikimedia's 500px CDN thumbnails.
No SVGs, no text overlays, pure real photos.
"""

import os
import sys
import json
import time
import urllib.request
import urllib.parse
from pathlib import Path

if sys.platform.startswith("win"):
    try:
        sys.stdout.reconfigure(encoding="utf-8")
        sys.stderr.reconfigure(encoding="utf-8")
    except Exception:
        pass

BASE_DIR = Path(__file__).resolve().parent.parent
SCIENCE_DIR = BASE_DIR / "images" / "science"
SCIENCE_DIR.mkdir(parents=True, exist_ok=True)

HEADERS = {
    "User-Agent": "NextGradeEdu/2.0 (contact: admin@nextgrade.local) Python/3.13"
}

ITEMS_TO_SCRAPE = [
    ("rubber_duck", ["yellow rubber duck bath toy", "rubber duck toy"]),
    ("iron_nail", ["iron nail steel", "metal nail tool"]),
    ("leaf", ["fallen autumn tree leaf", "green plant leaf single"]),
    ("coin", ["metal coin currency money", "euro metal coin"]),
    ("foam_float", ["pool noodle foam water", "swimming kickboard foam"]),
    ("marble", ["glass toy playing marble", "glass marbles colorful"]),
    ("plastic_bottle", ["clear plastic water bottle", "empty plastic drink bottle"]),
    ("anchor", ["large iron ship anchor marine", "steel boat anchor"]),
    ("brick", ["red construction clay brick", "building brick red"]),
    ("swim_ring", ["inflatable swim ring float pool", "swimming lifebuoy ring"]),
    ("cork", ["wine bottle cork stopper", "cork wood stopper"]),
    ("toy_boat", ["plastic toy boat child", "miniature toy sailboat"]),
    ("ice_cube", ["clear cold ice cubes melting", "ice cube single"]),
    ("paper_boat", ["folded origami paper boat", "paper boat origami"]),
    ("feather", ["white bird quill feather", "pigeon feather bird"]),
    ("hammer", ["steel claw hammer wooden handle", "carpenter hammer tool"]),
    ("coconut", ["whole brown coconut fruit", "coconut shell"]),
    ("balloon", ["inflatable colorful party balloon", "red party balloon"]),
    ("straw", ["colorful plastic drinking straws", "drinking straw"]),
    ("gold_ring", ["gold wedding band ring", "shiny gold ring"]),
    ("orange", ["fresh whole orange fruit citrus", "ripe orange fruit"]),
    ("sponge", ["yellow kitchen cleaning sponge", "household cleaning sponge"]),
    ("wrench", ["steel adjustable wrench tool", "metal spanner tool"]),
    ("bottle_cap", ["plastic bottle screw cap", "bottle cap colorful"]),
    ("ping_pong", ["white table tennis ping pong ball", "table tennis ball"]),
    ("metal_bolt", ["steel threaded bolt screw", "hex metal bolt nut"]),
    ("water_bowl", ["clear glass bowl filled with water", "glass bowl water"])
]

def search_wikimedia_thumb(query):
    url = (
        "https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search"
        f"&gsrsearch={urllib.parse.quote(query)}&gsrnamespace=6&gsrlimit=3&prop=imageinfo&iiprop=url&iiurlwidth=500"
    )
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
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

def download_image(url, target_path):
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=12) as resp:
            content = resp.read()
        if len(content) < 1000:
            return False
        with open(target_path, "wb") as f:
            f.write(content)
        return True
    except Exception as e:
        print(f"      [Download error]: {e}")
        return False

print("=== Scraping Real Photos for Sink or Float ===")
for name, queries in ITEMS_TO_SCRAPE:
    target_file = SCIENCE_DIR / f"{name}.jpg"
    if target_file.exists() and target_file.stat().st_size > 3000:
        print(f"  ✓ Already exists: {target_file.name} ({target_file.stat().st_size // 1024} KB)")
        continue

    downloaded = False
    for q in queries:
        print(f"  Searching for '{name}' using: '{q}'...")
        thumb_url = search_wikimedia_thumb(q)
        if thumb_url:
            print(f"    Downloading thumbnail from CDN...")
            if download_image(thumb_url, target_file):
                print(f"    ✓ Saved: {target_file.name} ({target_file.stat().st_size // 1024} KB)")
                downloaded = True
                time.sleep(0.5)
                break
            time.sleep(0.5)
        time.sleep(0.5)

    if not downloaded:
        print(f"  ❌ Failed to download photo for: {name}")

print("\n=== Scraping Completed ===")
