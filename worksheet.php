<?php
// NextGrade - Printable Educational Worksheets for Handwriting & Writing Skills
require_once __DIR__ . '/db.php';
session_start();

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? '';
$topicId = $_GET['topic'] ?? $_GET['topic_id'] ?? 'bm_suku_kata';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Handwriting Worksheet - NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <style>
    @media print {
      .no-print { display: none !important; }
      body { background: white !important; font-size: 14pt; }
      .print-border { border: 2px solid #000 !important; }
      .print-divider { border-color: #000 !important; }
    }
  </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 text-slate-900">

<!-- Non-Printable Action Bar -->
<div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
  <a 
    href="javascript:history.back()" 
    class="btn-chunky btn-white py-2 px-4 rounded-xl text-sm font-bold flex items-center gap-1.5"
  >
    <span>⬅️</span>
    <span>Back</span>
  </a>

  <div class="text-center">
    <span class="font-extrabold text-slate-700 text-sm md:text-base">Handwriting & Writing Practice Worksheet</span>
    <span class="block text-xs text-slate-400">Optimized for A4 paper printing</span>
  </div>

  <button 
    onclick="window.print()" 
    class="btn-chunky btn-primary py-2.5 px-6 rounded-xl text-base font-black shadow-md flex items-center gap-2"
  >
    <span>🖨️</span>
    <span>Print Now</span>
  </button>
</div>

<!-- A4 Printable Sheet Container -->
<div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-3xl shadow-lg border-2 border-slate-300 print-page print-border">

  <!-- Worksheet Header -->
  <div class="border-b-4 border-slate-800 pb-4 mb-6 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-4xl">🌟</span>
      <div>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">
          Next<span class="text-sky-600">Grade</span> Worksheet
        </h1>
        <p id="ws-subtitle" class="text-sm font-extrabold text-slate-500">
          Handwriting Practice & Writing Skills
        </p>
      </div>
    </div>

    <!-- Marks Box -->
    <div class="border-2 border-slate-800 px-4 py-2 rounded-xl text-center min-w-[120px]">
      <span class="block text-[10px] font-black uppercase text-slate-500">Score</span>
      <span class="text-2xl font-black text-slate-900">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / 10</span>
    </div>
  </div>

  <!-- Student Name & Date Lines (Requirement 1.12 Writing Skills) -->
  <div class="bg-slate-50 p-4 rounded-2xl border border-slate-300 mb-8 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm font-bold">
    <div class="flex items-center gap-2">
      <span class="text-slate-600">Student Name:</span>
      <span class="border-b-2 border-slate-800 flex-1 pb-1 font-black text-slate-800 text-base">
        <?= htmlspecialchars($studentName) ?>
      </span>
    </div>
    <div class="flex items-center gap-2">
      <span class="text-slate-600">Date:</span>
      <span class="border-b-2 border-slate-800 flex-1 pb-1 font-mono text-slate-800">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </span>
    </div>
  </div>

  <!-- Topic Title Banner -->
  <div class="mb-6">
    <h2 id="ws-topic-title" class="text-2xl font-black text-slate-800 flex items-center gap-2">
      <span>📝</span> Topic: Loading...
    </h2>
    <p class="text-sm text-slate-600 font-bold mt-1">
      Instructions: Read each question carefully. Write your answer neatly on the handwriting guidelines provided.
    </p>
  </div>

  <!-- Printable Questions List with Handwriting Lines -->
  <div id="ws-questions-container" class="space-y-6">
    <div class="text-center py-10 text-slate-400 font-bold">
      Loading worksheet...
    </div>
  </div>

  <!-- Teacher/Parent Remarks Footer -->
  <div class="mt-12 pt-6 border-t-2 border-dashed border-slate-300 flex items-center justify-between text-xs text-slate-500 font-bold">
    <span>Teacher / Parent Signature: ________________________</span>
    <span>Date Checked: ______________</span>
  </div>

</div>

<script>
  const topicId = "<?= htmlspecialchars($topicId) ?>";

  async function loadWorksheet() {
    try {
      const resp = await fetch(`api/worksheet.php?topic_id=${encodeURIComponent(topicId)}`);
      const data = await resp.json();

      if (!data.success || !data.topic) {
        document.getElementById('ws-topic-title').textContent = 'Topic not found.';
        return;
      }

      const t = data.topic;
      document.getElementById('ws-topic-title').innerHTML = `<span>${t.icon || '📝'}</span> Topic: ${t.name}`;
      document.getElementById('ws-subtitle').textContent = `${t.subject_name} • Writing Practice`;

      const container = document.getElementById('ws-questions-container');

      if (!data.questions || data.questions.length === 0) {
        container.innerHTML = '<p class="text-slate-500">No questions available for this worksheet.</p>';
        return;
      }

      container.innerHTML = data.questions.map((q, idx) => `
        <div class="p-4 rounded-2xl border-2 border-slate-200 bg-white break-inside-avoid">
          <div class="flex items-start justify-between gap-3 mb-2">
            <h3 class="text-base md:text-lg font-black text-slate-800 flex items-center gap-2">
              <span class="w-7 h-7 rounded-full bg-slate-800 text-white text-xs flex items-center justify-center shrink-0">
                ${idx + 1}
              </span>
              <span>${q.question_text}</span>
            </h3>
          </div>

          ${q.passage ? `
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-300 text-sm font-bold text-slate-700 my-2">
              <strong>Passage:</strong> "${q.passage}"
            </div>
          ` : ''}

          <!-- Options Guide Box -->
          ${q.options && q.options.length > 0 ? `
            <div class="my-2 p-2 bg-slate-50 border border-slate-200 rounded-xl flex flex-wrap gap-2 text-xs font-bold text-slate-600">
              <span class="text-slate-400">Options:</span>
              ${q.options.map(opt => `<span class="bg-white px-2.5 py-1 rounded-md border border-slate-300 font-extrabold">[ ${opt} ]</span>`).join(' ')}
            </div>
          ` : ''}

          <!-- Primary School 3-Line Handwriting Guide for Writing Practice -->
          <div class="mt-4">
            <span class="text-xs font-bold text-slate-400 block mb-1">Write your answer neatly:</span>
            <div class="ruling-line">
              <span class="handwriting-text opacity-25"></span>
            </div>
          </div>
        </div>
      `).join('');

    } catch (err) {
      console.error(err);
    }
  }

  document.addEventListener('DOMContentLoaded', loadWorksheet);
</script>

</body>
</html>
