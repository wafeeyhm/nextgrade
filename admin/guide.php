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
            <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Parents Accounts (CRUD)
          </a>
          <a href="kids.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-graduation-cap mr-1.5 text-slate-400"></i> Students & Kids
          </a>
          <a href="guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-book-open mr-1.5 text-indigo-400"></i> System Guide
          </a>
          <a href="developer_guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-code mr-1.5 text-slate-400"></i> Question & Dev Guide
          </a>
          <a href="profile.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-user-gear mr-1.5 text-slate-400"></i> My Profile
          </a>
        </div>
      </div>

      <!-- Admin Status & Actions -->
      <div class="flex items-center gap-3">
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

      <div class="flex items-center gap-3 shrink-0">
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
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
      <a href="#sec-architecture" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">🏛️</div>
        <span class="block text-xs font-black text-white">Architecture</span>
        <span class="text-[10px] text-slate-400 font-bold">Role Matrix</span>
      </a>

      <a href="#sec-parents" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">👨‍👩‍👧‍👦</div>
        <span class="block text-xs font-black text-white">Parent CRUD</span>
        <span class="text-[10px] text-slate-400 font-bold">Account Lifecycle</span>
      </a>

      <a href="#sec-kids" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">🧒</div>
        <span class="block text-xs font-black text-white">Kids Login</span>
        <span class="text-[10px] text-slate-400 font-bold">PIN & Avatars</span>
      </a>

      <a href="#sec-kg3" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">🎓</div>
        <span class="block text-xs font-black text-white">KG3 Curriculum</span>
        <span class="text-[10px] text-slate-400 font-bold">1,350+ Questions</span>
      </a>

      <a href="#sec-analytics" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">📊</div>
        <span class="block text-xs font-black text-white">GAP Analysis</span>
        <span class="text-[10px] text-slate-400 font-bold">Inspector Tool</span>
      </a>

      <a href="#sec-maintenance" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-indigo-500/50 p-4 rounded-2xl text-center transition-all group">
        <div class="text-2xl mb-1.5 group-hover:scale-110 transition-transform">🛠️</div>
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

    <!-- Section 4: Kindergarten 3 (KG3) Curriculum & Question Bank -->
    <section id="sec-kg3" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-5">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/30 border border-indigo-500/40 text-indigo-400 flex items-center justify-center text-lg font-black">
          4
        </div>
        <div>
          <h2 class="text-xl font-black text-white tracking-tight">Kindergarten 3 (KG3) Question Bank & Restriction Logic</h2>
          <p class="text-xs font-bold text-slate-400">Understanding why questions are currently exclusive to KG3 students and how gating works.</p>
        </div>
      </div>

      <div class="space-y-4 text-xs font-semibold text-slate-300 leading-relaxed">
        <div class="bg-amber-500/10 border border-amber-500/30 rounded-2xl p-4 text-amber-200 flex items-start gap-3">
          <span class="text-xl shrink-0">⚠️</span>
          <div>
            <strong class="text-white font-black block">Important Curriculum Directive:</strong>
            All current <strong>~1,350+ questions</strong> across all 5 subjects are strictly tailored for <strong>Kindergarten 3 (KG3)</strong> (ages 5–6). 
            If a student is registered with a different grade level (e.g. Year 1, Year 2, Year 3), the system prevents them from opening quizzes or printing worksheets until syllabus questions for their grade are uploaded.
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-2">
          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🇲🇾</span>
            <strong class="text-white text-xs block font-black">Bahasa Melayu</strong>
            <span class="text-[10px] text-slate-400">Suku Kata, Perkataan Mudah, Pemahaman Cerita</span>
          </div>

          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🇬🇧</span>
            <strong class="text-white text-xs block font-black">English</strong>
            <span class="text-[10px] text-slate-400">Phonics, Sight Words, Simple Sentences</span>
          </div>

          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🔢</span>
            <strong class="text-white text-xs block font-black">Mathematics</strong>
            <span class="text-[10px] text-slate-400">Counting 1-20, Addition, Subtraction, Shapes & Clock</span>
          </div>

          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">🌱</span>
            <strong class="text-white text-xs block font-black">Science</strong>
            <span class="text-[10px] text-slate-400">Living Things, Body Parts, Animals & Plants</span>
          </div>

          <div class="bg-slate-900/70 p-3.5 rounded-xl border border-slate-700 text-center">
            <span class="text-2xl block mb-1">💻</span>
            <strong class="text-white text-xs block font-black">ICT & Tech</strong>
            <span class="text-[10px] text-slate-400">Hardware Basics, Mouse Skills, Safe Tech Habits</span>
          </div>
        </div>

        <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-700 space-y-2">
          <strong class="text-white font-black block">Technical Enforcement Mechanism:</strong>
          <p class="text-slate-400">
            Protected endpoints (<code class="text-indigo-300">api/quiz.php</code> and <code class="text-indigo-300">api/worksheet.php</code>) invoke <code class="text-indigo-300">isGradeKG3()</code>. If non-KG3, the server returns <code class="text-amber-400">HTTP 403 Forbidden</code> with <code class="text-amber-300 font-mono">{"is_kg3_only": true}</code>. On the front-end, friendly locked banners inform the learner that questions for their grade level are currently under active development.
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
            <span class="text-slate-500"># 1. Full 35-Point Automated Regression Test (Schema, Auth, Inspector, KG3 Gate)</span>
            <span class="text-indigo-400 font-bold">php scripts/test_all_features.php</span>
          </div>

          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 font-mono text-[11px] text-slate-300 flex flex-col gap-1">
            <span class="text-slate-500"># 2. Database Migration & Seed Script (Initializes Admins, Demo Parents, Kids)</span>
            <span class="text-indigo-400 font-bold">php scripts/migrate_v2.php</span>
          </div>

          <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 font-mono text-[11px] text-slate-300 flex flex-col gap-1">
            <span class="text-slate-500"># 3. Kindergarten 3 (KG3) Grade Migration & Inspection Tool</span>
            <span class="text-indigo-400 font-bold">php scripts/update_kg3_grades.php</span>
          </div>
        </div>

        <div class="p-4 bg-slate-800/60 rounded-xl border border-slate-700/80 text-slate-400 text-xs">
          <strong>Database Schema Reference:</strong> Located in <code class="text-indigo-300">c:/xampp/htdocs/nextgrade/schema.sql</code>. Contains table definitions for <code class="text-slate-200">admins</code>, <code class="text-slate-200">parents</code>, <code class="text-slate-200">students</code>, <code class="text-slate-200">subjects</code>, <code class="text-slate-200">topics</code>, <code class="text-slate-200">questions</code>, <code class="text-slate-200">quiz_sessions</code>, and <code class="text-slate-200">quiz_session_answers</code>.
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
