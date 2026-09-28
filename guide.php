<?php
// NextGrade - Creator & Developer Guide
// Complete interactive guide for adding, modifying, removing questions, image placement, folder structures, and Git workflow.
require_once __DIR__ . '/db.php';
session_start();

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? 'Superstar';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creator & Developer Guide - NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <script src="js/sounds.js"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
    .code-block {
      background: #0f172a;
      color: #e2e8f0;
      border-radius: 1rem;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      position: relative;
    }
    .copy-btn {
      position: absolute;
      top: 0.75rem;
      right: 0.75rem;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(4px);
      color: #fff;
      padding: 0.35rem 0.75rem;
      border-radius: 0.5rem;
      font-size: 0.75rem;
      font-weight: 700;
      transition: all 0.2s;
    }
    .copy-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-1px);
    }
    .sidebar-link.active {
      background-color: #e0f2fe;
      color: #0369a1;
      font-weight: 800;
      border-left: 4px solid #0284c7;
    }
    .tree-view ul {
      margin-left: 1.25rem;
      border-left: 2px dashed #cbd5e1;
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
      background: #cbd5e1;
    }
  </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl font-bold text-sm transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-2 pointer-events-none">
  <span id="toast-icon">✅</span>
  <span id="toast-msg">Copied to clipboard!</span>
</div>

<div class="max-w-7xl mx-auto p-4 md:p-8 flex flex-col gap-6">

  <!-- Top Header Bar -->
  <header class="bg-white px-6 py-4 rounded-3xl shadow-sm border-2 border-slate-200 flex flex-wrap items-center justify-between gap-4 sticky top-4 z-40 backdrop-blur-md bg-white/95">
    <div class="flex items-center gap-3">
      <a 
        href="index.php" 
        onclick="SoundEffects.playPop();"
        class="btn-chunky btn-white text-xs md:text-sm py-2 px-3.5 rounded-xl flex items-center gap-1.5"
      >
        <span>🏠</span>
        <span class="font-black">Home</span>
      </a>
      <a 
        href="parent.php" 
        onclick="SoundEffects.playPop();"
        class="btn-chunky btn-white text-xs md:text-sm py-2 px-3.5 rounded-xl flex items-center gap-1.5"
      >
        <span>👨‍👩‍👧</span>
        <span class="font-black">Parent Portal</span>
      </a>
      <div class="hidden sm:block border-l-2 border-slate-200 h-6"></div>
      <div>
        <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
          <span>🛠️</span> Content & Developer Guide
        </h1>
        <p class="text-xs font-bold text-slate-400">Manage Questions, Organize Images & Push to Git</p>
      </div>
    </div>

    <!-- Quick Action: Trigger Live Seeder & Truncate -->
    <div class="flex items-center gap-2">
      <button 
        onclick="runLiveSeeder()"
        id="btn-run-seeder"
        class="btn-chunky btn-primary text-xs md:text-sm py-2 px-4 rounded-xl flex items-center gap-2 shadow-sm"
        title="Sync questions_bank.json into the MySQL database"
      >
        <span id="seeder-icon">🔄</span>
        <span id="seeder-label">Sync DB (seed.php)</span>
      </button>
      <button 
        onclick="runLiveTruncate()"
        id="btn-run-truncate"
        class="btn-chunky bg-rose-600 hover:bg-rose-700 text-white text-xs md:text-sm py-2 px-4 rounded-xl flex items-center gap-2 shadow-sm active:translate-y-1 transition-all"
        title="Truncate all tables and delete everything in MySQL database"
      >
        <span id="truncate-icon">🗑️</span>
        <span id="truncate-label">Truncate DB</span>
      </button>
      <a 
        href="#generator" 
        class="btn-chunky btn-amber text-xs md:text-sm py-2 px-4 rounded-xl flex items-center gap-2 shadow-sm"
      >
        <span>➕</span>
        <span>Question Builder</span>
      </a>
    </div>
  </header>

  <!-- Live Database Console Output Modal / Collapsible Box -->
  <div id="seeder-modal" class="hidden bg-slate-900 border-2 border-slate-700 text-slate-100 rounded-3xl p-5 shadow-2xl relative">
    <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-3">
      <div class="flex items-center gap-2 font-mono text-sm font-bold text-sky-400">
        <span id="console-pulse" class="animate-pulse">🟢</span>
        <span id="console-title">Database Output</span>
      </div>
      <button onclick="document.getElementById('seeder-modal').classList.add('hidden')" class="text-slate-400 hover:text-white font-black text-sm px-2 py-1 rounded-lg bg-slate-800">
        ✕ Close
      </button>
    </div>
    <pre id="seeder-output" class="font-mono text-xs text-emerald-400 max-h-60 overflow-y-auto whitespace-pre-wrap leading-relaxed p-2 bg-slate-950 rounded-xl">Console running...</pre>
  </div>

  <!-- Hero Banner -->
  <div class="bg-gradient-to-r from-sky-500 via-indigo-600 to-purple-600 rounded-[2.5rem] p-6 md:p-10 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="relative z-10 max-w-2xl">
      <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-3.5 py-1 rounded-full text-xs font-black tracking-wider uppercase mb-3">
        <span>📖</span> Comprehensive System Handbook
      </div>
      <h2 class="text-3xl md:text-5xl font-black mb-3 leading-tight">
        NextGrade Question & Git Workflow
      </h2>
      <p class="text-sky-100 text-base md:text-lg font-bold leading-relaxed">
        Everything you need to create questions, update existing ones, safely place educational photos, sync the local database, and push clean commits to GitHub.
      </p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="relative z-10 grid grid-cols-2 gap-3 shrink-0">
      <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center">
        <span class="block text-2xl md:text-3xl font-black text-white">1,497+</span>
        <span class="text-xs font-bold text-sky-200 uppercase">Questions Bank</span>
      </div>
      <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center">
        <span class="block text-2xl md:text-3xl font-black text-amber-300">27</span>
        <span class="text-xs font-bold text-sky-200 uppercase">Topics Covered</span>
      </div>
      <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center">
        <span class="block text-2xl md:text-3xl font-black text-emerald-300">5</span>
        <span class="text-xs font-bold text-sky-200 uppercase">Core Subjects</span>
      </div>
      <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center">
        <span class="block text-2xl md:text-3xl font-black text-pink-300">625+</span>
        <span class="text-xs font-bold text-sky-200 uppercase">Real Photos</span>
      </div>
    </div>

    <!-- Decorative Glow Circles -->
    <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
  </div>

  <!-- Main Content Layout (Sidebar Navigation + Guide Sections) -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- Sticky Navigation Sidebar -->
    <aside class="lg:col-span-3 bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm sticky top-28 hidden lg:flex flex-col gap-1 text-sm font-bold text-slate-600">
      <span class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2 px-3">Table of Contents</span>
      <a href="#architecture" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>🏛️</span> 1. System Architecture
      </a>
      <a href="#folder-structure" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>📁</span> 2. Folder Structure
      </a>
      <a href="#images-guide" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>🖼️</span> 3. Where to Put Images
      </a>
      <a href="#json-schema" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>📜</span> 4. Question JSON Format
      </a>
      <a href="#add-questions" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>➕</span> 5. How to ADD Questions
      </a>
      <a href="#edit-questions" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>✏️</span> 6. How to EDIT Questions
      </a>
      <a href="#remove-questions" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>🗑️</span> 7. How to REMOVE Questions
      </a>
      <a href="#sync-db" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>🔄</span> 8. Database Sync (seed.php)
      </a>
      <a href="#git-workflow" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>🚀</span> 9. Git Push Workflow
      </a>
      <a href="#generator" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>⚡</span> 10. Interactive Builder
      </a>
      <a href="#cheatsheet" class="sidebar-link px-3 py-2 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center gap-2">
        <span>📋</span> 11. Topics & IDs Cheatsheet
      </a>
    </aside>

    <!-- Guide Articles & Interactive Components -->
    <main class="lg:col-span-9 flex flex-col gap-8">

      <!-- ========================================================================= -->
      <!-- SECTION 1: ARCHITECTURE -->
      <!-- ========================================================================= -->
      <section id="architecture" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-sky-100 text-sky-700 rounded-2xl">🏛️</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">1. System Architecture & Data Flow</h3>
            <p class="text-xs font-bold text-slate-400">How NextGrade manages questions between file and database</p>
          </div>
        </div>

        <p class="text-slate-600 font-bold mb-4 leading-relaxed">
          NextGrade uses a reliable <span class="text-sky-600 font-black">Single Source of Truth</span> pattern. All 1,497+ questions live permanently in a JSON repository file, which gets compiled directly into MySQL when you run the seeder:
        </p>

        <!-- 3-Tier Diagram -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-sky-50 border-2 border-sky-200 rounded-2xl p-5 flex flex-col items-center text-center">
            <span class="text-4xl mb-2">📄</span>
            <span class="text-xs font-black uppercase text-sky-600 tracking-wider">Step 1: File Storage</span>
            <h4 class="text-base font-black text-slate-800 mb-1">data/questions_bank.json</h4>
            <p class="text-xs text-slate-500 font-bold">You add, edit, or delete questions here. This file is tracked in Git.</p>
          </div>

          <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-5 flex flex-col items-center text-center relative">
            <div class="hidden md:block absolute -left-4 top-1/2 -translate-y-1/2 text-xl font-black text-slate-400">➔</div>
            <span class="text-4xl mb-2">🔄</span>
            <span class="text-xs font-black uppercase text-amber-600 tracking-wider">Step 2: Sync Engine</span>
            <h4 class="text-base font-black text-slate-800 mb-1">seed.php</h4>
            <p class="text-xs text-slate-500 font-bold">Loads the JSON file and syncs it into MySQL tables cleanly and instantly.</p>
            <div class="hidden md:block absolute -right-4 top-1/2 -translate-y-1/2 text-xl font-black text-slate-400">➔</div>
          </div>

          <div class="bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-5 flex flex-col items-center text-center">
            <span class="text-4xl mb-2">🎮</span>
            <span class="text-xs font-black uppercase text-emerald-600 tracking-wider">Step 3: Frontend Runners</span>
            <h4 class="text-base font-black text-slate-800 mb-1">quiz.php & API</h4>
            <p class="text-xs text-slate-500 font-bold">Children play 10 randomized questions with sound, photos, and live grading.</p>
          </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-sky-500 p-4 rounded-xl text-sm font-bold text-sky-900 flex items-start gap-3">
          <span class="text-xl">💡</span>
          <div>
            <span class="font-black">Why this is great for Git:</span> Anyone who clones your Git repository doesn't need to manually import SQL dumps. They simply run <code class="bg-white px-2 py-0.5 rounded text-sky-700 font-mono">seed.php</code> once, and their local database is 100% up to date with all subjects, topics, and questions!
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 2: FOLDER STRUCTURE -->
      <!-- ========================================================================= -->
      <section id="folder-structure" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-purple-100 text-purple-700 rounded-2xl">📁</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">2. Repository Folder Structure</h3>
            <p class="text-xs font-bold text-slate-400">Where each file and asset is located in <code class="text-purple-600 font-mono">c:\xampp\htdocs\nextgrade\</code></p>
          </div>
        </div>

        <p class="text-slate-600 font-bold mb-4 leading-relaxed">
          Here is the complete folder layout of the NextGrade repository. The two most critical folders you will work with are <code class="text-sky-600 font-black">data/</code> (for question JSON) and <code class="text-emerald-600 font-black">images/</code> (for question pictures):
        </p>

        <div class="tree-view bg-slate-900 text-slate-200 p-6 rounded-2xl font-mono text-sm leading-relaxed overflow-x-auto">
          <div class="text-sky-400 font-bold">nextgrade/ (Project Root)</div>
          <ul>
            <li>
              <span class="text-amber-400 font-bold">📁 data/</span> <span class="text-slate-400 text-xs">— Core Question Bank Storage</span>
              <ul>
                <li><span class="text-emerald-300 font-bold">📄 questions_bank.json</span> <span class="text-sky-300 text-xs">⭐ ALL 1,497 questions live here!</span></li>
              </ul>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📁 images/</span> <span class="text-slate-400 text-xs">— Categorized Educational Photos & Media</span>
              <ul>
                <li>📁 <span class="text-teal-300">kenderaan/</span> <span class="text-slate-400 text-xs">(Cars, planes, boats: kereta.jpg, bas.jpg, etc.)</span></li>
                <li>📁 <span class="text-teal-300">binatang/</span> <span class="text-slate-400 text-xs">(Animals 2 vs 4 legs: kucing.jpg, ayam.jpg, etc.)</span></li>
                <li>📁 <span class="text-teal-300">suku-kata/</span> <span class="text-slate-400 text-xs">(Syllable flashcards: baju.jpg, bola.jpg, etc.)</span></li>
                <li>📁 <span class="text-teal-300">ini-itu/</span> <span class="text-slate-400 text-xs">(Demonstrative near/far object photos)</span></li>
                <li>📁 <span class="text-teal-300">science/</span> <span class="text-slate-400 text-xs">(Sink/float items, planets, materials, pollution, plants)</span></li>
                <li>📁 <span class="text-teal-300">ict/</span> <span class="text-slate-400 text-xs">(Computer peripherals & storage drives)</span></li>
                <li>📁 <span class="text-teal-300">english/</span> <span class="text-slate-400 text-xs">(Phonics blending, pronouns, demonstratives)</span></li>
                <li>📁 <span class="text-teal-300">maths/</span> <span class="text-slate-400 text-xs">(Analog clocks & math counting illustrations)</span></li>
              </ul>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📁 api/</span> <span class="text-slate-400 text-xs">— Backend REST API (Returns JSON)</span>
              <ul>
                <li>📄 quiz.php <span class="text-slate-400 text-xs">(Fetches 10 random questions, checks answers, records scores)</span></li>
                <li>📄 subjects.php <span class="text-slate-400 text-xs">(Lists 5 subjects with question counts)</span></li>
                <li>📄 topics.php <span class="text-slate-400 text-xs">(Lists topics per subject)</span></li>
                <li>📄 parent.php <span class="text-slate-400 text-xs">(Analytics data for parent portal)</span></li>
              </ul>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📁 scripts/</span> <span class="text-slate-400 text-xs">— Automation & Python Batch Tools</span>
              <ul>
                <li>📄 build_question_bank.py <span class="text-slate-400 text-xs">(Programmatic generator for full question banks)</span></li>
                <li>📄 scrape_real_images.py <span class="text-slate-400 text-xs">(Fetches Wikimedia real photos)</span></li>
              </ul>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📁 css/ & js/</span> <span class="text-slate-400 text-xs">— Styling, Chunky Button UI & TTS Audio</span>
              <ul>
                <li>📄 css/app.css <span class="text-slate-400 text-xs">(Tailwind styling & chunky buttons)</span></li>
                <li>📄 js/sounds.js <span class="text-slate-400 text-xs">(Web Audio API pops, victory fanfare, star jingles)</span></li>
                <li>📄 js/speech.js <span class="text-slate-400 text-xs">(Web Speech API for Malay & English read-aloud)</span></li>
              </ul>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📄 index.php</span> <span class="text-slate-400 text-xs">— Main student homepage & avatar onboarding</span>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📄 quiz.php</span> <span class="text-slate-400 text-xs">— Interactive 10-question quiz game runner</span>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📄 revision.php</span> <span class="text-slate-400 text-xs">— 5-minute interactive flashcard guide</span>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📄 worksheet.php</span> <span class="text-slate-400 text-xs">— Printable PDF-style handwriting worksheets</span>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📄 parent.php</span> <span class="text-slate-400 text-xs">— Parent insights & session history</span>
            </li>
            <li>
              <span class="text-emerald-400 font-bold">📄 seed.php</span> <span class="text-sky-300 text-xs">⭐ Re-seeds MySQL from questions_bank.json</span>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📄 db.php</span> <span class="text-slate-400 text-xs">— Database PDO credentials & BASE_URL</span>
            </li>
            <li>
              <span class="text-amber-400 font-bold">📄 guide.php</span> <span class="text-sky-300 text-xs">⭐ This developer & content guide!</span>
            </li>
          </ul>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 3: WHERE TO PUT IMAGES -->
      <!-- ========================================================================= -->
      <section id="images-guide" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-emerald-100 text-emerald-700 rounded-2xl">🖼️</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">3. Where to Put Images & Best Practices</h3>
            <p class="text-xs font-bold text-slate-400">Rules for adding educational photos without breaking the layout</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div class="bg-slate-50 border-2 border-slate-200 p-5 rounded-2xl">
            <h4 class="font-black text-slate-800 text-base mb-2 flex items-center gap-2">
              <span>📂</span> Target Subdirectories
            </h4>
            <p class="text-xs font-bold text-slate-500 mb-3">Place image files inside the matching subject folder:</p>
            <ul class="text-xs font-mono space-y-2 text-slate-700">
              <li class="bg-white p-2 rounded-lg border border-slate-200"><strong class="text-sky-600">images/science/</strong> — sink_stone.jpg, anchor.jpg, sun.jpg</li>
              <li class="bg-white p-2 rounded-lg border border-slate-200"><strong class="text-sky-600">images/ict/</strong> — monitor.jpg, pendrive.jpg, keyboard.jpg</li>
              <li class="bg-white p-2 rounded-lg border border-slate-200"><strong class="text-sky-600">images/kenderaan/</strong> — kereta.jpg, kapal_terbang.jpg</li>
              <li class="bg-white p-2 rounded-lg border border-slate-200"><strong class="text-sky-600">images/binatang/</strong> — kucing.jpg, ayam.jpg, gajah.jpg</li>
              <li class="bg-white p-2 rounded-lg border border-slate-200"><strong class="text-sky-600">images/suku-kata/</strong> — baju.jpg, bola.jpg, lori.jpg</li>
              <li class="bg-white p-2 rounded-lg border border-slate-200"><strong class="text-sky-600">images/english/</strong> — apple.jpg, chair.jpg, elephant.jpg</li>
              <li class="bg-white p-2 rounded-lg border border-slate-200"><strong class="text-sky-600">images/maths/</strong> — clock_3_00.jpg, clock_6_30.jpg</li>
            </ul>
          </div>

          <div class="bg-slate-50 border-2 border-slate-200 p-5 rounded-2xl flex flex-col justify-between">
            <div>
              <h4 class="font-black text-slate-800 text-base mb-2 flex items-center gap-2">
                <span>📏</span> Technical Image Requirements
              </h4>
              <ul class="text-xs font-bold text-slate-600 space-y-2.5">
                <li class="flex items-start gap-2">
                  <span class="text-emerald-500">✓</span>
                  <span><strong>Format:</strong> Use <code class="text-sky-600">.jpg</code>, <code class="text-sky-600">.png</code>, or <code class="text-sky-600">.webp</code>.</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-emerald-500">✓</span>
                  <span><strong>Naming:</strong> Always all-lowercase, separated by underscores (e.g. <code class="text-sky-600">float_wood.jpg</code>, not <code class="text-red-500">Float Wood 2.JPG</code>).</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-emerald-500">✓</span>
                  <span><strong>Dimensions:</strong> Around <strong>500×500 px</strong> (Square) or <strong>600×450 px</strong> (4:3 ratio).</span>
                </li>
                <li class="flex items-start gap-2">
                  <span class="text-emerald-500">✓</span>
                  <span><strong>File Size:</strong> Keep under <strong>200 KB</strong> so tablets load questions instantaneously.</span>
                </li>
              </ul>
            </div>

            <div class="mt-4 bg-red-50 border border-red-200 p-3 rounded-xl text-xs font-bold text-red-700">
              <span class="font-black">🚫 Pedagogical Golden Rule:</span> Real photographs preferred! <strong>NEVER</strong> use an image with text labels that spoil the correct answer (e.g., an image of a printer that has the word "PRINTER" printed in big letters across the middle).
            </div>
          </div>
        </div>

        <div class="bg-amber-50 border border-amber-300 p-4 rounded-2xl text-xs font-bold text-amber-900">
          <span class="font-black text-sm">💡 How to write the path in the JSON:</span><br>
          Always write the path relative to the NextGrade root: <code class="bg-white px-2 py-0.5 rounded text-amber-800 font-mono">"image_url": "images/science/anchor.jpg"</code>.<br>
          <em>Do NOT write <code class="text-red-600 font-mono">"c:/xampp/..."</code> or <code class="text-red-600 font-mono">"/nextgrade/images/..."</code></em>. The API in <code class="font-mono">api/quiz.php</code> will automatically prepend the correct <code class="font-mono">BASE_URL</code>!
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 4: QUESTION JSON SCHEMA -->
      <!-- ========================================================================= -->
      <section id="json-schema" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-sky-100 text-sky-700 rounded-2xl">📜</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">4. Question JSON Data Structure</h3>
            <p class="text-xs font-bold text-slate-400">All fields explained with standard types</p>
          </div>
        </div>

        <p class="text-slate-600 font-bold mb-4 text-sm leading-relaxed">
          Every single question in <code class="text-sky-600 font-mono font-black">data/questions_bank.json</code> is a JSON object with these exact keys:
        </p>

        <!-- JSON Table -->
        <div class="overflow-x-auto mb-6">
          <table class="w-full text-left text-xs font-bold border-collapse">
            <thead>
              <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] border-b-2 border-slate-200">
                <th class="p-3">Field Key</th>
                <th class="p-3">Type</th>
                <th class="p-3">Example Value</th>
                <th class="p-3">Purpose & Description</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
              <tr>
                <td class="p-3 font-mono text-sky-600">topic_id</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"sci_sink_float"</td>
                <td class="p-3">Exact ID from the 27 topics list (e.g. <code>bm_suku_kata</code>, <code>ict_parts</code>).</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">subject_id</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"science"</td>
                <td class="p-3">Subject ID: <code>bahasa_melayu</code>, <code>maths</code>, <code>english</code>, <code>science</code>, <code>ict</code>.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">question_text</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"Will a heavy metal anchor sink or float?"</td>
                <td class="p-3">The question prompt displayed on the card. Short & friendly for K3!</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">question_audio</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"Will a heavy metal anchor sink or float?"</td>
                <td class="p-3">Read aloud by the Web Speech TTS engine when the question loads.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">lang</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"en" / "ms"</td>
                <td class="p-3">Voice synthesis accent: <code>"ms"</code> for Malay, <code>"en"</code> for all others.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">question_type</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"multiple_choice"</td>
                <td class="p-3"><code>multiple_choice</code>, <code>syllable_split</code>, <code>ordering</code>, <code>clock_analog</code>.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">image_url</td>
                <td class="p-3 font-mono text-purple-600">string|null</td>
                <td class="p-3 font-mono text-slate-600">"images/science/anchor.jpg"</td>
                <td class="p-3">Relative image path, or <code>null</code> if text/emoji based.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">passage</td>
                <td class="p-3 font-mono text-purple-600">string|null</td>
                <td class="p-3 font-mono text-slate-600">null</td>
                <td class="p-3">Story passage text (used for Reading Comprehension topics) or <code>null</code>.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">options_json</td>
                <td class="p-3 font-mono text-purple-600">string (escaped)</td>
                <td class="p-3 font-mono text-slate-600">"[\"Sink\", \"Float\"]"</td>
                <td class="p-3">Stringified JSON array of choices for the child to tap.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">correct_answer</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"Sink"</td>
                <td class="p-3">Must EXACTLY match one of the items inside <code>options_json</code>.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">hint_text</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"An anchor is made of heavy iron!"</td>
                <td class="p-3">Warm clue shown when the student clicks the 💡 Hint button.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">hint_audio</td>
                <td class="p-3 font-mono text-purple-600">string</td>
                <td class="p-3 font-mono text-slate-600">"An anchor is heavy and drops to the seabed."</td>
                <td class="p-3">TTS voice prompt for the hint clue.</td>
              </tr>
              <tr>
                <td class="p-3 font-mono text-sky-600">meta_data_json</td>
                <td class="p-3 font-mono text-purple-600">string|null</td>
                <td class="p-3 font-mono text-slate-600">null</td>
                <td class="p-3">Optional extra parameters (e.g. clock hands angle) or <code>null</code>.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Sample Code Block -->
        <div class="code-block p-5 text-xs">
          <button class="copy-btn" onclick="copyCode('sample-q-json', this)">Copy JSON</button>
          <pre id="sample-q-json" class="overflow-x-auto text-emerald-300 leading-relaxed font-mono">
{
  "topic_id": "sci_sink_float",
  "subject_id": "science",
  "question_text": "Will a heavy iron ship anchor sink or float in water?",
  "question_audio": "Will a heavy iron ship anchor sink or float in water?",
  "lang": "en",
  "question_type": "multiple_choice",
  "image_url": "images/science/anchor.jpg",
  "passage": null,
  "options_json": "[\"Sink\", \"Float\"]",
  "correct_answer": "Sink",
  "hint_text": "Heavy solid iron drops right down to the deep sea floor!",
  "hint_audio": "Iron is very heavy and sinks to the bottom.",
  "meta_data_json": null
}</pre>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 5: HOW TO ADD QUESTIONS -->
      <!-- ========================================================================= -->
      <section id="add-questions" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-amber-100 text-amber-700 rounded-2xl">➕</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">5. How to ADD Questions (Step-by-Step)</h3>
            <p class="text-xs font-bold text-slate-400">Complete 4-step workflow to introduce new content</p>
          </div>
        </div>

        <ol class="space-y-4 text-slate-700 text-sm font-bold">
          <li class="bg-slate-50 border-2 border-slate-200 p-4 rounded-2xl flex items-start gap-3">
            <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-black shrink-0 text-sm">1</span>
            <div>
              <h4 class="font-black text-slate-900 text-base mb-1">Save the Picture (Optional)</h4>
              <p class="text-xs text-slate-500 mb-2">If your question has an illustration, save it into the right folder:</p>
              <code class="bg-slate-900 text-emerald-400 px-3 py-1 rounded-lg text-xs font-mono block w-fit">
                c:\xampp\htdocs\nextgrade\images\science\wooden_spoon.jpg
              </code>
            </div>
          </li>

          <li class="bg-slate-50 border-2 border-slate-200 p-4 rounded-2xl flex items-start gap-3">
            <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-black shrink-0 text-sm">2</span>
            <div>
              <h4 class="font-black text-slate-900 text-base mb-1">Open <code>data/questions_bank.json</code></h4>
              <p class="text-xs text-slate-500 mb-2">
                Open the file in VS Code. Use <strong class="text-sky-600">Ctrl + F</strong> to find the topic you want to add to (e.g. search <code class="font-mono text-purple-600">"sci_sink_float"</code>).
              </p>
              <p class="text-xs text-slate-500">
                Paste your new JSON object into the array. Remember to separate each question with a comma <code class="font-black text-red-500">,</code>!
              </p>
            </div>
          </li>

          <li class="bg-slate-50 border-2 border-slate-200 p-4 rounded-2xl flex items-start gap-3">
            <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-black shrink-0 text-sm">3</span>
            <div>
              <h4 class="font-black text-slate-900 text-base mb-1">Sync the Database (Re-seed)</h4>
              <p class="text-xs text-slate-500 mb-2">
                Simply click the <strong class="text-sky-600">"Sync DB (seed.php)"</strong> button at the top of this guide, or open this URL in your web browser:
              </p>
              <a href="seed.php" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-mono text-sky-600 bg-sky-50 border border-sky-200 px-3 py-1 rounded-lg hover:bg-sky-100">
                <span>🔗</span> http://localhost/nextgrade/seed.php
              </a>
              <p class="text-xs text-slate-400 mt-2">
                Or from PowerShell: <code class="font-mono bg-slate-900 text-emerald-400 px-2 py-0.5 rounded">php seed.php</code>
              </p>
            </div>
          </li>

          <li class="bg-slate-50 border-2 border-slate-200 p-4 rounded-2xl flex items-start gap-3">
            <span class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black shrink-0 text-sm">4</span>
            <div>
              <h4 class="font-black text-slate-900 text-base mb-1">Test Live on Tablet Quiz Runner</h4>
              <p class="text-xs text-slate-500 mb-2">Open the quiz runner for that topic to verify your new question displays with picture, sound, and correct answer validation:</p>
              <a href="quiz.php?topic=sci_sink_float" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-mono text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-lg hover:bg-emerald-100">
                <span>🎯</span> http://localhost/nextgrade/quiz.php?topic=sci_sink_float
              </a>
            </div>
          </li>
        </ol>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 6: HOW TO EDIT QUESTIONS -->
      <!-- ========================================================================= -->
      <section id="edit-questions" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-blue-100 text-blue-700 rounded-2xl">✏️</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">6. How to EDIT / CHANGE Existing Questions</h3>
            <p class="text-xs font-bold text-slate-400">Fixing typos, updating hints, changing options, or swapping images</p>
          </div>
        </div>

        <div class="space-y-4 text-slate-700 text-sm font-bold">
          <div class="p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl">
            <h4 class="text-base font-black text-slate-800 mb-1">1. Find the Question in <code>data/questions_bank.json</code></h4>
            <p class="text-xs text-slate-500 mb-2">
              Press <kbd class="bg-white border px-1.5 py-0.5 rounded shadow-sm">Ctrl + F</kbd> in your editor and type any keyword from the question text (e.g. <code class="font-mono text-purple-600">"anchor"</code> or <code class="font-mono text-purple-600">"Pecahkan Suku Kata"</code>).
            </p>
          </div>

          <div class="p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl">
            <h4 class="text-base font-black text-slate-800 mb-1">2. Modify the Desired Fields</h4>
            <ul class="text-xs font-bold text-slate-600 space-y-1.5 list-disc list-inside">
              <li><strong>To change options:</strong> Update the array inside <code class="text-sky-600 font-mono">options_json</code>.</li>
              <li><strong>To change the right answer:</strong> Make sure <code class="text-sky-600 font-mono">correct_answer</code> exactly matches one option.</li>
              <li><strong>To update the photo:</strong> Replace the file in <code class="text-sky-600 font-mono">images/...</code> or update the <code class="text-sky-600 font-mono">image_url</code> path.</li>
              <li><strong>To adjust speech voice:</strong> Modify <code class="text-sky-600 font-mono">question_audio</code> or <code class="text-sky-600 font-mono">hint_audio</code>.</li>
            </ul>
          </div>

          <div class="p-4 bg-slate-50 border-2 border-slate-200 rounded-2xl">
            <h4 class="text-base font-black text-slate-800 mb-1">3. Save & Sync Database</h4>
            <p class="text-xs text-slate-500">
              Save the file (<kbd class="bg-white border px-1.5 py-0.5 rounded shadow-sm">Ctrl + S</kbd>) and click the <strong class="text-sky-600">"Sync DB (seed.php)"</strong> button at the top. The new changes are instantly reflected in the live quiz!
            </p>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 7: HOW TO REMOVE QUESTIONS -->
      <!-- ========================================================================= -->
      <section id="remove-questions" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-red-100 text-red-700 rounded-2xl">🗑️</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">7. How to REMOVE Questions</h3>
            <p class="text-xs font-bold text-slate-400">Safely deleting a question from the repository and database</p>
          </div>
        </div>

        <div class="space-y-4 text-slate-700 text-sm font-bold">
          <p class="text-xs text-slate-600 leading-relaxed">
            Because NextGrade uses <code class="text-sky-600 font-mono font-bold">data/questions_bank.json</code> as the master record, deleting a question is simple and clean:
          </p>

          <div class="bg-slate-50 border-2 border-slate-200 p-4 rounded-2xl">
            <h4 class="text-base font-black text-slate-800 mb-1">Step 1: Locate and Delete the JSON Block</h4>
            <p class="text-xs text-slate-500 mb-3">
              Open <code class="font-mono text-purple-600">data/questions_bank.json</code>, find the question block from its opening brace <code class="font-mono">{</code> to its closing brace <code class="font-mono">}</code>, and delete it.
            </p>

            <div class="bg-red-50 border border-red-300 p-3 rounded-xl text-xs text-red-800 font-bold">
              <span class="font-black">⚠️ Syntax Warning (Trailing Comma):</span> Make sure the item preceding or following your deleted block still has valid comma separation. The last element in the array must NOT have a trailing comma!
            </div>
          </div>

          <div class="bg-slate-50 border-2 border-slate-200 p-4 rounded-2xl">
            <h4 class="text-base font-black text-slate-800 mb-1">Step 2: Remove Orphaned Image (Optional)</h4>
            <p class="text-xs text-slate-500">
              If the image file is no longer used by any other question, you can safely delete the file from the <code class="text-sky-600 font-mono">images/</code> folder to keep the repository lightweight.
            </p>
          </div>

          <div class="bg-slate-50 border-2 border-slate-200 p-4 rounded-2xl">
            <h4 class="text-base font-black text-slate-800 mb-1">Step 3: Run Database Sync</h4>
            <p class="text-xs text-slate-500">
              Run <code class="font-mono text-emerald-600">seed.php</code> (click "Sync DB" at top). This cleans the table and re-inserts the updated questions bank. The deleted question is immediately removed from all student quizzes.
            </p>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 8: DATABASE SYNC & TRUNCATE -->
      <!-- ========================================================================= -->
      <section id="sync-db" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-teal-100 text-teal-700 rounded-2xl">🔄</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">8. Database Synchronization (seed.php) & Truncate (truncate.php)</h3>
            <p class="text-xs font-bold text-slate-400">Managing database state, syncing from JSON, and wiping clean</p>
          </div>
        </div>

        <p class="text-slate-600 font-bold mb-4 text-sm leading-relaxed">
          NextGrade provides two essential one-click database operations accessible from the top header or terminal:
        </p>

        <!-- Operation 1: Sync DB (seed.php) -->
        <div class="mb-6">
          <h4 class="text-base font-black text-slate-800 mb-2 flex items-center gap-2">
            <span class="text-sky-600 font-black">A. Sync DB (seed.php)</span>
            <span class="text-xs bg-sky-100 text-sky-800 px-2.5 py-0.5 rounded-full font-bold">Standard Workflow</span>
          </h4>
          <p class="text-xs font-bold text-slate-500 mb-3">
            Whenever you run <code class="text-teal-600 font-mono font-black">seed.php</code>, it executes four automated tasks:
          </p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4 text-xs font-bold text-slate-700">
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-2">
              <span class="text-emerald-500 text-base">✓</span>
              <span>Cleans tables: <code>questions</code>, <code>revisions</code>, <code>topics</code>, <code>subjects</code></span>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-2">
              <span class="text-emerald-500 text-base">✓</span>
              <span>Inserts all 5 Subjects and 27 Topics with their icons</span>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-2">
              <span class="text-emerald-500 text-base">✓</span>
              <span>Imports all 1,497+ questions from <code>questions_bank.json</code></span>
            </div>
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-2">
              <span class="text-emerald-500 text-base">✓</span>
              <span>Dynamically generates 5-minute revision flashcards directly from <code>questions_bank.json</code></span>
            </div>
          </div>

          <div class="code-block p-4 text-xs">
            <button class="copy-btn" onclick="copyCode('cmd-seed', this)">Copy Command</button>
            <div class="text-slate-400 mb-1 font-mono"># Run from PowerShell / Command Prompt:</div>
            <pre id="cmd-seed" class="text-emerald-400 font-mono">cd c:\xampp\htdocs\nextgrade
php seed.php</pre>
          </div>
        </div>

        <!-- Operation 2: Truncate DB (truncate.php) -->
        <div class="p-5 bg-rose-50 border-2 border-rose-200 rounded-2xl">
          <h4 class="text-base font-black text-rose-800 mb-2 flex items-center gap-2">
            <span>🗑️</span>
            <span>B. Truncate DB (truncate.php) — Delete Everything</span>
            <span class="text-xs bg-rose-200 text-rose-900 px-2.5 py-0.5 rounded-full font-bold">Wipe Clean</span>
          </h4>
          <p class="text-xs font-bold text-rose-700 mb-3">
            Clicking the red <strong class="text-rose-900 font-black">"Truncate DB"</strong> button in the header bar completely wipes all tables in the database (including quiz histories, questions, revisions, topics, subjects, and student records).
          </p>
          <div class="code-block p-4 text-xs">
            <button class="copy-btn" onclick="copyCode('cmd-truncate', this)">Copy Command</button>
            <div class="text-slate-400 mb-1 font-mono"># Or run truncate directly via CLI:</div>
            <pre id="cmd-truncate" class="text-rose-300 font-mono">cd c:\xampp\htdocs\nextgrade
php truncate.php</pre>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 9: GIT PUSH WORKFLOW -->
      <!-- ========================================================================= -->
      <section id="git-workflow" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-purple-100 text-purple-700 rounded-2xl">🚀</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">9. Complete Git Workflow (Terminal to GitHub)</h3>
            <p class="text-xs font-bold text-slate-400">Step-by-step commands to commit and push your work</p>
          </div>
        </div>

        <div class="space-y-6">
          <!-- Step 1 -->
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center font-black text-xs">1</span>
              <h4 class="font-black text-slate-800 text-sm">Check Which Files Have Changed</h4>
            </div>
            <p class="text-xs text-slate-500 font-bold mb-2">Always run this first to review your edited files and newly added images:</p>
            <div class="code-block p-3 text-xs">
              <button class="copy-btn" onclick="copyCode('git-status-cmd', this)">Copy</button>
              <pre id="git-status-cmd" class="text-emerald-400 font-mono">git status</pre>
            </div>
          </div>

          <!-- Step 2 -->
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center font-black text-xs">2</span>
              <h4 class="font-black text-slate-800 text-sm">Stage Your Changes</h4>
            </div>
            <p class="text-xs text-slate-500 font-bold mb-2">Stage the question bank and any new images you added:</p>
            <div class="code-block p-3 text-xs">
              <button class="copy-btn" onclick="copyCode('git-add-cmd', this)">Copy</button>
              <pre id="git-add-cmd" class="text-emerald-400 font-mono">git add data/questions_bank.json images/</pre>
            </div>
            <p class="text-[11px] text-slate-400 font-bold mt-1">Or stage all modifications: <code class="font-mono text-purple-600 font-bold">git add .</code></p>
          </div>

          <!-- Step 3 -->
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center font-black text-xs">3</span>
              <h4 class="font-black text-slate-800 text-sm">Commit with a Meaningful Message</h4>
            </div>
            <p class="text-xs text-slate-500 font-bold mb-2">Write a clean, descriptive message explaining what questions were added or modified:</p>
            <div class="code-block p-3 text-xs">
              <button class="copy-btn" onclick="copyCode('git-commit-cmd', this)">Copy</button>
              <pre id="git-commit-cmd" class="text-emerald-400 font-mono">git commit -m "feat(science): add 5 new sink or float questions with real photos"</pre>
            </div>
          </div>

          <!-- Step 4 -->
          <div>
            <div class="flex items-center gap-2 mb-2">
              <span class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center font-black text-xs">4</span>
              <h4 class="font-black text-slate-800 text-sm">Push to GitHub (main branch)</h4>
            </div>
            <p class="text-xs text-slate-500 font-bold mb-2">Publish your commit to the remote repository:</p>
            <div class="code-block p-3 text-xs">
              <button class="copy-btn" onclick="copyCode('git-push-cmd', this)">Copy</button>
              <pre id="git-push-cmd" class="text-emerald-400 font-mono">git push origin main</pre>
            </div>
          </div>

          <!-- Helpful Troubleshooting -->
          <div class="bg-slate-100 p-4 rounded-2xl border border-slate-200">
            <h5 class="text-xs font-black uppercase text-slate-600 tracking-wider mb-2 flex items-center gap-1.5">
              <span>💡</span> Common Git Troubleshooting Tips
            </h5>
            <div class="space-y-2 text-xs font-bold text-slate-600">
              <p>
                <strong>Push Rejected (Non-fast-forward)?</strong> Pull the latest updates first using rebase:
                <code class="block font-mono bg-white p-1 rounded mt-1 text-slate-800 border">git pull --rebase origin main</code>
              </p>
              <p>
                <strong>Check Recent Commits:</strong>
                <code class="block font-mono bg-white p-1 rounded mt-1 text-slate-800 border">git log -n 5 --oneline</code>
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 10: INTERACTIVE QUESTION BUILDER -->
      <!-- ========================================================================= -->
      <section id="generator" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center gap-3 mb-4">
          <span class="text-3xl p-3 bg-amber-100 text-amber-700 rounded-2xl">⚡</span>
          <div>
            <h3 class="text-2xl font-black text-slate-800">10. Interactive Question JSON Generator</h3>
            <p class="text-xs font-bold text-slate-400">Fill in the fields to instantly generate valid, copy-pasteable JSON</p>
          </div>
        </div>

        <form id="builder-form" onsubmit="event.preventDefault(); generateJSON();" class="space-y-4">
          <!-- Row 1: Subject & Topic Dropdowns -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Subject:</label>
              <select 
                id="build-subject" 
                onchange="onSubjectSelectChange(this.value)"
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
                <option value="bahasa_melayu">Bahasa Melayu (🇲🇾)</option>
                <option value="maths">Mathematics (🔢)</option>
                <option value="english">English Language (🔤)</option>
                <option value="science" selected>Early Science (🔬)</option>
                <option value="ict">ICT & Computer (💻)</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Topic:</label>
              <select 
                id="build-topic" 
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
                <!-- Populated dynamically -->
              </select>
            </div>
          </div>

          <!-- Row 2: Question Text & Audio -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Question Prompt Text:</label>
              <input 
                type="text" 
                id="build-text" 
                value="Will a heavy iron ship anchor sink or float?" 
                placeholder="e.g. What animal is this?"
                required
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
            </div>
            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Speech Audio Prompt (Read Aloud):</label>
              <input 
                type="text" 
                id="build-audio" 
                value="Will a heavy iron ship anchor sink or float?" 
                placeholder="Same as question or speech-friendly version"
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
            </div>
          </div>

          <!-- Row 3: Question Type & Image Path -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Question Type:</label>
              <select 
                id="build-type" 
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
                <option value="multiple_choice">Multiple Choice (Standard)</option>
                <option value="syllable_split">Syllable Split (Suku Kata)</option>
                <option value="clock_analog">Analog Clock Hands</option>
                <option value="ordering">Descending Ordering</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Image Relative URL (optional):</label>
              <input 
                type="text" 
                id="build-image" 
                value="images/science/anchor.jpg" 
                placeholder="e.g. images/science/anchor.jpg (or leave empty)"
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-mono font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
            </div>
          </div>

          <!-- Row 4: Multiple Choice Options -->
          <div>
            <label class="block text-xs font-black uppercase text-slate-600 mb-1">Answer Choices (comma separated):</label>
            <input 
              type="text" 
              id="build-options" 
              value="Sink, Float" 
              placeholder="e.g. Sink, Float, Fly"
              required
              oninput="syncCorrectAnswerDropdown(this.value)"
              class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
            >
          </div>

          <!-- Row 5: Correct Answer & Hint -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Correct Answer:</label>
              <select 
                id="build-correct" 
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
                <option value="Sink">Sink</option>
                <option value="Float">Float</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-black uppercase text-slate-600 mb-1">Friendly Hint Clue:</label>
              <input 
                type="text" 
                id="build-hint" 
                value="An iron anchor is very heavy and drops to the seabed." 
                placeholder="Friendly clue for kindergarten students"
                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-sky-400 focus:outline-none"
              >
            </div>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            class="btn-chunky btn-primary py-3 px-6 rounded-2xl text-base font-black flex items-center gap-2 shadow-md w-full justify-center"
          >
            <span>✨</span>
            <span>Generate Valid JSON</span>
          </button>
        </form>

        <!-- Result Box -->
        <div class="mt-6">
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-black uppercase text-slate-500">Generated JSON (Ready to paste into <code>data/questions_bank.json</code>):</span>
          </div>
          <div class="code-block p-5 text-xs">
            <button class="copy-btn" onclick="copyCode('generated-json-output', this)">Copy JSON</button>
            <pre id="generated-json-output" class="text-emerald-300 font-mono leading-relaxed whitespace-pre-wrap">Click "Generate Valid JSON" above to build your question!</pre>
          </div>
        </div>
      </section>

      <!-- ========================================================================= -->
      <!-- SECTION 11: TOPICS & SUBJECTS CHEATSHEET -->
      <!-- ========================================================================= -->
      <section id="cheatsheet" class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-200 shadow-sm scroll-mt-28">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
          <div class="flex items-center gap-3">
            <span class="text-3xl p-3 bg-indigo-100 text-indigo-700 rounded-2xl">📋</span>
            <div>
              <h3 class="text-2xl font-black text-slate-800">11. All 27 Topics & Subject IDs Cheatsheet</h3>
              <p class="text-xs font-bold text-slate-400">Use exact IDs to prevent broken quiz links or database mismatches</p>
            </div>
          </div>

          <!-- Search Filter for Topics -->
          <input 
            type="text" 
            id="topic-search-input" 
            oninput="filterTopicsTable(this.value)" 
            placeholder="🔍 Search topic or ID..." 
            class="bg-slate-50 border-2 border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold focus:border-sky-400 focus:outline-none"
          >
        </div>

        <div class="overflow-x-auto">
          <table id="topics-table" class="w-full text-left text-xs font-bold border-collapse">
            <thead>
              <tr class="bg-slate-100 text-slate-600 uppercase text-[11px] border-b-2 border-slate-200">
                <th class="p-3">Topic ID</th>
                <th class="p-3">Subject ID</th>
                <th class="p-3">Topic Title</th>
                <th class="p-3">Lang</th>
                <th class="p-3">Live Test Link</th>
              </tr>
            </thead>
            <tbody id="topics-tbody" class="divide-y divide-slate-100 text-slate-700">
              <!-- Dynamically populated from JS array -->
            </tbody>
          </table>
        </div>
      </section>

    </main>
  </div>

  <!-- Footer -->
  <footer class="text-center text-xs font-bold text-slate-400 py-6 border-t border-slate-200">
    NextGrade Educational Suite • Content Creator & Developer Documentation • 3-Tier PHP/MySQL Architecture
  </footer>

</div>

<!-- Interactive Client-side Script -->
<script>
// Catalog of all 5 Subjects and 27 Topics
const TOPICS_DATA = [
  // Bahasa Melayu
  { id: 'bm_bulan', subject: 'bahasa_melayu', name: '12 Bulan dalam Setahun', icon: '📅', lang: 'ms' },
  { id: 'bm_suku_kata', subject: 'bahasa_melayu', name: 'Pecahkan Suku Kata', icon: '🧩', lang: 'ms' },
  { id: 'bm_kenderaan', subject: 'bahasa_melayu', name: 'Kenderaan Darat, Air & Udara', icon: '🚗', lang: 'ms' },
  { id: 'bm_binatang', subject: 'bahasa_melayu', name: 'Haiwan 2 Kaki & 4 Kaki', icon: '🐾', lang: 'ms' },
  { id: 'bm_ini_itu', subject: 'bahasa_melayu', name: 'Kata Tunjuk: Ini & Itu', icon: '👉', lang: 'ms' },

  // Maths
  { id: 'math_clocks', subject: 'maths', name: 'Analog Clocks & Time', icon: '🕒', lang: 'en' },
  { id: 'math_descending', subject: 'maths', name: 'Descending Numbers (20 to 1)', icon: '📉', lang: 'en' },
  { id: 'math_addition', subject: 'maths', name: 'Addition (Combining Numbers)', icon: '➕', lang: 'en' },
  { id: 'math_subtraction', subject: 'maths', name: 'Subtraction (Taking Away)', icon: '➖', lang: 'en' },

  // English
  { id: 'eng_days_months', subject: 'english', name: 'Days of Week & Months', icon: '🗓️', lang: 'en' },
  { id: 'eng_blending', subject: 'english', name: 'Beginning Blends (ch- & th-)', icon: '🗣️', lang: 'en' },
  { id: 'eng_pronouns', subject: 'english', name: 'Pronouns (He, She, It, They)', icon: '👥', lang: 'en' },
  { id: 'eng_articles', subject: 'english', name: 'Articles: A and An', icon: '🔤', lang: 'en' },
  { id: 'eng_has_have', subject: 'english', name: 'Using Has and Have', icon: '🤲', lang: 'en' },
  { id: 'eng_demonstratives', subject: 'english', name: 'This, That, These, Those', icon: '👉', lang: 'en' },
  { id: 'eng_comprehension', subject: 'english', name: 'Reading Comprehension', icon: '📖', lang: 'en' },

  // Science
  { id: 'sci_land_sea', subject: 'science', name: 'Land vs Sea Animals', icon: '🐬', lang: 'en' },
  { id: 'sci_sink_float', subject: 'science', name: 'Sink or Float', icon: '⚓', lang: 'en' },
  { id: 'sci_celestial', subject: 'science', name: 'Sun, Moon, Star & Earth', icon: '🌍', lang: 'en' },
  { id: 'sci_materials', subject: 'science', name: 'Materials: Metal, Glass & Paper', icon: '🪨', lang: 'en' },
  { id: 'sci_pollution', subject: 'science', name: 'Types of Pollution', icon: '🏭', lang: 'en' },
  { id: 'sci_plants', subject: 'science', name: 'Parts & Needs of a Plant', icon: '🌱', lang: 'en' },

  // ICT
  { id: 'ict_storage', subject: 'ict', name: 'Computer Drives & Storage', icon: '💾', lang: 'en' },
  { id: 'ict_parts', subject: 'ict', name: 'All About Computer Parts', icon: '🖥️', lang: 'en' },
  { id: 'ict_counting', subject: 'ict', name: 'Count Computer Peripherals', icon: '🔢', lang: 'en' },
  { id: 'ict_spelling', subject: 'ict', name: 'Fill in the Missing Letters', icon: '🔤', lang: 'en' },
  { id: 'ict_input_output', subject: 'ict', name: 'Input (I) vs Output (O) Devices', icon: '🔌', lang: 'en' }
];

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
  populateTopicsDropdown('science');
  renderTopicsTable(TOPICS_DATA);
  generateJSON(); // generate default sample
});

// Subject selection update
function onSubjectSelectChange(subjectId) {
  populateTopicsDropdown(subjectId);
}

function populateTopicsDropdown(subjectId) {
  const topicSelect = document.getElementById('build-topic');
  const filtered = TOPICS_DATA.filter(t => t.subject === subjectId);
  topicSelect.innerHTML = filtered.map(t => `
    <option value="${t.id}">${t.icon} ${t.name} (${t.id})</option>
  `).join('');
}

// Sync options input to correct answer dropdown
function syncCorrectAnswerDropdown(rawStr) {
  const correctSelect = document.getElementById('build-correct');
  const options = rawStr.split(',').map(s => s.trim()).filter(Boolean);
  if (options.length === 0) return;
  const currentVal = correctSelect.value;
  correctSelect.innerHTML = options.map(opt => `
    <option value="${opt}" ${opt === currentVal ? 'selected' : ''}>${opt}</option>
  `).join('');
}

// Generate valid Question JSON
function generateJSON() {
  const subjectId = document.getElementById('build-subject').value;
  const topicId = document.getElementById('build-topic').value;
  const text = document.getElementById('build-text').value.trim();
  const audio = document.getElementById('build-audio').value.trim() || text;
  const qType = document.getElementById('build-type').value;
  const imageRaw = document.getElementById('build-image').value.trim();
  const optionsRaw = document.getElementById('build-options').value;
  const optionsArr = optionsRaw.split(',').map(s => s.trim()).filter(Boolean);
  const correct = document.getElementById('build-correct').value.trim();
  const hint = document.getElementById('build-hint').value.trim();

  const lang = subjectId === 'bahasa_melayu' ? 'ms' : 'en';

  const qObj = {
    topic_id: topicId,
    subject_id: subjectId,
    question_text: text,
    question_audio: audio,
    lang: lang,
    question_type: qType,
    image_url: imageRaw ? imageRaw : null,
    passage: null,
    options_json: JSON.stringify(optionsArr),
    correct_answer: correct,
    hint_text: hint,
    hint_audio: hint,
    meta_data_json: null
  };

  const formatted = JSON.stringify(qObj, null, 2);
  document.getElementById('generated-json-output').textContent = formatted;
}

// Render Cheatsheet Table
function renderTopicsTable(topics) {
  const tbody = document.getElementById('topics-tbody');
  tbody.innerHTML = topics.map(t => `
    <tr class="hover:bg-slate-50 transition-colors">
      <td class="p-3 font-mono text-sky-600 font-bold">${t.id}</td>
      <td class="p-3 font-mono text-purple-600">${t.subject}</td>
      <td class="p-3 font-bold text-slate-800">${t.icon} ${t.name}</td>
      <td class="p-3 font-mono uppercase text-slate-500">${t.lang}</td>
      <td class="p-3">
        <a href="quiz.php?topic=${encodeURIComponent(t.id)}" target="_blank" class="text-xs text-sky-600 hover:text-sky-800 font-black flex items-center gap-1">
          Play ➔
        </a>
      </td>
    </tr>
  `).join('');
}

// Filter Cheatsheet
function filterTopicsTable(query) {
  const q = query.toLowerCase().trim();
  const filtered = TOPICS_DATA.filter(t => 
    t.id.toLowerCase().includes(q) || 
    t.subject.toLowerCase().includes(q) || 
    t.name.toLowerCase().includes(q)
  );
  renderTopicsTable(filtered);
}

// Copy Code Helper
function copyCode(elementId, btn) {
  const codeEl = document.getElementById(elementId);
  const text = codeEl.innerText || codeEl.textContent;

  navigator.clipboard.writeText(text).then(() => {
    const originalText = btn.innerText;
    btn.innerText = '✓ Copied!';
    btn.classList.add('bg-emerald-600', 'text-white');
    showToast('Copied to clipboard successfully!');
    setTimeout(() => {
      btn.innerText = originalText;
      btn.classList.remove('bg-emerald-600', 'text-white');
    }, 2000);
  }).catch(err => {
    console.error('Clipboard copy error:', err);
    showToast('Failed to copy, please select manually.', true);
  });
}

// Toast notification
function showToast(msg, isError = false) {
  const toast = document.getElementById('toast');
  const toastMsg = document.getElementById('toast-msg');
  const toastIcon = document.getElementById('toast-icon');

  toastMsg.textContent = msg;
  toastIcon.textContent = isError ? '❌' : '✅';
  toast.classList.remove('translate-y-20', 'opacity-0');
  toast.classList.add('translate-y-0', 'opacity-100');

  setTimeout(() => {
    toast.classList.remove('translate-y-0', 'opacity-100');
    toast.classList.add('translate-y-20', 'opacity-0');
  }, 2500);
}

// Trigger Live Database Seeder via AJAX
async function runLiveSeeder() {
  const btn = document.getElementById('btn-run-seeder');
  const icon = document.getElementById('seeder-icon');
  const label = document.getElementById('seeder-label');
  const modal = document.getElementById('seeder-modal');
  const output = document.getElementById('seeder-output');
  const title = document.getElementById('console-title');
  const pulse = document.getElementById('console-pulse');

  btn.disabled = true;
  icon.classList.add('animate-spin');
  label.textContent = 'Syncing...';
  modal.classList.remove('hidden');
  if (title) title.textContent = 'Database Seeder Output (seed.php)';
  if (pulse) pulse.textContent = '🟢';
  output.className = 'font-mono text-xs text-emerald-400 max-h-60 overflow-y-auto whitespace-pre-wrap leading-relaxed p-2 bg-slate-950 rounded-xl';
  output.textContent = '⏳ Executing seed.php on local server... Please wait...';

  try {
    const resp = await fetch('seed.php');
    const text = await resp.text();
    output.textContent = text;
    showToast('Database synced successfully!');
  } catch (err) {
    output.textContent = '❌ Error executing seed.php:\n' + err.message;
    showToast('Seeding failed.', true);
  } finally {
    btn.disabled = false;
    icon.classList.remove('animate-spin');
    label.textContent = 'Sync DB (seed.php)';
  }
}

// Trigger Live Database Truncate via AJAX
async function runLiveTruncate() {
  const confirmed = confirm(
    "⚠️ DANGER: ARE YOU SURE YOU WANT TO TRUNCATE AND DELETE EVERYTHING?\n\n" +
    "This will permanently delete ALL data in the database:\n" +
    "• All questions\n" +
    "• All topics & subjects\n" +
    "• All revision modules\n" +
    "• All student profiles & quiz history\n\n" +
    "Click OK to truncate everything, or Cancel to abort."
  );
  if (!confirmed) return;

  const btn = document.getElementById('btn-run-truncate');
  const icon = document.getElementById('truncate-icon');
  const label = document.getElementById('truncate-label');
  const modal = document.getElementById('seeder-modal');
  const output = document.getElementById('seeder-output');
  const title = document.getElementById('console-title');
  const pulse = document.getElementById('console-pulse');

  btn.disabled = true;
  icon.classList.add('animate-spin');
  label.textContent = 'Truncating...';
  modal.classList.remove('hidden');
  if (title) title.textContent = 'Database Truncate Output (truncate.php)';
  if (pulse) pulse.textContent = '🔴';
  output.className = 'font-mono text-xs text-rose-400 max-h-60 overflow-y-auto whitespace-pre-wrap leading-relaxed p-2 bg-slate-950 rounded-xl';
  output.textContent = '⏳ Executing truncate.php on local server... Deleting all tables...';

  try {
    const resp = await fetch('truncate.php');
    const text = await resp.text();
    output.textContent = text;
    showToast('Database truncated successfully!');
  } catch (err) {
    output.textContent = '❌ Error executing truncate.php:\n' + err.message;
    showToast('Truncate failed.', true);
  } finally {
    btn.disabled = false;
    icon.classList.remove('animate-spin');
    label.textContent = 'Truncate DB';
  }
}
</script>

</body>
</html>
