<?php
session_start();
require_once 'db.php';

$student = $_SESSION['student'] ?? $_COOKIE['student'] ?? 'Kawan';

// Define the KG3 Malay categories
$categories = [
    [
        'id' => 'kenderaan',
        'title' => 'Kenderaan',
        'desc' => 'Kereta, Kapal Terbang & Lain-lain',
        'icon' => '🚗',
        'color' => 'bg-amber-50 border-amber-300 text-amber-900 hover:bg-amber-100'
    ],
    [
        'id' => 'bulan',
        'title' => 'Bulan dalam Setahun',
        'desc' => 'Januari hingga Disember',
        'icon' => '📅',
        'color' => 'bg-sky-50 border-sky-300 text-sky-900 hover:bg-sky-100'
    ],
    [
        'id' => 'haiwan',
        'title' => 'Haiwan',
        'desc' => 'Kenali haiwan jinak & liar',
        'icon' => '🐱',
        'color' => 'bg-emerald-50 border-emerald-300 text-emerald-900 hover:bg-emerald-100'
    ],
    [
        'id' => 'suku_kata',
        'title' => 'Suku Kata',
        'desc' => 'Gabung bunyi kosa kata mudah',
        'icon' => '🧩',
        'color' => 'bg-purple-50 border-purple-300 text-purple-900 hover:bg-purple-100'
    ],
    [
        'id' => 'semua',
        'title' => 'Campur Semua',
        'desc' => 'Uji minda dengan semua topik!',
        'icon' => '🌟',
        'color' => 'bg-rose-50 border-rose-300 text-rose-900 hover:bg-rose-100'
    ]
];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Topik - Bahasa Melayu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-6 select-none font-sans">

  <div class="w-full max-w-4xl bg-white p-8 md:p-12 rounded-[3rem] shadow-xl border-4 border-slate-200 text-center relative">
    
    <a href="index.php" class="absolute top-6 left-6 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold px-5 py-2.5 rounded-2xl transition-all flex items-center gap-2">
      ⬅️ Menu Utama
    </a>

    <div class="text-7xl mt-4 mb-2">📚</div>
    <h1 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight mb-2">
      Bahasa Melayu
    </h1>
    <p class="text-xl md:text-2xl font-bold text-slate-400 mb-8">
      Pilih topik yang <?= htmlspecialchars($student) ?> nak belajar hari ini:
    </p>

    <!-- Category Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full max-w-3xl mx-auto">
      <?php foreach ($categories as $cat): ?>
        <a 
          href="malay.php?category=<?= urlencode($cat['id']) ?>" 
          class="block w-full text-left p-6 rounded-3xl border-4 border-b-[8px] <?= $cat['color'] ?> active:border-b-4 active:translate-y-1 transition-all cursor-pointer group shadow-sm"
        >
          <div class="flex items-center gap-5">
            <span class="text-5xl group-hover:scale-110 transition-transform"><?= $cat['icon'] ?></span>
            <div>
              <h2 class="text-2xl font-black tracking-tight"><?= $cat['title'] ?></h2>
              <p class="text-sm font-semibold opacity-75 mt-0.5"><?= $cat['desc'] ?></p>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

  </div>

</body>
</html>