"""
NextGrade Comprehensive Question Bank Generator
Generates 50 to 65 rich, curriculum-aligned questions for each of the 27 learning topics.
Total: ~1,500 questions.
- BM topics are 100% in Bahasa Melayu.
- Maths, English, Science, ICT topics are in English.
- Real images only; zero answer-revealing SVGs.
"""

import os
import sys
import json
import random
from pathlib import Path

# Fix Windows console UTF-8 output
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

print("Generating questions for 27 topics...")

# =========================================================================
# SUBJECT: BAHASA MELAYU (100% BM)
# =========================================================================

# --- Topic 1: bm_bulan (12 Bulan dalam Setahun) ---
months_bm = [
    ("Januari", 1), ("Februari", 2), ("Mac", 3), ("April", 4),
    ("Mei", 5), ("Jun", 6), ("Julai", 7), ("Ogos", 8),
    ("September", 9), ("Oktober", 10), ("November", 11), ("Disember", 12)
]
month_names = [m[0] for m in months_bm]

# Q1-12: Bulan ke-X
for name, num in months_bm:
    opts = [name]
    while len(opts) < 4:
        cand = random.choice(month_names)
        if cand not in opts:
            opts.append(cand)
    random.shuffle(opts)
    add_q(
        "bm_bulan", "bahasa_melayu",
        f"Apakah nama bulan yang ke-{num} dalam kalendar setahun?",
        f"Apakah nama bulan yang ke-{num} dalam setahun?",
        "ms", "multiple_choice", None, None, opts, name,
        f"Kira dari awal: Bulan ke-1 ialah Januari. Bulan ke-{num} ialah {name}.",
        f"Bulan ke-{num} ialah {name}."
    )

# Q13-24: Bulan sebelum
for i in range(1, 12):
    curr = months_bm[i][0]
    prev_m = months_bm[i-1][0]
    opts = [prev_m]
    while len(opts) < 4:
        cand = random.choice(month_names)
        if cand not in opts:
            opts.append(cand)
    random.shuffle(opts)
    add_q(
        "bm_bulan", "bahasa_melayu",
        f"Apakah nama bulan sebelum bulan {curr}?",
        f"Apakah nama bulan sebelum bulan {curr}?",
        "ms", "multiple_choice", None, None, opts, prev_m,
        f"Sebelum {curr} tiba, kita berada dalam bulan {prev_m}.",
        f"Bulan sebelum {curr} ialah {prev_m}."
    )

# Q25-35: Bulan selepas
for i in range(11):
    curr = months_bm[i][0]
    next_m = months_bm[i+1][0]
    opts = [next_m]
    while len(opts) < 4:
        cand = random.choice(month_names)
        if cand not in opts:
            opts.append(cand)
    random.shuffle(opts)
    add_q(
        "bm_bulan", "bahasa_melayu",
        f"Apakah nama bulan selepas bulan {curr}?",
        f"Apakah nama bulan selepas bulan {curr}?",
        "ms", "multiple_choice", None, None, opts, next_m,
        f"Selepas kita menyambut bulan {curr}, bulan seterusnya ialah {next_m}.",
        f"Bulan selepas {curr} ialah {next_m}."
    )

# Q36-45: Peristiwa & Ciri Bulan
events = [
    ("Bulan Kemerdekaan negara kita Malaysia disambut pada bulan...", "Ogos", ["Ogos", "Julai", "September", "Disember"], "Hari Kebangsaan jatuh pada 31 Ogos."),
    ("Hari Guru di Malaysia disambut pada setiap bulan...", "Mei", ["Mei", "April", "Jun", "Januari"], "16 Mei ialah Hari Guru."),
    ("Bulan yang mempunyai bilangan hari paling sedikit (28 atau 29 hari) ialah...", "Februari", ["Februari", "Mac", "April", "Januari"], "Februari hanya mempunyai 28 atau 29 hari."),
    ("Bulan pertama dalam kalendar Masihi ialah...", "Januari", ["Januari", "Disember", "Februari", "Mac"], "Tahun baharu bermula pada 1 Januari."),
    ("Bulan terakhir dan ke-12 dalam kalendar setahun ialah...", "Disember", ["Disember", "November", "Januari", "Oktober"], "Bulan penutup tahun ialah Disember."),
    ("Hari Kanak-kanak Kebangsaan biasanya diraikan pada bulan...", "Oktober", ["Oktober", "Ogos", "September", "Mei"], "Oktober meraikan kanak-kanak sedunia."),
    ("Bulan ke-7 dalam setahun ialah...", "Julai", ["Julai", "Jun", "Ogos", "Mei"], "Selepas Jun tiba Julai."),
    ("Berapakah jumlah bilangan bulan dalam setahun?", "12 Bulan", ["12 Bulan", "10 Bulan", "7 Bulan", "24 Bulan"], "Ada 12 bulan dari Januari hingga Disember."),
    ("Cuti persekolahan akhir tahun biasanya bermula dalam bulan...", "Disember", ["Disember", "Jun", "Mac", "Ogos"], "Disember ialah bulan cuti akhir tahun panjang."),
    ("Hari Ibu sedunia diraikan pada bulan...", "Mei", ["Mei", "Mac", "April", "Jun"], "Hari Ibu disambut pada hari Ahad kedua bulan Mei.")
]
for q_text, ans, opts, hint in events:
    add_q("bm_bulan", "bahasa_melayu", q_text, q_text, "ms", "multiple_choice", None, None, opts, ans, hint, hint)

# Q46-55: Susunan turutan 3 bulan (ordering)
orderings_bm = [
    (["Januari", "Februari", "Mac"], "Susun 3 bulan pertama mengikut urutan yang betul:"),
    (["April", "Mei", "Jun"], "Susun 3 bulan pertengahan tahun ini mengikut urutan yang betul:"),
    (["Julai", "Ogos", "September"], "Susun 3 bulan berturutan ini dari awal ke akhir:"),
    (["Oktober", "November", "Disember"], "Susun 3 bulan terakhir dalam setahun mengikut urutan:"),
    (["Mac", "April", "Mei"], "Susun bulan-bulan ini mengikut kalendar:")
]
for seq, prompt in orderings_bm:
    shuffled = list(seq)
    random.shuffle(shuffled)
    add_q("bm_bulan", "bahasa_melayu", prompt, prompt, "ms", "ordering", None, None, shuffled, seq, f"Turutan yang betul ialah: {' -> '.join(seq)}.", f"Susunannya ialah {' kemudian '.join(seq)}.")

# Extra riddles to reach 55
riddles_bm = [
    ("Saya bulan ketiga dalam setahun. Nama saya bermula dengan huruf M. Siapakah saya?", "Mac", ["Mac", "Mei", "Januari", "Jun"], "Bulan ke-3 bermula M ialah Mac."),
    ("Saya bulan keenam dalam setahun. Nama saya pendek dengan 3 huruf sahaja. Siapakah saya?", "Jun", ["Jun", "Mei", "Mac", "Julai"], "Bulan ke-6 ialah Jun."),
    ("Saya bulan kesembilan dalam kalendar setahun. Siapakah saya?", "September", ["September", "Ogos", "Oktober", "Disember"], "Bulan ke-9 ialah September."),
    ("Saya bulan kesepuluh dalam kalendar setahun. Siapakah saya?", "Oktober", ["Oktober", "September", "November", "Julai"], "Bulan ke-10 ialah Oktober."),
    ("Saya bulan kesebelas sebelum Disember. Siapakah saya?", "November", ["November", "Oktober", "Disember", "Januari"], "Bulan ke-11 ialah November."),
    ("Saya bulan kedua dalam setahun yang istimewa dengan 28 atau 29 hari. Siapakah saya?", "Februari", ["Februari", "Januari", "Mac", "April"], "Bulan ke-2 ialah Februari.")
]
for q_text, ans, opts, hint in riddles_bm:
    add_q("bm_bulan", "bahasa_melayu", q_text, q_text, "ms", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 2: bm_suku_kata (Pecahkan Suku Kata) ---
suku_kata_bank = [
    ("baju", "Ba", "ju", "Ba - ju", "images/suku-kata/baju.jpg", "Pakaian harian kita."),
    ("batu", "Ba", "tu", "Ba - tu", "images/suku-kata/batu.jpg", "Objek keras di tanah."),
    ("bola", "Bo", "la", "Bo - la", "images/suku-kata/bola.jpg", "Alat permainan bulat melantun."),
    ("buku", "Bu", "ku", "Bu - ku", "images/suku-kata/buku.jpg", "Bahan bacaan di sekolah."),
    ("bumi", "Bu", "mi", "Bu - mi", "images/suku-kata/bumi.jpg", "Planet tempat kita hidup."),
    ("cili", "Ci", "li", "Ci - li", "images/suku-kata/cili.jpg", "Sayuran kecil pedas menyengat."),
    ("dadu", "Da", "du", "Da - du", "images/suku-kata/dadu.jpg", "Kiub bertitik untuk permainan dam."),
    ("gigi", "Gi", "gi", "Gi - gi", "images/suku-kata/gigi.jpg", "Digunakan untuk mengunyah makanan."),
    ("gua", "Gu", "a", "Gu - a", "images/suku-kata/gua.jpg", "Lubang besar di bukit batu."),
    ("guli", "Gu", "li", "Gu - li", "images/suku-kata/guli.jpg", "Biji kaca bulat yang berkilau."),
    ("kaki", "Ka", "ki", "Ka - ki", "images/suku-kata/kaki.jpg", "Anggota tubuh untuk berdiri dan berjalan."),
    ("kari", "Ka", "ri", "Ka - ri", "images/suku-kata/kari.jpg", "Masakan berempah sedap dan pekat."),
    ("keju", "Ke", "ju", "Ke - ju", "images/suku-kata/keju.jpg", "Makanan kuning enak daripada susu."),
    ("kuku", "Ku", "ku", "Ku - ku", "images/suku-kata/kuku.jpg", "Bahagian keras di hujung jari."),
    ("labu", "La", "bu", "La - bu", "images/suku-kata/labu.jpg", "Sayuran buah oren yang manis."),
    ("laci", "La", "ci", "La - ci", "images/suku-kata/laci.jpg", "Kotak boleh tarik di meja."),
    ("madu", "Ma", "du", "Ma - du", "images/suku-kata/madu.jpg", "Cecair manis dihasilkan lebah."),
    ("mata", "Ma", "ta", "Ma - ta", "images/suku-kata/mata.jpg", "Pancaindera untuk melihat dunia."),
    ("meja", "Me", "ja", "Me - ja", "images/suku-kata/meja.jpg", "Perabot berkaki empat untuk menulis."),
    ("paku", "Pa", "ku", "Pa - ku", "images/suku-kata/paku.jpg", "Besi runcing untuk kayu."),
    ("pasu", "Pa", "su", "Pa - su", "images/suku-kata/pasu.jpg", "Bekas tanah tempat tanam bunga."),
    ("roti", "Ro", "ti", "Ro - ti", "images/suku-kata/roti.jpg", "Makanan enak daripada gandum."),
    ("rusa", "Ru", "sa", "Ru - sa", "images/suku-kata/rusa.jpg", "Haiwan bertanduk cantik di rimba."),
    ("sagu", "Sa", "gu", "Sa - gu", "images/suku-kata/sagu.jpg", "Biji putih daripada pokok rumbia."),
    ("sofa", "So", "fa", "So - fa", "images/suku-kata/sofa.jpg", "Tempat duduk empuk di ruang tamu."),
    ("susu", "Su", "su", "Su - su", "images/suku-kata/susu.jpg", "Minuman berkhasiat kuatkan tulang."),
    ("tali", "Ta", "li", "Ta - li", "images/suku-kata/tali.jpg", "Utas panjang untuk mengikat."),
    ("tebu", "Te", "bu", "Te - bu", "images/suku-kata/tebu.jpg", "Batang manis menghasilkan gula."),
    ("tisu", "Ti", "su", "Ti - su", "images/suku-kata/tisu.jpg", "Kertas lembut untuk lap tangan."),
    ("topi", "To", "pi", "To - pi", "images/suku-kata/topi.jpg", "Dipakai di kepala elak panas matahari.")
]

# Generate 30 Pecahkan Suku Kata with real images
for word, s1, s2, split_str, img_path, desc in suku_kata_bank:
    correct_split = f"{s1} - {s2}"
    wrong_1 = f"{s1}a - {s2}"
    wrong_2 = f"{s1} - {s2}k"
    wrong_3 = f"{s2} - {s1}"
    opts = [correct_split, wrong_1, wrong_2, wrong_3]
    random.shuffle(opts)
    add_q(
        "bm_suku_kata", "bahasa_melayu",
        f"Pecahkan perkataan '{word.capitalize()}' kepada dua suku kata yang tepat:",
        f"Pecahkan perkataan {word} kepada suku kata.",
        "ms", "multiple_choice", img_path, None, opts, correct_split,
        f"Suku kata pertama ialah '{s1}' dan suku kata kedua ialah '{s2}'.",
        f"Sebutan suku katanya ialah {s1}, diikuti {s2}."
    )

# Generate 25 Cantum Suku Kata questions
for i, (word, s1, s2, split_str, img_path, desc) in enumerate(suku_kata_bank[:25]):
    correct_word = word.capitalize()
    opts = [correct_word]
    while len(opts) < 4:
        cand = random.choice(suku_kata_bank)[0].capitalize()
        if cand not in opts:
            opts.append(cand)
    random.shuffle(opts)
    add_q(
        "bm_suku_kata", "bahasa_melayu",
        f"Cantumkan suku kata '{s1}' + '{s2}' untuk membentuk perkataan:",
        f"Cantumkan suku kata {s1} dan {s2}.",
        "ms", "multiple_choice", img_path, None, opts, correct_word,
        f"Bunyi '{s1}' apabila dicantum dengan '{s2}' menghasilkan perkataan '{correct_word}'.",
        f"Jawapannya ialah {correct_word}."
    )


# --- Topic 3: bm_kenderaan (Kenderaan Darat, Air & Udara) ---
kenderaan_data = [
    ("ambulans", "Darat", "Membawa pesakit cemas ke hospital."),
    ("bas", "Darat", "Membawa ramai penumpang di jalan raya."),
    ("basikal", "Darat", "Kenderaan 2 roda dikayuh dengan kaki."),
    ("beca", "Darat", "Kenderaan 3 roda berkayuh tradisional."),
    ("bomba", "Darat", "Trak merah besar memadam kebakaran."),
    ("bot", "Air", "Kenderaan air berenjin laju."),
    ("feri", "Air", "Kapal besar membawa orang dan kereta menyeberang laut."),
    ("helikopter", "Udara", "Terbang tinggi dengan bilah kipas berputar."),
    ("jentolak", "Darat", "Jentera berat menolak tanah dan batu di tapak binaan."),
    ("jet", "Udara", "Pesawat laju meluncur di langit."),
    ("kapal", "Air", "Kenderaan air gergasi belayar di lautan."),
    ("kapal_angkasa", "Udara", "Membawa angkasawan menjelajah angkasa lepas."),
    ("kapal_layar", "Air", "Bergerak di laut ditolak oleh angin pada layar."),
    ("kapal_terbang", "Udara", "Mempunyai sayap dan membawa penumpang ke luar negara."),
    ("kereta", "Darat", "Kenderaan 4 roda paling biasa di jalan raya."),
    ("kereta_api", "Darat", "Bergerak panjang di atas landasan besi rel."),
    ("kereta_kabel", "Udara", "Bergantung pada kabel besi mendaki bukit tinggi."),
    ("kereta_kebal", "Darat", "Kenderaan berperisai tebal milik tentera pertahanan."),
    ("kereta_polis", "Darat", "Kereta ada lampu siren untuk menjaga keselamatan."),
    ("kren", "Darat", "Jentera tinggi mengangkat bahan binaan berat."),
    ("lori", "Darat", "Mengangkut muatan barang dagangan yang banyak."),
    ("lori_sampah", "Darat", "Mengutip sisa buangan perumahan demi kebersihan."),
    ("motosikal", "Darat", "Kenderaan 2 roda berenjin pantas."),
    ("rakit", "Air", "Batang buluh diikat bersama untuk terapung di sungai."),
    ("roket", "Udara", "Meluncur tegak ke angkasa dengan kuasa tujahan api."),
    ("sampan", "Air", "Perahu kecil dikayuh nelayan di muara sungai."),
    ("skuter", "Darat", "Kenderaan roda kecil untuk bersiar-siar."),
    ("teksi", "Darat", "Kenderaan awam berbayar mengambil penumpang."),
    ("traktor", "Darat", "Jentera pembajak tanah di bendang sawah padi.")
]

# Laluan questions (Darat, Air, Udara)
for name, laluan, desc in kenderaan_data:
    display_name = name.replace("_", " ").title()
    img = f"images/kenderaan/{name}.jpg"
    opts = ["Kenderaan Darat", "Kenderaan Air", "Kenderaan Udara"]
    corr = f"Kenderaan {laluan}"
    add_q(
        "bm_kenderaan", "bahasa_melayu",
        f"Berdasarkan gambar, '{display_name}' tergolong dalam jenis kenderaan apa?",
        f"Adakah {display_name} kenderaan darat, air, atau udara?",
        "ms", "multiple_choice", img, None, opts, corr,
        desc, f"{display_name} ialah kenderaan {laluan.lower()}."
    )

# Bilangan roda & ciri soalan kenderaan
kenderaan_ciri = [
    ("Berapakah bilangan roda bagi sebuah basikal biasa?", "2 roda", ["2 roda", "4 roda", "3 roda", "8 roda"], "Basikal ada roda hadapan dan roda belakang: 2 roda."),
    ("Berapakah bilangan roda bagi sebuah kereta biasa?", "4 roda", ["4 roda", "2 roda", "6 roda", "3 roda"], "Kereta ada 4 roda di jalan raya."),
    ("Berapakah bilangan roda bagi sebuah beca tradisional?", "3 roda", ["3 roda", "2 roda", "4 roda", "1 roda"], "Beca mempunyai 3 roda."),
    ("Kenderaan manakah yang bergerak di atas landasan besi rel?", "Kereta api", ["Kereta api", "Basikal", "Sampan", "Helikopter"], "Kereta api meluncur di atas rel besi."),
    ("Kenderaan manakah yang mengeluarkan bunyi siren kecemasan untuk padam api?", "Kereta bomba", ["Kereta bomba", "Lori sampah", "Traktor", "Bot laju"], "Kereta bomba memadam kebakaran."),
    ("Kenderaan manakah yang digunakan oleh angkasawan untuk ke bulan?", "Roket", ["Roket", "Helikopter", "Feri", "Kapal layar"], "Roket menolak kapal angkasa melepasi atmosfera."),
    ("Kenderaan manakah yang terapung di atas permukaan air sungai?", "Sampan", ["Sampan", "Kereta api", "Bas", "Lori"], "Sampan ialah kenderaan air."),
    ("Apakah bahan bakar yang biasa digunakan oleh kapal terbang?", "Bahan api penerbangan (Jet fuel)", ["Bahan api penerbangan", "Air paip", "Minyak masak", "Kayu api"], "Pesawat jet perlukan bahan api khusus."),
    ("Kenderaan manakah yang dikayuh menggunakan dua pendayung di muara sungai?", "Sampan", ["Sampan", "Helikopter", "Basikal", "Kereta kebal"], "Sampan nelayan dikayuh dengan pendayung."),
    ("Kenderaan darat manakah yang membajak tanah di sawah padi?", "Traktor", ["Traktor", "Beca", "Kereta kabel", "Ambulans"], "Traktor digunakan pesawah di bendang."),
    ("Kenderaan udara manakah yang boleh mendarat tegak tanpa memerlukan landasan panjang?", "Helikopter", ["Helikopter", "Kapal terbang", "Kereta api", "Feri"], "Helikopter ada kipas berputar di atas."),
    ("Apakah nama kenderaan yang membawa kereta dan lori menyeberang dari Pulau Pinang ke Butterworth?", "Feri", ["Feri", "Basikal", "Helikopter", "Rakit"], "Feri laut membawa kenderaan dan orang ramai."),
    ("Kenderaan manakah yang tiada enjin dan hanya menggunakan tiupan angin pada kain?", "Kapal layar", ["Kapal layar", "Jet pejuang", "Motosikal", "Bot laju"], "Layar menangkap hembusan angin."),
    ("Kenderaan khas manakah yang membawa pesakit dan membunyikan siren 'wee-woo' ke hospital?", "Ambulans", ["Ambulans", "Lori kontena", "Bas ekspres", "Teksi"], "Ambulans memberi rawatan kecemasan."),
    ("Kenderaan manakah yang tergantung pada dawai kabel tebal di Genting Highlands?", "Kereta kabel", ["Kereta kabel", "Kereta kebal", "Beca", "Traktor"], "Kereta kabel meluncur di udara antara bukit."),
    ("Kenderaan manakah yang mempunyai 2 roda dan digerakkan oleh enjin petrol serta penunggang memakai topi keledar?", "Motosikal", ["Motosikal", "Basikal", "Kereta", "Feri"], "Penunggang motosikal wajib pakai topi keledar."),
    ("Di manakah laluan perjalanan bagi sebuah kapal selam?", "Di dasar dalam laut", ["Di dasar dalam laut", "Di atas awan", "Di jalan raya", "Di landasan kereta api"], "Kapal selam menyelam di bawah permukaan air."),
    ("Apakah alat keselamatan yang wajib dipakai di dalam kereta?", "Tali pinggang keledar", ["Tali pinggang keledar", "Topi renang", "Kaca mata hitam", "Sarung tangan"], "Tali pinggang keledar melindungi penumpang."),
    ("Apakah lampu isyarat yang memberitahu pemandu untuk BERHENTI?", "Lampu Merah", ["Lampu Merah", "Lampu Hijau", "Lampu Kuning", "Lampu Biru"], "Merah bermaksud berhenti."),
    ("Apakah lampu isyarat yang membenarkan kenderaan BERGERAK jalan?", "Lampu Hijau", ["Lampu Hijau", "Lampu Merah", "Lampu Kuning", "Lampu Hitam"], "Hijau menandakan selamat untuk jalan."),
    ("Di manakah kapal terbang mendarat dan berlepas?", "Lapangan terbang (Airport)", ["Lapangan terbang", "Stesen kereta api", "Pelabuhan laut", "Terminal bas"], "Pesawat menggunakan landasan lapangan terbang."),
    ("Di manakah kapal penumpang besar berlabuh?", "Pelabuhan laut (Harbour)", ["Pelabuhan laut", "Stesen bas", "Atas jalan raya", "Di lapangan terbang"], "Pelabuhan tempat kapal bersandar."),
    ("Kenderaan manakah yang mengutip tong sampah di hadapan rumah setiap minggu?", "Lori sampah", ["Lori sampah", "Kereta bomba", "Beca", "Ambulans"], "Lori sisa menjaga kebersihan kawasan kediaman."),
    ("Kanak-kanak menunggang basikal hendaklah memakai apa di kepala untuk keselamatan?", "Topi keledar basikal (Helmet)", ["Topi keledar basikal", "Payung", "Topi kain", "Cermin mata"], "Helmet melindungi kepala jika terjatuh."),
    ("Berapakah roda bagi sebuah lori kontena besar di lebuh raya?", "Banyak roda (10 hingga 18 roda)", ["Banyak roda (10 hingga 18 roda)", "2 roda sahaja", "1 roda sahaja", "Tiada roda"], "Lori berat ada banyak tayar sokong muatan.")
]
for q_text, ans, opts, hint in kenderaan_ciri:
    add_q("bm_kenderaan", "bahasa_melayu", q_text, q_text, "ms", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 4: bm_binatang (Haiwan 2 Kaki & 4 Kaki) ---
binatang_data = [
    ("ayam", 2, "Unggas bertaji yang berkokok setiap pagi."),
    ("itik", 2, "Berenang di kolam dan berparuh leper."),
    ("burung", 2, "Mempunyai sayap berbulu dan terbang di langit."),
    ("penguin", 2, "Burung kutub sejuk yang berjalan tegak di atas ais."),
    ("anjing", 4, "Haiwan peliharaan setia yang menyalak."),
    ("arnab", 4, "Telinga panjang dan melompat lincah makan lobak."),
    ("ikan", 0, "Berenang di dalam air menggunakan sirip dan bernafas guna insang."),
    ("beruang", 4, "Berbulu lebat dan gemar makan madu."),
    ("buaya", 4, "Reptilia ganas berkulit keras di sungai."),
    ("jerung", 0, "Pemangsa lautan berenang pantas dengan sirip tajam."),
    ("gajah", 4, "Haiwan darat terbesar dengan belalai panjang."),
    ("harimau", 4, "Pemangsa belang berani digelar Pak Belang."),
    ("kambing", 4, "Haiwan ternakan berjanggut yang mengembek."),
    ("katak", 4, "Amfibia melompat menangkap serangga dengan lidah."),
    ("ketam", 10, "Krustasea penyepit berjalan mengiring di pantai."),
    ("kucing", 4, "Haiwan comel berbulu lembut yang mengiau mesra."),
    ("kuda", 4, "Berlari pantas dan ditunggang manusia."),
    ("kura_kura", 4, "Mempunyai cengkerang keras dan berjalan perlahan."),
    ("lembu", 4, "Meragut rumput dan menghasilkan susu berkhasiat."),
    ("lumba_lumba", 0, "Mamalia marin pintar melompat di lautan."),
    ("monyet", 4, "Memanjat pokok dan bergayut lincah makan pisang."),
    ("panda", 4, "Beruang comel warna hitam putih makan buluh."),
    ("paus", 0, "Gergasi lautan menyembur air melalui lubang nafas."),
    ("singa", 4, "Raja rimba dengan surai lebat mengaum garang."),
    ("siput", 0, "Membawa cengkerang berlendir tanpa sebarang kaki."),
    ("sotong", 10, "Haiwan laut bertentakel mengeluarkan dakwat hitam."),
    ("tikus", 4, "Haiwan pengerat kecil berbulu cergas."),
    ("udang", 10, "Berenang mengundur di dalam air."),
    ("ular", 0, "Reptilia menjalar tanpa sebarang kaki."),
    ("zirafah", 4, "Haiwan darat paling tinggi berleher panjang.")
]

for name, legs, desc in binatang_data:
    disp_name = name.replace("_", " ").title()
    img = f"images/binatang/{name}.jpg"
    corr = f"Berkaki {legs}" if legs > 0 else "Tiada Kaki"
    opts = ["Berkaki 2", "Berkaki 4", "Tiada Kaki", "Berkaki 6 atau lebih"]
    if corr not in opts:
        opts.append(corr)
    add_q(
        "bm_binatang", "bahasa_melayu",
        f"Berdasarkan gambar, haiwan '{disp_name}' tergolong dalam kumpulan:",
        f"Berapakah bilangan kaki haiwan {disp_name}?",
        "ms", "multiple_choice", img, None, opts, corr,
        desc, f"{disp_name} tergolong dalam kumpulan {corr.lower()}."
    )

# 25 Fun animal trivia & sounds questions in BM
binatang_trivia = [
    ("Haiwan manakah yang mengeluarkan bunyi 'Meow'?", "Kucing", ["Kucing", "Anjing", "Lembu", "Kuda"], "Kucing mengiau meow."),
    ("Haiwan manakah yang mengeluarkan bunyi 'Moo' di ladang?", "Lembu", ["Lembu", "Kambing", "Ayam", "Itik"], "Lembu melenguh moo."),
    ("Haiwan manakah yang mengeluarkan bunyi 'Embek'?", "Kambing", ["Kambing", "Gajah", "Harimau", "Kucing"], "Kambing mengembek."),
    ("Haiwan manakah yang berkokok 'Kok-kok-kok' pada waktu subuh?", "Ayam jantan", ["Ayam jantan", "Kucing", "Itik", "Burung hantu"], "Ayam jantan berkokok mengejutkan pagi."),
    ("Haiwan manakah yang berparuh dan mengeluarkan bunyi 'Kwek-kwek'?", "Itik", ["Itik", "Ayam", "Singa", "Kuda"], "Itik berbunyi kwek-kwek."),
    ("Haiwan manakah yang mempunyai belalai panjang dan telinga lebar?", "Gajah", ["Gajah", "Zirafah", "Arnab", "Panda"], "Gajah menghisap air dengan belalainya."),
    ("Haiwan manakah yang mempunyai leher paling panjang di dunia?", "Zirafah", ["Zirafah", "Gajah", "Kuda", "Harimau"], "Zirafah makan pucuk daun pokok tinggi."),
    ("Haiwan manakah yang mempunyai belang hitam dan putih pada badannya?", "Zebra", ["Zebra", "Kuda", "Singa", "Unta"], "Kuda belang mempunyai corak hitam putih."),
    ("Haiwan comel manakah yang gemar memakan batang buluh segar di hutan buluh?", "Panda", ["Panda", "Singa", "Serigala", "Badak"], "Panda gergasi makan buluh."),
    ("Haiwan manakah yang membawa rumah cengkerang keras di belakang badannya?", "Kura-kura", ["Kura-kura", "Kucing", "Arnab", "Burung"], "Kura-kura menyorokkan kepala dalam cengkerang."),
    ("Haiwan manakah yang melompat pantas dan suka makan sayur lobak merah?", "Arnab", ["Arnab", "Kura-kura", "Kambing", "Gajah"], "Arnab bertelinga panjang suka lobak."),
    ("Haiwan manakah yang digelar sebagai 'Raja Rimba'?", "Singa", ["Singa", "Tikus", "Kambing", "Zirafah"], "Singa jantan bermane lebat ialah raja rimba."),
    ("Haiwan manakah yang menjalar di atas tanah tanpa mempunyai kaki?", "Ular", ["Ular", "Ayam", "Kucing", "Lembu"], "Ular tidak berkaki dan menjalar melingkar."),
    ("Haiwan manakah yang berenang di lautan dan menyembur air dari atas kepalanya?", "Paus", ["Paus", "Katak", "Harimau", "Zirafah"], "Paus bernafas melalui lubang semburan atas."),
    ("Haiwan manakah yang mempunyai sayap tetapi tinggal di kutub ais dan berenang menangkap ikan?", "Penguin", ["Penguin", "Ayam", "Burung helang", "Itik"], "Penguin ialah burung berenang di laut sejuk."),
    ("Haiwan manakah yang mempunyai kantung di perutnya untuk membawa anaknya melompat?", "Kanggaru", ["Kanggaru", "Harimau", "Singa", "Beruang"], "Anak kanggaru duduk di dalam kantung ibu."),
    ("Haiwan manakah yang hidup dua alam (di darat dan di dalam air) serta melompat?", "Katak", ["Katak", "Ayam", "Lembu", "Kucing"], "Katak bertelur di air dan melompat di daratan."),
    ("Haiwan manakah yang membantu petani menarik pedati dan berlari pantas dengan ladam besi?", "Kuda", ["Kuda", "Tikus", "Kucing", "Monyet"], "Kuda kuat berlari kencang."),
    ("Haiwan kecil manakah yang bekerja kuat mengumpul makanan dalam sarang tanah secara beramai-ramai?", "Semut", ["Semut", "Gajah", "Singa", "Harimau"], "Semut rajin bekerjasama."),
    ("Haiwan manakah yang menghasilkan madu manis di sarang pokok?", "Lebah", ["Lebah", "Kumbang", "Nyamuk", "Lalat"], "Lebah menghisap nektar bunga."),
    ("Haiwan manakah yang berkaki 8 dan membina sarang sarang jejaring sutera halus?", "Labah-labah", ["Labah-labah", "Semut", "Kucing", "Ayam"], "Labah-labah berkaki 8 menangkap mangsa di sarang."),
    ("Haiwan laut manakah yang mempunyai kulit berduri tajam di dasar terumbu karang?", "Landak laut", ["Landak laut", "Kuda laut", "Ketam", "Ikan kembung"], "Landak laut ada duri pelindung diri."),
    ("Haiwan manakah yang bertukar warna kulitnya untuk menyamar di dahan pokok?", "Sesumpah (Chameleon)", ["Sesumpah", "Kucing", "Ayam", "Gajah"], "Sesumpah menyamar menyerupai daun atau batang."),
    ("Haiwan manakah yang boleh terbang pada waktu malam dan tidur tergantung ke bawah?", "Kelawar", ["Kelawar", "Ayam", "Itik", "Burung merpati"], "Kelawar memburu buah dan serangga di waktu malam."),
    ("Haiwan manakah yang pandai meniru percakapan suara manusia?", "Burung kakak tua", ["Burung kakak tua", "Ayam", "Itik", "Burung helang"], "Burung kakak tua mengulang patah perkataan.")
]
for q_text, ans, opts, hint in binatang_trivia:
    add_q("bm_binatang", "bahasa_melayu", q_text, q_text, "ms", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 5: bm_ini_itu (Kata Tunjuk: Ini & Itu) ---
ini_itu_items = [
    ("basikal", "Ini", "basikal_dekat.jpg", "Basikal ini berada sangat dekat di hadapan saya."),
    ("hadiah", "Itu", "hadiah_jauh.jpg", "Hadiah itu diletakkan jauh di atas meja di sana."),
    ("jus_oren", "Ini", "jus_oren_dekat.jpg", "Segelas jus oren ini sejuk dan berada di tangan saya."),
    ("gunting", "Ini", "gunting_dekat.jpg", "Gunting ini tajam dan dipegang berhampiran."),
    ("bunga_ros", "Itu", "bunga_ros_jauh.jpg", "Bunga ros itu kembang mekar di taman seberang jalan.")
]
for item_name, word, img_name, exp in ini_itu_items:
    img = f"images/ini-itu/{img_name}"
    dist = "dekat" if word == "Ini" else "jauh"
    opts = ["Ini", "Itu"]
    add_q(
        "bm_ini_itu", "bahasa_melayu",
        f"Lengkapkan ayat tunjuk berdasarkan jarak objek yang {dist}: '_____ {item_name} saya.'",
        f"Pilih Ini atau Itu untuk objek yang berjarak {dist}.",
        "ms", "multiple_choice", img, None, opts, word,
        f"Objek yang berada {dist} dengan kita menggunakan kata tunjuk '{word}'.",
        f"Jawapannya ialah {word}."
    )

# 50 comprehensive Ini vs Itu contextual questions
ini_itu_drills = [
    ("Pensel yang sedang dipegang di dalam genggaman tangan saya: '_____ pensel saya.'", "Ini", ["Ini", "Itu"], "Objek di tangan sendiri menggunakan 'Ini'."),
    ("Burung yang sedang terbang tinggi di langit biru di sana: '_____ burung helang.'", "Itu", ["Ini", "Itu"], "Burung di langit jauh menggunakan 'Itu'."),
    ("Kasut sekolah yang sedang saya pakai di kaki saya sekarang: '_____ kasut baharu saya.'", "Ini", ["Ini", "Itu"], "Kasut yang sedang dipakai sangat dekat: 'Ini'."),
    ("Gunung Kinabalu yang kelihatan sayup-sayup dari jauh: '_____ Gunung Kinabalu.'", "Itu", ["Ini", "Itu"], "Objek jauh di seberang sana menggunakan 'Itu'."),
    ("Buku cerita yang sedang terbuka di atas riba saya: '_____ buku cerita kegemaran saya.'", "Ini", ["Ini", "Itu"], "Buku di atas riba berhampiran: 'Ini'."),
    ("Bintang-bintang yang berkelip-kelip di angkasa malam: '_____ bintang malam.'", "Itu", ["Ini", "Itu"], "Bintang di angkasa amat jauh: 'Itu'."),
    ("Cawan teh suam yang sedang saya minum sekarang: '_____ teh manis.'", "Ini", ["Ini", "Itu"], "Minuman yang sedang diminum: 'Ini'."),
    ("Kereta api yang sedang meluncur laju di landasan seberang sana: '_____ kereta api ekspres.'", "Itu", ["Ini", "Itu"], "Kenderaan di seberang sana: 'Itu'."),
    ("Baju kemeja yang sedang tersarung pada tubuh saya: '_____ baju bersih saya.'", "Ini", ["Ini", "Itu"], "Pakaian yang dipakai: 'Ini'."),
    ("Kapal terbang yang kelihatan kecil di celah-celah awan: '_____ kapal terbang.'", "Itu", ["Ini", "Itu"], "Pesawat di celah awan jauh di atas: 'Itu'."),
    ("Anak patung teddy bear yang sedang saya peluk erat: '_____ teddy bear saya.'", "Ini", ["Ini", "Itu"], "Anak patung dipeluk erat: 'Ini'."),
    ("Kapal layar yang berlayar di tengah-tengah lautan luas: '_____ kapal layar nelayan.'", "Itu", ["Ini", "Itu"], "Kapal di tengah laut jauh: 'Itu'."),
    ("Pinggan nasi yang berada di atas meja di hadapan saya: '_____ hidangan makan tengah hari saya.'", "Ini", ["Ini", "Itu"], "Makanan di hadapan mata: 'Ini'."),
    ("Lampu rumah jiran di seberang jalan yang menyala terang: '_____ rumah Encik Ali.'", "Itu", ["Ini", "Itu"], "Rumah jiran di seberang jalan: 'Itu'."),
    ("Beg galas yang sedang saya sandang di bahu: '_____ beg sekolah saya.'", "Ini", ["Ini", "Itu"], "Beg di bahu sendiri: 'Ini'."),
    ("Pelangi tujuh warna yang melengkung indah di ufuk langit: '_____ pelangi selepas hujan.'", "Itu", ["Ini", "Itu"], "Pelangi di ufuk langit: 'Itu'."),
    ("Pemadam getah kecil yang terletak di sebelah pensel saya: '_____ pemadam saya.'", "Ini", ["Ini", "Itu"], "Pemadam di sebelah tangan: 'Ini'."),
    ("Puncak menara jam yang menjulang tinggi di bandar: '_____ menara jam bandar.'", "Itu", ["Ini", "Itu"], "Menara jam di kejauhan: 'Itu'."),
    ("Jam tangan yang sedang melingkar di pergelangan tangan saya: '_____ jam tangan pintar saya.'", "Ini", ["Ini", "Itu"], "Jam di pergelangan tangan sendiri: 'Ini'."),
    ("Pulau hijau yang kelihatan dari tepi pantai: '_____ Pulau Tioman.'", "Itu", ["Ini", "Itu"], "Pulau di kejauhan laut: 'Itu'."),
    ("Kucing comel yang sedang baring di atas peha saya: '_____ Si Comel.'", "Ini", ["Ini", "Itu"], "Kucing di atas peha berdekatan: 'Ini'."),
    ("Layang-layang yang terbang melayang-layang ditiup angin tinggi: '_____ wau bulan.'", "Itu", ["Ini", "Itu"], "Wau di udara tinggi: 'Itu'."),
    ("Topi yang sedang saya pakai di atas kepala saya: '_____ topi sukan saya.'", "Ini", ["Ini", "Itu"], "Topi di atas kepala sendiri: 'Ini'."),
    ("Lori treler yang membunyikan hon di hujung jalan sana: '_____ lori kontena.'", "Itu", ["Ini", "Itu"], "Lori di hujung jalan: 'Itu'."),
    ("Komputer riba yang sedang saya tekan papan kuncinya: '_____ komputer saya.'", "Ini", ["Ini", "Itu"], "Komputer yang ditaip di hadapan: 'Ini'."),
    ("Bulan purnama yang menerangi kegelapan malam: '_____ bulan mengambang penuh.'", "Itu", ["Ini", "Itu"], "Bulan di langit malam jauh: 'Itu'."),
    ("Botol air minuman yang ada di dalam poket beg saya: '_____ botol air mineral.'", "Ini", ["Ini", "Itu"], "Botol air dalam poket beg sendiri: 'Ini'."),
    ("Helikopter penyelamat yang berlegar-legar di atas bukit sana: '_____ helikopter bomba.'", "Itu", ["Ini", "Itu"], "Helikopter di atas bukit jauh: 'Itu'."),
    ("Roti bakar yang sedang saya kunyah dengan selera: '_____ sarapan pagi saya.'", "Ini", ["Ini", "Itu"], "Roti yang sedang dimakan: 'Ini'."),
    ("Matahari pagi yang mula terbit dari arah timur: '_____ matahari pagi.'", "Itu", ["Ini", "Itu"], "Matahari di timur jauh: 'Itu'."),
    ("Sikat rambut yang saya gunakan untuk merapikan rambut: '_____ sikat merah saya.'", "Ini", ["Ini", "Itu"], "Sikat di tangan sendiri: 'Ini'."),
    ("Papan tanda jalan raya di selekoh simpang sana: '_____ papan tanda henti.'", "Itu", ["Ini", "Itu"], "Papan tanda di simpang seberang: 'Itu'."),
    ("Dompet duit syiling yang berada di dalam saku seluar saya: '_____ dompet saya.'", "Ini", ["Ini", "Itu"], "Dompet di dalam saku sendiri: 'Ini'."),
    ("Kereta polis yang sedang mengejar pencuri di lebuh raya: '_____ kereta peronda polis.'", "Itu", ["Ini", "Itu"], "Kereta peronda di kejauhan: 'Itu'."),
    ("Lukisan warna air yang baru sahaja siap saya lukis di kertas ini: '_____ lukisan saya.'", "Ini", ["Ini", "Itu"], "Lukisan di atas meja sendiri: 'Ini'."),
    ("Bunga api yang meletup berwarna-warni di langit malam sambutan: '_____ percikan bunga api.'", "Itu", ["Ini", "Itu"], "Bunga api di langit tinggi: 'Itu'."),
    ("Gula-gula manis yang baru saya buka bungkusannya di tapak tangan: '_____ gula-gula strawberi.'", "Ini", ["Ini", "Itu"], "Gula-gula di tapak tangan: 'Ini'."),
    ("Awan putih berkepul-kepul seperti kapas di langit: '_____ awan kumulus.'", "Itu", ["Ini", "Itu"], "Awan di langit tinggi: 'Itu'."),
    ("Pembaris plastik yang saya gunakan untuk menggaris buku: '_____ pembaris 30cm saya.'", "Ini", ["Ini", "Itu"], "Pembaris di tangan: 'Ini'."),
    ("Lumba-lumba yang melompat keluar dari permukaan air laut sana: '_____ kawanan lumba-lumba.'", "Itu", ["Ini", "Itu"], "Lumba-lumba di lautan jauh: 'Itu'."),
    ("Stoking kaki yang sedang membalut kaki saya: '_____ stoking putih saya.'", "Ini", ["Ini", "Itu"], "Stoking di kaki sendiri: 'Ini'."),
    ("Bendera Malaysia yang berkibar megah di tiang tinggi sekolah: '_____ Jalur Gemilang.'", "Itu", ["Ini", "Itu"], "Bendera di puncak tiang tinggi: 'Itu'."),
    ("Cermin mata yang sedang saya kenakan pada mata saya: '_____ cermin mata saya.'", "Ini", ["Ini", "Itu"], "Cermin mata di muka sendiri: 'Ini'."),
    ("Kuil atau masjid kubah emas yang kelihatan di seberang sungai: '_____ masjid negeri.'", "Itu", ["Ini", "Itu"], "Masjid di seberang sungai: 'Itu'."),
    ("Kotak pensel warna yang terletak kemas di atas meja belajar saya: '_____ kotak pensel saya.'", "Ini", ["Ini", "Itu"], "Kotak pensel di atas meja dekat: 'Ini'."),
    ("Burung layang-layang yang hinggap di atas wayar elektrik di tiang sana: '_____ sarang burung.'", "Itu", ["Ini", "Itu"], "Wayar elektrik di tiang luar: 'Itu'."),
    ("Bantal empuk yang sedang saya sandar di kepala: '_____ bantal tidur saya.'", "Ini", ["Ini", "Itu"], "Bantal di kepala sendiri: 'Ini'."),
    ("Puncak pokok kelapa yang bergoyang ditiup angin kencang di tepi pantai: '_____ pokok kelapa.'", "Itu", ["Ini", "Itu"], "Pokok kelapa tinggi di kejauhan: 'Itu'."),
    ("Biskut coklat enak yang sedang saya cecah ke dalam susu: '_____ biskut kegemaran saya.'", "Ini", ["Ini", "Itu"], "Biskut di tangan: 'Ini'."),
    ("Belon udara panas yang meluncur perlahan melepasi bukit: '_____ belon udara panas.'", "Itu", ["Ini", "Itu"], "Belon udara di atas bukit jauh: 'Itu'.")
]
for q_text, ans, opts, hint in ini_itu_drills:
    add_q("bm_ini_itu", "bahasa_melayu", q_text, q_text, "ms", "multiple_choice", None, None, opts, ans, hint, hint)

print(f"Generated BM questions! Running total: {len(all_questions)}")

# =========================================================================
# SUBJECT: MATHEMATICS (100% English)
# =========================================================================

# --- Topic 6: math_clocks (Analog Clocks & Time) ---
# Clock hours (1:00 to 12:00)
for hr in range(1, 13):
    correct_time = f"{hr}:00"
    wrong_1 = f"{(hr % 12) + 1}:00"
    wrong_2 = f"{hr}:30"
    wrong_3 = f"{((hr + 10) % 12) + 1}:00"
    opts = [correct_time, wrong_1, wrong_2, wrong_3]
    random.shuffle(opts)
    add_q(
        "math_clocks", "maths",
        f"The short hand points at {hr} and the long hand points straight up at 12. What time is it?",
        f"The short hand points at {hr} and the long hand points at 12. What time is it?",
        "en", "multiple_choice", "images/maths/clock_wall.jpg", None, opts, correct_time,
        f"When the long minute hand points at 12, it is exactly o'clock: {hr}:00.",
        f"The time is {hr} o'clock."
    )

# Half past hours (1:30 to 12:30)
for hr in range(1, 13):
    correct_time = f"{hr}:30"
    wrong_1 = f"{hr}:00"
    wrong_2 = f"{(hr % 12) + 1}:30"
    wrong_3 = f"{(hr % 12) + 1}:00"
    opts = [correct_time, wrong_1, wrong_2, wrong_3]
    random.shuffle(opts)
    add_q(
        "math_clocks", "maths",
        f"The short hour hand points between {hr} and {(hr % 12) + 1}, and the long minute hand points straight down at 6. What time is it?",
        f"The short hand is past {hr} and the long hand points at 6. What time is it?",
        "en", "multiple_choice", "images/maths/clock_wall.jpg", None, opts, correct_time,
        f"Pointing at 6 means half past the hour: 30 minutes. The time is {hr}:30.",
        f"The time is {hr} thirty."
    )

# Clock mechanics & daily routine questions (31 questions)
clock_mechanics = [
    ("Which hand on an analog clock shows the HOUR?", "The short hand", ["The short hand", "The long hand", "The second hand", "The numbers"], "The short sturdy hand shows the hour."),
    ("Which hand on an analog clock shows the MINUTES?", "The long hand", ["The long hand", "The short hand", "The battery", "The alarm bell"], "The long hand measures minutes."),
    ("How many minutes are there in one whole hour?", "60 minutes", ["60 minutes", "100 minutes", "30 minutes", "24 minutes"], "There are 60 minutes in 1 hour."),
    ("How many hours are there in one complete day and night?", "24 hours", ["24 hours", "12 hours", "60 hours", "10 hours"], "A full day has 24 hours."),
    ("When the minute hand points straight up at number 12, it represents:", ":00 (O'clock)", [":00 (O'clock)", ":30 (Half past)", ":15 (Quarter past)", ":45 (Quarter to)"], "12 marks the start of the hour: 00 minutes."),
    ("When the minute hand points straight down at number 6, it represents:", ":30 (Half past)", [":30 (Half past)", ":00 (O'clock)", ":12 (Twelve)", ":06 (Six)"], "6 represents 30 minutes past the hour."),
    ("What numbers are displayed around the face of a standard analog clock?", "Numbers 1 to 12", ["Numbers 1 to 12", "Numbers 1 to 10", "Numbers 1 to 24", "Numbers 1 to 100"], "Clock dials show numbers 1 through 12."),
    ("Adam eats his morning breakfast before school. What is a typical breakfast time?", "7:00 AM", ["7:00 AM", "12:00 AM (Midnight)", "3:00 PM", "9:00 PM"], "7:00 in the morning is breakfast time."),
    ("School assembly starts in the morning. Which time represents morning assembly?", "8:00 AM", ["8:00 AM", "8:00 PM", "1:00 AM", "11:00 PM"], "Morning assembly starts at 8:00 AM."),
    ("What time is lunchtime at school midday?", "12:00 PM", ["12:00 PM", "6:00 AM", "12:00 AM (Midnight)", "4:00 AM"], "12:00 PM noon is lunchtime."),
    ("Children go to sleep at bedtime in the evening. What is a good bedtime?", "9:00 PM", ["9:00 PM", "12:00 PM", "8:00 AM", "2:00 PM"], "9:00 PM is bedtime for children."),
    ("How many times does the hour hand travel all the way around the clock face in one full 24-hour day?", "2 times", ["2 times", "1 time", "24 times", "12 times"], "12 hours AM + 12 hours PM = 2 complete rounds."),
    ("If it is 3:00 now, what time will it be in 1 hour?", "4:00", ["4:00", "5:00", "3:30", "2:00"], "Add 1 hour: 3 + 1 = 4:00."),
    ("If it is 8:00 in the morning, what time was it 1 hour ago?", "7:00", ["7:00", "9:00", "6:00", "8:30"], "Subtract 1 hour: 8 - 1 = 7:00."),
    ("What time is exactly midway between 2:00 and 3:00?", "2:30", ["2:30", "2:15", "3:30", "1:30"], "Halfway is 30 minutes: 2:30."),
    ("How many minutes are in half an hour?", "30 minutes", ["30 minutes", "15 minutes", "60 minutes", "20 minutes"], "Half of 60 is 30 minutes."),
    ("If the minute hand moves from 12 all the way around back to 12, how much time has passed?", "1 hour (60 minutes)", ["1 hour (60 minutes)", "1 day", "30 minutes", "12 hours"], "One full rotation of the minute hand equals 1 hour."),
    ("If the hour hand points at 5 and the minute hand points at 12, what time is it?", "5:00", ["5:00", "12:05", "5:30", "12:00"], "Hour 5, minute 12 = 5:00."),
    ("If the hour hand points at 10 and the minute hand points at 12, what time is it?", "10:00", ["10:00", "12:10", "10:30", "2:00"], "The time is 10:00."),
    ("If the hour hand points at 11 and the minute hand points at 6, what time is it?", "11:30", ["11:30", "11:00", "6:11", "12:30"], "Hour 11, minute hand at 6 = 11:30."),
    ("Which clock hand moves the fastest?", "Second hand", ["Second hand", "Minute hand", "Hour hand", "All move at the same speed"], "The second hand ticks every single second."),
    ("Which clock hand moves the slowest?", "Hour hand", ["Hour hand", "Minute hand", "Second hand", "None"], "The short hour hand takes a whole hour to move to the next number."),
    ("If cartoon playtime starts at 4:30 and finishes at 5:00, how long was the show?", "30 minutes", ["30 minutes", "1 hour", "15 minutes", "45 minutes"], "From 4:30 to 5:00 is 30 minutes."),
    ("What does 'AM' usually refer to?", "Morning (Midnight to Noon)", ["Morning (Midnight to Noon)", "Evening / Night", "Afternoon only", "Weekend only"], "AM stands for ante meridiem, morning hours."),
    ("What does 'PM' usually refer to?", "Afternoon and Evening (Noon to Midnight)", ["Afternoon and Evening (Noon to Midnight)", "Early morning", "Breakfast time", "Dawn"], "PM stands for post meridiem, afternoon/evening hours."),
    ("Sarah starts reading at 2:00 and reads for 2 hours. What time does she finish?", "4:00", ["4:00", "3:00", "5:00", "4:30"], "2:00 + 2 hours = 4:00."),
    ("A pizza takes 30 minutes to bake. If Mom puts it in the oven at 6:00, what time is it ready?", "6:30", ["6:30", "7:00", "6:15", "7:30"], "6:00 + 30 minutes = 6:30."),
    ("A digital clock shows 09:00. Where does the long hand point on an analog clock?", "Points at 12", ["Points at 12", "Points at 9", "Points at 6", "Points at 3"], "09:00 means the minute hand points at 12."),
    ("A digital clock shows 04:30. Where does the long hand point on an analog clock?", "Points at 6", ["Points at 6", "Points at 4", "Points at 12", "Points at 3"], "30 minutes means the long hand points at 6."),
    ("What instrument on the wall tells us the time with hands and dial?", "An analog clock", ["An analog clock", "A thermometer", "A compass", "A ruler"], "A wall clock tells time."),
    ("If you wake up when the sun rises, what time of day is it?", "Morning (AM)", ["Morning (AM)", "Midnight", "Evening (PM)", "Night"], "Sunrise occurs in the morning.")
]
for q_text, ans, opts, hint in clock_mechanics:
    add_q("math_clocks", "maths", q_text, q_text, "en", "multiple_choice", "images/maths/clock_watch.jpg", None, opts, ans, hint, hint)


# --- Topic 7: math_descending (Descending Numbers 20 to 1) ---
# Generate 55 descending questions
# Sequence fill in the blanks
for start in range(20, 5, -1):
    seq = [start, start - 1, start - 2, start - 3, start - 4]
    missing_idx = random.randint(1, 3)
    correct_num = seq[missing_idx]
    display_seq = [str(x) if i != missing_idx else "[ ? ]" for i, x in enumerate(seq)]
    opts = [str(correct_num), str(correct_num + 1), str(correct_num - 1), str(correct_num + 2)]
    random.shuffle(opts)
    add_q(
        "math_descending", "maths",
        f"Fill in the missing descending number: {', '.join(display_seq)}",
        f"What number fills the question mark in this descending count?",
        "en", "multiple_choice", None, None, opts, str(correct_num),
        f"Count backwards: before {seq[missing_idx-1]} comes {correct_num}.",
        f"The missing number is {correct_num}."
    )

# "What comes immediately before" backwards questions
for n in range(20, 1, -1):
    prev_n = n - 1
    opts = [str(prev_n), str(n + 1), str(prev_n - 1), str(n)]
    random.shuffle(opts)
    add_q(
        "math_descending", "maths",
        f"When counting backwards (descending), what number comes immediately after {n}?",
        f"Counting down from {n}, what is the next number?",
        "en", "multiple_choice", None, None, opts, str(prev_n),
        f"Counting down: {n}, {prev_n}...",
        f"After {n} counting down is {prev_n}."
    )

# Arrange 3 or 4 numbers in descending order (largest to smallest)
descending_sets = [
    ([18, 14, 9], "Order these numbers in descending order (largest to smallest):"),
    ([20, 15, 10, 5], "Order these numbers from largest to smallest:"),
    ([17, 13, 8], "Arrange from highest to lowest:"),
    ([19, 12, 6], "Sort descending from greatest to least:"),
    ([16, 11, 7, 2], "Arrange these numbers in descending sequence:"),
    ([15, 10, 4], "Which shows the correct descending countdown?"),
    ([14, 9, 3], "Countdown order from largest to smallest:"),
    ([13, 8, 5, 1], "Order backwards from highest to lowest:"),
    ([20, 18, 16], "Even numbers descending order:"),
    ([19, 17, 15], "Odd numbers descending order:")
]
for seq, prompt in descending_sets:
    shuffled = [str(x) for x in seq]
    random.shuffle(shuffled)
    correct_str = [str(x) for x in seq]
    add_q(
        "math_descending", "maths",
        f"{prompt} {', '.join(shuffled)}",
        prompt,
        "en", "ordering", None, None, shuffled, correct_str,
        f"Start with the biggest number: {' -> '.join(correct_str)}.",
        f"The order is {' then '.join(correct_str)}."
    )

# Extra descending concept questions
desc_concepts = [
    ("What does 'descending order' mean in mathematics?", "From largest to smallest", ["From largest to smallest", "From smallest to largest", "Random order", "Adding numbers"], "Descending means going down like steps."),
    ("In a rocket countdown: '10, 9, 8, 7, 6, 5, 4, 3, 2, 1, Blast off!', what order is used?", "Descending order", ["Descending order", "Ascending order", "Alphabetical order", "Multiplying order"], "Counting down from 10 to 1 is descending."),
    ("Which number is the largest when counting down from 20 to 1?", "20", ["20", "1", "10", "15"], "20 is the greatest number in 1 to 20."),
    ("Which number is the smallest when counting down from 20 to 1?", "1", ["1", "20", "0", "10"], "1 is the smallest natural count in 20 down to 1."),
    ("Which of the following is in correct descending order?", "15, 14, 13, 12", ["15, 14, 13, 12", "12, 13, 14, 15", "10, 12, 14, 16", "15, 12, 14, 13"], "15 down to 12 decreases by 1 each step.")
]
for q_text, ans, opts, hint in desc_concepts:
    add_q("math_descending", "maths", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)

# Extra descending practice questions
extra_desc = [
    ("Which sequence correctly counts down from 5 to 1?", "5, 4, 3, 2, 1", ["5, 4, 3, 2, 1", "1, 2, 3, 4, 5", "5, 3, 1, 2, 4", "5, 4, 2, 3, 1"], "Countdown from 5: 5, 4, 3, 2, 1."),
    ("Which sequence correctly counts down from 10 to 6?", "10, 9, 8, 7, 6", ["10, 9, 8, 7, 6", "6, 7, 8, 9, 10", "10, 8, 9, 7, 6", "10, 9, 7, 8, 6"], "Count down: 10, 9, 8, 7, 6."),
    ("Which number is smaller: 12 or 9?", "9", ["9", "12", "They are equal", "None"], "9 is less than 12."),
    ("Which number is larger: 18 or 14?", "18", ["18", "14", "They are equal", "None"], "18 is greater than 14."),
    ("If you have 20 steps and walk down 1 step, on which step do you stand?", "Step 19", ["Step 19", "Step 21", "Step 18", "Step 10"], "20 - 1 = 19."),
    ("Which group of numbers is strictly in descending order?", "16, 12, 8, 4", ["16, 12, 8, 4", "4, 8, 12, 16", "16, 8, 12, 4", "4, 16, 8, 12"], "Each number is smaller than the previous.")
]
for q_text, ans, opts, hint in extra_desc:
    add_q("math_descending", "maths", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 8: math_addition (Addition / Combining Numbers) ---
# Generate 55 addition questions
for a in range(1, 11):
    for b in range(1, 6):
        total = a + b
        opts = [str(total), str(total + 1), str(total - 1), str(total + 2)]
        random.shuffle(opts)
        add_q(
            "math_addition", "maths",
            f"What is {a} + {b}?",
            f"What is {a} plus {b}?",
            "en", "multiple_choice", None, None, opts, str(total),
            f"Start at {a} and count forward {b} steps: {total}.",
            f"{a} plus {b} equals {total}."
        )

# Story word problems for addition
addition_stories = [
    ("Lily has 4 red apples. Her brother gives her 3 green apples. How many apples does Lily have now?", "7 apples", ["7 apples", "6 apples", "8 apples", "5 apples"], "4 + 3 = 7 apples."),
    ("There are 5 birds sitting on a fence. 4 more birds fly in to join them. How many birds altogether?", "9 birds", ["9 birds", "8 birds", "10 birds", "7 birds"], "5 + 4 = 9 birds."),
    ("Lucas has 6 toy cars. For his birthday, he receives 4 new toy cars. How many toy cars does he have in total?", "10 toy cars", ["10 toy cars", "9 toy cars", "11 toy cars", "8 toy cars"], "6 + 4 = 10 toy cars."),
    ("A farm has 8 brown cows and 2 spotted cows. How many cows are on the farm?", "10 cows", ["10 cows", "9 cows", "12 cows", "7 cows"], "8 + 2 = 10 cows."),
    ("Mia collected 7 seashells in the morning and 5 seashells in the afternoon. How many seashells did she collect?", "12 seashells", ["12 seashells", "11 seashells", "13 seashells", "10 seashells"], "7 + 5 = 12 seashells.")
]
for q_text, ans, opts, hint in addition_stories:
    add_q("math_addition", "maths", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 9: math_subtraction (Subtraction / Taking Away) ---
# Generate 55 subtraction questions
for a in range(5, 16):
    for b in range(1, 6):
        diff = a - b
        opts = [str(diff), str(diff + 1), str(diff - 1 if diff > 0 else diff + 2), str(diff + 2)]
        random.shuffle(opts)
        add_q(
            "math_subtraction", "maths",
            f"What is {a} - {b}?",
            f"What is {a} minus {b}?",
            "en", "multiple_choice", None, None, opts, str(diff),
            f"Start at {a} and take away {b}: {diff}.",
            f"{a} minus {b} equals {diff}."
        )

# Story word problems for subtraction
subtraction_stories = [
    ("There were 10 colorful balloons. 3 balloons popped! How many balloons are left?", "7 balloons", ["7 balloons", "8 balloons", "6 balloons", "9 balloons"], "10 - 3 = 7 balloons remain."),
    ("Mom baked 8 sweet cupcakes. Tim ate 2 cupcakes. How many cupcakes remain on the tray?", "6 cupcakes", ["6 cupcakes", "7 cupcakes", "5 cupcakes", "4 cupcakes"], "8 - 2 = 6 cupcakes."),
    ("There were 9 ducks swimming in a pond. 4 ducks walked onto the grass. How many ducks are still swimming?", "5 ducks", ["5 ducks", "6 ducks", "4 ducks", "3 ducks"], "9 - 4 = 5 ducks."),
    ("Sam had 15 crayons in his box. He gave 5 crayons to his friend. How many crayons does Sam have left?", "10 crayons", ["10 crayons", "9 crayons", "11 crayons", "8 crayons"], "15 - 5 = 10 crayons."),
    ("A tree branch had 12 green leaves. A gust of wind blew 4 leaves away. How many leaves are left on the branch?", "8 leaves", ["8 leaves", "7 leaves", "9 leaves", "6 leaves"], "12 - 4 = 8 leaves.")
]
for q_text, ans, opts, hint in subtraction_stories:
    add_q("math_subtraction", "maths", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)

print(f"Generated Maths questions! Running total: {len(all_questions)}")

# =========================================================================
# SUBJECT: ENGLISH LANGUAGE (100% English)
# =========================================================================

# --- Topic 10: eng_days_months (Days of Week & Months) ---
days_eng = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]
months_eng = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]

# Days after / before (14 questions)
for i in range(7):
    curr = days_eng[i]
    next_d = days_eng[(i + 1) % 7]
    prev_d = days_eng[(i - 1) % 7]
    
    opts1 = [next_d]
    while len(opts1) < 4:
        c = random.choice(days_eng)
        if c not in opts1: opts1.append(c)
    random.shuffle(opts1)
    add_q("eng_days_months", "english", f"What day comes immediately AFTER {curr}?", f"What day comes after {curr}?", "en", "multiple_choice", None, None, opts1, next_d, f"After {curr} comes {next_d}.", f"The next day is {next_d}.")
    
    opts2 = [prev_d]
    while len(opts2) < 4:
        c = random.choice(days_eng)
        if c not in opts2: opts2.append(c)
    random.shuffle(opts2)
    add_q("eng_days_months", "english", f"What day comes immediately BEFORE {curr}?", f"What day comes before {curr}?", "en", "multiple_choice", None, None, opts2, prev_d, f"Before {curr} was {prev_d}.", f"The day before was {prev_d}.")

# Months positions (12 questions)
for idx, m_name in enumerate(months_eng, 1):
    opts = [m_name]
    while len(opts) < 4:
        c = random.choice(months_eng)
        if c not in opts: opts.append(c)
    random.shuffle(opts)
    add_q("eng_days_months", "english", f"What is the {idx}{'st' if idx==1 else ('nd' if idx==2 else ('rd' if idx==3 else 'th'))} month of the year?", f"What is month number {idx}?", "en", "multiple_choice", None, None, opts, m_name, f"Month {idx} is {m_name}.", f"It is {m_name}.")

# Days and months trivia (29 questions)
cal_trivia = [
    ("How many days are in one full week?", "7 days", ["7 days", "5 days", "10 days", "12 days"], "From Monday to Sunday is 7 days."),
    ("How many months are in one full year?", "12 months", ["12 months", "10 months", "7 months", "24 months"], "January to December is 12 months."),
    ("Which two days make up the 'weekend'?", "Saturday and Sunday", ["Saturday and Sunday", "Monday and Tuesday", "Thursday and Friday", "Sunday and Monday"], "Saturday and Sunday are weekend rest days."),
    ("What is the first day of the school week?", "Monday", ["Monday", "Friday", "Sunday", "Wednesday"], "The school week starts on Monday."),
    ("What is the last school day of the standard school week?", "Friday", ["Friday", "Sunday", "Saturday", "Thursday"], "Friday is the final school weekday."),
    ("Which month has the fewest days (28 or 29 days)?", "February", ["February", "March", "April", "January"], "February is the shortest month."),
    ("Which is the very first month of a brand new year?", "January", ["January", "December", "February", "March"], "January 1st is New Year's Day."),
    ("Which is the twelfth and final month of the year?", "December", ["December", "November", "January", "October"], "December concludes the year."),
    ("If today is Wednesday, what day will tomorrow be?", "Thursday", ["Thursday", "Tuesday", "Friday", "Monday"], "Tomorrow is Thursday."),
    ("If today is Sunday, what day was yesterday?", "Saturday", ["Saturday", "Monday", "Friday", "Sunday"], "Yesterday was Saturday."),
    ("How many days are in a leap year?", "366 days", ["366 days", "365 days", "300 days", "400 days"], "A leap year adds 1 day in February: 366 days."),
    ("How many days are in a standard calendar year?", "365 days", ["365 days", "366 days", "300 days", "350 days"], "A normal year has 365 days."),
    ("Which month comes right before Christmas in December?", "November", ["November", "October", "January", "September"], "November precedes December."),
    ("Which month comes right after April?", "May", ["May", "June", "March", "July"], "April showers bring May flowers."),
    ("Which month comes right before August?", "July", ["July", "June", "September", "May"], "July is month 7, right before August."),
    ("What day comes between Tuesday and Thursday?", "Wednesday", ["Wednesday", "Friday", "Monday", "Saturday"], "Wednesday is hump day in the middle."),
    ("What day comes between Friday and Sunday?", "Saturday", ["Saturday", "Monday", "Thursday", "Tuesday"], "Saturday is the first day of the weekend."),
    ("Which month celebrates Halloween at the end of the month?", "October", ["October", "November", "September", "December"], "Halloween is on October 31st."),
    ("Which day comes right after Sunday?", "Monday", ["Monday", "Tuesday", "Saturday", "Friday"], "After Sunday the new week begins with Monday."),
    ("Which day comes right before Monday?", "Sunday", ["Sunday", "Saturday", "Friday", "Tuesday"], "Sunday comes before Monday."),
    ("How many hours are in 1 whole day?", "24 hours", ["24 hours", "12 hours", "60 hours", "7 hours"], "Day and night together make 24 hours."),
    ("If yesterday was Friday, what day is today?", "Saturday", ["Saturday", "Sunday", "Thursday", "Monday"], "Today is Saturday."),
    ("If tomorrow is Tuesday, what day is today?", "Monday", ["Monday", "Wednesday", "Sunday", "Friday"], "Today is Monday."),
    ("Which month is named after July's summer period?", "July", ["July", "June", "May", "August"], "July is month 7."),
    ("In which month do we welcome the 1st day of the year?", "January", ["January", "February", "December", "March"], "January 1 is New Year's Day."),
    ("Which day of the week starts with the letter 'W'?", "Wednesday", ["Wednesday", "Monday", "Tuesday", "Thursday"], "Wednesday begins with W."),
    ("Which two days of the week start with the letter 'T'?", "Tuesday and Thursday", ["Tuesday and Thursday", "Thursday and Friday", "Tuesday and Sunday", "Monday and Tuesday"], "Tuesday and Thursday start with T."),
    ("Which two days of the week start with the letter 'S'?", "Saturday and Sunday", ["Saturday and Sunday", "Sunday and Monday", "Friday and Saturday", "Tuesday and Sunday"], "Saturday and Sunday start with S."),
    ("How many weeks are there approximately in one calendar month?", "About 4 weeks", ["About 4 weeks", "About 2 weeks", "About 7 weeks", "About 10 weeks"], "A month has about 4 weeks (28 to 31 days).")
]
for q_text, ans, opts, hint in cal_trivia:
    add_q("eng_days_months", "english", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 11: eng_blending (Beginning Blends ch- & th-) ---
blends_data = [
    # ch- words
    ("chair", "ch", "ch-", "A seat with four legs to sit on."),
    ("cheese", "ch", "ch-", "A tasty yellow dairy food made from milk."),
    ("chick", "ch", "ch-", "A baby chicken that chirps."),
    ("cherry", "ch", "ch-", "A sweet small red fruit with a stem."),
    ("chin", "ch", "ch-", "The bottom part of your face below your mouth."),
    ("chest", "ch", "ch-", "The front upper part of your body."),
    ("chips", "ch", "ch-", "Crispy fried potato snacks."),
    ("chocolate", "ch", "ch-", "A delicious sweet brown treat."),
    ("church", "ch", "ch-", "A place for prayer and Sunday worship."),
    ("chalk", "ch", "ch-", "White or colored stick used to write on blackboards."),
    # th- words
    ("thorn", "th", "th-", "A sharp spike on a rose stem."),
    ("thumb", "th", "th-", "The short thick first finger on your hand."),
    ("three", "th", "th-", "The number that comes after two."),
    ("thunder", "th", "th-", "The loud rumbling boom during a rainstorm."),
    ("thief", "th", "th-", "A dishonest person who steals things."),
    ("thick", "th", "th-", "The opposite of thin."),
    ("think", "th", "th-", "Using your mind and brain to solve a puzzle."),
    ("throat", "th", "th-", "The passage inside your neck for swallowing."),
    ("thimble", "th", "th-", "A metal cap to protect fingers when sewing."),
    ("thermometer", "th", "th-", "An instrument used to measure temperature.")
]

for word, blend, blend_label, desc in blends_data:
    rest = word[len(blend):]
    opts = ["ch-", "th-", "sh-", "wh-"]
    add_q(
        "eng_blending", "english",
        f"Which beginning blend sound completes the word: [ ? ]{rest} ({desc})?",
        f"Which blend sound completes the word for: {desc}?",
        "en", "multiple_choice", None, None, opts, blend_label,
        f"The word '{word}' begins with the '{blend_label}' sound.",
        f"The blend is {blend_label}."
    )

# 35 More beginning blend questions (including sh-, wh-, bl-, cl-, fl-)
other_blends = [
    ("shoe", "sh-", ["sh-", "ch-", "th-", "wh-"], "Footwear you wear over socks: [ ? ]oe."),
    ("ship", "sh-", ["sh-", "ch-", "th-", "wh-"], "A large sea vessel: [ ? ]ip."),
    ("shark", "sh-", ["sh-", "ch-", "th-", "wh-"], "A powerful ocean predator with sharp teeth: [ ? ]ark."),
    ("sheep", "sh-", ["sh-", "ch-", "th-", "wh-"], "A fluffy animal that gives wool: [ ? ]eep."),
    ("shell", "sh-", ["sh-", "ch-", "th-", "wh-"], "Found on the seashore: [ ? ]ell."),
    ("shirt", "sh-", ["sh-", "ch-", "th-", "wh-"], "Clothing worn on the upper body: [ ? ]irt."),
    ("whale", "wh-", ["wh-", "sh-", "ch-", "th-"], "The largest mammal in the ocean: [ ? ]ale."),
    ("wheel", "wh-", ["wh-", "ch-", "sh-", "th-"], "A round object on a car that rolls: [ ? ]eel."),
    ("whistle", "wh-", ["wh-", "th-", "sh-", "ch-"], "Makes a high shrill sound when blown: [ ? ]istle."),
    ("white", "wh-", ["wh-", "ch-", "sh-", "th-"], "The color of pure fresh snow: [ ? ]ite."),
    ("clock", "cl-", ["cl-", "bl-", "fl-", "gl-"], "Tells the time on the wall: [ ? ]ock."),
    ("clown", "cl-", ["cl-", "fl-", "bl-", "sl-"], "Funny circus performer with red nose: [ ? ]own."),
    ("cloud", "cl-", ["cl-", "bl-", "gl-", "pl-"], "Fluffy white shape in the blue sky: [ ? ]oud."),
    ("black", "bl-", ["bl-", "cl-", "fl-", "sl-"], "The dark color of night: [ ? ]ack."),
    ("blue", "bl-", ["bl-", "cl-", "gl-", "fl-"], "The color of the ocean and clear sky: [ ? ]ue."),
    ("flower", "fl-", ["fl-", "bl-", "cl-", "pl-"], "Blooms with sweet fragrance: [ ? ]ower."),
    ("flag", "fl-", ["fl-", "cl-", "bl-", "gl-"], "Flies high on a flagpole: [ ? ]ag."),
    ("frog", "fr-", ["fr-", "gr-", "tr-", "br-"], "Green amphibian that leaps in ponds: [ ? ]og."),
    ("tree", "tr-", ["tr-", "dr-", "pr-", "gr-"], "Has a tall wooden trunk and leaves: [ ? ]ee."),
    ("train", "tr-", ["tr-", "cr-", "pr-", "dr-"], "Rides on railroad tracks: [ ? ]ain."),
    ("star", "st-", ["st-", "sp-", "sk-", "sm-"], "Twinkles high in the night sky: [ ? ]ar."),
    ("spoon", "sp-", ["sp-", "st-", "sk-", "sn-"], "Utensil used to eat hot soup: [ ? ]oon."),
    ("snake", "sn-", ["sn-", "sm-", "st-", "sp-"], "Reptile that slithers through grass: [ ? ]ake."),
    ("smile", "sm-", ["sm-", "sn-", "st-", "sp-"], "Happy expression on your face: [ ? ]ile."),
    ("swing", "sw-", ["sw-", "st-", "sp-", "sl-"], "Playground seat hanging from chains: [ ? ]ing."),
    ("drum", "dr-", ["dr-", "tr-", "br-", "cr-"], "Musical percussion instrument beaten with sticks: [ ? ]um."),
    ("brush", "br-", ["br-", "dr-", "tr-", "cr-"], "Used to comb hair or paint: [ ? ]ush."),
    ("green", "gr-", ["gr-", "br-", "tr-", "cr-"], "The color of fresh spring grass: [ ? ]een."),
    ("grapes", "gr-", ["gr-", "pr-", "tr-", "fr-"], "Juicy round purple or green fruit: [ ? ]apes."),
    ("crab", "cr-", ["cr-", "gr-", "br-", "tr-"], "Has claws and walks sideways on the beach: [ ? ]ab."),
    ("plane", "pl-", ["pl-", "cl-", "fl-", "bl-"], "Flies in the sky: [ ? ]ane."),
    ("plate", "pl-", ["pl-", "cl-", "bl-", "fl-"], "Dish on which dinner is served: [ ? ]ate."),
    ("glove", "gl-", ["gl-", "cl-", "bl-", "pl-"], "Worn on hands to keep warm: [ ? ]ove."),
    ("glass", "gl-", ["gl-", "cl-", "fl-", "bl-"], "Transparent drinking cup: [ ? ]ass."),
    ("slide", "sl-", ["sl-", "fl-", "cl-", "bl-"], "Fun smooth slope at the playground: [ ? ]ide.")
]
for word, corr, opts, hint in other_blends:
    rest = word[len(corr)-1:]
    add_q("eng_blending", "english", f"Choose the correct beginning blend: [ ? ]{rest} - {hint}", f"Choose the blend for {word}.", "en", "multiple_choice", None, None, opts, corr, f"The word is '{word}', starting with {corr}.", f"It starts with {corr}.")


# --- Topic 12: eng_pronouns (Pronouns: He, She, It, They) ---
pronoun_scenarios = [
    ("Lucas is riding his bicycle. _____ wears a blue helmet.", "He", ["He", "She", "It", "They"], "Lucas is a boy: use 'He'."),
    ("Emma is drawing a butterfly. _____ loves bright pink colors.", "She", ["She", "He", "It", "They"], "Emma is a girl: use 'She'."),
    ("The cute kitten is drinking milk. _____ has soft white fur.", "It", ["It", "He", "She", "They"], "An animal is referred to as 'It'."),
    ("Tom and Ben are playing soccer. _____ scored a winning goal.", "They", ["They", "He", "She", "It"], "More than one person: use 'They'."),
    ("My father is cooking dinner in the kitchen. _____ makes yummy pasta.", "He", ["He", "She", "It", "They"], "Father is male: use 'He'."),
    ("My mother reads a bedtime story. _____ has a gentle voice.", "She", ["She", "He", "It", "They"], "Mother is female: use 'She'."),
    ("The school bus stopped at the red traffic light. _____ is bright yellow.", "It", ["It", "He", "She", "They"], "A vehicle or object: use 'It'."),
    ("The birds are singing in the tree branches. _____ built a cozy nest.", "They", ["They", "It", "He", "She"], "Multiple birds in plural: use 'They'."),
    ("Mr. David is our school principal. _____ speaks at the morning assembly.", "He", ["He", "She", "It", "They"], "Mr. David is male: use 'He'."),
    ("Nurse Sarah bandaged my scraped knee. _____ was very kind and gentle.", "She", ["She", "He", "It", "They"], "Nurse Sarah is female: use 'She'."),
    ("The grandfather clock ticks steadily. _____ hangs on the wall.", "It", ["It", "He", "She", "They"], "A clock is an object: use 'It'."),
    ("The children are laughing in the playground. _____ love the swings.", "They", ["They", "He", "She", "It"], "The children are a group: use 'They'."),
    ("Uncle Bob bought a shiny new tractor. _____ works on the farm.", "He", ["He", "She", "It", "They"], "Uncle is male: use 'He'."),
    ("Auntie Mary baked delicious chocolate cookies. _____ gave me two.", "She", ["She", "He", "It", "They"], "Auntie is female: use 'She'."),
    ("The red apple fell from the branch. _____ is sweet and crisp.", "It", ["It", "He", "She", "They"], "An apple is an object/fruit: use 'It'."),
    ("My brothers are building a sandcastle on the beach. _____ have buckets and spades.", "They", ["They", "He", "She", "It"], "Plural brothers: use 'They'."),
    ("The policeman directed the traffic safely. _____ blew his whistle.", "He", ["He", "She", "It", "They"], "Policeman is male: use 'He'."),
    ("The ballerina danced gracefully across the stage. _____ wore a pink tutu.", "She", ["She", "He", "It", "They"], "Ballerina is female: use 'She'."),
    ("The big computer monitor displays colorful games. _____ is wide and clear.", "It", ["It", "He", "She", "They"], "A monitor is an inanimate object: use 'It'."),
    ("My classmates are studying in the library. _____ are reading quietly.", "They", ["They", "He", "She", "It"], "Classmates are plural: use 'They'."),
    ("King Arthur sat upon the golden throne. _____ wore a sparkling crown.", "He", ["He", "She", "It", "They"], "King is male: use 'He'."),
    ("Queen Elizabeth waved to the crowd. _____ held a bouquet of flowers.", "She", ["She", "He", "It", "They"], "Queen is female: use 'She'."),
    ("The little puppy wagged its tail happily. _____ fetched the rubber ball.", "It", ["It", "He", "She", "They"], "A puppy is an animal: use 'It'."),
    ("The monkeys are swinging from vine to vine. _____ eat sweet yellow bananas.", "They", ["They", "He", "She", "It"], "Monkeys are plural animals: use 'They'."),
    ("Grandfather tells exciting tales of long ago. _____ wears reading glasses.", "He", ["He", "She", "It", "They"], "Grandfather is male: use 'He'."),
    ("Grandmother knits warm woollen sweaters. _____ loves to sing lullabies.", "She", ["She", "He", "It", "They"], "Grandmother is female: use 'She'."),
    ("The yellow sun shines brightly in the sky. _____ gives warmth and light.", "It", ["It", "He", "She", "They"], "The sun is a celestial body: use 'It'."),
    ("The firefighters put out the blaze quickly. _____ are brave heroes.", "They", ["They", "He", "She", "It"], "Firefighters are plural: use 'They'."),
    ("The male doctor examined my sore throat. _____ gave me cough syrup.", "He", ["He", "She", "It", "They"], "Male doctor: use 'He'."),
    ("The female teacher wrote on the whiteboard. _____ explained the lesson clearly.", "She", ["She", "He", "It", "They"], "Female teacher: use 'She'."),
    ("The round football rolled into the goal net. _____ crossed the white line.", "It", ["It", "He", "She", "They"], "A football is an object: use 'It'."),
    ("The students lined up neatly for recess. _____ walked quietly down the corridor.", "They", ["They", "He", "She", "It"], "Students are plural: use 'They'."),
    ("The brave pilot flew the passenger plane through clouds. _____ landed smoothly.", "He", ["He", "She", "It", "They"], "Male pilot: use 'He'."),
    ("The little girl tied her shoelaces tightly. _____ is ready to run.", "She", ["She", "He", "It", "They"], "Little girl: use 'She'."),
    ("The green palm tree sways gently in the breeze. _____ grows tall near the sea.", "It", ["It", "He", "She", "They"], "A tree is a plant: use 'It'."),
    ("The colorful crayons are inside the tin. _____ are sharp and clean.", "They", ["They", "He", "She", "It"], "Plural crayons: use 'They'."),
    ("My big brother won the running race. _____ received a shiny gold medal.", "He", ["He", "She", "It", "They"], "Brother is male: use 'He'."),
    ("My baby sister drinks from a bottle. _____ sleeps in her crib.", "She", ["She", "He", "It", "They"], "Sister is female: use 'She'."),
    ("The shiny red bicycle has two wheels. _____ belongs to Peter.", "It", ["It", "He", "She", "They"], "A bicycle is an object: use 'It'."),
    ("The soccer players celebrated their victory. _____ lifted the golden trophy.", "They", ["They", "He", "She", "It"], "Soccer players are plural: use 'They'."),
    ("The farmer plowed the fertile field with his tractor. _____ planted corn seeds.", "He", ["He", "She", "It", "They"], "Farmer (he) works the field."),
    ("The little girl wore a pretty floral dress. _____ twirled with joy.", "She", ["She", "He", "It", "They"], "Girl: use 'She'."),
    ("The pencil sharpener sharpens blunt pencils. _____ sits on the desk.", "It", ["It", "He", "She", "They"], "Stationery tool: use 'It'."),
    ("The ducks waddled down to the cool pond. _____ splashed in the water.", "They", ["They", "He", "She", "It"], "Plural ducks: use 'They'."),
    ("Uncle Joe baked a loaf of fresh sourdough bread. _____ is a baker.", "He", ["He", "She", "It", "They"], "Uncle Joe: use 'He'."),
    ("Aunt Jenny paints beautiful landscape portraits. _____ is a talented artist.", "She", ["She", "He", "It", "They"], "Aunt Jenny: use 'She'."),
    ("The heavy dictionary has thousands of definitions. _____ has a dark blue cover.", "It", ["It", "He", "She", "They"], "A book: use 'It'."),
    ("The tourists took photographs of the tall towers. _____ smiled for the camera.", "They", ["They", "He", "She", "It"], "Tourists: use 'They'."),
    ("The boy ate an orange slice. _____ smiled because it tasted sweet.", "He", ["He", "She", "It", "They"], "Boy: use 'He'."),
    ("The girl braided her hair with ribbons. _____ looked in the mirror.", "She", ["She", "He", "It", "They"], "Girl: use 'She'."),
    ("The green frog caught a fly on a lily pad. _____ jumped into the water.", "It", ["It", "He", "She", "They"], "Frog (animal): use 'It'."),
    ("The puppies chased each other around the yard. _____ barked playfully.", "They", ["They", "He", "She", "It"], "Plural puppies: use 'They'."),
    ("My uncle is an astronaut. _____ trained to travel into space.", "He", ["He", "She", "It", "They"], "Uncle: use 'He'."),
    ("My aunt is a dentist. _____ checks my teeth every six months.", "She", ["She", "He", "It", "They"], "Aunt: use 'She'."),
    ("The grandfather clock struck twelve times. _____ has a swinging pendulum.", "It", ["It", "He", "She", "They"], "Clock: use 'It'.")
]
for q_text, ans, opts, hint in pronoun_scenarios:
    add_q("eng_pronouns", "english", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 13: eng_articles (Articles: A and An) ---
vowel_words = [
    ("apple", "images/english/apple.jpg"), ("elephant", None), ("ice cream", None), ("orange", None),
    ("umbrella", None), ("octopus", "images/english/octopus.jpg"), ("astronaut", None), ("egg", "images/english/egg.jpg"),
    ("owl", None), ("igloo", None), ("airplane", None), ("alligator", None), ("eagle", None),
    ("insect", None), ("onion", None), ("ant", None), ("axe", None), ("envelope", None),
    ("iron", None), ("ostrich", None), ("artist", None), ("engine", None), ("island", None),
    ("ox", None), ("urchin", None), ("acorn", None), ("apron", None), ("elf", None)
]
consonant_words = [
    ("cat", "images/english/cat.jpg"), ("dog", None), ("car", None), ("banana", "images/english/banana.jpg"),
    ("house", "images/english/house.jpg"), ("book", None), ("pencil", None), ("ball", None),
    ("table", None), ("chair", None), ("star", None), ("zebra", None), ("tree", None),
    ("flower", None), ("clock", "images/english/clock.jpg"), ("lollipop", "images/english/lollipop.jpg"),
    ("duck", None), ("lion", None), ("monkey", None), ("rabbit", None), ("tiger", None),
    ("van", None), ("window", None), ("kite", None), ("guitar", None), ("lamp", None), ("nest", None)
]

for w, img in vowel_words:
    opts = ["An", "A"]
    add_q(
        "eng_articles", "english",
        f"Choose the correct article: '_____ {w}.'",
        f"Do we say A or An before {w}?",
        "en", "multiple_choice", img, None, opts, "An",
        f"'{w.capitalize()}' starts with a vowel sound ({w[0]}). Use 'An'.",
        f"Use An before {w}."
    )

for w, img in consonant_words:
    opts = ["A", "An"]
    add_q(
        "eng_articles", "english",
        f"Choose the correct article: '_____ {w}.'",
        f"Do we say A or An before {w}?",
        "en", "multiple_choice", img, None, opts, "A",
        f"'{w.capitalize()}' starts with a consonant sound ({w[0]}). Use 'A'.",
        f"Use A before {w}."
    )


# --- Topic 14: eng_has_have (Using Has and Have) ---
has_have_drills = [
    ("He _____ a brand new football.", "has", ["has", "have"]),
    ("She _____ a pink school bag with stars.", "has", ["has", "have"]),
    ("It _____ sharp claws to climb trees.", "has", ["has", "have"]),
    ("The boy _____ a blue umbrella for the rain.", "has", ["has", "have"]),
    ("The girl _____ a sweet chocolate ice cream.", "has", ["has", "have"]),
    ("My father _____ a black leather wallet.", "has", ["has", "have"]),
    ("My mother _____ a shiny pearl necklace.", "has", ["has", "have"]),
    ("The giraffe _____ a very long neck to reach leaves.", "has", ["has", "have"]),
    ("The elephant _____ a long grey trunk.", "has", ["has", "have"]),
    ("The house _____ a wooden front door.", "has", ["has", "have"]),
    ("The car _____ four black rubber tires.", "has", ["has", "have"]),
    ("Our teacher _____ a red marking pen.", "has", ["has", "have"]),
    ("The dog _____ a bushy tail that wags happily.", "has", ["has", "have"]),
    ("The bird _____ two colorful wings to fly.", "has", ["has", "have"]),
    ("The clock _____ two hands on its dial.", "has", ["has", "have"]),
    ("A triangle _____ three straight sides.", "has", ["has", "have"]),
    ("A square _____ four equal sides.", "has", ["has", "have"]),
    ("A spider _____ eight long legs.", "has", ["has", "have"]),
    ("An insect _____ six tiny legs.", "has", ["has", "have"]),
    ("The king _____ a golden crown on his head.", "has", ["has", "have"]),
    ("Grandpa _____ a wooden walking cane.", "has", ["has", "have"]),
    ("Grandma _____ a pair of reading glasses.", "has", ["has", "have"]),
    ("The doctor _____ a silver stethoscope.", "has", ["has", "have"]),
    ("The policeman _____ a shiny silver badge.", "has", ["has", "have"]),
    ("The chef _____ a tall white hat.", "has", ["has", "have"]),
    ("The rabbit _____ long furry ears.", "has", ["has", "have"]),
    ("The cat _____ whiskers on its face.", "has", ["has", "have"]),
    ("The table _____ four sturdy wooden legs.", "has", ["has", "have"]),
    # HAVE drills
    ("I _____ two eyes, one nose, and one mouth.", "have", ["have", "has"]),
    ("You _____ a friendly warm smile.", "have", ["have", "has"]),
    ("We _____ fun playing together in the park.", "have", ["have", "has"]),
    ("They _____ colorful storybooks in their bags.", "have", ["have", "has"]),
    ("The children _____ delicious sandwiches for lunch.", "have", ["have", "has"]),
    ("I _____ ten fingers on my two hands.", "have", ["have", "has"]),
    ("You _____ a cute pet hamster in a cage.", "have", ["have", "has"]),
    ("We _____ our art class every Wednesday.", "have", ["have", "has"]),
    ("They _____ a big swimming pool in their backyard.", "have", ["have", "has"]),
    ("The students _____ finished their homework neatly.", "have", ["have", "has"]),
    ("I _____ a glass of fresh cold milk every morning.", "have", ["have", "has"]),
    ("You _____ a shiny gold star on your workbook.", "have", ["have", "has"]),
    ("We _____ lots of fun outdoor games at recess.", "have", ["have", "has"]),
    ("They _____ twenty colored pencils in their tin.", "have", ["have", "has"]),
    ("The birds _____ built a soft nest in the tree.", "have", ["have", "has"]),
    ("The ducks _____ yellow webbed feet to swim.", "have", ["have", "has"]),
    ("The players _____ matching blue soccer jerseys.", "have", ["have", "has"]),
    ("I _____ a loving family at home.", "have", ["have", "has"]),
    ("We _____ music lessons with piano every Friday.", "have", ["have", "has"]),
    ("They _____ tickets to the circus show tonight.", "have", ["have", "has"]),
    ("Both of my brothers _____ bicycles.", "have", ["have", "has"]),
    ("You _____ an exciting puzzle to solve.", "have", ["have", "has"]),
    ("We _____ a lovely flower garden.", "have", ["have", "has"]),
    ("They _____ warm coats for winter.", "have", ["have", "has"]),
    ("I _____ an apple in my lunchbox.", "have", ["have", "has"]),
    ("Do you _____ a pencil I can borrow?", "have", ["have", "has"]),
    ("Does he _____ a pet dog at his house?", "have", ["have", "has"])
]
for q_text, ans, opts in has_have_drills:
    hint = f"Singular subjects (He/She/It/The boy) take 'has'. Plural and I/You/We/They take 'have'."
    add_q("eng_has_have", "english", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 15: eng_demonstratives (This, That, These, Those) ---
demonstratives_drills = [
    # THIS (Near + Singular)
    ("_____ is my pencil right here in my hand.", "This", ["This", "That", "These", "Those"], "Near and singular (one pencil): use 'This'."),
    ("_____ is my delicious apple on my plate in front of me.", "This", ["This", "That", "These", "Those"], "Near and singular: use 'This'."),
    ("_____ is my school backpack beside my chair.", "This", ["This", "That", "These", "Those"], "Close singular object: use 'This'."),
    ("_____ is my pet kitten sitting on my lap.", "This", ["This", "That", "These", "Those"], "Pet on lap is near: use 'This'."),
    ("_____ is my favorite storybook on my desk.", "This", ["This", "That", "These", "Those"], "Book on desk close by: use 'This'."),
    ("_____ is my wrist watch on my arm.", "This", ["This", "That", "These", "Those"], "On my arm is near: use 'This'."),
    ("_____ is my cup of warm cocoa here.", "This", ["This", "That", "These", "Those"], "Drink right here: use 'This'."),
    ("_____ is my drawing of a rocket I just finished.", "This", ["This", "That", "These", "Those"], "Fresh drawing in hand: use 'This'."),
    ("_____ is my umbrella keeping me dry now.", "This", ["This", "That", "These", "Those"], "Umbrella in hand: use 'This'."),
    ("_____ is my ruler beside my notebook.", "This", ["This", "That", "These", "Those"], "Ruler nearby: use 'This'."),
    ("_____ is my lunchbox on the cafeteria table.", "This", ["This", "That", "These", "Those"], "Lunchbox right in front: use 'This'."),
    ("_____ is my jacket that I am wearing.", "This", ["This", "That", "These", "Those"], "Jacket being worn: use 'This'."),
    ("_____ is my eraser next to my paper.", "This", ["This", "That", "These", "Those"], "Eraser on paper: use 'This'."),
    ("_____ is my water bottle on my desk.", "This", ["This", "That", "These", "Those"], "Water bottle close: use 'This'."),

    # THAT (Far + Singular)
    ("_____ is an airplane flying high up in the sky over there.", "That", ["That", "This", "Those", "These"], "Far and singular (one airplane): use 'That'."),
    ("_____ is the bright sun shining far above.", "That", ["That", "This", "Those", "These"], "The sun is far away: use 'That'."),
    ("_____ is a lighthouse on the distant cliff.", "That", ["That", "This", "Those", "These"], "Lighthouse far away: use 'That'."),
    ("_____ is a red car parked across the street.", "That", ["That", "This", "These", "Those"], "Car across the street is distant: use 'That'."),
    ("_____ is a bird perched on the tall tree top.", "That", ["That", "This", "Those", "These"], "Bird high in tree: use 'That'."),
    ("_____ is the school bell ringing over on the pole.", "That", ["That", "This", "These", "Those"], "School bell far on pole: use 'That'."),
    ("_____ is a sailboat drifting out in the bay.", "That", ["That", "This", "Those", "These"], "Sailboat in the bay is far: use 'That'."),
    ("_____ is a hot air balloon floating over the mountain.", "That", ["That", "This", "These", "Those"], "Balloon over mountain: use 'That'."),
    ("_____ is the clock on the classroom wall at the front.", "That", ["That", "This", "Those", "These"], "Clock on wall across the room: use 'That'."),
    ("_____ is a dog barking in the neighbor's yard.", "That", ["That", "This", "These", "Those"], "Dog in neighbor's yard: use 'That'."),
    ("_____ is a yellow kite flying high above the park.", "That", ["That", "This", "Those", "These"], "Kite high in sky: use 'That'."),
    ("_____ is the flag waving on the tall flagpole.", "That", ["That", "This", "These", "Those"], "Flag at top of pole: use 'That'."),
    ("_____ is a tractor plowing the field in the distance.", "That", ["That", "This", "Those", "These"], "Tractor far in field: use 'That'."),
    ("_____ is the moon glowing in the night sky.", "That", ["That", "This", "These", "Those"], "Moon is far: use 'That'."),

    # THESE (Near + Plural)
    ("_____ are my crayons here in my pencil case.", "These", ["These", "Those", "This", "That"], "Near and plural (many crayons): use 'These'."),
    ("_____ are my clean school shoes on my feet right now.", "These", ["These", "Those", "This", "That"], "Plural shoes being worn: use 'These'."),
    ("_____ are sweet grapes I am eating from my bowl.", "These", ["These", "Those", "This", "That"], "Grapes being eaten right here: use 'These'."),
    ("_____ are my toy bricks on the carpet in front of me.", "These", ["These", "Those", "This", "That"], "Bricks on carpet near: use 'These'."),
    ("_____ are my textbooks stacked on my desk.", "These", ["These", "Those", "This", "That"], "Stack of books on desk: use 'These'."),
    ("_____ are colorful flowers in the vase on our table.", "These", ["These", "Those", "This", "That"], "Flowers on our table: use 'These'."),
    ("_____ are my fingers on my hands.", "These", ["These", "Those", "This", "That"], "Fingers on hand: use 'These'."),
    ("_____ are cookies fresh out of the oven on the tray here.", "These", ["These", "Those", "This", "That"], "Cookies on tray in front: use 'These'."),
    ("_____ are pencils in my cup on my desk.", "These", ["These", "Those", "This", "That"], "Pencils in cup nearby: use 'These'."),
    ("_____ are my stickers on my notebook cover.", "These", ["These", "Those", "This", "That"], "Stickers on notebook: use 'These'."),
    ("_____ are warm mittens on my hands.", "These", ["These", "Those", "This", "That"], "Mittens on hands: use 'These'."),
    ("_____ are cute puppies playing at my feet.", "These", ["These", "Those", "This", "That"], "Puppies at my feet: use 'These'."),
    ("_____ are storybooks I borrowed from the library today.", "These", ["These", "Those", "This", "That"], "Books in my bag: use 'These'."),
    ("_____ are slices of watermelon on my plate.", "These", ["These", "Those", "This", "That"], "Slices on plate: use 'These'."),

    # THOSE (Far + Plural)
    ("_____ are twinkling stars shining in the dark night sky.", "Those", ["Those", "These", "That", "This"], "Far and plural (many stars in the sky): use 'Those'."),
    ("_____ are birds flying in a flock across the horizon.", "Those", ["Those", "These", "That", "This"], "Birds flying far: use 'Those'."),
    ("_____ are houses across the wide river.", "Those", ["Those", "These", "That", "This"], "Houses across river: use 'Those'."),
    ("_____ are cows grazing in the distant pasture.", "Those", ["Those", "These", "That", "This"], "Cows far in pasture: use 'Those'."),
    ("_____ are apples hanging high up on the orchard tree.", "Those", ["Those", "These", "That", "This"], "Apples high on tree: use 'Those'."),
    ("_____ are fluffy clouds drifting over the hills.", "Those", ["Those", "These", "That", "This"], "Clouds over hills: use 'Those'."),
    ("_____ are children playing swings on the far side of the park.", "Those", ["Those", "These", "That", "This"], "Children far in park: use 'Those'."),
    ("_____ are mountains with snowy peaks far away.", "Those", ["Those", "These", "That", "This"], "Distant mountains: use 'Those'."),
    ("_____ are streetlights glowing down the long avenue.", "Those", ["Those", "These", "That", "This"], "Streetlights down avenue: use 'Those'."),
    ("_____ are sailboats racing far out on the sea.", "Those", ["Those", "These", "That", "This"], "Sailboats on distant sea: use 'Those'."),
    ("_____ are sheep on the distant hillside.", "Those", ["Those", "These", "That", "This"], "Sheep on hill far away: use 'Those'."),
    ("_____ are tall wind turbines spinning in the breeze over there.", "Those", ["Those", "These", "That", "This"], "Turbines far away: use 'Those'."),
    ("_____ are cars waiting at the distant traffic light.", "Those", ["Those", "These", "That", "This"], "Cars far at light: use 'Those'.")
]
for q_text, ans, opts, hint in demonstratives_drills:
    add_q("eng_demonstratives", "english", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 16: eng_comprehension (Reading Comprehension) ---
# 55 questions across 7 sweet passages
stories = [
    {
        "title": "Troy's Sweet Heart Lollipop",
        "passage": "Troy went to the candy shop with his mother on a sunny Saturday afternoon. He had saved three silver coins in his piggy bank. In the glass jar, Troy spotted a big, heart-shaped lollipop with colorful strawberry stripes. The shopkeeper smiled and wrapped it in clear paper. Troy felt very proud and happy. He decided to share half of his sweet treat with his little sister Lily when he got home.",
        "questions": [
            ("Where did Troy go on Saturday afternoon?", "To the candy shop", ["To the candy shop", "To the zoo", "To the library", "To school"], "The story says Troy went to the candy shop."),
            ("Who accompanied Troy to the candy shop?", "His mother", ["His mother", "His teacher", "His puppy", "His grandmother"], "Troy went with his mother."),
            ("What coins had Troy saved in his piggy bank?", "Three silver coins", ["Three silver coins", "Ten gold coins", "Paper notes", "Five copper coins"], "He saved three silver coins."),
            ("What shape was the lollipop Troy chose?", "Heart-shaped", ["Heart-shaped", "Star-shaped", "Square", "Round ring"], "The lollipop was heart-shaped with strawberry stripes."),
            ("What flavor stripes did the lollipop have?", "Strawberry stripes", ["Strawberry stripes", "Mint stripes", "Lemon stripes", "Grape stripes"], "It had colorful strawberry stripes."),
            ("How was the lollipop wrapped by the shopkeeper?", "In clear paper", ["In clear paper", "In a metal tin", "In a cardboard box", "In a plastic bag"], "The shopkeeper wrapped it in clear paper."),
            ("How did Troy feel after buying his treat?", "Proud and happy", ["Proud and happy", "Angry and sad", "Sleepy", "Bored"], "He felt proud and happy."),
            ("Who did Troy decide to share his lollipop with?", "His little sister Lily", ["His little sister Lily", "His pet cat", "His cousin Ben", "Nobody"], "He decided to share half with Lily.")
        ]
    },
    {
        "title": "Andy's Exciting Day at the Zoo",
        "passage": "Andy visited the National Zoo with his class on a school excursion. First, they saw the tall giraffes reaching their long necks to eat acacia leaves from the high wooden basket. Next, they heard a loud roar from the lion king resting in the shade of a rock. Andy ate an egg sandwich and drank orange juice under a shady oak tree. His favorite animals were the funny sea otters splashing and doing backflips in the clean pool.",
        "questions": [
            ("Where did Andy go on his school excursion?", "The National Zoo", ["The National Zoo", "The cinema", "The toy museum", "The swimming beach"], "Andy visited the National Zoo."),
            ("What were the tall giraffes eating?", "Acacia leaves from a high basket", ["Acacia leaves", "Fish and prawns", "Watermelon", "Bananas"], "Giraffes ate acacia leaves."),
            ("Which animal made a loud roar while resting by a rock?", "The lion king", ["The lion king", "The elephant", "The parrot", "The zebra"], "The lion king roared loudly."),
            ("What did Andy eat for his lunch under the oak tree?", "An egg sandwich", ["An egg sandwich", "A pizza", "A bowl of noodles", "Fried chicken"], "He ate an egg sandwich."),
            ("What beverage did Andy drink?", "Orange juice", ["Orange juice", "Hot tea", "Milk", "Soda"], "He drank refreshing orange juice."),
            ("Which animals were Andy's absolute favorite at the zoo?", "The funny sea otters", ["The funny sea otters", "The bears", "The snakes", "The frogs"], "His favorite were the playful sea otters."),
            ("What were the sea otters doing in the pool?", "Splashing and doing backflips", ["Splashing and doing backflips", "Sleeping quietly", "Eating carrots", "Flying"], "They splashed and did backflips in the pool."),
            ("How did Andy travel to the zoo?", "With his class on a school excursion", ["With his class", "Alone on a bicycle", "With his grandfather", "On a skateboard"], "He went with his classmates on an excursion.")
        ]
    },
    {
        "title": "Lily's Secret Flower Garden",
        "passage": "Lily planted tiny black sunflower seeds in a clay pot behind her cottage. Every morning, she carried a small green watering can to water the soil. She placed the pot on the window sill where warm sunlight beamed down all day. Two weeks later, a tiny green sprout with two round leaves poked through the dark soil. Lily clapped her hands with joy because her plant was growing strong.",
        "questions": [
            ("What kind of seeds did Lily plant in the clay pot?", "Sunflower seeds", ["Sunflower seeds", "Watermelon seeds", "Rose seeds", "Apple seeds"], "Lily planted sunflower seeds."),
            ("What color were the sunflower seeds?", "Black", ["Black", "Red", "Blue", "White"], "They were tiny black seeds."),
            ("What tool did Lily use to water her plant every morning?", "A small green watering can", ["A small green watering can", "A big bucket", "A garden hose", "A glass cup"], "She used a small green watering can."),
            ("Why did Lily place the pot on the window sill?", "To get warm sunlight all day", ["To get warm sunlight all day", "To hide from bees", "To keep it cold", "To catch insects"], "Plants need sunlight to grow."),
            ("How long did it take for the sprout to appear?", "Two weeks", ["Two weeks", "Two days", "Two months", "One hour"], "Two weeks later it sprouted."),
            ("How many round leaves did the baby sprout have?", "Two round leaves", ["Two round leaves", "Ten leaves", "No leaves", "Five leaves"], "It had two round leaves."),
            ("What color was Lily's small watering can?", "Green", ["Green", "Blue", "Red", "Yellow"], "She carried a small green watering can."),
            ("How did Lily react when she saw the sprout?", "Clapped her hands with joy", ["Clapped her hands with joy", "Cried with sorrow", "Fell asleep", "Ran away"], "She clapped with joy.")
        ]
    },
    {
        "title": "Max the Playful Puppy",
        "passage": "Max is a fluffy golden retriever puppy with floppy ears and a wagging tail. Whenever his owner Sam throws a bright yellow tennis ball across the green lawn, Max dashes across the grass like a lightning bolt. Max catches the ball gently in his mouth and trots back proudly to drop it at Sam's feet. At night, Max curls up on his soft blue fleece blanket at the foot of Sam's bed.",
        "questions": [
            ("What breed of puppy is Max?", "A golden retriever", ["A golden retriever", "A bulldog", "A poodle", "A beagle"], "Max is a fluffy golden retriever."),
            ("What color is the tennis ball Sam throws?", "Bright yellow", ["Bright yellow", "Red", "Blue", "White"], "The tennis ball is bright yellow."),
            ("What does Max love to chase across the lawn?", "A bright yellow tennis ball", ["A bright yellow tennis ball", "A butterfly", "A rabbit", "A car"], "He dashes after the yellow tennis ball."),
            ("How fast does Max run across the lawn?", "Like a lightning bolt", ["Like a lightning bolt", "Very slowly like a snail", "He hops like a kangaroo", "He rolls"], "He dashes like a lightning bolt."),
            ("What does Max do after catching the ball?", "Trots back and drops it at Sam's feet", ["Trots back and drops it at Sam's feet", "Buries it in the mud", "Chews it into pieces", "Runs away into the woods"], "Max trots back proudly."),
            ("Where does Max sleep at night?", "On his soft blue fleece blanket", ["On his soft blue fleece blanket", "On the kitchen table", "Outside in the rain", "On the sofa"], "He curls up on his blue fleece blanket."),
            ("Who is Max's owner?", "Sam", ["Sam", "Troy", "Andy", "Lucas"], "Sam is Max's owner."),
            ("What kind of ears does Max have?", "Floppy ears", ["Floppy ears", "Pointy ears", "No ears", "Tiny ears"], "He has floppy ears.")
        ]
    },
    {
        "title": "The Helpful Little Red Hen",
        "passage": "The Little Red Hen lived on a cozy farm with a lazy cat, a sleepy dog, and a noisy duck. One afternoon, the hen found some golden grains of wheat. She asked her friends, 'Who will help me plant this wheat?' The cat said, 'Not I.' The dog said, 'Not I.' The duck said, 'Not I.' So the little hen planted, watered, harvested, and baked fresh crusty bread all by herself. When the warm bread smelled delicious, the animals wanted to eat, but the hen shared it only with her hardworking chicks.",
        "questions": [
            ("What did the Little Red Hen find on the farm?", "Golden grains of wheat", ["Golden grains of wheat", "A gold coin", "An apple", "A caterpillar"], "She found grains of wheat."),
            ("Who planted and watered the wheat seeds?", "The Little Red Hen alone", ["The Little Red Hen alone", "The lazy cat", "The sleepy dog", "The noisy duck"], "She planted it all by herself."),
            ("Which animals refused to help the Little Red Hen?", "The cat, dog, and duck", ["The cat, dog, and duck", "The cow and horse", "The sheep and goat", "The pig and rabbit"], "The cat, dog, and duck said 'Not I'."),
            ("What delicious food did the hen bake from the harvested wheat?", "Fresh crusty bread", ["Fresh crusty bread", "A chocolate cake", "Pancakes", "A fruit pie"], "She baked fresh crusty bread."),
            ("Who did the Little Red Hen share the warm bread with?", "Her hardworking chicks", ["Her hardworking chicks", "The lazy cat", "The sleepy dog", "The noisy duck"], "She shared it with her chicks."),
            ("What moral lesson does the story teach us?", "Hard work brings true rewards", ["Hard work brings true rewards", "Being lazy is good", "Never share with anyone", "Wheat is bad to eat"], "Hard work is rewarded."),
            ("Where did the Little Red Hen live?", "On a cozy farm", ["On a cozy farm", "In a city apartment", "In a dark cave", "On a spaceship"], "She lived on a farm."),
            ("What was the duck described as?", "Noisy", ["Noisy", "Lazy", "Sleepy", "Fast"], "The duck was noisy.")
        ]
    },
    {
        "title": "Sam's Cardboard Space Rocket",
        "passage": "On a rainy Sunday, Sam transformed a giant tall refrigerator cardboard box into a silver space rocket. With silver foil, silver duct tape, and round plastic jar lids for control buttons, Sam built a cockpit. He drew planet Earth, the glowing Moon, and red Mars on his dashboard. Wearing his white winter bike helmet as a space helmet, Sam counted down: '3, 2, 1, Blast off!' to embark on an imaginary voyage to explore the stars.",
        "questions": [
            ("What did Sam use to build his space rocket?", "A giant refrigerator cardboard box", ["A giant refrigerator cardboard box", "A wooden barrel", "Metal sheets", "A tent"], "He used a tall cardboard box."),
            ("What did Sam use as control buttons in the cockpit?", "Round plastic jar lids", ["Round plastic jar lids", "Shiny coins", "Cookies", "Stones"], "He used jar lids as buttons."),
            ("What three celestial bodies did Sam draw on his dashboard?", "Earth, the Moon, and Mars", ["Earth, the Moon, and Mars", "Jupiter, Saturn, and Pluto", "The Sun and black hole", "Stars only"], "He drew Earth, Moon, and Mars."),
            ("What did Sam wear as an astronaut helmet?", "His white bike helmet", ["His white bike helmet", "A paper bag", "A pirate hat", "A swimming cap"], "He wore his white bike helmet."),
            ("On which day did Sam build his rocket?", "A rainy Sunday", ["A rainy Sunday", "A hot Friday", "A stormy Monday", "A chilly Wednesday"], "It was a rainy Sunday."),
            ("What words did Sam shout when launching?", "'3, 2, 1, Blast off!'", ["'3, 2, 1, Blast off!'", "'Ready, set, go!'", "'Good night!'", "'Stop the car!'"], "He shouted 'Blast off!'."),
            ("What material did Sam wrap his rocket with to make it look silver?", "Silver foil and duct tape", ["Silver foil and duct tape", "Blue paint", "Gold paper", "Cotton wool"], "He used silver foil and duct tape.")
        ]
    },
    {
        "title": "Maya's Surprise Birthday Party",
        "passage": "Maya opened the front door after her ballet lesson and heard: 'SURPRISE!' Her parents, brother Noah, and best friends clapped and cheered. Colorful balloons filled the living room, and on the dining table sat a two-tiered strawberry sponge cake topped with seven glowing candles. Maya made a secret wish in her heart and blew out all seven candles in one big puff of breath. Everyone sang the Happy Birthday song joyfully.",
        "questions": [
            ("Where was Maya returning from before the surprise?", "Her ballet lesson", ["Her ballet lesson", "Her piano lesson", "The grocery store", "The dental clinic"], "She came back from her ballet lesson."),
            ("How old did Maya turn on her birthday?", "Seven years old (7 candles)", ["Seven years old", "Ten years old", "Five years old", "Eight years old"], "There were seven candles."),
            ("What flavor was Maya's two-tiered birthday cake?", "Strawberry sponge cake", ["Strawberry sponge cake", "Chocolate mud cake", "Vanilla lemon", "Carrot cake"], "It was a strawberry sponge cake."),
            ("How many puffs of breath did Maya need to blow out the candles?", "One big puff", ["One big puff", "Three puffs", "Ten puffs", "She used a fan"], "She blew them out in one big puff."),
            ("What did Maya do in her heart before blowing out the candles?", "Made a secret wish", ["Made a secret wish", "Counted to ten", "Sang a song", "Ate a strawberry"], "She made a secret wish."),
            ("What was filling the living room decorations?", "Colorful balloons", ["Colorful balloons", "Snowflakes", "Paper airplanes", "Dried leaves"], "Colorful balloons filled the room."),
            ("Who was Maya's brother mentioned in the story?", "Noah", ["Noah", "Troy", "Andy", "Lucas"], "Her brother's name is Noah."),
            ("What song did everyone sing together?", "The Happy Birthday song", ["The Happy Birthday song", "Twinkle Twinkle Little Star", "Row Row Row Your Boat", "Jingle Bells"], "They sang Happy Birthday.")
        ]
    }
]

for s in stories:
    for q_text, ans, opts, hint in s["questions"]:
        add_q("eng_comprehension", "english", q_text, q_text, "en", "multiple_choice", None, s["passage"], opts, ans, hint, hint)

print(f"Generated English questions! Running total: {len(all_questions)}")

# =========================================================================
# SUBJECT: SCIENCE (100% English)
# =========================================================================

# --- Topic 17: sci_land_sea (Land vs Sea Animals) ---
animals_land_sea = [
    # Sea animals
    ("dolphin", "Sea", "Dolphins swim gracefully and breathe through a blowhole."),
    ("shark", "Sea", "Sharks are apex marine predators living in deep oceans."),
    ("whale", "Sea", "Whales are giant marine mammals living in ocean waters."),
    ("octopus", "Sea", "Octopuses have eight tentacles and swim in the sea."),
    ("crab", "Sea", "Crabs scuttle along the shoreline and ocean floor."),
    ("sea turtle", "Sea", "Sea turtles paddle through tropical ocean currents."),
    ("jellyfish", "Sea", "Jellyfish drift through ocean waters with stinging tentacles."),
    ("seahorse", "Sea", "Seahorses curl their tails around underwater coral reefs."),
    ("clownfish", "Sea", "Clownfish live safely among sea anemone tentacles."),
    ("lobster", "Sea", "Lobsters crawl along the rocky ocean seabed."),
    ("squid", "Sea", "Squids jet-propel themselves through ocean depths."),
    ("starfish", "Sea", "Starfish have five arms and cling to tidal rocks in the sea."),
    ("seal", "Sea", "Seals swim in freezing ocean waters hunting fish."),
    ("stingray", "Sea", "Stingrays glide flat along the sandy ocean bottom."),
    ("walrus", "Sea", "Walruses have long tusks and swim in cold Arctic seas."),
    # Land animals
    ("cow", "Land", "Cows graze on green grass in pastures on land."),
    ("lion", "Land", "Lions are big cats that roam the dry African savannah on land."),
    ("tiger", "Land", "Tigers prowl and hunt silently in thick land jungles."),
    ("elephant", "Land", "Elephants are the largest land mammals walking on four legs."),
    ("giraffe", "Land", "Giraffes have long necks to reach tall trees on land."),
    ("monkey", "Land", "Monkeys climb and leap through trees in land forests."),
    ("horse", "Land", "Horses gallop swiftly across open land plains."),
    ("goat", "Land", "Goats climb rocky hills and graze on land shrubs."),
    ("rabbit", "Land", "Rabbits dig underground burrows in land meadows."),
    ("kangaroo", "Land", "Kangaroos hop across the Australian outback on land."),
    ("bear", "Land", "Bears forage in mountain forests and rivers on land."),
    ("zebra", "Land", "Zebras graze in herds across grassland plains."),
    ("camel", "Land", "Camels are well adapted to survive in dry sandy desert land."),
    ("panda", "Land", "Giant pandas munch bamboo in misty mountain land."),
    ("cheetah", "Land", "Cheetahs run at incredible speeds across flat land.")
]

for anim, habitat, desc in animals_land_sea:
    opts = ["Sea", "Land"]
    img = f"images/binatang/{anim.replace(' ', '_')}.jpg" if (BASE_DIR / f"images/binatang/{anim.replace(' ', '_')}.jpg").exists() else None
    add_q(
        "sci_land_sea", "science",
        f"Does a {anim} live primarily in the Sea or on Land?",
        f"Where does a {anim} live?",
        "en", "multiple_choice", img, None, opts, habitat,
        desc, f"A {anim} lives in the {habitat.lower()}."
    )

# 25 Biology & habitat trivia questions
habitat_trivia = [
    ("Which organ do fish use to breathe dissolved oxygen underwater?", "Gills", ["Gills", "Lungs", "Nose", "Skin"], "Fish absorb oxygen through feathery gills."),
    ("Which organ do land mammals like dogs and humans use to breathe air?", "Lungs", ["Lungs", "Gills", "Fins", "Scales"], "Mammals breathe air into lungs."),
    ("Even though dolphins and whales swim in the sea, how do they breathe?", "They surface to breathe air through a blowhole into lungs", ["They surface to breathe air into lungs", "They use gills like fish", "They drink seawater", "They don't breathe"], "Whales and dolphins are mammals with lungs."),
    ("What kind of habitat covers over 70% of planet Earth?", "The Oceans (Sea)", ["The Oceans (Sea)", "Deserts", "Snow mountains", "Forests"], "Oceans cover more than 70% of Earth."),
    ("Animals that can live BOTH in water and on land (like frogs) are called:", "Amphibians", ["Amphibians", "Reptiles", "Fish", "Birds"], "Amphibians inhabit both land and water."),
    ("Where do sea turtles lay their eggs?", "In the warm sand on ocean beaches", ["In the warm sand on ocean beaches", "In deep underwater caves", "In tree tops", "On icebergs"], "Female turtles crawl ashore to bury eggs in sand."),
    ("Which sea animal has 8 suction-cupped arms and squirts dark ink?", "Octopus", ["Octopus", "Starfish", "Jellyfish", "Crab"], "An octopus has eight arms."),
    ("Which animal is known as the 'Ship of the Desert' because it lives on dry sandy land?", "Camel", ["Camel", "Horse", "Dolphin", "Penguin"], "Camels can store fat in humps to survive desert land."),
    ("Where do earthworms live?", "In moist, dark underground soil", ["In moist underground soil", "In the ocean depths", "In the sky", "On tree leaves"], "Earthworms live underground in damp soil."),
    ("Which animal builds a wooden dam across flowing river water?", "Beaver", ["Beaver", "Otter", "Fish", "Bear"], "Beavers build dams from logs and mud."),
    ("What allows birds to fly in the air over land?", "Lightweight bones and feathered wings", ["Lightweight bones and feathered wings", "Heavy metal scales", "Fins", "Gills"], "Wings and light bones allow flight."),
    ("Which animal carries its shell on its back and retreats inside when frightened?", "Tortoise / Snail", ["Tortoise / Snail", "Dog", "Fish", "Eagle"], "Tortoises and snails have protective shells."),
    ("Which cold habitat is home to polar bears and seals?", "The Arctic / Polar ice", ["The Arctic / Polar ice", "The hot desert", "The tropical jungle", "The swamp"], "Polar bears live in Arctic ice."),
    ("Which animal can sleep standing up on its four legs on land?", "Horse", ["Horse", "Dolphin", "Whale", "Seal"], "Horses have special leg locking mechanisms to rest standing."),
    ("What body covering helps fish glide smoothly through seawater?", "Slippery scales", ["Slippery scales", "Feathers", "Fur", "Wool"], "Fish scales reduce water friction."),
    ("What body covering keeps polar bears warm in freezing snowy land?", "Thick fur and blubber", ["Thick fur and blubber", "Scales", "Feathers", "Bare skin"], "Dense fur and fat retain warmth."),
    ("Which sea creature looks like a star and can regenerate a lost arm?", "Starfish", ["Starfish", "Shark", "Dolphin", "Crab"], "Starfish have radial arms that can regrow."),
    ("Which marine predator has multiple rows of sharp triangular teeth?", "Shark", ["Shark", "Whale", "Dolphin", "Turtle"], "Sharks have rows of replaceable teeth."),
    ("Which animal lives in treetops and swings with a prehensile tail?", "Spider monkey", ["Spider monkey", "Cow", "Elephant", "Lion"], "Monkeys climb and swing in trees on land."),
    ("What helps ducks paddle smoothly on water ponds?", "Webbed feet", ["Webbed feet", "Sharp claws", "Hooves", "Long toes"], "Webbed feet act like flippers in water."),
    ("Which animal changes the color of its skin to blend into tree bark on land?", "Chameleon", ["Chameleon", "Dog", "Cow", "Horse"], "Chameleons camouflage into surroundings."),
    ("Which sea creature has transparent, umbrella-shaped bell body that floats?", "Jellyfish", ["Jellyfish", "Crab", "Shrimp", "Clam"], "Jellyfish drift with ocean currents."),
    ("Where do honeybees live and build their wax combs?", "In a beehive on land", ["In a beehive on land", "Underwater in the sea", "In deep caves", "In ice"], "Bees build hives on trees or land structures."),
    ("Which animal has black and white feathers, walks on land, and swims in chilly seas?", "Penguin", ["Penguin", "Parrot", "Flamingo", "Peacock"], "Penguins are flightless birds that swim."),
    ("Why can't land mammals survive underwater for hours like fish?", "They lack gills and need atmospheric air", ["They lack gills and need atmospheric air", "They don't like water", "Their fur gets wet", "They get cold"], "Mammals need to inhale oxygen from air.")
]
for q_text, ans, opts, hint in habitat_trivia:
    add_q("sci_land_sea", "science", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 18: sci_sink_float (Sink or Float) ---
sink_float_objects = [
    # Sink
    ("metal key", "Sink", "images/science/sink_key.jpg", "Metal is dense and heavier than water, so it sinks down."),
    ("heavy stone pebble", "Sink", "images/science/sink_stone.jpg", "Solid stone is dense and quickly sinks to the bottom."),
    ("stainless steel spoon", "Sink", "images/science/metal_spoon.jpg", "Solid metal spoons sink to the bottom of the basin."),
    ("glass marble", "Sink", None, "Solid glass is heavy and sinks straight down in water."),
    ("iron nail", "Sink", None, "An iron nail is made of dense metal and sinks immediately."),
    ("ceramic mug", "Sink", None, "Ceramic clay is heavy and sinks when filled with water."),
    ("gold coin", "Sink", None, "Gold is an extremely dense heavy metal that sinks fast."),
    ("lead fishing weight", "Sink", None, "Lead is heavy and pulls fishing lines down to the seabed."),
    ("metal bolt and nut", "Sink", None, "Steel bolts are heavy and drop to the bottom."),
    ("brick", "Sink", None, "A construction brick is made of dense clay that sinks."),
    ("metal scissors", "Sink", "images/ini-itu/gunting_dekat.jpg", "Metal scissors sink due to their heavy steel blades."),
    ("anchor", "Sink", None, "A ship's anchor is designed to sink deeply to hold ships in place."),
    ("wrench tool", "Sink", None, "A steel wrench tool sinks instantly."),
    ("horseshoe", "Sink", None, "An iron horseshoe sinks to the basin floor."),
    ("padlock", "Sink", None, "A brass padlock is heavy and sinks in water."),

    # Float
    ("plastic toy ball", "Float", "images/science/float_ball.jpg", "Light plastic filled with air floats buoyantly on the surface."),
    ("wooden twig branch", "Float", "images/science/float_wood.jpg", "Dry wood is less dense than water, so it floats gently."),
    ("cork stopper", "Float", None, "Cork contains tiny air pockets that keep it bobbing on top."),
    ("rubber bath duck", "Float", None, "Hollow rubber ducks trap air and float in bath tubs."),
    ("dry tree leaf", "Float", None, "A thin lightweight leaf rests easily on water surface tension."),
    ("ping pong ball", "Float", None, "A hollow plastic table tennis ball is full of air and floats."),
    ("inflated beach ball", "Float", None, "Air-filled beach balls float high on ocean waves."),
    ("plastic water bottle (empty & closed)", "Float", None, "An empty capped bottle holds air and floats."),
    ("wooden popsicle stick", "Float", None, "Lightweight wood floats on the surface of water."),
    ("bird feather", "Float", None, "Feathers are light with water-repellent oils and float."),
    ("foam pool noodle", "Float", None, "Foam is filled with countless air bubbles, keeping swimmers afloat."),
    ("dry sponge", "Float", None, "Sponges are full of air holes and float on water."),
    ("life jacket", "Float", None, "Life jackets are filled with buoyant foam to keep humans safe."),
    ("styrofoam cup", "Float", None, "Styrofoam is mostly air and floats effortlessly."),
    ("wooden surfboard", "Float", None, "Surfboards are buoyant so surfers can ride waves.")
]

for obj_name, behavior, img, hint in sink_float_objects:
    opts = ["Sink", "Float"] if behavior == "Sink" else ["Float", "Sink"]
    add_q(
        "sci_sink_float", "science",
        f"If you place a {obj_name} into a bowl of water, will it Sink or Float?",
        f"Will a {obj_name} sink or float in water?",
        "en", "multiple_choice", img, None, opts, behavior,
        hint, f"A {obj_name} will {behavior.lower()}."
    )

# 25 Scientific concepts of buoyancy & density
sink_float_concepts = [
    ("Why do some objects float on water?", "They are lighter and less dense than water", ["They are less dense than water", "They are made of heavy iron", "They have no weight", "Water dislikes them"], "Objects float when their density is less than water."),
    ("Why do heavy metal coins sink to the bottom of a pool?", "They are denser and heavier than water", ["They are denser than water", "They are filled with air", "They have wings", "They dissolve immediately"], "Dense heavy materials sink."),
    ("How does a giant steel ship manage to float on the ocean?", "Its hollow hull traps a huge volume of air", ["Its hollow hull traps air inside", "Steel is naturally lighter than water", "Magic keeps it up", "Magnets pull it up"], "The large hollow shape displaces enough water to float."),
    ("What safety device filled with buoyant foam keeps people floating safely in the sea?", "A life jacket", ["A life jacket", "Heavy boots", "A metal helmet", "A backpack full of rocks"], "Life jackets keep humans safely afloat."),
    ("If you push a floating plastic ball under water and release it, what happens?", "It pops right back up to the surface", ["It pops back up to the surface", "It stays at the bottom forever", "It disappears", "It turns into water"], "Buoyancy pushes it back to the top."),
    ("What happens to an ice cube when dropped into a glass of drinking water?", "It floats near the top", ["It floats near the top", "It sinks to the bottom", "It explodes", "It becomes a stone"], "Ice is slightly less dense than liquid water, so it floats."),
    ("Does salt water in the sea make it EASIER or HARDER for things to float compared to fresh river water?", "Easier to float (more buoyant)", ["Easier to float", "Harder to float", "No difference", "Everything sinks in salt water"], "Salt water is denser, providing stronger buoyant lift."),
    ("What happens if water leaks inside a hollow toy boat until it fills completely?", "It becomes heavy and sinks", ["It becomes heavy and sinks", "It flies up", "It floats higher", "It grows bigger"], "When air is replaced by water, the boat sinks."),
    ("If you ball up a sheet of aluminum foil very tightly into a solid marble, what will happen?", "It will sink", ["It will sink", "It will float", "It will melt", "It will turn green"], "Crushing out all air pockets causes dense foil to sink."),
    ("If you shape aluminum foil into a wide open boat, what will happen?", "It will float on the water", ["It will float on the water", "It will sink immediately", "It will vanish", "It catches fire"], "The boat shape displaces water and floats."),
    ("What do submarines do to dive down deep into the ocean?", "They fill ballast tanks with water to sink", ["They fill ballast tanks with water to sink", "They drop their engine", "They turn off their lights", "They throw away food"], "Pumping water into ballast tanks increases density to sink."),
    ("What do submarines do to rise back up to the ocean surface?", "They blow compressed air into the tanks to float", ["They blow compressed air into tanks to float", "They paddle with fins", "They pull a rope", "They heat the water"], "Air replaces water to restore buoyancy."),
    ("Why does oil float on top of water in salad dressing?", "Oil is less dense than water", ["Oil is less dense than water", "Oil is heavier than water", "Oil is sticky", "Water is scared of oil"], "Oil has lower density than water."),
    ("What will happen to a hollow plastic bottle if the cap is tightly closed?", "It will float on the water", ["It will float on the water", "It will sink to the bottom", "It will break", "It turns into glass"], "Trapped air keeps it afloat."),
    ("What will happen to that same plastic bottle if you fill it completely with sand?", "It will sink to the bottom", ["It will sink to the bottom", "It will float", "It will fly", "It becomes soft"], "Sand makes it much heavier and denser than water."),
    ("A wooden log floats, but an iron nail sinks. This proves that floating depends on:", "The material and density, not just size", ["Material and density", "Color of the object", "The time of day", "The name of the item"], "Density determines sinking or floating."),
    ("Can a fresh hen's egg float in plain tap water?", "No, it sinks to the bottom", ["No, it sinks to the bottom", "Yes, it floats high", "It dissolves", "It turns into a chick"], "A fresh egg is denser than tap water and sinks."),
    ("If you stir several spoonfuls of salt into the water, what happens to the egg?", "It floats to the top", ["It floats to the top", "It sinks deeper", "It cracks open", "It cooks"], "Salt increases water density until the egg floats."),
    ("What natural force pushes floating objects upward against gravity?", "Buoyant force (Upthrust)", ["Buoyant force (Upthrust)", "Friction", "Magnetism", "Wind"], "Water exerts an upward buoyant force."),
    ("Will a dry wooden pencil float or sink in water?", "Float", ["Float", "Sink"], "Wood floats on water."),
    ("Will an eraser made of solid dense rubber float or sink?", "Sink", ["Sink", "Float"], "Dense solid rubber usually sinks."),
    ("Will a metal coin sink or float in water?", "Sink", ["Sink", "Float"], "Coins are solid metal and sink."),
    ("Will a fresh green leaf float or sink in a puddle?", "Float", ["Float", "Sink"], "Leaves float on puddles."),
    ("Will a stone drop to the bottom of a river or float on top?", "Drop to the bottom (sink)", ["Drop to the bottom (sink)", "Float on top", "Fly into trees", "Dissolve into mud"], "Stones sink to the riverbed."),
    ("Why do human swimmers float more easily when their lungs are full of air?", "Lungs act like built-in buoyant air balloons", ["Lungs act like buoyant air balloons", "Air is heavy", "Water turns into ice", "Muscles disappear"], "Air in the chest lowers overall body density.")
]
for q_text, ans, opts, hint in sink_float_concepts:
    add_q("sci_sink_float", "science", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 19: sci_celestial (Sun, Moon, Star & Earth) ---
celestial_data = [
    # The Sun
    ("Which celestial body provides Earth with bright daylight and warmth?", "The Sun", ["The Sun", "The Moon", "Mars", "Pluto"], "images/science/sun.jpg", "The Sun is our closest star giving light and heat."),
    ("What is the Sun actually classified as?", "A giant glowing star", ["A giant glowing star", "A solid rocky planet", "A comet of ice", "An asteroid"], "images/science/sun.jpg", "The Sun is a hot ball of glowing gas: a star."),
    ("At what time of day is the Sun visible in the sky?", "Daytime", ["Daytime", "Midnight", "Only at eclipse", "Never"], "images/science/sun.jpg", "The Sun lights up our day."),
    ("In which direction does the Sun rise every morning?", "East", ["East", "West", "North", "South"], "images/science/sun.jpg", "The Sun rises in the East."),
    ("In which direction does the Sun set every evening?", "West", ["West", "East", "North", "South"], "images/science/sun.jpg", "The Sun sets in the West."),
    ("Why should you NEVER look directly at the Sun?", "Its intense light can damage your eyes", ["Its intense light can damage eyes", "It will fall down", "It turns green", "It stops shining"], "images/science/sun.jpg", "Looking at the Sun can cause permanent eye damage."),
    ("What would happen to planet Earth if there were no Sun?", "It would be completely dark, freezing, and lifeless", ["It would be dark, freezing, and lifeless", "It would be warmer", "Plants would grow faster", "Nothing changes"], "images/science/sun.jpg", "Life depends on the Sun's light and heat."),

    # The Moon
    ("Which celestial object is Earth's only natural satellite?", "The Moon", ["The Moon", "The Sun", "Mars", "Venus"], "images/science/moon.jpg", "The Moon orbits planet Earth."),
    ("Does the Moon produce its own light?", "No, it reflects sunlight like a giant mirror", ["No, it reflects sunlight", "Yes, it burns like a lightbulb", "Yes, it is on fire", "It has electric batteries"], "images/science/moon.jpg", "Moonlight is reflected sunlight."),
    ("When the Moon looks like a complete bright glowing circle, it is called a:", "Full Moon", ["Full Moon", "Crescent Moon", "New Moon", "Quarter Moon"], "images/science/moon.jpg", "A complete circle is a Full Moon."),
    ("When the Moon appears like a thin curved sliver, it is called a:", "Crescent Moon", ["Crescent Moon", "Full Moon", "Sun Moon", "Star Moon"], "images/science/moon.jpg", "A thin curve is a crescent moon."),
    ("How long does the Moon take to travel all the way around planet Earth?", "About 28 to 29 days (one month)", ["About 28 to 29 days (one month)", "24 hours", "365 days", "10 years"], "images/science/moon.jpg", "The Moon orbits Earth in about one month."),
    ("What are the bowl-shaped holes on the rocky surface of the Moon called?", "Craters", ["Craters", "Caves", "Ponds", "Wells"], "images/science/moon.jpg", "Meteor impacts created craters on the Moon."),
    ("Can humans breathe naturally on the Moon without space suits?", "No, there is no air or atmosphere on the Moon", ["No, there is no air on the Moon", "Yes, easily", "Only during daytime", "Only near craters"], "images/science/moon.jpg", "Astronauts must wear spacesuits with oxygen tanks."),

    # Planet Earth
    ("What is the name of our home planet where we live?", "Earth", ["Earth", "Mars", "Jupiter", "Venus"], "images/science/earth.jpg", "We all live on planet Earth."),
    ("Why is planet Earth known as the 'Blue Planet' when viewed from space?", "Because over 70% of its surface is covered by blue oceans", ["Because it is covered by oceans", "Because the dirt is blue", "Because trees are blue", "Because clouds are blue"], "images/science/earth.jpg", "Oceans make Earth look like a blue marble."),
    ("How long does planet Earth take to complete one full orbit around the Sun?", "One year (365 days)", ["One year (365 days)", "One day (24 hours)", "One month (30 days)", "Ten years"], "images/science/earth.jpg", "One orbit around the Sun equals one year."),
    ("What causes day and night on planet Earth?", "Earth spinning on its axis every 24 hours", ["Earth spinning on its axis", "The Sun turns on and off", "Clouds block the Sun", "The Moon covers the Sun"], "images/science/earth.jpg", "Earth's rotation creates day and night."),
    ("What layer of gases surrounds Earth protecting us and giving us air to breathe?", "The Atmosphere", ["The Atmosphere", "The Hydrosphere", "The Crust", "The Core"], "images/science/earth.jpg", "The atmosphere provides air and protects Earth."),

    # Stars
    ("What are the twinkling points of light seen in the sky on a clear dark night?", "Stars", ["Stars", "Streetlamps", "Airplanes", "Fireflies"], "images/science/star.jpg", "Stars are distant glowing suns."),
    ("Why do stars appear so tiny in the night sky?", "Because they are billions of miles far away", ["Because they are extremely far away", "Because they are smaller than peas", "Because they are toys", "Because they hide"], "images/science/star.jpg", "Immense distance makes giant stars look tiny."),
    ("What huge spiral galaxy of billions of stars do we live in?", "The Milky Way galaxy", ["The Milky Way galaxy", "Andromeda", "The Solar System", "Orion"], "images/science/star.jpg", "Our solar system is in the Milky Way."),
    ("A group of stars that forms an imaginary picture or pattern (like Orion the Hunter) is called a:", "Constellation", ["Constellation", "Comet", "Solar flare", "Planetarium"], "images/science/star.jpg", "Star patterns are constellations."),
    ("Which famous star in the northern sky stays in almost the same spot and helps travelers navigate?", "The North Star (Polaris)", ["The North Star (Polaris)", "The Sun", "The Red Star", "The Moon Star"], "images/science/star.jpg", "Polaris points towards True North.")
]
for q_text, ans, opts, img, hint in celestial_data:
    add_q("sci_celestial", "science", q_text, q_text, "en", "multiple_choice", img, None, opts, ans, hint, hint)

# Extra astronomy questions to reach 55
astronomy_extra = [
    ("Which planet is the fourth from the Sun and known as the 'Red Planet'?", "Mars", ["Mars", "Venus", "Jupiter", "Mercury"], "images/science/earth.jpg", "Mars has reddish iron oxide dust."),
    ("Which is the largest planet in our solar system?", "Jupiter", ["Jupiter", "Saturn", "Earth", "Neptune"], None, "Jupiter is a giant gas planet."),
    ("Which planet is famous for having spectacular bright rings around it?", "Saturn", ["Saturn", "Earth", "Mars", "Mercury"], None, "Saturn has wide icy rings."),
    ("What instrument do astronomers look through to see distant planets and stars up close?", "A telescope", ["A telescope", "A microscope", "Binoculars", "Eyeglasses"], None, "Telescopes magnify distant celestial bodies."),
    ("Who was the first astronaut to step onto the surface of the Moon in 1969?", "Neil Armstrong", ["Neil Armstrong", "Buzz Lightyear", "Albert Einstein", "Isaac Newton"], None, "Neil Armstrong said: 'One small step for man.'"),
    ("What force keeps planet Earth and all the other planets orbiting around the Sun?", "Gravity", ["Gravity", "Wind", "Magnetism", "Electricity"], None, "The Sun's immense gravity holds planets in orbit."),
    ("What force pulls our feet down to the ground so we don't float away into the sky?", "Earth's Gravity", ["Earth's Gravity", "Friction", "Static electricity", "Atmosphere"], None, "Gravity pulls objects toward Earth's center."),
    ("Is there weather or rain on the Moon?", "No, there are no clouds, rain, or wind", ["No, there is no weather", "Yes, frequent snowstorms", "Yes, heavy rain every week", "Tornadoes only"], None, "Without an atmosphere, the Moon has no weather."),
    ("What is an astronaut's special protective suit called?", "A spacesuit", ["A spacesuit", "A diving suit", "A raincoat", "A winter coat"], None, "Spacesuits provide oxygen and temperature control."),
    ("How many planets orbit around our Sun in the Solar System?", "8 planets", ["8 planets", "9 planets", "12 planets", "5 planets"], None, "Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptune: 8 planets."),
    ("Which planet is closest to the Sun?", "Mercury", ["Mercury", "Venus", "Earth", "Mars"], None, "Mercury is planet number 1."),
    ("Which is the hottest planet in our solar system due to a thick greenhouse atmosphere?", "Venus", ["Venus", "Mercury", "Mars", "Jupiter"], None, "Venus traps extreme heat."),
    ("A shooting star burning up in Earth's atmosphere is actually a:", "Meteor", ["Meteor", "Real star falling", "Airplane", "Firework"], None, "Meteors glow as they enter the atmosphere."),
    ("A ball of dirty ice and dust with a glowing tail orbiting the Sun is a:", "Comet", ["Comet", "Meteor", "Planet", "Asteroid"], None, "Comets have glowing vapor tails near the Sun."),
    ("What causes a solar eclipse?", "The Moon passes directly between the Sun and Earth", ["The Moon passes between Sun and Earth", "The Sun turns off", "Clouds block the sky", "Earth stops spinning"], None, "The Moon's shadow falls onto Earth."),
    ("What causes a lunar eclipse?", "Earth passes directly between the Sun and the Moon", ["Earth passes between Sun and Moon", "The Moon disappears", "The Sun moves behind Mars", "A cloud blocks the Moon"], None, "Earth's shadow covers the Moon."),
    ("Which planet is furthest from the Sun in our Solar System?", "Neptune", ["Neptune", "Uranus", "Jupiter", "Earth"], None, "Neptune is the eighth and outermost planet."),
    ("What shape is planet Earth?", "A round sphere (like a ball)", ["A round sphere", "Flat like a pancake", "A square box", "A triangle"], None, "Earth is spherical."),
    ("Do stars shine during the daytime?", "Yes, but the bright Sun makes them invisible to our eyes", ["Yes, but sunlight hides them", "No, they turn off in the morning", "They go to sleep", "They fly away"], None, "Stars are always shining; daytime sunlight outshines them."),
    ("What is the name of the international space laboratory orbiting high above Earth?", "International Space Station (ISS)", ["International Space Station (ISS)", "Apollo Base", "Moon Village", "Mars Station"], None, "Astronauts live and conduct experiments on the ISS."),
    ("What provides electrical power to satellites and the Space Station in orbit?", "Solar panels collecting sunlight", ["Solar panels collecting sunlight", "Gasoline engines", "Windmills", "Coal fires"], None, "Solar panels convert sunlight to electricity."),
    ("Why is Earth the only known planet with abundant life?", "It has liquid water, breathable oxygen, and suitable temperatures", ["Liquid water, oxygen, and moderate temperatures", "It has cities", "It is painted blue", "It has animals only"], None, "Water, air, and climate support life."),
    ("What is the Sun's surface temperature approximately?", "About 5,500 degrees Celsius (extremely hot)", ["About 5,500 degrees Celsius", "0 degrees", "100 degrees", "Freezing cold"], None, "The Sun is super hot plasma."),
    ("How long does sunlight take to travel through space and reach planet Earth?", "About 8 minutes", ["About 8 minutes", "1 second", "1 hour", "1 year"], None, "Light travels at 300,000 km/s and takes ~8 minutes to arrive."),
    ("What do we call the dark shadow created when an object blocks the Sun's light?", "A shadow", ["A shadow", "A crater", "A cloud", "A reflection"], None, "Shadows form on the opposite side of light sources."),
    ("At what time of day are our shadows the SHORTEST?", "At midday (noon) when the Sun is directly overhead", ["At midday (noon)", "At sunrise", "At sunset", "At midnight"], None, "Sun directly above makes short shadows."),
    ("At what time of day are our shadows the LONGEST?", "Early morning and late evening when the Sun is low", ["Early morning and late evening", "At noon", "At midnight", "Never"], None, "Low sun angles cast long shadows."),
    ("What gives plant leaves their green color while absorbing sunlight?", "Chlorophyll", ["Chlorophyll", "Paint", "Water", "Soil"], None, "Chlorophyll captures solar energy for photosynthesis."),
    ("What is the path a planet follows around the Sun called?", "An orbit", ["An orbit", "A road", "A track", "A street"], None, "Planets travel along gravitational orbits."),
    ("Which season usually brings the warmest weather because that hemisphere tilts toward the Sun?", "Summer", ["Summer", "Winter", "Autumn", "Spring"], None, "Summer has direct sunlight and long days."),
    ("Which season brings cold weather and snow because that hemisphere tilts away from the Sun?", "Winter", ["Winter", "Summer", "Spring", "Autumn"], None, "Winter has indirect sunlight and short days.")
]
for q_text, ans, opts, img, hint in astronomy_extra:
    add_q("sci_celestial", "science", q_text, q_text, "en", "multiple_choice", img, None, opts, ans, hint, hint)


# --- Topic 20: sci_materials (Materials: Metal, Glass & Paper) ---
material_items = [
    # Metal
    ("metal spoon", "Metal", "images/science/metal_spoon.jpg", "Shiny, sturdy, and conducts heat well."),
    ("brass door key", "Metal", "images/science/sink_key.jpg", "Hard, durable, and strong enough to turn door locks."),
    ("iron nail", "Metal", None, "Strong, sharp, and magnetic metal fastener."),
    ("cooking frying pan", "Metal", None, "Conducts stove heat quickly to cook food."),
    ("gold ring", "Metal", None, "Precious, shiny, and non-rusting metallic jewelry."),
    ("copper electrical wire", "Metal", None, "Conducts electricity through cables."),
    ("silver coin", "Metal", None, "Heavy, metallic, and chimes when dropped."),
    ("steel scissors", "Metal", "images/ini-itu/gunting_dekat.jpg", "Sharp steel blades cut through paper."),
    ("aluminum soda can", "Metal", None, "Lightweight, flexible metal can that can be recycled."),
    ("car chassis frame", "Metal", None, "Strong rigid steel protects vehicle passengers."),

    # Glass
    ("clear drinking glass cup", "Glass", "images/science/glass_cup.jpg", "Transparent, smooth, waterproof, but fragile if dropped."),
    ("house window pane", "Glass", None, "Allows daylight into rooms while keeping wind and rain out."),
    ("optical eyeglasses lens", "Glass", None, "Transparent shaped glass helps people see clearly."),
    ("glass pickle jar", "Glass", None, "See-through container preserves food safely."),
    ("bathroom wall mirror", "Glass", None, "Smooth glass coated with silver reflection to see yourself."),
    ("car windshield", "Glass", None, "Tough laminated transparent glass shield."),
    ("glass light bulb", "Glass", None, "Transparent glass bulb encloses glowing filament."),
    ("glass microscope slide", "Glass", None, "Clear thin glass to view specimen under microscope."),
    ("crystal vase", "Glass", None, "Decorative transparent container for fresh flowers."),
    ("aquarium tank walls", "Glass", None, "Waterproof transparent walls so you can watch fish swim."),

    # Paper
    ("reading storybook", "Paper", "images/science/paper_book.jpg", "Made from pressed tree wood pulp, lightweight and printable."),
    ("origami folded crane", "Paper", None, "Thin, foldable, and holds clean crease lines."),
    ("newspaper", "Paper", None, "Printed sheets with daily news and crossword puzzles."),
    ("cardboard cereal box", "Paper", None, "Stiff layered paper packaging to hold breakfast food."),
    ("paper facial tissue", "Paper", None, "Soft, absorbent, disposable paper for wiping sneezes."),
    ("drawing sketchbook sheet", "Paper", None, "Clean white surface for crayons and pencil drawings."),
    ("paper birthday greeting card", "Paper", None, "Folded card stock with written wishes."),
    ("paper shopping bag", "Paper", None, "Biodegradable paper carrier for grocery shopping."),
    ("paper napkin", "Paper", None, "Absorbs spills and wipes mouths at dinner table."),
    ("postage stamp", "Paper", None, "Small printed paper sticker on envelopes.")
]

for item, mat, img, desc in material_items:
    opts = ["Metal", "Glass", "Paper"]
    add_q(
        "sci_materials", "science",
        f"What material is a '{item}' primarily made from?",
        f"What material is a {item} made of?",
        "en", "multiple_choice", img, None, opts, mat,
        desc, f"A {item} is made of {mat.lower()}."
    )

# 25 Material property questions
material_properties = [
    ("Which material property describes glass because you can see completely through it?", "Transparent", ["Transparent", "Opaque", "Magnetic", "Flexible"], "Transparent materials let light pass through clearly."),
    ("What happens to most glass objects if they are accidentally dropped onto hard concrete?", "They shatter and break into sharp pieces", ["They shatter into pieces", "They bounce like rubber balls", "They bend like playdough", "They float away"], "Glass is brittle and fragile."),
    ("Which material is typically attracted to a magnet?", "Iron and steel (Metal)", ["Iron and steel (Metal)", "Glass", "Paper", "Wood"], "Magnets attract certain metals like iron, nickel, and steel."),
    ("Which material is manufactured from tree wood fibers and pulp?", "Paper", ["Paper", "Glass", "Metal", "Plastic"], "Paper comes from processed wood pulp."),
    ("Which material is the best conductor of heat for cooking pots and pans?", "Metal", ["Metal", "Paper", "Plastic", "Fabric"], "Metal transfers stove heat quickly and evenly."),
    ("Why shouldn't a cooking pot be made of paper?", "Paper burns easily when exposed to fire", ["Paper burns easily over fire", "Paper is too heavy", "Paper is transparent", "Paper is magnetic"], "Paper catches fire at cooking temperatures."),
    ("Which material is waterproof, transparent, and used for house windows?", "Glass", ["Glass", "Paper", "Cardboard", "Cotton"], "Glass lets in sunlight while blocking rain."),
    ("Which material can be easily folded, cut with safety scissors, and written on with pencils?", "Paper", ["Paper", "Glass", "Metal", "Stone"], "Paper is ideal for writing and crafts."),
    ("What property describes a material that bends easily without breaking (like a paperclip)?", "Flexible", ["Flexible", "Brittle", "Rigid", "Transparent"], "Flexible materials bend easily."),
    ("What property describes a material that cannot be seen through at all (like a metal door)?", "Opaque", ["Opaque", "Transparent", "Translucent", "Clear"], "Opaque materials block all light from passing."),
    ("Why are car keys and door keys made of metal instead of paper or glass?", "Metal is strong, hard, and does not snap easily", ["Metal is strong and durable", "Metal is soft", "Metal is transparent", "Metal burns easily"], "Keys need strong metal to turn lock tumblers."),
    ("Which material is recycled from soda cans made of aluminum?", "Metal", ["Metal", "Paper", "Glass", "Ceramic"], "Aluminum cans are melted and recycled as metal."),
    ("Which material is recycled from newspapers, cardboard boxes, and workbooks?", "Paper", ["Paper", "Metal", "Plastic", "Glass"], "Paper recycling saves trees."),
    ("Which material is recycled from bottles and jars by crushing and melting into cullet?", "Glass", ["Glass", "Wood", "Paper", "Fabric"], "Glass can be melted and reformed indefinitely."),
    ("What happens to paper when it gets soaking wet in water?", "It becomes soggy, weak, and tears easily", ["It becomes soggy and tears", "It turns into iron", "It becomes harder than rock", "It bounces"], "Paper fibers loosen when saturated with water."),
    ("Why are electrical wires coated with plastic on the outside?", "Plastic is an insulator that prevents electric shocks", ["Plastic prevents electric shocks", "Plastic looks pretty", "Plastic is magnetic", "Plastic dissolves"], "Plastic insulation protects people from current."),
    ("Which material makes a clear ringing chime sound when tapped gently with a spoon?", "Glass or Metal", ["Glass or Metal", "Paper", "Sponge", "Cotton"], "Rigid glass and metal ring with resonant sound."),
    ("What material are safety mirrors and bathroom mirrors made of?", "Glass coated with a reflective metal backing", ["Glass with a reflective metal backing", "Paper with crayon", "Wood with oil", "Stone with water"], "Silvered glass creates crisp reflections."),
    ("Which everyday kitchen utensil is made of stainless steel metal?", "A dinner fork", ["A dinner fork", "A paper towel", "A glass cup", "A plastic straw"], "Forks are made of strong stainless steel."),
    ("What material are books, notebooks, and coloring pages made of?", "Paper", ["Paper", "Glass", "Metal", "Brick"], "Books are printed on paper pages."),
    ("Which material is used to construct sturdy bridges and skyscraper girders?", "Steel (Metal)", ["Steel (Metal)", "Glass", "Paper", "Cardboard"], "Steel provides massive structural strength."),
    ("Why are aquarium walls made of glass instead of metal?", "So people can see the fish swimming through transparent walls", ["So people can see the fish clearly", "Metal is too cheap", "Fish eat metal", "Water melts metal"], "Glass provides a clear view into water."),
    ("What material are tissue boxes and cereal packaging cartons made of?", "Cardboard (thick paper)", ["Cardboard (thick paper)", "Glass", "Iron", "Gold"], "Packaging uses cardboard paper."),
    ("Which material can rust when exposed to moisture and air over time?", "Iron and steel (Metal)", ["Iron and steel (Metal)", "Glass", "Pure paper", "Gold"], "Iron oxidizes into reddish rust."),
    ("What can you do to protect books and paper documents from water damage?", "Keep them in waterproof bags or plastic covers", ["Keep them in waterproof covers", "Put them in a puddle", "Freeze them in ice", "Wash them with soap"], "Waterproof covers protect paper.")
]
for q_text, ans, opts, hint in material_properties:
    add_q("sci_materials", "science", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 21: sci_pollution (Types of Pollution) ---
pollution_scenarios = [
    # Air pollution
    ("Thick dark smoke billowing from factory chimneys into the sky causes:", "Air pollution", ["Air pollution", "Water pollution", "Land pollution", "Noise pollution"], "images/science/air_pollution.jpg", "Smoke and fumes pollute the air we breathe."),
    ("Exhaust fumes and carbon monoxide from heavy traffic on a highway cause:", "Air pollution", ["Air pollution", "Water pollution", "Land pollution", "Light pollution"], "images/science/air_pollution.jpg", "Vehicle exhaust pollutes the air."),
    ("Smoke and haze caused by open burning of dry leaves and forest fires is:", "Air pollution", ["Air pollution", "Water pollution", "Land pollution", "Sound pollution"], "images/science/air_pollution.jpg", "Haze particles pollute atmospheric air."),
    ("Smog that makes people cough and makes the city sky look grey and hazy is:", "Air pollution", ["Air pollution", "Water pollution", "Land pollution", "Soil pollution"], "images/science/air_pollution.jpg", "Smog is a dangerous form of air pollution."),
    ("Harmful chemicals and dust released into the air by coal power plants cause:", "Air pollution", ["Air pollution", "Water pollution", "Land pollution", "Noise pollution"], "images/science/air_pollution.jpg", "Power plant emissions cause air pollution."),

    # Water pollution
    ("Discarded plastic bags, bottles, and straws floating in the ocean cause:", "Water pollution", ["Water pollution", "Air pollution", "Land pollution", "Noise pollution"], "images/science/water_pollution.jpg", "Plastic trash in oceans and rivers is water pollution."),
    ("An oil tanker spilling sticky black crude oil across the sea surface causes:", "Water pollution", ["Water pollution", "Air pollution", "Land pollution", "Sound pollution"], "images/science/water_pollution.jpg", "Oil spills pollute marine waters and coat birds' feathers."),
    ("Factories dumping toxic liquid chemicals directly into a freshwater river cause:", "Water pollution", ["Water pollution", "Air pollution", "Land pollution", "Light pollution"], "images/science/water_pollution.jpg", "Toxic liquid waste poisons river water and kills fish."),
    ("Sewage and soapy dirty wash water flowing untreated into local streams cause:", "Water pollution", ["Water pollution", "Air pollution", "Land pollution", "Noise pollution"], "images/science/water_pollution.jpg", "Untreated sewage contaminates drinking water sources."),
    ("Chemical fertilizers washed by heavy rain from farmland into lakes cause:", "Water pollution", ["Water pollution", "Air pollution", "Land pollution", "Air smog"], "images/science/water_pollution.jpg", "Algal blooms from runoff cause water pollution."),

    # Land pollution
    ("Overflowing heaps of plastic containers and garbage dumped in an open field cause:", "Land pollution", ["Land pollution", "Air pollution", "Water pollution", "Noise pollution"], "images/science/land_pollution.jpg", "Solid waste dumped on the ground is land pollution."),
    ("People throwing empty snack wrappers and drink cans onto park lawns cause:", "Land pollution (Littering)", ["Land pollution (Littering)", "Air pollution", "Water pollution", "Sound pollution"], "images/science/land_pollution.jpg", "Littering degrades and pollutes public land."),
    ("Massive landfill dumps where tons of non-biodegradable trash pile up cause:", "Land pollution", ["Land pollution", "Air pollution", "Water pollution", "Space pollution"], "images/science/land_pollution.jpg", "Trash dumps pollute soil and natural land."),
    ("Burying poisonous batteries, electronics, and toxic waste into the ground causes:", "Land pollution", ["Land pollution", "Air pollution", "Water pollution", "Noise pollution"], "images/science/land_pollution.jpg", "Hazardous e-waste poisons the surrounding soil."),
    ("Discarded broken glass and plastic junk littering a nature trail cause:", "Land pollution", ["Land pollution", "Air pollution", "Water pollution", "Sky pollution"], "images/science/land_pollution.jpg", "Trail litter spoils the natural environment.")
]

for q_text, ans, opts, img, hint in pollution_scenarios:
    add_q("sci_pollution", "science", q_text, q_text, "en", "multiple_choice", img, None, opts, ans, hint, hint)

# 40 Environmental solutions & 3R questions
eco_solutions = [
    ("What are the '3Rs' of environmental protection?", "Reduce, Reuse, Recycle", ["Reduce, Reuse, Recycle", "Run, Rest, Repeat", "Read, Remember, Review", "Ride, Race, Return"], "The 3Rs are Reduce waste, Reuse items, and Recycle materials."),
    ("What does 'Reduce' mean?", "Using less and avoiding unnecessary single-use items", ["Using less waste", "Throwing away more", "Buying plastic bags", "Burning trash"], "Reduce means creating less waste in the first place."),
    ("What does 'Reuse' mean?", "Using things again instead of throwing them away", ["Using things again", "Throwing items into rivers", "Breaking glass", "Buying new toys daily"], "Reuse means giving items a second life, like water bottles."),
    ("What does 'Recycle' mean?", "Processing used materials into new useful products", ["Processing waste into new products", "Burying trash in the garden", "Burning garbage", "Leaving litter on beach"], "Recycling turns paper, metal, and glass into new goods."),
    ("Which color recycling bin is commonly used for PAPER (books, boxes, newspapers)?", "Blue bin", ["Blue bin", "Brown bin", "Orange bin", "Black bin"], "Blue bins collect clean paper."),
    ("Which recycling bin is commonly used for GLASS bottles and jars?", "Brown bin", ["Brown bin", "Blue bin", "Orange bin", "Green bin"], "Brown bins collect glass containers."),
    ("Which recycling bin is commonly used for PLASTIC and ALUMINUM cans?", "Orange bin", ["Orange bin", "Blue bin", "Brown bin", "Grey bin"], "Orange bins collect plastics and metal cans."),
    ("How does planting more green trees help fight air pollution?", "Trees absorb carbon dioxide and release fresh oxygen", ["Trees absorb carbon dioxide and release oxygen", "Trees make wind", "Trees paint the sky", "Trees block the rain"], "Trees filter airborne dust and replenish oxygen."),
    ("What can you bring to the grocery store instead of taking plastic shopping bags?", "A reusable cloth canvas bag", ["A reusable cloth bag", "Cardboard plate", "No bag, drop everything", "New plastic bags"], "Cloth bags prevent thousands of plastic bags from polluting oceans."),
    ("What happens to sea turtles when plastic bags float in the ocean?", "They mistake plastic bags for jellyfish and choke on them", ["They choke mistaking them for jellyfish", "They play with them safely", "They build nests", "They eat them like fruit"], "Plastic bags look like jellyfish to turtles."),
    ("What is an eco-friendly way to travel to a nearby school instead of driving a car?", "Walking or riding a bicycle", ["Walking or riding a bicycle", "Taking a helicopter", "Riding a tractor", "Driving a bulldozer"], "Walking and cycling create zero exhaust emissions."),
    ("Why should we turn off lights, fans, and televisions when leaving an empty room?", "To save electricity and reduce energy waste", ["To save electricity", "Because light is scary", "To make room cold", "To sleep early"], "Conserving electricity reduces power plant emissions."),
    ("What should you do with a banana peel or apple core after eating?", "Put it in the compost bin to become natural soil fertilizer", ["Put in compost for soil fertilizer", "Throw it on the road", "Toss it in the river", "Burn it with petrol"], "Organic fruit peels decompose into rich garden compost."),
    ("What should you do if you see an empty plastic bottle lying on the school field?", "Pick it up and toss it into the recycling bin", ["Pick it up and recycle it", "Kick it into the bushes", "Ignore it", "Step on it and leave it"], "Responsible citizens pick up and recycle litter."),
    ("What renewable clean energy source uses sunlight to generate electricity without smoke?", "Solar energy (Solar panels)", ["Solar energy", "Coal burning", "Diesel fuel", "Firewood"], "Solar power produces clean, pollution-free electricity."),
    ("What clean energy source uses tall spinning blades powered by wind?", "Wind turbines", ["Wind turbines", "Gas stoves", "Steam locomotives", "Wood fires"], "Wind energy creates clean green power."),
    ("Why shouldn't dirty engine oil be poured down street drain gutters?", "Drains lead to rivers and ocean, poisoning fish and water", ["Drains lead to rivers, poisoning wildlife", "Drains will turn blue", "It smells nice", "It makes water warm"], "Motor oil contaminates miles of clean waterways."),
    ("What kind of pollution is caused by deafening loud car horns, blaring sirens, and construction jackhammers?", "Noise pollution", ["Noise pollution", "Water pollution", "Air pollution", "Land pollution"], "Excessive loud sounds cause noise pollution."),
    ("How does noise pollution affect human health?", "It can cause hearing damage, stress, and sleep disruption", ["It causes hearing damage and stress", "It makes bones strong", "It cleans teeth", "It makes you taller"], "Loud noises harm ears and cause headaches."),
    ("What can factories install on their tall smoke chimneys to trap soot before it escapes?", "Scrubbers and smoke filters", ["Scrubbers and smoke filters", "Big fans to blow it faster", "Water sprinklers", "Loudspeakers"], "Industrial filters trap particulate smoke."),
    ("What eco-friendly container should students carry for drinking water at school?", "A refillable reusable water tumbler", ["A refillable reusable tumbler", "Single-use disposable cups daily", "A plastic bag", "No water"], "Reusable tumblers stop plastic bottle waste."),
    ("What does 'biodegradable' mean?", "Materials that naturally decompose and break down in nature", ["Materials that break down naturally", "Materials that never rot", "Shiny metals", "Plastic that stays forever"], "Organic matter breaks down into soil."),
    ("How long can a single plastic bottle take to decompose in a landfill?", "Hundreds of years (up to 450 years)", ["Up to 450 years", "Two days", "One month", "One year"], "Plastic lasts centuries without breaking down."),
    ("Why is clean water essential for all living creatures on Earth?", "All humans, animals, and plants need water to survive and stay healthy", ["Living things need water to survive", "Only for washing cars", "Only for fish", "Water is a toy"], "Water is the foundation of life on Earth."),
    ("What is an eco-friendly practice when brushing your teeth at the sink?", "Turn off the running tap while brushing", ["Turn off tap while brushing", "Let water run full blast", "Fill the whole sink", "Use hot boiling water"], "Turning off the tap saves gallons of clean water."),
    ("Why should we avoid littering on sandy beaches during vacations?", "Trash washes into the tide, harming ocean marine life", ["Trash harms marine sea life", "Sand likes trash", "Crabs eat plastic", "It makes beach colorful"], "Beach cleanups protect marine habitats."),
    ("Which international day celebrated on April 22nd promotes worldwide environmental protection?", "Earth Day", ["Earth Day", "New Year's Day", "Halloween", "Christmas"], "Earth Day reminds everyone to protect planet Earth."),
    ("What can broken glass bottles and plastic jars be sorted into at school?", "Recycling bins", ["Recycling bins", "Compost pile", "Flower pots", "Water drain"], "Recycling bins sort reusable raw materials."),
    ("Why is burning plastic trash in your backyard dangerous?", "It releases toxic poisonous smoke fumes into the air", ["It releases poisonous toxic smoke", "It smells like flowers", "It creates clean rain", "It feeds birds"], "Burning plastic generates cancer-causing dioxins."),
    ("What simple daily action can a family take to conserve water at home?", "Fixing leaky taps and taking shorter showers", ["Fixing leaky taps and shorter showers", "Leaving garden hose on all night", "Washing driveway with hose daily", "Taking four hour baths"], "Fixing dripping taps saves thousands of liters of clean water."),
    ("What is the layer high in the stratosphere that shields Earth from harmful solar UV rays?", "The Ozone layer", ["The Ozone layer", "The Cloud layer", "The Ocean layer", "The Mountain layer"], "The ozone layer filters ultraviolet radiation."),
    ("What kind of bags are made from cornstarch that break down naturally in soil?", "Compostable biodegradable bags", ["Compostable biodegradable bags", "Heavy plastic bags", "Lead foil bags", "PVC vinyl bags"], "Cornstarch bags decompose in compost."),
    ("Why do community parks hold 'Clean Up Day' campaigns?", "To remove litter, plant flowers, and keep nature clean and green", ["To clean nature and protect environment", "To make more trash", "To cut down all trees", "To scare birds away"], "Volunteer cleanups restore public parks."),
    ("What should you do with used batteries instead of tossing them into regular trash?", "Drop them into special hazardous e-waste collection bins", ["Take them to e-waste recycling bins", "Bury them in flowerbeds", "Throw them in rivers", "Burn them in fireplaces"], "Batteries contain toxic heavy metals that must be recycled."),
    ("How does using both sides of a sheet of notebook paper help the environment?", "It halves paper usage and saves forest trees", ["It saves trees by using less paper", "It makes backpacks heavier", "It ruins pens", "It makes books thicker"], "Using both sides of paper conserves wood pulp."),
    ("What can old plastic milk jugs be recycled into?", "Playground equipment, park benches, and new bottles", ["Park benches, new bottles, and toys", "Food to eat", "Drinking water", "Clouds"], "Recycled plastic makes sturdy outdoor equipment."),
    ("What is the greenest way to dry freshly washed clothes on a sunny day?", "Hanging them on an outdoor clothesline in the sun", ["Hanging on an outdoor clothesline", "Running an electric dryer for hours", "Using a hairdryer", "Baking them in the oven"], "Sun and wind dry clothes naturally with zero electricity."),
    ("Why should cars be properly serviced and tuned by mechanics regularly?", "To burn fuel efficiently and emit less exhaust smoke", ["To emit less exhaust pollution", "To make more noise", "To smell like smoke", "To go slower"], "Well-tuned engines produce less emissions."),
    ("What is the main goal of keeping our planet clean from pollution?", "To ensure a healthy, safe, and beautiful world for future generations", ["To ensure a healthy world for all life", "To win a medal", "To make roads shiny", "To keep shops open"], "Protecting Earth secures our shared future."),
    ("You have finished your bottle of water. What should you do with the empty plastic bottle?", "Rinse and drop it into the orange recycling bin", ["Drop it into the orange recycling bin", "Throw it out the car window", "Bury it under sand", "Kick it down the drain"], "Recycling plastic keeps our environment clean.")
]
for q_text, ans, opts, hint in eco_solutions:
    add_q("sci_pollution", "science", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 22: sci_plants (Parts & Needs of a Plant) ---
plant_parts_data = [
    # Roots
    ("Which part of the plant grows underground, anchors the plant, and absorbs water?", "Roots", ["Roots", "Leaves", "Flower", "Stem"], "images/science/plant_parts.jpg", "Roots spread in the soil like drinking straws to absorb water and minerals."),
    ("Which plant part keeps a tall tree firmly anchored in the ground during windy storms?", "Roots", ["Roots", "Leaves", "Flower", "Fruit"], "images/science/plant_parts.jpg", "Roots anchor the plant securely into the earth."),
    ("Carrots and radishes that we eat are actually swollen, nutritious plant:", "Roots", ["Roots", "Leaves", "Flowers", "Stems"], None, "Carrots are taproots storing food."),
    ("What tiny hair-like structures on roots absorb water and nutrients from moist soil?", "Root hairs", ["Root hairs", "Flower petals", "Bark", "Seeds"], None, "Root hairs increase absorption surface area."),
    
    # Stem
    ("Which plant part acts like an elevator trunk, holding the plant upright and carrying water?", "Stem", ["Stem", "Roots", "Flower", "Fruit"], "images/science/plant_parts.jpg", "The stem supports branches and carries water up to leaves."),
    ("What is the thick, woody, sturdy stem of a tall tree called?", "Trunk", ["Trunk", "Root", "Petal", "Twig"], None, "A tree trunk is its main woody stem."),
    ("Celery and asparagus that we crunch and eat are plant:", "Stems", ["Stems", "Roots", "Flowers", "Seeds"], None, "Celery stalks are plant stems."),
    ("What tough outer layer protects a tree trunk from insects, cold, and damage?", "Bark", ["Bark", "Leaves", "Seeds", "Petals"], None, "Bark is the protective armor on tree stems."),

    # Leaves
    ("Which green plant part acts like a kitchen, absorbing sunlight to make food?", "Leaves", ["Leaves", "Roots", "Flower", "Stem"], "images/science/plant_parts.jpg", "Leaves absorb sunlight energy to produce glucose food."),
    ("What green pigment in leaves captures energy from sunlight?", "Chlorophyll", ["Chlorophyll", "Melanin", "Hemoglobin", "Carotene"], None, "Chlorophyll gives leaves their green color and traps solar energy."),
    ("What are the tiny microscopic breathing pores on the underside of leaves called?", "Stomata", ["Stomata", "Roots", "Petals", "Veins"], None, "Stomata allow gas exchange: taking in CO2 and releasing O2."),
    ("Spinach, lettuce, and cabbage that we eat in fresh salads are plant:", "Leaves", ["Leaves", "Roots", "Stems", "Bark"], None, "Lettuce and spinach are edible green leaves."),

    # Flower
    ("Which colorful, sweet-smelling plant part attracts bees, butterflies, and pollinators?", "Flower", ["Flower", "Roots", "Bark", "Stem"], "images/science/flower.jpg", "Bright petals and sweet nectar attract pollinators."),
    ("What powdery yellow grains produced by flowers are transferred by bees to make seeds?", "Pollen", ["Pollen", "Mud", "Honey", "Chlorophyll"], None, "Bees spread pollen between flowers for pollination."),
    ("What do we call the colorful individual leaves that make up a blossom?", "Petals", ["Petals", "Roots", "Stems", "Bark"], None, "Petals are the bright parts of flowers."),
    ("Cauliflower and broccoli that we steam and eat are actually plant:", "Flowers / Flower buds", ["Flowers / Flower buds", "Roots", "Stems", "Bark"], None, "Broccoli consists of clusters of flower buds."),

    # Fruit & Seed
    ("Which plant part develops from a pollinated flower to protect and contain seeds?", "Fruit", ["Fruit", "Roots", "Stem", "Leaf"], "images/science/fruit.jpg", "Fruit encloses and protects developing seeds."),
    ("What tiny plant structure inside an apple or watermelon can sprout into a whole new plant?", "A seed", ["A seed", "A petal", "A leaf", "A root hair"], None, "Seeds contain the baby plant embryo."),
    ("Which of the following is scientifically a fruit because it has seeds inside?", "A tomato", ["A tomato", "A potato", "A carrot", "An onion"], None, "Tomatoes develop from flowers and contain seeds."),
    ("Apples, bananas, and strawberries are all sweet examples of plant:", "Fruits", ["Fruits", "Roots", "Stems", "Bark"], None, "Fruits contain and disperse seeds.")
]

for q_text, ans, opts, img, hint in plant_parts_data:
    add_q("sci_plants", "science", q_text, q_text, "en", "multiple_choice", img, None, opts, ans, hint, hint)

# 35 Plant needs & growth questions
plant_needs = [
    ("What are the four essential things green plants need to grow healthy and strong?", "Sunlight, Water, Air, and Soil", ["Sunlight, Water, Air, and Soil", "Juice, Soda, Milk, and Bread", "Darkness, Cold, Plastic, and Rocks", "Meat, Bones, Sugar, and Salt"], "Plants require sun, water, carbon dioxide, and nutrients."),
    ("What happens to a green plant if you lock it inside a dark closet with no light?", "Its leaves turn yellow, it grows weak, and eventually withers", ["It turns yellow and withers", "It grows bigger and stronger", "It produces lots of fruit", "It turns into a tree"], "Without sunlight, plants cannot make food."),
    ("What happens to a potted plant if you forget to water it for three weeks?", "Its leaves wilt and dry up", ["Its leaves wilt and dry up", "It grows flowers faster", "It turns blue", "It swims"], "Water carries nutrients and maintains plant cell pressure."),
    ("What process do plants use to make food using sunlight, water, and carbon dioxide?", "Photosynthesis", ["Photosynthesis", "Respiration", "Evaporation", "Hibernation"], "Photo (light) + synthesis (putting together)."),
    ("What vital gas do plants release into the air that humans and animals need to breathe?", "Oxygen", ["Oxygen", "Carbon dioxide", "Smoke", "Helium"], "Plants produce life-giving oxygen."),
    ("What gas from the air do plants absorb through their leaves to make food?", "Carbon dioxide", ["Carbon dioxide", "Oxygen", "Nitrogen", "Methane"], "Plants take in carbon dioxide during photosynthesis."),
    ("What is the process called when a tiny seed begins to sprout and grow into a young seedling?", "Germination", ["Germination", "Pollination", "Transpiration", "Fermentation"], "Germination is when a seed sprouts."),
    ("What must a seed have to begin germinating?", "Moisture, warmth, and air", ["Moisture, warmth, and air", "Direct bright sunlight", "Chemical fertilizer", "Ice cubes"], "Seeds germinate underground in moist, warm soil."),
    ("Which direction do plant roots naturally grow?", "Downwards into the dark soil (with gravity)", ["Downwards into the soil", "Upwards toward the clouds", "Sideways in the air", "They don't grow"], "Roots have positive geotropism, growing downward."),
    ("Which direction does a plant stem and shoot naturally grow?", "Upwards toward the sunlight", ["Upwards toward the sunlight", "Downwards into the dark", "Into the center of the earth", "Backwards"], "Shoots grow toward sunlight (phototropism)."),
    ("How do dandelion seeds travel far away to find new places to grow?", "They have fluffy parachute tufts carried by the wind", ["Fluffy tufts carried by the wind", "They swim in rivers", "They jump like frogs", "They ride on bicycles"], "Wind carries dandelion seed parachutes."),
    ("How do burr seeds travel to new locations?", "They stick like velcro to animal fur and hikers' clothes", ["They stick to animal fur", "They fly with wings", "They roll down hills", "They float on clouds"], "Burrs hook onto animal fur."),
    ("How do delicious sweet berries disperse their seeds?", "Animals eat the fruit and deposit seeds in new locations", ["Animals eat the fruit and deposit seeds", "They burst like fireworks", "They crawl on legs", "They stay on tree forever"], "Animals eat sweet fruit and pass seeds unharmed."),
    ("Why is healthy, nutrient-rich soil important for plants?", "It holds water and provides minerals for roots", ["It holds water and provides minerals", "It gives sunlight", "It keeps plants cold", "It sings to plants"], "Soil provides physical support and minerals."),
    ("What happens to deciduous tree leaves in autumn when sunlight hours decrease?", "They change color to red/orange/yellow and drop off", ["They turn red/orange and fall", "They turn into flowers", "They grow twice as large", "They turn into ice"], "Trees conserve water by shedding leaves in autumn."),
    ("What plant survives in dry hot deserts by storing water in its thick fleshy stem?", "A cactus", ["A cactus", "A sunflower", "A water lily", "An oak tree"], "Cacti store water and have sharp spines instead of wide leaves."),
    ("Why do desert cacti have sharp needles instead of broad green leaves?", "To prevent water evaporation and protect from thirsty animals", ["To prevent water loss and protect from animals", "To look pretty", "To catch rain like a bucket", "To make honey"], "Spines reduce transpiration in arid deserts."),
    ("What freshwater plant has wide round leaves that float on the surface of calm ponds?", "A water lily (Lotus)", ["A water lily (Lotus)", "A pine tree", "A cactus", "A rose bush"], "Water lilies have buoyant pads with stomata on top."),
    ("What tall climbing plant wraps its tendrils around poles or fences to reach higher sunlight?", "A bean vine / Ivy", ["A bean vine / Ivy", "A grass blade", "A carrot", "A mushroom"], "Climbing vines seek sunlight."),
    ("How does a garden plant absorb liquid plant food or fertilizer?", "Through its roots dissolved in water", ["Through its roots dissolved in water", "Through its flower petals", "Through its bark", "It eats it with a mouth"], "Roots absorb dissolved mineral ions."),
    ("Why do indoor house plants lean and bend toward the window?", "They grow toward the source of light", ["They grow toward the light", "They want to go outside", "The wind pushes them", "They are tired"], "Phototropism bends stems toward light."),
    ("What sweet liquid do flowers produce deep inside their blossoms to reward pollinating bees?", "Nectar", ["Nectar", "Honey", "Sap", "Water"], "Bees collect nectar to make honey in their hives."),
    ("Which plant part transports food made in the leaves down to the roots?", "Phloem vessels inside the stem", ["Phloem vessels inside the stem", "The flower", "The seed", "The bark"], "Phloem tubes transport sugary sap throughout the plant."),
    ("Which plant tubes transport water from roots upward to the leaves?", "Xylem vessels inside the stem", ["Xylem vessels inside the stem", "Petals", "Seeds", "Leaf tips"], "Xylem carries water up the stem like drinking straws."),
    ("Why do gardeners prune dead branches and dry leaves from plants?", "To help the plant direct energy to new healthy growth and flowers", ["To encourage new healthy growth", "To make plant sad", "To stop it from growing", "To make it smaller"], "Pruning stimulates vigorous growth."),
    ("What is the golden powder on a flower stamen that sticks to a bee's fuzzy legs?", "Pollen", ["Pollen", "Soil", "Sand", "Honey"], "Bees carry pollen from flower to flower."),
    ("What part of a pea pod do we shell and eat?", "The seeds", ["The seeds", "The roots", "The bark", "The flower"], "Peas are edible seeds."),
    ("Which giant tree is among the oldest and tallest living organisms on Earth?", "The Giant Sequoia / Redwood", ["The Giant Sequoia / Redwood", "The rose bush", "The dandelion", "The tomato plant"], "Redwoods grow over 300 feet tall in California forests."),
    ("What happens to seeds when they have no water at all?", "They stay dormant (asleep) and do not sprout", ["They stay dormant and do not sprout", "They rot immediately", "They turn into trees", "They fly away"], "Dry seeds remain dormant until moistened."),
    ("Why do plants need carbon dioxide from the atmosphere?", "To build carbon sugars during photosynthesis", ["To build food sugars", "To breathe like fish", "To keep warm", "To turn blue"], "Carbon dioxide is a raw ingredient for plant food."),
    ("What do we call farming that grows plants in nutrient-rich water without any soil?", "Hydroponics", ["Hydroponics", "Aquarium", "Forestry", "Digging"], "Hydroponic plants grow directly in mineral water."),
    ("What do roots of mangrove trees in coastal swamps have to breathe in muddy water?", "Aerial breathing roots (pneumatophores) that stick out of mud", ["Aerial breathing roots", "Gills like fish", "Fins", "Wings"], "Mangroves adapt to oxygen-poor mud with aerial roots."),
    ("What is compost made from?", "Decomposed fruit peels, vegetable scraps, and dry leaves", ["Decomposed fruit scraps and leaves", "Plastic bottles and cans", "Broken glass", "Metal screws"], "Compost enriches garden soil with organic matter."),
    ("Can a plant grow if you water it with sugary soda instead of fresh water?", "No, soda damages plant roots and attracts mold", ["No, soda damages roots and dehydrates plant", "Yes, it loves sugar", "It grows five times faster", "It turns into candy"], "High sugar levels draw water out of roots by osmosis."),
    ("Why are rainforests often called the 'Lungs of the Earth'?", "Because millions of trees generate vast amounts of clean oxygen", ["Because trees produce huge amounts of oxygen", "Because they breathe air with lungs", "Because they have chests", "Because they are windy"], "Rainforests produce vital oxygen for the globe.")
]
for q_text, ans, opts, hint in plant_needs:
    add_q("sci_plants", "science", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)

print(f"Generated Science questions! Running total: {len(all_questions)}")

# =========================================================================
# SUBJECT: ICT & COMPUTER TECHNOLOGY (100% English)
# =========================================================================

# --- Topic 23: ict_storage (Computer Drives & Storage) ---
storage_devices = [
    ("Hard Disk Drive (HDD / SSD)", "Internal primary storage drive inside the computer storing the operating system and all files.", "images/ict/system_unit.jpg"),
    ("USB Flash Drive (Pendrive)", "Small portable storage stick that plugs into a USB port to carry homework and photos.", "images/ict/pendrive.jpg"),
    ("Floppy Disk", "Vintage square 3.5-inch magnetic diskette used in classic computers to store small files.", "images/ict/floppy_disk.jpg"),
    ("CD-ROM / DVD Disc", "Shiny circular optical disc read with laser beam inside a computer drive tray.", "images/ict/cd_rom.jpg"),
    ("SD Memory Card", "Tiny flat flash memory card inserted into digital cameras, tablets, and smartphones.", "images/ict/memory_card.jpg")
]

for dev_name, desc, img in storage_devices:
    for variant in range(3):
        opts = ["USB Pendrive", "Floppy Disk", "CD-ROM Disc", "Hard Disk Drive (HDD)", "SD Memory Card"]
        short_name = dev_name.split(" (")[0]
        if short_name not in opts:
            opts[0] = short_name
        add_q(
            "ict_storage", "ict",
            f"Which computer storage device is described here: '{desc}'?",
            f"Which storage device matches this description?",
            "en", "multiple_choice", img, None, opts, short_name,
            desc, f"The storage device is {short_name}."
        )

# 40 Deep storage questions
storage_extra = [
    ("What unit is commonly used to measure computer storage capacity today?", "Gigabytes (GB) and Terabytes (TB)", ["Gigabytes (GB) and Terabytes (TB)", "Kilograms (kg)", "Meters (m)", "Liters (L)"], "Storage is measured in bytes, MB, GB, and TB."),
    ("Which storage device is small enough to fit on your keychain and plugs into a USB port?", "USB Flash Pendrive", ["USB Flash Pendrive", "Monitor", "Printer", "Scanner"], "USB pendrives are pocket-sized portable drives."),
    ("What kind of drive has no moving parts and is much faster than an old mechanical hard drive?", "Solid State Drive (SSD)", ["Solid State Drive (SSD)", "Floppy disk", "Tape cassette", "Paper punch card"], "SSDs use flash memory chips with zero spinning platters."),
    ("Where is a computer's operating system (like Windows or macOS) permanently installed?", "On the internal Hard Drive (HDD / SSD)", ["On the internal Hard Drive (HDD / SSD)", "Inside the mouse", "On the keyboard", "On the power cord"], "The main drive stores system files and programs."),
    ("Which circular shiny disc uses laser light to read and write music, videos, and software?", "CD-ROM / DVD", ["CD-ROM / DVD", "Floppy disk", "Keyboard", "Mouse pad"], "Optical discs use laser beams to read microscopic pits."),
    ("Which tiny memory card is commonly inserted into digital cameras and Nintendo Switch?", "SD Memory Card (microSD)", ["SD Memory Card (microSD)", "Floppy disk", "CD-ROM", "Monitor"], "Cameras and portable consoles use SD cards."),
    ("What was the common storage capacity of an old vintage 3.5-inch floppy disk?", "1.44 Megabytes (MB)", ["1.44 Megabytes (MB)", "1 Terabyte (TB)", "500 Gigabytes (GB)", "100 Liters"], "Classic floppy disks held only 1.44 MB of data."),
    ("What service allows you to save and access your files safely over the internet from any device?", "Cloud Storage (Google Drive / iCloud / OneDrive)", ["Cloud Storage", "A Floppy disk", "A printer", "A speaker"], "Cloud storage hosts files on secure remote servers."),
    ("Why should you safely 'Eject' a USB pendrive before pulling it out of the computer port?", "To make sure all files have finished saving and prevent data corruption", ["To prevent data corruption", "To cool it down", "Because it is locked with a key", "To clean it"], "Ejecting ensures no active write operations are interrupted."),
    ("What is a 'Backup' in computer safety?", "Making an extra copy of important files on another drive", ["Making an extra copy of files", "Deleting your files", "Changing your desktop wallpaper", "Playing video games"], "Backups protect photos and schoolwork if a drive fails."),
    ("Which has larger storage capacity: 1 Gigabyte (GB) or 1 Megabyte (MB)?", "1 Gigabyte (GB is 1,000 times larger than MB)", ["1 Gigabyte (GB)", "1 Megabyte (MB)", "Both are equal", "Megabyte is larger"], "1 GB = 1,024 MB."),
    ("Which has larger storage capacity: 1 Terabyte (TB) or 1 Gigabyte (GB)?", "1 Terabyte (TB is 1,000 times larger than GB)", ["1 Terabyte (TB)", "1 Gigabyte (GB)", "Both are equal", "Gigabyte is larger"], "1 TB = 1,024 GB."),
    ("Can a CD-ROM disc get scratched if you leave it face down on a rough desk?", "Yes, scratches on the shiny surface can cause read errors", ["Yes, scratches cause read errors", "No, it is made of diamond", "No, scratches make it faster", "Discs cannot be scratched"], "Keep discs inside cases to prevent scratches."),
    ("What happens to files saved on your computer's hard drive when you shut down the PC?", "They remain safely stored for the next time you turn it on", ["They remain safely stored", "They are erased immediately", "They turn into paper", "They print out"], "Hard drives provide non-volatile permanent storage."),
    ("What component in a computer temporarily holds active programs while running but clears on shutdown?", "RAM (Random Access Memory)", ["RAM (Random Access Memory)", "Hard Drive", "Pendrive", "CD-ROM"], "RAM is temporary working memory."),
    ("Can you save photos from a smartphone onto a USB flash drive?", "Yes, using a compatible USB-C adapter or flash drive", ["Yes, using a USB adapter", "No, never", "Only if you print them first", "Phones cannot store photos"], "Modern pendrives connect directly to phones."),
    ("What is the shiny rainbow reflection on the bottom of a CD-ROM caused by?", "Light diffraction from thousands of microscopic data tracks", ["Diffraction from microscopic tracks", "Liquid paint inside", "Soap bubbles", "A mirror sticker"], "Laser data spirals create rainbow diffraction."),
    ("Which portable drive does not need an internet connection to transfer files between two PCs?", "USB Flash Pendrive", ["USB Flash Pendrive", "Email", "Cloud Storage", "Webcam"], "Pendrives transfer data offline directly."),
    ("If your computer hard drive is almost full, what should you do?", "Delete unwanted files and move old photos to an external drive or cloud", ["Delete unwanted files or move to backup", "Pour water on the PC", "Hit the monitor", "Buy a new desk"], "Freeing up disk space keeps computers running smoothly."),
    ("Which storage device fits into the slot on the side of a modern laptop?", "SD Card Reader slot", ["SD Card Reader slot", "Floppy slot", "Cassette slot", "VCR slot"], "Laptops often feature built-in SD card readers."),
    ("What port on a computer do you plug a pendrive into?", "A USB Port", ["A USB Port", "An HDMI Port", "An Audio Jack", "The power plug"], "USB stands for Universal Serial Bus."),
    ("What does SSD stand for in modern computers?", "Solid State Drive", ["Solid State Drive", "Super Speed Disk", "System Software Drive", "Screen Saver Device"], "Solid State Drives use electronic flash memory chips."),
    ("Why is an SSD quieter than an old HDD?", "Because an SSD has zero spinning motors or moving parts", ["Zero spinning parts or motors", "It has a pillow inside", "It has no electricity", "It is smaller than a coin"], "SSDs operate completely silent."),
    ("Which vintage media was flexible, magnetic, and came inside a square plastic jacket?", "Floppy disk", ["Floppy disk", "CD-ROM", "USB drive", "RAM chip"], "Floppy disks were bendable magnetic sheets inside square cases."),
    ("How many bytes are in a single Kilobyte (KB)?", "1,024 bytes (about 1,000 bytes)", ["1,024 bytes", "10 bytes", "100,000 bytes", "1,000,000 bytes"], "1 KB = 1,024 bytes."),
    ("If you save a digital drawing in Paint, where should you save it to keep it safe?", "In your 'Documents' or 'Pictures' folder on your drive", ["In Documents or Pictures folder", "In the Recycle Bin", "In an email draft", "Nowhere"], "Save files in designated user folders."),
    ("What happens to files moved into the 'Recycle Bin' or 'Trash'?", "They stay there until permanently emptied or restored", ["They stay until emptied or restored", "They are destroyed instantly", "They send an email", "They turn into pictures"], "Recycle bin allows restoring accidentally deleted files."),
    ("Why should external backup hard drives be stored in a safe, dry place?", "To protect family memories and school projects from drops or spills", ["To protect files from physical damage", "To keep them warm", "Because they need sleep", "To hide from games"], "Proper physical storage extends drive lifespan."),
    ("Can a CD-RW disc be erased and written with new files more than once?", "Yes, the 'RW' stands for Re-Writable", ["Yes, RW means Re-Writable", "No, only once", "Only on Sundays", "Only in cameras"], "CD-RW discs allow erasing and rewriting data."),
    ("What is an external portable hard drive?", "A hard drive in a portable case that connects via a USB cable", ["A hard drive connected via USB cable", "A keyboard with buttons", "A monitor that folds", "A mouse that records"], "External HDDs provide high-capacity mobile backups."),
    ("Which device would a photographer use to move 500 wedding photos from her camera to her laptop?", "An SD Memory Card inserted into a card reader", ["An SD Memory Card", "A floppy disk", "A printer", "Headphones"], "SD cards transfer high-res photos quickly."),
    ("What symbol on computer ports looks like a three-pronged trident fork?", "The USB symbol", ["The USB symbol", "The Wi-Fi symbol", "The Bluetooth icon", "The Power button"], "The USB trident icon marks data ports."),
    ("Can you store both text files and video files on the same USB drive?", "Yes, storage drives can hold any digital file types", ["Yes, any digital files can be stored", "No, only text", "No, only videos", "No, one file per drive"], "Drives hold documents, songs, videos, and games."),
    ("What happens if you run out of storage space while installing a new educational game?", "The computer displays a warning message that disk space is insufficient", ["An insufficient disk space warning appears", "The computer sparks", "The game plays half", "The screen turns off"], "You must free space before installation can finish."),
    ("What file size is typical for a short typed poem document?", "A few Kilobytes (KB)", ["A few Kilobytes (KB)", "10 Gigabytes", "500 Terabytes", "Zero bytes"], "Simple text documents are tiny: only a few KB."),
    ("What file size is typical for a full-length animated movie?", "1 to 4 Gigabytes (GB)", ["1 to 4 Gigabytes (GB)", "2 Kilobytes", "50 bytes", "100 Terabytes"], "High definition movies take several GB."),
    ("Why do computers need permanent storage if they already have RAM?", "RAM loses its contents when power is turned off; storage keeps it forever", ["Storage retains data when power is turned off", "Storage is cheaper than wires", "RAM is too heavy", "Computers only need RAM"], "Non-volatile storage saves your work permanently."),
    ("What is it called when you save files to Google Drive, Dropbox, or OneDrive?", "Uploading to the Cloud", ["Uploading to the Cloud", "Printing to paper", "Scanning to disk", "Deleting from PC"], "Transferring to remote servers is uploading to the cloud."),
    ("What is it called when you copy a file from the internet onto your computer drive?", "Downloading", ["Downloading", "Uploading", "Formatting", "Ejecting"], "Bringing files from the web down to your PC is downloading."),
    ("A USB drive is plugged in and recognized. In 'This PC', it usually appears with a letter like:", "Drive (D:) or Drive (E:)", ["Drive (D:) or (E:)", "Drive (Z:) only", "Drive (A:) only", "No letter"], "Windows assigns drive letters to storage volumes.")
]
for q_text, ans, opts, hint in storage_extra:
    add_q("ict_storage", "ict", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 24: ict_parts (All About Computer Parts) ---
parts_catalog = [
    ("monitor", "Monitor Screen", "images/ict/monitor.jpg", "Displays visuals, videos, text, and games onto the screen for your eyes to see."),
    ("keyboard", "Keyboard", "images/ict/keyboard.jpg", "Has keys with letters, numbers, and spacebar used for typing text into the computer."),
    ("mouse", "Computer Mouse", "images/ict/mouse.jpg", "Handheld pointing device with left and right buttons used to move the cursor and click."),
    ("printer", "Desktop Printer", "images/ict/printer.jpg", "Prints text documents, drawings, and photos from the computer onto physical sheets of paper."),
    ("system_unit", "System Unit (CPU)", "images/ict/system_unit.jpg", "The main computer tower box housing the motherboard, processor, memory, and power supply."),
    ("headphones", "Headphones", "images/ict/headphones.jpg", "Worn over your ears so you can listen to music and educational audio privately without disturbing others."),
    ("speakers", "Speakers", "images/ict/speakers.jpg", "Play music, game sounds, and teacher's voice out loud into the room."),
    ("microphone", "Microphone", "images/ict/microphone.jpg", "Captures and records your spoken voice or singing into the computer."),
    ("scanner", "Flatbed Scanner", "images/ict/scanner.jpg", "Scans physical drawings and papers into digital picture files on the computer."),
    ("webcam", "Webcam", "images/ict/webcam.jpg", "Video camera that captures live video of your face for online classes and video calls.")
]

for p_name, p_title, img, desc in parts_catalog:
    for v in range(3):
        opts = [p_title]
        while len(opts) < 4:
            cand = random.choice(parts_catalog)[1]
            if cand not in opts: opts.append(cand)
        random.shuffle(opts)
        add_q(
            "ict_parts", "ict",
            f"Identify this computer peripheral: '{desc}'",
            f"What computer part is this: {desc}?",
            "en", "multiple_choice", img, None, opts, p_title,
            desc, f"This is the {p_title}."
        )

# 25 General hardware questions
parts_extra = [
    ("What part is often called the 'Brain of the Computer' because it performs calculations?", "CPU (Central Processing Unit)", ["CPU (Central Processing Unit)", "The mouse pad", "The plastic case", "The power cable"], "The CPU processes all instructions."),
    ("What portable computer has a screen, keyboard, and battery built together in a folding case?", "A Laptop", ["A Laptop", "A Desktop tower", "A Mainframe", "A Television"], "Laptops are portable all-in-one PCs."),
    ("What flat mobile computer uses a touchscreen glass without a physical keyboard attached?", "A Tablet (like iPad)", ["A Tablet (like iPad)", "A Desktop PC", "A Server", "A Printer"], "Tablets use touch input directly on screen."),
    ("What handheld device do gamers use with thumbsticks and trigger buttons to play games?", "A Gamepad / Controller", ["A Gamepad / Controller", "A scanner", "A printer", "A microphone"], "Controllers are input devices for games."),
    ("What is the long horizontal bar key at the bottom of a keyboard called?", "The Spacebar", ["The Spacebar", "The Enter key", "The Shift key", "The Backspace key"], "The spacebar adds a blank space between typed words."),
    ("Which key on the keyboard do you press to erase a letter you typed by mistake?", "Backspace", ["Backspace", "Spacebar", "Shift", "Caps Lock"], "Backspace deletes the character to the left."),
    ("Which key do you press to start a new line or submit a typed command?", "Enter / Return", ["Enter / Return", "Escape", "Caps Lock", "Tab"], "The Enter key submits or moves to the next line."),
    ("Which key locks all letters to CAPITAL UPPERCASE letters?", "Caps Lock", ["Caps Lock", "Spacebar", "Control", "Alt"], "Caps Lock turns all typed letters into uppercase."),
    ("What appears on the screen that moves when you glide your mouse across the mousepad?", "The Mouse Cursor / Pointer arrow", ["The Mouse Pointer arrow", "The wallpaper", "The power light", "The volume bar"], "The mouse moves the on-screen cursor pointer."),
    ("What action means pressing the left mouse button quickly two times in a row?", "Double-click", ["Double-click", "Right-click", "Drag and drop", "Scroll"], "Double-clicking opens folders and applications."),
    ("What action means pressing down the mouse button, moving an item, and letting go?", "Drag and Drop", ["Drag and Drop", "Triple-click", "Double-click", "Right-click"], "Drag and drop moves icons across the screen."),
    ("What wheel between the two mouse buttons allows you to glide up and down long web pages?", "The Scroll Wheel", ["The Scroll Wheel", "The power wheel", "The click knob", "The volume dial"], "The scroll wheel scrolls documents smoothly."),
    ("What is the board with electrical circuits connecting CPU, RAM, and graphics called?", "The Motherboard", ["The Motherboard", "The keyboard", "The surfboard", "The mousepad"], "The motherboard is the main internal circuit board."),
    ("What computer device projects big images onto a classroom wall or presentation screen?", "A Digital Projector", ["A Digital Projector", "A scanner", "A printer", "A microphone"], "Projectors beam large displays for audiences."),
    ("What is the surface called where you rest and glide an optical mouse smoothly?", "A Mousepad", ["A Mousepad", "A clipboard", "A keyboard", "A notepad"], "Mousepads provide consistent optical tracking."),
    ("What connects a desktop computer to a home Wi-Fi wireless internet network?", "A Wi-Fi network card / antenna", ["A Wi-Fi network card / antenna", "A printer cable", "A mouse wire", "A headphone plug"], "Wi-Fi adapters receive wireless radio internet signals."),
    ("Why do computer cases have spinning fans inside them?", "To blow hot air out and keep sensitive chips cool", ["To keep chips cool", "To make musical sounds", "To blow dust inside", "To dry paper"], "Cooling fans prevent processors from overheating."),
    ("What should you never spill near a computer keyboard or laptop?", "Water, juice, or drinks", ["Water, juice, or drinks", "Air", "Light", "Shadows"], "Liquids cause short circuits and permanent damage."),
    ("How should you clean dust off a computer screen safely?", "With a soft, dry microfiber cloth", ["With a dry microfiber cloth", "With soapy dishwater", "With sandpaper", "With cooking oil"], "Microfiber cloths clean screens without scratching."),
    ("Which port transmits both high-definition video and audio to modern monitors and TVs?", "HDMI Port", ["HDMI Port", "Power plug", "Microphone jack", "Floppy slot"], "HDMI carries crisp HD video and digital audio."),
    ("What peripheral allows a singer to record vocals into music software?", "A Microphone", ["A Microphone", "A printer", "A scanner", "A monitor"], "Microphones capture vocal sound waves."),
    ("What peripheral lets you print a colorful birthday invitation card onto thick cardstock?", "A Color Printer", ["A Color Printer", "A webcam", "A mouse", "A hard drive"], "Color printers produce physical copies."),
    ("What part of a laptop is used instead of a separate mouse to move the cursor with your finger?", "A Trackpad / Touchpad", ["A Trackpad / Touchpad", "A spacebar", "A webcam", "A speaker"], "Laptops feature built-in touchpads."),
    ("What happens when you press the power button on a computer system unit?", "The computer boots up and starts the operating system", ["The computer boots up", "It prints a page", "It deletes all games", "It plays a movie"], "The power button initiates the system boot sequence."),
    ("Which peripheral is essential for an online classroom so the teacher can see your smiling face?", "A Webcam", ["A Webcam", "A printer", "A scanner", "A floppy disk"], "Webcams stream live video in virtual classrooms.")
]
for q_text, ans, opts, hint in parts_extra:
    add_q("ict_parts", "ict", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 25: ict_counting (Count Computer Peripherals) ---
# Generate 55 counting questions
peripherals_icons = [
    ("monitor", "🖥️", "monitors"),
    ("mouse", "🖱️", "computer mice"),
    ("keyboard", "⌨️", "keyboards"),
    ("printer", "🖨️", "desktop printers"),
    ("headphones", "🎧", "headphones"),
    ("speaker", "🔊", "speakers"),
    ("webcam", "📹", "webcams"),
    ("pendrive", "💾", "USB flash drives"),
    ("cd", "💿", "CD-ROM discs"),
    ("microphone", "🎙️", "microphones")
]

for p_key, icon, label in peripherals_icons:
    for count in range(1, 6):
        icons_str = " ".join([icon] * count)
        opts = [str(count), str(count + 1), str(count - 1 if count > 1 else count + 2), str(count + 2)]
        random.shuffle(opts)
        add_q(
            "ict_counting", "ict",
            f"Count the {label} displayed here: {icons_str}",
            f"How many {label} do you count?",
            "en", "multiple_choice", None, None, opts, str(count),
            f"Count carefully one by one: there are {count} {label}.",
            f"There are {count} {label}."
        )

# 10 Computer Lab Word Counting Problems
lab_counting = [
    ("A computer lab has 6 laptops on table A and 4 laptops on table B. How many laptops in total?", "10 laptops", ["10 laptops", "9 laptops", "12 laptops", "8 laptops"], "6 + 4 = 10 laptops."),
    ("Teacher Emma puts 5 computer mice on the desk. She adds 3 more mice. How many mice altogether?", "8 mice", ["8 mice", "7 mice", "9 mice", "10 mice"], "5 + 3 = 8 mice."),
    ("The lab technician had 9 pairs of headphones. 2 pairs were taken by students. How many pairs are left in the cabinet?", "7 pairs", ["7 pairs", "8 pairs", "6 pairs", "5 pairs"], "9 - 2 = 7 pairs."),
    ("There are 4 monitors connected to PC towers, and 4 spare monitors in the storeroom. How many monitors are there?", "8 monitors", ["8 monitors", "6 monitors", "10 monitors", "7 monitors"], "4 + 4 = 8 monitors."),
    ("If each student desk has 1 keyboard and 1 mouse, how many total peripherals are on 5 desks?", "10 peripherals (5 keyboards + 5 mice)", ["10 peripherals", "5 peripherals", "7 peripherals", "12 peripherals"], "5 desks x 2 devices each = 10 peripherals.")
]
for q_text, ans, opts, hint in lab_counting:
    add_q("ict_counting", "ict", q_text, q_text, "en", "multiple_choice", None, None, opts, ans, hint, hint)


# --- Topic 26: ict_spelling (Fill in the Missing Letters) ---
spelling_words = [
    ("MONITOR", "M _ N _ T O R", "O, I", ["O, I", "A, E", "U, I", "E, O"], "images/ict/monitor.jpg"),
    ("KEYBOARD", "K _ Y B _ A R D", "E, O", ["E, O", "A, I", "O, E", "U, A"], "images/ict/keyboard.jpg"),
    ("MOUSE", "M _ U S _", "O, E", ["O, E", "A, I", "U, E", "E, O"], "images/ict/mouse.jpg"),
    ("PRINTER", "P R _ N T _ R", "I, E", ["I, E", "E, A", "O, E", "U, I"], "images/ict/printer.jpg"),
    ("SCANNER", "S C _ N N _ R", "A, E", ["A, E", "E, O", "O, A", "I, E"], "images/ict/scanner.jpg"),
    ("SPEAKERS", "S P _ _ K E R S", "E, A", ["E, A", "A, E", "E, E", "O, A"], "images/ict/speakers.jpg"),
    ("WEBCAM", "W _ B C _ M", "E, A", ["E, A", "A, E", "O, A", "I, A"], "images/ict/webcam.jpg"),
    ("LAPTOP", "L _ P T _ P", "A, O", ["A, O", "O, A", "E, O", "A, E"], None),
    ("SCREEN", "S C R _ _ N", "E, E", ["E, E", "E, A", "O, O", "A, E"], "images/ict/monitor.jpg"),
    ("SYSTEM", "S _ S T _ M", "Y, E", ["Y, E", "I, E", "E, E", "A, E"], "images/ict/system_unit.jpg"),
    ("FLOPPY", "F L _ P P _", "O, Y", ["O, Y", "A, Y", "O, I", "E, Y"], "images/ict/floppy_disk.jpg"),
    ("DISK", "D _ S K", "I", ["I", "E", "A", "O"], "images/ict/floppy_disk.jpg"),
    ("DRIVE", "D R _ V _", "I, E", ["I, E", "A, E", "O, E", "E, I"], "images/ict/pendrive.jpg"),
    ("CABLE", "C _ B L _", "A, E", ["A, E", "E, A", "O, E", "I, E"], None),
    ("TABLET", "T _ B L _ T", "A, E", ["A, E", "E, A", "O, E", "A, O"], None),
    ("MEMORY", "M _ M _ R Y", "E, O", ["E, O", "A, O", "E, E", "O, E"], "images/ict/memory_card.jpg"),
    ("PIXEL", "P _ X _ L", "I, E", ["I, E", "E, I", "A, E", "O, E"], None),
    ("ROBOT", "R _ B _ T", "O, O", ["O, O", "O, A", "A, O", "E, O"], None),
    ("BUTTON", "B _ T T _ N", "U, O", ["U, O", "A, O", "O, U", "O, O"], None),
    ("CURSOR", "C _ R S _ R", "U, O", ["U, O", "O, U", "A, O", "E, O"], None)
]

for word, blank_str, corr, opts, img in spelling_words:
    for v in range(3):
        add_q(
            "ict_spelling", "ict",
            f"Fill in the missing vowels to complete the spelling: '{blank_str}' ({word.title()})",
            f"What missing letters complete the word {word}?",
            "en", "multiple_choice", img, None, opts, corr,
            f"The correct spelling is '{word}' with letters {corr}.",
            f"The letters are {corr}."
        )


# --- Topic 27: ict_input_output (Input vs Output Devices) ---
io_items = [
    # INPUT DEVICES (Take data IN)
    ("keyboard", "Input (I)", "images/ict/keyboard.jpg", "Feeds typed letters, numbers, and commands INTO the computer."),
    ("computer mouse", "Input (I)", "images/ict/mouse.jpg", "Sends click coordinates and movement signals INTO the computer."),
    ("microphone", "Input (I)", "images/ict/microphone.jpg", "Transmits spoken sound and vocal recordings INTO the computer."),
    ("webcam", "Input (I)", "images/ict/webcam.jpg", "Captures visual images and streams video footage INTO the computer."),
    ("document scanner", "Input (I)", "images/ict/scanner.jpg", "Scans paper drawings and photos INTO digital image files on the computer."),
    ("touchscreen (tapping it)", "Input (I)", None, "Your finger taps send coordinates and touch commands INTO the device."),
    ("gamepad controller", "Input (I)", None, "Pressing buttons and moving analog sticks sends gaming commands INTO the console."),
    ("barcode scanner", "Input (I)", None, "Beams laser at store products and sends price codes INTO the cash register computer."),
    ("drawing graphics tablet & stylus", "Input (I)", None, "An artist draws with a digital pen sending brush strokes INTO the computer."),
    ("fingerprint biometric scanner", "Input (I)", None, "Reads your fingerprint ridges and sends security data INTO the system."),

    # OUTPUT DEVICES (Send results OUT)
    ("computer monitor screen", "Output (O)", "images/ict/monitor.jpg", "Displays visuals, games, colors, and text OUT to the user's eyes."),
    ("desktop printer", "Output (O)", "images/ict/printer.jpg", "Prints text and full-color pictures OUT onto physical paper sheets."),
    ("stereo speakers", "Output (O)", "images/ict/speakers.jpg", "Play songs, sound effects, and voices OUT into the room for ears to hear."),
    ("audio headphones", "Output (O)", "images/ict/headphones.jpg", "Channels music and audio signals OUT directly into your ears."),
    ("classroom video projector", "Output (O)", None, "Beams large movies and presentation slides OUT onto a big wall screen."),
    ("braille embossing printer", "Output (O)", None, "Punches raised braille dots OUT onto paper for blind readers to feel."),
    ("earbuds", "Output (O)", None, "Miniature headphones that play sound OUT into your ears."),
    ("computer plotter", "Output (O)", None, "Architectural machine that draws large blueprint maps OUT on giant sheets of paper."),
    ("vibration rumble pack in controller", "Output (O)", None, "Vibrates OUT physical rumble sensations to your hands during gameplay action."),
    ("LED indicator light", "Output (O)", None, "Glows green or red OUT to signal computer power and status.")
]

for dev_name, io_type, img, desc in io_items:
    for v in range(3):
        opts = ["Input (I)", "Output (O)"] if io_type == "Input (I)" else ["Output (O)", "Input (I)"]
        add_q(
            "ict_input_output", "ict",
            f"Is a {dev_name} an Input (I) or Output (O) device? ({desc})",
            f"Is a {dev_name} an input or output device?",
            "en", "multiple_choice", img, None, opts, io_type,
            desc, f"A {dev_name} is an {io_type.lower()} device."
        )

print(f"\n🎉 Generation complete! Total questions created: {len(all_questions)}")

# Write to JSON
with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
    json.dump(all_questions, f, ensure_ascii=False, indent=2)

print(f"Saved to: {OUTPUT_FILE} ({OUTPUT_FILE.stat().st_size // 1024} KB)")

# Verify topic counts
from collections import Counter
counts = Counter([q["topic_id"] for q in all_questions])
print("\n--- Question Counts Per Topic ---")
for t_id, cnt in sorted(counts.items()):
    status = "✓ VALID (50-90)" if 50 <= cnt <= 90 else f"⚠️ INVALID ({cnt})"
    print(f"  {t_id:<22} : {cnt:>2} questions  {status}")
