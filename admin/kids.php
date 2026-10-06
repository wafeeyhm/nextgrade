<?php
// NextGrade - System Admin: Students & Kids Management (Full CRUD, Toggle Status, PIN Reset)
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Students & Kids - System Admin | NextGrade</title>
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
<body class="admin-dark min-h-screen bg-slate-900 text-slate-100 flex flex-col font-sans">

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

        <div class="hidden md:flex items-center gap-1 ml-6 border-l border-slate-700 pl-6">
          <a href="index.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-chart-pie mr-1.5 text-slate-400"></i> Dashboard
          </a>
          <a href="parents.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Parents
          </a>
          <a href="kids.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-graduation-cap mr-1.5 text-indigo-400"></i> Students
          </a>
          <a href="topics.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-layer-group mr-1.5 text-slate-400"></i> Topics (CRUD)
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
          href="profile.php"
          class="hidden sm:flex flex-col text-right hover:opacity-80 transition-opacity"
          title="Click to edit admin profile"
        >
          <span class="text-xs font-black text-white"><?= htmlspecialchars($admin['full_name']) ?></span>
          <span class="text-[10px] font-bold text-indigo-400 font-mono">@<?= htmlspecialchars($admin['username']) ?></span>
        </a>

        <a 
          href="guide.php" 
          title="System Admin Guide"
          class="bg-slate-700/70 hover:bg-slate-700 text-indigo-300 hover:text-white text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-book text-xs"></i>
          <span class="hidden sm:inline">Guide</span>
        </a>

        <a 
          href="../index.php" 
          target="_blank" 
          title="Open Student App"
          class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <span>🌟</span> <span class="hidden lg:inline">Student App</span>
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

  <!-- Main Content Container -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8 space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight flex items-center gap-2.5">
          <span>🧒</span>
          <span>Students & Kids Management</span>
        </h1>
        <p class="text-sm font-semibold text-slate-400 mt-1">
          Full management of student accounts: Toggle active/disabled status, update 4-digit PINs, and link parent families.
        </p>
      </div>

      <div class="flex items-center gap-2.5">
        <a 
          href="parents.php"
          class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs py-2.5 px-4 rounded-xl border border-slate-700 transition-all flex items-center gap-2"
        >
          <i class="fa-solid fa-users"></i>
          <span>Parent Directory</span>
        </a>

        <button 
          onclick="openCreateStudentModal()"
          class="bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-600 hover:to-sky-600 text-white font-black text-xs py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-500/20 transition-all flex items-center gap-2 cursor-pointer"
        >
          <i class="fa-solid fa-plus text-xs"></i>
          <span>Add New Student</span>
        </button>
      </div>
    </div>

    <!-- Live Toast Alert Banner -->
    <div id="live-toast" class="hidden rounded-xl p-3.5 text-xs font-bold transition-all shadow-lg flex items-center justify-between">
      <div class="flex items-center gap-2" id="live-toast-content"></div>
      <button onclick="document.getElementById('live-toast').classList.add('hidden')" class="text-slate-400 hover:text-white text-sm ml-4">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5 shadow-md">
        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Total Students</span>
          <span id="stat-total-students" class="text-2xl font-black text-white">0</span>
        </div>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5 shadow-md">
        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Active (Enabled)</span>
          <span id="stat-active-students" class="text-2xl font-black text-emerald-400">0</span>
        </div>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5 shadow-md">
        <div class="w-12 h-12 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-ban"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Disabled (Locked)</span>
          <span id="stat-disabled-students" class="text-2xl font-black text-rose-400">0</span>
        </div>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5 shadow-md">
        <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 border border-purple-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-award"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Quiz Sessions</span>
          <span id="stat-total-quizzes" class="text-2xl font-black text-purple-400">0</span>
        </div>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4 shadow-lg">
      <div class="relative w-full md:max-w-md">
        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input 
          type="text" 
          id="student-search-input" 
          placeholder="Search by student name, username, PIN, parent..."
          oninput="debounceSearch()"
          class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-2 text-sm text-white font-medium placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 transition-colors"
        >
      </div>

      <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="flex items-center gap-1.5">
          <label for="status-filter" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status:</label>
          <select 
            id="status-filter" 
            onchange="fetchStudents()"
            class="bg-slate-900 border border-slate-700 text-slate-200 text-xs font-bold rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-500"
          >
            <option value="">All Statuses</option>
            <option value="active">Active Only</option>
            <option value="inactive">Disabled Only</option>
          </select>
        </div>

        <div class="flex items-center gap-1.5">
          <label for="grade-filter" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Grade:</label>
          <select 
            id="grade-filter" 
            onchange="fetchStudents()"
            class="bg-slate-900 border border-slate-700 text-slate-200 text-xs font-bold rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-500"
          >
            <option value="">All Grades</option>
            <option value="Kindergarten 3 (KG3)">Kindergarten 3 (KG3)</option>
            <option value="Primary Year 6 (PSR)">Primary Year 6 (PSR)</option>
          </select>
        </div>

        <button 
          onclick="fetchStudents()" 
          title="Refresh List"
          class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-bold px-3 py-2 rounded-xl transition-colors cursor-pointer"
        >
          <i class="fa-solid fa-arrows-rotate"></i>
        </button>
      </div>
    </div>

    <!-- Students Table Card -->
    <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl overflow-hidden shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-900/80 border-b border-slate-700 text-slate-400 uppercase text-[11px] font-black tracking-wider">
            <tr>
              <th class="py-3.5 px-4">Kid Profile</th>
              <th class="py-3.5 px-4">Login Access (Username & PIN)</th>
              <th class="py-3.5 px-4">Parent / Family Account</th>
              <th class="py-3.5 px-4 text-center">Grade Level</th>
              <th class="py-3.5 px-4 text-center">Quizzes</th>
              <th class="py-3.5 px-4 text-center">Accuracy</th>
              <th class="py-3.5 px-4 text-center">Account Status</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="students-table-body" class="divide-y divide-slate-700/50 text-slate-200 font-medium">
            <tr>
              <td colspan="8" class="text-center py-12 text-slate-400 font-bold">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl text-indigo-400 mb-2 block"></i>
                Loading students and learning profiles...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- Modal: Create / Edit Student -->
  <div id="student-modal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative text-left">
      <button 
        onclick="closeStudentModal()"
        class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors"
      >
        <i class="fa-solid fa-xmark text-lg"></i>
      </button>

      <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg">
          🧒
        </div>
        <div>
          <h2 id="student-modal-title" class="text-lg font-black text-white">Add New Student Profile</h2>
          <p class="text-xs text-slate-400 font-medium">Configure student identity, simple access credentials, and parental linkage.</p>
        </div>
      </div>

      <div id="student-modal-alert" class="hidden mb-4 p-3 rounded-xl text-xs font-bold"></div>

      <form id="student-form" onsubmit="handleSaveStudent(event)" class="space-y-4">
        <input type="hidden" id="form-student-id" value="">

        <!-- Name & Username -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Student Full Name *</label>
            <input 
              type="text" 
              id="form-student-name"
              required
              placeholder="e.g. Aisyah"
              oninput="suggestStudentUsername(this.value)"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"
            >
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Simple Username *</label>
            <input 
              type="text" 
              id="form-student-username"
              required
              placeholder="e.g. aisyah"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm font-mono text-indigo-300 focus:outline-none focus:border-indigo-500"
            >
          </div>
        </div>

        <!-- 4-Digit PIN & Account Status -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <div class="flex items-center justify-between mb-1">
              <label class="block text-xs font-bold text-slate-300">4-Digit PIN *</label>
              <button 
                type="button" 
                onclick="document.getElementById('form-student-pin').value = String(Math.floor(1000 + Math.random() * 9000))"
                class="text-[10px] text-amber-400 hover:text-amber-300 font-bold"
              >
                🎲 Random PIN
              </button>
            </div>
            <input 
              type="text" 
              id="form-student-pin"
              required
              maxlength="6"
              placeholder="1234"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm font-mono font-black text-amber-400 focus:outline-none focus:border-indigo-500"
            >
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Account Status *</label>
            <select 
              id="form-student-status"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm font-bold text-white focus:outline-none focus:border-indigo-500"
            >
              <option value="active">Active (Can log in & practice)</option>
              <option value="inactive">Disabled (Account locked)</option>
            </select>
          </div>
        </div>

        <!-- Grade Level & Parent Link -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Curriculum Grade Level *</label>
            <select 
              id="form-student-grade"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500 font-bold"
            >
              <option value="Kindergarten 3 (KG3)">⭐ Kindergarten 3 (KG3)</option>
              <option value="Primary Year 6 (PSR)">🇧🇳 Primary Year 6 (PSR)</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1">Linked Parent Account</label>
            <select 
              id="form-student-parent"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 font-medium"
            >
              <option value="">-- No Parent Linked (Standalone) --</option>
            </select>
          </div>
        </div>

        <!-- Avatar Choice -->
        <div>
          <label class="block text-xs font-bold text-slate-300 mb-2">Choose Avatar:</label>
          <div class="grid grid-cols-6 gap-2">
            <label class="cursor-pointer">
              <input type="radio" name="student_avatar" value="star_kid" class="peer sr-only" checked>
              <div class="h-12 rounded-xl bg-slate-900 border-2 border-slate-700 flex items-center justify-center text-xl peer-checked:border-indigo-500 peer-checked:bg-indigo-600/20 hover:border-slate-500 transition-all">⭐</div>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="student_avatar" value="bunny" class="peer sr-only">
              <div class="h-12 rounded-xl bg-slate-900 border-2 border-slate-700 flex items-center justify-center text-xl peer-checked:border-indigo-500 peer-checked:bg-indigo-600/20 hover:border-slate-500 transition-all">🐰</div>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="student_avatar" value="astronaut" class="peer sr-only">
              <div class="h-12 rounded-xl bg-slate-900 border-2 border-slate-700 flex items-center justify-center text-xl peer-checked:border-indigo-500 peer-checked:bg-indigo-600/20 hover:border-slate-500 transition-all">🚀</div>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="student_avatar" value="dino" class="peer sr-only">
              <div class="h-12 rounded-xl bg-slate-900 border-2 border-slate-700 flex items-center justify-center text-xl peer-checked:border-indigo-500 peer-checked:bg-indigo-600/20 hover:border-slate-500 transition-all">🦖</div>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="student_avatar" value="kitten" class="peer sr-only">
              <div class="h-12 rounded-xl bg-slate-900 border-2 border-slate-700 flex items-center justify-center text-xl peer-checked:border-indigo-500 peer-checked:bg-indigo-600/20 hover:border-slate-500 transition-all">🐱</div>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="student_avatar" value="unicorn" class="peer sr-only">
              <div class="h-12 rounded-xl bg-slate-900 border-2 border-slate-700 flex items-center justify-center text-xl peer-checked:border-indigo-500 peer-checked:bg-indigo-600/20 hover:border-slate-500 transition-all">🦄</div>
            </label>
          </div>
        </div>

        <div class="pt-3 border-t border-slate-700 flex items-center justify-end gap-3">
          <button 
            type="button" 
            onclick="closeStudentModal()"
            class="px-4 py-2.5 rounded-xl border border-slate-700 text-xs font-bold text-slate-300 hover:bg-slate-700/50 transition-colors cursor-pointer"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="btn-save-student"
            class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-black text-white shadow-lg shadow-indigo-600/30 transition-all cursor-pointer"
          >
            Save Student Profile
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Quick Change PIN Code -->
  <div id="pin-modal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-slate-800 border border-amber-500/40 rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl relative">
      <div class="w-12 h-12 mx-auto mb-2 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-2xl">
        🔑
      </div>
      <h3 id="pin-modal-title" class="text-lg font-black text-white">Change Student PIN</h3>
      <p class="text-xs text-slate-400 font-medium mb-4">Set a 4-digit PIN for tablet and app login:</p>

      <div id="pin-modal-alert" class="hidden mb-3 p-2.5 rounded-xl text-xs font-bold"></div>

      <input type="hidden" id="pin-student-id" value="">
      <div class="mb-4">
        <input 
          type="text" 
          id="pin-modal-input" 
          maxlength="6"
          placeholder="1234"
          class="w-full bg-slate-900 border-2 border-amber-500/50 rounded-2xl py-3 text-center text-2xl font-black font-mono text-amber-400 tracking-widest focus:outline-none focus:border-amber-400 transition-all"
        />
        <div class="flex items-center justify-center gap-1.5 mt-2">
          <span class="text-[10px] text-slate-400 font-bold">Presets:</span>
          <button type="button" onclick="document.getElementById('pin-modal-input').value='1234'" class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-700 hover:bg-slate-600 cursor-pointer text-slate-200">1234</button>
          <button type="button" onclick="document.getElementById('pin-modal-input').value='0000'" class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-700 hover:bg-slate-600 cursor-pointer text-slate-200">0000</button>
          <button type="button" onclick="document.getElementById('pin-modal-input').value=String(Math.floor(1000 + Math.random() * 9000))" class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 hover:bg-amber-500/30 cursor-pointer">🎲 Random</button>
        </div>
      </div>

      <div class="flex items-center justify-center gap-2">
        <button 
          type="button" 
          onclick="closePinModal()" 
          class="px-4 py-2.5 rounded-xl border border-slate-700 text-xs font-bold text-slate-300 hover:bg-slate-700/50 transition-colors cursor-pointer"
        >
          Cancel
        </button>
        <button 
          type="button" 
          id="btn-save-pin"
          onclick="submitChangePin()" 
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-xs font-black text-slate-900 shadow-lg shadow-amber-500/20 transition-all cursor-pointer"
        >
          Update PIN
        </button>
      </div>
    </div>
  </div>

  <script>
    let studentsData = [];
    let parentsListData = [];
    let searchDebounceTimeout = null;

    const avatarMap = {
      'star_kid': '⭐',
      'bunny': '🐰',
      'astronaut': '🚀',
      'dino': '🦖',
      'kitten': '🐱',
      'unicorn': '🦄'
    };

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function showToast(message, type = 'success') {
      const toast = document.getElementById('live-toast');
      const content = document.getElementById('live-toast-content');
      
      toast.className = type === 'success' 
        ? 'rounded-xl p-3.5 text-xs font-bold shadow-lg flex items-center justify-between bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 block'
        : 'rounded-xl p-3.5 text-xs font-bold shadow-lg flex items-center justify-between bg-rose-500/20 border border-rose-500/40 text-rose-300 block';

      content.innerHTML = `
        <i class="fa-solid ${type === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-rose-400'} text-sm"></i>
        <span>${escapeHtml(message)}</span>
      `;

      setTimeout(() => {
        toast.classList.add('hidden');
      }, 5000);
    }

    function debounceSearch() {
      clearTimeout(searchDebounceTimeout);
      searchDebounceTimeout = setTimeout(() => {
        fetchStudents();
      }, 300);
    }

    async function fetchStudents() {
      const tbody = document.getElementById('students-table-body');
      tbody.innerHTML = `
        <tr>
          <td colspan="8" class="text-center py-10 text-slate-400 font-bold">
            <i class="fa-solid fa-circle-notch fa-spin text-xl text-indigo-400 mb-2 block"></i>
            Refreshing student directory...
          </td>
        </tr>
      `;

      const search = document.getElementById('student-search-input').value.trim();
      const status = document.getElementById('status-filter').value;
      const grade = document.getElementById('grade-filter').value;

      const params = new URLSearchParams({ action: 'list' });
      if (search) params.append('search', search);
      if (status) params.append('status', status);
      if (grade) params.append('grade_level', grade);

      try {
        const resp = await fetch(`../api/admin_kids.php?${params.toString()}`);
        const data = await resp.json();

        if (!data.success) {
          tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-rose-400 font-bold">${escapeHtml(data.error || 'Failed to load students.')}</td></tr>`;
          return;
        }

        studentsData = data.students || [];
        parentsListData = data.parents || [];

        // Update stats
        if (data.stats) {
          document.getElementById('stat-total-students').textContent = data.stats.total || 0;
          document.getElementById('stat-active-students').textContent = data.stats.active || 0;
          document.getElementById('stat-disabled-students').textContent = data.stats.inactive || 0;
          document.getElementById('stat-total-quizzes').textContent = data.stats.quizzes || 0;
        }

        // Populate Parent Dropdown in modal
        const parentSelect = document.getElementById('form-student-parent');
        parentSelect.innerHTML = '<option value="">-- No Parent Linked (Standalone) --</option>' + 
          parentsListData.map(p => `
            <option value="${p.id}">
              ${escapeHtml(p.full_name)} (${p.parent_code}) ${p.status === 'inactive' ? '[Parent Deactivated]' : ''}
            </option>
          `).join('');

        renderTable(studentsData);

      } catch (err) {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-10 text-rose-400 font-bold">Connection error loading students.</td></tr>';
      }
    }

    function renderTable(students) {
      const tbody = document.getElementById('students-table-body');
      if (students.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-12 text-slate-400 font-bold">No students found matching your criteria.</td></tr>';
        return;
      }

      tbody.innerHTML = students.map(s => {
        const isInactive = s.status === 'inactive';
        const isParentInactive = s.parent_id && s.parent_status === 'inactive';
        const isHigh = s.avg_score >= 70;

        return `
          <tr class="hover:bg-slate-700/30 transition-colors ${isInactive ? 'bg-rose-950/10' : ''}">
            <!-- Profile -->
            <td class="py-3.5 px-4">
              <div class="flex items-center gap-3">
                <span class="text-2xl p-1.5 rounded-xl bg-slate-900 border border-slate-700">
                  ${avatarMap[s.avatar] || '⭐'}
                </span>
                <div>
                  <div class="flex items-center gap-2">
                    <span class="font-black text-white text-sm">${escapeHtml(s.name)}</span>
                    ${isInactive 
                      ? '<span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-300 border border-rose-500/30">Disabled</span>' 
                      : '<span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>'
                    }
                  </div>
                  <span class="text-[11px] text-slate-400 font-mono">ID: #${s.id}</span>
                </div>
              </div>
            </td>

            <!-- Login Access -->
            <td class="py-3.5 px-4 font-mono text-xs">
              <div class="flex items-center gap-1.5">
                <span class="text-slate-400">User:</span>
                <strong class="text-indigo-400 font-bold">${escapeHtml(s.username || 'Not set')}</strong>
              </div>
              <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-slate-400">PIN:</span>
                <strong class="text-amber-400 font-black tracking-wider bg-slate-900 px-1.5 py-0.5 rounded border border-slate-700">${escapeHtml(s.pin_code || '1234')}</strong>
                <button 
                  onclick="openChangePinModal(${s.id}, '${escapeHtml(s.name)}', '${escapeHtml(s.pin_code || '1234')}')"
                  class="text-[10px] text-amber-400 hover:text-amber-300 font-sans font-bold px-1.5 py-0.5 rounded bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 transition-colors cursor-pointer"
                  title="Change Student PIN"
                >
                  <i class="fa-solid fa-key text-[9px]"></i> PIN
                </button>
              </div>
            </td>

            <!-- Parent Link -->
            <td class="py-3.5 px-4 text-xs">
              ${s.parent_id ? `
                <div class="font-bold text-white flex items-center gap-1.5">
                  <span>${escapeHtml(s.parent_name)}</span>
                  ${isParentInactive ? '<span class="text-[9px] px-1 py-0.2 rounded bg-rose-500/20 text-rose-400 border border-rose-500/30 font-black uppercase" title="Parent is inactive: Child login blocked">Parent Inactive</span>' : ''}
                </div>
                <div class="text-indigo-400 font-mono text-[11px]">${escapeHtml(s.parent_code)}</div>
                ${isParentInactive ? '<div class="text-[10px] text-rose-400 font-semibold mt-0.5">⚠️ Child blocked by parent deactivation</div>' : ''}
              ` : `
                <span class="text-slate-500 italic">No parent linked</span>
              `}
            </td>

            <!-- Grade -->
            <td class="py-3.5 px-4 text-center text-xs font-bold">
              ${s.grade_level && s.grade_level.includes('Year 6') ? `
                <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 px-2.5 py-1 rounded-lg font-black inline-block">
                  🇧🇳 Year 6 (PSR)
                </span>
              ` : (s.grade_level && s.grade_level.includes('KG3') ? `
                <span class="bg-sky-500/20 text-sky-300 border border-sky-500/40 px-2.5 py-1 rounded-lg font-black inline-block">
                  ⭐ KG3
                </span>
              ` : `
                <span class="bg-slate-900 px-2.5 py-1 rounded-lg border border-slate-700 text-slate-400">
                  ${escapeHtml(s.grade_level || 'KG3')}
                </span>
              `)}
            </td>

            <!-- Quizzes -->
            <td class="py-3.5 px-4 text-center font-black text-indigo-400 font-mono">
              ${s.sessions_count || 0}
            </td>

            <!-- Score -->
            <td class="py-3.5 px-4 text-center">
              <span class="px-2 py-0.5 rounded-full text-xs font-black ${isHigh ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300'}">
                ${Math.round(s.avg_score || 0)}%
              </span>
            </td>

            <!-- Status with 1-click Toggle -->
            <td class="py-3.5 px-4 text-center">
              <button 
                onclick="toggleStudentStatus(${s.id})"
                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-black transition-all cursor-pointer ${
                  isInactive 
                    ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40 hover:bg-rose-500/30' 
                    : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-500/30'
                }"
                title="Click to toggle status (enable / disable account)"
              >
                <i class="fa-solid ${isInactive ? 'fa-toggle-off text-rose-400' : 'fa-toggle-on text-emerald-400'}"></i>
                <span>${isInactive ? 'Disabled' : 'Active'}</span>
              </button>
            </td>

            <!-- Actions -->
            <td class="py-3.5 px-4 text-right">
              <div class="flex items-center justify-end gap-1.5">
                <button 
                  onclick="openEditStudentModal(${s.id})"
                  class="bg-slate-700/80 hover:bg-slate-700 text-slate-200 text-xs font-bold py-1.5 px-2.5 rounded-lg border border-slate-600 transition-colors cursor-pointer"
                  title="Edit Student Profile"
                >
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button 
                  onclick="confirmDeleteStudent(${s.id}, '${escapeHtml(s.name)}')"
                  class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-xs font-bold py-1.5 px-2.5 rounded-lg border border-rose-500/30 transition-colors cursor-pointer"
                  title="Delete Student"
                >
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    // Toggle Active / Disabled Status
    async function toggleStudentStatus(id) {
      try {
        const resp = await fetch('../api/admin_kids.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'toggle_status', id })
        });
        const data = await resp.json();
        if (data.success) {
          showToast(data.message, 'success');
          fetchStudents();
        } else {
          showToast(data.error || 'Failed to toggle status.', 'error');
        }
      } catch (err) {
        console.error(err);
        showToast('Network error toggling student status.', 'error');
      }
    }

    // Quick Change PIN Modal Handlers
    function openChangePinModal(id, name, currentPin) {
      document.getElementById('pin-student-id').value = id;
      document.getElementById('pin-modal-title').textContent = `Change PIN: ${name}`;
      document.getElementById('pin-modal-input').value = currentPin || '1234';
      document.getElementById('pin-modal-alert').className = 'hidden';
      document.getElementById('pin-modal').classList.remove('hidden');
      document.getElementById('pin-modal-input').focus();
      document.getElementById('pin-modal-input').select();
    }

    function closePinModal() {
      document.getElementById('pin-modal').classList.add('hidden');
    }

    async function submitChangePin() {
      const id = parseInt(document.getElementById('pin-student-id').value);
      const pin = document.getElementById('pin-modal-input').value.trim();
      const alertBox = document.getElementById('pin-modal-alert');
      const btn = document.getElementById('btn-save-pin');

      if (!pin || pin.length < 3) {
        alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
        alertBox.textContent = 'Please enter a PIN of at least 3 digits.';
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Saving...';
      try {
        const resp = await fetch('../api/admin_kids.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'update_pin', id, pin_code: pin })
        });
        const data = await resp.json();
        if (data.success) {
          showToast(data.message, 'success');
          closePinModal();
          fetchStudents();
        } else {
          alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
          alertBox.textContent = data.error || 'Failed to update PIN.';
        }
      } catch (err) {
        console.error(err);
        alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
        alertBox.textContent = 'Network error updating PIN.';
      } finally {
        btn.disabled = false;
        btn.textContent = 'Update PIN';
      }
    }

    // Create / Edit Student Modal Handlers
    function openCreateStudentModal() {
      document.getElementById('student-modal-title').textContent = 'Add New Student Profile';
      document.getElementById('form-student-id').value = '';
      document.getElementById('form-student-name').value = '';
      document.getElementById('form-student-username').value = '';
      document.getElementById('form-student-pin').value = '1234';
      document.getElementById('form-student-grade').value = 'Kindergarten 3 (KG3)';
      document.getElementById('form-student-status').value = 'active';
      document.getElementById('form-student-parent').value = '';
      document.querySelector('input[name="student_avatar"][value="star_kid"]').checked = true;

      document.getElementById('student-modal-alert').className = 'hidden';
      document.getElementById('student-modal').classList.remove('hidden');
    }

    function openEditStudentModal(id) {
      const s = studentsData.find(x => x.id == id);
      if (!s) return;

      document.getElementById('student-modal-title').textContent = `Edit Student: ${s.name}`;
      document.getElementById('form-student-id').value = s.id;
      document.getElementById('form-student-name').value = s.name;
      document.getElementById('form-student-username').value = s.username || '';
      document.getElementById('form-student-pin').value = s.pin_code || '1234';
      document.getElementById('form-student-grade').value = s.grade_level || 'Kindergarten 3 (KG3)';
      document.getElementById('form-student-status').value = s.status || 'active';
      document.getElementById('form-student-parent').value = s.parent_id || '';

      const avRadio = document.querySelector(`input[name="student_avatar"][value="${s.avatar}"]`);
      if (avRadio) avRadio.checked = true;

      document.getElementById('student-modal-alert').className = 'hidden';
      document.getElementById('student-modal').classList.remove('hidden');
    }

    function closeStudentModal() {
      document.getElementById('student-modal').classList.add('hidden');
    }

    function suggestStudentUsername(name) {
      if (!document.getElementById('form-student-id').value) {
        const clean = name.toLowerCase().replace(/[^a-z0-9]/g, '');
        if (clean) {
          document.getElementById('form-student-username').value = clean;
        }
      }
    }

    async function handleSaveStudent(e) {
      e.preventDefault();
      const id = document.getElementById('form-student-id').value;
      const isEdit = !!id;

      const payload = {
        action: isEdit ? 'update' : 'create',
        id: id ? parseInt(id) : undefined,
        name: document.getElementById('form-student-name').value.trim(),
        username: document.getElementById('form-student-username').value.trim(),
        pin_code: document.getElementById('form-student-pin').value.trim(),
        grade_level: document.getElementById('form-student-grade').value,
        status: document.getElementById('form-student-status').value,
        parent_id: document.getElementById('form-student-parent').value ? parseInt(document.getElementById('form-student-parent').value) : null,
        avatar: document.querySelector('input[name="student_avatar"]:checked')?.value || 'star_kid'
      };

      const alertBox = document.getElementById('student-modal-alert');
      const btn = document.getElementById('btn-save-student');

      alertBox.className = 'hidden';
      btn.disabled = true;
      btn.textContent = 'Saving...';

      try {
        const resp = await fetch('../api/admin_kids.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const data = await resp.json();

        if (data.success) {
          showToast(data.message, 'success');
          closeStudentModal();
          fetchStudents();
        } else {
          alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
          alertBox.textContent = data.error || 'Failed to save student.';
        }
      } catch (err) {
        console.error(err);
        alertBox.className = 'mb-4 p-3 rounded-xl text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
        alertBox.textContent = 'Network error saving student.';
      } finally {
        btn.disabled = false;
        btn.textContent = 'Save Student Profile';
      }
    }

    async function confirmDeleteStudent(id, name) {
      if (!confirm(`Are you sure you want to permanently delete student ${name}? All their quiz session history will also be removed.`)) {
        return;
      }

      try {
        const resp = await fetch('../api/admin_kids.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', id })
        });
        const data = await resp.json();
        if (data.success) {
          showToast(data.message, 'success');
          fetchStudents();
        } else {
          showToast(data.error || 'Failed to delete student.', 'error');
        }
      } catch (err) {
        console.error(err);
        showToast('Network error deleting student.', 'error');
      }
    }

    // Initial Load
    document.addEventListener('DOMContentLoaded', () => {
      fetchStudents();
    });
  </script>

</body>
</html>
