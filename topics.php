<?php
// NextGrade - Subject Topics Hub
require_once __DIR__ . '/db.php';
session_start();

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? 'Kawan Pintar';
$studentAvatar = $_SESSION['student_avatar'] ?? 'star_kid';
$subjectId = $_GET['subject'] ?? 'bahasa_melayu';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Topik Pembelajaran - NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <script src="js/sounds.js"></script>
  <script src="js/speech.js"></script>
</head>
<body class="min-h-screen flex flex-col justify-between p-4 md:p-8 select-none">

<div class="max-w-5xl w-full mx-auto flex flex-col gap-6">

  <!-- Top Navigation Bar -->
  <header class="bg-white px-6 py-4 rounded-3xl shadow-sm border-2 border-slate-200 flex items-center justify-between">
    <a 
      href="index.php" 
      onclick="SoundEffects.playPop();"
      class="btn-chunky btn-white text-sm md:text-base py-2 px-4 rounded-2xl flex items-center gap-2"
    >
      <span>⬅️</span>
      <span class="font-extrabold">Utama</span>
    </a>

    <div class="flex items-center gap-2 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
      <span class="text-xl">⭐</span>
      <span class="font-black text-slate-700 text-sm md:text-base"><?= htmlspecialchars($studentName) ?></span>
    </div>

    <a 
      href="parent.php" 
      class="btn-chunky btn-white text-sm md:text-base py-2 px-4 rounded-2xl flex items-center gap-2"
    >
      <span>👨‍👩‍👧</span>
      <span class="hidden sm:inline font-bold">Kemajuan</span>
    </a>
  </header>

  <!-- Subject Hero Banner -->
  <div id="subject-banner" class="bg-gradient-to-r from-sky-400 to-indigo-600 rounded-[2.5rem] p-6 md:p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-4 text-center md:text-left">
      <div id="subject-icon" class="text-5xl md:text-6xl p-3 bg-white/20 backdrop-blur-md rounded-2xl">
        📚
      </div>
      <div>
        <span class="bg-white/20 text-white text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-1">
          Mata Pelajaran
        </span>
        <h1 id="subject-title" class="text-3xl md:text-4xl font-black">
          Memuatkan...
        </h1>
        <p id="subject-desc" class="text-sky-100 text-sm md:text-base font-bold">
          Sila tunggu sebentar...
        </p>
      </div>
    </div>

    <!-- Quick 10 Random Quiz from this Subject -->
    <a 
      id="subject-random-quiz-btn"
      href="quiz.php?subject=<?= urlencode($subjectId) ?>"
      onclick="SoundEffects.playPop();"
      class="btn-chunky btn-amber py-3 px-5 rounded-2xl text-base shadow-md shrink-0 flex items-center gap-2"
    >
      <span>🚀</span>
      <span>Kuiz Campur 10 Soalan</span>
    </a>
  </div>

  <!-- Topics Section -->
  <div>
    <div class="flex items-center justify-between mb-4 px-2">
      <h2 class="text-2xl font-black text-slate-800 flex items-center gap-2">
        <span>📑</span> Pilih Topik Pembelajaran
      </h2>
      <span id="topic-count-badge" class="text-sm font-bold text-slate-400">0 Topik</span>
    </div>

    <div id="topics-list" class="flex flex-col gap-4">
      <div class="text-center py-12 text-slate-400 font-bold">
        <div class="text-4xl mb-2 animate-spin">⏳</div>
        Memuatkan senarai topik...
      </div>
    </div>
  </div>

</div>

<!-- Footer -->
<footer class="text-center text-xs font-bold text-slate-400 mt-8">
  NextGrade • 5 Minit Ulang Kaji • 10 Soalan Setiap Sesi • Lembaran Cetakan
</footer>

<script>
  const subjectId = "<?= htmlspecialchars($subjectId) ?>";

  async function loadSubjectAndTopics() {
    try {
      const resp = await fetch(`api/subjects.php?id=${encodeURIComponent(subjectId)}`);
      const data = await resp.json();

      if (!data.success || !data.subject) {
        document.getElementById('topics-list').innerHTML = '<p class="text-red-500 font-bold">Subjek tidak dijumpai.</p>';
        return;
      }

      const s = data.subject;
      document.getElementById('subject-title').textContent = s.name;
      document.getElementById('subject-desc').textContent = s.description;
      document.getElementById('subject-icon').textContent = s.icon;
      document.getElementById('topic-count-badge').textContent = `${s.topics.length} Topik Tersedia`;

      const banner = document.getElementById('subject-banner');
      banner.className = `bg-gradient-to-r ${s.theme_gradient} rounded-[2.5rem] p-6 md:p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-4`;

      const listContainer = document.getElementById('topics-list');
      
      listContainer.innerHTML = s.topics.map((t, idx) => `
        <div class="bg-white rounded-3xl p-5 md:p-6 border-2 border-slate-200 shadow-sm hover:border-sky-300 transition-all flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
          <div class="flex items-start gap-4 flex-1">
            <span class="text-4xl p-3 bg-slate-50 border border-slate-100 rounded-2xl shrink-0">
              ${t.icon}
            </span>
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="bg-slate-100 text-slate-600 text-xs font-extrabold px-2.5 py-0.5 rounded-full">
                  Topik ${idx + 1}
                </span>
                <span class="text-xs font-bold text-slate-400">
                  ${t.question_count} Soalan
                </span>
              </div>
              <h3 class="text-xl md:text-2xl font-black text-slate-800 mb-1">
                ${t.name}
              </h3>
              <p class="text-sm font-bold text-slate-500">
                ${t.description}
              </p>
            </div>
          </div>

          <!-- 3 Core Action Buttons (Requirement 1.10, 1.11, 1.12) -->
          <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto shrink-0 justify-end pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
            <!-- 1. 5-Min Revision (Requirement 1.11) -->
            <a 
              href="revision.php?topic=${t.id}"
              onclick="SoundEffects.playPop();"
              title="5 Minit Ulang Kaji Topik Ini"
              class="btn-chunky btn-purple text-sm md:text-base py-2.5 px-4 rounded-2xl flex-1 md:flex-none flex items-center justify-center gap-1.5 shadow-sm"
            >
              <span>⏱️</span>
              <span class="font-extrabold">Ulang Kaji</span>
            </a>

            <!-- 2. Start 10-Question Quiz (Requirement 1.10) -->
            <a 
              href="quiz.php?topic=${t.id}"
              onclick="SoundEffects.playPop();"
              title="Mula Kuiz 10 Soalan"
              class="btn-chunky btn-success text-sm md:text-base py-2.5 px-5 rounded-2xl flex-1 md:flex-none flex items-center justify-center gap-1.5 shadow-sm"
            >
              <span>🚀</span>
              <span class="font-black">Kuiz (10)</span>
            </a>

            <!-- 3. Print Worksheet (Requirement 1.12) -->
            <a 
              href="worksheet.php?topic=${t.id}"
              onclick="SoundEffects.playPop();"
              title="Cetak Lembaran Kerja Menulis"
              class="btn-chunky btn-white text-sm md:text-base py-2.5 px-3.5 rounded-2xl flex items-center justify-center gap-1.5 shadow-sm"
            >
              <span>🖨️</span>
              <span class="font-bold">Cetak</span>
            </a>
          </div>
        </div>
      `).join('');

    } catch (err) {
      console.error(err);
    }
  }

  document.addEventListener('DOMContentLoaded', loadSubjectAndTopics);
</script>

</body>
</html>
