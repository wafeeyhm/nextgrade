<?php
// NextGrade - Parent Portal: Progress, Status, Scores & Insights
require_once __DIR__ . '/db.php';
session_start();

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Parent Portal - NextGrade Insights</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <script src="js/sounds.js"></script>
</head>
<body class="min-h-screen bg-slate-50 p-4 md:p-8 text-slate-800">

<div class="max-w-6xl w-full mx-auto flex flex-col gap-6">

  <!-- Header Bar -->
  <header class="bg-white px-6 py-4 rounded-3xl shadow-sm border-2 border-slate-200 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <a 
        href="index.php" 
        onclick="SoundEffects.playPop();"
        class="btn-chunky btn-white text-xs md:text-sm py-2 px-3.5 rounded-xl flex items-center gap-1.5"
      >
        <span>⬅️</span>
        <span class="font-black">Home</span>
      </a>
      <div>
        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
          <span>👨‍👩‍👧</span> Parent Portal
        </h1>
        <p class="text-xs font-bold text-slate-400">Child Learning Progress, Performance & Analytics</p>
      </div>
    </div>

    <!-- Student Filter Dropdown -->
    <div class="flex items-center gap-2">
      <label for="filter-student-select" class="text-xs font-extrabold text-slate-500 uppercase">Select Student:</label>
      <select 
        id="filter-student-select" 
        onchange="onStudentFilterChange(this.value)"
        class="bg-slate-100 border-2 border-slate-200 text-slate-800 font-extrabold text-sm rounded-xl px-3 py-2 focus:border-sky-400 focus:outline-none"
      >
        <option value="">All Students</option>
      </select>
    </div>
  </header>

  <!-- 1. Top Key Statistics Cards -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center gap-4">
      <div class="text-4xl p-3 bg-sky-50 rounded-2xl">📝</div>
      <div>
        <span class="block text-xs font-black uppercase text-slate-400">Total Sessions</span>
        <span id="stat-total-sessions" class="text-2xl md:text-3xl font-black text-sky-600">0</span>
      </div>
    </div>

    <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center gap-4">
      <div class="text-4xl p-3 bg-emerald-50 rounded-2xl">🎯</div>
      <div>
        <span class="block text-xs font-black uppercase text-slate-400">Average Accuracy</span>
        <span id="stat-overall-accuracy" class="text-2xl md:text-3xl font-black text-emerald-600">0%</span>
      </div>
    </div>

    <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center gap-4">
      <div class="text-4xl p-3 bg-amber-50 rounded-2xl">⭐</div>
      <div>
        <span class="block text-xs font-black uppercase text-slate-400">Questions Answered</span>
        <span id="stat-total-questions" class="text-2xl md:text-3xl font-black text-amber-600">0</span>
      </div>
    </div>

    <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center gap-4">
      <div class="text-4xl p-3 bg-purple-50 rounded-2xl">⏱️</div>
      <div>
        <span class="block text-xs font-black uppercase text-slate-400">Learning Time</span>
        <span id="stat-total-time" class="text-2xl md:text-3xl font-black text-purple-600">0m</span>
      </div>
    </div>
  </div>

  <!-- 2. Subject Performance Breakdown -->
  <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl md:text-2xl font-black text-slate-800 flex items-center gap-2">
        <span>📊</span> Subject Performance Breakdown
      </h2>
      <span class="text-xs font-bold text-slate-400">Stored in MySQL Database</span>
    </div>

    <div id="subject-breakdown-list" class="space-y-4">
      <!-- Subject progress items rendered via API -->
      <div class="text-center py-6 text-slate-400 font-bold">Loading subject breakdown...</div>
    </div>
  </div>

  <!-- 3. Smart Insights & Recommendations Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Strengths -->
    <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-emerald-200 shadow-sm">
      <h3 class="text-xl font-black text-emerald-800 mb-4 flex items-center gap-2">
        <span>🌟</span> Strengths & High Mastery (≥ 70%)
      </h3>
      <div id="strengths-list" class="space-y-3">
        <p class="text-sm font-bold text-slate-400">No evaluation records available yet.</p>
      </div>
    </div>

    <!-- Topics Needing Practice -->
    <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-amber-200 shadow-sm">
      <h3 class="text-xl font-black text-amber-800 mb-4 flex items-center gap-2">
        <span>🎯</span> Recommended Practice & Focus Areas (&lt; 70%)
      </h3>
      <div id="needs-practice-list" class="space-y-3">
        <p class="text-sm font-bold text-slate-400">All topics are performing well!</p>
      </div>
    </div>

  </div>

  <!-- 4. Historical Quiz Sessions Log Table -->
  <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl md:text-2xl font-black text-slate-800 flex items-center gap-2">
        <span>📜</span> Recent Quiz Sessions Log
      </h2>
      <span class="text-xs font-bold text-slate-400">Last 20 Sessions</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm font-bold">
        <thead>
          <tr class="border-b-2 border-slate-200 text-slate-400 uppercase text-xs">
            <th class="py-3 px-3">Date & Time</th>
            <th class="py-3 px-3">Student</th>
            <th class="py-3 px-3">Subject</th>
            <th class="py-3 px-3">Topic</th>
            <th class="py-3 px-3 text-center">Score (10)</th>
            <th class="py-3 px-3 text-center">Percentage</th>
            <th class="py-3 px-3 text-right">Time Taken</th>
          </tr>
        </thead>
        <tbody id="sessions-table-body" class="divide-y divide-slate-100 text-slate-700">
          <tr>
            <td colspan="7" class="text-center py-8 text-slate-400">No quiz sessions recorded yet.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
  let activeStudentFilter = "<?= htmlspecialchars($studentName) ?>";

  async function loadParentData(student = '') {
    try {
      let url = 'api/parent.php';
      if (student) {
        url += `?student_name=${encodeURIComponent(student)}`;
      }

      const resp = await fetch(url);
      const data = await resp.json();

      if (!data.success) return;

      // 1. Populate Filter Dropdown
      const select = document.getElementById('filter-student-select');
      const curVal = student || select.value;
      select.innerHTML = '<option value="">All Students</option>' + 
        data.students_list.map(s => `<option value="${s}" ${s === curVal ? 'selected' : ''}>${s}</option>`).join('');

      // 2. Summary stats
      const sum = data.summary;
      document.getElementById('stat-total-sessions').textContent = sum.total_sessions;
      document.getElementById('stat-overall-accuracy').textContent = `${sum.overall_accuracy}%`;
      document.getElementById('stat-total-questions').textContent = sum.total_questions;
      document.getElementById('stat-total-time').textContent = sum.formatted_time;

      // 3. Subject Progress Breakdown
      const subContainer = document.getElementById('subject-breakdown-list');
      subContainer.innerHTML = data.subject_stats.map(s => `
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <div class="flex items-center gap-2">
              <span class="text-2xl">${s.icon}</span>
              <span class="font-black text-slate-800">${s.name}</span>
            </div>
            <div class="text-xs font-black text-slate-500">
              <span class="text-sky-600 font-extrabold text-sm">${s.accuracy}%</span> 
              (${s.correct_answers} / ${s.questions_attempted} questions)
            </div>
          </div>
          <div class="w-full bg-slate-100 h-3.5 rounded-full overflow-hidden border border-slate-200">
            <div class="h-full rounded-full transition-all duration-500" style="width: ${s.accuracy}%; background-color: ${s.color};"></div>
          </div>
        </div>
      `).join('');

      // 4. Strengths
      const strList = document.getElementById('strengths-list');
      if (data.insights.strengths && data.insights.strengths.length > 0) {
        strList.innerHTML = data.insights.strengths.map(st => `
          <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-sm">
            <div>
              <span class="font-black text-emerald-900">${st.topic_name}</span>
              <span class="block text-xs text-emerald-700">${st.subject_name}</span>
            </div>
            <span class="bg-emerald-600 text-white text-xs font-black px-2.5 py-1 rounded-lg">
              ${Math.round(st.avg_score)}% Score
            </span>
          </div>
        `).join('');
      } else {
        strList.innerHTML = '<p class="text-sm font-bold text-slate-400 py-3">No topics above 70% yet. Encourage regular practice!</p>';
      }

      // 5. Needs Practice
      const npList = document.getElementById('needs-practice-list');
      if (data.insights.needs_practice && data.insights.needs_practice.length > 0) {
        npList.innerHTML = data.insights.needs_practice.map(np => `
          <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-between text-sm">
            <div>
              <span class="font-black text-amber-900">${np.topic_name}</span>
              <span class="block text-xs text-amber-700">${np.subject_name}</span>
            </div>
            <a href="revision.php?topic=${np.topic_id}" class="btn-chunky btn-amber text-xs py-1.5 px-3 rounded-lg font-bold">
              Revision ➔
            </a>
          </div>
        `).join('');
      } else {
        npList.innerHTML = '<p class="text-sm font-bold text-slate-400 py-3">Great job! All attempted topics demonstrate high mastery.</p>';
      }

      // 6. Recent Sessions Table
      const tbBody = document.getElementById('sessions-table-body');
      if (data.recent_sessions && data.recent_sessions.length > 0) {
        tbBody.innerHTML = data.recent_sessions.map(sess => {
          const mins = Math.floor(sess.time_spent_seconds / 60);
          const secs = sess.time_spent_seconds % 60;
          const timeFormatted = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
          
          return `
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="py-3 px-3 text-xs text-slate-500 font-mono">${sess.completed_at}</td>
              <td class="py-3 px-3 font-black text-slate-800">${sess.student_name}</td>
              <td class="py-3 px-3 text-slate-700">${sess.subject_icon} ${sess.subject_name}</td>
              <td class="py-3 px-3 text-slate-600 text-xs">${sess.topic_name}</td>
              <td class="py-3 px-3 text-center font-black text-sky-600">${sess.score} / ${sess.total_questions}</td>
              <td class="py-3 px-3 text-center">
                <span class="px-2 py-0.5 rounded-full text-xs font-black ${sess.percentage >= 80 ? 'bg-emerald-100 text-emerald-800' : (sess.percentage >= 60 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')}">
                  ${sess.percentage}%
                </span>
              </td>
              <td class="py-3 px-3 text-right font-mono text-slate-500 text-xs">${timeFormatted}</td>
            </tr>
          `;
        }).join('');
      } else {
        tbBody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-slate-400 font-bold">No quiz sessions recorded yet.</td></tr>';
      }

    } catch (err) {
      console.error(err);
    }
  }

  function onStudentFilterChange(val) {
    SoundEffects.playPop();
    loadParentData(val);
  }

  document.addEventListener('DOMContentLoaded', () => {
    loadParentData(activeStudentFilter);
  });
</script>

</body>
</html>
