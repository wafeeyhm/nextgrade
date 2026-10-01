<?php
// NextGrade - System Admin: Official System & Operations Guide
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Admin Operations Guide - NextGrade Portal</title>
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
          <a href="questions.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-circle-question mr-1.5 text-slate-400"></i> Questions (CRUD)
          </a>
          <a href="guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-book-open mr-1.5 text-indigo-400"></i> Guide
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
          href="developer_guide.php" 
          title="Question Builder & Developer Guide"
          class="bg-slate-700/70 hover:bg-slate-700 text-indigo-300 hover:text-white text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-code text-xs"></i>
          <span class="hidden sm:inline">Dev Tools</span>
        </a>

        <a 
          href="profile.php" 
          title="Edit Admin Profile"
          class="bg-slate-700/70 hover:bg-slate-700 text-slate-200 text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-user-shield text-xs text-indigo-400"></i>
          <span class="hidden sm:inline">Profile</span>
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
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8 space-y-8">

    <!-- Page Title & Header Banner -->
    <div class="bg-gradient-to-r from-slate-800 via-indigo-950/40 to-slate-800 border border-slate-700/80 rounded-3xl p-6 md:p-8 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-black uppercase tracking-wider mb-2">
          <i class="fa-solid fa-book-bookmark"></i> NextGrade Core Administrator Manual
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">System Administrator Reference Guide</h1>
        <p class="text-sm font-semibold text-slate-400 mt-1 max-w-2xl">
          Comprehensive operational handbook covering user access hierarchies, parent account governance, Kindergarten 3 (KG3) curriculum rules, learning analytics, and maintenance diagnostics.
        </p>
      </div>

      <div class="flex items-center gap-3 shrink-0 flex-wrap">
        <a 
          href="#sec-hosting" 
          class="bg-amber-600 hover:bg-amber-500 text-white font-black text-xs py-2.5 px-4 rounded-xl shadow-lg shadow-amber-600/20 transition-all flex items-center gap-2"
        >
          <i class="fa-solid fa-cloud-arrow-up"></i>
          <span>Hosting Guide</span>
        </a>
        <a 
          href="verification.php" 
          class="bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs py-2.5 px-4 rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2"
        >
          <i class="fa-solid fa-clipboard-check"></i>
          <span>System Verification</span>
        </a>
        <a 
          href="developer_guide.php" 
          class="bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center gap-2"
        >
          <i class="fa-solid fa-code"></i>
          <span>Question & Dev Guide</span>
        </a>
        <a 
          href="parents.php" 
          class="bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2"
        >
          <i class="fa-solid fa-users"></i>
          <span>Manage Parents</span>
        </a>
        <a 
          href="profile.php" 
          class="bg-slate-800 hover:bg-slate-700 border border-slate-600 text-slate-200 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2"
        >
          <i class="fa-solid fa-gear"></i>
          <span>Settings</span>
        </a>
      </div>
    </div>

    <!-- Quick Navigation Anchor Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
      <a href="#sec-hosting" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-amber-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🌐</div>
        <span class="block text-xs font-black text-amber-400">Hosting Setup</span>
        <span class="text-[10px] text-slate-400 font-bold">SQL & Deploy</span>
      </a>

      <a href="#sec-verification" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-emerald-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🔍</div>
        <span class="block text-xs font-black text-emerald-400">Verification</span>
        <span class="text-[10px] text-slate-400 font-bold">Online Readiness</span>
      </a>

      <a href="#sec-architecture" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🏛️</div>
        <span class="block text-xs font-black text-white">Architecture</span>
        <span class="text-[10px] text-slate-400 font-bold">Role Matrix</span>
      </a>

      <a href="#sec-parents" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">👨‍👩‍👧‍👦</div>
        <span class="block text-xs font-black text-white">Parent CRUD</span>
        <span class="text-[10px] text-slate-400 font-bold">Account Lifecycle</span>
      </a>

      <a href="#sec-kids" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🧒</div>
        <span class="block text-xs font-black text-white">Kids Login</span>
        <span class="text-[10px] text-slate-400 font-bold">PIN & Avatars</span>
      </a>

      <a href="#sec-kg3" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🎓</div>
        <span class="block text-xs font-black text-white">Curriculum</span>
        <span class="text-[10px] text-slate-400 font-bold">1,540+ Questions</span>
      </a>

      <a href="#sec-analytics" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">📊</div>
        <span class="block text-xs font-black text-white">GAP Analysis</span>
        <span class="text-[10px] text-slate-400 font-bold">Inspector Tool</span>
      </a>

      <a href="#sec-maintenance" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-3.5 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🛠️</div>
        <span class="block text-xs font-black text-white">Operations</span>
        <span class="text-[10px] text-slate-400 font-bold">CLI & Backups</span>
      </a>
    </div>

    <!-- Section 1: Tri-Tier Access Architecture -->
    <section id="sec-architecture" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg font-black">
          1
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">Tri-Tier Access Control Architecture</h2>
          <p class="text-xs font-bold text-slate-400">Strict separation of duties between System Admins, Parents, and Early Learners.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- System Admin -->
        <div class="bg-slate-900/80 border border-indigo-500/30 rounded-2xl p-5 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-black uppercase text-indigo-400 tracking-wider">Level 1: Core</span>
            <span class="text-lg">🛡️</span>
          </div>
          <h3 class="font-black text-white text-base">System Administrator</h3>
          <p class="text-xs text-slate-400 font-semibold leading-relaxed">
            Full root platform privileges. Manages parent account onboarding, system configuration, credential resets, and curriculum auditing.
          </p>
          <ul class="text-xs font-bold text-slate-300 space-y-1.5 pt-2 border-t border-slate-800">
            <li>✓ Parent CRUD operations</li>
            <li>✓ Cross-system analytics</li>
            <li>✓ Profile & credential management</li>
          </ul>
        </div>

        <!-- Parents -->
        <div class="bg-slate-900/80 border border-sky-500/30 rounded-2xl p-5 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-black uppercase text-sky-400 tracking-wider">Level 2: Family</span>
            <span class="text-lg">👨‍👩‍👧‍👦</span>
          </div>
          <h3 class="font-black text-white text-base">Parent Portal Manager</h3>
          <p class="text-xs text-slate-400 font-semibold leading-relaxed">
            Dedicated portal for parents. Controls child accounts, simple usernames, memorable 4-digit PINs, and monitors detailed quiz performance.
          </p>
          <ul class="text-xs font-bold text-slate-300 space-y-1.5 pt-2 border-t border-slate-800">
            <li>✓ Create & edit kid profiles (CRUD)</li>
            <li>✓ Question Inspector (per attempt)</li>
            <li>✓ Automated GAP analysis tips (&lt;70%)</li>
          </ul>
        </div>

        <!-- Kids -->
        <div class="bg-slate-900/80 border border-amber-500/30 rounded-2xl p-5 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-black uppercase text-amber-400 tracking-wider">Level 3: Learner</span>
            <span class="text-lg">🧒</span>
          </div>
          <h3 class="font-black text-white text-base">Student (Kid Profile)</h3>
          <p class="text-xs text-slate-400 font-semibold leading-relaxed">
            Simplified kid-friendly gate. Avatar click + 4-digit PIN access. Engaging sound effects, TTS audio readouts, and interactive question types.
          </p>
          <ul class="text-xs font-bold text-slate-300 space-y-1.5 pt-2 border-t border-slate-800">
            <li>✓ Zero email or complex password</li>
            <li>✓ Kindergarten 3 (KG3) exclusive pool</li>
            <li>✓ Audio narration & immediate feedback</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Section 2: Parent Management (CRUD) -->
    <section id="sec-parents" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg font-black">
          2
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">Parent Account Governance (CRUD Guide)</h2>
          <p class="text-xs font-bold text-slate-400">Managing family accounts, parent codes, credentials, and activation state.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs text-slate-300 leading-relaxed font-semibold">
        <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-700/60 space-y-3">
          <h3 class="font-black text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-user-plus text-indigo-400"></i>
            <span>Creating a New Parent Account</span>
          </h3>
          <p>
            When adding a parent in <strong class="text-white">Parents Accounts</strong>, enter their Full Name, Login Username, Email, and temporary Password.
          </p>
          <div class="p-3 bg-slate-800 rounded-xl font-mono text-[11px] text-indigo-300 border border-slate-700">
            Family Code Pattern: PAR-XXXXXX (e.g. PAR-SARAH01)<br>
            Auto-generated if left blank to ensure database uniqueness.
          </div>
          <p>
            The Parent Code acts as a human-readable identifier that links all child profiles under one unified household umbrella.
          </p>
        </div>

        <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-700/60 space-y-3">
          <h3 class="font-black text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-user-pen text-indigo-400"></i>
            <span>Editing, Password Resets & Deactivation</span>
          </h3>
          <p>
            Admins can edit any parent's profile details at any time:
          </p>
          <ul class="space-y-1.5 pl-3 list-disc marker:text-indigo-400">
            <li><strong>Password Reset:</strong> Provide a new password directly in the edit modal; the backend uses <code class="text-indigo-300 bg-slate-800 px-1 py-0.5 rounded">PASSWORD_DEFAULT</code> bcrypt hashing.</li>
            <li><strong>Deactivation:</strong> Setting status to <code class="text-rose-400">inactive</code> immediately prevents the parent from logging in and flags children profiles.</li>
            <li><strong>Deletion:</strong> Removing a parent unlinks associated student records gracefully using foreign key cascade rules.</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Section 3: Student Login & Simple Access Model -->
    <section id="sec-kids" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg font-black">
          3
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">Kid-Friendly Authentication Architecture</h2>
          <p class="text-xs font-bold text-slate-400">How young learners log in without cumbersome passwords or email verifications.</p>
        </div>
      </div>

      <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-700/60 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-semibold text-slate-300">
          <div class="p-4 bg-slate-800/70 rounded-xl border border-slate-700 space-y-2">
            <span class="text-2xl block">1️⃣</span>
            <strong class="text-white block font-black">One-Tap Avatar Selection</strong>
            <p class="text-slate-400">On the student gate (<code class="text-indigo-300">index.php</code>), all registered kids appear with friendly, colorful avatars (Star, Astronaut, Dino, etc.).</p>
          </div>
          <div class="p-4 bg-slate-800/70 rounded-xl border border-slate-700 space-y-2">
            <span class="text-2xl block">2️⃣</span>
            <strong class="text-white block font-black">Memorable 4-Digit PIN</strong>
            <p class="text-slate-400">Kids enter a simple 4-digit PIN (default <code class="text-indigo-300 font-mono">1234</code>) set by their parent. Parents can customize this at any time.</p>
          </div>
          <div class="p-4 bg-slate-800/70 rounded-xl border border-slate-700 space-y-2">
            <span class="text-2xl block">3️⃣</span>
            <strong class="text-white block font-black">Grade Badge & Guard</strong>
            <p class="text-slate-400">The session stores the kid's active grade level. Access to questions is automatically evaluated against the KG3 question curriculum.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 4: Curriculum Matrices (Kindergarten 3 & Year 6 Brunei PSR) -->
    <section id="sec-kg3" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg font-black">
          4
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">Curriculum Structure & Multi-Grade Question Banks</h2>
          <p class="text-xs font-bold text-slate-400">1,549+ syllabus questions across Kindergarten 3 (KG3) and Year 6 (PSR Brunei) with strict grade isolation.</p>
        </div>
      </div>

      <div class="space-y-4 text-xs font-semibold text-slate-300 leading-relaxed">
        <div class="bg-sky-500/10 border border-sky-500/30 rounded-2xl p-4 text-sky-200 flex items-start gap-3">
          <span class="text-xl shrink-0">🎓</span>
          <div>
            <strong class="text-white font-black block">Supported Grade Levels & Curriculum Pools:</strong>
            NextGrade features two comprehensive curriculum tiers:
            <ul class="list-disc pl-4 mt-1 space-y-1 text-sky-100">
              <li><strong>Kindergarten 3 (KG3) (Ages 5–6):</strong> 5 subjects, 27 topics, 919 questions with phonics, syllable blending, audio voice hints, and printable tracing worksheets.</li>
              <li><strong>Year 6 Brunei PSR (Ages 11–12):</strong> 5 SPN21 core subjects, 21 topics, 630 exam questions (exactly 30 per topic) covering tenses, fractions, circuits, MIB heritage, and penjodoh bilangan.</li>
            </ul>
          </div>
        </div>

        <!-- Grade 1: KG3 Grid -->
        <h3 class="text-white font-black text-sm pt-2 flex items-center gap-2">
          <span>⭐</span> Tier 1: Kindergarten 3 (KG3) Subjects
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">📚</span>
            <strong class="text-white text-xs block font-black">Bahasa Melayu</strong>
            <span class="text-[10px] text-slate-400">Suku Kata, Kenderaan, Haiwan, Bulan</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🔤</span>
            <strong class="text-white text-xs block font-black">English</strong>
            <span class="text-[10px] text-slate-400">Phonics, Pronouns, Articles, Sight Words</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🔢</span>
            <strong class="text-white text-xs block font-black">Mathematics</strong>
            <span class="text-[10px] text-slate-400">Numbers 1-20, Addition, Clocks & Shapes</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🌱</span>
            <strong class="text-white text-xs block font-black">Science</strong>
            <span class="text-[10px] text-slate-400">Living Things, Sink/Float, Plants & Animals</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">💻</span>
            <strong class="text-white text-xs block font-black">ICT & Tech</strong>
            <span class="text-[10px] text-slate-400">Hardware, Mouse Skills, Safe Tech Habits</span>
          </div>
        </div>

        <!-- Grade 2: PSR Grid -->
        <h3 class="text-white font-black text-sm pt-2 flex items-center gap-2">
          <span>🇧🇳</span> Tier 2: Year 6 (PSR Brunei) Core Subjects (630 Questions)
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🇧🇳</span>
            <strong class="text-white text-xs block font-black">Bahasa Melayu (PSR)</strong>
            <span class="text-[10px] text-slate-400">Imbuhan, Penjodoh Bilangan, Peribahasa</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">📖</span>
            <strong class="text-white text-xs block font-black">English (PSR)</strong>
            <span class="text-[10px] text-slate-400">Grammar, Tenses, Prepositions, Idioms</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">📐</span>
            <strong class="text-white text-xs block font-black">Mathematics (PSR)</strong>
            <span class="text-[10px] text-slate-400">Fractions, Decimals, Geometry, Data</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🔬</span>
            <strong class="text-white text-xs block font-black">Science (PSR)</strong>
            <span class="text-[10px] text-slate-400">Human Body, Energy, Forces, Machines</span>
          </div>
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🕌</span>
            <strong class="text-white text-xs block font-black">MIB (Tahun 6)</strong>
            <span class="text-[10px] text-slate-400">Konsep MIB, Kesultanan, Adat Istiadat</span>
          </div>
        </div>

        <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-700 space-y-2">
          <strong class="text-white font-black block">Automated Grade Isolation Architecture:</strong>
          <p class="text-slate-400">
            NextGrade automatically isolates content based on the active student's <code class="text-indigo-300">grade_level</code>. KG3 learners only see early childhood modules, while Year 6 students receive PSR exam quizzes. Non-supported grade profiles see friendly lock screens advising that their curriculum is in development.
          </p>
        </div>
      </div>
    </section>

    <!-- Section 5: Learning GAP Analysis & Question Inspector -->
    <section id="sec-analytics" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg font-black">
          5
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">Attempt Logging, GAP Analysis & Question Inspector</h2>
          <p class="text-xs font-bold text-slate-400">How the platform turns raw quiz answers into actionable early intervention data.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs text-slate-300 leading-relaxed font-semibold">
        <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-700/60 space-y-3">
          <h3 class="font-black text-white text-sm flex items-center gap-2">
            <span class="text-amber-400">🎯</span>
            <span>The 70% Mastery Benchmark</span>
          </h3>
          <p>
            Every finished quiz stores both summary metrics (<code class="text-indigo-300">quiz_sessions</code>) and question-by-question telemetry (<code class="text-indigo-300">quiz_session_answers</code>).
          </p>
          <p>
            When a child's average accuracy for any topic falls below <strong class="text-amber-300">70%</strong>, the system automatically triggers a <strong>Learning GAP alert</strong> on the Parent Portal.
          </p>
          <div class="p-3 bg-slate-800/90 rounded-xl border border-slate-700 text-slate-300">
            Each GAP item includes a customized parent guidance tip explaining why the child is struggling (e.g. confusing clock hands or letter blending) and a direct link to the 5-minute revision module.
          </div>
        </div>

        <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-700/60 space-y-3">
          <h3 class="font-black text-white text-sm flex items-center gap-2">
            <span class="text-indigo-400">🔍</span>
            <span>Question Inspector Modal</span>
          </h3>
          <p>
            Parents and Administrators can inspect past quiz sessions. Clicking <strong class="text-white">Inspect</strong> opens a detailed modal showing:
          </p>
          <ul class="space-y-1.5 pl-3 list-disc marker:text-indigo-400">
            <li>Full text and question type.</li>
            <li>The child's chosen answer vs. the correct answer.</li>
            <li>Visual badges: <span class="text-emerald-400">✓ Correct</span> vs <span class="text-rose-400">✗ Incorrect</span>.</li>
            <li>Whether the child clicked the audio hint during the test.</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Section 6: Operations & Maintenance Diagnostics -->
    <section id="sec-maintenance" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg font-black">
          6
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">System Operations, CLI Tools & Diagnostics</h2>
          <p class="text-xs font-bold text-slate-400">Command line utilities available in the codebase for verification and testing.</p>
        </div>
      </div>

      <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700 space-y-4 text-xs font-semibold">
        <p class="text-slate-300">
          The NextGrade codebase contains automated health check and diagnostic scripts in the <code class="text-indigo-300">scripts/</code> directory:
        </p>

        <div class="space-y-3">
          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 font-mono text-[11px] text-slate-300 flex flex-col gap-1">
            <span class="text-slate-500"># 1. Production Readiness & System Verification CLI (20 Deep Audits)</span>
            <span class="text-emerald-400 font-bold">php scripts/verify_production.php</span>
          </div>

          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 font-mono text-[11px] text-slate-300 flex flex-col gap-1">
            <span class="text-slate-500"># 2. Full 35-Point Automated Regression Test (Schema, Auth, Inspector, KG3 Gate)</span>
            <span class="text-indigo-400 font-bold">php scripts/test_all_features.php</span>
          </div>

          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 font-mono text-[11px] text-slate-300 flex flex-col gap-1">
            <span class="text-slate-500"># 3. Database Migration & Seed Script (Initializes Admins, Demo Parents, Kids)</span>
            <span class="text-indigo-400 font-bold">php scripts/migrate_v2.php</span>
          </div>

          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 font-mono text-[11px] text-slate-300 flex flex-col gap-1">
            <span class="text-slate-500"># 4. Kindergarten 3 (KG3) Grade Migration & Inspection Tool</span>
            <span class="text-indigo-400 font-bold">php scripts/update_kg3_grades.php</span>
          </div>
        </div>

        <div class="p-4 bg-slate-800/60 rounded-xl border border-slate-700/80 text-slate-400 text-xs">
          <strong>Database Schema Reference:</strong> Located in <code class="text-indigo-300">c:/xampp/htdocs/nextgrade/schema.sql</code>. Contains table definitions for <code class="text-slate-200">admins</code>, <code class="text-slate-200">parents</code>, <code class="text-slate-200">students</code>, <code class="text-slate-200">subjects</code>, <code class="text-slate-200">topics</code>, <code class="text-slate-200">questions</code>, <code class="text-slate-200">quiz_sessions</code>, and <code class="text-slate-200">quiz_session_answers</code>.
        </div>
      </div>
    </section>

    <!-- Section 7: System Verification & Production Readiness -->
    <section id="sec-verification" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-600/30 border border-emerald-500/40 text-emerald-400 flex items-center justify-center text-lg font-black">
          7
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">System Health & Verification Dashboard</h2>
          <p class="text-xs font-bold text-slate-400">Automated pre-flight validation before deploying NextGrade online to a public web server.</p>
        </div>
      </div>

      <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700 space-y-4 text-xs font-semibold">
        <p class="text-slate-300 leading-relaxed">
          The <strong class="text-white">System Verification Checker</strong> (<a href="verification.php" class="text-emerald-400 underline font-bold">admin/verification.php</a>) automatically checks 20 deep system parameters across 5 categories to ensure complete operational readiness:
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 pt-2">
          <div class="p-3.5 bg-slate-950/80 rounded-xl border border-slate-800 space-y-1">
            <span class="text-cyan-400 font-bold block">1. Database Health</span>
            <p class="text-slate-400 text-[11px]">Validates MySQL connection, ping latency, all 10 schema tables, columns, foreign keys, read/write transaction rollbacks, and record counts.</p>
          </div>
          <div class="p-3.5 bg-slate-950/80 rounded-xl border border-slate-800 space-y-1">
            <span class="text-indigo-400 font-bold block">2. Pages & Route Integrity</span>
            <p class="text-slate-400 text-[11px]">Lints all 32 PHP files for 0 syntax errors, tests HTTP status codes on public portals, and confirms security gates on maintenance scripts.</p>
          </div>
          <div class="p-3.5 bg-slate-950/80 rounded-xl border border-slate-800 space-y-1">
            <span class="text-emerald-400 font-bold block">3. Media & Assets</span>
            <p class="text-slate-400 text-[11px]">Scans all 173 referenced question illustrations on disk, ensuring 0 missing files, valid file sizes, and non-corrupt assets.</p>
          </div>
          <div class="p-3.5 bg-slate-950/80 rounded-xl border border-slate-800 space-y-1">
            <span class="text-amber-400 font-bold block">4. Admin & User Credentials</span>
            <p class="text-slate-400 text-[11px]">Verifies active System Admin accounts, tests password hashes, checks parent demo accounts, and verifies kid 4-digit PINs.</p>
          </div>
          <div class="p-3.5 bg-slate-950/80 rounded-xl border border-slate-800 space-y-1">
            <span class="text-purple-400 font-bold block">5. Online Server Config</span>
            <p class="text-slate-400 text-[11px]">Audits PHP version (>= 8.0), required extensions, session directory write permissions, and pre-flight notices (db password, SSL, display_errors).</p>
          </div>
          <div class="p-3.5 bg-emerald-950/30 rounded-xl border border-emerald-500/40 space-y-1 flex flex-col justify-between">
            <div>
              <span class="text-emerald-400 font-bold block">Launch Verification Audit</span>
              <p class="text-slate-300 text-[11px]">Run live interactive diagnostic scan with full visual scorecard and report export.</p>
            </div>
            <a href="verification.php" class="mt-2 inline-flex items-center gap-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 px-3 py-1.5 rounded-lg transition-colors w-fit">
              <span>Open Verification Tool</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
          </div>
        </div>
      </div>
    <!-- Section 8: Web Hosting Deployment & Production Setup Manual -->
    <section id="sec-hosting" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-6">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-600/30 border border-amber-500/40 text-amber-400 flex items-center justify-center text-lg font-black">
          8
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">Web Hosting Deployment & Step-by-Step Setup Manual</h2>
          <p class="text-xs font-bold text-slate-400">Complete walkthrough for uploading NextGrade, importing the database, and going live on cPanel, Plesk, or Cloud Hosting.</p>
        </div>
      </div>

      <!-- Overview Alert Banner -->
      <div class="bg-gradient-to-r from-amber-500/10 via-indigo-500/10 to-emerald-500/10 border border-amber-500/30 rounded-2xl p-5 text-xs text-slate-300 leading-relaxed space-y-2">
        <div class="flex items-center gap-2 text-amber-300 font-black text-sm">
          <span class="text-lg">🚀</span> Ready-to-Deploy Standalone Architecture
        </div>
        <p>
          NextGrade is pre-packaged for zero-downtime deployment to any standard PHP/MySQL web host (cPanel, Hostinger, SiteGround, Namecheap, Plesk, or VPS).
          The repository includes an all-in-one database file: <code class="text-amber-300 bg-slate-900 px-2 py-0.5 rounded font-mono font-bold">nextgrade_complete.sql</code> containing all 10 schema tables, 10 subjects, 48 topics, 48 revision modules, and 1,549+ verified syllabus questions.
        </p>
      </div>

      <!-- 6-Step Implementation Walkthrough -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-semibold">
        
        <!-- Step 1 -->
        <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700/80 space-y-2">
          <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-[10px] font-black uppercase tracking-wider">Step 1</span>
            <span class="text-lg">📁</span>
          </div>
          <h3 class="text-white font-black text-sm">Upload Project Files</h3>
          <p class="text-slate-400">
            Upload the entire NextGrade folder to your web hosting account via <strong class="text-slate-200">cPanel File Manager</strong> or <strong class="text-slate-200">FTP / SFTP</strong> (FileZilla):
          </p>
          <ul class="list-disc pl-4 space-y-1 text-slate-300 text-[11px]">
            <li>If installing on your main domain (e.g. <code class="text-indigo-300">https://yourdomain.com</code>): upload files directly inside <code class="text-amber-300">public_html/</code>.</li>
            <li>If installing in a subfolder (e.g. <code class="text-indigo-300">https://yourdomain.com/nextgrade</code>): create a <code class="text-amber-300">nextgrade/</code> folder inside <code class="text-slate-300">public_html/</code>.</li>
          </ul>
        </div>

        <!-- Step 2 -->
        <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700/80 space-y-2">
          <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-[10px] font-black uppercase tracking-wider">Step 2</span>
            <span class="text-lg">🗄️</span>
          </div>
          <h3 class="text-white font-black text-sm">Create MySQL Database & User</h3>
          <p class="text-slate-400">
            In your cPanel control panel, navigate to <strong class="text-slate-200">MySQL Database Wizard</strong> (or MySQL Databases):
          </p>
          <ul class="list-disc pl-4 space-y-1 text-slate-300 text-[11px]">
            <li><strong>Database Name:</strong> Create a new database (e.g. <code class="text-indigo-300">myuser_nextgrade</code>).</li>
            <li><strong>Database User:</strong> Create a dedicated user with a strong password.</li>
            <li><strong>Privileges:</strong> Assign the user to the database and select <code class="text-emerald-400">ALL PRIVILEGES</code>.</li>
          </ul>
        </div>

        <!-- Step 3 -->
        <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700/80 space-y-2">
          <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-black uppercase tracking-wider">Step 3 (Crucial)</span>
            <span class="text-lg">⚡</span>
          </div>
          <h3 class="text-white font-black text-sm">Import SQL via phpMyAdmin</h3>
          <p class="text-slate-400">
            Choose your preferred import method based on your hosting bandwidth and needs:
          </p>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
            <div class="bg-slate-950/80 p-3 rounded-xl border border-indigo-500/30 space-y-1.5">
              <span class="text-indigo-400 font-bold text-xs">Option A (Recommended & Lean):</span>
              <p class="text-[11px] text-slate-300">
                Import <code class="text-amber-300 font-mono">schema.sql</code> (only ~50KB). It sets up all tables with default Admin (<code class="text-indigo-300">@admin</code> / <code class="text-indigo-300">admin123</code>). Then open <code class="text-indigo-300">seed.php</code> in your browser to selectively seed KG3, PSR Brunei, or both!
              </p>
            </div>
            <div class="bg-slate-950/80 p-3 rounded-xl border border-slate-700/80 space-y-1.5">
              <span class="text-slate-300 font-bold text-xs">Option B (All-in-One Precompiled):</span>
              <p class="text-[11px] text-slate-300">
                Import <code class="text-amber-300 font-mono">nextgrade_complete.sql</code> (~1.5MB). Contains all 1,549+ questions and topics pre-loaded in a single file ready to run.
              </p>
            </div>
          </div>
          <ol class="list-decimal pl-4 space-y-1 text-slate-300 text-[11px] pt-1">
            <li>Open <strong class="text-white">phpMyAdmin</strong> from your hosting dashboard.</li>
            <li>Click on your new database name in the left navigation sidebar.</li>
            <li>Click the <strong class="text-indigo-300">Import</strong> tab at the top.</li>
            <li>Click <em>Choose File</em> and select <code class="text-amber-300 font-mono">schema.sql</code> (or <code class="text-amber-300 font-mono">nextgrade_complete.sql</code>).</li>
            <li>Click <strong class="text-emerald-400">Import / Go</strong> at the bottom. Success will show within seconds.</li>
          </ol>
        </div>

        <!-- Step 4 -->
        <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700/80 space-y-2">
          <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-[10px] font-black uppercase tracking-wider">Step 4</span>
            <span class="text-lg">⚙️</span>
          </div>
          <h3 class="text-white font-black text-sm">Configure db.php Credentials</h3>
          <p class="text-slate-400">
            Open <code class="text-indigo-300">db.php</code> in your file manager and update lines 18-20 with your hosting database credentials:
          </p>
          <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-800 font-mono text-[11px] text-indigo-300">
            $host = 'localhost';<br>
            $db   = 'myuser_nextgrade';<br>
            $user = 'myuser_dbuser';<br>
            $pass = 'your_super_secret_password';
          </div>
          <p class="text-[11px] text-slate-400">
            <em>Note on BASE_URL:</em> <code class="text-indigo-300">db.php</code> includes automatic URL path detection. Whether running at root (<code class="text-slate-300">/</code>) or subdirectory (<code class="text-slate-300">/nextgrade/</code>), links will resolve seamlessly.
          </p>
        </div>

        <!-- Step 5 -->
        <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700/80 space-y-2">
          <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-black uppercase tracking-wider">Step 5</span>
            <span class="text-lg">🩺</span>
          </div>
          <h3 class="text-white font-black text-sm">Run Online Verification Check</h3>
          <p class="text-slate-400">
            Open your browser and navigate to the automated verification tool:
          </p>
          <div class="p-2.5 bg-slate-950 rounded-xl border border-slate-800 font-mono text-[11px] text-emerald-300">
            https://yourdomain.com/admin/verification.php
          </div>
          <p class="text-[11px] text-slate-400">
            The verification engine executes 20 deep health tests across database integrity, question counts, file paths, and PHP extensions to guarantee online readiness.
          </p>
        </div>

        <!-- Step 6 -->
        <div class="bg-slate-900/70 p-5 rounded-2xl border border-slate-700/80 space-y-2">
          <div class="flex items-center justify-between">
            <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-black uppercase tracking-wider">Step 6 (Security)</span>
            <span class="text-lg">🛡️</span>
          </div>
          <h3 class="text-white font-black text-sm">Admin Login & Password Hardening</h3>
          <p class="text-slate-400">
            Log in to the System Admin portal using default initial credentials:
          </p>
          <ul class="list-disc pl-4 space-y-1 text-slate-300 text-[11px]">
            <li><strong>Login URL:</strong> <code class="text-indigo-300">https://yourdomain.com/admin/login.php</code></li>
            <li><strong>Default Username:</strong> <code class="text-white font-mono">admin</code></li>
            <li><strong>Default Password:</strong> <code class="text-white font-mono">admin123</code></li>
            <li><strong class="text-rose-400">Action Required:</strong> Immediately open <a href="profile.php" class="text-indigo-400 underline font-bold">My Profile</a> and change your username and password before making the site public.</li>
          </ul>
        </div>

      </div>

      <!-- Quick Comparison: schema.sql vs nextgrade_complete.sql -->
      <div class="bg-slate-900/90 rounded-2xl border border-slate-700 p-4 space-y-2 text-xs">
        <strong class="text-white font-black block text-sm flex items-center gap-2">
          <i class="fa-solid fa-file-code text-indigo-400"></i>
          <span>Database SQL Files Explained: Which one should I use?</span>
        </strong>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1 text-[11px] text-slate-300">
          <div class="p-3 bg-slate-950/70 rounded-xl border border-slate-800 space-y-1">
            <div class="font-bold text-amber-300 font-mono">nextgrade_complete.sql (Recommended)</div>
            <p class="text-slate-400">
              <strong>All-in-one database export:</strong> Contains both the 10 schema table structures AND all 1,549+ syllabus questions, topics, revisions, and accounts. <em>Import this file for your web host to be 100% ready immediately without running seed scripts.</em>
            </p>
          </div>
          <div class="p-3 bg-slate-950/70 rounded-xl border border-slate-800 space-y-1">
            <div class="font-bold text-indigo-300 font-mono">schema.sql (Pure DDL)</div>
            <p class="text-slate-400">
              <strong>Empty schema definition:</strong> Contains only table structures, indexes, and foreign keys without questions or rows. Useful for automated CI/CD pipelines or clean development testing.
            </p>
          </div>
        </div>
      </div>

    </section>

  </main>

  <!-- Footer -->
  <footer class="mt-auto border-t border-slate-800 py-6 text-center text-xs font-bold text-slate-500">
    NextGrade Educational Operating System • System Admin Operations Manual v2.0
  </footer>

</body>
</html>
