<?php
// NextGrade - Subject Topics Hub
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helper.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? 'Kawan Pintar';
$studentAvatar = $_SESSION['student_avatar'] ?? 'star_kid';
$studentId = $_SESSION['student_id'] ?? null;
$studentGrade = $_SESSION['student_grade'] ?? null;

if ($studentId && !$studentGrade) {
    $stmtG = $pdo->prepare("SELECT grade_level FROM students WHERE id = ?");
    $stmtG->execute([$studentId]);
    $studentGrade = $stmtG->fetchColumn();
    if ($studentGrade) {
        $_SESSION['student_grade'] = $studentGrade;
    }
}

$isKG3 = empty($studentGrade) || isGradeKG3($studentGrade);
$isYear6 = isGradeYear6($studentGrade);
$hasActiveCurriculum = $isKG3 || $isYear6;
$subjectId = $_GET['subject'] ?? 'bahasa_melayu';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Learning Topics - NextGrade</title>
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
      <span class="font-extrabold">Home</span>
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
      <span class="hidden sm:inline font-bold">Progress</span>
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
          Subject Module
        </span>
        <h1 id="subject-title" class="text-3xl md:text-4xl font-black">
          Loading...
        </h1>
        <p id="subject-desc" class="text-sky-100 text-sm md:text-base font-bold">
          Please wait a moment...
        </p>
      </div>
    </div>

    <!-- Quick 10 Random Quiz from this Subject -->
    <?php if ($hasActiveCurriculum): ?>
    <a 
      id="subject-random-quiz-btn"
      href="quiz.php?subject=<?= urlencode($subjectId) ?>"
      onclick="SoundEffects.playPop();"
      class="btn-chunky btn-amber py-3 px-5 rounded-2xl text-base shadow-md shrink-0 flex items-center gap-2"
    >
      <span>🚀</span>
      <span>Mixed 10-Question Quiz</span>
    </a>
    <?php else: ?>
    <div class="bg-white/20 backdrop-blur-md px-4 py-2.5 rounded-2xl text-xs font-black text-white shrink-0 flex items-center gap-1.5 border border-white/30">
      <span>🔒</span> <span>Curriculum Coming Soon</span>
    </div>
    <?php endif; ?>
  </div>

  <?php if (!$hasActiveCurriculum): ?>
  <!-- Non-Supported Grade Curriculum Notice Banner -->
  <div class="bg-amber-50 border-3 border-amber-300 rounded-3xl p-5 md:p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-3.5 text-center sm:text-left">
      <span class="text-3xl p-2.5 bg-amber-100 rounded-2xl shrink-0">🔒</span>
      <div>
        <h3 class="text-base font-black text-amber-950">Questions are for KG3 & Year 6 (PSR Brunei)</h3>
        <p class="text-xs font-bold text-amber-800 mt-0.5">
          All current questions in NextGrade are tailored for <strong>Kindergarten 3 (KG3)</strong> and <strong>Year 6 (PSR Brunei)</strong>. Questions for your grade level (<strong><?= htmlspecialchars($studentGrade) ?></strong>) are coming soon!
        </p>
      </div>
    </div>
    <a href="index.php?reset=1" onclick="SoundEffects.playPop();" class="btn-chunky btn-primary text-xs py-2.5 px-4 rounded-xl font-black shrink-0 flex items-center gap-1.5">
      <span>🔄</span> <span>Switch Student Profile</span>
    </a>
  </div>
  <?php endif; ?>

  <!-- Topics Section -->
  <div>
    <div class="flex items-center justify-between mb-4 px-2">
      <h2 class="text-2xl font-black text-slate-800 flex items-center gap-2">
        <span>📑</span> Select a Learning Topic
      </h2>
      <span id="topic-count-badge" class="text-sm font-bold text-slate-400">0 Topics</span>
    </div>

    <div id="topics-list" class="flex flex-col gap-4">
      <div class="text-center py-12 text-slate-400 font-bold">
        <div class="text-4xl mb-2 animate-spin">⏳</div>
        Loading topic modules...
      </div>
    </div>
  </div>

</div>

<!-- Footer -->
<footer class="text-center text-xs font-bold text-slate-400 mt-8">
  NextGrade • 5-Minute Revisions • 10 Questions Per Session • Handwriting Worksheets
</footer>

<script>
  const subjectId = "<?= htmlspecialchars($subjectId) ?>";
  const isKG3 = <?= json_encode($isKG3) ?>;
  const isYear6 = <?= json_encode($isYear6) ?>;
  const hasActiveCurriculum = <?= json_encode($hasActiveCurriculum) ?>;

  async function loadSubjectAndTopics() {
    try {
      const resp = await fetch(`api/subjects.php?id=${encodeURIComponent(subjectId)}`);
      const data = await resp.json();

      if (!data.success || !data.subject) {
        document.getElementById('topics-list').innerHTML = '<p class="text-red-500 font-bold">Subject not found.</p>';
        return;
      }

      const s = data.subject;
      document.getElementById('subject-title').textContent = s.name;
      document.getElementById('subject-desc').textContent = s.description;
      document.getElementById('subject-icon').textContent = s.icon;
      document.getElementById('topic-count-badge').textContent = `${s.topics.length} Topics Available`;

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
                  Topic ${idx + 1}
                </span>
                <span class="text-xs font-bold text-slate-400">
                  ${t.question_count} Questions
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

          <!-- 3 Core Action Buttons -->
          <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto shrink-0 justify-end pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
            <!-- 1. 5-Min Revision -->
            <a 
              href="revision.php?topic=${t.id}"
              onclick="SoundEffects.playPop();"
              title="5-Minute Topic Revision"
              class="btn-chunky btn-purple text-sm md:text-base py-2.5 px-4 rounded-2xl flex-1 md:flex-none flex items-center justify-center gap-1.5 shadow-sm"
            >
              <span>⏱️</span>
              <span class="font-extrabold">Revision</span>
            </a>

            <!-- 2. Start 10-Question Quiz -->
            ${hasActiveCurriculum ? `
              <a 
                href="quiz.php?topic=${t.id}"
                onclick="SoundEffects.playPop();"
                title="Start 10-Question Quiz"
                class="btn-chunky btn-success text-sm md:text-base py-2.5 px-5 rounded-2xl flex-1 md:flex-none flex items-center justify-center gap-1.5 shadow-sm"
              >
                <span>🚀</span>
                <span class="font-black">Quiz (10)</span>
              </a>
            ` : `
              <button 
                onclick="SoundEffects.playBoop(); alert('Questions are for KG3 and Year 6 (PSR Brunei) students. Content for <?= htmlspecialchars(addslashes($studentGrade)) ?> is coming soon!');"
                title="Questions coming soon"
                class="btn-chunky btn-white text-xs md:text-sm py-2.5 px-4 rounded-2xl flex-1 md:flex-none flex items-center justify-center gap-1.5 border-amber-300 text-amber-800 opacity-80"
              >
                <span>🔒</span>
                <span class="font-black">Coming Soon</span>
              </button>
            `}

            <!-- 3. Print Worksheet -->
            ${hasActiveCurriculum ? `
              <a 
                href="worksheet.php?topic=${t.id}"
                onclick="SoundEffects.playPop();"
                title="Print Practice Worksheet"
                class="btn-chunky btn-white text-sm md:text-base py-2.5 px-3.5 rounded-2xl flex items-center justify-center gap-1.5 shadow-sm"
              >
                <span>🖨️</span>
                <span class="font-bold">Print</span>
              </a>
            ` : `
              <button 
                onclick="SoundEffects.playBoop(); alert('Worksheet questions are for KG3 and Year 6 (PSR Brunei) students.');"
                title="Worksheets coming soon"
                class="btn-chunky btn-white text-xs md:text-sm py-2.5 px-3 rounded-2xl flex items-center justify-center gap-1 border-amber-300 text-amber-800 opacity-80"
              >
                <span>🔒</span>
                <span class="font-bold">Print</span>
              </button>
            `}
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
