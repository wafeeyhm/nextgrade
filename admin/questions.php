<?php
// NextGrade - System Admin: Questions Bank Management (CRUD)
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();
$initialTopicFilter = $_GET['topic_id'] ?? '';
$initialSubjectFilter = $_GET['subject_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Questions Bank (CRUD) - System Admin | NextGrade</title>
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
          <a href="topics.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-layer-group mr-1.5 text-slate-400"></i> Topics (CRUD)
          </a>
          <a href="questions.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-circle-question mr-1.5 text-indigo-400"></i> Questions (CRUD)
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

  <!-- Main Content -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8 space-y-6">

    <!-- Top Banner -->
    <div class="bg-gradient-to-r from-slate-800 via-indigo-950/40 to-slate-800 border border-slate-700/80 rounded-3xl p-6 md:p-8 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-black uppercase tracking-wider mb-2">
          <i class="fa-solid fa-circle-question"></i> Comprehensive Curriculum Bank
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Questions Bank Management (CRUD)</h1>
        <p class="text-sm font-semibold text-slate-400 mt-1 max-w-2xl">
          Search, create, update, duplicate, and delete questions across Kindergarten 3 (KG3) and Year 6 (PSR Brunei). Full support for multiple choice, comprehension, analog clocks, and audio hints.
        </p>
      </div>

      <div class="flex items-center gap-3 shrink-0 flex-wrap">
        <a 
          href="topics.php" 
          class="bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2"
        >
          <i class="fa-solid fa-layer-group"></i>
          <span>Topics List</span>
        </a>

        <button 
          type="button" 
          onclick="openCreateQuestionModal()"
          class="bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2 cursor-pointer"
        >
          <i class="fa-solid fa-plus"></i>
          <span>Add New Question</span>
        </button>
      </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Questions</span>
        <span id="stat-total" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">All grade levels</span>
      </div>
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-sky-400 uppercase tracking-wider block">KG3 Questions</span>
        <span id="stat-kg3" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">Early Learning Syllabus</span>
      </div>
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider block">Year 6 PSR Questions</span>
        <span id="stat-psr" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">Brunei PSR Exam</span>
      </div>
      <div class="bg-slate-800/80 border border-slate-700/80 p-4 rounded-2xl">
        <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block">Filtered Match</span>
        <span id="stat-filtered" class="text-2xl font-black text-white">--</span>
        <span class="text-[10px] text-slate-500 block mt-0.5">Current view results</span>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-slate-800/90 border border-slate-700 rounded-2xl p-4 space-y-3">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
        
        <!-- Search Input -->
        <div class="relative lg:col-span-2">
          <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
          <input 
            type="text" 
            id="search-input" 
            placeholder="Search prompt, answer, hint..."
            oninput="debounceSearch()"
            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-9 pr-4 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 font-semibold"
          />
        </div>

        <!-- Grade Filter -->
        <select 
          id="filter-grade" 
          onchange="onGradeFilterChange()"
          class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-semibold"
        >
          <option value="">All Grade Levels</option>
          <option value="Kindergarten 3 (KG3)">Kindergarten 3 (KG3)</option>
          <option value="Year 6 (PSR)">Year 6 (PSR Brunei)</option>
        </select>

        <!-- Subject Filter -->
        <select 
          id="filter-subject" 
          onchange="onSubjectFilterChange()"
          class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-semibold"
        >
          <option value="">All Subjects</option>
        </select>

        <!-- Topic Filter (Cascaded) -->
        <select 
          id="filter-topic" 
          onchange="loadQuestions(1)"
          class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-semibold"
        >
          <option value="">All Topics</option>
        </select>

        <!-- Question Type Filter -->
        <select 
          id="filter-type" 
          onchange="loadQuestions(1)"
          class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-semibold"
        >
          <option value="">All Question Types</option>
          <option value="multiple_choice">Multiple Choice</option>
          <option value="ordering">Ordering Sequence</option>
          <option value="clock_analog">Clock / Time</option>
          <option value="comprehension">Reading Comprehension</option>
          <option value="syllable_split">Syllable Split</option>
          <option value="fill_blank">Fill in the Blank</option>
        </select>

        <!-- Status Filter (Enable / Disable) -->
        <select 
          id="filter-status" 
          onchange="loadQuestions(1)"
          class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-indigo-500 font-semibold"
        >
          <option value="">All Statuses</option>
          <option value="active">Active (Enabled Only)</option>
          <option value="inactive">Disabled Only</option>
        </select>

      </div>

      <div class="flex items-center justify-between pt-2 border-t border-slate-700/60 text-xs">
        <div class="flex items-center gap-2 text-slate-400">
          <span>Show:</span>
          <select 
            id="filter-limit" 
            onchange="loadQuestions(1)"
            class="bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-slate-200 focus:outline-none"
          >
            <option value="15">15 per page</option>
            <option value="25" selected>25 per page</option>
            <option value="50">50 per page</option>
          </select>

          <!-- Has Image filter -->
          <span class="ml-4">Media:</span>
          <select 
            id="filter-image" 
            onchange="loadQuestions(1)"
            class="bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-xs text-slate-200 focus:outline-none"
          >
            <option value="">All Questions</option>
            <option value="yes">With Illustration Image</option>
            <option value="no">Text Only</option>
          </select>
        </div>

        <button 
          type="button" 
          onclick="resetFilters()"
          class="text-xs text-slate-400 hover:text-white px-3 py-1 rounded-xl bg-slate-900 border border-slate-700 hover:bg-slate-700/50 transition-colors"
        >
          Reset Filters
        </button>
      </div>
    </div>

    <!-- Questions Table Container -->
    <div class="bg-slate-800/80 border border-slate-700/80 rounded-3xl overflow-hidden shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-900/80 border-b border-slate-700 text-slate-400 font-black uppercase text-[10px] tracking-wider">
            <tr>
              <th class="py-3.5 px-4 w-16">ID</th>
              <th class="py-3.5 px-4 min-w-[240px]">Question Prompt</th>
              <th class="py-3.5 px-4">Subject & Topic</th>
              <th class="py-3.5 px-4 text-center">Status</th>
              <th class="py-3.5 px-4">Type</th>
              <th class="py-3.5 px-4 min-w-[180px]">Options & Answer</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="questions-tbody" class="divide-y divide-slate-700/60 font-semibold text-slate-300">
            <tr>
              <td colspan="7" class="text-center py-12 text-slate-400">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl text-indigo-400 mb-2 block"></i>
                Loading questions bank...
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="bg-slate-900/90 border-t border-slate-700/80 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div id="pagination-info" class="text-xs font-bold text-slate-400">
          Showing 0 to 0 of 0 questions
        </div>
        <div id="pagination-controls" class="flex items-center gap-1.5">
          <!-- Rendered dynamically -->
        </div>
      </div>
    </div>

  </main>

  <!-- Modal: Create / Edit Question -->
  <div id="question-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4 my-8 relative">
      <div class="flex items-center justify-between pb-3 border-b border-slate-800">
        <h2 id="modal-title" class="text-lg font-black text-white flex items-center gap-2">
          <span>❓</span> <span>Add New Question</span>
        </h2>
        <button type="button" onclick="closeQuestionModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">
          <i class="fa-solid fa-xmark text-base"></i>
        </button>
      </div>

      <form id="question-form" onsubmit="handleSaveQuestion(event)" class="space-y-4 text-xs font-semibold">
        <input type="hidden" id="q-action" value="create" />
        <input type="hidden" id="q-id" value="0" />

        <!-- Row 1: Grade, Subject, Topic, Status -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Grade Level *</label>
            <select 
              id="q-grade-level" 
              required
              onchange="onModalGradeChange()"
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            >
              <option value="Kindergarten 3 (KG3)">Kindergarten 3 (KG3)</option>
              <option value="Year 6 (PSR)">Year 6 (PSR Brunei)</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Subject *</label>
            <select 
              id="q-subject-id" 
              required
              onchange="onModalSubjectChange()"
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            >
              <option value="">Select Subject</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Topic *</label>
            <select 
              id="q-topic-id" 
              required
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            >
              <option value="">Select Topic</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Status *</label>
            <select 
              id="q-status" 
              required
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500"
            >
              <option value="active">Active (Enabled)</option>
              <option value="inactive">Disabled</option>
            </select>
          </div>
        </div>

        <!-- Row 2: Prompt & Audio -->
        <div>
          <label class="block text-[11px] font-bold text-slate-400 mb-1">Question Prompt (Text) *</label>
          <textarea 
            id="q-text" 
            rows="2"
            placeholder="Type question prompt here..."
            required
            oninput="autoFillAudioPrompt()"
            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-xs"
          ></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-[11px] font-bold text-slate-400 mb-1">TTS Audio Prompt (Voice narration)</label>
            <input 
              type="text" 
              id="q-audio" 
              placeholder="What the TTS audio synthesizer speaks..."
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-xs"
            />
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Language</label>
            <select 
              id="q-lang" 
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-xs"
            >
              <option value="en">English (en)</option>
              <option value="ms">Bahasa Melayu (ms)</option>
            </select>
          </div>
        </div>

        <!-- Row 3: Question Type & Image -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Question Type *</label>
            <select 
              id="q-type" 
              required
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-xs"
            >
              <option value="multiple_choice">Multiple Choice</option>
              <option value="ordering">Ordering Sequence</option>
              <option value="clock_analog">Clock / Time</option>
              <option value="comprehension">Reading Comprehension</option>
              <option value="syllable_split">Syllable Split</option>
              <option value="fill_blank">Fill in the Blank</option>
            </select>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Illustration Image URL (Optional)</label>
            <div class="flex items-center gap-2">
              <input 
                type="text" 
                id="q-image-url" 
                placeholder="images/kenderaan/kereta.png"
                oninput="previewModalImage()"
                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white font-mono text-[11px] focus:outline-none focus:border-indigo-500"
              />
              <img id="q-img-preview" src="" alt="preview" class="w-9 h-9 rounded-lg object-cover bg-slate-800 border border-slate-700 hidden shrink-0" />
            </div>
          </div>
        </div>

        <!-- Row 4: Reading Comprehension Passage (Optional) -->
        <div>
          <label class="block text-[11px] font-bold text-slate-400 mb-1">Story Passage (For Comprehension type only)</label>
          <textarea 
            id="q-passage" 
            rows="2"
            placeholder="Optional story passage for reading questions..."
            class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-xs"
          ></textarea>
        </div>

        <!-- Row 5: Options Editor -->
        <div class="bg-slate-950/70 p-4 rounded-2xl border border-slate-800 space-y-3">
          <div class="flex items-center justify-between">
            <label class="text-[11px] font-black uppercase text-indigo-400 tracking-wider">Multiple Choice Options</label>
            <button 
              type="button" 
              onclick="addOptionField('')"
              class="text-[11px] font-bold text-indigo-300 hover:text-white bg-indigo-600/30 hover:bg-indigo-600/50 px-2.5 py-1 rounded-lg border border-indigo-500/40 transition-colors"
            >
              + Add Option
            </button>
          </div>

          <div id="options-container" class="space-y-2">
            <!-- Dynamic Options injected here -->
          </div>
        </div>

        <!-- Row 6: Correct Answer -->
        <div>
          <label class="block text-[11px] font-bold text-emerald-400 mb-1">Correct Answer *</label>
          <input 
            type="text" 
            id="q-correct-answer" 
            placeholder="Type correct answer or click an option above to set..."
            required
            class="w-full bg-slate-950 border border-emerald-500/50 rounded-xl px-3 py-2 text-emerald-300 font-bold focus:outline-none focus:border-emerald-400"
          />
        </div>

        <!-- Row 7: Hint Text & Audio -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Guidance Hint Text</label>
            <input 
              type="text" 
              id="q-hint-text" 
              placeholder="Hint shown if child clicks lightbulb..."
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-xs"
            />
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 mb-1">Guidance Hint Audio (TTS)</label>
            <input 
              type="text" 
              id="q-hint-audio" 
              placeholder="TTS spoken hint..."
              class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-white focus:outline-none focus:border-indigo-500 text-xs"
            />
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
          <button 
            type="button" 
            onclick="closeQuestionModal()" 
            class="px-4 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors font-bold text-xs"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="q-submit-btn"
            class="bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs py-2 px-5 rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-1.5 cursor-pointer"
          >
            <span>Save Question</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Preview Question -->
  <div id="preview-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 relative">
      <div class="flex items-center justify-between pb-3 border-b border-slate-800">
        <h3 class="text-base font-black text-white flex items-center gap-2">
          <span>👁️</span> <span>Question Preview</span>
        </h3>
        <button type="button" onclick="document.getElementById('preview-modal').classList.add('hidden')" class="text-slate-400 hover:text-white p-1 rounded-lg">
          <i class="fa-solid fa-xmark text-base"></i>
        </button>
      </div>

      <div id="preview-body" class="space-y-4">
        <!-- Rendered preview content -->
      </div>
    </div>
  </div>

  <!-- Modal: Delete Confirmation -->
  <div id="delete-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-4 text-center">
      <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center text-xl mx-auto">
        <i class="fa-solid fa-trash-can"></i>
      </div>
      <div>
        <h3 class="text-base font-black text-white">Delete Question?</h3>
        <p id="delete-msg" class="text-xs text-slate-400 mt-1">
          Are you sure you want to permanently delete this question?
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
          class="bg-rose-600 hover:bg-rose-500 text-white font-black text-xs py-2 px-4 rounded-xl shadow-lg shadow-rose-600/30 transition-all cursor-pointer"
        >
          Yes, Delete
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
    NextGrade Educational Operating System • System Admin Questions Module
  </footer>

  <script>
    let metaSubjects = [];
    let metaTopics = [];
    let currentPage = 1;
    let searchTimeout = null;
    const initialTopic = "<?= htmlspecialchars($initialTopicFilter) ?>";
    const initialSubject = "<?= htmlspecialchars($initialSubjectFilter) ?>";

    function showToast(msg, isSuccess = true) {
      const toast = document.getElementById('toast');
      document.getElementById('toast-icon').textContent = isSuccess ? '✅' : '❌';
      document.getElementById('toast-msg').textContent = msg;
      toast.className = `fixed bottom-6 right-6 z-50 bg-slate-800 border ${isSuccess ? 'border-emerald-500/50 text-emerald-200' : 'border-rose-500/50 text-rose-200'} px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-xs font-bold transition-all animate-bounce`;
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 3500);
    }

    function debounceSearch() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => loadQuestions(1), 300);
    }

    function resetFilters() {
      document.getElementById('search-input').value = '';
      document.getElementById('filter-grade').value = '';
      document.getElementById('filter-subject').value = '';
      document.getElementById('filter-topic').value = '';
      document.getElementById('filter-type').value = '';
      document.getElementById('filter-status').value = '';
      document.getElementById('filter-image').value = '';
      onSubjectFilterChange();
      loadQuestions(1);
    }

    async function loadMeta() {
      try {
        const resp = await fetch('../api/admin_questions.php?action=meta');
        const data = await resp.json();
        if (data.success) {
          metaSubjects = data.subjects;
          metaTopics = data.topics;

          document.getElementById('stat-total').textContent = data.stats.total.toLocaleString();
          document.getElementById('stat-kg3').textContent = data.stats.kg3.toLocaleString();
          document.getElementById('stat-psr').textContent = data.stats.psr.toLocaleString();

          populateSubjectDropdowns();
          if (initialSubject) {
            document.getElementById('filter-subject').value = initialSubject;
            onSubjectFilterChange();
          }
          if (initialTopic) {
            // Find topic's subject
            const foundT = metaTopics.find(t => t.id === initialTopic);
            if (foundT) {
              document.getElementById('filter-subject').value = foundT.subject_id;
              onSubjectFilterChange();
              document.getElementById('filter-topic').value = initialTopic;
            }
          }
        }
      } catch (err) {
        console.error(err);
      }
    }

    function populateSubjectDropdowns() {
      const filterSub = document.getElementById('filter-subject');
      filterSub.innerHTML = '<option value="">All Subjects</option>' + 
        metaSubjects.map(s => `<option value="${s.id}" data-grade="${s.grade_level}">${s.icon} ${s.name} (${s.grade_level})</option>`).join('');

      const modalSub = document.getElementById('q-subject-id');
      modalSub.innerHTML = '<option value="">Select Subject</option>' + 
        metaSubjects.map(s => `<option value="${s.id}" data-grade="${s.grade_level}">${s.icon} ${s.name} (${s.grade_level})</option>`).join('');
    }

    function onGradeFilterChange() {
      const selectedGrade = document.getElementById('filter-grade').value;
      const subSel = document.getElementById('filter-subject');

      // Filter subjects dropdown
      let filteredSubs = metaSubjects;
      if (selectedGrade) {
        filteredSubs = metaSubjects.filter(s => s.grade_level === selectedGrade);
      }
      subSel.innerHTML = '<option value="">All Subjects</option>' + 
        filteredSubs.map(s => `<option value="${s.id}" data-grade="${s.grade_level}">${s.icon} ${s.name} (${s.grade_level})</option>`).join('');

      onSubjectFilterChange();
      loadQuestions(1);
    }

    function onSubjectFilterChange() {
      const selectedSub = document.getElementById('filter-subject').value;
      const topicSel = document.getElementById('filter-topic');

      let filteredTopics = metaTopics;
      if (selectedSub) {
        filteredTopics = metaTopics.filter(t => t.subject_id === selectedSub);
      } else {
        const selectedGrade = document.getElementById('filter-grade').value;
        if (selectedGrade) {
          filteredTopics = metaTopics.filter(t => t.grade_level === selectedGrade);
        }
      }

      topicSel.innerHTML = '<option value="">All Topics</option>' + 
        filteredTopics.map(t => `<option value="${t.id}">${t.icon} ${t.name}</option>`).join('');

      loadQuestions(1);
    }

    function onModalGradeChange() {
      const grade = document.getElementById('q-grade-level').value;
      const subSel = document.getElementById('q-subject-id');

      const filteredSubs = metaSubjects.filter(s => s.grade_level === grade);
      subSel.innerHTML = '<option value="">Select Subject</option>' + 
        filteredSubs.map(s => `<option value="${s.id}">${s.icon} ${s.name}</option>`).join('');

      onModalSubjectChange();
    }

    function onModalSubjectChange() {
      const subId = document.getElementById('q-subject-id').value;
      const topicSel = document.getElementById('q-topic-id');

      const filteredTopics = metaTopics.filter(t => t.subject_id === subId);
      topicSel.innerHTML = '<option value="">Select Topic</option>' + 
        filteredTopics.map(t => `<option value="${t.id}">${t.icon} ${t.name}</option>`).join('');
    }

    function autoFillAudioPrompt() {
      const text = document.getElementById('q-text').value;
      const audioInput = document.getElementById('q-audio');
      if (!audioInput.dataset.manualEdited) {
        audioInput.value = text;
      }
    }

    function previewModalImage() {
      const url = document.getElementById('q-image-url').value.trim();
      const img = document.getElementById('q-img-preview');
      if (url) {
        img.src = '../' + url.replace(/^\/+/, '');
        img.classList.remove('hidden');
        img.onerror = () => img.classList.add('hidden');
      } else {
        img.classList.add('hidden');
      }
    }

    function addOptionField(val = '') {
      const container = document.getElementById('options-container');
      const idx = container.children.length;
      const div = document.createElement('div');
      div.className = 'flex items-center gap-2 option-row';
      div.innerHTML = `
        <span class="text-xs font-mono text-slate-500 w-6 text-center">${idx + 1}.</span>
        <input 
          type="text" 
          value="${escapeHtml(val)}" 
          placeholder="Option text..."
          class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5 text-white text-xs focus:outline-none focus:border-indigo-500 option-val"
          oninput="syncCorrectAnswerDropdown()"
        />
        <button 
          type="button" 
          onclick="setAsCorrect(this)" 
          title="Mark as correct answer"
          class="px-2 py-1 rounded-lg text-[10px] font-bold bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-300 border border-emerald-500/40 transition-colors"
        >
          ✓ Set Correct
        </button>
        <button 
          type="button" 
          onclick="this.parentElement.remove(); syncCorrectAnswerDropdown();" 
          class="p-1 text-slate-500 hover:text-rose-400"
        >
          <i class="fa-solid fa-xmark"></i>
        </button>
      `;
      container.appendChild(div);
    }

    function setAsCorrect(btn) {
      const val = btn.parentElement.querySelector('.option-val').value.trim();
      if (val) {
        document.getElementById('q-correct-answer').value = val;
        showToast(`Correct answer set to: "${val}"`);
      }
    }

    function syncCorrectAnswerDropdown() {
      // Optional helper
    }

    function escapeHtml(str) {
      if (!str) return '';
      return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    async function loadQuestions(page = 1) {
      currentPage = page;
      const search = document.getElementById('search-input').value.trim();
      const grade = document.getElementById('filter-grade').value;
      const subject = document.getElementById('filter-subject').value;
      const topic = document.getElementById('filter-topic').value;
      const type = document.getElementById('filter-type').value;
      const status = document.getElementById('filter-status').value;
      const hasImage = document.getElementById('filter-image').value;
      const limit = document.getElementById('filter-limit').value;

      const params = new URLSearchParams({
        action: 'list',
        page: page,
        limit: limit,
        search: search,
        grade_level: grade,
        subject_id: subject,
        topic_id: topic,
        question_type: type,
        status: status,
        has_image: hasImage
      });

      const tbody = document.getElementById('questions-tbody');
      tbody.innerHTML = `
        <tr>
          <td colspan="7" class="text-center py-10 text-slate-400">
            <i class="fa-solid fa-circle-notch fa-spin text-2xl text-indigo-400 mb-2 block"></i>
            Loading questions page ${page}...
          </td>
        </tr>
      `;

      try {
        const resp = await fetch(`../api/admin_questions.php?${params.toString()}`);
        const data = await resp.json();

        if (!data.success) {
          showToast(data.error || 'Failed to load questions', false);
          return;
        }

        renderQuestionsTable(data.questions);
        renderPagination(data.total, data.page, data.limit, data.total_pages);
        document.getElementById('stat-filtered').textContent = data.total.toLocaleString();
      } catch (err) {
        console.error(err);
        showToast('Error loading questions', false);
      }
    }

    function renderQuestionsTable(questions) {
      const tbody = document.getElementById('questions-tbody');

      if (!questions || questions.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="7" class="text-center py-12 text-slate-500 font-bold">
              No questions matched your search or filters.
            </td>
          </tr>
        `;
        return;
      }

      tbody.innerHTML = questions.map(q => {
        const isKG3 = (q.grade_level === 'Kindergarten 3 (KG3)');
        const gradeBadge = isKG3 
          ? `<span class="bg-sky-500/20 text-sky-300 border border-sky-500/30 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">KG3</span>`
          : `<span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider">PSR</span>`;

        const isActive = (q.status === 'active');
        const statusBadge = isActive
          ? `<button type="button" onclick="toggleQuestionStatus(${q.id})" title="Status: Active. Click to disable" class="inline-flex items-center gap-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider transition-colors cursor-pointer"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active</button>`
          : `<button type="button" onclick="toggleQuestionStatus(${q.id})" title="Status: Disabled. Click to enable" class="inline-flex items-center gap-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/40 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider transition-colors cursor-pointer"><span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Disabled</button>`;

        let typeBadge = `<span class="bg-slate-700/60 text-slate-300 px-2 py-0.5 rounded-md text-[10px] font-mono">${q.question_type}</span>`;
        if (q.question_type === 'multiple_choice') typeBadge = `<span class="bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded-md text-[10px] font-mono">Multiple Choice</span>`;
        if (q.question_type === 'clock_analog') typeBadge = `<span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2 py-0.5 rounded-md text-[10px] font-mono">Clock / Time</span>`;
        if (q.question_type === 'ordering') typeBadge = `<span class="bg-purple-500/20 text-purple-300 border border-purple-500/30 px-2 py-0.5 rounded-md text-[10px] font-mono">Ordering</span>`;
        if (q.question_type === 'comprehension') typeBadge = `<span class="bg-sky-500/20 text-sky-300 border border-sky-500/30 px-2 py-0.5 rounded-md text-[10px] font-mono">Comprehension</span>`;

        // Image thumbnail
        let imgThumb = '';
        if (q.image_url) {
          const clean = q.image_url.replace(/^\/+/, '');
          imgThumb = `<img src="../${clean}" alt="img" class="w-8 h-8 rounded-lg object-cover bg-slate-900 border border-slate-700 shrink-0" onerror="this.remove()" />`;
        }

        // Options snippet
        const opts = q.options_list || [];
        const optsSnippet = opts.map(o => {
          const isCorrect = (String(o).trim() === String(q.correct_answer).trim());
          return `<span class="inline-block px-1.5 py-0.5 rounded text-[10px] ${isCorrect ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-black' : 'bg-slate-800 text-slate-400'}">${escapeHtml(o)}</span>`;
        }).slice(0, 4).join(' ');

        return `
          <tr class="hover:bg-slate-800/50 transition-colors group ${isActive ? '' : 'opacity-70 bg-slate-950/30'}">
            <td class="py-3 px-4 font-mono text-slate-400 text-xs">
              #${q.id}
            </td>

            <td class="py-3 px-4">
              <div class="flex items-start gap-2.5">
                ${imgThumb}
                <div class="space-y-1">
                  <div class="font-black ${isActive ? 'text-white' : 'text-slate-300'} text-xs leading-snug line-clamp-2">
                    ${escapeHtml(q.question_text)}
                  </div>
                  <div class="flex items-center gap-2 text-[10px] text-slate-500">
                    ${q.question_audio ? '<span title="TTS audio available">🔊 Audio</span>' : ''}
                    <span>• Lang: <strong class="uppercase text-slate-400">${q.lang}</strong></span>
                    ${!isActive ? '<span class="text-rose-400 font-bold">• Inactive</span>' : ''}
                  </div>
                </div>
              </div>
            </td>

            <td class="py-3 px-4">
              <div class="space-y-1">
                <span class="text-slate-200 font-bold block text-xs truncate max-w-[150px]" title="${q.subject_name}">
                  ${q.subject_icon || '📚'} ${q.subject_name || q.subject_id}
                </span>
                <span class="text-slate-400 text-[11px] block truncate max-w-[150px]" title="${q.topic_name}">
                  ${q.topic_name || q.topic_id}
                </span>
                ${gradeBadge}
              </div>
            </td>

            <td class="py-3 px-4 text-center">
              ${statusBadge}
            </td>

            <td class="py-3 px-4">
              ${typeBadge}
            </td>

            <td class="py-3 px-4">
              <div class="space-y-1">
                <div class="text-emerald-400 font-black text-xs flex items-center gap-1">
                  <i class="fa-solid fa-check text-[10px]"></i>
                  <span>${escapeHtml(q.correct_answer)}</span>
                </div>
                <div class="flex flex-wrap gap-1">
                  ${optsSnippet}
                </div>
              </div>
            </td>

            <td class="py-3 px-4 text-right">
              <div class="flex items-center justify-end gap-1">
                <!-- Quick Toggle Status Button -->
                <button 
                  type="button" 
                  onclick="toggleQuestionStatus(${q.id})"
                  title="${isActive ? 'Quick Disable Question' : 'Quick Enable Question'}" 
                  class="p-1.5 rounded-lg ${isActive ? 'bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30' : 'bg-slate-700/60 hover:bg-slate-700 text-slate-400 hover:text-white'} transition-colors cursor-pointer"
                >
                  <i class="fa-solid ${isActive ? 'fa-toggle-on text-xs' : 'fa-toggle-off text-xs'}"></i>
                </button>
                <button 
                  type="button" 
                  onclick="previewQuestion(${q.id})"
                  title="Preview" 
                  class="p-1.5 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-indigo-300 hover:text-white transition-colors cursor-pointer"
                >
                  <i class="fa-solid fa-eye text-xs"></i>
                </button>
                <button 
                  type="button" 
                  onclick="openEditQuestionModal(${q.id})"
                  title="Edit" 
                  class="p-1.5 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-amber-300 hover:text-white transition-colors cursor-pointer"
                >
                  <i class="fa-solid fa-pen-to-square text-xs"></i>
                </button>
                <button 
                  type="button" 
                  onclick="duplicateQuestion(${q.id})"
                  title="Duplicate Question" 
                  class="p-1.5 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-sky-300 hover:text-white transition-colors cursor-pointer"
                >
                  <i class="fa-solid fa-copy text-xs"></i>
                </button>
                <button 
                  type="button" 
                  onclick="confirmDeleteQuestion(${q.id})"
                  title="Delete Question" 
                  class="p-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 transition-colors cursor-pointer"
                >
                  <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    function renderPagination(total, page, limit, totalPages) {
      const from = total === 0 ? 0 : (page - 1) * limit + 1;
      const to = Math.min(page * limit, total);
      document.getElementById('pagination-info').textContent = `Showing ${from.toLocaleString()} to ${to.toLocaleString()} of ${total.toLocaleString()} questions`;

      const controls = document.getElementById('pagination-controls');
      if (totalPages <= 1) {
        controls.innerHTML = '';
        return;
      }

      let html = '';
      if (page > 1) {
        html += `<button onclick="loadQuestions(${page - 1})" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300">&laquo; Prev</button>`;
      }

      // Page numbers window
      const startP = Math.max(1, page - 2);
      const endP = Math.min(totalPages, page + 2);
      for (let p = startP; p <= endP; p++) {
        html += `<button onclick="loadQuestions(${p})" class="px-2.5 py-1 rounded-lg text-xs font-bold ${p === page ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-800 hover:bg-slate-700 text-slate-300'}">${p}</button>`;
      }

      if (page < totalPages) {
        html += `<button onclick="loadQuestions(${page + 1})" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300">Next &raquo;</button>`;
      }

      controls.innerHTML = html;
    }

    function openCreateQuestionModal() {
      document.getElementById('modal-title').innerHTML = '<span>❓</span> <span>Add New Question</span>';
      document.getElementById('q-action').value = 'create';
      document.getElementById('q-id').value = '0';
      document.getElementById('q-grade-level').value = 'Kindergarten 3 (KG3)';
      onModalGradeChange();

      // If filter has subject, select it
      const curSub = document.getElementById('filter-subject').value;
      if (curSub) {
        document.getElementById('q-subject-id').value = curSub;
        onModalSubjectChange();
      }
      const curTopic = document.getElementById('filter-topic').value;
      if (curTopic) {
        document.getElementById('q-topic-id').value = curTopic;
      }

      document.getElementById('q-text').value = '';
      document.getElementById('q-audio').value = '';
      document.getElementById('q-audio').dataset.manualEdited = '';
      document.getElementById('q-lang').value = 'en';
      document.getElementById('q-type').value = 'multiple_choice';
      document.getElementById('q-status').value = 'active';
      document.getElementById('q-image-url').value = '';
      document.getElementById('q-passage').value = '';
      document.getElementById('q-correct-answer').value = '';
      document.getElementById('q-hint-text').value = '';
      document.getElementById('q-hint-audio').value = '';
      document.getElementById('q-img-preview').classList.add('hidden');

      // Clear and seed 4 empty options
      document.getElementById('options-container').innerHTML = '';
      addOptionField('');
      addOptionField('');
      addOptionField('');
      addOptionField('');

      document.getElementById('question-modal').classList.remove('hidden');
    }

    async function openEditQuestionModal(qId) {
      try {
        const resp = await fetch(`../api/admin_questions.php?action=single&id=${qId}`);
        const data = await resp.json();

        if (!data.success || !data.question) {
          showToast(data.error || 'Failed to fetch question details', false);
          return;
        }

        const q = data.question;
        document.getElementById('modal-title').innerHTML = `<span>✏️</span> <span>Edit Question #${q.id}</span>`;
        document.getElementById('q-action').value = 'update';
        document.getElementById('q-id').value = q.id;

        document.getElementById('q-grade-level').value = q.grade_level || 'Kindergarten 3 (KG3)';
        onModalGradeChange();

        document.getElementById('q-subject-id').value = q.subject_id;
        onModalSubjectChange();
        document.getElementById('q-topic-id').value = q.topic_id;

        document.getElementById('q-text').value = q.question_text;
        document.getElementById('q-audio').value = q.question_audio || q.question_text;
        document.getElementById('q-audio').dataset.manualEdited = 'true';
        document.getElementById('q-lang').value = q.lang || 'en';
        document.getElementById('q-type').value = q.question_type || 'multiple_choice';
        document.getElementById('q-status').value = q.status || 'active';
        document.getElementById('q-image-url').value = q.image_url || '';
        document.getElementById('q-passage').value = q.passage || '';
        document.getElementById('q-correct-answer').value = q.correct_answer;
        document.getElementById('q-hint-text').value = q.hint_text || '';
        document.getElementById('q-hint-audio').value = q.hint_audio || '';

        previewModalImage();

        // Options
        const container = document.getElementById('options-container');
        container.innerHTML = '';
        const opts = q.options_list || [];
        if (opts.length > 0) {
          opts.forEach(o => addOptionField(o));
        } else {
          addOptionField(q.correct_answer);
        }

        document.getElementById('question-modal').classList.remove('hidden');
      } catch (err) {
        console.error(err);
        showToast('Error loading question details', false);
      }
    }

    function closeQuestionModal() {
      document.getElementById('question-modal').classList.add('hidden');
    }

    async function toggleQuestionStatus(qId) {
      try {
        const resp = await fetch('../api/admin_questions.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'toggle_status', id: qId })
        });
        const data = await resp.json();
        if (data.success) {
          showToast(data.message || 'Status updated');
          loadQuestions(currentPage);
          loadMeta();
        } else {
          showToast(data.error || 'Failed to toggle status', false);
        }
      } catch (err) {
        showToast('Network error toggling status', false);
      }
    }

    async function handleSaveQuestion(e) {
      e.preventDefault();
      const action = document.getElementById('q-action').value;
      const submitBtn = document.getElementById('q-submit-btn');
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

      // Gather options
      const optInputs = document.querySelectorAll('.option-val');
      const options = [];
      optInputs.forEach(i => {
        const v = i.value.trim();
        if (v) options.push(v);
      });

      const payload = {
        action: action,
        id: parseInt(document.getElementById('q-id').value) || 0,
        grade_level: document.getElementById('q-grade-level').value,
        subject_id: document.getElementById('q-subject-id').value,
        topic_id: document.getElementById('q-topic-id').value,
        status: document.getElementById('q-status').value,
        question_text: document.getElementById('q-text').value.trim(),
        question_audio: document.getElementById('q-audio').value.trim(),
        lang: document.getElementById('q-lang').value,
        question_type: document.getElementById('q-type').value,
        image_url: document.getElementById('q-image-url').value.trim(),
        passage: document.getElementById('q-passage').value.trim(),
        options: options,
        correct_answer: document.getElementById('q-correct-answer').value.trim(),
        hint_text: document.getElementById('q-hint-text').value.trim(),
        hint_audio: document.getElementById('q-hint-audio').value.trim()
      };

      try {
        const resp = await fetch('../api/admin_questions.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await resp.json();

        if (data.success) {
          showToast(data.message || 'Saved successfully');
          closeQuestionModal();
          loadQuestions(currentPage);
          loadMeta();
        } else {
          showToast(data.error || 'Failed to save question', false);
        }
      } catch (err) {
        showToast('Network error while saving', false);
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>Save Question</span>';
      }
    }

    async function duplicateQuestion(qId) {
      if (!confirm(`Duplicate question #${qId}? A cloned copy will be added to the question bank.`)) {
        return;
      }

      try {
        const resp = await fetch('../api/admin_questions.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'duplicate', id: qId })
        });
        const data = await resp.json();

        if (data.success) {
          showToast(data.message || 'Question duplicated');
          loadQuestions(1);
          loadMeta();
        } else {
          showToast(data.error || 'Failed to duplicate question', false);
        }
      } catch (err) {
        showToast('Network error duplicating question', false);
      }
    }

    function confirmDeleteQuestion(qId) {
      document.getElementById('delete-msg').innerHTML = `Are you sure you want to permanently delete question <strong class="text-white">#${qId}</strong>?`;
      const btn = document.getElementById('confirm-delete-btn');
      btn.onclick = () => executeDeleteQuestion(qId);
      document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
    }

    async function executeDeleteQuestion(qId) {
      try {
        const resp = await fetch('../api/admin_questions.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', id: qId })
        });
        const data = await resp.json();

        if (data.success) {
          showToast(data.message || 'Question deleted');
          closeDeleteModal();
          loadQuestions(currentPage);
          loadMeta();
        } else {
          showToast(data.error || 'Failed to delete question', false);
        }
      } catch (err) {
        showToast('Network error while deleting', false);
      }
    }

    async function previewQuestion(qId) {
      try {
        const resp = await fetch(`../api/admin_questions.php?action=single&id=${qId}`);
        const data = await resp.json();

        if (!data.success || !data.question) {
          showToast('Failed to load preview', false);
          return;
        }

        const q = data.question;
        const body = document.getElementById('preview-body');

        let imgHtml = '';
        if (q.image_url) {
          const clean = q.image_url.replace(/^\/+/, '');
          imgHtml = `
            <div class="flex justify-center mb-3">
              <img src="../${clean}" alt="Question Image" class="max-h-40 rounded-2xl border-2 border-slate-700 shadow-md object-contain bg-slate-950 p-1" />
            </div>
          `;
        }

        let passageHtml = '';
        if (q.passage) {
          passageHtml = `
            <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-slate-300 text-xs italic mb-3 max-h-32 overflow-y-auto">
              ${escapeHtml(q.passage)}
            </div>
          `;
        }

        const opts = q.options_list || [];
        const optsHtml = opts.map((o, i) => {
          const isCorrect = (String(o).trim() === String(q.correct_answer).trim());
          return `
            <div class="p-3 rounded-xl border flex items-center justify-between ${isCorrect ? 'bg-emerald-950/40 border-emerald-500/60 text-emerald-200 font-bold' : 'bg-slate-950 border-slate-800 text-slate-300'}">
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-400 text-[10px] flex items-center justify-center font-bold">${i + 1}</span>
                <span>${escapeHtml(o)}</span>
              </div>
              ${isCorrect ? '<span class="text-xs text-emerald-400 font-black">✓ Correct Answer</span>' : ''}
            </div>
          `;
        }).join('');

        body.innerHTML = `
          <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 pb-2 border-b border-slate-800">
            <span>#${q.id} • ${q.subject_name} ➔ ${q.topic_name}</span>
            <span class="px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-mono">${q.grade_level}</span>
          </div>

          ${passageHtml}
          ${imgHtml}

          <div class="text-base font-black text-white text-center py-2">
            ${escapeHtml(q.question_text)}
          </div>

          <div class="space-y-2 pt-2">
            ${optsHtml}
          </div>

          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 text-xs text-slate-400 space-y-1 mt-3">
            <div><strong class="text-slate-300">💡 Hint:</strong> ${escapeHtml(q.hint_text || 'None')}</div>
            <div><strong class="text-slate-300">🔊 Voice Prompt:</strong> ${escapeHtml(q.question_audio || q.question_text)}</div>
          </div>
        `;

        document.getElementById('preview-modal').classList.remove('hidden');
      } catch (err) {
        showToast('Error displaying preview', false);
      }
    }

    document.addEventListener('DOMContentLoaded', async () => {
      await loadMeta();
      await loadQuestions(1);
    });
  </script>

</body>
</html>
