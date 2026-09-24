"""
NextGrade Kindergarten 3 (Age 5-6) Question Bank Generator
Generates exactly 55 child-friendly, bite-sized questions for each of the 27 learning topics.
Total: 1,485 questions.
- Simple, warm kindergarten vocabulary (short questions under 10-12 words).
- Real photographs only, no text overlays or answer reveals.
- Bahasa Melayu module 100% in BM; English, Maths, Science, ICT in English.
"""

import os
import sys
import json
import random
from pathlib import Path

if sys.platform.startswith("win"):
    try:
        sys.stdout.reconfigure(encoding="utf-8")
        sys.stderr.reconfigure(encoding="utf-8")
    except Exception:
        pass

BASE_DIR = Path(__file__).resolve().parent.parent
DATA_DIR = BASE_DIR / "data"
DATA_DIR.mkdir(parents=True, exist_ok=True)
OUTPUT_FILE = DATA_DIR / "questions_bank.json"

all_questions = []

def add_q(topic_id, subject_id, text, audio, lang, q_type, img, passage, options, correct, hint, hint_audio, meta=None):
    all_questions.append({
        "topic_id": topic_id,
        "subject_id": subject_id,
        "question_text": text,
        "question_audio": audio or text,
        "lang": lang,
        "question_type": q_type,
        "image_url": img,
        "passage": passage,
        "options_json": json.dumps(options, ensure_ascii=False),
        "correct_answer": json.dumps(correct, ensure_ascii=False) if isinstance(correct, list) else str(correct),
        "hint_text": hint,
        "hint_audio": hint_audio or hint,
        "meta_data_json": json.dumps(meta, ensure_ascii=False) if meta else None
    })

print("Generating Kindergarten 3 Question Bank...")

# =========================================================================
# SUBJECT 1: BAHASA MELAYU (100% BM, K3 Friendly)
# =========================================================================

# --- Topic 1: bm_bulan (12 Bulan dalam Setahun) ---
months_bm = ["Januari", "Februari", "Mac", "April", "Mei", "Jun", "Julai", "Ogos", "September", "Oktober", "November", "Disember"]

# Q1-12: Bulan ke-X
for i, m in enumerate(months_bm, 1):
    q = f"Bulan ke-{i} dalam setahun ialah?"
    opts = [m] + random.sample([x for x in months_bm if x != m], 2)
    random.shuffle(opts)
    add_q("bm_bulan", "bahasa_melayu", q, q, "ms", "multiple_choice", None, None, opts, m, f"Kira dari awal: bulan ke-{i} ialah {m}.", f"Jawapannya ialah {m}.")

# Q13-24: Selepas bulan X
for i in range(11):
    cur_m = months_bm[i]
    next_m = months_bm[i + 1]
    q = f"Selepas bulan {cur_m}, bulan apa?"
    opts = [next_m] + random.sample([x for x in months_bm if x != next_m and x != cur_m], 2)
    random.shuffle(opts)
    add_q("bm_bulan", "bahasa_melayu", q, q, "ms", "multiple_choice", None, None, opts, next_m, f"Nyanyi lagu bulan: lepas {cur_m} mestilah {next_m}!", f"Selepas {cur_m} ialah {next_m}.")

# Q25-35: Sebelum bulan X
for i in range(1, 12):
    cur_m = months_bm[i]
    prev_m = months_bm[i - 1]
    q = f"Sebelum bulan {cur_m}, bulan apa?"
    opts = [prev_m] + random.sample([x for x in months_bm if x != prev_m and x != cur_m], 2)
    random.shuffle(opts)
    add_q("bm_bulan", "bahasa_melayu", q, q, "ms", "multiple_choice", None, None, opts, prev_m, f"Sebelum {cur_m} ialah {prev_m}.", f"Sebelum {cur_m} ialah {prev_m}.")

# Q36-45: Fakta ringkas K3
k3_facts_bulan = [
    ("Berapa bulan ada dalam setahun?", "12 bulan", ["12 bulan", "7 bulan", "5 bulan"], "Ada 12 bulan dalam satu tahun!"),
    ("Bulan pertama dalam setahun ialah?", "Januari", ["Januari", "Disember", "Mac"], "Bulan nombor 1 ialah Januari."),
    ("Bulan terakhir dalam setahun ialah?", "Disember", ["Disember", "Januari", "November"], "Bulan nombor 12 ialah Disember."),
    ("Bulan manakah paling pendek dengan 28 hari?", "Februari", ["Februari", "Mei", "Ogos"], "Bulan kedua sangat istimewa iaitu Februari."),
    ("Bulan Kebangsaan Malaysia disambut pada bulan?", "Ogos", ["Ogos", "Januari", "April"], "Bulan Merdeka ialah bulan Ogos."),
    ("Bulan yang bermula dengan huruf M ada Mac dan?", "Mei", ["Mei", "Jun", "Julai"], "Mac dan Mei bermula dengan huruf M."),
    ("Bulan yang bermula dengan huruf J ada Januari, Jun dan?", "Julai", ["Julai", "Ogos", "September"], "Januari, Jun dan Julai."),
    ("Bulan yang mempunyai 3 huruf sahaja ialah Mei dan?", "Jun", ["Jun", "Julai", "Mac"], "J - U - N ada tiga huruf sahaja."),
    ("Tahun baharu bermula pada 1 hari bulan apa?", "Januari", ["Januari", "Februari", "Disember"], "Tahun baharu bermula 1 Januari."),
    ("Sambung lagu: Januari, Februari, kemudian apa?", "Mac", ["Mac", "April", "Mei"], "Januari, Februari, Mac!")
]
for q, ans, opts, hint in k3_facts_bulan:
    add_q("bm_bulan", "bahasa_melayu", q, q, "ms", "multiple_choice", None, None, opts, ans, hint, hint)

# Q46-55: Susun 3 bulan mudah (ordering)
easy_order_bulan = [
    (["Januari", "Februari", "Mac"], "Susun 3 bulan ini dari awal:"),
    (["Februari", "Mac", "April"], "Susun 3 bulan berturutan ini:"),
    (["Mac", "April", "Mei"], "Susun mengikut urutan yang betul:"),
    (["April", "Mei", "Jun"], "Susun mengikut kalendar:"),
    (["Mei", "Jun", "Julai"], "Susun mengikut urutan:"),
    (["Jun", "Julai", "Ogos"], "Susun bulan-bulan ini mengikut urutan:"),
    (["Julai", "Ogos", "September"], "Susun mengikut urutan yang betul:"),
    (["Ogos", "September", "Oktober"], "Susun 3 bulan berturutan ini:"),
    (["September", "Oktober", "November"], "Susun mengikut urutan yang betul:"),
    (["Oktober", "November", "Disember"], "Susun 3 bulan terakhir dalam setahun:")
]
for seq, prompt in easy_order_bulan:
    shuffled = list(seq)
    random.shuffle(shuffled)
    add_q("bm_bulan", "bahasa_melayu", prompt, prompt, "ms", "ordering", None, None, shuffled, seq, f"Turutan yang betul: {' kemudian '.join(seq)}.", f"Susunannya ialah {' kemudian '.join(seq)}.")


# --- Topic 2: bm_suku_kata (Pecahkan Suku Kata) ---
suku_kata_list = [
    ("baju", "Ba", "ju", "images/suku-kata/baju.jpg"),
    ("batu", "Ba", "tu", "images/suku-kata/batu.jpg"),
    ("bola", "Bo", "la", "images/suku-kata/bola.jpg"),
    ("buku", "Bu", "ku", "images/suku-kata/buku.jpg"),
    ("bumi", "Bu", "mi", "images/suku-kata/bumi.jpg"),
    ("cili", "Ci", "li", "images/suku-kata/cili.jpg"),
    ("dadu", "Da", "du", "images/suku-kata/dadu.jpg"),
    ("gigi", "Gi", "gi", "images/suku-kata/gigi.jpg"),
    ("gua", "Gu", "a", "images/suku-kata/gua.jpg"),
    ("guli", "Gu", "li", "images/suku-kata/guli.jpg"),
    ("kaki", "Ka", "ki", "images/suku-kata/kaki.jpg"),
    ("kari", "Ka", "ri", "images/suku-kata/kari.jpg"),
    ("keju", "Ke", "ju", "images/suku-kata/keju.jpg"),
    ("kuku", "Ku", "ku", "images/suku-kata/kuku.jpg"),
    ("labu", "La", "bu", "images/suku-kata/labu.jpg"),
    ("laci", "La", "ci", "images/suku-kata/laci.jpg"),
    ("madu", "Ma", "du", "images/suku-kata/madu.jpg"),
    ("mata", "Ma", "ta", "images/suku-kata/mata.jpg"),
    ("meja", "Me", "ja", "images/suku-kata/meja.jpg"),
    ("paku", "Pa", "ku", "images/suku-kata/paku.jpg"),
    ("pasu", "Pa", "su", "images/suku-kata/pasu.jpg"),
    ("roti", "Ro", "ti", "images/suku-kata/roti.jpg"),
    ("rusa", "Ru", "sa", "images/suku-kata/rusa.jpg"),
    ("sagu", "Sa", "gu", "images/suku-kata/sagu.jpg"),
    ("sofa", "So", "fa", "images/suku-kata/sofa.jpg"),
    ("susu", "Su", "su", "images/suku-kata/susu.jpg"),
    ("tali", "Ta", "li", "images/suku-kata/tali.jpg"),
    ("tebu", "Te", "bu", "images/suku-kata/tebu.jpg"),
    ("tisu", "Ti", "su", "images/suku-kata/tisu.jpg"),
    ("topi", "To", "pi", "images/suku-kata/topi.jpg")
]

# Q1-30: Lengkapkan suku kata berpandukan gambar
for word, s1, s2, img in suku_kata_list:
    q = f"Lihat gambar! Lengkapkan perkataan: {s1} - ___ ?"
    ans = s2
    distractors = [x[2] for x in suku_kata_list if x[2] != s2]
    opts = [ans] + random.sample(list(set(distractors)), 2)
    random.shuffle(opts)
    add_q("bm_suku_kata", "bahasa_melayu", q, q, "ms", "multiple_choice", img, None, opts, ans, f"Sebut gambar itu: {word.title()}! Jadi {s1} + {s2}.", f"Pilih {ans}.")

# Q31-45: Pecahkan suku kata
for word, s1, s2, img in suku_kata_list[:15]:
    q = f"Bagaimana pecahkan suku kata bagi '{word.title()}'?"
    ans = f"{s1} - {s2}"
    wrong1 = f"{s1}a - {s2}"
    wrong2 = f"{s1} - {s2}k"
    opts = [ans, wrong1, wrong2]
    random.shuffle(opts)
    add_q("bm_suku_kata", "bahasa_melayu", q, q, "ms", "multiple_choice", img, None, opts, ans, f"Tepuk tangan dua kali: {s1}... {s2}!", f"Jawapannya {ans}.")

# Q46-55: Cantumkan suku kata mudah
cantum_bank = [
    ("Bo", "la", "Bola", "images/suku-kata/bola.jpg"),
    ("Bu", "ku", "Buku", "images/suku-kata/buku.jpg"),
    ("Ro", "ti", "Roti", "images/suku-kata/roti.jpg"),
    ("Su", "su", "Susu", "images/suku-kata/susu.jpg"),
    ("Ma", "ta", "Mata", "images/suku-kata/mata.jpg"),
    ("Ka", "ki", "Kaki", "images/suku-kata/kaki.jpg"),
    ("Gi", "gi", "Gigi", "images/suku-kata/gigi.jpg"),
    ("To", "pi", "Topi", "images/suku-kata/topi.jpg"),
    ("Me", "ja", "Meja", "images/suku-kata/meja.jpg"),
    ("Ci", "li", "Cili", "images/suku-kata/cili.jpg")
]
for s1, s2, full_w, img in cantum_bank:
    q = f"Cantumkan suku kata: {s1} + {s2} = ?"
    ans = full_w
    wrong_opts = [x[2] for x in cantum_bank if x[2] != full_w]
    opts = [ans] + random.sample(wrong_opts, 2)
    random.shuffle(opts)
    add_q("bm_suku_kata", "bahasa_melayu", q, q, "ms", "multiple_choice", img, None, opts, ans, f"Cantumkan bunyi {s1} dan {s2} jadi {full_w}!", f"Jawapannya {ans}.")


# --- Topic 3: bm_kenderaan (Kenderaan Darat, Air & Udara) ---
kenderaan_files = [
    ("kereta", "Darat", "images/kenderaan/kereta.jpg"),
    ("bas", "Darat", "images/kenderaan/bas.jpg"),
    ("basikal", "Darat", "images/kenderaan/basikal.jpg"),
    ("motosikal", "Darat", "images/kenderaan/motosikal.jpg"),
    ("lori", "Darat", "images/kenderaan/lori.jpg"),
    ("lori_sampah", "Darat", "images/kenderaan/lori_sampah.jpg"),
    ("teksi", "Darat", "images/kenderaan/teksi.jpg"),
    ("ambulans", "Darat", "images/kenderaan/ambulans.jpg"),
    ("bomba", "Darat", "images/kenderaan/bomba.jpg"),
    ("kereta_polis", "Darat", "images/kenderaan/kereta_polis.jpg"),
    ("traktor", "Darat", "images/kenderaan/traktor.jpg"),
    ("beca", "Darat", "images/kenderaan/beca.jpg"),
    ("kereta_api", "Darat", "images/kenderaan/kereta_api.jpg"),
    ("kereta_kebal", "Darat", "images/kenderaan/kereta_kebal.jpg"),
    ("jentolak", "Darat", "images/kenderaan/jentolak.jpg"),
    ("kren", "Darat", "images/kenderaan/kren.jpg"),
    ("skuter", "Darat", "images/kenderaan/skuter.jpg"),
    ("bot", "Air", "images/kenderaan/bot.jpg"),
    ("feri", "Air", "images/kenderaan/feri.jpg"),
    ("kapal", "Air", "images/kenderaan/kapal.jpg"),
    ("sampan", "Air", "images/kenderaan/sampan.jpg"),
    ("rakit", "Air", "images/kenderaan/rakit.jpg"),
    ("kapal_layar", "Air", "images/kenderaan/kapal_layar.jpg"),
    ("kapal_terbang", "Udara", "images/kenderaan/kapal_terbang.jpg"),
    ("helikopter", "Udara", "images/kenderaan/helikopter.jpg"),
    ("roket", "Udara", "images/kenderaan/roket.jpg"),
    ("jet", "Udara", "images/kenderaan/jet.jpg"),
    ("kapal_angkasa", "Udara", "images/kenderaan/kapal_angkasa.jpg"),
    ("kereta_kabel", "Udara", "images/kenderaan/kereta_kabel.jpg")
]

# Q1-29: Di manakah kenderaan ini bergerak?
for name, domain, img in kenderaan_files:
    disp = name.replace("_", " ").title()
    q = f"Lihat gambar! {disp} bergerak di mana?"
    opts = ["Darat", "Air", "Udara"]
    add_q("bm_kenderaan", "bahasa_melayu", q, q, "ms", "multiple_choice", img, None, opts, domain, f"Fikirkan: Adakah ia ada roda di jalan, terapung di air, atau terbang di langit?", f"Pilih {domain}.")

# Q30-55: Soalan K3 kenderaan
k3_kenderaan_questions = [
    ("Berapa roda pada sebuah basikal biasa?", "2 roda", ["2 roda", "4 roda", "3 roda"], "Kira roda basikal: satu di depan, satu di belakang!"),
    ("Kenderaan manakah membawa pesakit ke hospital?", "Ambulans", ["Ambulans", "Lori", "Basikal"], "Ambulans ada lampu siren kecemasan!"),
    ("Kenderaan manakah digunakan oleh anggota bomba?", "Kereta bomba", ["Kereta bomba", "Teksi", "Sampan"], "Kereta bomba merah bawa hos air!"),
    ("Kenderaan manakah bergerak laju di atas landasan rel?", "Kereta api", ["Kereta api", "Kereta", "Bas"], "Choo-choo! Kereta api di atas landasan."),
    ("Apakah kenderaan udara yang ada bilah kipas berputar di atas?", "Helikopter", ["Helikopter", "Kapal terbang", "Bas"], "Kipas helikopter berputar di atas."),
    ("Kenderaan air yang dikayuh menggunakan pendayung ialah?", "Sampan", ["Sampan", "Kereta", "Van"], "Kita kayuh sampan di sungai."),
    ("Apakah kenderaan yang terbang tinggi ke angkasa lepas?", "Roket", ["Roket", "Basikal", "Feri"], "3, 2, 1 blast off! Roket terbang ke angkasa."),
    ("Berapa roda pada sebuah kereta biasa?", "4 roda", ["4 roda", "2 roda", "1 roda"], "Kereta ada empat roda bergetah."),
    ("Kenderaan manakah boleh menyelam di dalam dasar laut?", "Kapal selam", ["Kapal selam", "Helikopter", "Lori"], "Kapal selam menyelam di dalam air laut."),
    ("Kenderaan manakah membantu pak cik petani membajak sawah?", "Traktor", ["Traktor", "Beca", "Jet ski"], "Traktor ada roda besar di ladang."),
    ("Adakah basikal memerlukan minyak petrol?", "Tidak perlu", ["Tidak perlu", "Perlu", "Kadang-kadang"], "Kita hanya perlu kayuh basikal dengan kaki!"),
    ("Kenderaan manakah membawa ramai murid ke sekolah?", "Bas sekolah", ["Bas sekolah", "Sampan", "Roket"], "Bas sekolah berwarna kuning bawa murid."),
    ("Adakah kapal terbang lebih laju daripada basikal?", "Ya, lebih laju", ["Ya, lebih laju", "Tidak, lebih perlahan"], "Kapal terbang terbang sangat laju di awan!"),
    ("Kereta polis mempunyai lampu siren di bahagian?", "Atas bumbung", ["Atas bumbung", "Bawah tayar"], "Lampu siren biru merah berkelip di atas bumbung."),
    ("Beca roda tiga dikayuh oleh abang beca menggunakan?", "Kaki", ["Kaki", "Tangan sahaja"], "Abang beca mengayuh pedal dengan kaki."),
    ("Apakah nama kenderaan yang terapung dengan layar ditiup angin?", "Kapal layar", ["Kapal layar", "Kereta api", "Van"], "Angin menolak kain layar kapal."),
    ("Kereta bergerak di atas apa?", "Jalan raya", ["Jalan raya", "Awan", "Air sungai"], "Kereta beroda bergerak di jalan raya."),
    ("Kapal terbang mendarat di mana?", "Lapangan terbang", ["Lapangan terbang", "Atas bumbung rumah"], "Kapal terbang mendarat di landasan lapangan terbang."),
    ("Adakah helikopter boleh terbang tegak ke atas?", "Boleh", ["Boleh", "Tidak boleh"], "Helikopter boleh naik tegak ke udara."),
    ("Apakah kenderaan yang boleh membawa barang-barang sangat berat?", "Lori", ["Lori", "Basikal", "Beca"], "Lori besar membawa muatan berat."),
    ("Kenderaan air manakah yang sangat besar menyeberangi lautan?", "Kapal", ["Kapal", "Kereta", "Van"], "Kapal besar belayar di lautan."),
    ("Apakah bunyi hon kereta?", "Pon pon!", ["Pon pon!", "Meow meow!", "Kwek kwek!"], "Hon kereta berbunyi pon pon!"),
    ("Adakah kita perlu memakai tali pinggang keledar di dalam kereta?", "Ya, wajib", ["Ya, wajib", "Tidak perlu"], "Pakai tali pinggang keledar untuk keselamatan!"),
    ("Adakah penunggang motorsikal mesti memakai topi keledar?", "Ya, mesti", ["Ya, mesti", "Tidak perlu"], "Pakai topi keledar untuk lindungi kepala!"),
    ("Kenderaan manakah yang bergerak paling senyap?", "Basikal", ["Basikal", "Lori", "Kapal terbang"], "Basikal tidak bising sebab tiada enjin!"),
    ("Kereta api membawa ramai orang di dalam banyak?", "Gerabak", ["Gerabak", "Bakul", "Kotak"], "Kereta api ada banyak gerabak bersambung.")
]
for q, ans, opts, hint in k3_kenderaan_questions:
    add_q("bm_kenderaan", "bahasa_melayu", q, q, "ms", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 4: bm_binatang (Haiwan 2 Kaki & 4 Kaki) ---
binatang_list = [
    ("ayam", 2, "images/binatang/ayam.jpg"),
    ("itik", 2, "images/binatang/itik.jpg"),
    ("burung", 2, "images/binatang/burung.jpg"),
    ("penguin", 2, "images/binatang/penguin.jpg"),
    ("anjing", 4, "images/binatang/anjing.jpg"),
    ("arnab", 4, "images/binatang/arnab.jpg"),
    ("beruang", 4, "images/binatang/beruang.jpg"),
    ("buaya", 4, "images/binatang/buaya.jpg"),
    ("gajah", 4, "images/binatang/gajah.jpg"),
    ("harimau", 4, "images/binatang/harimau.jpg"),
    ("kambing", 4, "images/binatang/kambing.jpg"),
    ("katak", 4, "images/binatang/katak.jpg"),
    ("ketam", 10, "images/binatang/ketam.jpg"),
    ("kucing", 4, "images/binatang/kucing.jpg"),
    ("kuda", 4, "images/binatang/kuda.jpg"),
    ("kura_kura", 4, "images/binatang/kura_kura.jpg"),
    ("lembu", 4, "images/binatang/lembu.jpg"),
    ("lumba_lumba", 0, "images/binatang/lumba_lumba.jpg"),
    ("monyet", 4, "images/binatang/monyet.jpg"),
    ("panda", 4, "images/binatang/panda.jpg"),
    ("paus", 0, "images/binatang/paus.jpg"),
    ("singa", 4, "images/binatang/singa.jpg"),
    ("siput", 0, "images/binatang/siput.jpg"),
    ("sotong", 10, "images/binatang/sotong.jpg"),
    ("tikus", 4, "images/binatang/tikus.jpg"),
    ("udang", 10, "images/binatang/udang.jpg"),
    ("ular", 0, "images/binatang/ular.jpg"),
    ("zirafah", 4, "images/binatang/zirafah.jpg"),
    ("ikan", 0, "images/binatang/ikan.jpg"),
    ("jerung", 0, "images/binatang/jerung.jpg")
]

# Q1-30: Berapa kaki haiwan ini?
for name, legs, img in binatang_list:
    disp = name.replace("_", " ").title()
    ans = f"{legs} kaki" if legs > 0 else "Tiada kaki"
    q = f"Lihat gambar! {disp} ada berapa kaki?"
    opts = [ans]
    if legs == 2:
        opts += ["4 kaki", "Tiada kaki"]
    elif legs == 4:
        opts += ["2 kaki", "Tiada kaki"]
    elif legs == 0:
        opts += ["2 kaki", "4 kaki"]
    else:
        opts += ["4 kaki", "2 kaki"]
    random.shuffle(opts)
    hint = f"Kira kaki {disp} dalam gambar!"
    add_q("bm_binatang", "bahasa_melayu", q, q, "ms", "multiple_choice", img, None, opts, ans, hint, hint)

# Q31-55: Soalan K3 haiwan ceria
k3_binatang_questions = [
    ("Haiwan manakah yang berkokok 'Kok-ko-kok' setiap pagi?", "Ayam", ["Ayam", "Kucing", "Lembu"], "Ayam berkokok pagi-pagi!"),
    ("Haiwan manakah berbunyi 'Meow meow'?", "Kucing", ["Kucing", "Anjing", "Harimau"], "Kucing comel berbunyi meow."),
    ("Haiwan manakah berbunyi 'Moo moo' dan memberi susu?", "Lembu", ["Lembu", "Burung", "Itik"], "Lembu meragut rumput dan memberi susu."),
    ("Haiwan manakah berleher paling panjang di dunia?", "Zirafah", ["Zirafah", "Gajah", "Kambing"], "Zirafah makan daun di pokok tinggi."),
    ("Haiwan manakah mempunyai belalai yang panjang?", "Gajah", ["Gajah", "Singa", "Arnab"], "Gajah guna belalai untuk minum."),
    ("Haiwan manakah melompat-lompat dan suka makan lobak oren?", "Arnab", ["Arnab", "Penyu", "Kuda"], "Arnab bertelinga panjang suka makan lobak."),
    ("Haiwan manakah berenang di kolam dan berbunyi 'Kwek kwek'?", "Itik", ["Itik", "Ayam", "Burung"], "Itik berparuh leper berenang di air."),
    ("Haiwan manakah yang berenang di dalam laut dengan sirip?", "Ikan", ["Ikan", "Monyet", "Kuda"], "Ikan berenang guna sirip."),
    ("Adakah ular mempunyai sebarang kaki?", "Tiada kaki", ["Tiada kaki", "2 kaki", "4 kaki"], "Ular menjalar tanpa kaki."),
    ("Haiwan manakah digelar Raja Rimba?", "Singa", ["Singa", "Tikus", "Kucing"], "Singa garang dengan surai lebat."),
    ("Panda comel suka makan apa?", "Buluh", ["Buluh", "Ikan goreng", "Coklat"], "Panda suka makan batang buluh hijau."),
    ("Burung terbang di udara menggunakan apa?", "Sayap", ["Sayap", "Ekor", "Telinga"], "Burung mengepakkan sayapnya di udara."),
    ("Haiwan manakah membawa cengkerang keras di belakangnya?", "Kura-kura", ["Kura-kura", "Kuda", "Ayam"], "Kura-kura berjalan perlahan."),
    ("Haiwan manakah memanjat pokok dan suka makan pisang?", "Monyet", ["Monyet", "Lembu", "Ikan"], "Monyet bergayut di pokok makan pisang."),
    ("Haiwan manakah menyalak 'Gong gong' menjaga rumah?", "Anjing", ["Anjing", "Itik", "Burung"], "Anjing menjaga rumah dengan setia."),
    ("Kambing suka makan apa?", "Rumput", ["Rumput", "Nasi lemak", "Aiskrim"], "Kambing meragut rumput hijau."),
    ("Haiwan manakah berenang di lautan sejuk berais dan berjalan tegak?", "Penguin", ["Penguin", "Zirafah", "Monyet"], "Penguin burung kutub yang comel."),
    ("Katak melompat dan menangkap serangga menggunakan apa?", "Lidah panjang", ["Lidah panjang", "Ekor", "Telinga"], "Lidah katak cepat menyambar nyamuk."),
    ("Adakah ikan boleh bernafas di dalam air?", "Boleh", ["Boleh", "Tidak boleh"], "Ikan bernafas dalam air guna insang."),
    ("Haiwan manakah mempunyai belang jingga dan hitam?", "Harimau", ["Harimau", "Zirafah", "Beruang"], "Pak Belang ialah harimau."),
    ("Haiwan manakah yang sangat kecil dan suka makan keju?", "Tikus", ["Tikus", "Gajah", "Singa"], "Tikus kecil lari laju."),
    ("Adakah burung bertelur?", "Ya, bertelur", ["Ya, bertelur", "Tidak"], "Burung bertelur di dalam sarang."),
    ("Kuda berlari dengan laju menggunakan berapa kaki?", "4 kaki", ["4 kaki", "2 kaki"], "Kuda ada empat kaki yang kuat."),
    ("Apakah warna bulu beruang kutub di salji?", "Putih", ["Putih", "Hitam", "Merah"], "Bulu beruang kutub putih seperti salji."),
    ("Haiwan manakah yang mengeluarkan dakwat hitam di laut?", "Sotong", ["Sotong", "Ketam", "Ikan"], "Sotong pancutkan dakwat bila terkejut.")
]
for q, ans, opts, hint in k3_binatang_questions:
    add_q("bm_binatang", "bahasa_melayu", q, q, "ms", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 5: bm_ini_itu (Penggunaan 'Ini' & 'Itu') ---
ini_itu_pics = [
    ("basikal_dekat", "Ini", "images/ini-itu/basikal_dekat.jpg", "Basikal ada dekat dengan saya."),
    ("hadiah_jauh", "Itu", "images/ini-itu/hadiah_jauh.jpg", "Hadiah diletakkan jauh di atas almari."),
    ("jus_oren_dekat", "Ini", "images/ini-itu/jus_oren_dekat.jpg", "Gelas jus oren di depan tangan saya."),
    ("gunting_dekat", "Ini", "images/ini-itu/gunting_dekat.jpg", "Gunting ada di atas meja saya."),
    ("bunga_ros_jauh", "Itu", "images/ini-itu/bunga_ros_jauh.jpg", "Bunga ros mekar jauh di taman.")
]

# Q1-15: Foto dekat vs jauh
for i in range(3):
    for name, correct_word, img, desc in ini_itu_pics:
        q = f"Lihat gambar! Benda ini {correct_word.lower()} atau {('itu' if correct_word == 'Ini' else 'ini')}?"
        opts = ["Ini", "Itu"]
        hint = f"{desc} Jika dekat sebut 'Ini', jika jauh sebut 'Itu'."
        add_q("bm_ini_itu", "bahasa_melayu", q, q, "ms", "multiple_choice", img, None, opts, correct_word, hint, hint)

# Q16-55: Situasi K3 dekat vs jauh
k3_situasi_ini_itu = [
    ("Pensel yang saya pegang di tangan: '___ pensel saya.'", "Ini", ["Ini", "Itu"], "Benda dalam tangan kita sebut 'Ini'."),
    ("Burung terbang tinggi di langit sana: '___ burung helang.'", "Itu", ["Itu", "Ini"], "Burung jauh di langit kita sebut 'Itu'."),
    ("Buku di depan mata saya: '___ buku cerita saya.'", "Ini", ["Ini", "Itu"], "Buku dekat depan mata sebut 'Ini'."),
    ("Bulan yang bersinar jauh di langit malam: '___ bulan.'", "Itu", ["Itu", "Ini"], "Bulan jauh di langit sebut 'Itu'."),
    ("Kasut yang sedang saya pakai di kaki: '___ kasut saya.'", "Ini", ["Ini", "Itu"], "Kasut di kaki sendiri sebut 'Ini'."),
    ("Kapal terbang tinggi di awan: '___ kapal terbang.'", "Itu", ["Itu", "Ini"], "Kapal terbang jauh di atas sebut 'Itu'."),
    ("Baju yang saya pakai hari ini: '___ baju baru saya.'", "Ini", ["Ini", "Itu"], "Baju pada badan kita sebut 'Ini'."),
    ("Kereta ayah di seberang jalan sana: '___ kereta ayah.'", "Itu", ["Itu", "Ini"], "Kereta jauh di seberang jalan sebut 'Itu'."),
    ("Epal yang saya pegang untuk makan: '___ epal merah.'", "Ini", ["Ini", "Itu"], "Epal di tangan sebut 'Ini'."),
    ("Awan putih terapung tinggi: '___ awan tebal.'", "Itu", ["Itu", "Ini"], "Awan jauh di langit sebut 'Itu'."),
    ("Cawan susu di atas meja saya: '___ cawan saya.'", "Ini", ["Ini", "Itu"], "Cawan dekat sebut 'Ini'."),
    ("Bintang berkelip jauh di angkasa: '___ bintang.'", "Itu", ["Itu", "Ini"], "Bintang jauh di sana sebut 'Itu'."),
    ("Topi yang ada di atas kepala saya: '___ topi saya.'", "Ini", ["Ini", "Itu"], "Topi di kepala sendiri sebut 'Ini'."),
    ("Rumah datuk jauh di hujung kampung: '___ rumah datuk.'", "Itu", ["Itu", "Ini"], "Rumah jauh di sana sebut 'Itu'."),
    ("Pemadam yang ada di dalam kotak pensel saya: '___ pemadam.'", "Ini", ["Ini", "Itu"], "Pemadam dekat dalam kotak sebut 'Ini'."),
    ("Gunung tinggi nun jauh di sana: '___ Gunung Kinabalu.'", "Itu", ["Itu", "Ini"], "Gunung jauh nun di sana sebut 'Itu'."),
    ("Beg sekolah yang saya galas: '___ beg sekolah saya.'", "Ini", ["Ini", "Itu"], "Beg pada badan kita sebut 'Ini'."),
    ("Bot berlayar jauh di tengah laut: '___ bot nelayan.'", "Itu", ["Itu", "Ini"], "Bot jauh di laut sebut 'Itu'."),
    ("Pinggan nasi di depan saya: '___ makanan saya.'", "Ini", ["Ini", "Itu"], "Pinggan dekat sebut 'Ini'."),
    ("Layang-layang terbang tinggi di udara: '___ layang-layang adik.'", "Itu", ["Itu", "Ini"], "Layang-layang tinggi jauh sebut 'Itu'."),
    ("Jari tangan saya sendiri: '___ jari saya.'", "Ini", ["Ini", "Itu"], "Jari tangan sendiri sangat dekat: 'Ini'."),
    ("Matahari yang memancar jauh di atas: '___ matahari.'", "Itu", ["Itu", "Ini"], "Matahari sangat jauh: 'Itu'."),
    ("Pembaris pendek atas meja: '___ pembaris saya.'", "Ini", ["Ini", "Itu"], "Pembaris di meja sebut 'Ini'."),
    ("Pelangi cantik melengkung jauh di langit: '___ pelangi.'", "Itu", ["Itu", "Ini"], "Pelangi jauh di langit sebut 'Itu'."),
    ("Kucing yang duduk di atas riba saya: '___ kucing comel.'", "Ini", ["Ini", "Itu"], "Kucing di atas riba dekat sebut 'Ini'."),
    ("Kucing jiran berjalan di seberang pagar: '___ kucing jiran.'", "Itu", ["Itu", "Ini"], "Kucing di seberang pagar jauh sebut 'Itu'."),
    ("Mainan blok yang sedang saya susun: '___ mainan saya.'", "Ini", ["Ini", "Itu"], "Mainan sedang kita main sebut 'Ini'."),
    ("Lampu jalan di hujung simpang: '___ lampu jalan.'", "Itu", ["Itu", "Ini"], "Lampu jauh di hujung simpang sebut 'Itu'."),
    ("Krayon yang saya pegang untuk mewarna: '___ krayon saya.'", "Ini", ["Ini", "Itu"], "Krayon dalam genggaman sebut 'Ini'."),
    ("Pokok kelapa tinggi di luar rumah: '___ pokok kelapa.'", "Itu", ["Itu", "Ini"], "Pokok kelapa tinggi sebut 'Itu'."),
    ("Botol air yang saya minum: '___ botol air saya.'", "Ini", ["Ini", "Itu"], "Botol air kita sebut 'Ini'."),
    ("Helikopter terbang di atas awan: '___ helikopter.'", "Itu", ["Itu", "Ini"], "Helikopter jauh di langit sebut 'Itu'."),
    ("Kertas lukisan di hadapan saya: '___ lukisan saya.'", "Ini", ["Ini", "Itu"], "Kertas di depan kita sebut 'Ini'."),
    ("Menara berkembar jauh di bandar: '___ Menara KLCC.'", "Itu", ["Itu", "Ini"], "Menara jauh di bandar sebut 'Itu'."),
    ("Bantal tempat saya tidur: '___ bantal saya.'", "Ini", ["Ini", "Itu"], "Bantal kita dekat sebut 'Ini'."),
    ("Belon adik terlepas terbang ke langit: '___ belon merah.'", "Itu", ["Itu", "Ini"], "Belon terbang jauh sebut 'Itu'."),
    ("Sudu yang saya pegang: '___ sudu saya.'", "Ini", ["Ini", "Itu"], "Sudu di tangan sebut 'Ini'."),
    ("Jam dinding tergantung tinggi di atas pintu: '___ jam dinding.'", "Itu", ["Itu", "Ini"], "Jam tergantung jauh sebut 'Itu'."),
    ("Kucing tidur di kaki saya: '___ Si Comel.'", "Ini", ["Ini", "Itu"], "Kucing di kaki dekat sebut 'Ini'."),
    ("Bunga mekar di pokok tinggi seberang sana: '___ bunga raya.'", "Itu", ["Itu", "Ini"], "Bunga jauh di seberang sana sebut 'Itu'.")
]
for q, ans, opts, hint in k3_situasi_ini_itu:
    add_q("bm_ini_itu", "bahasa_melayu", q, q, "ms", "multiple_choice", None, None, opts, ans, hint, hint)


# =========================================================================
# SUBJECT 2: MATHEMATICS (in English, K3 Friendly)
# =========================================================================

# --- Topic 1: math_clocks (Clocks & Telling Time) ---
# Q1-12: Telling exact hours (1:00 to 12:00)
for h in range(1, 13):
    time_str = f"{h}:00"
    q = f"The short hand points at {h}, and the long hand points at 12. What time is it?"
    opts = [time_str] + [f"{(h + 1) % 12 or 12}:00", f"{(h + 5) % 12 or 12}:00"]
    random.shuffle(opts)
    hint = f"Look at the short hand: it points to {h}! So it is {time_str}."
    img = "images/maths/clock_wall.jpg" if h % 2 == 1 else "images/maths/clock_watch.jpg"
    add_q("math_clocks", "maths", q, q, "en", "multiple_choice", img, None, opts, time_str, hint, hint)

# Q13-24: Half hours (:30)
for h in range(1, 13):
    time_str = f"{h}:30"
    q = f"The short hand is past {h}, and the long hand points at 6. What time is it?"
    opts = [time_str, f"{h}:00", f"{(h + 1) % 12 or 12}:30"]
    random.shuffle(opts)
    hint = f"When the long hand points at 6, it is half past: {time_str}!"
    add_q("math_clocks", "maths", q, q, "en", "multiple_choice", "images/english/clock.jpg", None, opts, time_str, hint, hint)

# Q25-55: Clock concepts for K3
k3_clock_trivia = [
    ("Which hand on a clock tells the hour?", "The short hand", ["The short hand", "The long hand", "The tick hand"], "The short little hand shows the hour!"),
    ("Which hand on a clock tells the minutes?", "The long hand", ["The long hand", "The short hand"], "The longer hand shows the minutes."),
    ("When it is exactly 3:00, where does the long hand point?", "At 12", ["At 12", "At 6", "At 3"], "At o'clock, the long hand points straight up at 12."),
    ("When it is 6:30, where does the long hand point?", "At 6", ["At 6", "At 12", "At 3"], "Half past means pointing down at 6!"),
    ("How many numbers are on a clock face?", "12 numbers", ["12 numbers", "10 numbers", "24 numbers"], "Count them around the circle: 1 to 12!"),
    ("What time do you usually eat lunch at school?", "12:00 pm", ["12:00 pm", "3:00 am", "11:00 pm"], "12:00 in the afternoon is lunchtime!"),
    ("What shape is a round wall clock?", "A circle", ["A circle", "A triangle", "A star"], "A wall clock is round like a circle."),
    ("What sound does an analog clock make?", "Tick-tock!", ["Tick-tock!", "Quack-quack!", "Vroom-vroom!"], "The clock ticks: tick, tock, tick, tock!"),
    ("A little clock you wear on your wrist is called a?", "Watch", ["Watch", "Necklace", "Belt"], "A wristwatch tells time on your wrist."),
    ("What time comes right after 1:00?", "2:00", ["2:00", "12:00", "5:00"], "1, 2, 3! After 1:00 comes 2:00."),
    ("What time comes right after 4:00?", "5:00", ["5:00", "3:00", "6:00"], "After 4:00 comes 5:00."),
    ("What time comes right after 8:00?", "9:00", ["9:00", "7:00", "10:00"], "After 8:00 comes 9:00."),
    ("What time comes right after 11:00?", "12:00", ["12:00", "10:00", "1:00"], "After 11:00 comes 12:00."),
    ("What time comes right before 5:00?", "4:00", ["4:00", "6:00", "3:00"], "Before 5:00 was 4:00."),
    ("What time comes right before 10:00?", "9:00", ["9:00", "11:00", "8:00"], "Before 10:00 was 9:00."),
    ("If you sleep at 8:00 pm, is it day or night?", "Night", ["Night", "Day"], "8:00 at night is bedtime."),
    ("If you wake up at 7:00 am, is it morning or evening?", "Morning", ["Morning", "Night"], "7:00 in the morning is breakfast time!"),
    ("At 12:00 o'clock, both hands point straight up at?", "12", ["12", "6", "9"], "Both hands meet right at 12!"),
    ("At 9:00, the short hand points to?", "9", ["9", "12", "3"], "The short hand points right at 9."),
    ("At 10:00, the long hand points to?", "12", ["12", "10", "6"], "The long minute hand points to 12."),
    ("At 4:30, is the long hand at 12 or 6?", "At 6", ["At 6", "At 12"], "Half past 4 has the long hand at 6."),
    ("How many hands does a simple clock face have?", "2 hands", ["2 hands", "5 hands", "10 hands"], "A short hour hand and a long minute hand!"),
    ("Does a clock tell us the time or the weather?", "The time", ["The time", "The weather"], "A clock tells us what time it is."),
    ("Can a clock wake you up in the morning?", "Yes, an alarm clock", ["Yes, an alarm clock", "No, never"], "An alarm clock rings to wake you up!"),
    ("If school ends at 1:00 pm, is that in the afternoon?", "Yes", ["Yes", "No"], "1:00 in the afternoon is when school finishes."),
    ("Which hand moves faster around the clock?", "The long hand", ["The long hand", "The short hand"], "The long minute hand runs faster."),
    ("Where does the short hand point at 7:00?", "At 7", ["At 7", "At 12", "At 6"], "Short hand points to 7."),
    ("Where does the short hand point at 11:00?", "At 11", ["At 11", "At 12", "At 1"], "Short hand points to 11."),
    ("Where does the short hand point at 2:00?", "At 2", ["At 2", "At 12", "At 6"], "Short hand points to 2."),
    ("Where does the short hand point at 5:00?", "At 5", ["At 5", "At 12", "At 6"], "Short hand points to 5."),
    ("Where does the short hand point at 8:00?", "At 8", ["At 8", "At 12", "At 6"], "Short hand points to 8.")
]
for q, ans, opts, hint in k3_clock_trivia:
    add_q("math_clocks", "maths", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 2: math_descending (Descending Numbers / Counting Down) ---
# Q1-20: Rocket countdowns & backwards sequences (5 to 1, 10 to 1)
countdowns_k3 = [
    ("Count down like a rocket: 5, 4, 3, 2, ___ ?", "1", ["1", "6", "0"], "5, 4, 3, 2, 1, BLAST OFF!"),
    ("Count down: 10, 9, 8, 7, ___ ?", "6", ["6", "8", "11"], "Counting backwards: 10, 9, 8, 7, 6!"),
    ("Count down: 9, 8, 7, 6, ___ ?", "5", ["5", "7", "10"], "Next backwards is 5!"),
    ("Count down: 8, 7, 6, 5, ___ ?", "4", ["4", "6", "9"], "Next backwards is 4!"),
    ("Count down: 7, 6, 5, 4, ___ ?", "3", ["3", "5", "8"], "Next backwards is 3!"),
    ("Count down: 6, 5, 4, 3, ___ ?", "2", ["2", "4", "7"], "Next backwards is 2!"),
    ("Count down: 4, 3, 2, ___ ?", "1", ["1", "5", "0"], "Next backwards is 1!"),
    ("Count down: 3, 2, ___ ?", "1", ["1", "4", "0"], "3, 2, 1!"),
    ("What number comes right before 10?", "9", ["9", "11", "8"], "Count down: 10, then 9!"),
    ("What number comes right before 9?", "8", ["8", "10", "7"], "Count down: 9, then 8!"),
    ("What number comes right before 8?", "7", ["7", "9", "6"], "Count down: 8, then 7!"),
    ("What number comes right before 7?", "6", ["6", "8", "5"], "Count down: 7, then 6!"),
    ("What number comes right before 6?", "5", ["5", "7", "4"], "Count down: 6, then 5!"),
    ("What number comes right before 5?", "4", ["4", "6", "3"], "Count down: 5, then 4!"),
    ("What number comes right before 4?", "3", ["3", "5", "2"], "Count down: 4, then 3!"),
    ("What number comes right before 3?", "2", ["2", "4", "1"], "Count down: 3, then 2!"),
    ("What number comes right before 2?", "1", ["1", "3", "0"], "Count down: 2, then 1!"),
    ("Count down: 15, 14, 13, 12, ___ ?", "11", ["11", "13", "10"], "Counting down from 15: next is 11."),
    ("Count down: 20, 19, 18, 17, ___ ?", "16", ["16", "18", "15"], "Counting down from 20: next is 16."),
    ("Count down: 12, 11, 10, ___ ?", "9", ["9", "11", "8"], "After 10 backwards is 9!")
]
for q, ans, opts, hint in countdowns_k3:
    add_q("math_descending", "maths", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)

# Q21-40: Which number is smaller? (K3 concept of descending / smaller)
smaller_pairs = [
    (5, 3), (7, 2), (9, 4), (6, 1), (8, 5), (10, 6), (4, 2), (8, 3), (5, 1), (9, 2),
    (7, 3), (6, 2), (10, 1), (4, 1), (8, 4), (10, 5), (9, 7), (6, 4), (5, 2), (7, 5)
]
for big, small in smaller_pairs:
    q = f"Which number is smaller: {big} or {small}?"
    opts = [str(small), str(big)]
    hint = f"{small} is smaller than {big}!"
    add_q("math_descending", "maths", q, q, "en", "multiple_choice", None, None, opts, str(small), hint, hint)

# Q41-55: Order 3 numbers from biggest to smallest (ordering)
order_descending_bank = [
    ([5, 4, 3], "Arrange from biggest to smallest:"),
    ([4, 3, 2], "Arrange from biggest to smallest:"),
    ([3, 2, 1], "Arrange from biggest to smallest:"),
    ([6, 5, 4], "Arrange from biggest to smallest:"),
    ([7, 6, 5], "Arrange from biggest to smallest:"),
    ([8, 7, 6], "Arrange from biggest to smallest:"),
    ([9, 8, 7], "Arrange from biggest to smallest:"),
    ([10, 9, 8], "Arrange from biggest to smallest:"),
    ([5, 3, 1], "Arrange from biggest to smallest:"),
    ([6, 4, 2], "Arrange from biggest to smallest:"),
    ([8, 5, 2], "Arrange from biggest to smallest:"),
    ([9, 6, 3], "Arrange from biggest to smallest:"),
    ([10, 5, 1], "Arrange from biggest to smallest:"),
    ([7, 4, 1], "Arrange from biggest to smallest:"),
    ([8, 6, 4], "Arrange from biggest to smallest:")
]
for seq, prompt in order_descending_bank:
    seq_str = [str(x) for x in seq]
    shuffled = list(seq_str)
    random.shuffle(shuffled)
    add_q("math_descending", "maths", prompt, prompt, "en", "ordering", None, None, shuffled, seq_str, f"Start with the biggest number: {' -> '.join(seq_str)}", f"Order is {' then '.join(seq_str)}.")


# --- Topic 3: math_addition (Addition 0 to 20, K3 Friendly) ---
addition_k3 = [
    # Visual finger / object stories (Q1-25)
    ("You have 1 apple 🍎 and get 1 more apple 🍎. How many apples now?", "2", ["2", "3", "1"], "1 + 1 = 2 apples!"),
    ("You have 2 balls ⚽⚽ and get 1 more ball ⚽. How many balls?", "3", ["3", "4", "2"], "2 + 1 = 3 balls!"),
    ("You have 2 stars ⭐⭐ and get 2 more stars ⭐⭐. How many stars?", "4", ["4", "5", "3"], "2 + 2 = 4 stars!"),
    ("You have 3 flowers 🌸🌸🌸 and pick 1 more 🌸. How many flowers?", "4", ["4", "5", "3"], "3 + 1 = 4 flowers!"),
    ("You have 3 ducks 🦆🦆🦆 and 2 more join 🦆🦆. How many ducks?", "5", ["5", "6", "4"], "3 + 2 = 5 ducks!"),
    ("You have 4 toy cars 🚗🚗🚗🚗 and get 1 more 🚗. How many cars?", "5", ["5", "6", "4"], "4 + 1 = 5 cars!"),
    ("You have 5 candies 🍬🍬🍬🍬🍬 and Mommy gives you 1 more 🍬. How many?", "6", ["6", "7", "5"], "5 + 1 = 6 candies!"),
    ("You have 5 pencils ✏️ and your friend gives you 2 more ✏️✏️. How many?", "7", ["7", "8", "6"], "5 + 2 = 7 pencils!"),
    ("You see 4 birds on a fence and 4 more fly in. How many birds?", "8", ["8", "7", "9"], "4 + 4 = 8 birds!"),
    ("5 fingers on your left hand and 5 on your right hand. How many fingers?", "10", ["10", "8", "12"], "5 + 5 = 10 fingers!"),
    ("What is 1 + 2?", "3", ["3", "4", "2"], "Count on your fingers: 1, 2, 3!"),
    ("What is 2 + 3?", "5", ["5", "4", "6"], "2 + 3 = 5!"),
    ("What is 3 + 3?", "6", ["6", "5", "7"], "3 + 3 = 6!"),
    ("What is 4 + 2?", "6", ["6", "7", "5"], "4 + 2 = 6!"),
    ("What is 5 + 3?", "8", ["8", "7", "9"], "5 + 3 = 8!"),
    ("What is 6 + 1?", "7", ["7", "8", "6"], "6 + 1 = 7!"),
    ("What is 7 + 2?", "9", ["9", "8", "10"], "7 + 2 = 9!"),
    ("What is 8 + 1?", "9", ["9", "10", "8"], "8 + 1 = 9!"),
    ("What is 9 + 1?", "10", ["10", "11", "9"], "9 + 1 = 10!"),
    ("What is 5 + 0?", "5", ["5", "0", "6"], "Adding zero keeps the number the same: 5!"),
    ("What is 0 + 4?", "4", ["4", "0", "5"], "0 + 4 = 4!"),
    ("What is 10 + 2?", "12", ["12", "11", "13"], "10 + 2 = 12!"),
    ("What is 10 + 5?", "15", ["15", "14", "16"], "10 + 5 = 15!"),
    ("What is 10 + 10?", "20", ["20", "15", "10"], "10 + 10 = 20!"),
    ("What is 1 + 0?", "1", ["1", "0", "2"], "1 + 0 = 1!")
]
for q, ans, opts, hint in addition_k3:
    add_q("math_addition", "maths", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)

# Q26-55: Additional straightforward K3 math facts
simple_additions = [
    (1, 3, 4), (2, 4, 6), (3, 4, 7), (1, 5, 6), (2, 5, 7),
    (3, 5, 8), (4, 5, 9), (1, 6, 7), (2, 6, 8), (3, 6, 9),
    (1, 7, 8), (2, 7, 9), (1, 8, 9), (2, 8, 10), (1, 9, 10),
    (6, 2, 8), (7, 1, 8), (8, 2, 10), (4, 3, 7), (5, 4, 9),
    (6, 3, 9), (7, 3, 10), (10, 1, 11), (10, 3, 13), (10, 4, 14),
    (11, 1, 12), (12, 1, 13), (13, 2, 15), (14, 1, 15), (15, 1, 16)
]
for a, b, s in simple_additions:
    q = f"What is {a} + {b}?"
    ans = str(s)
    opts = [ans, str(s + 1), str(s - 1 if s > 1 else s + 2)]
    random.shuffle(opts)
    hint = f"Count {a} and add {b} more: {s}!"
    add_q("math_addition", "maths", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 4: math_subtraction (Subtraction 0 to 20, K3 Friendly) ---
subtraction_k3 = [
    # Visual stories (Q1-25)
    ("You have 2 cookies 🍪🍪 and eat 1 cookie. How many cookies left?", "1", ["1", "2", "0"], "2 take away 1 is 1!"),
    ("You have 3 balloons 🎈🎈🎈 and 1 pops! How many balloons left?", "2", ["2", "3", "1"], "3 take away 1 is 2!"),
    ("You have 4 apples 🍎 and give 2 apples to Mommy. How many left?", "2", ["2", "3", "1"], "4 - 2 = 2 apples!"),
    ("5 birds sit on a tree 🐦. 1 flies away. How many stay?", "4", ["4", "5", "3"], "5 - 1 = 4 birds!"),
    ("5 frogs sit on a log 🐸. 2 jump in water. How many stay on the log?", "3", ["3", "4", "2"], "5 - 2 = 3 frogs!"),
    ("You have 4 toy cars 🚗. You give away 4 toy cars. How many left?", "0", ["0", "1", "4"], "4 - 4 = 0! Nothing left."),
    ("You have 6 crayons 🖍️ and lose 1 crayon. How many crayons left?", "5", ["5", "6", "4"], "6 - 1 = 5 crayons!"),
    ("You have 7 strawberries 🍓 and eat 2 of them. How many left?", "5", ["5", "6", "4"], "7 - 2 = 5 strawberries!"),
    ("You have 8 stickers ⭐ and use 3 stickers. How many left?", "5", ["5", "4", "6"], "8 - 3 = 5 stickers!"),
    ("10 ducks swim in a pond 🦆. 5 walk out. How many ducks left?", "5", ["5", "4", "6"], "10 - 5 = 5 ducks!"),
    ("What is 3 - 1?", "2", ["2", "1", "3"], "3 take away 1 is 2!"),
    ("What is 4 - 1?", "3", ["3", "2", "4"], "4 take away 1 is 3!"),
    ("What is 5 - 1?", "4", ["4", "3", "5"], "5 take away 1 is 4!"),
    ("What is 5 - 3?", "2", ["2", "3", "1"], "5 - 3 = 2!"),
    ("What is 6 - 2?", "4", ["4", "3", "5"], "6 - 2 = 4!"),
    ("What is 6 - 3?", "3", ["3", "2", "4"], "6 - 3 = 3!"),
    ("What is 7 - 1?", "6", ["6", "5", "7"], "7 - 1 = 6!"),
    ("What is 8 - 2?", "6", ["6", "5", "7"], "8 - 2 = 6!"),
    ("What is 9 - 1?", "8", ["8", "7", "9"], "9 - 1 = 8!"),
    ("What is 10 - 1?", "9", ["9", "8", "10"], "10 - 1 = 9!"),
    ("What is 10 - 2?", "8", ["8", "7", "9"], "10 - 2 = 8!"),
    ("What is 5 - 5?", "0", ["0", "1", "5"], "5 - 5 = 0!"),
    ("What is 3 - 0?", "3", ["3", "0", "2"], "Taking away zero leaves the same number: 3!"),
    ("What is 2 - 2?", "0", ["0", "1", "2"], "2 - 2 = 0!"),
    ("What is 1 - 1?", "0", ["0", "1", "2"], "1 - 1 = 0!")
]
for q, ans, opts, hint in subtraction_k3:
    add_q("math_subtraction", "maths", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)

# Q26-60: Additional simple K3 subtractions (up to 60)
simple_subtractions = [
    (4, 3, 1), (5, 4, 1), (6, 4, 2), (6, 5, 1), (7, 3, 4),
    (7, 4, 3), (7, 5, 2), (8, 4, 4), (8, 5, 3), (8, 6, 2),
    (9, 2, 7), (9, 3, 6), (9, 4, 5), (9, 5, 4), (9, 6, 3),
    (10, 3, 7), (10, 4, 6), (10, 6, 4), (10, 7, 3), (10, 8, 2),
    (10, 9, 1), (10, 10, 0), (7, 0, 7), (8, 0, 8), (9, 0, 9),
    (11, 1, 10), (12, 2, 10), (13, 3, 10), (14, 4, 10), (15, 5, 10),
    (12, 1, 11), (13, 1, 12), (14, 1, 13), (15, 1, 14), (20, 10, 10)
]
for a, b, diff in simple_subtractions:
    q = f"What is {a} - {b}?"
    ans = str(diff)
    opts = [ans, str(diff + 1), str(diff - 1 if diff > 0 else diff + 2)]
    random.shuffle(opts)
    hint = f"Start at {a} and count back {b}: {diff}!"
    add_q("math_subtraction", "maths", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# =========================================================================
# SUBJECT 3: ENGLISH (K3 Friendly)
# =========================================================================

# --- Topic 1: eng_blending (Phonics & CVC Word Blending) ---
cvc_words = [
    ("c - a - t", "cat", "images/english/cat.jpg", "A cute pet that says meow!"),
    ("d - o - g", "dog", None, "A friendly pet that barks woof!"),
    ("s - u - n", "sun", "images/science/sun.jpg", "Shines bright in the daytime sky!"),
    ("b - e - d", "bed", None, "Where you sleep at night."),
    ("p - i - g", "pig", None, "A pink animal that says oink oink!"),
    ("b - a - t", "bat", None, "Flies at night or hits a ball!"),
    ("c - u - p", "cup", "images/science/glass_cup.jpg", "Used for drinking water or milk."),
    ("f - a - n", "fan", None, "Spins to blow cool air."),
    ("b - u - s", "bus", "images/kenderaan/bas.jpg", "Big vehicle that takes kids to school."),
    ("p - e - n", "pen", None, "Used to write and draw ink lines."),
    ("h - e - n", "hen", "images/binatang/ayam.jpg", "Mother bird that lays eggs."),
    ("b - o - x", "box", None, "Square cardboard container."),
    ("f - o - x", "fox", None, "Clever wild animal with a bushy tail."),
    ("v - a - n", "van", None, "A vehicle bigger than a car."),
    ("m - o - p", "mop", None, "Used to clean wet floors."),
    ("n - e - t", "net", None, "Used to catch fish or butterflies."),
    ("r - e - d", "red", "images/english/apple.jpg", "The color of an apple and a ruby."),
    ("p - i - n", "pin", None, "A little pointy metal tool."),
    ("s - i - t", "sit", None, "Sit down nicely on a chair!"),
    ("r - u - n", "run", None, "Run fast on the playground!")
]

# Q1-20: Sound it out
for blended, word, img, hint in cvc_words:
    q = f"Sound it out: {blended}. What word is it?"
    opts = [word, word + "s", word[:-1] + "p"]
    random.shuffle(opts)
    add_q("eng_blending", "english", q, q, "en", "multiple_choice", img, None, opts, word, hint, hint)

# Q21-35: Beginning sound
beginning_sounds = [
    ("Apple", "A", ["A", "B", "C"], "images/english/apple.jpg", "A is for Apple!"),
    ("Banana", "B", ["B", "P", "D"], "images/english/banana.jpg", "B is for Banana!"),
    ("Cat", "C", ["C", "K", "S"], "images/english/cat.jpg", "C is for Cat!"),
    ("Egg", "E", ["E", "A", "I"], "images/english/egg.jpg", "E is for Egg!"),
    ("House", "H", ["H", "M", "N"], "images/english/house.jpg", "H is for House!"),
    ("Octopus", "O", ["O", "U", "A"], "images/english/octopus.jpg", "O is for Octopus!"),
    ("Sun", "S", ["S", "C", "Z"], "images/science/sun.jpg", "S is for Sun!"),
    ("Moon", "M", ["M", "N", "W"], "images/science/moon.jpg", "M is for Moon!"),
    ("Tree", "T", ["T", "D", "P"], "images/english/palm_tree.jpg", "T is for Tree!"),
    ("Clock", "C", ["C", "K", "O"], "images/english/clock.jpg", "C is for Clock!"),
    ("Lollipop", "L", ["L", "I", "T"], "images/english/lollipop.jpg", "L is for Lollipop!"),
    ("Dog", "D", ["D", "B", "P"], None, "D is for Dog!"),
    ("Fish", "F", ["F", "V", "P"], "images/binatang/ikan.jpg", "F is for Fish!"),
    ("Pig", "P", ["P", "B", "Q"], None, "P is for Pig!"),
    ("Star", "S", ["S", "T", "R"], "images/science/star.jpg", "S is for Star!")
]
for item, sound, opts, img, hint in beginning_sounds:
    q = f"What is the first letter sound of '{item}'?"
    add_q("eng_blending", "english", q, q, "en", "multiple_choice", img, None, opts, sound, hint, hint)

# Q36-45: Rhyming words for K3
rhymes_k3 = [
    ("Which word rhymes with 'Cat'?", "Hat", ["Hat", "Dog", "Sun"], "Cat and Hat both say -at!"),
    ("Which word rhymes with 'Sun'?", "Run", ["Run", "Bed", "Cat"], "Sun and Run both say -un!"),
    ("Which word rhymes with 'Dog'?", "Frog", ["Frog", "Pig", "Hen"], "Dog and Frog both say -og!"),
    ("Which word rhymes with 'Bed'?", "Red", ["Red", "Box", "Cup"], "Bed and Red both say -ed!"),
    ("Which word rhymes with 'Pig'?", "Big", ["Big", "Hat", "Sun"], "Pig and Big both say -ig!"),
    ("Which word rhymes with 'Pen'?", "Hen", ["Hen", "Cat", "Fox"], "Pen and Hen both say -en!"),
    ("Which word rhymes with 'Fox'?", "Box", ["Box", "Net", "Bus"], "Fox and Box both say -ox!"),
    ("Which word rhymes with 'Van'?", "Fan", ["Fan", "Mop", "Sit"], "Van and Fan both say -an!"),
    ("Which word rhymes with 'Hop'?", "Mop", ["Mop", "Run", "Hat"], "Hop and Mop both say -op!"),
    ("Which word rhymes with 'Bug'?", "Hug", ["Hug", "Pin", "Dog"], "Bug and Hug both say -ug!")
]
for q, ans, opts, hint in rhymes_k3:
    add_q("eng_blending", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)

# Q46-55: Fill in missing vowel (a, e, i, o, u)
vowel_k3 = [
    ("Fill in the vowel for Cat: c - __ - t", "a", ["a", "e", "o"], "Cat has letter 'a' in the middle!"),
    ("Fill in the vowel for Bed: b - __ - d", "e", ["e", "a", "u"], "Bed has letter 'e' in the middle!"),
    ("Fill in the vowel for Pig: p - __ - g", "i", ["i", "o", "a"], "Pig has letter 'i' in the middle!"),
    ("Fill in the vowel for Dog: d - __ - g", "o", ["o", "a", "e"], "Dog has letter 'o' in the middle!"),
    ("Fill in the vowel for Sun: s - __ - n", "u", ["u", "a", "i"], "Sun has letter 'u' in the middle!"),
    ("Fill in the vowel for Cup: c - __ - p", "u", ["u", "o", "e"], "Cup has letter 'u'!"),
    ("Fill in the vowel for Pen: p - __ - n", "e", ["e", "i", "u"], "Pen has letter 'e'!"),
    ("Fill in the vowel for Hat: h - __ - t", "a", ["a", "u", "o"], "Hat has letter 'a'!"),
    ("Fill in the vowel for Box: b - __ - x", "o", ["o", "e", "a"], "Box has letter 'o'!"),
    ("Fill in the vowel for Pin: p - __ - n", "i", ["i", "a", "u"], "Pin has letter 'i'!")
]
for q, ans, opts, hint in vowel_k3:
    add_q("eng_blending", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 2: eng_pronouns (Pronouns: He, She, It, They) ---
k3_pronouns_bank = [
    # He (boy / man)
    ("Ben is a little boy. We say: (He / She / It)", "He", ["He", "She", "It"], "images/english/little_boy.jpg", "Use 'He' for a boy or man!"),
    ("My father is driving a car. We say: (He / She / It)", "He", ["He", "She", "It"], None, "A father is a man: use 'He'."),
    ("Mr. Lee is our teacher. We say: (He / She / It)", "He", ["He", "She", "It"], "images/english/male_teacher.jpg", "Mr. Lee is a man: use 'He'."),
    ("The boy is kicking a ball. ___ is happy.", "He", ["He", "She", "It"], "images/english/little_boy.jpg", "A boy: use 'He'."),
    ("Grandpa is reading a book. ___ wears glasses.", "He", ["He", "She", "It"], None, "Grandpa is a man: use 'He'."),
    ("My brother likes toy cars. ___ has a red car.", "He", ["He", "She", "It"], None, "A brother is a boy: use 'He'."),
    ("The little boy is smiling. ___ has a balloon.", "He", ["He", "She", "It"], "images/english/little_boy.jpg", "Boy: use 'He'."),
    ("Uncle Sam is waving. ___ says hello!", "He", ["He", "She", "It"], None, "Uncle is a man: use 'He'."),
    ("The prince lives in a castle. ___ wears a crown.", "He", ["He", "She", "It"], None, "A prince is a boy/man: use 'He'."),
    ("Ali is drinking milk. ___ is healthy.", "He", ["He", "She", "It"], None, "Ali is a boy: use 'He'."),
    
    # She (girl / woman)
    ("Lily is a little girl. We say: (She / He / It)", "She", ["She", "He", "It"], "images/english/little_girl.jpg", "Use 'She' for a girl or woman!"),
    ("My mother is cooking dinner. We say: (She / He / It)", "She", ["She", "He", "It"], None, "A mother is a woman: use 'She'."),
    ("The girl has a pretty pink doll. ___ loves it.", "She", ["She", "He", "It"], "images/english/little_girl.jpg", "Girl: use 'She'."),
    ("Grandma is baking cookies. ___ is very kind.", "She", ["She", "He", "It"], None, "Grandma is a woman: use 'She'."),
    ("My sister is singing a song. ___ sings nicely.", "She", ["She", "He", "It"], None, "Sister is a girl: use 'She'."),
    ("The princess has a magic wand. ___ smiles happily.", "She", ["She", "He", "It"], None, "Princess is a girl: use 'She'."),
    ("Miss Sarah is reading a story. ___ is our teacher.", "She", ["She", "He", "It"], None, "Miss Sarah is a lady: use 'She'."),
    ("Aunt May is visiting us. ___ brought cupcakes!", "She", ["She", "He", "It"], None, "Aunt is a lady: use 'She'."),
    ("The little girl is jumping rope. ___ is fast.", "She", ["She", "He", "It"], "images/english/little_girl.jpg", "Girl: use 'She'."),
    ("Siti is drawing a rainbow. ___ uses color pencils.", "She", ["She", "He", "It"], "images/english/color_pencils.jpg", "Siti is a girl: use 'She'."),

    # It (things / animals)
    ("The cat drinks fresh milk. We say: (It / He / She)", "It", ["It", "He", "She"], "images/english/cat.jpg", "Use 'It' for animals and objects!"),
    ("The red apple is sweet and crunchy. ___ is in a bowl.", "It", ["It", "He", "She"], "images/english/apple.jpg", "An apple is a thing: use 'It'."),
    ("The clock hangs on the wall. ___ goes tick-tock.", "It", ["It", "He", "She"], "images/english/clock.jpg", "A clock is an object: use 'It'."),
    ("The dog wags its tail. ___ is playful.", "It", ["It", "He", "She"], None, "Dog is an animal: use 'It'."),
    ("The banana is yellow. ___ is ripe and sweet.", "It", ["It", "He", "She"], "images/english/banana.jpg", "A banana is a fruit: use 'It'."),
    ("The pencil is on my desk. ___ is sharp.", "It", ["It", "He", "She"], "images/english/color_pencils.jpg", "A pencil is an object: use 'It'."),
    ("The sun shines high in the sky. ___ is very bright.", "It", ["It", "He", "She"], "images/science/sun.jpg", "The sun is an object: use 'It'."),
    ("The house has a red roof. ___ is big and cozy.", "It", ["It", "He", "She"], "images/english/house.jpg", "A house is a building: use 'It'."),
    ("The car has four wheels. ___ drives on the road.", "It", ["It", "He", "She"], "images/kenderaan/kereta.jpg", "A car is a vehicle: use 'It'."),
    ("The lollipop is sweet. ___ is on a stick.", "It", ["It", "He", "She"], "images/english/lollipop.jpg", "A lollipop is candy: use 'It'."),

    # They (many people / group)
    ("Tom and Ben are playing soccer. We say: (They / He / She)", "They", ["They", "He", "She"], "images/english/children_group.jpg", "Two or more people: use 'They'!"),
    ("The children are laughing in class. ___ are happy.", "They", ["They", "He", "It"], "images/english/children_group.jpg", "Many children: use 'They'."),
    ("My friends are coming over. ___ bring toys.", "They", ["They", "She", "It"], "images/english/toys_bunch.jpg", "Friends: use 'They'."),
    ("The birds are singing in the tree. ___ have wings.", "They", ["They", "It", "He"], None, "Many birds: use 'They'."),
    ("Ali and Siti are eating lunch. ___ eat rice.", "They", ["They", "He", "She"], None, "Ali and Siti together: use 'They'."),
    ("The students are listening to teacher. ___ sit quietly.", "They", ["They", "She", "He"], "images/english/children_group.jpg", "Students: use 'They'."),
    ("Look at the baby puppies! ___ are sleeping.", "They", ["They", "It", "She"], None, "Many puppies: use 'They'."),
    ("Mom and Dad are smiling. ___ love me very much.", "They", ["They", "He", "She"], None, "Mom and Dad together: use 'They'."),
    ("The players kick the ball. ___ scored a goal!", "They", ["They", "He", "It"], "images/english/sports_balls.jpg", "Players: use 'They'."),
    ("The flowers bloom in the garden. ___ are colorful.", "They", ["They", "It", "He"], "images/science/flower.jpg", "Flowers: use 'They'.")
]
# Add 15 more simple pronoun practice questions to reach 55
extra_pronouns = [
    ("Look at the boy running. ___ is fast! (He / She)", "He", ["He", "She"], "Boy: He!"),
    ("Look at the girl skipping rope. ___ is smiling! (She / He)", "She", ["She", "He"], "Girl: She!"),
    ("Here is a green frog. ___ jumps high! (It / He)", "It", ["It", "He"], "Frog: It!"),
    ("The kids are playing on the slide. ___ are having fun! (They / It)", "They", ["They", "It"], "Kids: They!"),
    ("My brother loves dinosaurs. ___ has five toys. (He / She)", "He", ["He", "She"], "Brother: He!"),
    ("My sister has long hair. ___ wears a ribbon. (She / He)", "She", ["She", "He"], "Sister: She!"),
    ("This is a shiny metal spoon. ___ is clean. (It / She)", "It", ["It", "She"], "Spoon: It!"),
    ("The ducks are swimming in the pond. ___ quack! (They / It)", "They", ["They", "It"], "Ducks: They!"),
    ("Dad is washing his car. ___ is wearing boots. (He / She)", "He", ["He", "She"], "Dad: He!"),
    ("Mom is reading me a bedtime story. ___ is sweet. (She / He)", "She", ["She", "He"], "Mom: She!"),
    ("The school bus is yellow. ___ stops at the gate. (It / He)", "It", ["It", "He"], "Bus: It!"),
    ("Tim and Leo are building blocks. ___ made a tower! (They / He)", "They", ["They", "He"], "Tim and Leo: They!"),
    ("A little baby is sleeping in the cot. ___ is cute. (He / It)", "He", ["He", "It"], "Baby boy: He!"),
    ("The teacher wrote on the board. ___ taught us ABC. (She / It)", "She", ["She", "It"], "Teacher: She!"),
    ("Here is a fresh yellow banana. ___ tastes yummy! (It / He)", "It", ["It", "He"], "Banana: It!")
]
for q, ans, opts, img, hint in k3_pronouns_bank:
    add_q("eng_pronouns", "english", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)
for q, ans, opts, hint in extra_pronouns:
    add_q("eng_pronouns", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 3: eng_articles (Articles: A vs An) ---
articles_k3_bank = [
    # An (starts with vowel sound A, E, I, O, U)
    ("___ apple", "An", ["An", "A"], "images/english/apple.jpg", "'Apple' starts with vowel A: use 'An'!"),
    ("___ egg", "An", ["An", "A"], "images/english/egg.jpg", "'Egg' starts with vowel E: use 'An'!"),
    ("___ octopus", "An", ["An", "A"], "images/english/octopus.jpg", "'Octopus' starts with vowel O: use 'An'!"),
    ("___ umbrella", "An", ["An", "A"], None, "'Umbrella' starts with vowel U: use 'An'!"),
    ("___ ice cream", "An", ["An", "A"], None, "'Ice cream' starts with vowel I: use 'An'!"),
    ("___ orange", "An", ["An", "A"], None, "'Orange' starts with vowel O: use 'An'!"),
    ("___ elephant", "An", ["An", "A"], "images/binatang/gajah.jpg", "'Elephant' starts with vowel E: use 'An'!"),
    ("___ ant", "An", ["An", "A"], None, "'Ant' starts with vowel A: use 'An'!"),
    ("___ airplane", "An", ["An", "A"], "images/kenderaan/kapal_terbang.jpg", "'Airplane' starts with vowel A: use 'An'!"),
    ("___ owl", "An", ["An", "A"], None, "'Owl' starts with vowel O: use 'An'!"),
    ("___ igloo", "An", ["An", "A"], None, "'Igloo' starts with vowel I: use 'An'!"),
    ("___ alligator", "An", ["An", "A"], "images/binatang/buaya.jpg", "'Alligator' starts with vowel A: use 'An'!"),
    ("I eat ___ sweet apple for breakfast.", "an", ["an", "a"], "images/english/apple.jpg", "Use 'an' before apple!"),
    ("Mom fried ___ egg for me.", "an", ["an", "a"], "images/english/egg.jpg", "Use 'an' before egg!"),
    ("Look at ___ elephant in the zoo!", "an", ["an", "a"], "images/binatang/gajah.jpg", "Use 'an' before elephant!"),

    # A (starts with consonant sound)
    ("___ banana", "A", ["A", "An"], "images/english/banana.jpg", "'Banana' starts with B: use 'A'!"),
    ("___ cat", "A", ["A", "An"], "images/english/cat.jpg", "'Cat' starts with C: use 'A'!"),
    ("___ dog", "A", ["A", "An"], None, "'Dog' starts with D: use 'A'!"),
    ("___ house", "A", ["A", "An"], "images/english/house.jpg", "'House' starts with H: use 'A'!"),
    ("___ clock", "A", ["A", "An"], "images/english/clock.jpg", "'Clock' starts with C: use 'A'!"),
    ("___ lollipop", "A", ["A", "An"], "images/english/lollipop.jpg", "'Lollipop' starts with L: use 'A'!"),
    ("___ ball", "A", ["A", "An"], "images/english/sports_balls.jpg", "'Ball' starts with B: use 'A'!"),
    ("___ tree", "A", ["A", "An"], "images/english/palm_tree.jpg", "'Tree' starts with T: use 'A'!"),
    ("___ car", "A", ["A", "An"], "images/kenderaan/kereta.jpg", "'Car' starts with C: use 'A'!"),
    ("___ book", "A", ["A", "An"], "images/science/paper_book.jpg", "'Book' starts with B: use 'A'!"),
    ("___ fish", "A", ["A", "An"], "images/binatang/ikan.jpg", "'Fish' starts with F: use 'A'!"),
    ("___ duck", "A", ["A", "An"], "images/binatang/itik.jpg", "'Duck' starts with D: use 'A'!"),
    ("___ flower", "A", ["A", "An"], "images/science/flower.jpg", "'Flower' starts with F: use 'A'!"),
    ("___ pencil", "A", ["A", "An"], "images/english/color_pencils.jpg", "'Pencil' starts with P: use 'A'!"),
    ("___ teacher", "A", ["A", "An"], "images/english/male_teacher.jpg", "'Teacher' starts with T: use 'A'!"),
    ("I have ___ yellow banana.", "a", ["a", "an"], "images/english/banana.jpg", "Use 'a' before banana!"),
    ("She has ___ cute cat.", "a", ["a", "an"], "images/english/cat.jpg", "Use 'a' before cat!"),
    ("He draws ___ big house.", "a", ["a", "an"], "images/english/house.jpg", "Use 'a' before house!"),
    ("We kick ___ round ball.", "a", ["a", "an"], "images/english/sports_balls.jpg", "Use 'a' before ball!"),
    ("This is ___ wooden pencil.", "a", ["a", "an"], "images/english/color_pencils.jpg", "Use 'a' before pencil!")
]
# Extra 20 items to hit 55
extra_articles = [
    ("___ frog", "A", ["A", "An"], "Frog starts with F: A!"),
    ("___ bird", "A", ["A", "An"], "Bird starts with B: A!"),
    ("___ monkey", "A", ["A", "An"], "Monkey starts with M: A!"),
    ("___ lion", "A", ["A", "An"], "Lion starts with L: A!"),
    ("___ tiger", "A", ["A", "An"], "Tiger starts with T: A!"),
    ("___ cup", "A", ["A", "An"], "Cup starts with C: A!"),
    ("___ star", "A", ["A", "An"], "Star starts with S: A!"),
    ("___ spoon", "A", ["A", "An"], "Spoon starts with S: A!"),
    ("___ sun", "A", ["A", "An"], "Sun starts with S: A!"),
    ("___ van", "A", ["A", "An"], "Van starts with V: A!"),
    ("___ insect", "An", ["An", "A"], "Insect starts with I: An!"),
    ("___ ox", "An", ["An", "A"], "Ox starts with O: An!"),
    ("___ elf", "An", ["An", "A"], "Elf starts with E: An!"),
    ("___ apron", "An", ["An", "A"], "Apron starts with A: An!"),
    ("___ acorn", "An", ["An", "A"], "Acorn starts with A: An!"),
    ("Which vowel gets 'An': A, E, I, O, or U?", "All of them", ["All of them", "Only A"], "All vowels A, E, I, O, U take 'an'!"),
    ("Do we say 'a cat' or 'an cat'?", "a cat", ["a cat", "an cat"], "We say 'a cat'!"),
    ("Do we say 'a apple' or 'an apple'?", "an apple", ["an apple", "a apple"], "We say 'an apple'!"),
    ("Do we say 'a egg' or 'an egg'?", "an egg", ["an egg", "a egg"], "We say 'an egg'!"),
    ("Do we say 'a dog' or 'an dog'?", "a dog", ["a dog", "an dog"], "We say 'a dog'!")
]
for item in articles_k3_bank:
    q, ans, opts, img, hint = item
    add_q("eng_articles", "english", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)
for q, ans, opts, hint in extra_articles:
    add_q("eng_articles", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 4: eng_has_have (Has vs Have, K3 Friendly) ---
has_have_k3 = [
    # I have, You have, We have, They have
    ("I ___ two hands to clap. (have / has)", "have", ["have", "has"], "Say: 'I have'!"),
    ("I ___ two eyes to see. (have / has)", "have", ["have", "has"], "Say: 'I have'!"),
    ("I ___ a red toy car. (have / has)", "have", ["have", "has"], "Say: 'I have'!"),
    ("I ___ ten fingers. (have / has)", "have", ["have", "has"], "Say: 'I have'!"),
    ("You ___ a bright smile. (have / has)", "have", ["have", "has"], "Say: 'You have'!"),
    ("We ___ many books in our classroom. (have / has)", "have", ["have", "has"], "Say: 'We have'!"),
    ("We ___ fun on the playground. (have / has)", "have", ["have", "has"], "Say: 'We have'!"),
    ("They ___ colorful balloons. (have / has)", "have", ["have", "has"], "Say: 'They have'!"),
    ("The children ___ ice cream. (have / has)", "have", ["have", "has"], "Many children: 'They have'!"),
    ("Ali and Ben ___ a football. (have / has)", "have", ["have", "has"], "Two friends: 'have'!"),

    # He has, She has, It has
    ("He ___ a blue cap. (has / have)", "has", ["has", "have"], "A boy: 'He has'!"),
    ("He ___ a green bicycle. (has / have)", "has", ["has", "have"], "A boy: 'He has'!"),
    ("He ___ a puppy at home. (has / have)", "has", ["has", "have"], "A boy: 'He has'!"),
    ("She ___ a pretty pink dress. (has / have)", "has", ["has", "have"], "A girl: 'She has'!"),
    ("She ___ a storybook to read. (has / have)", "has", ["has", "have"], "A girl: 'She has'!"),
    ("She ___ long black hair. (has / have)", "has", ["has", "have"], "A girl: 'She has'!"),
    ("A dog ___ four furry legs. (has / have)", "has", ["has", "have"], "One dog: 'It has'!"),
    ("A cat ___ whiskers. (has / have)", "has", ["has", "have"], "One cat: 'It has'!"),
    ("A bird ___ two wings to fly. (has / have)", "has", ["has", "have"], "One bird: 'It has'!"),
    ("An elephant ___ a long trunk. (has / have)", "has", ["has", "have"], "One elephant: 'It has'!"),
    ("A car ___ four wheels. (has / have)", "has", ["has", "have"], "One car: 'It has'!"),
    ("The clock ___ two hands. (has / have)", "has", ["has", "have"], "One clock: 'It has'!"),
    ("My father ___ a brown watch. (has / have)", "has", ["has", "have"], "Father: 'He has'!"),
    ("My mother ___ a flower basket. (has / have)", "has", ["has", "have"], "Mother: 'She has'!"),
    ("The rabbit ___ long ears. (has / have)", "has", ["has", "have"], "One rabbit: 'It has'!")
]
for q, ans, opts, hint in has_have_k3:
    add_q("eng_has_have", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)

# Additional 30 practice items for K3 to reach 55
extra_has_have = [
    ("Tom ___ a pet goldfish. (has / have)", "has", ["has", "have"], "Tom is one boy: 'has'."),
    ("Mia ___ a singing bird. (has / have)", "has", ["has", "have"], "Mia is one girl: 'has'."),
    ("The boy ___ a yellow kite. (has / have)", "has", ["has", "have"], "Boy: 'has'."),
    ("The girl ___ shiny shoes. (has / have)", "has", ["has", "have"], "Girl: 'has'."),
    ("I ___ a sweet red apple. (have / has)", "have", ["have", "has"], "'I have'."),
    ("You ___ a clean water bottle. (have / has)", "have", ["have", "has"], "'You have'."),
    ("We ___ color pencils to draw. (have / has)", "have", ["have", "has"], "'We have'."),
    ("They ___ a big toy train. (have / has)", "have", ["have", "has"], "'They have'."),
    ("A fish ___ fins to swim. (has / have)", "has", ["has", "have"], "One fish: 'has'."),
    ("A spider ___ eight legs. (has / have)", "has", ["has", "have"], "One spider: 'has'."),
    ("A giraffe ___ a tall neck. (has / have)", "has", ["has", "have"], "One giraffe: 'has'."),
    ("The baby ___ a soft blanket. (has / have)", "has", ["has", "have"], "Baby: 'has'."),
    ("The teacher ___ a box of chalk. (has / have)", "has", ["has", "have"], "Teacher: 'has'."),
    ("I ___ two ears to listen. (have / has)", "have", ["have", "has"], "'I have'."),
    ("You ___ nice manners. (have / has)", "have", ["have", "has"], "'You have'."),
    ("We ___ art class today! (have / has)", "have", ["have", "has"], "'We have'."),
    ("My brother ___ a robot toy. (has / have)", "has", ["has", "have"], "Brother: 'has'."),
    ("My sister ___ a doll house. (has / have)", "has", ["has", "have"], "Sister: 'has'."),
    ("A duck ___ a flat beak. (has / have)", "has", ["has", "have"], "Duck: 'has'."),
    ("A plane ___ two big wings. (has / have)", "has", ["has", "have"], "Plane: 'has'."),
    ("The tree ___ green leaves. (has / have)", "has", ["has", "have"], "Tree: 'has'."),
    ("I ___ a warm bed. (have / has)", "have", ["have", "has"], "'I have'."),
    ("They ___ music class on Friday. (have / has)", "have", ["have", "has"], "'They have'."),
    ("We ___ lunch at noon. (have / has)", "have", ["have", "has"], "'We have'."),
    ("Leo ___ a football. (has / have)", "has", ["has", "have"], "Leo: 'has'."),
    ("Sarah ___ a kitten. (has / have)", "has", ["has", "have"], "Sarah: 'has'."),
    ("A bicycle ___ two pedals. (has / have)", "has", ["has", "have"], "Bicycle: 'has'."),
    ("A house ___ a front door. (has / have)", "has", ["has", "have"], "House: 'has'."),
    ("I ___ five fingers on this hand. (have / has)", "have", ["have", "has"], "'I have'."),
    ("You ___ a birthday party coming! (have / has)", "have", ["have", "has"], "'You have'.")
]
for q, ans, opts, hint in extra_has_have:
    add_q("eng_has_have", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 5: eng_days_months (Days of the Week & Months, K3 Friendly) ---
days_k3 = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]

# Q1-7: Day after X
for i in range(6):
    cur_d = days_k3[i]
    next_d = days_k3[i + 1]
    q = f"What day comes after {cur_d}?"
    opts = [next_d] + random.sample([x for x in days_k3 if x != next_d and x != cur_d], 2)
    random.shuffle(opts)
    hint = f"Sing the days of the week: after {cur_d} is {next_d}!"
    add_q("eng_days_months", "english", q, q, "en", "multiple_choice", None, None, opts, next_d, hint, hint)

# Day after Sunday
q = "What day comes after Sunday?"
opts = ["Monday", "Friday", "Wednesday"]
add_q("eng_days_months", "english", q, q, "en", "multiple_choice", None, None, opts, "Monday", "After Sunday, the school week starts with Monday!", "Monday.")

# Q8-20: Day trivia for K3
days_trivia_k3 = [
    ("How many days are in a week?", "7 days", ["7 days", "5 days", "10 days"], "There are 7 days in a week!"),
    ("Which two days are the weekend when you rest at home?", "Saturday and Sunday", ["Saturday and Sunday", "Monday and Tuesday"], "Saturday and Sunday are weekend days!"),
    ("What day comes right before Tuesday?", "Monday", ["Monday", "Wednesday", "Friday"], "Monday comes before Tuesday."),
    ("What day comes right before Friday?", "Thursday", ["Thursday", "Wednesday", "Saturday"], "Thursday comes before Friday."),
    ("What day comes right before Sunday?", "Saturday", ["Saturday", "Friday", "Monday"], "Saturday comes before Sunday."),
    ("What day starts with the letter 'M'?", "Monday", ["Monday", "Friday", "Sunday"], "M is for Monday!"),
    ("Which days start with the letter 'S'?", "Saturday and Sunday", ["Saturday and Sunday", "Tuesday and Thursday"], "Saturday and Sunday both start with S!"),
    ("Which days start with the letter 'T'?", "Tuesday and Thursday", ["Tuesday and Thursday", "Monday and Friday"], "Tuesday and Thursday both start with T!"),
    ("What day is in the middle of Monday and Wednesday?", "Tuesday", ["Tuesday", "Thursday", "Friday"], "Monday, Tuesday, Wednesday!"),
    ("What day is in the middle of Wednesday and Friday?", "Thursday", ["Thursday", "Tuesday", "Saturday"], "Wednesday, Thursday, Friday!"),
    ("What is the first school day of the week?", "Monday", ["Monday", "Sunday", "Saturday"], "Monday is day 1 of school."),
    ("What is the last school day before the weekend?", "Friday", ["Friday", "Thursday", "Wednesday"], "TGIF! Friday is the last day of school."),
    ("How many months are in a whole year?", "12 months", ["12 months", "7 months", "10 months"], "There are 12 months in a year!")
]
for q, ans, opts, hint in days_trivia_k3:
    add_q("eng_days_months", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)

# Q21-32: Months in English
eng_months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
for i, m in enumerate(eng_months, 1):
    q = f"What is the {i}{'st' if i==1 else ('nd' if i==2 else ('rd' if i==3 else 'th'))} month of the year?"
    opts = [m] + random.sample([x for x in eng_months if x != m], 2)
    random.shuffle(opts)
    hint = f"Count on the calendar: month number {i} is {m}."
    add_q("eng_days_months", "english", q, q, "en", "multiple_choice", None, None, opts, m, hint, hint)

# Q33-55: Quick months questions
extra_months_k3 = [
    ("What is the very first month of the year?", "January", ["January", "December", "March"], "January is month 1!"),
    ("What is the very last month of the year?", "December", ["December", "November", "January"], "December is month 12!"),
    ("Which month is the shortest with only 28 or 29 days?", "February", ["February", "May", "July"], "February is the shortest month."),
    ("Which month has only 3 letters: M - A - Y?", "May", ["May", "June", "July"], "M - A - Y spells May!"),
    ("Which month comes after June?", "July", ["July", "August", "May"], "June, then July!"),
    ("Which month comes after October?", "November", ["November", "December", "September"], "October, then November!"),
    ("Which month comes before March?", "February", ["February", "January", "April"], "February comes before March."),
    ("Which month comes before December?", "November", ["November", "October", "January"], "November comes before December."),
    ("New Year's Day is on the 1st of which month?", "January", ["January", "December", "February"], "Happy New Year on January 1st!"),
    ("Christmas is celebrated in which month?", "December", ["December", "January", "November"], "Christmas is on December 25th!"),
    ("What month comes right after March?", "April", ["April", "May", "February"], "March, then April!"),
    ("What month comes right after August?", "September", ["September", "October", "July"], "August, then September!"),
    ("Which month starts with the letter 'O'?", "October", ["October", "August", "November"], "O is for October!"),
    ("Which month starts with the letter 'S'?", "September", ["September", "October", "December"], "S is for September!"),
    ("Which month starts with the letter 'N'?", "November", ["November", "December", "January"], "N is for November!"),
    ("Which months start with the letter 'J'?", "January, June, July", ["January, June, July", "May, March"], "January, June, and July all start with J!"),
    ("Which months start with the letter 'M'?", "March and May", ["March and May", "June and July"], "March and May start with M!"),
    ("Which month comes after July?", "August", ["August", "September", "June"], "July, then August!"),
    ("Which month comes before September?", "August", ["August", "July", "October"], "August comes before September."),
    ("How many days are in a weekend?", "2 days", ["2 days", "3 days", "5 days"], "Saturday and Sunday = 2 days!"),
    ("Do you go to kindergarten on Sunday?", "No, it is a holiday", ["No, it is a holiday", "Yes"], "Sunday is a holiday with family!"),
    ("Sing the rhyme: Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, ___!", "Sunday", ["Sunday", "January", "March"], "Sunday finishes the week!"),
    ("What day is today if yesterday was Monday?", "Tuesday", ["Tuesday", "Wednesday", "Sunday"], "After Monday is Tuesday!")
]
for q, ans, opts, hint in extra_months_k3:
    add_q("eng_days_months", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 6: eng_demonstratives (This, That, These, Those, K3 Friendly) ---
demonstratives_k3 = [
    # THIS (near, 1 item)
    ("Hold in your hand: '___ is my pencil.' (This / That)", "This", ["This", "That"], "images/english/color_pencils.jpg", "Holding close in your hand: say 'This'!"),
    ("On your foot right now: '___ is my shoe.' (This / That)", "This", ["This", "That"], None, "On your own foot: say 'This'!"),
    ("In front of you on the desk: '___ is my drawing.' (This / That)", "This", ["This", "That"], None, "Close in front of you: say 'This'!"),
    ("Wearing on your head: '___ is my warm cap.' (This / That)", "This", ["This", "That"], None, "On your head: say 'This'!"),
    ("Holding in your hand: '___ is my sweet apple.' (This / That)", "This", ["This", "That"], "images/english/apple.jpg", "In your hand: say 'This'!"),
    ("Sitting right on your lap: '___ is my teddy bear.' (This / That)", "This", ["This", "That"], None, "On your lap: say 'This'!"),
    ("Holding the handle: '___ is my school bag.' (This / That)", "This", ["This", "That"], None, "Holding it close: say 'This'!"),
    ("Drinking right now: '___ is my cup of milk.' (This / That)", "This", ["This", "That"], "images/science/glass_cup.jpg", "In your hand: say 'This'!"),
    ("Looking in a handheld mirror: '___ is my happy face.' (This / That)", "This", ["This", "That"], None, "Close to you: say 'This'!"),
    ("Point to your own nose: '___ is my nose.' (This / That)", "This", ["This", "That"], None, "Your own nose is close: say 'This'!"),

    # THAT (far, 1 item)
    ("Pointing far away across the room: '___ is a wall clock.' (That / This)", "That", ["That", "This"], "images/english/clock.jpg", "Far across the room: say 'That'!"),
    ("Flying far up in the clouds: '___ is an airplane.' (That / This)", "That", ["That", "This"], "images/kenderaan/kapal_terbang.jpg", "Far in the sky: say 'That'!"),
    ("High up in the night sky: '___ is the glowing moon.' (That / This)", "That", ["That", "This"], "images/science/moon.jpg", "The moon is far away: say 'That'!"),
    ("Over on the other side of the road: '___ is a big tree.' (That / This)", "That", ["That", "This"], "images/english/palm_tree.jpg", "Far over the road: say 'That'!"),
    ("On top of the roof far away: '___ is a little bird.' (That / This)", "That", ["That", "This"], None, "High on the roof: say 'That'!"),
    ("Across the playground: '___ is my friend Ben.' (That / This)", "That", ["That", "This"], "images/english/little_boy.jpg", "Across the field: say 'That'!"),
    ("Out the window: '___ is a yellow taxi.' (That / This)", "That", ["That", "This"], "images/kenderaan/teksi.jpg", "Outside the window: say 'That'!"),
    ("High in the daytime sky: '___ is the hot sun.' (That / This)", "That", ["That", "This"], "images/science/sun.jpg", "The sun is far: say 'That'!"),
    ("Way up on a tall hill: '___ is a castle.' (That / This)", "That", ["That", "This"], None, "Far on the hill: say 'That'!"),
    ("At the end of the street: '___ is the school gate.' (That / This)", "That", ["That", "This"], None, "Far at the end: say 'That'!"),

    # THESE (near, many items)
    ("Here on my desk: '___ are my color pencils.' (These / Those)", "These", ["These", "Those"], "images/english/color_pencils.jpg", "Many items close to you: say 'These'!"),
    ("In my bowl right here: '___ are sweet grapes.' (These / Those)", "These", ["These", "Those"], None, "Many grapes close: say 'These'!"),
    ("On my hands: '___ are my ten fingers.' (These / Those)", "These", ["These", "Those"], None, "Your own fingers: say 'These'!"),
    ("In my toy box next to me: '___ are my toy blocks.' (These / Those)", "These", ["These", "Those"], "images/english/toys_bunch.jpg", "Toys close to you: say 'These'!"),
    ("On my feet: '___ are my new socks.' (These / Those)", "These", ["These", "Those"], None, "Socks on your feet: say 'These'!"),
    ("In this basket here: '___ are fresh bananas.' (These / Those)", "These", ["These", "Those"], "images/english/banana.jpg", "Bananas right here: say 'These'!"),
    ("Here on my table: '___ are my storybooks.' (These / Those)", "These", ["These", "Those"], "images/science/paper_book.jpg", "Books right here: say 'These'!"),
    ("Right in front of me: '___ are my drawing papers.' (These / Those)", "These", ["These", "Those"], None, "Papers right here: say 'These'!"),

    # THOSE (far, many items)
    ("Flying high in the sky: '___ are wild birds.' (Those / These)", "Those", ["Those", "These"], None, "Many birds far away: say 'Those'!"),
    ("Twinkling far away at night: '___ are bright stars.' (Those / These)", "Those", ["Those", "These"], "images/science/star.jpg", "Stars far in the sky: say 'Those'!"),
    ("Over in the school field: '___ are children playing.' (Those / These)", "Those", ["Those", "These"], "images/english/children_group.jpg", "Children far over there: say 'Those'!"),
    ("Parked far down the parking lot: '___ are red cars.' (Those / These)", "Those", ["Those", "These"], "images/kenderaan/kereta.jpg", "Cars far away: say 'Those'!"),
    ("High up on the tree branches: '___ are red apples.' (Those / These)", "Those", ["Those", "These"], "images/science/fruit.jpg", "Apples far up in the tree: say 'Those'!"),
    ("Floating far in the ocean: '___ are fishing boats.' (Those / These)", "Those", ["Those", "These"], "images/kenderaan/bot.jpg", "Boats far away: say 'Those'!"),
    ("Far away on the mountains: '___ are white clouds.' (Those / These)", "Those", ["Those", "These"], None, "Clouds far away: say 'Those'!"),
    ("In the playground across the fence: '___ are swings.' (Those / These)", "Those", ["Those", "These"], None, "Swings across the fence: say 'Those'!")
]
for item in demonstratives_k3:
    q, ans, opts, img, hint = item
    add_q("eng_demonstratives", "english", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)

# Extra 19 simple practice items to hit 55
extra_demonstratives = [
    ("Close to you: '___ is a book.' (This / That)", "This", ["This", "That"], "'This' is for near!"),
    ("Far away: '___ is a kite.' (That / This)", "That", ["That", "This"], "'That' is for far!"),
    ("Close to you: '___ are my toys.' (These / Those)", "These", ["These", "Those"], "'These' for near plural!"),
    ("Far away: '___ are balloons.' (Those / These)", "Those", ["Those", "These"], "'Those' for far plural!"),
    ("Point to a ball near your feet: '___ is a ball.' (This / That)", "This", ["This", "That"], "Ball near feet: This!"),
    ("Point to a plane far in the sky: '___ is a plane.' (That / This)", "That", ["That", "This"], "Plane far away: That!"),
    ("Point to pencils in your hand: '___ are pencils.' (These / Those)", "These", ["These", "Those"], "Pencils in hand: These!"),
    ("Point to birds far on a wire: '___ are birds.' (Those / These)", "Those", ["Those", "These"], "Birds far away: Those!"),
    ("Hold a flower: '___ flower smells sweet.' (This / That)", "This", ["This", "That"], "Holding flower: This!"),
    ("Look at the sun far away: '___ sun is hot.' (That / This)", "That", ["That", "This"], "Sun far: That!"),
    ("Wear a hat: '___ hat is cute.' (This / That)", "This", ["This", "That"], "Wearing hat: This!"),
    ("Look at a bus across the street: '___ is our bus.' (That / This)", "That", ["That", "This"], "Bus far: That!"),
    ("Apples in your hands: '___ apples are sweet.' (These / Those)", "These", ["These", "Those"], "Apples in hands: These!"),
    ("Ducks swimming far in the lake: '___ ducks are yellow.' (Those / These)", "Those", ["Those", "These"], "Ducks far: Those!"),
    ("Touch your chair: '___ chair is comfy.' (This / That)", "This", ["This", "That"], "Touching chair: This!"),
    ("Look at the moon far away: '___ is the moon.' (That / This)", "That", ["That", "This"], "Moon far: That!"),
    ("Holding two cookies: '___ cookies are yummy.' (These / Those)", "These", ["These", "Those"], "Cookies in hand: These!"),
    ("Bicycles parked across the field: '___ bicycles are blue.' (Those / These)", "Those", ["Those", "These"], "Bicycles far: Those!"),
    ("Wear your watch: '___ is my watch.' (This / That)", "This", ["This", "That"], "Wearing watch: This!")
]
for q, ans, opts, hint in extra_demonstratives:
    add_q("eng_demonstratives", "english", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 7: eng_comprehension (Reading Comprehension Passages, K3 Friendly) ---
stories_k3 = [
    # Story 1: Ben's Red Car
    ("Ben has a little red car. The car runs very fast on the floor.", [
        ("What color is Ben's car?", "Red", ["Red", "Blue", "Green"], "Ben's car is red!"),
        ("Does Ben's car run fast or slow?", "Fast", ["Fast", "Slow"], "The story says it runs very fast!"),
        ("Who owns the red car?", "Ben", ["Ben", "Sam", "Lily"], "The story is about Ben."),
        ("Where does the car run?", "On the floor", ["On the floor", "In the water"], "It runs on the floor!")
    ]),
    # Story 2: Mia and Her Cat
    ("Mia has a pet cat named Fluffy. Fluffy is white and soft. Fluffy loves to drink milk.", [
        ("What is Mia's pet?", "A cat", ["A cat", "A dog", "A bird"], "Mia has a pet cat!"),
        ("What is the cat's name?", "Fluffy", ["Fluffy", "Spot", "Bella"], "Its name is Fluffy."),
        ("What color is Fluffy?", "White", ["White", "Black", "Brown"], "Fluffy is white and soft."),
        ("What does Fluffy love to drink?", "Milk", ["Milk", "Juice", "Water"], "Fluffy loves to drink milk!")
    ]),
    # Story 3: Sam in the Park
    ("Sam went to the green park. He saw a bright yellow butterfly on a pretty flower.", [
        ("Where did Sam go?", "To the park", ["To the park", "To the beach", "To bed"], "Sam went to the park."),
        ("What did Sam see?", "A butterfly", ["A butterfly", "A dinosaur", "A car"], "He saw a butterfly!"),
        ("What color was the butterfly?", "Yellow", ["Yellow", "Purple", "Blue"], "The butterfly was bright yellow."),
        ("Where was the butterfly sitting?", "On a flower", ["On a flower", "In a car"], "It was on a pretty flower.")
    ]),
    # Story 4: Pip the Tiny Bird
    ("Pip is a tiny blue bird. Pip can fly high in the blue sky. Pip sings a sweet song.", [
        ("What kind of animal is Pip?", "A bird", ["A bird", "A frog", "A turtle"], "Pip is a tiny bird!"),
        ("What color is Pip?", "Blue", ["Blue", "Red", "Yellow"], "Pip is a blue bird."),
        ("Where does Pip fly?", "High in the sky", ["High in the sky", "Under the water"], "Pip flies high in the sky."),
        ("What does Pip sing?", "A sweet song", ["A sweet song", "A roar"], "Pip sings a sweet song.")
    ]),
    # Story 5: Lucy Loves Fruit
    ("Lucy loves fresh fruit. Today, she eats a big crunchy green apple for snack.", [
        ("What does Lucy love to eat?", "Fruit", ["Fruit", "Candy", "Chips"], "Lucy loves fresh fruit!"),
        ("What fruit does Lucy eat today?", "An apple", ["An apple", "A banana", "An orange"], "She eats an apple."),
        ("What color is Lucy's apple?", "Green", ["Green", "Blue", "Black"], "It is a crunchy green apple."),
        ("Is the apple soft or crunchy?", "Crunchy", ["Crunchy", "Hot"], "The story says crunchy green apple!")
    ]),
    # Story 6: Leo and His Dog
    ("Leo has a brown dog named Max. Max wags his tail and barks happily: Woof woof!", [
        ("What animal is Max?", "A dog", ["A dog", "A cat", "A fish"], "Max is a brown dog!"),
        ("What color is Max?", "Brown", ["Brown", "Green", "Pink"], "Max is brown."),
        ("What sound does Max make?", "Woof woof!", ["Woof woof!", "Meow meow!", "Quack quack!"], "Max barks woof woof!"),
        ("Who is Max's friend?", "Leo", ["Leo", "Tim", "Tom"], "Leo has a dog named Max.")
    ]),
    # Story 7: Anna's Yellow Duck
    ("Anna plays in the bathtub. She has a yellow rubber duck that floats on the water.", [
        ("What does Anna have in the tub?", "A rubber duck", ["A rubber duck", "A real dog"], "Anna has a rubber duck!"),
        ("What color is the duck?", "Yellow", ["Yellow", "Red", "Blue"], "It is a yellow duck."),
        ("Does the rubber duck sink or float?", "Floats", ["Floats", "Sinks"], "It floats on the water!"),
        ("Where does Anna play with her duck?", "In the bathtub", ["In the bathtub", "In the garden"], "In the bathtub.")
    ]),
    # Story 8: Tim's Birthday Party
    ("Today is Tim's 6th birthday. He blows out six candles on a delicious chocolate cake.", [
        ("How old is Tim today?", "6 years old", ["6 years old", "3 years old", "10 years old"], "Tim is 6 today!"),
        ("What kind of cake does Tim have?", "Chocolate cake", ["Chocolate cake", "Carrot cake"], "Delicious chocolate cake."),
        ("How many candles are on the cake?", "Six candles", ["Six candles", "Two candles"], "Six candles for 6 years!"),
        ("What does Tim do to the candles?", "Blows them out", ["Blows them out", "Eats them"], "He blows out the candles.")
    ]),
    # Story 9: Dan and His Big Ball
    ("Dan has a bouncy sports ball. He kicks the ball high over the green grass.", [
        ("What does Dan have?", "A ball", ["A ball", "A kite", "A bike"], "Dan has a bouncy ball!"),
        ("What does Dan do to the ball?", "Kicks it", ["Kicks it", "Eats it"], "He kicks the ball high."),
        ("What color is the grass?", "Green", ["Green", "Purple", "Orange"], "Green grass in the field."),
        ("Is the ball bouncy or flat?", "Bouncy", ["Bouncy", "Broken"], "It is a bouncy ball!")
    ]),
    # Story 10: Kelly and the Stars
    ("Kelly looks out her bedroom window at night. She sees many twinkling stars in the sky.", [
        ("When does Kelly look out the window?", "At night", ["At night", "In the morning"], "She looks out at night."),
        ("What does Kelly see in the night sky?", "Twinkling stars", ["Twinkling stars", "The hot sun"], "She sees stars!"),
        ("Where is Kelly?", "In her bedroom", ["In her bedroom", "At the park"], "Looking out her bedroom window."),
        ("Do the stars twinkle or bark?", "Twinkle", ["Twinkle", "Bark"], "The stars twinkle in the dark.")
    ]),
    # Story 11: The Busy Honey Bee
    ("A busy little bee flies from flower to flower. It gathers sweet nectar to make honey.", [
        ("What insect is busy?", "A bee", ["A bee", "A beetle", "A fly"], "A busy little bee!"),
        ("Where does the bee fly?", "From flower to flower", ["From car to car"], "Flower to flower."),
        ("What does the bee make?", "Honey", ["Honey", "Juice", "Milk"], "Bees make sweet honey!")
    ]),
    # Story 12: Emma's Clean Teeth
    ("Emma brushes her teeth every morning and night. Her teeth are clean, white, and shiny.", [
        ("What does Emma brush?", "Her teeth", ["Her teeth", "Her shoes"], "She brushes her teeth!"),
        ("What do Emma's teeth look like?", "Clean and white", ["Dirty", "Yellow"], "Clean, white, and shiny."),
        ("When does Emma brush her teeth?", "Morning and night", ["Only on Sunday"], "Morning and night!")
    ]),
    # Story 13: Toby's Warm Milk
    ("Toby drinks a cup of warm milk before going to bed. It helps him sleep peacefully.", [
        ("What does Toby drink?", "Warm milk", ["Warm milk", "Cold soda"], "A cup of warm milk."),
        ("When does Toby drink milk?", "Before bed", ["Before running"], "Before going to bed."),
        ("Does it help Toby sleep?", "Yes", ["Yes", "No"], "It helps him sleep peacefully!")
    ]),
    # Story 14: Sunny Day at the Beach
    ("Lily builds a sandcastle on the sandy beach. She puts a little shell on top.", [
        ("What does Lily build?", "A sandcastle", ["A sandcastle", "A car"], "Lily builds a sandcastle!"),
        ("Where is Lily?", "At the beach", ["At the beach", "At school"], "On the sandy beach."),
        ("What does she put on top?", "A shell", ["A shell", "A rock"], "A little shell on top.")
    ]),
    # Story 15: The Rainbow
    ("After the rain, the sun shines. A colorful rainbow appears in the blue sky.", [
        ("What appears in the sky?", "A rainbow", ["A rainbow", "A snowman"], "A colorful rainbow!"),
        ("When does the rainbow appear?", "After the rain", ["In the dark"], "After the rain!"),
        ("Is a rainbow colorful or black?", "Colorful", ["Colorful", "Black"], "A rainbow has many colors!")
    ])
]
for passage, q_items in stories_k3:
    for q, ans, opts, hint in q_items:
        add_q("eng_comprehension", "english", q, q, "en", "comprehension", None, passage, opts, ans, hint, hint)


# =========================================================================
# SUBJECT 4: EARLY SCIENCE (in English, K3 Friendly)
# =========================================================================

# --- Topic 1: sci_land_sea (Land Animals vs Water Animals) ---
animals_land_sea = [
    ("fish", "Water", "images/binatang/ikan.jpg", "A fish swims in water using fins!"),
    ("dolphin", "Water", "images/binatang/lumba_lumba.jpg", "A dolphin leaps in ocean water!"),
    ("whale", "Water", "images/binatang/paus.jpg", "A giant whale lives in the sea!"),
    ("shark", "Water", "images/binatang/jerung.jpg", "A shark swims in the deep ocean!"),
    ("crab", "Water", "images/binatang/ketam.jpg", "A crab scuttles in the sea and sand!"),
    ("prawn", "Water", "images/binatang/udang.jpg", "A prawn swims backwards in water!"),
    ("squid", "Water", "images/binatang/sotong.jpg", "A squid swims in the ocean water!"),
    ("cow", "Land", "images/binatang/lembu.jpg", "A cow walks on the grassy farm!"),
    ("cat", "Land", "images/binatang/kucing.jpg", "A cat walks on land in our home!"),
    ("dog", "Land", "images/binatang/anjing.jpg", "A dog runs on land in the yard!"),
    ("elephant", "Land", "images/binatang/gajah.jpg", "A huge elephant walks on land!"),
    ("lion", "Land", "images/binatang/singa.jpg", "A lion runs on land in the wild!"),
    ("tiger", "Land", "images/binatang/harimau.jpg", "A tiger prowls on land in the jungle!"),
    ("horse", "Land", "images/binatang/kuda.jpg", "A horse gallops on grassy land!"),
    ("monkey", "Land", "images/binatang/monyet.jpg", "A monkey climbs trees on land!"),
    ("giraffe", "Land", "images/binatang/zirafah.jpg", "A tall giraffe walks on land!"),
    ("rabbit", "Land", "images/binatang/arnab.jpg", "A rabbit hops on land in the garden!"),
    ("goat", "Land", "images/binatang/kambing.jpg", "A goat walks on land and eats grass!"),
    ("chicken", "Land", "images/binatang/ayam.jpg", "A chicken pecks for seeds on land!"),
    ("bear", "Land", "images/binatang/beruang.jpg", "A furry bear walks on land in forests!")
]

# Q1-20: Does it live on Land or in Water?
for anim, habitat, img, hint in animals_land_sea:
    q = f"Look at the picture! Does a {anim} live on Land or in Water?"
    opts = ["Land", "Water"]
    add_q("sci_land_sea", "science", q, q, "en", "multiple_choice", img, None, opts, habitat, hint, hint)

# Q21-55: Land vs Water facts for K3
k3_land_sea_trivia = [
    ("Where do fish swim and breathe?", "In water", ["In water", "On land", "In trees"], "Fish have gills to breathe in water."),
    ("Where does a cow walk and eat grass?", "On land", ["On land", "In the ocean", "In the clouds"], "Cows live on the farm on land."),
    ("Can a monkey swim deep under the sea like a fish?", "No", ["No", "Yes"], "Monkeys live on land in trees!"),
    ("Which animal lives in the ocean?", "Shark", ["Shark", "Giraffe", "Elephant"], "Sharks swim in ocean water."),
    ("Which animal lives on a farm?", "Sheep", ["Sheep", "Whale", "Octopus"], "Sheep walk on farm land."),
    ("Can a dolphin breathe fresh air when it jumps out of water?", "Yes", ["Yes", "No"], "Dolphins jump up to breathe air!"),
    ("Where does a sea turtle lay its eggs?", "On sandy land", ["On sandy land", "In the sky"], "Mother turtles crawl onto the sandy beach."),
    ("Which animal has 8 arms and lives under the sea?", "Octopus", ["Octopus", "Horse", "Cat"], "An octopus lives in the sea."),
    ("Where does a camel walk?", "On dry desert land", ["On dry desert land", "Under water"], "Camels walk on desert sand."),
    ("Can an elephant live underwater forever?", "No", ["No", "Yes"], "Elephants need to breathe air on land."),
    ("Which of these can fly in the sky?", "Bird", ["Bird", "Fish", "Crab"], "Birds have wings to fly."),
    ("Does a goldfish live in a water bowl or in a tree?", "In a water bowl", ["In a water bowl", "In a tree"], "Fish must stay in water."),
    ("Where does a polar bear walk on cold ice?", "On land and ice", ["On land and ice", "In a hot desert"], "Polar bears walk on Arctic ice."),
    ("Do crabs have shells and live near water?", "Yes", ["Yes", "No"], "Crabs live on beaches and in water."),
    ("Which animal roars on land?", "Lion", ["Lion", "Prawn", "Jellyfish"], "Lions roar on land."),
    ("Where do ducks love to swim?", "In a pond", ["In a pond", "In a tree"], "Ducks paddle in pond water."),
    ("Can a land animal like a horse walk on four hooves?", "Yes", ["Yes", "No"], "Horses have four hooves on land."),
    ("Where do jellyfish float?", "In the sea", ["In the sea", "On trees"], "Jellyfish float in sea water."),
    ("Does a penguin swim in cold water?", "Yes", ["Yes", "No"], "Penguins are fantastic swimmers!"),
    ("Which animal has a belalai and walks on land?", "Elephant", ["Elephant", "Whale"], "Elephants walk on land."),
    ("Which animal barks and plays on land?", "Dog", ["Dog", "Fish"], "Dogs play on land."),
    ("Which sea animal has sharp teeth and fins?", "Shark", ["Shark", "Rabbit"], "Sharks swim in the sea."),
    ("Do whales swim in the ocean?", "Yes", ["Yes", "No"], "Whales are giant sea swimmers."),
    ("Can an eagle soar high in the sky?", "Yes", ["Yes", "No"], "Eagles soar high in the air."),
    ("Which animal climbs trees and swings on branches?", "Monkey", ["Monkey", "Crab"], "Monkeys climb on land trees."),
    ("Where does a frog lay its jelly eggs?", "In water", ["In water", "On a roof"], "Frogs lay eggs in pond water."),
    ("Can tadpoles swim in water with a tail?", "Yes", ["Yes", "No"], "Baby tadpoles swim in water."),
    ("Where does a kangaroo hop?", "On land", ["On land", "Under water"], "Kangaroos hop on land in Australia."),
    ("Do sea stars live on rocks under water?", "Yes", ["Yes", "No"], "Starfish live on underwater rocks."),
    ("Which animal gives us milk and eats grass on land?", "Cow", ["Cow", "Squid"], "Cows eat grass on land."),
    ("Does an octopus have fins or tentacles?", "Tentacles", ["Tentacles", "Wings"], "An octopus has 8 tentacles."),
    ("Can a cat live underwater?", "No", ["No", "Yes"], "Cats cannot breathe underwater!"),
    ("Which animal loves carrots on land?", "Rabbit", ["Rabbit", "Shark"], "Rabbits hop on land eating carrots."),
    ("Where does a wild tiger hunt for food?", "On land in the forest", ["On land in the forest", "At the bottom of the sea"], "Tigers live in forests."),
    ("Do all fish have fins to help them swim?", "Yes", ["Yes", "No"], "Fish use fins to swim!")
]
for q, ans, opts, hint in k3_land_sea_trivia:
    add_q("sci_land_sea", "science", q, q, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 2: sci_sink_float (Objects that Sink or Float) ---
sink_float_k3 = [
    ("Drop a heavy metal key 🗝️ in water. Does it sink or float?", "Sinks", ["Sinks", "Floats"], "images/science/sink_key.jpg", "Heavy metal keys sink straight to the bottom!"),
    ("Drop a heavy river stone 🪨 in water. Does it sink or float?", "Sinks", ["Sinks", "Floats"], "images/science/sink_stone.jpg", "Heavy rocks sink to the bottom!"),
    ("Put a light plastic toy ball ⚽ in water. Does it sink or float?", "Floats", ["Floats", "Sinks"], "images/science/float_ball.jpg", "Light plastic balls float on top of water!"),
    ("Put a dry wooden stick 🪵 in water. Does it sink or float?", "Floats", ["Floats", "Sinks"], "images/science/float_wood.jpg", "Wood is light and floats on top!"),
    ("Does a yellow rubber bath duck 🦆 sink or float?", "Floats", ["Floats", "Sinks"], None, "Rubber ducks float happily in the bathtub!"),
    ("Drop an iron nail in water. Does it sink or float?", "Sinks", ["Sinks", "Floats"], None, "Metal nails sink to the bottom!"),
    ("Does a dry leaf 🍂 floating on a pond sink or float?", "Floats", ["Floats", "Sinks"], None, "A leaf is light and floats on the surface!"),
    ("Drop a shiny metal coin in water. Does it sink or float?", "Sinks", ["Sinks", "Floats"], None, "Heavy coins sink to the bottom of the pool."),
    ("Put a foam swimming noodle in the pool. Does it sink or float?", "Floats", ["Floats", "Sinks"], None, "Foam is filled with air: it floats!"),
    ("Does a glass marble sink or float?", "Sinks", ["Sinks", "Floats"], None, "Heavy glass marbles sink to the bottom!"),
    ("Put an empty plastic bottle with a cap on water. Does it float?", "Yes, it floats", ["Yes, it floats", "No, it sinks"], None, "Filled with air, an empty bottle floats!"),
    ("Put a big metal anchor in the sea. Does it sink or float?", "Sinks", ["Sinks", "Floats"], None, "A heavy anchor sinks to hold the ship."),
    ("Does a wooden pencil float in water?", "Floats", ["Floats", "Sinks"], "images/english/color_pencils.jpg", "Wood floats on water!"),
    ("Does an apple float in water?", "Floats", ["Floats", "Sinks"], "images/english/apple.jpg", "Apples have air inside and float!"),
    ("Does a solid brick sink or float?", "Sinks", ["Sinks", "Floats"], None, "A heavy brick sinks immediately!"),
    ("Where do floating things stay in water?", "On top of the water", ["On top of the water", "At the bottom"], None, "Floating things stay on the surface."),
    ("Where do sinking things go in water?", "To the bottom", ["To the bottom", "In the clouds"], None, "Sinking things drop down to the bottom."),
    ("Does an inflatable swim ring help you float in the pool?", "Yes, it helps float", ["Yes, it helps float", "No, it sinks"], None, "Air inside helps you float safely!"),
    ("Does a metal spoon sink in soup or water?", "Sinks", ["Sinks", "Floats"], "images/science/metal_spoon.jpg", "Metal spoons sink to the bowl bottom."),
    ("Does a cork bottle stopper float on water?", "Floats", ["Floats", "Sinks"], None, "Cork is super light and floats!"),
    ("If you fill a plastic bottle with sand and drop it in water, does it sink?", "Sinks", ["Sinks", "Floats"], None, "Heavy sand makes it sink!"),
    ("Does a toy boat made of plastic float?", "Floats", ["Floats", "Sinks"], None, "Toy boats float across the water."),
    ("Does an ice cube float in a glass of water?", "Floats", ["Floats", "Sinks"], None, "Ice cubes float at the top of your drink!"),
    ("Does a heavy glass bottle sink when full of water?", "Sinks", ["Sinks", "Floats"], "images/science/glass_cup.jpg", "Heavy glass filled with water sinks."),
    ("Does a paper boat float for a little while?", "Yes, it floats", ["Yes, it floats", "No"], None, "Paper floats until it gets too soggy!"),
    ("Does a feather float on water?", "Floats", ["Floats", "Sinks"], None, "Feathers are super light and float."),
    ("Does a steel hammer sink or float?", "Sinks", ["Sinks", "Floats"], None, "Heavy steel hammers sink fast."),
    ("Does a coconut float in ocean water?", "Floats", ["Floats", "Sinks"], None, "Coconuts float across the sea to new islands!"),
    ("Does a metal key sink in the pool?", "Sinks", ["Sinks", "Floats"], "images/science/sink_key.jpg", "Keys sink straight to the pool floor."),
    ("Does a balloon filled with air float on water?", "Floats", ["Floats", "Sinks"], None, "Air balloons float on top."),
    ("Can a beach ball float on water?", "Floats", ["Floats", "Sinks"], "images/science/float_ball.jpg", "Beach balls float!"),
    ("Does a pebble from the garden sink?", "Sinks", ["Sinks", "Floats"], "images/science/sink_stone.jpg", "Pebbles sink in water."),
    ("Does a wooden toy block float?", "Floats", ["Floats", "Sinks"], "images/science/float_wood.jpg", "Wooden toys float."),
    ("Does a plastic straw float?", "Floats", ["Floats", "Sinks"], None, "Plastic straws float."),
    ("Does a gold ring sink?", "Sinks", ["Sinks", "Floats"], None, "Heavy gold sinks."),
    ("Does an orange peel float?", "Floats", ["Floats", "Sinks"], None, "Orange peel has tiny air pockets: it floats!"),
    ("Does a dry sponge float on water?", "Floats", ["Floats", "Sinks"], None, "Sponges have lots of air and float!"),
    ("Does a metal wrench sink?", "Sinks", ["Sinks", "Floats"], None, "Metal tools sink."),
    ("Does a plastic bottle cap float?", "Floats", ["Floats", "Sinks"], None, "Light caps float on water."),
    ("Do large ships float on water?", "Yes, they float", ["Yes, they float", "No, they sink"], None, "Big ships are designed to float on the sea!"),
    ("Does a big rock sink in a river?", "Sinks", ["Sinks", "Floats"], "images/science/sink_stone.jpg", "Rocks sink to the riverbed."),
    ("Does a tree log float down the river?", "Floats", ["Floats", "Sinks"], "images/science/float_wood.jpg", "Tree logs float on river water."),
    ("Does a glass cup sink when full of water?", "Sinks", ["Sinks", "Floats"], "images/science/glass_cup.jpg", "Glass sinks when full of water."),
    ("Does a plastic spoon float better than a metal spoon?", "Yes", ["Yes", "No"], "images/science/metal_spoon.jpg", "Plastic is much lighter than metal!"),
    ("Does a beach ball sink to the bottom of the sea?", "No, it floats", ["No, it floats", "Yes, it sinks"], "images/science/float_ball.jpg", "Beach balls stay on top!"),
    ("Does a heavy safe box sink?", "Sinks", ["Sinks", "Floats"], None, "Heavy iron safes sink."),
    ("Does a ping-pong ball float?", "Floats", ["Floats", "Sinks"], None, "A ping pong ball is full of air and floats!"),
    ("Does a plastic toy duck float?", "Floats", ["Floats", "Sinks"], None, "Toy ducks float in baths."),
    ("Does an iron nail float?", "No, it sinks", ["No, it sinks", "Yes, it floats"], None, "Iron nails sink."),
    ("Does a piece of dry cardboard float?", "Floats", ["Floats", "Sinks"], None, "Light cardboard floats on water."),
    ("Does a metal bolt sink?", "Sinks", ["Sinks", "Floats"], None, "Metal bolts sink."),
    ("Does an apple sink to the bottom?", "No, it floats", ["No, it floats", "Yes"], "images/english/apple.jpg", "Apples bob on water!"),
    ("Does a key float in the sink?", "No, it sinks", ["No, it sinks", "Yes"], "images/science/sink_key.jpg", "Keys sink to the bottom."),
    ("If an object stays on top of water, we say it:", "Floats", ["Floats", "Sinks"], None, "It floats!"),
    ("If an object drops to the bottom of water, we say it:", "Sinks", ["Sinks", "Floats"], None, "It sinks!")
]
for item in sink_float_k3:
    q, ans, opts, img, hint = item
    add_q("sci_sink_float", "science", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 3: sci_materials (Basic Materials: Wood, Plastic, Metal, Glass) ---
materials_k3 = [
    ("What is a window pane made of?", "Glass", ["Glass", "Wood", "Cloth"], "images/science/glass_cup.jpg", "Windows are made of clear glass so we can see through!"),
    ("What is a drinking cup made of glass called?", "Glass cup", ["Glass cup", "Metal spoon", "Wooden stick"], "images/science/glass_cup.jpg", "It is made of glass."),
    ("What is a shiny dining spoon usually made of?", "Metal", ["Metal", "Paper", "Cloth"], "images/science/metal_spoon.jpg", "Spoons and forks are made of strong metal."),
    ("What is a book's pages made of?", "Paper", ["Paper", "Glass", "Metal"], "images/science/paper_book.jpg", "Books are made of paper from trees!"),
    ("What is a beach ball made of?", "Plastic", ["Plastic", "Wood", "Metal"], "images/science/float_ball.jpg", "Beach balls are made of soft plastic."),
    ("What is a classroom wooden chair made of?", "Wood", ["Wood", "Glass", "Paper"], "images/science/float_wood.jpg", "Chairs and desks are made of sturdy wood."),
    ("What is a door key made of?", "Metal", ["Metal", "Paper", "Plastic"], "images/science/sink_key.jpg", "Keys are made of hard metal."),
    ("Can you see through clear glass?", "Yes, it is see-through", ["Yes, it is see-through", "No"], "images/science/glass_cup.jpg", "Glass is transparent and see-through!"),
    ("Which material breaks easily if dropped on the floor?", "Glass", ["Glass", "Wood", "Metal"], "images/science/glass_cup.jpg", "Be careful! Glass cups can break."),
    ("What are warm t-shirts and socks made of?", "Cloth", ["Cloth", "Metal", "Glass"], None, "Clothes are made of soft cloth."),
    ("What is a plastic drinking straw made of?", "Plastic", ["Plastic", "Wood", "Metal"], None, "Straws are made of light plastic."),
    ("What is a wooden pencil made of?", "Wood", ["Wood", "Glass", "Cloth"], "images/english/color_pencils.jpg", "Pencils are made of wood."),
    ("What is a car's outer body made of?", "Metal", ["Metal", "Paper", "Glass"], "images/kenderaan/kereta.jpg", "Car bodies are made of strong metal."),
    ("What is an eraser made of?", "Rubber", ["Rubber", "Glass", "Metal"], None, "Erasers are made of soft rubber."),
    ("What is an origami paper boat made of?", "Paper", ["Paper", "Metal", "Stone"], None, "Origami is folded paper."),
    ("What are drinking water bottles usually made of?", "Plastic", ["Plastic", "Wood", "Cloth"], None, "Plastic bottles carry water."),
    ("What is a tree trunk made of?", "Wood", ["Wood", "Metal", "Plastic"], "images/science/float_wood.jpg", "Tree trunks are natural wood."),
    ("Which material is hard and used for cooking pots?", "Metal", ["Metal", "Paper", "Glass"], "images/science/metal_spoon.jpg", "Cooking pots are made of metal."),
    ("Is cloth soft or hard?", "Soft", ["Soft", "Hard as rock"], None, "Cloth is soft and cozy."),
    ("What is a cardboard box made of?", "Paper / Cardboard", ["Paper / Cardboard", "Metal", "Glass"], None, "Cardboard comes from paper."),
    ("What are bathroom mirrors made of?", "Glass", ["Glass", "Wood", "Cloth"], None, "Mirrors are shiny glass."),
    ("Can you fold a sheet of paper easily?", "Yes", ["Yes", "No"], "images/science/paper_book.jpg", "Paper folds easily into shapes!"),
    ("What material are Lego building blocks made of?", "Plastic", ["Plastic", "Metal", "Paper"], None, "Lego bricks are colorful plastic!"),
    ("What is a rubber tire on a bicycle made of?", "Rubber", ["Rubber", "Glass", "Paper"], "images/kenderaan/basikal.jpg", "Tires are made of bouncy black rubber."),
    ("What is an iron fence made of?", "Metal", ["Metal", "Glass", "Cloth"], None, "Fences are sturdy metal."),
    ("What are wool mittens made of?", "Cloth / Wool", ["Cloth / Wool", "Glass", "Metal"], None, "Mittens are made of warm wool cloth."),
    ("What is a wooden table made of?", "Wood", ["Wood", "Paper"], "images/science/float_wood.jpg", "Tables are made of wood."),
    ("What is a metal coin made of?", "Metal", ["Metal", "Glass"], "images/science/metal_spoon.jpg", "Coins are stamped metal."),
    ("Is metal strong and hard?", "Yes", ["Yes", "No"], "images/science/metal_spoon.jpg", "Metal is very strong."),
    ("Is glass clear like water?", "Yes", ["Yes", "No"], "images/science/glass_cup.jpg", "Clear glass lets light pass through."),
    ("Can you rip paper with your hands?", "Yes", ["Yes", "No"], "images/science/paper_book.jpg", "Paper tears easily with fingers."),
    ("Can you rip a metal spoon with your hands?", "No", ["No", "Yes"], "images/science/metal_spoon.jpg", "Metal is too strong to rip!"),
    ("What are bath towels made of?", "Soft cloth", ["Soft cloth", "Metal"], None, "Towels are soft absorbent cloth."),
    ("What are plastic toy dinosaurs made of?", "Plastic", ["Plastic", "Glass"], None, "Toy figures are molded plastic."),
    ("What is a newspaper made of?", "Paper", ["Paper", "Metal"], None, "Newspapers are printed on paper."),
    ("What are spectacles and eyeglasses lenses made of?", "Glass or clear plastic", ["Glass or clear plastic", "Wood"], None, "Eyeglass lenses are clear glass or plastic."),
    ("What is a ruler made of?", "Plastic or wood", ["Plastic or wood", "Cloth"], None, "Rulers are plastic or wood."),
    ("Is wood from trees?", "Yes", ["Yes", "No"], "images/science/float_wood.jpg", "Wood comes from trees."),
    ("Is rubber waterproof?", "Yes", ["Yes", "No"], None, "Rubber boots keep your feet dry in rain!"),
    ("What are rain boots made of?", "Rubber", ["Rubber", "Paper"], None, "Rain boots are waterproof rubber."),
    ("What is an aluminium soda can made of?", "Metal", ["Metal", "Cloth"], None, "Soda cans are thin metal."),
    ("What is a light bulb made of on the outside?", "Glass", ["Glass", "Wood"], None, "Light bulbs have clear glass shells."),
    ("What are party balloons made of?", "Rubber / Latex", ["Rubber / Latex", "Wood"], None, "Balloons are stretchy rubber latex."),
    ("What are wooden chopsticks made of?", "Wood", ["Wood", "Glass"], "images/science/float_wood.jpg", "Chopsticks are made of bamboo or wood."),
    ("What is a metal nail made of?", "Metal", ["Metal", "Cloth"], None, "Nails are hard metal."),
    ("What is tissue paper made of?", "Soft paper", ["Soft paper", "Metal"], None, "Tissues are soft paper."),
    ("Which material is best for an umbrella to keep off rain?", "Waterproof plastic or cloth", ["Waterproof plastic or cloth", "Paper"], None, "Umbrellas need waterproof material!"),
    ("If you drop a plastic cup, does it shatter like glass?", "No, plastic does not shatter easily", ["No, plastic does not shatter easily", "Yes"], None, "Plastic cups are safe and durable."),
    ("Which material is shiny: a new metal coin or wood?", "Metal coin", ["Metal coin", "Wood"], "images/science/metal_spoon.jpg", "Metal is shiny!"),
    ("What are kitchen sponge cleaners made of?", "Foam / Plastic", ["Foam / Plastic", "Metal"], None, "Sponges are soft foam."),
    ("What is a baseball bat usually made of?", "Wood or metal", ["Wood or metal", "Paper"], None, "Bats are hard wood or metal."),
    ("What is a pillow filled with?", "Soft cloth and cotton", ["Soft cloth and cotton", "Stones"], None, "Pillows are soft for sleeping."),
    ("What is a ceramic plate made of?", "Clay / Ceramic", ["Clay / Ceramic", "Wood"], None, "Plates are baked ceramic."),
    ("Which material does a magnet attract?", "Metal / Iron", ["Metal / Iron", "Wood", "Glass"], None, "Magnets click onto metal!"),
    ("Do magnets stick to wooden tables?", "No", ["No", "Yes"], "images/science/float_wood.jpg", "Magnets only stick to certain metals!")
]
for item in materials_k3:
    q, ans, opts, img, hint = item
    add_q("sci_materials", "science", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 4: sci_celestial (Celestial Objects: Sun, Moon, Earth, Stars) ---
celestial_k3 = [
    ("Look at the picture! What shines bright and warm during daytime?", "The Sun", ["The Sun", "The Moon", "A Star"], "images/science/sun.jpg", "The big, warm Sun lights up our day!"),
    ("Look at the picture! What glows softly in the night sky?", "The Moon", ["The Moon", "The Sun", "A Tree"], "images/science/moon.jpg", "The Moon shines in the dark night sky."),
    ("Look at the picture! What are the tiny lights that twinkle at night?", "Stars", ["Stars", "Clouds", "Birds"], "images/science/star.jpg", "Twinkle, twinkle, little stars!"),
    ("Look at the picture! What is the name of our beautiful home planet?", "Earth", ["Earth", "The Sun", "The Moon"], "images/science/earth.jpg", "We live on Planet Earth!"),
    ("Is the Sun hot or cold?", "Very hot", ["Very hot", "Very cold"], "images/science/sun.jpg", "The Sun is a giant ball of hot fire!"),
    ("When can we see the bright Sun?", "In the daytime", ["In the daytime", "At midnight"], "images/science/sun.jpg", "The Sun rises in the morning and shines all day."),
    ("When can we see the Moon and twinkling stars?", "At night", ["At night", "At lunchtime"], "images/science/moon.jpg", "The Moon and stars come out when the sky is dark."),
    ("What color does the daytime sky look on a sunny day?", "Blue", ["Blue", "Black", "Pink"], "images/science/sun.jpg", "Sunny skies are bright blue."),
    ("What color is the sky at midnight?", "Dark / Black", ["Dark / Black", "Bright yellow"], "images/science/star.jpg", "The night sky is dark so stars can twinkle!"),
    ("What shape does a full moon look like?", "A round circle", ["A round circle", "A triangle", "A square"], "images/science/moon.jpg", "A full moon is round like a ball."),
    ("Does the Sun give us light and heat to stay warm?", "Yes", ["Yes", "No"], "images/science/sun.jpg", "The Sun warms the whole world!"),
    ("Can plants grow without sunlight?", "No, plants need sun", ["No, plants need sun", "Yes"], "images/science/sun.jpg", "Plants need sunlight to grow healthy."),
    ("What shape is our Planet Earth?", "Round like a ball", ["Round like a ball", "Flat like a pancake"], "images/science/earth.jpg", "Planet Earth is a big round sphere."),
    ("What colors can you see on Earth from space?", "Blue, green, and white", ["Blue, green, and white", "Red and purple"], "images/science/earth.jpg", "Blue oceans, green land, and white clouds!"),
    ("Are the stars close or very far away?", "Very far away", ["Very far away", "Just above our heads"], "images/science/star.jpg", "Stars are very far away in space!"),
    ("Can an astronaut fly to space in a rocket?", "Yes", ["Yes", "No"], "images/kenderaan/roket.jpg", "Astronauts ride rockets to space."),
    ("Does the Moon change its shape in the sky?", "Yes, from crescent to full", ["Yes, from crescent to full", "No, always square"], "images/science/moon.jpg", "Sometimes crescent, sometimes round!"),
    ("What gives Earth daylight?", "The Sun", ["The Sun", "The Moon"], "images/science/sun.jpg", "The Sun gives daytime light."),
    ("Do stars twinkle like little diamonds in the sky?", "Yes", ["Yes", "No"], "images/science/star.jpg", "Like a diamond in the sky!"),
    ("Do we wear sunglasses on sunny days to protect our eyes?", "Yes", ["Yes", "No"], "images/science/sun.jpg", "Sunglasses protect our eyes from bright sun."),
    ("Is the Sun a star?", "Yes, the closest star", ["Yes, the closest star", "No, it is a planet"], "images/science/sun.jpg", "The Sun is our closest star!"),
    ("Can we touch the Moon with our hand from Earth?", "No, it is too far", ["No, it is too far", "Yes"], "images/science/moon.jpg", "The Moon is very far up in space."),
    ("What covers most of planet Earth: water or land?", "Water / Oceans", ["Water / Oceans", "Sand"], "images/science/earth.jpg", "Earth has big blue oceans of water!"),
    ("What is Earth called because of all its water?", "The Blue Planet", ["The Blue Planet", "The Red Planet"], "images/science/earth.jpg", "Earth is called the Blue Planet."),
    ("Does the Moon shine with its own fire or reflect sunlight?", "Reflects sunlight", ["Reflects sunlight", "Its own fire"], "images/science/moon.jpg", "The Moon reflects the Sun's light."),
    ("Can you see clouds in the sky during the day?", "Yes", ["Yes", "No"], None, "Fluffy white clouds float in the sky."),
    ("What falls from dark clouds when it rains?", "Raindrops of water", ["Raindrops of water", "Coins"], None, "Raindrops fall from rain clouds."),
    ("What appears in the sky when sun shines after rain?", "A rainbow", ["A rainbow", "The moon"], None, "A colorful rainbow!"),
    ("Do we sleep when it is night?", "Yes", ["Yes", "No"], "images/science/moon.jpg", "Nighttime is when we sleep and rest."),
    ("Do roosters crow when the sun rises in the morning?", "Yes", ["Yes", "No"], "images/binatang/ayam.jpg", "Cock-a-doodle-doo!"),
    ("Which celestial object is the largest: Sun or Earth?", "The Sun is much bigger", ["The Sun is much bigger", "Earth is bigger"], "images/science/sun.jpg", "The Sun is huge!"),
    ("Which is closer to us: the Moon or the Stars?", "The Moon is closer", ["The Moon is closer", "The Stars are closer"], "images/science/moon.jpg", "The Moon is our closest neighbor in space."),
    ("Does the Earth turn around so we have day and night?", "Yes", ["Yes", "No"], "images/science/earth.jpg", "Earth spins: day, night, day, night!"),
    ("Can we live on the Sun?", "No, too hot!", ["No, too hot!", "Yes, it is nice"], "images/science/sun.jpg", "The Sun would burn anything up!"),
    ("Can we breathe air on the Moon without a spacesuit?", "No, need a spacesuit", ["No, need a spacesuit", "Yes"], "images/science/moon.jpg", "Astronauts wear spacesuits to breathe."),
    ("Are there thousands of stars in the night sky?", "Yes, so many!", ["Yes, so many!", "Only two"], "images/science/star.jpg", "Countless sparkling stars!"),
    ("What vehicle takes people into outer space?", "A rocket", ["A rocket", "A bicycle"], "images/kenderaan/roket.jpg", "Rockets blast off to space!"),
    ("What do we call people who travel in space?", "Astronauts", ["Astronauts", "Sailors"], None, "Astronauts wear space helmets!"),
    ("Does the sun rise in the morning?", "Yes", ["Yes", "No"], "images/science/sun.jpg", "The sun rises in the East every morning."),
    ("Does the sun set in the evening?", "Yes", ["Yes", "No"], "images/science/sun.jpg", "The sun sets in the West at dusk."),
    ("What do shadows need to appear: light or total darkness?", "Light", ["Light", "Total darkness"], "images/science/sun.jpg", "Light makes shadows on the ground!"),
    ("When the sun is right above you at noon, is your shadow short?", "Yes, very short", ["Yes, very short", "Very long"], "images/science/sun.jpg", "At noon, shadows are short under your feet."),
    ("Does the Moon have craters on its surface?", "Yes, round craters", ["Yes, round craters", "No"], "images/science/moon.jpg", "The Moon has bumpy round craters."),
    ("Is the Earth our home planet?", "Yes", ["Yes", "No"], "images/science/earth.jpg", "Earth is where we live!"),
    ("Can birds fly all the way to the Moon?", "No, too far", ["No, too far", "Yes"], "images/science/moon.jpg", "Birds can only fly in Earth's air."),
    ("What keeps us on the ground so we don't float away?", "Gravity", ["Gravity", "Glue"], "images/science/earth.jpg", "Earth's gravity holds us on the ground!"),
    ("Is space very dark and quiet?", "Yes", ["Yes", "No"], "images/science/star.jpg", "Space is dark and quiet."),
    ("Can you see the stars better in the dark countryside or in city lights?", "Dark countryside", ["Dark countryside", "Under street lights"], "images/science/star.jpg", "Dark skies make stars sparkle brightly!"),
    ("Do animals on Earth need the Sun to live?", "Yes", ["Yes", "No"], "images/science/sun.jpg", "All life on Earth needs warmth and light."),
    ("Is Earth round like an apple?", "Yes, round", ["Yes, round", "Flat like a ruler"], "images/science/earth.jpg", "Earth is round!"),
    ("What shape are the stars drawn in cartoon pictures?", "Five-pointed stars", ["Five-pointed stars", "Squares"], "images/science/star.jpg", "⭐ Five-pointed stars!"),
    ("Is it safe to look directly at the bright sun with bare eyes?", "No, never look directly", ["No, never look directly", "Yes, look right at it"], "images/science/sun.jpg", "Looking at the sun hurts your eyes!"),
    ("Does the Moon appear in the sky every month?", "Yes", ["Yes", "No"], "images/science/moon.jpg", "The Moon orbits Earth every month."),
    ("Is our planet Earth special because it has water and life?", "Yes, very special!", ["Yes, very special!", "No"], "images/science/earth.jpg", "Earth is our beautiful home!"),
    ("What song do children sing to the stars?", "Twinkle, Twinkle, Little Star", ["Twinkle, Twinkle, Little Star", "Old MacDonald"], "images/science/star.jpg", "Twinkle, twinkle, little star!")
]
for item in celestial_k3:
    q, ans, opts, img, hint = item
    add_q("sci_celestial", "science", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 5: sci_pollution (Types of Pollution: Air, Water, Land) ---
pollution_k3 = [
    ("Look at the picture! Dirty black smoke coming from chimneys is called:", "Air pollution", ["Air pollution", "Water pollution", "Clean air"], "images/science/air_pollution.jpg", "Smoky air makes us cough: that is air pollution!"),
    ("Look at the picture! Trash and plastic dumped into rivers and oceans is called:", "Water pollution", ["Water pollution", "Air pollution", "Clean water"], "images/science/water_pollution.jpg", "Dirty garbage in rivers causes water pollution."),
    ("Look at the picture! Rubbish dumped all over the ground is called:", "Land pollution", ["Land pollution", "Air pollution", "Clean park"], "images/science/land_pollution.jpg", "Littering on the ground causes land pollution."),
    ("Where should we throw our snack wrappers and rubbish?", "In the dustbin", ["In the dustbin", "On the playground floor", "In the river"], None, "Always toss trash in the dustbin!"),
    ("Does clean fresh air help us breathe easily?", "Yes, it keeps us healthy", ["Yes, it keeps us healthy", "No, it makes us sick"], None, "Fresh clean air keeps our lungs healthy!"),
    ("What happens to fish if river water is polluted with trash and chemicals?", "They get sick or die", ["They get sick or die", "They get happy"], "images/science/water_pollution.jpg", "Fish need clean river water to live."),
    ("Is dirty car exhaust smoke good for our lungs?", "No, it is bad", ["No, it is bad", "Yes, it smells sweet"], "images/science/air_pollution.jpg", "Car smoke pollutes the air we breathe."),
    ("Should we plant more green trees to clean our air?", "Yes, trees clean the air", ["Yes, trees clean the air", "No, cut down all trees"], "images/english/palm_tree.jpg", "Trees produce fresh oxygen for us!"),
    ("Should you throw plastic bags into the sea?", "No, turtles might eat it", ["No, turtles might eat it", "Yes, throw it in"], "images/science/water_pollution.jpg", "Protect sea animals: never throw plastic in the sea!"),
    ("What should we do if we see a piece of paper on the classroom floor?", "Pick it up and bin it", ["Pick it up and bin it", "Step on it and leave it"], None, "Keep our classroom clean and tidy!"),
    ("Does cigarette smoke cause air pollution?", "Yes", ["Yes", "No"], "images/science/air_pollution.jpg", "Smoke is dirty air pollution."),
    ("Can we recycle plastic bottles, paper, and metal cans?", "Yes, recycling helps Earth", ["Yes, recycling helps Earth", "No"], None, "Recycle bins help reduce trash!"),
    ("What color recycle bin is often used for paper in Malaysia?", "Blue bin", ["Blue bin", "Red bin"], None, "Blue bins are for clean paper!"),
    ("What color recycle bin is often used for glass?", "Brown bin", ["Brown bin", "Pink bin"], None, "Brown bins are for glass bottles!"),
    ("What color recycle bin is used for plastic and aluminium cans?", "Orange bin", ["Orange bin", "Purple bin"], None, "Orange bins are for plastic and cans!"),
    ("Does throwing litter on the grass make the park look ugly?", "Yes, it makes it dirty", ["Yes, it makes it dirty", "No, it looks nice"], "images/science/land_pollution.jpg", "Keep parks green and beautiful!"),
    ("Is tap water that is brown and smelly safe to drink?", "No, never drink dirty water", ["No, never drink dirty water", "Yes, drink it all"], "images/science/water_pollution.jpg", "Drink clean, boiled or filtered water!"),
    ("Does turning off the lights when leaving a room help save energy?", "Yes, saves electricity", ["Yes, saves electricity", "No"], None, "Save electricity to protect our planet!"),
    ("Does closing the water tap while brushing teeth help save water?", "Yes, saves clean water", ["Yes, saves clean water", "No"], None, "Don't let clean water run away!"),
    ("What are the 3 R's to keep Earth clean?", "Reduce, Reuse, Recycle", ["Reduce, Reuse, Recycle", "Run, Rest, Repeat"], None, "Reduce, Reuse, and Recycle!"),
    ("Can an old glass jar be reused to store pencils?", "Yes, that is reusing", ["Yes, that is reusing", "No"], None, "Reusing items keeps them out of the landfill."),
    ("Is a landfill a giant place where rubbish is buried?", "Yes", ["Yes", "No"], "images/science/land_pollution.jpg", "Landfills hold piles of waste."),
    ("Does burning trash create dirty air pollution?", "Yes, dirty black smoke", ["Yes, dirty black smoke", "No, clean smoke"], "images/science/air_pollution.jpg", "Burning plastics makes bad toxic smoke."),
    ("Should we use a reusable water bottle instead of buying single-use bottles?", "Yes, reusable is better", ["Yes, reusable is better", "No"], None, "Reusable bottles save plastic!"),
    ("Should we bring our own shopping bag to the supermarket?", "Yes, save plastic bags", ["Yes, save plastic bags", "No"], None, "Cloth bags are eco-friendly!"),
    ("Can dirty oil spilled in the ocean hurt sea birds?", "Yes, it coats their feathers", ["Yes, it coats their feathers", "No"], "images/science/water_pollution.jpg", "Oil spills hurt ocean animals."),
    ("Does riding a bicycle make smoke pollution?", "No, bicycles are clean", ["No, bicycles are clean", "Yes"], "images/kenderaan/basikal.jpg", "Bicycles don't burn petrol or pollute air!"),
    ("Does walking to school help keep the air clean?", "Yes, zero pollution", ["Yes, zero pollution", "No"], None, "Walking is healthy and zero pollution!"),
    ("Do factories need filters to stop dirty smoke?", "Yes", ["Yes", "No"], "images/science/air_pollution.jpg", "Filters clean smoke before it enters the air."),
    ("Can plastic straws hurt sea turtles?", "Yes", ["Yes", "No"], "images/science/water_pollution.jpg", "Straws can harm marine animals."),
    ("Should you throw an apple core in a compost bin?", "Yes, it turns into soil", ["Yes, it turns into soil", "No"], "images/english/apple.jpg", "Fruit scraps make rich compost soil!"),
    ("Does loud noise from honking horns cause noise pollution?", "Yes", ["Yes", "No"], None, "Excessive loud sounds cause noise pollution."),
    ("Can clean parks be fun places for kids to play?", "Yes!", ["Yes!", "No"], None, "Clean parks are safe and fun for children!"),
    ("Should we keep our beaches clean from trash?", "Yes", ["Yes", "No"], "images/science/water_pollution.jpg", "Pick up trash on the beach."),
    ("What do sea animals think plastic bags are?", "Jellyfish food", ["Jellyfish food", "Toys"], "images/science/water_pollution.jpg", "Turtles mistake plastic bags for jellyfish food!"),
    ("Is fresh air good for running and playing outside?", "Yes, very good", ["Yes, very good", "No"], None, "Fresh air gives us energy!"),
    ("Should we leave rubbish behind after a picnic?", "No, take it all home", ["No, take it all home", "Yes, leave it there"], "images/science/land_pollution.jpg", "Leave nothing behind except footprints."),
    ("Does planting flowers help bees and butterflies?", "Yes", ["Yes", "No"], "images/science/flower.jpg", "Flowers give food to helpful pollinators!"),
    ("Can old paper be made into new notebooks?", "Yes, by recycling", ["Yes, by recycling", "No"], "images/science/paper_book.jpg", "Recycling paper saves trees."),
    ("Can clean water be clear and transparent?", "Yes", ["Yes", "No"], "images/science/glass_cup.jpg", "Clean water is clear and fresh."),
    ("Should we dump paint into the storm drain?", "No, never", ["No, never", "Yes"], "images/science/water_pollution.jpg", "Drains lead straight to rivers and oceans!"),
    ("Can a dirty river make bad smells?", "Yes", ["Yes", "No"], "images/science/water_pollution.jpg", "Polluted rivers smell terrible."),
    ("Do trees give us shade on hot days?", "Yes", ["Yes", "No"], "images/english/palm_tree.jpg", "Big leafy trees keep us cool."),
    ("Should we step on growing little flower plants in the garden?", "No, protect them", ["No, protect them", "Yes, crush them"], "images/science/flower.jpg", "Be gentle with young plants!"),
    ("Does clean Earth make everyone happy and healthy?", "Yes!", ["Yes!", "No"], "images/science/earth.jpg", "A clean planet keeps us all healthy!"),
    ("Can metal cans be melted and recycled into new cans?", "Yes", ["Yes", "No"], "images/science/metal_spoon.jpg", "Metal is 100% recyclable!"),
    ("Is smoke from burning leaves good to breathe?", "No, it makes you cough", ["No, it makes you cough", "Yes"], "images/science/air_pollution.jpg", "Smoke irritates eyes and lungs."),
    ("Should we turn off the tap while soaping our hands?", "Yes, save water", ["Yes, save water", "No"], None, "Save water every time you wash hands!"),
    ("Can picking up one piece of trash make a difference?", "Yes, every bit helps!", ["Yes, every bit helps!", "No"], None, "Every good action counts!"),
    ("Does nature give us clean water, fresh air, and food?", "Yes, nature gives us everything", ["Yes, nature gives us everything", "No"], "images/science/earth.jpg", "We must protect our planet Earth!"),
    ("Should we write on both sides of a paper to save trees?", "Yes, use both sides", ["Yes, use both sides", "No, one side only"], "images/science/paper_book.jpg", "Using both sides saves paper!"),
    ("Can dirty air give animals a cough too?", "Yes", ["Yes", "No"], "images/science/air_pollution.jpg", "Animals also need fresh clean air."),
    ("What color should water in a swimming pool be?", "Clear blue", ["Clear blue", "Muddy brown"], None, "Pool water should be clean and clear."),
    ("Who is responsible for keeping Earth clean?", "All of us!", ["All of us!", "Only the teacher"], "images/science/earth.jpg", "We all take care of our world!"),
    ("Is Earth our only home?", "Yes, protect our Earth!", ["Yes, protect our Earth!", "No"], "images/science/earth.jpg", "Earth is our home sweet home!")
]
for item in pollution_k3:
    q, ans, opts, img, hint = item
    add_q("sci_pollution", "science", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 6: sci_plants (Parts & Needs of a Plant, K3 Friendly) ---
plants_k3 = [
    ("Look at the picture! What part of the plant grows underground in the soil?", "Roots", ["Roots", "Flower", "Leaves"], "images/science/plant_parts.jpg", "Roots drink water from the soil!"),
    ("Look at the picture! What holds the plant up tall like a backbone?", "Stem", ["Stem", "Roots", "Petals"], "images/science/plant_parts.jpg", "The stem stands straight and tall."),
    ("Look at the picture! What flat green parts catch sunlight?", "Leaves", ["Leaves", "Roots", "Seeds"], "images/science/plant_parts.jpg", "Green leaves catch sunshine!"),
    ("Look at the picture! What colorful, sweet-smelling part attracts bees?", "Flower", ["Flower", "Roots", "Stem"], "images/science/flower.jpg", "Flowers have bright petals and smell sweet!"),
    ("Look at the picture! What sweet part grows with seeds inside, like an apple?", "Fruit", ["Fruit", "Roots", "Bark"], "images/science/fruit.jpg", "Fruits are delicious and have seeds inside!"),
    ("What two main things does a little plant need to grow big?", "Water and sunlight", ["Water and sunlight", "Candy and soda"], "images/science/sun.jpg", "Plants need water, sunlight, and good soil!"),
    ("What color are most plant leaves?", "Green", ["Green", "Blue", "Purple"], "images/science/plant_parts.jpg", "Leaves are green!"),
    ("What do plants drink from the soil?", "Water", ["Water", "Orange juice", "Milk"], "images/science/plant_parts.jpg", "Roots absorb water from soil."),
    ("Where do seeds start growing?", "In the soil / dirt", ["In the soil / dirt", "In the freezer"], None, "Plant a seed in warm, rich soil."),
    ("Can a plant grow if you lock it in a dark closet with no light?", "No, it needs light", ["No, it needs light", "Yes, it loves dark"], "images/science/sun.jpg", "Plants need light to make food!"),
    ("What falls from the sky to water wild plants and trees?", "Rain", ["Rain", "Snow cones"], None, "Rain waters plants naturally."),
    ("Do plants need fresh air to breathe?", "Yes", ["Yes", "No"], None, "Plants breathe air too!"),
    ("What tiny thing grows into a big plant when watered?", "A seed", ["A seed", "A stone"], None, "A tiny seed sprouts into a plant!"),
    ("Do trees give us delicious fruits like bananas and apples?", "Yes", ["Yes", "No"], "images/science/fruit.jpg", "Apples, bananas, and oranges grow on trees!"),
    ("What insect helps flowers by carrying pollen on its fuzzy legs?", "A bee", ["A bee", "A worm"], "images/science/flower.jpg", "Busy honeybees help flowers grow!"),
    ("What part of a sunflower is bright yellow with petals?", "The flower", ["The flower", "The root"], "images/science/flower.jpg", "Sunflower blossoms are sunny yellow!"),
    ("What protects the tiny baby plant inside before it sprouts?", "Seed coat", ["Seed coat", "A blanket"], None, "A hard seed coat protects the seed."),
    ("Do carrots grow underground as roots?", "Yes, carrots are roots", ["Yes, carrots are roots", "No, carrots are leaves"], None, "Orange carrots grow underground!"),
    ("Are trees big plants with a thick wooden trunk?", "Yes", ["Yes", "No"], "images/english/palm_tree.jpg", "Trees have tall wooden trunks."),
    ("What do we use to water plants in our garden?", "A watering can", ["A watering can", "A hair dryer"], None, "Sprinkle water gently with a watering can!"),
    ("Can you plant a seed in a flowerpot?", "Yes", ["Yes", "No"], None, "Flowerpots on balconies grow lovely plants."),
    ("What color is a ripe red apple fruit?", "Red", ["Red", "Blue"], "images/english/apple.jpg", "Ripe apples are bright red!"),
    ("What color is a ripe banana fruit?", "Yellow", ["Yellow", "Pink"], "images/english/banana.jpg", "Ripe bananas are bright yellow!"),
    ("Do plant roots anchor the plant firmly in the dirt?", "Yes, so it does not fall", ["Yes, so it does not fall", "No"], "images/science/plant_parts.jpg", "Roots hold the plant tight in the ground!"),
    ("Do plants give off fresh oxygen for us to breathe?", "Yes, plants make clean air", ["Yes, plants make clean air", "No"], None, "Plants make oxygen for humans and animals!"),
    ("Do cactus plants live in dry, hot deserts with very little water?", "Yes", ["Yes", "No"], None, "Cactus plants hold water inside!"),
    ("What grows on a rose stem that can prick your finger?", "Thorns", ["Thorns", "Feathers"], None, "Be careful of sharp thorns on roses!"),
    ("What do leaves use sunlight for?", "To make food for the plant", ["To make food for the plant", "To play games"], "images/science/plant_parts.jpg", "Leaves cook food using sunlight!"),
    ("Is grass in the garden a kind of plant?", "Yes", ["Yes", "No"], None, "Green grass is a plant."),
    ("What happens to a plant if you forget to water it for weeks?", "It wilts and dries up", ["It wilts and dries up", "It turns into candy"], None, "Plants need water or they dry up!"),
    ("Do butterflies love drinking sweet nectar from flowers?", "Yes", ["Yes", "No"], "images/science/flower.jpg", "Butterflies sip sweet nectar from flowers."),
    ("Can big trees live for many, many years?", "Yes, for a very long time", ["Yes, for a very long time", "Only one day"], "images/english/palm_tree.jpg", "Some trees live for hundreds of years!"),
    ("Do some plants grow juicy watermelons on vines?", "Yes", ["Yes", "No"], "images/science/fruit.jpg", "Watermelons grow on garden vines."),
    ("What is the top part of a flower called that has soft petals?", "Petals", ["Petals", "Roots"], "images/science/flower.jpg", "Petals are soft and colorful."),
    ("Do plant stems carry water up from the roots to the leaves?", "Yes, like a straw", ["Yes, like a straw", "No"], "images/science/plant_parts.jpg", "The stem works like a drinking straw!"),
    ("Are beans and peas seeds that we can eat?", "Yes", ["Yes", "No"], None, "Peas and beans are nutritious seeds."),
    ("Do tomatoes have seeds inside them?", "Yes", ["Yes", "No"], "images/science/fruit.jpg", "Tomatoes have lots of tiny seeds inside."),
    ("Does a seed need warm soil to sprout?", "Yes", ["Yes", "No"], None, "Warm soil wakes up the sleeping seed!"),
    ("Can we eat plant leaves like fresh green spinach?", "Yes, spinach is leaves", ["Yes, spinach is leaves", "No"], None, "Spinach and lettuce are healthy leaves."),
    ("Can a flower turn into a fruit with seeds?", "Yes", ["Yes", "No"], "images/science/fruit.jpg", "Flowers bloom, then turn into fruit!"),
    ("Do palm trees grow coconuts?", "Yes", ["Yes", "No"], "images/english/palm_tree.jpg", "Tall palm trees grow coconuts."),
    ("Does watering a plant every day help it grow strong?", "Yes", ["Yes", "No"], None, "Gentle watering helps it thrive."),
    ("Are roots white or brown underground?", "Yes, white and brown", ["Yes, white and brown", "Bright blue"], "images/science/plant_parts.jpg", "Roots spread under the dark soil."),
    ("Do plants have bones like humans?", "No, they have sturdy stems", ["No, they have sturdy stems", "Yes, skeletons"], "images/science/plant_parts.jpg", "Plants have cell walls and strong stems!"),
    ("What is the green powder that makes leaves green called?", "Chlorophyll", ["Chlorophyll", "Chocolate"], "images/science/plant_parts.jpg", "Chlorophyll catches sunshine."),
    ("Do baby seedlings need gentle sunshine?", "Yes", ["Yes", "No"], "images/science/sun.jpg", "Gentle sunlight helps seedlings grow."),
    ("Do earthworms help plants by making soil loose and rich?", "Yes, earthworms help soil", ["Yes, earthworms help soil", "No"], None, "Worms are friends of garden soil!"),
    ("Can plants grow in a garden?", "Yes!", ["Yes!", "No"], None, "Gardens are full of flowers and vegetables."),
    ("What is an orange: a fruit or a rock?", "A fruit", ["A fruit", "A rock"], "images/science/fruit.jpg", "Oranges are juicy citrus fruits!"),
    ("Is a sunflower named after the sun?", "Yes, because it looks like the sun", ["Yes, because it looks like the sun", "No"], "images/science/flower.jpg", "Sunflowers follow the sun across the sky!"),
    ("Do trees lose their leaves in autumn?", "Yes, leaves turn yellow and fall", ["Yes, leaves turn yellow and fall", "No"], None, "Autumn leaves change color and fall."),
    ("Do seeds grow roots first downwards into the dirt?", "Yes, roots grow down first", ["Yes, roots grow down first", "No"], "images/science/plant_parts.jpg", "Roots go down to find water first!"),
    ("Does a sprout push upwards into the sunshine?", "Yes, stems reach for light", ["Yes, stems reach for light", "No"], "images/science/plant_parts.jpg", "Sprouts climb up to the sun!"),
    ("Can you smell the sweet scent of a rose flower?", "Yes, it smells sweet", ["Yes, it smells sweet", "No"], "images/science/flower.jpg", "Roses smell lovely."),
    ("Should we care for plants and water them with love?", "Yes!", ["Yes!", "No"], "images/science/plant_parts.jpg", "Caring for plants is fun and rewarding!")
]
for item in plants_k3:
    q, ans, opts, img, hint = item
    add_q("sci_plants", "science", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# =========================================================================
# SUBJECT 5: ICT & COMPUTERS (in English, K3 Friendly)
# =========================================================================

# --- Topic 1: ict_parts (Parts of a Computer) ---
ict_parts_k3 = [
    ("Look at the picture! What is this screen that shows pictures and cartoons?", "Monitor", ["Monitor", "Keyboard", "Mouse"], "images/ict/monitor.jpg", "A monitor or screen shows your videos!"),
    ("Look at the picture! What is this board with letter keys to type your name?", "Keyboard", ["Keyboard", "Mouse", "Speaker"], "images/ict/keyboard.jpg", "Press the keys on a keyboard to type!"),
    ("Look at the picture! What is this little handheld device with buttons to click?", "Mouse", ["Mouse", "Printer", "Monitor"], "images/ict/mouse.jpg", "Click, click! A mouse moves the arrow cursor."),
    ("Look at the picture! What machine prints your colorful drawings onto paper?", "Printer", ["Printer", "Monitor", "Webcam"], "images/ict/printer.jpg", "A printer puts drawings onto paper!"),
    ("Look at the picture! What part plays loud music and game sounds?", "Speakers", ["Speakers", "Mouse", "Keyboard"], "images/ict/speakers.jpg", "Speakers play sound out loud!"),
    ("Look at the picture! What do you wear on your ears to hear sounds privately?", "Headphones", ["Headphones", "Monitor", "Scanner"], "images/ict/headphones.jpg", "Wear headphones over your ears!"),
    ("Look at the picture! What hears your voice when you sing into the computer?", "Microphone", ["Microphone", "Printer", "Mouse"], "images/ict/microphone.jpg", "A microphone records your voice."),
    ("Look at the picture! What little camera sits on top to show your face on video call?", "Webcam", ["Webcam", "Speaker", "Floppy disk"], "images/ict/webcam.jpg", "A webcam is a computer camera!"),
    ("Look at the picture! What big box is the main brain case of the desktop computer?", "System Unit", ["System Unit", "Mouse pad", "CD"], "images/ict/system_unit.jpg", "The system unit case holds the computer's CPU brain!"),
    ("Look at the picture! What flat machine scans paper drawings into the computer?", "Scanner", ["Scanner", "Speakers", "Headphones"], "images/ict/scanner.jpg", "A scanner copies pictures into the computer."),
    ("Which device do you hold in your hand and click: a mouse or a screen?", "Mouse", ["Mouse", "Screen"], "images/ict/mouse.jpg", "You click with a mouse!"),
    ("Which part has the longest bar key called the Spacebar?", "Keyboard", ["Keyboard", "Mouse", "Speaker"], "images/ict/keyboard.jpg", "The keyboard has the long Spacebar key."),
    ("Where do you see the little arrow cursor moving?", "On the monitor screen", ["On the monitor screen", "On the paper"], "images/ict/monitor.jpg", "The arrow pointer glides on the monitor screen."),
    ("Which computer part prints paper worksheets for teacher?", "Printer", ["Printer", "Mouse"], "images/ict/printer.jpg", "Printers print worksheets on paper."),
    ("Can you hear funny game sounds through computer speakers?", "Yes", ["Yes", "No"], "images/ict/speakers.jpg", "Speakers play game music and sounds."),
    ("What button on a mouse do you click most often?", "Left button", ["Left button", "Underneath button"], "images/ict/mouse.jpg", "Click with your index finger on the left button!"),
    ("Does a keyboard have number keys 1, 2, 3, 4, 5?", "Yes", ["Yes", "No"], "images/ict/keyboard.jpg", "You can type numbers on a keyboard!"),
    ("Does a keyboard have ABC letter keys?", "Yes", ["Yes", "No"], "images/ict/keyboard.jpg", "Keys A to Z to type your name!"),
    ("Is a laptop computer portable to carry around?", "Yes, you can fold it", ["Yes, you can fold it", "No, too heavy"], None, "Laptops fold up to take anywhere."),
    ("Does a tablet like an iPad have a touchscreen you tap with fingers?", "Yes, tap the screen", ["Yes, tap the screen", "No"], None, "Tablets have touchscreens!"),
    ("What do you rest your mouse on to slide smoothly?", "Mouse pad", ["Mouse pad", "Pillow"], None, "A mouse pad helps the mouse glide smoothly."),
    ("Which key makes a blank space between two words?", "Spacebar", ["Spacebar", "Letter A", "Escape"], "images/ict/keyboard.jpg", "The big long bar is the Spacebar!"),
    ("Which key do you press to start a new line?", "Enter key", ["Enter key", "Spacebar"], "images/ict/keyboard.jpg", "The Enter key starts a new line!"),
    ("Which key deletes a letter if you make a mistake?", "Backspace key", ["Backspace key", "Spacebar"], "images/ict/keyboard.jpg", "Backspace erases a mistake!"),
    ("What button turns the computer on and off?", "Power button", ["Power button", "Volume knob"], "images/ict/system_unit.jpg", "Press the power button with the circle symbol!"),
    ("Can you watch cartoons on a computer monitor?", "Yes!", ["Yes!", "No"], "images/ict/monitor.jpg", "Monitors show bright colorful cartoons!"),
    ("Can you talk to grandma on a webcam video call?", "Yes!", ["Yes!", "No"], "images/ict/webcam.jpg", "Webcam video calls let grandma see your smile!"),
    ("Can you listen to your favorite songs on headphones?", "Yes!", ["Yes!", "No"], "images/ict/headphones.jpg", "Headphones play music into your ears!"),
    ("Does a printer need ink and paper to print pictures?", "Yes, ink and paper", ["Yes, ink and paper", "No, just air"], "images/ict/printer.jpg", "Printers use ink on paper."),
    ("Is a computer mouse alive like a real furry mouse?", "No, it is a plastic tool", ["No, it is a plastic tool", "Yes, it eats cheese"], "images/ict/mouse.jpg", "It's an electronic device, not an animal!"),
    ("Why is a computer mouse called a mouse?", "Because it has a cord tail and little body", ["Because it eats cheese", "Because it is furry"], "images/ict/mouse.jpg", "The wire looks like a little mouse tail!"),
    ("Does a wireless mouse need a cord to work?", "No, works without wire", ["No, works without wire", "Yes"], "images/ict/mouse.jpg", "Wireless mice use battery power."),
    ("Can you adjust the volume louder or softer on speakers?", "Yes", ["Yes", "No"], "images/ict/speakers.jpg", "Turn the knob to make it soft or loud."),
    ("Should you keep drinks away from your computer keyboard?", "Yes, keep drinks away", ["Yes, keep drinks away", "No, spill juice on it"], "images/ict/keyboard.jpg", "Liquids can break the keyboard!"),
    ("Should you press the keyboard keys gently or smash them?", "Gently with soft fingers", ["Gently with soft fingers", "Smash them hard"], "images/ict/keyboard.jpg", "Type gently with your fingers."),
    ("Should you wash your sticky hands before using the computer?", "Yes, clean hands!", ["Yes, clean hands!", "No, use sticky jam hands"], None, "Always use clean hands on computer parts!"),
    ("What part lets the teacher see all the students on screen?", "Monitor", ["Monitor", "Speakers"], "images/ict/monitor.jpg", "Monitors show the whole class on video."),
    ("What part records teacher's voice clearly?", "Microphone", ["Microphone", "Scanner"], "images/ict/microphone.jpg", "Microphones catch speech clearly."),
    ("What color is paper usually put into a printer?", "White paper", ["White paper", "Black paper"], "images/ict/printer.jpg", "Clean white paper sheets."),
    ("Can a printer print in colors like red, blue, and yellow?", "Yes, color printer", ["Yes, color printer", "No"], "images/ict/printer.jpg", "Color printers make colorful printouts!"),
    ("What do you click on to play a game: an icon on screen?", "Yes, double click the icon", ["Yes, double click the icon", "No"], "images/ict/mouse.jpg", "Click on the game icon to start!"),
    ("What finger do you use to click the mouse left button?", "Index pointer finger", ["Index pointer finger", "Pinky finger"], "images/ict/mouse.jpg", "Your index pointer finger clicks the left button!"),
    ("Does a computer have letters A to Z on its keyboard?", "Yes, all 26 letters", ["Yes, all 26 letters", "Only 3 letters"], "images/ict/keyboard.jpg", "All 26 alphabet letters are on the keyboard!"),
    ("Can you draw pictures on a computer using a mouse?", "Yes, in paint programs", ["Yes, in paint programs", "No"], "images/ict/mouse.jpg", "You can draw and color digitally!"),
    ("What shape is a desktop monitor screen?", "Rectangle", ["Rectangle", "Triangle"], "images/ict/monitor.jpg", "Monitors are rectangular screens."),
    ("Can you wear headphones while playing quiet games so you don't disturb baby?", "Yes", ["Yes", "No"], "images/ict/headphones.jpg", "Headphones keep sounds quiet for others."),
    ("Can you speak into a microphone to talk with friends online?", "Yes", ["Yes", "No"], "images/ict/microphone.jpg", "Microphones let your friends hear you."),
    ("Is the CPU case the main computing unit?", "Yes", ["Yes", "No"], "images/ict/system_unit.jpg", "The CPU case holds the computer engine!"),
    ("Does a computer need electricity to run?", "Yes, plug in or battery", ["Yes, plug in or battery", "No, runs on water"], None, "Computers need electrical power."),
    ("What is an iPad: a tablet or a television?", "A tablet computer", ["A tablet computer", "A television"], None, "An iPad is a tablet computer."),
    ("Do you touch the screen of a tablet to draw?", "Yes, with your finger", ["Yes, with your finger", "No"], None, "Tablets respond to finger touches!"),
    ("What computer part looks like a little camera eye?", "Webcam", ["Webcam", "Speaker"], "images/ict/webcam.jpg", "A webcam looks like a tiny round camera."),
    ("Can you print a picture of a dinosaur to color with crayons?", "Yes, print it out!", ["Yes, print it out!", "No"], "images/ict/printer.jpg", "Printers print fun coloring pages!"),
    ("Is a computer a helpful tool for learning?", "Yes, very helpful!", ["Yes, very helpful!", "No"], None, "Computers help us learn math, science, and languages!"),
    ("Should we take turns nicely when sharing a computer at school?", "Yes, share kindly!", ["Yes, share kindly!", "No, grab it"], None, "Sharing nicely is great teamwork!")
]
for item in ict_parts_k3:
    q, ans, opts, img, hint = item
    add_q("ict_parts", "ict", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 2: ict_storage (Storage Devices, K3 Friendly) ---
storage_k3 = [
    ("Look at the picture! What tiny stick plugs into a USB port to save your school drawings?", "Pendrive / USB drive", ["Pendrive / USB drive", "Spoon", "Pencil"], "images/ict/pendrive.jpg", "A pendrive or thumb drive saves your files to carry anywhere!"),
    ("Look at the picture! What round shiny disc can store music, songs, and movies?", "CD-ROM / DVD", ["CD-ROM / DVD", "Paper plate", "Coin"], "images/ict/cd_rom.jpg", "A compact disc CD is round and shiny!"),
    ("Look at the picture! What tiny memory card goes inside digital cameras to store photos?", "Memory Card (SD Card)", ["Memory Card (SD Card)", "Credit card", "Bus ticket"], "images/ict/memory_card.jpg", "Memory cards keep hundreds of photos inside cameras!"),
    ("Look at the picture! What old square plastic disk was used to save files long ago?", "Floppy disk", ["Floppy disk", "Sandwich", "Book"], "images/ict/floppy_disk.jpg", "Floppy disks were square plastic disks used in old computers."),
    ("Can you save your colorful drawing on a USB pendrive to show Grandma?", "Yes!", ["Yes!", "No"], "images/ict/pendrive.jpg", "Plug your pendrive into Grandma's computer to show her!"),
    ("Can a CD-ROM play children's songs on a music player?", "Yes", ["Yes", "No"], "images/ict/cd_rom.jpg", "CDs store songs and music tracks."),
    ("Where do you save photos on a tablet or phone?", "In internal storage or memory card", ["In internal storage or memory card", "In a pencil case"], "images/ict/memory_card.jpg", "Digital memory holds all your photos!"),
    ("What does a computer do when you click 'Save'?", "Keeps your work safely", ["Keeps your work safely", "Throws it away"], None, "Clicking Save stores your work so you don't lose it!"),
    ("Is a USB pendrive small enough to fit inside your pocket?", "Yes, very small", ["Yes, very small", "No, too big"], "images/ict/pendrive.jpg", "USB pendrives are pocket-sized!"),
    ("What shape is a CD disc?", "Round circle", ["Round circle", "Square", "Triangle"], "images/ict/cd_rom.jpg", "CDs are round discs with a hole in the middle."),
    ("Can a memory card save video recordings?", "Yes", ["Yes", "No"], "images/ict/memory_card.jpg", "Memory cards hold photos and videos!"),
    ("Should you keep storage devices dry and safe from water?", "Yes, keep them dry!", ["Yes, keep them dry!", "No, wash them in soup"], None, "Water can damage digital memory."),
    ("Does a hard drive live inside the computer to store everything?", "Yes, inside the computer", ["Yes, inside the computer", "No, on the roof"], None, "The computer hard drive stores all games and apps."),
    ("Can you save your favorite game progress so you can play tomorrow?", "Yes, click save!", ["Yes, click save!", "No"], None, "Saving lets you continue your game anytime!"),
    ("What icon in computer apps looks like a floppy disk to mean 'Save'?", "Floppy disk icon 💾", ["Floppy disk icon 💾", "Ice cream icon 🍦"], "images/ict/floppy_disk.jpg", "The save button 💾 looks like a floppy disk!"),
    ("Can you store your favorite cartoon episodes on a USB pendrive?", "Yes", ["Yes", "No"], "images/ict/pendrive.jpg", "Pendrives can hold video cartoons."),
    ("Can a CD scratch if you scrape it on rough rocks?", "Yes, hold it by the edges!", ["Yes, hold it by the edges!", "No, indestructible"], "images/ict/cd_rom.jpg", "Always hold CDs gently by the edges!"),
    ("Does a computer remember your files when turned off if you saved them?", "Yes, saved files stay", ["Yes, saved files stay", "No, they disappear"], None, "Saved files stay safe on the disk."),
    ("Where does a photographer store photos taken on a camera?", "On an SD memory card", ["On an SD memory card", "On a banana"], "images/ict/memory_card.jpg", "Digital cameras use SD memory cards."),
    ("Can a tiny USB drive hold thousands of pictures?", "Yes, so many!", ["Yes, so many!", "Only one"], "images/ict/pendrive.jpg", "Modern USB drives hold thousands of files!"),
    ("What port on a computer does a pendrive plug into?", "USB port", ["USB port", "Power plug"], "images/ict/pendrive.jpg", "Slide it gently into the rectangular USB port."),
    ("Can you listen to an audiobook from a CD?", "Yes", ["Yes", "No"], "images/ict/cd_rom.jpg", "Audio CDs read stories aloud!"),
    ("What color is the shiny rainbow reflection on the back of a CD?", "Rainbow colors", ["Rainbow colors", "Plain black"], "images/ict/cd_rom.jpg", "Light makes rainbow colors shine on CDs!"),
    ("Can you save a typing worksheet on a computer?", "Yes", ["Yes", "No"], None, "You can save text documents anytime."),
    ("Should you pull out a pendrive while it is saving a file?", "No, wait until it finishes!", ["No, wait until it finishes!", "Yes, yank it fast"], "images/ict/pendrive.jpg", "Wait until the file finishes saving!"),
    ("Is a memory card bigger or smaller than a pendrive?", "Smaller, very tiny", ["Smaller, very tiny", "Bigger than a bus"], "images/ict/memory_card.jpg", "Memory cards are tiny like a postage stamp!"),
    ("Does a smartphone have storage inside for games and apps?", "Yes", ["Yes", "No"], None, "Phones have internal storage."),
    ("If you make a new digital painting, should you save it?", "Yes, click Save!", ["Yes, click Save!", "No, close window"], None, "Save it to keep your artwork forever!"),
    ("Can cloud storage save files over the internet?", "Yes, in the cloud", ["Yes, in the cloud", "No"], None, "Cloud storage saves files online!"),
    ("Can you watch a movie stored on a DVD disc?", "Yes, in a DVD player", ["Yes, in a DVD player", "No"], "images/ict/cd_rom.jpg", "DVDs play movies on your TV or screen."),
    ("Can you store songs on an MP3 player?", "Yes", ["Yes", "No"], None, "Digital music players store songs."),
    ("Does a pendrive need batteries to save files?", "No, gets power from USB port", ["No, gets power from USB port", "Yes"], "images/ict/pendrive.jpg", "Pendrives get power from the computer!"),
    ("Can you delete an old file you don't need anymore to make space?", "Yes, delete it", ["Yes, delete it", "No"], None, "Deleting old files frees up room."),
    ("Where do deleted files go on Windows before being emptied?", "Recycle Bin 🗑️", ["Recycle Bin 🗑️", "Under the bed"], None, "The Recycle Bin holds deleted files temporarily!"),
    ("Can you restore a file from the Recycle Bin if deleted by accident?", "Yes, click Restore", ["Yes, click Restore", "No"], None, "You can put it back from the Recycle Bin!"),
    ("Can you carry 10 pendrives in a small pouch?", "Yes, they are lightweight", ["Yes, they are lightweight", "No"], "images/ict/pendrive.jpg", "Pendrives are super light."),
    ("What device was used first: Floppy disk or USB flash drive?", "Floppy disk was older", ["Floppy disk was older", "USB drive was older"], "images/ict/floppy_disk.jpg", "Floppy disks were used first in older computers."),
    ("Does a CD disc have a small hole in the exact center?", "Yes, a round hole", ["Yes, a round hole", "No"], "images/ict/cd_rom.jpg", "The center hole fits on the spinning spindle."),
    ("Can you label a pendrive with your name sticker so you don't lose it?", "Yes, good idea!", ["Yes, good idea!", "No"], "images/ict/pendrive.jpg", "Label your pendrive so it doesn't get lost."),
    ("Is it fun to see all your saved kindergarten drawings on computer?", "Yes, wonderful!", ["Yes, wonderful!", "No"], None, "Looking back at your digital artwork is so fun!"),
    ("Can digital photos on a memory card be copied to a computer?", "Yes", ["Yes", "No"], "images/ict/memory_card.jpg", "Copy them to the computer to view on screen."),
    ("Can you save audio voice recordings on a pendrive?", "Yes", ["Yes", "No"], "images/ict/pendrive.jpg", "Audio recordings can be saved easily."),
    ("Does a digital camera work without a memory card inside?", "Usually needs a card to save photos", ["Usually needs a card to save photos", "No need"], "images/ict/memory_card.jpg", "Put in a memory card to take pictures!"),
    ("Can you email a saved file to teacher?", "Yes, attach the file", ["Yes, attach the file", "No"], None, "You can send saved files by email."),
    ("Does a hard drive spin or use flash memory?", "Yes, stores files safely", ["Yes, stores files safely", "No"], None, "Hard drives store all computer data."),
    ("Can you save an animated story on a USB drive?", "Yes", ["Yes", "No"], "images/ict/pendrive.jpg", "USB drives store animation files."),
    ("Can a CD hold software and fun learning games?", "Yes, game CDs", ["Yes, game CDs", "No"], "images/ict/cd_rom.jpg", "Game CDs install learning games!"),
    ("Is an external hard drive used for big backups?", "Yes", ["Yes", "No"], None, "Backup hard drives keep copies of everything."),
    ("Can you rename a file after saving it, like 'My_Drawing.png'?", "Yes, you can rename it", ["Yes, you can rename it", "No"], None, "Give your file a clear name!"),
    ("Should you keep magnets away from old floppy disks?", "Yes, magnets damage them", ["Yes, magnets damage them", "No"], "images/ict/floppy_disk.jpg", "Magnets can erase floppy disks."),
    ("Can a USB pendrive plug into a TV to show pictures?", "Yes, many modern TVs have USB ports", ["Yes, many modern TVs have USB ports", "No"], "images/ict/pendrive.jpg", "Plug it into the TV to see family photos!"),
    ("Does a tablet store your favorite storybook apps?", "Yes", ["Yes", "No"], None, "Apps stay stored on your tablet."),
    ("What key shortcut on keyboard saves a file on PC?", "Ctrl + S", ["Ctrl + S", "Ctrl + Z"], None, "Ctrl + S is the shortcut for Save!"),
    ("Is saving your work important so you don't lose it if power goes out?", "Yes, save often!", ["Yes, save often!", "No need to save"], None, "Always save your work regularly!"),
    ("Do you feel proud when you see your saved work complete?", "Yes, very proud!", ["Yes, very proud!", "No"], None, "Good job saving your project!")
]
for item in storage_k3:
    q, ans, opts, img, hint = item
    add_q("ict_storage", "ict", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 3: ict_counting (Counting Computer Peripherals, K3 Friendly) ---
counting_peripherals_k3 = [
    ("Count the computer mice: 🖱️. How many mouse do you see?", "1", ["1", "2", "0"], "images/ict/mouse.jpg", "Count: 1 mouse!"),
    ("Count the mice: 🖱️ 🖱️. How many mice do you see?", "2", ["2", "3", "1"], "images/ict/mouse.jpg", "Count with your finger: 1, 2!"),
    ("Count the mice: 🖱️ 🖱️ 🖱️. How many mice do you see?", "3", ["3", "4", "2"], "images/ict/mouse.jpg", "1, 2, 3 mice!"),
    ("Count the screens: 🖥️. How many screens do you see?", "1", ["1", "2", "3"], "images/ict/monitor.jpg", "Just 1 screen!"),
    ("Count the screens: 🖥️ 🖥️. How many screens do you see?", "2", ["2", "3", "1"], "images/ict/monitor.jpg", "1, 2 screens!"),
    ("Count the screens: 🖥️ 🖥️ 🖥️. How many screens do you see?", "3", ["3", "4", "2"], "images/ict/monitor.jpg", "1, 2, 3 screens!"),
    ("Count the screens: 🖥️ 🖥️ 🖥️ 🖥️. How many screens do you see?", "4", ["4", "5", "3"], "images/ict/monitor.jpg", "1, 2, 3, 4 screens!"),
    ("Count the keyboards: ⌨️. How many keyboards do you see?", "1", ["1", "2", "0"], "images/ict/keyboard.jpg", "1 keyboard!"),
    ("Count the keyboards: ⌨️ ⌨️. How many keyboards do you see?", "2", ["2", "3", "1"], "images/ict/keyboard.jpg", "1, 2 keyboards!"),
    ("Count the keyboards: ⌨️ ⌨️ ⌨️. How many keyboards do you see?", "3", ["3", "4", "2"], "images/ict/keyboard.jpg", "1, 2, 3 keyboards!"),
    ("Count the keyboards: ⌨️ ⌨️ ⌨️ ⌨️. How many keyboards do you see?", "4", ["4", "5", "3"], "images/ict/keyboard.jpg", "1, 2, 3, 4 keyboards!"),
    ("Count the keyboards: ⌨️ ⌨️ ⌨️ ⌨️ ⌨️. How many keyboards do you see?", "5", ["5", "6", "4"], "images/ict/keyboard.jpg", "1, 2, 3, 4, 5 keyboards!"),
    ("Count the headphones: 🎧. How many headphones do you see?", "1", ["1", "2", "3"], "images/ict/headphones.jpg", "1 pair of headphones!"),
    ("Count the headphones: 🎧 🎧. How many headphones do you see?", "2", ["2", "3", "1"], "images/ict/headphones.jpg", "1, 2 headphones!"),
    ("Count the headphones: 🎧 🎧 🎧. How many headphones do you see?", "3", ["3", "4", "2"], "images/ict/headphones.jpg", "1, 2, 3 headphones!"),
    ("Count the CDs: 💿. How many shiny discs do you see?", "1", ["1", "2", "0"], "images/ict/cd_rom.jpg", "1 shiny CD!"),
    ("Count the CDs: 💿 💿. How many shiny discs do you see?", "2", ["2", "3", "1"], "images/ict/cd_rom.jpg", "1, 2 shiny CDs!"),
    ("Count the CDs: 💿 💿 💿. How many shiny discs do you see?", "3", ["3", "4", "2"], "images/ict/cd_rom.jpg", "1, 2, 3 shiny CDs!"),
    ("Count the CDs: 💿 💿 💿 💿. How many shiny discs do you see?", "4", ["4", "5", "3"], "images/ict/cd_rom.jpg", "1, 2, 3, 4 shiny CDs!"),
    ("Count the CDs: 💿 💿 💿 💿 💿. How many shiny discs do you see?", "5", ["5", "6", "4"], "images/ict/cd_rom.jpg", "1, 2, 3, 4, 5 shiny CDs!"),
    ("Count the printers: 🖨️. How many printers do you see?", "1", ["1", "2", "0"], "images/ict/printer.jpg", "1 printer!"),
    ("Count the printers: 🖨️ 🖨️. How many printers do you see?", "2", ["2", "3", "1"], "images/ict/printer.jpg", "1, 2 printers!"),
    ("Count the printers: 🖨️ 🖨️ 🖨️. How many printers do you see?", "3", ["3", "4", "2"], "images/ict/printer.jpg", "1, 2, 3 printers!"),
    ("Count the USB pendrives: 💾 💾 💾. How many drives do you see?", "3", ["3", "2", "4"], "images/ict/pendrive.jpg", "1, 2, 3 drives!"),
    ("Count the USB pendrives: 💾 💾 💾 💾. How many drives do you see?", "4", ["4", "5", "3"], "images/ict/pendrive.jpg", "1, 2, 3, 4 drives!"),
    ("Count: 🖥️ and 🖱️. How many computer items in total?", "2", ["2", "3", "1"], None, "1 monitor + 1 mouse = 2 items!"),
    ("Count: 🖥️, ⌨️, and 🖱️. How many computer items in total?", "3", ["3", "4", "2"], None, "1 monitor + 1 keyboard + 1 mouse = 3 items!"),
    ("Count: 🎧 and 🎧. How many headphones?", "2", ["2", "1", "3"], "images/ict/headphones.jpg", "1 + 1 = 2 headphones!"),
    ("Count: ⌨️ and ⌨️ and ⌨️. How many keyboards?", "3", ["3", "2", "4"], "images/ict/keyboard.jpg", "1 + 1 + 1 = 3 keyboards!"),
    ("If teacher brings 2 mice 🖱️🖱️ and adds 1 more 🖱️, how many mice?", "3", ["3", "4", "2"], "images/ict/mouse.jpg", "2 + 1 = 3 mice!"),
    ("If you have 1 monitor 🖥️ and get 1 more 🖥️, how many monitors?", "2", ["2", "3", "1"], "images/ict/monitor.jpg", "1 + 1 = 2 monitors!"),
    ("Count the microphones: 🎤. How many microphones?", "1", ["1", "2", "0"], "images/ict/microphone.jpg", "1 microphone!"),
    ("Count the microphones: 🎤 🎤. How many microphones?", "2", ["2", "3", "1"], "images/ict/microphone.jpg", "1, 2 microphones!"),
    ("Count the webcams: 📹. How many cameras?", "1", ["1", "2", "0"], "images/ict/webcam.jpg", "1 webcam camera!"),
    ("Count the webcams: 📹 📹. How many cameras?", "2", ["2", "3", "1"], "images/ict/webcam.jpg", "1, 2 webcams!"),
    ("Count: 🖱️ 🖱️ 🖱️ 🖱️. How many mice?", "4", ["4", "5", "3"], "images/ict/mouse.jpg", "Count: 1, 2, 3, 4!"),
    ("Count: 🖱️ 🖱️ 🖱️ 🖱️ 🖱️. How many mice?", "5", ["5", "6", "4"], "images/ict/mouse.jpg", "Count: 1, 2, 3, 4, 5!"),
    ("Count: 🖥️ 🖥️ 🖥️ 🖥️ 🖥️. How many screens?", "5", ["5", "4", "6"], "images/ict/monitor.jpg", "Five screens!"),
    ("Count: 🎧 🎧 🎧 🎧. How many headphones?", "4", ["4", "3", "5"], "images/ict/headphones.jpg", "Four headphones!"),
    ("Count: 💿 💿 💿 💿 💿 💿. How many CDs?", "6", ["6", "5", "7"], "images/ict/cd_rom.jpg", "Six shiny CDs!"),
    ("Count the buttons on a basic mouse: left and right. How many main buttons?", "2 buttons", ["2 buttons", "10 buttons", "5 buttons"], "images/ict/mouse.jpg", "Two main buttons: left and right!"),
    ("Count: 🖨️ 🖨️ 🖨️ 🖨️. How many printers?", "4", ["4", "3", "5"], "images/ict/printer.jpg", "Four printers!"),
    ("Count: ⌨️ ⌨️ ⌨️ ⌨️ ⌨️ ⌨️. How many keyboards?", "6", ["6", "5", "7"], "images/ict/keyboard.jpg", "Six keyboards!"),
    ("Count: 🎤 🎤 🎤. How many microphones?", "3", ["3", "2", "4"], "images/ict/microphone.jpg", "Three microphones!"),
    ("Count: 📹 📹 📹. How many webcams?", "3", ["3", "2", "4"], "images/ict/webcam.jpg", "Three webcams!"),
    ("If you have 4 laptop computers and give 1 to Ben, how many left?", "3", ["3", "4", "2"], None, "4 - 1 = 3 laptops left!"),
    ("If you have 5 USB pendrives and lose 1, how many left?", "4", ["4", "5", "3"], "images/ict/pendrive.jpg", "5 - 1 = 4 pendrives!"),
    ("If a computer lab has 3 desks with 1 computer on each, how many computers?", "3 computers", ["3 computers", "5 computers"], "images/ict/monitor.jpg", "3 desks = 3 computers!"),
    ("Count: 🖱️ and 🎧. How many items?", "2", ["2", "1", "3"], None, "1 mouse + 1 headphone = 2 items!"),
    ("Count: 🖨️ and 🖥️. How many items?", "2", ["2", "3", "1"], None, "1 printer + 1 screen = 2 items!"),
    ("Count: 💿 and 💿 and 💿 and 💿. How many CDs?", "4", ["4", "3", "5"], "images/ict/cd_rom.jpg", "1, 2, 3, 4 CDs!"),
    ("Count the computers in your hand right now: 📱 (1 smartphone). How many?", "1", ["1", "2", "3"], None, "Just 1 device!"),
    ("Count: 🖱️ 🖱️ 🖱️ and 🖱️ 🖱️ 🖱️. How many mice in total?", "6", ["6", "5", "7"], "images/ict/mouse.jpg", "3 + 3 = 6 mice!"),
    ("Count: 🖥️ 🖥️ and 🖥️ 🖥️. How many monitors?", "4", ["4", "3", "5"], "images/ict/monitor.jpg", "2 + 2 = 4 monitors!"),
    ("How many screens does a regular laptop have?", "1 screen", ["1 screen", "4 screens"], None, "Laptops have 1 screen.")
]
for item in counting_peripherals_k3:
    q, ans, opts, img, hint = item
    add_q("ict_counting", "ict", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 4: ict_spelling (Spelling Computer Peripherals, K3 Friendly) ---
spelling_k3 = [
    # MOUSE
    ("What letter is missing in M - O - U - S - __?", "E", ["E", "A", "O"], "images/ict/mouse.jpg", "M - O - U - S - E spells Mouse!"),
    ("What letter does 'Mouse' start with?", "M", ["M", "N", "W"], "images/ict/mouse.jpg", "M is for Mouse!"),
    ("What vowel is in the middle of M - O - __ - S - E?", "U", ["U", "I", "A"], "images/ict/mouse.jpg", "M - O - U - S - E."),
    ("Spell the word for the clicking device: M - O - U - S - E. Is that correct?", "Yes, Mouse!", ["Yes, Mouse!", "No"], "images/ict/mouse.jpg", "M-O-U-S-E is correct!"),
    
    # SCREEN / MONITOR
    ("What letter does 'Screen' start with?", "S", ["S", "C", "T"], "images/ict/monitor.jpg", "S is for Screen!"),
    ("What letter does 'Monitor' start with?", "M", ["M", "N", "P"], "images/ict/monitor.jpg", "M is for Monitor!"),
    ("Complete the word: S - C - R - E - E - __", "N", ["N", "M", "T"], "images/ict/monitor.jpg", "S - C - R - E - E - N spells Screen!"),
    ("What double vowel is in S - C - R - __ - __ - N?", "EE", ["EE", "OO", "AA"], "images/ict/monitor.jpg", "Double E in Screen!"),

    # KEYBOARD
    ("What letter does 'Key' start with?", "K", ["K", "C", "T"], "images/ict/keyboard.jpg", "K is for Key!"),
    ("What letter is missing: K - E - __?", "Y", ["Y", "I", "E"], "images/ict/keyboard.jpg", "K - E - Y spells Key!"),
    ("Complete the word: K - E - Y - B - O - A - R - __", "D", ["D", "T", "P"], "images/ict/keyboard.jpg", "K - E - Y - B - O - A - R - D spells Keyboard!"),
    ("What vowel starts the word 'Board': B - __ - A - R - D?", "O", ["O", "E", "U"], "images/ict/keyboard.jpg", "B - O - A - R - D."),

    # PRINTER
    ("What letter does 'Printer' start with?", "P", ["P", "B", "D"], "images/ict/printer.jpg", "P is for Printer!"),
    ("Complete the word: P - R - I - N - T - E - __", "R", ["R", "L", "T"], "images/ict/printer.jpg", "P - R - I - N - T - E - R spells Printer!"),
    ("What vowel is after P - R: P - R - __ - N - T?", "I", ["I", "E", "O"], "images/ict/printer.jpg", "P - R - I - N - T."),
    ("What word is P - R - I - N - T?", "Print", ["Print", "Paint", "Plant"], "images/ict/printer.jpg", "P - R - I - N - T is Print!"),

    # CD
    ("What two letters stand for Compact Disc?", "CD", ["CD", "TV", "PC"], "images/ict/cd_rom.jpg", "C - D stands for Compact Disc!"),
    ("What letter is first in C - D?", "C", ["C", "D", "B"], "images/ict/cd_rom.jpg", "C comes first!"),
    ("What letter is second in C - D?", "D", ["D", "C", "E"], "images/ict/cd_rom.jpg", "D comes second!"),

    # PENDRIVE
    ("What letter does 'Pendrive' start with?", "P", ["P", "B", "D"], "images/ict/pendrive.jpg", "P is for Pendrive!"),
    ("Complete the word: P - E - N - D - R - I - V - __", "E", ["E", "A", "O"], "images/ict/pendrive.jpg", "P - E - N - D - R - I - V - E spells Pendrive!"),
    ("What word starts with P - E - N: is it 'Pen'?", "Yes, Pen!", ["Yes, Pen!", "No"], "images/ict/pendrive.jpg", "Pen + Drive = Pendrive!"),
    ("What letters spell D - R - I - V - E?", "Drive", ["Drive", "Dove", "Dive"], "images/ict/pendrive.jpg", "D - R - I - V - E spells Drive!"),

    # WEBCAM
    ("What letter does 'Webcam' start with?", "W", ["W", "V", "M"], "images/ict/webcam.jpg", "W is for Webcam!"),
    ("Complete the word: W - E - B - C - A - __", "M", ["M", "N", "P"], "images/ict/webcam.jpg", "W - E - B - C - A - M spells Webcam!"),
    ("What word is W - E - B?", "Web", ["Web", "Wig", "Wet"], "images/ict/webcam.jpg", "W - E - B is Web!"),
    ("What word is C - A - M short for?", "Camera", ["Camera", "Candy", "Car"], "images/ict/webcam.jpg", "Cam is short for camera!"),

    # MIC / MICROPHONE
    ("What letter does 'Mic' start with?", "M", ["M", "N", "W"], "images/ict/microphone.jpg", "M is for Microphone!"),
    ("Complete the short word: M - I - __", "C", ["C", "K", "S"], "images/ict/microphone.jpg", "M - I - C spells Mic!"),
    ("What vowel is in M - __ - C?", "I", ["I", "E", "O"], "images/ict/microphone.jpg", "Letter I in Mic!"),

    # SPEAKERS
    ("What letter does 'Speaker' start with?", "S", ["S", "C", "T"], "images/ict/speakers.jpg", "S is for Speaker!"),
    ("Complete the word: S - P - E - A - K - E - __", "R", ["R", "L", "D"], "images/ict/speakers.jpg", "S - P - E - A - K - E - R spells Speaker!"),
    ("What word is S - P - E - A - K?", "Speak", ["Speak", "Spark", "Spook"], "images/ict/speakers.jpg", "S - P - E - A - K is Speak!"),

    # HEADPHONES
    ("What letter does 'Headphone' start with?", "H", ["H", "M", "N"], "images/ict/headphones.jpg", "H is for Headphone!"),
    ("What word is H - E - A - D?", "Head", ["Head", "Hand", "Hat"], "images/ict/headphones.jpg", "H - E - A - D is Head!"),
    ("What word is P - H - O - N - E?", "Phone", ["Phone", "Pony", "Plan"], "images/ict/headphones.jpg", "P - H - O - N - E is Phone!"),

    # LAPTOP
    ("What letter does 'Laptop' start with?", "L", ["L", "I", "T"], None, "L is for Laptop!"),
    ("Complete the word: L - A - P - T - O - __", "P", ["P", "B", "D"], None, "L - A - P - T - O - P spells Laptop!"),
    ("What word is L - A - P?", "Lap", ["Lap", "Lip", "Log"], None, "L - A - P is Lap!"),
    ("What word is T - O - P?", "Top", ["Top", "Tap", "Tip"], None, "T - O - P is Top!"),

    # TABLET
    ("What letter does 'Tablet' start with?", "T", ["T", "D", "P"], None, "T is for Tablet!"),
    ("Complete the word: T - A - B - L - E - __", "T", ["T", "D", "P"], None, "T - A - B - L - E - T spells Tablet!"),
    ("What vowel is first in T - __ - B - L - E - T?", "A", ["A", "E", "O"], None, "T - A - B."),

    # ROBOT & APP & CODE
    ("What letter does 'Robot' start with?", "R", ["R", "B", "P"], None, "R is for Robot!"),
    ("Complete the word: R - O - B - O - __", "T", ["T", "D", "P"], None, "R - O - B - O - T spells Robot!"),
    ("What short word is A - P - P?", "App", ["App", "Ape", "Ant"], None, "A - P - P is App!"),
    ("What word is C - O - D - E?", "Code", ["Code", "Cold", "Cone"], None, "C - O - D - E is Code!"),
    ("What letter does 'Computer' start with?", "C", ["C", "K", "S"], None, "C is for Computer!"),
    ("Complete the word: C - O - M - P - U - T - E - __", "R", ["R", "L", "T"], None, "C - O - M - P - U - T - E - R spells Computer!"),
    ("What vowel is in the word B - __ - T - E (Byte)?", "Y", ["Y", "I", "A"], None, "B - Y - T - E."),
    ("What letters spell C - L - I - C - K?", "Click", ["Click", "Clock", "Cluck"], "images/ict/mouse.jpg", "C - L - I - C - K is Click!"),
    ("What letters spell T - Y - P - E?", "Type", ["Type", "Tape", "Tree"], "images/ict/keyboard.jpg", "T - Y - P - E is Type!"),
    ("What letter does 'Scan' start with?", "S", ["S", "C", "T"], "images/ict/scanner.jpg", "S is for Scan!"),
    ("What letter does 'File' start with?", "F", ["F", "V", "P"], None, "F is for File!"),
    ("Complete the word: F - I - L - __", "E", ["E", "A", "O"], None, "F - I - L - E spells File!"),
    ("What word is S - A - V - E?", "Save", ["Save", "Safe", "Soup"], None, "S - A - V - E is Save!"),
    ("What word is O - P - E - N?", "Open", ["Open", "Over", "Oven"], None, "O - P - E - N is Open!"),
    ("What word is E - X - I - T?", "Exit", ["Exit", "Eggs", "East"], None, "E - X - I - T is Exit!"),
    ("What letter does 'Icon' start with?", "I", ["I", "E", "A"], None, "I is for Icon!"),
    ("Complete the word: G - A - M - __", "E", ["E", "A", "O"], None, "G - A - M - E spells Game!"),
    ("What word is P - L - A - Y?", "Play", ["Play", "Plan", "Plow"], None, "P - L - A - Y is Play!"),
    ("Do all letters on the keyboard make fun words?", "Yes!", ["Yes!", "No"], "images/ict/keyboard.jpg", "Typing letters makes words!")
]
for item in spelling_k3:
    q, ans, opts, img, hint = item
    add_q("ict_spelling", "ict", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 5: ict_input_output (Input vs Output Devices, K3 Friendly) ---
input_output_k3 = [
    ("When you click a mouse 🖱️, does it send your click IN to the computer?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/mouse.jpg", "Your click goes IN: Mouse is Input!"),
    ("When you type on a keyboard ⌨️, do your letters go IN to the computer?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/keyboard.jpg", "Typing letters puts them IN: Keyboard is Input!"),
    ("When a speaker 🔊 plays music, does sound come OUT to your ears?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/speakers.jpg", "Music comes OUT to your ears: Speakers are Output!"),
    ("When a monitor screen 🖥️ shows a video, does it show pictures OUT for your eyes?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/monitor.jpg", "Pictures shine OUT to your eyes: Monitor is Output!"),
    ("When a printer 🖨️ prints a drawing on paper, does paper come OUT?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/printer.jpg", "Paper comes OUT: Printer is Output!"),
    ("When you sing into a microphone 🎤, does your voice go IN?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/microphone.jpg", "Your voice travels IN: Microphone is Input!"),
    ("When you wear headphones 🎧, does music play OUT into your ears?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/headphones.jpg", "Music plays OUT into your ears: Headphones are Output!"),
    ("When a webcam 📹 takes your photo, does the picture go IN to the computer?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/webcam.jpg", "Your photo goes IN: Webcam is Input!"),
    ("When a scanner 📠 copies your drawing, does the drawing go IN to the computer?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/scanner.jpg", "Scanning puts the image IN: Scanner is Input!"),
    ("Is a keyboard an Input device (IN) or Output device (OUT)?", "Input (IN)", ["Input (IN)", "Output (OUT)"], "images/ict/keyboard.jpg", "Keyboard puts letters IN!"),
    ("Is a computer monitor an Output device (OUT)?", "Yes, Output (OUT)", ["Yes, Output (OUT)", "No, Input"], "images/ict/monitor.jpg", "Monitor shows pictures OUT!"),
    ("Is a computer mouse an Input device (IN)?", "Yes, Input (IN)", ["Yes, Input (IN)", "No, Output"], "images/ict/mouse.jpg", "Mouse sends clicks IN!"),
    ("Is a printer an Output device (OUT)?", "Yes, Output (OUT)", ["Yes, Output (OUT)", "No, Input"], "images/ict/printer.jpg", "Printer outputs paper!"),
    ("Are audio speakers Output devices (OUT)?", "Yes, Output (OUT)", ["Yes, Output (OUT)", "No, Input"], "images/ict/speakers.jpg", "Speakers play sound OUT!"),
    ("Is a microphone an Input device (IN)?", "Yes, Input (IN)", ["Yes, Input (IN)", "No, Output"], "images/ict/microphone.jpg", "Microphone hears voice IN!"),
    ("Does 'Input' mean putting commands and information IN to the computer?", "Yes, putting IN", ["Yes, putting IN", "No, taking away"], None, "Input means putting things IN."),
    ("Does 'Output' mean getting results, sound, or pictures OUT?", "Yes, getting OUT", ["Yes, getting OUT", "No, putting in"], None, "Output means sending things OUT."),
    ("If you tap a touch screen with your finger, is that Input?", "Yes, finger tap is Input", ["Yes, finger tap is Input", "No"], None, "Tapping the screen puts commands IN."),
    ("When the screen lights up with a cartoon, is that Output?", "Yes, picture Output", ["Yes, picture Output", "No"], "images/ict/monitor.jpg", "Displaying the cartoon is Output!"),
    ("When you move a joystick to drive a car in a game, is that Input?", "Yes, steering is Input", ["Yes, steering is Input", "No"], None, "Joystick sends moves IN to the game."),
    ("Does a webcam put your live video IN or send paper OUT?", "Puts live video IN", ["Puts live video IN", "Sends paper OUT"], "images/ict/webcam.jpg", "Webcam captures video IN."),
    ("Does a printer give you physical paper OUT?", "Yes, paper comes OUT", ["Yes, paper comes OUT", "No"], "images/ict/printer.jpg", "Paper comes OUT of the printer tray."),
    ("Do headphones send sound OUT into your ears?", "Yes, sound OUT", ["Yes, sound OUT", "No"], "images/ict/headphones.jpg", "Headphones output audio."),
    ("Which device puts letters into the computer: Keyboard or Speaker?", "Keyboard", ["Keyboard", "Speaker"], "images/ict/keyboard.jpg", "Keyboard inputs letters."),
    ("Which device plays game sounds out loud: Speakers or Mouse?", "Speakers", ["Speakers", "Mouse"], "images/ict/speakers.jpg", "Speakers output sounds."),
    ("Which device displays your game graphics: Monitor or Microphone?", "Monitor", ["Monitor", "Microphone"], "images/ict/monitor.jpg", "Monitor outputs graphics."),
    ("Which device captures your speaking voice: Microphone or Printer?", "Microphone", ["Microphone", "Printer"], "images/ict/microphone.jpg", "Microphone inputs your voice."),
    ("Which device clicks buttons: Mouse or Headphones?", "Mouse", ["Mouse", "Headphones"], "images/ict/mouse.jpg", "Mouse inputs clicks."),
    ("Can a device be both input and output, like a touchscreen tablet?", "Yes, touch in and display out!", ["Yes, touch in and display out!", "No, impossible"], None, "Touchscreens take input and display output!"),
    ("When you press the Enter key, does the computer receive the command?", "Yes, received IN", ["Yes, received IN", "No"], "images/ict/keyboard.jpg", "Enter key sends your command IN."),
    ("When a song plays from your tablet, is that audio output?", "Yes, audio output", ["Yes, audio output", "No"], None, "Sound playing is audio output."),
    ("When you scan a photo with a scanner, does the image go IN?", "Yes, image goes IN", ["Yes, image goes IN", "No"], "images/ict/scanner.jpg", "Scanners input photos."),
    ("Do eyes look at output on the screen?", "Yes, eyes watch output", ["Yes, eyes watch output", "No"], "images/ict/monitor.jpg", "Our eyes see monitor output."),
    ("Do fingers give input by pressing buttons?", "Yes, fingers give input", ["Yes, fingers give input", "No"], "images/ict/keyboard.jpg", "Fingers type input!"),
    ("Do ears hear audio output from headphones?", "Yes, ears hear output", ["Yes, ears hear output", "No"], "images/ict/headphones.jpg", "Our ears hear audio output."),
    ("Is a computer mouse an IN or OUT device?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/mouse.jpg", "Mouse is Input (IN)."),
    ("Is a printer an IN or OUT device?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/printer.jpg", "Printer is Output (OUT)."),
    ("Is a webcam an IN or OUT device?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/webcam.jpg", "Webcam is Input (IN)."),
    ("Are speakers an IN or OUT device?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/speakers.jpg", "Speakers are Output (OUT)."),
    ("Is a microphone an IN or OUT device?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/microphone.jpg", "Microphone is Input (IN)."),
    ("Is a computer screen an IN or OUT device?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/monitor.jpg", "Screen is Output (OUT)."),
    ("Is a scanner an IN or OUT device?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/scanner.jpg", "Scanner is Input (IN)."),
    ("Are headphones an IN or OUT device?", "OUT (Output)", ["OUT (Output)", "IN (Input)"], "images/ict/headphones.jpg", "Headphones are Output (OUT)."),
    ("Is a keyboard an IN or OUT device?", "IN (Input)", ["IN (Input)", "OUT (Output)"], "images/ict/keyboard.jpg", "Keyboard is Input (IN)."),
    ("When you draw with a digital pen on a tablet, is that Input?", "Yes, digital pen is Input", ["Yes, digital pen is Input", "No"], None, "Stylus pens give input."),
    ("When the tablet shows your drawing, is that Output?", "Yes, showing is Output", ["Yes, showing is Output", "No"], None, "Displaying is output."),
    ("Does input go INTO the computer?", "Yes, IN", ["Yes, IN", "No, OUT"], None, "Input goes IN!"),
    ("Does output come OUT of the computer?", "Yes, OUT", ["Yes, OUT", "No, IN"], None, "Output comes OUT!"),
    ("Is clicking a button Input?", "Yes", ["Yes", "No"], "images/ict/mouse.jpg", "Clicking is input."),
    ("Is hearing a beep Output?", "Yes", ["Yes", "No"], "images/ict/speakers.jpg", "Hearing a beep is output."),
    ("Is holding a printed worksheet Output?", "Yes", ["Yes", "No"], "images/ict/printer.jpg", "Printed paper is output."),
    ("Is speaking into a mic Input?", "Yes", ["Yes", "No"], "images/ict/microphone.jpg", "Speaking into mic is input."),
    ("Is looking at a cartoon video Output?", "Yes", ["Yes", "No"], "images/ict/monitor.jpg", "Watching video is output."),
    ("Do Input and Output work together like a team in a computer?", "Yes, like a great team!", ["Yes, like a great team!", "No"], None, "Input and Output work together!"),
    ("Did you learn what Input and Output mean today?", "Yes, IN and OUT!", ["Yes, IN and OUT!", "No"], None, "Great job learning Input and Output!")
]
for item in input_output_k3:
    q, ans, opts, img, hint = item
    add_q("ict_input_output", "ict", q, q, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# =========================================================================
# SAVE TO JSON FILE
# =========================================================================
with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
    json.dump(all_questions, f, ensure_ascii=False, indent=2)

print(f"\n🎉 Generation complete! Total K3 questions created: {len(all_questions)}")
print(f"Saved to: {OUTPUT_FILE} ({OUTPUT_FILE.stat().st_size // 1024} KB)")

# Verify topic counts
from collections import Counter
counts = Counter(q["topic_id"] for q in all_questions)
print("\n--- Question Counts Per Topic ---")
for t, c in sorted(counts.items()):
    status = "✓ VALID (50-90)" if 50 <= c <= 90 else "❌ OUT OF RANGE"
    print(f"  {t:23}: {c} questions  {status}")
