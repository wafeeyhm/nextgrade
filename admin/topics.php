<?php
// NextGrade - System Admin: Topics CRUD Management
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Topics Management (CRUD) - System Admin | NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/app.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
  <style>
    body, html {
      background-color: #0b0f19 !important;
      background-image: 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, transparent 1.5px), 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, #0b0f19 1.5px) !important;
      color: #f1f5f9 !important;
    }
  </style>
</head>
<body class="admin-dark min-h-screen bg-slate-900 text-slate-100 flex flex-col selection:bg-indigo-500 selection:text-white">

  <!-- Top Navigation Bar -->
  <header class="bg-slate-800/90 backdrop-blur-md border-b border-slate-700/80 sticky top-0 z-30 px-4 md:px-8 py-3.5">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
      
      <!-- Brand & Title -->
      <div class="flex items-center gap-3">
        <a href="index.php" class="flex items-center gap-2.5">
          <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-xl shadow-md shadow-indigo-600/30">
            🛡️
          </div>
          <div>
            <span class="text-lg font-black tracking-tight text-white block leading-none">
              Next<span class="text-indigo-400">Grade</span>
            </span>
            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">System Admin Portal</span>
          </div>
        </a>

        <!-- Desktop Navigation Links -->
        <div class="hidden md:flex items-center gap-1 ml-6 border-l border-slate-700 pl-6">
          <a href="index.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-chart-pie mr-1.5 text-slate-400"></i> Dashboard
          </a>
          <a href="parents.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Parents
          </a>
          <a href="kids.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-graduation-cap mr-1.5 text-slate-400"></i> Students
          </a>
          <a href="topics.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-layer-group mr-1.5 text-indigo-400"></i> Topics (CRUD)
          </a>
          <a href="questions.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-circle-question mr-1.5 text-slate-400"></i> Questions (CRUD)
          </a>
          <a href="guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-book-open mr-1.5 text-slate-400"></i> Guide
          </a>
          <a href="developer_guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-code mr-1.5 text-slate-400"></i> Dev
          </a>
          <a href="verification.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-clipboard-check mr-1.5 text-emerald-400"></i> Verification
          </a>
          <a href="profile.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-user-gear mr-1.5 text-slate-400"></i> Profile
          </a>
        </div>
      </div>

      <!-- Admin Status & Actions -->
      <div class="flex items-center gap-3">
        <a 
          href="../seed.php" 
          title="Curriculum Seeder"
          class="bg-amber-600/20 hover:bg-amber-600/30 text-amber-300 text-xs font-bold py-2 px-3 rounded-xl border border-amber-500/40 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-database text-xs"></i>
          <span class="hidden sm:inline">Seeder</span>
        </a>

        <a 
          href="logout.php" 
          class="bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/40 text-rose-300 text-xs font-bold py-2 px-3 rounded-xl transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <span class="hidden sm:inline">Logout</span>
        </a>
      </div>

    </div>
  </header>

  <!-- Main Container -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8 space-y-6">

    <!-- Top Banner -->
    <div class="bg-gradient-to-r from-slate-800 via-indigo-950/40 to-slate-800 border border-slate-700/80 rounded-3xl p-6 md:p-8 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-black uppercase tracking-wider mb-2">
          <i class="fa-solid fa-layer-group"></i> Curriculum Architecture
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Topics Management (CRUD)</h1>
        <p class="text-sm font-semibold text-slate-400 mt-1 max-w-2xl">
          Create, edit, and organize curriculum topics for Kindergarten 3 (KG3) and Year 6 (PSR Brunei). Directly jump into topic question banks or modify 5-minute revision metadata.
        </p>
      </div>

      <div class="flex items-center gap-3 shrink-0 flex-wrap">
        <a 
          href="questions.php" 
          class="bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2"
        >
          <i class="fa-solid fa-circle-question"></i>
          <span>Questions Bank</span>
        </a>

        <button 
          type="button" 
          onclick="openCreateTopicModal()"
          class="bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2 cursor-pointer"
        >
          <i class="fa-solid fa-plus"></i>
          <span>Add New Topic</span>
        </button>
      </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Topics</span>
        <span id="stat-total" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">Across all curricula</span>
      </div>
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-sky-400 uppercase tracking-wider block">KG3 Topics</span>
        <span id="stat-kg3" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">Early Learning Syllabus</span>
      </div>
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider block">Year 6 PSR Topics</span>
        <span id="stat-psr" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">Brunei PSR Exam</span>
      </div>
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">Total Questions</span>
        <span id="stat-questions" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">In linked question banks</span>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-slate-800/90 border border-slate-700 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex flex-1 items-center gap-3 w-full md:w-auto flex-wrap">
        <!-- Search Input -->
        <div class="relative flex-1 min-w-[200px]">
          <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
          <input 
            type="text" 
            id="search-input" 
            placeholder="Search by topic name, ID, or description..."
            oninput="debounceSearch()"
            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-9 pr-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 font-semibold"
          />
        </div>

        <!-- Grade Filter -->
        <select 
          id="filter-grade" 
          onchange="loadTopics()"
          class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-semibold"
        >
          <option value="">All Grade Levels</option>
          <option value="Kindergarten 3 (KG3)">Kindergarten 3 (KG3)</option>
          <option value="Year 6 (PSR)">Year 6 (PSR Brunei)</option>
        </select>

        <!-- Subject Filter -->
        <select 
          id="filter-subject" 
          onchange="loadTopics()"
          class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-semibold"
        >
          <option value="">All Subjects</option>
        </select>
      </div>

      <div class="flex items-center gap-2">
        <button 
          type="button" 
          onclick="resetFilters()"
          class="text-xs text-slate-400 hover:text-white px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:bg-slate-700/50 transition-colors"
        >
          Reset Filters
        </button>
      </div>
    </div>

    <!-- Topics Table Container -->
    <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl overflow-hidden shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-900/80 border-b border-slate-700 text-slate-400 font-black uppercase text-[10px] tracking-wider">
            <tr>
              <th class="py-3.5 px-4">Topic Details</th>
              <th class="py-3.5 px-4">Subject & Grade</th>
              <th class="py-3.5 px-4 text-center">Questions</th>
              <th class="py-3.5 px-4 text-center">Revision Timer</th>
              <th class="py-3.5 px-4 text-center">Sort</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="topics-tbody" class="divide-y divide-slate-700/60 font-semibold text-slate-300">
            <tr>
              <td colspan="6" class="text-center py-12 text-slate-400">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl text-indigo-400 mb-2 block"></i>
                Loading topics list...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- Modal: Create / Edit Topic -->
  <div id="topic-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 my-8 relative">
      <div class="flex items-center justify-between pb-3 border-b border-slate-800">
        <h2 id="modal-title" class="text-lg font-black text-white flex items-center gap-2">
          <span>📚</span> <span>Add New Topic</span>
        </h2>
        <button type="button" onclick="closeTopicModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">
          <i class="fa-solid fa-xmark text-base"></i>
        </button>
      </div>

      <form id="topic-form" onsubmit="handleSaveTopic(event)" class="space-y-4 text-xs font-semibold">
        <input type="hidden" id="topic-action" value="create" />

        <!-- ID & Subject -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Topic ID (Slug) *</label>
            <input 
              type="text" 
              id="topic-id" 
              placeholder="e.g. bm_suku_kata" 
              required
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white font-mono focus:outline-none focus:border-indigo-500"
            />
            <span class="text-[10px] text-slate-500">Lowercase letters, numbers, underscores</span>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Subject *</label>
            <select 
              id="topic-subject-id" 
              required
              onchange="syncGradeFromSubject()"
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            >
              <option value="">Select Subject</option>
            </select>
          </div>
        </div>

        <!-- Grade Level -->
        <div>
          <label class="block text-[11px] font-bold text-slate-400 mb-1">Grade Level *</label>
          <select 
            id="topic-grade-level" 
            required
            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
          >
            <option value="Kindergarten 3 (KG3)">Kindergarten 3 (KG3)</option>
            <option value="Year 6 (PSR)">Year 6 (PSR Brunei)</option>
          </select>
        </div>

        <!-- Names -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Topic Name *</label>
            <input 
              type="text" 
              id="topic-name" 
              placeholder="e.g. Pecahkan Suku Kata" 
              required
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            />
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Native Name / Subtitle</label>
            <input 
              type="text" 
              id="topic-name-native" 
              placeholder="e.g. Pecahkan Perkataan..." 
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            />
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-[11px] font-bold text-slate-400 mb-1">Description</label>
          <textarea 
            id="topic-description" 
            rows="2"
            placeholder="Short explanation of what the student will learn..."
            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
          ></textarea>
        </div>

        <!-- Icon & Color Badge -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Topic Icon (Emoji)</label>
            <div class="flex items-center gap-2">
              <input 
                type="text" 
                id="topic-icon" 
                value="📚" 
                maxlength="8"
                class="w-16 text-center text-xl bg-slate-950 border border-slate-700 rounded-xl px-2 py-1.5 text-white focus:outline-none focus:border-indigo-500"
              />
              <div class="flex items-center gap-1 text-sm">
                <button type="button" onclick="setTopicIcon('📚')" class="p-1 hover:bg-slate-800 rounded">📚</button>
                <button type="button" onclick="setTopicIcon('🧩')" class="p-1 hover:bg-slate-800 rounded">🧩</button>
                <button type="button" onclick="setTopicIcon('🔢')" class="p-1 hover:bg-slate-800 rounded">🔢</button>
                <button type="button" onclick="setTopicIcon('📐')" class="p-1 hover:bg-slate-800 rounded">📐</button>
                <button type="button" onclick="setTopicIcon('🔬')" class="p-1 hover:bg-slate-800 rounded">🔬</button>
                <button type="button" onclick="setTopicIcon('🕌')" class="p-1 hover:bg-slate-800 rounded">🕌</button>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Color Badge CSS</label>
            <input 
              type="text" 
              id="topic-color-badge" 
              value="bg-indigo-100 text-indigo-800"
              placeholder="e.g. bg-teal-100 text-teal-800"
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white font-mono text-[11px] focus:outline-none focus:border-indigo-500"
            />
          </div>
        </div>

        <!-- Timer & Sort -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Revision Time Limit (Seconds)</label>
            <input 
              type="number" 
              id="topic-time-limit" 
              value="300" 
              step="30"
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            />
            <span class="text-[10px] text-slate-500">300s = 5 minutes</span>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Sort Order</label>
            <input 
              type="number" 
              id="topic-sort-order" 
              value="1" 
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            />
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
          <button 
            type="button" 
            onclick="closeTopicModal()" 
            class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors font-bold text-xs"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="modal-submit-btn"
            class="bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs py-2 px-5 rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5"
          >
            <span>Save Topic</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Delete Confirmation -->
  <div id="delete-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
      <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center text-xl mx-auto">
        <i class="fa-solid fa-trash-can"></i>
      </div>
      <div>
        <h3 class="text-base font-black text-white">Delete Topic?</h3>
        <p id="delete-msg" class="text-xs text-slate-400 mt-1">
          Are you sure you want to delete this topic?
        </p>
        <p class="text-[11px] text-rose-400 font-bold mt-2 bg-rose-950/40 p-2 rounded-xl border border-rose-800/40">
          ⚠️ Warning: All questions and revision guides linked to this topic will also be permanently deleted.
        </p>
      </div>

      <div class="flex items-center justify-center gap-3 pt-2">
        <button 
          type="button" 
          onclick="closeDeleteModal()" 
          class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors font-bold text-xs"
        >
          Cancel
        </button>
        <button 
          type="button" 
          id="confirm-delete-btn"
          class="bg-rose-600 hover:bg-rose-500 text-white font-black text-xs py-2 px-4 rounded-xl shadow-lg shadow-rose-600/30 transition-all"
        >
          Yes, Delete Topic
        </button>
      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div id="toast" class="fixed bottom-6 right-6 z-50 hidden bg-slate-800 border border-slate-700 text-white px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold transition-all">
    <span id="toast-icon">✅</span>
    <span id="toast-msg">Operation completed</span>
  </div>

  <!-- Footer -->
  <footer class="mt-auto border-t border-slate-800 py-6 text-center text-xs font-bold text-slate-500">
    NextGrade Educational Operating System • System Admin Topics Module
  </footer>

  <script>
    let allSubjects = [];
    let searchTimeout = null;

    function showToast(msg, isSuccess = true) {
      const toast = document.getElementById('toast');
      document.getElementById('toast-icon').textContent = isSuccess ? '✅' : '❌';
      document.getElementById('toast-msg').textContent = msg;
      toast.className = `fixed bottom-6 right-6 z-50 bg-slate-800 border ${isSuccess ? 'border-emerald-500/50 text-emerald-200' : 'border-rose-500/50 text-rose-200'} px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold transition-all animate-bounce`;
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 3500);
    }

    function setTopicIcon(emoji) {
      document.getElementById('topic-icon').value = emoji;
    }

    function debounceSearch() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(loadTopics, 300);
    }

    function resetFilters() {
      document.getElementById('search-input').value = '';
      document.getElementById('filter-grade').value = '';
      document.getElementById('filter-subject').value = '';
      loadTopics();
    }

    async function loadSubjects() {
      try {
        const resp = await fetch('../api/admin_topics.php?action=subjects');
        const data = await resp.json();
        if (data.success) {
          allSubjects = data.subjects;
          const filterSel = document.getElementById('filter-subject');
          const modalSel = document.getElementById('topic-subject-id');

          filterSel.innerHTML = '<option value="">All Subjects</option>' + 
            allSubjects.map(s => `<option value="${s.id}">${s.icon} ${s.name} (${s.grade_level})</option>`).join('');

          modalSel.innerHTML = '<option value="">Select Subject</option>' + 
            allSubjects.map(s => `<option value="${s.id}" data-grade="${s.grade_level}">${s.icon} ${s.name} (${s.grade_level})</option>`).join('');
        }
      } catch (err) {
        console.error(err);
      }
    }

    function syncGradeFromSubject() {
      const modalSel = document.getElementById('topic-subject-id');
      const selectedOpt = modalSel.options[modalSel.selectedIndex];
      if (selectedOpt && selectedOpt.dataset.grade) {
        document.getElementById('topic-grade-level').value = selectedOpt.dataset.grade;
      }
    }

    async function loadTopics() {
      const search = document.getElementById('search-input').value.trim();
      const grade = document.getElementById('filter-grade').value;
      const subject = document.getElementById('filter-subject').value;

      const params = new URLSearchParams({
        action: 'list',
        search: search,
        grade_level: grade,
        subject_id: subject
      });

      try {
        const resp = await fetch(`../api/admin_topics.php?${params.toString()}`);
        const data = await resp.json();

        if (!data.success) {
          showToast(data.error || 'Failed to load topics', false);
          return;
        }

        renderTopicsTable(data.topics);
        updateStats(data.topics);
      } catch (err) {
        console.error(err);
        showToast('Error loading topics', false);
      }
    }

    function updateStats(topics) {
      document.getElementById('stat-total').textContent = topics.length;
      const kg3 = topics.filter(t => t.grade_level === 'Kindergarten 3 (KG3)').length;
      const psr = topics.filter(t => t.grade_level === 'Year 6 (PSR)').length;
      const qTotal = topics.reduce((acc, t) => acc + parseInt(t.question_count || 0), 0);

      document.getElementById('stat-kg3').textContent = kg3;
      document.getElementById('stat-psr').textContent = psr;
      document.getElementById('stat-questions').textContent = qTotal.toLocaleString();
    }

    function renderTopicsTable(topics) {
      const tbody = document.getElementById('topics-tbody');

      if (!topics || topics.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="6" class="text-center py-10 text-slate-500 font-bold">
              No topics matched your search or filters.
            </td>
          </tr>
        `;
        return;
      }

      tbody.innerHTML = topics.map(t => {
        const isKG3 = (t.grade_level === 'Kindergarten 3 (KG3)');
        const gradeBadge = isKG3 
          ? `<span class="bg-sky-500/20 text-sky-300 border border-sky-500/30 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider">KG3 ⭐</span>`
          : `<span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider">Year 6 PSR 🇧🇳</span>`;

        return `
          <tr class="hover:bg-slate-800/50 transition-colors group">
            <td class="py-3 px-4">
              <div class="flex items-center gap-3">
                <span class="text-2xl p-2 rounded-xl bg-slate-900 border border-slate-700/60 shrink-0">
                  ${t.icon || '📚'}
                </span>
                <div>
                  <div class="font-black text-white text-sm flex items-center gap-2">
                    <span>${t.name}</span>
                    <span class="text-[10px] font-mono text-slate-500">(${t.id})</span>
                  </div>
                  <div class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">
                    ${t.description || t.name_native || 'No description'}
                  </div>
                </div>
              </div>
            </td>

            <td class="py-3 px-4">
              <div class="space-y-1">
                <span class="text-slate-200 font-bold block text-xs">${t.subject_name || t.subject_id}</span>
                ${gradeBadge}
              </div>
            </td>

            <td class="py-3 px-4 text-center">
              <a 
                href="questions.php?topic_id=${encodeURIComponent(t.id)}" 
                title="View and manage questions in this topic"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl font-black text-xs ${parseInt(t.question_count) > 0 ? 'bg-indigo-500/20 text-indigo-300 hover:bg-indigo-500/30 border border-indigo-500/40' : 'bg-slate-800 text-slate-500 border border-slate-700'} transition-all"
              >
                <span>${t.question_count || 0}</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
              </a>
            </td>

            <td class="py-3 px-4 text-center">
              <span class="font-mono text-xs text-slate-300 bg-slate-900 px-2 py-1 rounded-lg border border-slate-800">
                ${Math.round(t.revision_time_limit / 60)}m (${t.revision_time_limit}s)
              </span>
            </td>

            <td class="py-3 px-4 text-center font-mono text-slate-400">
              #${t.sort_order}
            </td>

            <td class="py-3 px-4 text-right">
              <div class="flex items-center justify-end gap-1.5">
                <a 
                  href="questions.php?topic_id=${encodeURIComponent(t.id)}"
                  title="Questions" 
                  class="p-2 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-indigo-300 hover:text-white transition-colors"
                >
                  <i class="fa-solid fa-circle-question"></i>
                </a>
                <button 
                  type="button" 
                  onclick="openEditTopicModal('${t.id}')"
                  title="Edit Topic" 
                  class="p-2 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-amber-300 hover:text-white transition-colors cursor-pointer"
                >
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button 
                  type="button" 
                  onclick="confirmDeleteTopic('${t.id}', '${encodeURIComponent(t.name)}', ${t.question_count || 0})"
                  title="Delete Topic" 
                  class="p-2 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 transition-colors cursor-pointer"
                >
                  <i class="fa-solid fa-trash-can"></i>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    function openCreateTopicModal() {
      document.getElementById('modal-title').innerHTML = '<span>📚</span> <span>Add New Topic</span>';
      document.getElementById('topic-action').value = 'create';
      document.getElementById('topic-id').value = '';
      document.getElementById('topic-id').disabled = false;
      document.getElementById('topic-subject-id').value = '';
      document.getElementById('topic-grade-level').value = 'Kindergarten 3 (KG3)';
      document.getElementById('topic-name').value = '';
      document.getElementById('topic-name-native').value = '';
      document.getElementById('topic-description').value = '';
      document.getElementById('topic-icon').value = '📚';
      document.getElementById('topic-color-badge').value = 'bg-indigo-100 text-indigo-800';
      document.getElementById('topic-time-limit').value = '300';
      document.getElementById('topic-sort-order').value = '1';
      document.getElementById('topic-modal').classList.remove('hidden');
    }

    async function openEditTopicModal(topicId) {
      try {
        const resp = await fetch(`../api/admin_topics.php?action=single&id=${encodeURIComponent(topicId)}`);
        const data = await resp.json();

        if (!data.success || !data.topic) {
          showToast(data.error || 'Failed to fetch topic details', false);
          return;
        }

        const t = data.topic;
        document.getElementById('modal-title').innerHTML = `<span>✏️</span> <span>Edit Topic: ${t.name}</span>`;
        document.getElementById('topic-action').value = 'update';
        document.getElementById('topic-id').value = t.id;
        document.getElementById('topic-id').disabled = true; // Primary key locked on edit
        document.getElementById('topic-subject-id').value = t.subject_id;
        document.getElementById('topic-grade-level').value = t.grade_level;
        document.getElementById('topic-name').value = t.name;
        document.getElementById('topic-name-native').value = t.name_native || '';
        document.getElementById('topic-description').value = t.description || '';
        document.getElementById('topic-icon').value = t.icon || '📚';
        document.getElementById('topic-color-badge').value = t.color_badge || 'bg-indigo-100 text-indigo-800';
        document.getElementById('topic-time-limit').value = t.revision_time_limit || 300;
        document.getElementById('topic-sort-order').value = t.sort_order || 0;

        document.getElementById('topic-modal').classList.remove('hidden');
      } catch (err) {
        console.error(err);
        showToast('Error loading topic details', false);
      }
    }

    function closeTopicModal() {
      document.getElementById('topic-modal').classList.add('hidden');
    }

    async function handleSaveTopic(e) {
      e.preventDefault();
      const action = document.getElementById('topic-action').value;
      const submitBtn = document.getElementById('modal-submit-btn');
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

      const payload = {
        action: action,
        id: document.getElementById('topic-id').value.trim(),
        subject_id: document.getElementById('topic-subject-id').value,
        grade_level: document.getElementById('topic-grade-level').value,
        name: document.getElementById('topic-name').value.trim(),
        name_native: document.getElementById('topic-name-native').value.trim(),
        description: document.getElementById('topic-description').value.trim(),
        icon: document.getElementById('topic-icon').value.trim(),
        color_badge: document.getElementById('topic-color-badge').value.trim(),
        revision_time_limit: parseInt(document.getElementById('topic-time-limit').value) || 300,
        sort_order: parseInt(document.getElementById('topic-sort-order').value) || 0
      };

      try {
        const resp = await fetch('../api/admin_topics.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await resp.json();

        if (data.success) {
          showToast(data.message || 'Saved successfully');
          closeTopicModal();
          loadTopics();
        } else {
          showToast(data.error || 'Failed to save topic', false);
        }
      } catch (err) {
        showToast('Network error while saving', false);
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>Save Topic</span>';
      }
    }

    function confirmDeleteTopic(id, encodedName, qCount) {
      const name = decodeURIComponent(encodedName);
      document.getElementById('delete-msg').innerHTML = `Are you sure you want to delete topic <strong class="text-white">"${name}"</strong> (${id})?`;
      const btn = document.getElementById('confirm-delete-btn');
      btn.onclick = () => executeDeleteTopic(id);
      document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
    }

    async function executeDeleteTopic(id) {
      try {
        const resp = await fetch('../api/admin_topics.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', id: id })
        });
        const data = await resp.json();

        if (data.success) {
          showToast(data.message || 'Topic deleted');
          closeDeleteModal();
          loadTopics();
        } else {
          showToast(data.error || 'Failed to delete topic', false);
        }
      } catch (err) {
        showToast('Network error while deleting', false);
      }
    }

    document.addEventListener('DOMContentLoaded', async () => {
      await loadSubjects();
      await loadTopics();
    });
  </script>

</body>
</html>
