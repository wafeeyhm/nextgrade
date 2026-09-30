<?php
// NextGrade - System Admin: Content Creator, Question Builder & Git Guide
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Developer & Content Guide - System Admin | NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/app.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body, html {
      background-color: #0b0f19 !important;
      background-image: 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, transparent 1.5px), 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, #0b0f19 1.5px) !important;
      color: #f1f5f9 !important;
      scroll-behavior: smooth;
    }
    .code-block {
      background: #030712;
      color: #e2e8f0;
      border-radius: 1rem;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      position: relative;
    }
    .copy-btn {
      position: absolute;
      top: 0.75rem;
      right: 0.75rem;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(4px);
      color: #fff;
      padding: 0.35rem 0.75rem;
      border-radius: 0.5rem;
      font-size: 0.75rem;
      font-weight: 700;
      transition: all 0.2s;
    }
    .copy-btn:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-1px);
    }
    .tree-view ul {
      margin-left: 1.25rem;
      border-left: 2px dashed #334155;
      padding-left: 0.75rem;
    }
    .tree-view li {
      margin: 0.35rem 0;
      position: relative;
    }
    .tree-view li::before {
      content: "";
      position: absolute;
      left: -0.75rem;
      top: 0.65rem;
      width: 0.5rem;
      height: 2px;
      background: #334155;
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
            <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Parents Accounts
          </a>
          <a href="kids.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-graduation-cap mr-1.5 text-slate-400"></i> Students & Kids
          </a>
          <a href="guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-book-open mr-1.5 text-slate-400"></i> System Guide
          </a>
          <a href="developer_guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-code mr-1.5 text-indigo-400"></i> Question & Dev Guide
          </a>
          <a href="profile.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-user-gear mr-1.5 text-slate-400"></i> My Profile
          </a>
        </div>
      </div>

      <!-- Admin Status & Actions -->
      <div class="flex items-center gap-3">
        <a 
          href="guide.php" 
          title="System Admin Handbook"
          class="bg-slate-700/70 hover:bg-slate-700 text-indigo-300 hover:text-white text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-book text-xs"></i>
          <span class="hidden sm:inline">System Guide</span>
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

  <!-- Live Database Console Output Modal -->
  <div id="seeder-modal" class="hidden fixed top-20 right-4 md:right-8 z-50 max-w-xl w-full bg-slate-900 border-2 border-slate-700 text-slate-100 rounded-3xl p-5 shadow-2xl">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-3">
      <div class="flex items-center gap-2 font-mono text-sm font-bold text-sky-400">
        <span id="console-pulse" class="animate-pulse">🟢</span>
        <span id="console-title">Database Output</span>
      </div>
      <button onclick="document.getElementById('seeder-modal').classList.add('hidden')" class="text-slate-400 hover:text-white font-black text-xs px-2.5 py-1 rounded-lg bg-slate-800">
        ✕ Close
      </button>
    </div>
    <pre id="seeder-output" class="font-mono text-xs text-emerald-400 max-h-60 overflow-y-auto whitespace-pre-wrap leading-relaxed p-3 bg-slate-950 rounded-xl">Console running...</pre>
  </div>

  <!-- Toast -->
  <div id="toast" class="fixed bottom-6 right-6 z-50 bg-indigo-600 text-white px-5 py-3 rounded-2xl shadow-2xl font-bold text-sm transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-2 pointer-events-none">
    <span id="toast-icon">✅</span>
    <span id="toast-msg">Copied!</span>
  </div>

  <!-- Main Content Container -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8 space-y-8">

    <!-- Hero Header Banner with Live DB Tools -->
    <div class="bg-gradient-to-r from-slate-800 via-indigo-950/60 to-slate-800 border border-slate-700/80 rounded-3xl p-6 md:p-8 shadow-xl flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-black uppercase tracking-wider mb-2">
          <i class="fa-solid fa-code"></i> Internal Content & Curriculum Engineer Manual
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Question Builder, Asset Placement & Git Guide</h1>
        <p class="text-sm font-semibold text-slate-400 mt-1 max-w-2xl">
          Everything you need to author new Kindergarten 3 (KG3) questions, generate JSON payloads, sync the database safely, and push commits to GitHub.
        </p>
      </div>

      <!-- Action Buttons for Admin Database Operations -->
      <div class="flex flex-wrap items-center gap-2 shrink-0">
        <button 
          onclick="runLiveSeeder()" 
          id="btn-run-seeder"
          class="bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2 cursor-pointer"
          title="Sync questions_bank.json into the MySQL database"
        >
          <span id="seeder-icon">🔄</span>
          <span id="seeder-label">Sync DB (seed.php)</span>
        </button>

        <button 
          onclick="runLiveTruncate()" 
          id="btn-run-truncate"
          class="bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/40 text-rose-300 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2 cursor-pointer"
          title="Truncate tables (Admin authorized only)"
        >
          <span id="truncate-icon">🗑️</span>
          <span id="truncate-label">Truncate DB</span>
        </button>

        <a 
          href="#builder" 
          class="bg-sky-500/20 hover:bg-sky-500/30 border border-sky-500/30 text-sky-300 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2"
        >
          <span>➕</span>
          <span>Question Builder</span>
        </a>
      </div>
    </div>

    <!-- Quick Navigation Anchor Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <a href="#structure" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">📁</div>
        <span class="block text-xs font-black text-white">Project Structure</span>
        <span class="text-[10px] text-slate-400 font-bold">Files & Folders</span>
      </a>

      <a href="#json-format" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">📋</div>
        <span class="block text-xs font-black text-white">JSON Schema</span>
        <span class="text-[10px] text-slate-400 font-bold">Payload Fields</span>
      </a>

      <a href="#builder" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">➕</div>
        <span class="block text-xs font-black text-white">Question Builder</span>
        <span class="text-[10px] text-slate-400 font-bold">Interactive Tool</span>
      </a>

      <a href="#git-workflow" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">🚀</div>
        <span class="block text-xs font-black text-white">Git Workflow</span>
        <span class="text-[10px] text-slate-400 font-bold">Commit & Push</span>
      </a>
    </div>

    <!-- Section 1: Project Structure -->
    <section id="structure" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-4">
      <h2 class="text-xl font-black text-white tracking-tight flex items-center gap-2">
        <span>📁</span> 1. NextGrade Core Directory Tree
      </h2>
      <p class="text-xs text-slate-400 font-semibold leading-relaxed">
        Understand where questions, images, API endpoints, and admin portal files live in the repository:
      </p>

      <div class="tree-view bg-slate-950 p-5 rounded-2xl border border-slate-800 font-mono text-xs text-slate-300">
        <ul>
          <li><span class="text-indigo-400 font-bold">📁 admin/</span> <span class="text-slate-500">— System Admin Portal (Dark Mode)</span>
            <ul>
              <li><span class="text-emerald-400">📄 index.php</span> <span class="text-slate-500">— Admin Dashboard</span></li>
              <li><span class="text-emerald-400">📄 parents.php</span> <span class="text-slate-500">— Parents CRUD Management</span></li>
              <li><span class="text-emerald-400">📄 kids.php</span> <span class="text-slate-500">— Student & Kid Profiles</span></li>
              <li><span class="text-emerald-400">📄 guide.php</span> <span class="text-slate-500">— System Operations Handbook</span></li>
              <li><span class="text-emerald-400">📄 developer_guide.php</span> <span class="text-slate-500">— This Question & Git Guide</span></li>
              <li><span class="text-emerald-400">📄 profile.php</span> <span class="text-slate-500">— Admin Profile & Password Settings</span></li>
            </ul>
          </li>
          <li><span class="text-indigo-400 font-bold">📁 api/</span> <span class="text-slate-500">— JSON APIs for Quizzes, Parents, & Admins</span></li>
          <li><span class="text-indigo-400 font-bold">📁 data/</span>
            <ul>
              <li><span class="text-amber-400 font-bold">📄 questions_bank.json</span> <span class="text-slate-400">— Master repository of ~1,350+ KG3 questions</span></li>
            </ul>
          </li>
          <li><span class="text-indigo-400 font-bold">📁 images/questions/</span> <span class="text-slate-500">— Educational photos organized by subject</span></li>
          <li><span class="text-emerald-400 font-bold">📄 parent.php</span> <span class="text-slate-500">— Parent Portal (Progress, GAP Analysis, Kids CRUD)</span></li>
          <li><span class="text-emerald-400 font-bold">📄 index.php</span> <span class="text-slate-500">— Student Child Login Gate & Dashboard</span></li>
          <li><span class="text-emerald-400 font-bold">📄 quiz.php</span> <span class="text-slate-500">— Interactive Quiz Runner (KG3 exclusive)</span></li>
          <li><span class="text-emerald-400 font-bold">📄 worksheet.php</span> <span class="text-slate-500">— Printable 3-Line Handwriting Worksheets</span></li>
          <li><span class="text-emerald-400 font-bold">📄 seed.php</span> <span class="text-slate-500">— Syncs questions_bank.json into MySQL</span></li>
        </ul>
      </div>
    </section>

    <!-- Section 2: Interactive Question Builder Tool -->
    <section id="builder" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center justify-between flex-wrap gap-2">
        <div>
          <h2 class="text-xl font-black text-white tracking-tight flex items-center gap-2">
            <span>➕</span> 2. Interactive KG3 Question Builder
          </h2>
          <p class="text-xs text-slate-400 font-semibold mt-0.5">
            Fill in the fields below to instantly generate a validated JSON object ready to paste into <code class="text-indigo-300">data/questions_bank.json</code>.
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Builder Form -->
        <div class="space-y-4 text-xs font-semibold">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Subject</label>
              <select id="gen-subject" onchange="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
                <option value="bahasa_melayu">Bahasa Melayu</option>
                <option value="english">English</option>
                <option value="maths">Mathematics</option>
                <option value="science">Science</option>
                <option value="ict">ICT</option>
              </select>
            </div>

            <div>
              <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Topic ID</label>
              <input type="text" id="gen-topic" value="bm_suku_kata" oninput="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
            </div>
          </div>

          <div>
            <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Question Prompt Text</label>
            <input type="text" id="gen-text" value="Pilih suku kata pertama bagi gambar: BOLA" oninput="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Question Type</label>
              <select id="gen-type" onchange="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
                <option value="multiple_choice">Multiple Choice</option>
                <option value="fill_blank">Fill in the Blank</option>
                <option value="syllable_split">Syllable Split</option>
                <option value="clock_analog">Analog Clock</option>
              </select>
            </div>

            <div>
              <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Language</label>
              <select id="gen-lang" onchange="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
                <option value="ms">ms (Malay TTS)</option>
                <option value="en">en (English TTS)</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Options (comma-separated)</label>
            <input type="text" id="gen-options" value="bo, ba, bi, bu" oninput="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
          </div>

          <div>
            <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Correct Answer</label>
            <input type="text" id="gen-correct" value="bo" oninput="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
          </div>

          <div>
            <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Educational Hint</label>
            <input type="text" id="gen-hint" value="Bola bermula dengan huruf B dan O: bo-la." oninput="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
          </div>

          <div>
            <label class="block text-slate-400 uppercase text-[10px] font-black mb-1">Image URL (Optional)</label>
            <input type="text" id="gen-image" value="images/questions/bm/bola.webp" oninput="updateGeneratedJson()" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-2.5 text-white font-bold">
          </div>
        </div>

        <!-- Generated JSON Output -->
        <div class="flex flex-col">
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-black text-indigo-400 uppercase tracking-wider">Generated JSON Output:</span>
            <button onclick="copyGeneratedCode()" id="btn-copy-gen" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-1.5 px-3 rounded-lg flex items-center gap-1">
              <i class="fa-regular fa-copy"></i> Copy JSON
            </button>
          </div>
          <pre id="gen-output" class="code-block flex-1 p-4 text-xs font-mono text-emerald-400 border border-slate-800 overflow-y-auto max-h-[460px]"></pre>
        </div>

      </div>
    </section>

    <!-- Section 3: Git Workflow & Sync -->
    <section id="git-workflow" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-4">
      <h2 class="text-xl font-black text-white tracking-tight flex items-center gap-2">
        <span>🚀</span> 3. Git Workflow & Local Database Sync
      </h2>
      <p class="text-xs text-slate-400 font-semibold leading-relaxed">
        After adding questions to <code class="text-indigo-300">data/questions_bank.json</code>, follow these steps to test locally and push to the remote GitHub repository:
      </p>

      <div class="space-y-3 font-mono text-xs">
        <div class="code-block p-4 border border-slate-800">
          <button class="copy-btn" onclick="copySnippet('snippet-git-1', this)">Copy</button>
          <pre id="snippet-git-1" class="text-slate-300"># Step 1: Sync JSON changes into the local MySQL database
php seed.php

# Step 2: Run the automated test suite to ensure zero broken questions
php scripts/test_all_features.php
php scripts/test_profile_and_guides.php

# Step 3: Check git status and stage modified files
git status
git add data/questions_bank.json images/questions/

# Step 4: Commit with a descriptive message
git commit -m "feat: add 20 new Kindergarten 3 (KG3) questions to Science"

# Step 5: Push cleanly to main
git push origin main</pre>
        </div>
      </div>
    </section>

  </main>

  <footer class="mt-auto border-t border-slate-800 py-6 text-center text-xs font-bold text-slate-500">
    NextGrade Internal Developer Handbook • Content Authoring System v2.0
  </footer>

  <script>
    function updateGeneratedJson() {
      const subject = document.getElementById('gen-subject').value;
      const topic = document.getElementById('gen-topic').value.trim();
      const text = document.getElementById('gen-text').value.trim();
      const type = document.getElementById('gen-type').value;
      const lang = document.getElementById('gen-lang').value;
      const options = document.getElementById('gen-options').value.split(',').map(s => s.trim()).filter(Boolean);
      const correct = document.getElementById('gen-correct').value.trim();
      const hint = document.getElementById('gen-hint').value.trim();
      const image = document.getElementById('gen-image').value.trim();

      const obj = {
        topic_id: topic,
        subject_id: subject,
        question_text: text,
        question_audio: text,
        lang: lang,
        question_type: type,
        image_url: image || null,
        options: options,
        correct_answer: correct,
        hint_text: hint,
        hint_audio: hint
      };

      document.getElementById('gen-output').textContent = JSON.stringify(obj, null, 2);
    }

    function copyGeneratedCode() {
      const text = document.getElementById('gen-output').textContent;
      navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('btn-copy-gen');
        btn.innerHTML = '<i class="fa-solid fa-check text-emerald-400"></i> Copied!';
        showToast('Generated JSON copied to clipboard!');
        setTimeout(() => {
          btn.innerHTML = '<i class="fa-regular fa-copy"></i> Copy JSON';
        }, 2000);
      });
    }

    function copySnippet(id, btn) {
      const el = document.getElementById(id);
      navigator.clipboard.writeText(el.innerText || el.textContent).then(() => {
        const orig = btn.innerText;
        btn.innerText = '✓ Copied!';
        showToast('Command snippet copied!');
        setTimeout(() => { btn.innerText = orig; }, 2000);
      });
    }

    function showToast(msg) {
      const toast = document.getElementById('toast');
      const toastMsg = document.getElementById('toast-msg');
      toastMsg.textContent = msg;
      toast.classList.remove('translate-y-20', 'opacity-0');
      toast.classList.add('translate-y-0', 'opacity-100');
      setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
      }, 2500);
    }

    async function runLiveSeeder() {
      const btn = document.getElementById('btn-run-seeder');
      const icon = document.getElementById('seeder-icon');
      const label = document.getElementById('seeder-label');
      const modal = document.getElementById('seeder-modal');
      const output = document.getElementById('seeder-output');

      btn.disabled = true;
      icon.classList.add('animate-spin');
      label.textContent = 'Syncing...';
      modal.classList.remove('hidden');
      output.textContent = '⏳ Executing seed.php on local server...';

      try {
        const resp = await fetch('../seed.php');
        const text = await resp.text();
        output.textContent = text;
        showToast('Database synced successfully!');
      } catch (err) {
        output.textContent = '❌ Error executing seed.php: ' + err.message;
        showToast('Seeding failed.');
      } finally {
        btn.disabled = false;
        icon.classList.remove('animate-spin');
        label.textContent = 'Sync DB (seed.php)';
      }
    }

    async function runLiveTruncate() {
      const confirmed = confirm("⚠️ DANGER: ARE YOU SURE YOU WANT TO TRUNCATE ALL TABLES?\n\nThis permanently deletes all questions, revisions, quiz sessions, and students.");
      if (!confirmed) return;

      const btn = document.getElementById('btn-run-truncate');
      const icon = document.getElementById('truncate-icon');
      const label = document.getElementById('truncate-label');
      const modal = document.getElementById('seeder-modal');
      const output = document.getElementById('seeder-output');

      btn.disabled = true;
      icon.classList.add('animate-spin');
      label.textContent = 'Truncating...';
      modal.classList.remove('hidden');
      output.textContent = '⏳ Executing truncate.php...';

      try {
        const resp = await fetch('../truncate.php');
        const text = await resp.text();
        output.textContent = text;
        showToast('Database truncated successfully!');
      } catch (err) {
        output.textContent = '❌ Error executing truncate.php: ' + err.message;
        showToast('Truncate failed.');
      } finally {
        btn.disabled = false;
        icon.classList.remove('animate-spin');
        label.textContent = 'Truncate DB';
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      updateGeneratedJson();
    });
  </script>

</body>
</html>
