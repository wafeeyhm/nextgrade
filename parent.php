<?php
// NextGrade - Parent Portal: Multi-Kid Management (CRUD), Previous Attempt Inspector & GAP Analysis
require_once __DIR__ . '/auth_helper.php';
requireParent();

$parent = getParentUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Parent Portal - NextGrade Child Progress & GAP Analysis</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="js/sounds.js"></script>
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
</head>

<body class="min-h-screen bg-slate-50 p-3 md:p-6 text-slate-800">

  <div class="max-w-6xl w-full mx-auto flex flex-col gap-6">

    <!-- Header Bar -->
    <header class="bg-white px-5 py-4 rounded-3xl shadow-sm border-2 border-slate-200 flex flex-wrap items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <a
          href="index.php"
          onclick="SoundEffects.playPop();"
          class="btn-chunky btn-white text-xs md:text-sm py-2 px-3 rounded-xl flex items-center gap-1.5">
          <span>⬅️</span>
          <span class="font-black">Child Gate</span>
        </a>

        <div>
          <h1 class="text-xl md:text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
            <span>👨‍👩‍👧</span> Parent Portal
          </h1>
          <p class="text-xs font-bold text-slate-400">
            Family Account: <span class="text-sky-600 font-mono"><?= htmlspecialchars($parent['parent_code']) ?></span> • <span id="header-parent-name"><?= htmlspecialchars($parent['full_name']) ?></span>
          </p>
        </div>
      </div>

      <!-- Right Controls: Child Selector, Profile, Guide & Logout -->
      <div class="flex items-center gap-2 sm:gap-3">
        <div class="flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-2xl border border-slate-200">
          <label for="filter-kid-select" class="text-[11px] font-black text-slate-500 uppercase">Child:</label>
          <select
            id="filter-kid-select"
            onchange="onKidFilterChange(this.value)"
            class="bg-white border border-slate-300 text-slate-800 font-extrabold text-xs rounded-xl px-2.5 py-1.5 focus:border-sky-400 focus:outline-none cursor-pointer">
            <option value="">All My Kids</option>
          </select>
        </div>

        <button
          onclick="switchTab('profile')"
          title="Edit Profile & Password"
          class="bg-slate-100 hover:bg-sky-50 text-slate-700 hover:text-sky-700 border border-slate-200 hover:border-sky-200 text-xs font-bold py-2 px-3 rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer">
          <i class="fa-solid fa-user-gear text-sky-500"></i>
          <span class="hidden sm:inline">Profile</span>
        </button>

        <button
          onclick="switchTab('guide')"
          title="Open Parent User Guide"
          class="bg-slate-100 hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 hover:border-amber-200 text-xs font-bold py-2 px-3 rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer">
          <i class="fa-solid fa-book-open text-amber-500"></i>
          <span class="hidden sm:inline">Guide</span>
        </button>

        <a
          href="parent_logout.php"
          onclick="SoundEffects.playPop();"
          title="Sign Out"
          class="bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 border border-slate-200 hover:border-rose-200 text-xs font-bold py-2 px-3 rounded-xl transition-colors flex items-center gap-1.5">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <span class="hidden sm:inline">Logout</span>
        </a>
      </div>
    </header>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b-2 border-slate-200 pb-2 overflow-x-auto">
      <button
        id="tab-btn-insights"
        onclick="switchTab('insights')"
        class="px-5 py-2.5 rounded-2xl font-black text-sm flex items-center gap-2 bg-sky-500 text-white shadow-md transition-all cursor-pointer shrink-0">
        <span>📊</span>
        <span>Learning Progress & GAP Analysis</span>
      </button>

      <button
        id="tab-btn-kids"
        onclick="switchTab('kids')"
        class="px-5 py-2.5 rounded-2xl font-black text-sm flex items-center gap-2 bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer shrink-0">
        <span>🧒</span>
        <span>My Kids & Access Control (<span id="tab-kids-badge">0</span>)</span>
      </button>

      <button
        id="tab-btn-profile"
        onclick="switchTab('profile')"
        class="px-5 py-2.5 rounded-2xl font-black text-sm flex items-center gap-2 bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer shrink-0">
        <span>⚙️</span>
        <span>My Profile & Credentials</span>
      </button>

      <button
        id="tab-btn-guide"
        onclick="switchTab('guide')"
        class="px-5 py-2.5 rounded-2xl font-black text-sm flex items-center gap-2 bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer shrink-0">
        <span>📖</span>
        <span>Parent User Guide</span>
      </button>
    </div>

    <!-- ================= TAB 1: LEARNING PROGRESS, GAP ANALYSIS & ATTEMPTS ================= -->
    <div id="tab-content-insights" class="space-y-6">

      <!-- 1. Key Statistics Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center gap-4">
          <div class="text-4xl p-3 bg-sky-50 rounded-2xl">📝</div>
          <div>
            <span class="block text-xs font-black uppercase text-slate-400">Total Quizzes</span>
            <span id="stat-total-sessions" class="text-2xl md:text-3xl font-black text-sky-600">0</span>
          </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border-2 border-slate-200 shadow-sm flex items-center gap-4">
          <div class="text-4xl p-3 bg-emerald-50 rounded-2xl">🎯</div>
          <div>
            <span class="block text-xs font-black uppercase text-slate-400">Overall Accuracy</span>
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

      <!-- 2. SMART GAP IDENTIFICATION & WEAKNESS BREAKDOWN (Requirement 3) -->
      <div class="bg-gradient-to-br from-amber-50/70 via-white to-rose-50/50 p-6 md:p-8 rounded-[2rem] border-3 border-amber-200 shadow-md space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-2">
          <div>
            <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-800 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider mb-1">
              <i class="fa-solid fa-bullseye"></i> AI & Curriculum Insights
            </span>
            <h2 class="text-xl md:text-2xl font-black text-slate-800 flex items-center gap-2">
              <span>🎯</span> Identified Learning Gaps & Actionable Guidance
            </h2>
            <p class="text-xs font-bold text-slate-500">
              Topics where accuracy is below 70%. Reviewing mistakes helps bridge learning gaps early.
            </p>
          </div>
          <span id="gaps-count-badge" class="bg-amber-500 text-white font-black text-xs px-3 py-1.5 rounded-xl shadow-sm">
            0 Gaps Found
          </span>
        </div>

        <!-- GAP Items Container -->
        <div id="gap-analysis-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
          <div class="col-span-full text-center py-6 text-slate-400 font-bold">
            Checking learning data...
          </div>
        </div>
      </div>

      <!-- 3. Subject Mastery Breakdown & Strengths -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Subject Breakdown -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg md:text-xl font-black text-slate-800 flex items-center gap-2">
              <span>📊</span> Subject Performance Breakdown
            </h2>
            <span class="text-xs font-bold text-slate-400">All 5 Core Subjects</span>
          </div>
          <div id="subject-breakdown-list" class="space-y-4">
            <div class="text-center py-6 text-slate-400 font-bold">Loading subjects...</div>
          </div>
        </div>

        <!-- Strengths (≥ 70%) -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-emerald-200 shadow-sm flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg md:text-xl font-black text-emerald-800 flex items-center gap-2">
                <span>🌟</span> High Mastery Strengths (≥ 70%)
              </h2>
              <span class="text-xs font-bold text-emerald-600 font-black">Well Done!</span>
            </div>
            <div id="strengths-list" class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
              <p class="text-xs font-bold text-slate-400 py-3">No evaluation records yet.</p>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-emerald-100 flex items-center justify-between text-xs text-emerald-700 font-bold">
            <span>Encourage your child's strong topics with praise!</span>
            <span>🏆</span>
          </div>
        </div>

      </div>

      <!-- 4. HISTORICAL QUIZ ATTEMPTS LOG TABLE (Requirement 3: Previous attempts & Answer inspection) -->
      <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-2">
          <div>
            <h2 class="text-xl md:text-2xl font-black text-slate-800 flex items-center gap-2">
              <span>📜</span> Previous Quiz Attempts Log
            </h2>
            <p class="text-xs font-bold text-slate-400">
              Click on any attempt to inspect every single question, correct answer, and your child's given answer.
            </p>
          </div>
          <span class="text-xs font-black bg-slate-100 text-slate-600 px-3 py-1.5 rounded-xl">
            Detailed Question Inspector
          </span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm font-bold">
            <thead>
              <tr class="border-b-2 border-slate-200 text-slate-400 uppercase text-xs">
                <th class="py-3 px-3">Date & Time</th>
                <th class="py-3 px-3">Child</th>
                <th class="py-3 px-3">Subject & Topic</th>
                <th class="py-3 px-3 text-center">Score</th>
                <th class="py-3 px-3 text-center">Accuracy</th>
                <th class="py-3 px-3 text-center">Time</th>
                <th class="py-3 px-3 text-right">Inspect Questions</th>
              </tr>
            </thead>
            <tbody id="sessions-table-body" class="divide-y divide-slate-100 text-slate-700">
              <tr>
                <td colspan="7" class="text-center py-8 text-slate-400">Loading quiz sessions...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- ================= TAB 2: MY KIDS & ACCESS CONTROL (Requirement 1: Kid CRUD) ================= -->
    <div id="tab-content-kids" class="hidden space-y-6">

      <!-- Top Action Banner -->
      <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <span class="inline-flex items-center gap-1.5 bg-sky-100 text-sky-800 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider mb-2">
            <span>👶</span> Multi-Kid User Access
          </span>
          <h2 class="text-2xl font-black text-slate-800">My Children Accounts</h2>
          <p class="text-xs font-bold text-slate-400 mt-1">
            Manage your kids' access profiles. Each kid has a simple username and 4-digit PIN for easy login on tablets.
          </p>
        </div>

        <button
          onclick="openAddKidModal()"
          class="btn-chunky btn-primary text-sm py-3 px-5 rounded-2xl shadow-md flex items-center gap-2 cursor-pointer shrink-0">
          <i class="fa-solid fa-plus"></i>
          <span>Add Child Profile</span>
        </button>
      </div>

      <!-- Kids Profiles Cards Grid -->
      <div id="kids-cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-full text-center py-10 text-slate-400 font-bold">
          Loading children profiles...
        </div>
      </div>

    </div>

    <!-- ================= TAB 3: MY PROFILE & CREDENTIALS (Requirement 1: Parent Profile Update) ================= -->
    <div id="tab-content-profile" class="hidden space-y-6">

      <!-- Top Action Banner -->
      <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <span class="inline-flex items-center gap-1.5 bg-sky-100 text-sky-800 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider mb-2">
            <span>⚙️</span> Account Settings
          </span>
          <h2 class="text-2xl font-black text-slate-800">My Profile & Security Credentials</h2>
          <p class="text-xs font-bold text-slate-400 mt-1">
            Update your contact details, login username, and account password anytime.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <button
            onclick="switchTab('guide')"
            class="btn-chunky btn-white text-xs py-2.5 px-4 rounded-xl font-bold flex items-center gap-1.5">
            <span>📖</span> <span>Need Help? View Guide</span>
          </button>
        </div>
      </div>

      <!-- Status Alert Container -->
      <div id="profile-status-alert" class="hidden p-4 rounded-2xl text-xs font-bold border transition-all"></div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Parent Identity Card -->
        <div class="lg:col-span-1 space-y-6">
          <div class="bg-white rounded-[2rem] border-2 border-slate-200 p-6 shadow-sm text-center flex flex-col items-center">
            <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-sky-400 to-indigo-500 text-white text-4xl flex items-center justify-center shadow-lg shadow-sky-400/20 mb-4 border-2 border-white">
              👨‍👩‍👧
            </div>

            <h3 id="profile-card-name" class="text-lg font-black text-slate-800"><?= htmlspecialchars($parent['full_name']) ?></h3>
            <span id="profile-card-user" class="inline-block mt-0.5 text-xs font-bold text-sky-600 font-mono">
              @<?= htmlspecialchars($parent['username']) ?>
            </span>

            <!-- Family Code Badge with Copy -->
            <div class="w-full mt-4 p-3 bg-sky-50 rounded-2xl border-2 border-dashed border-sky-200 flex items-center justify-between">
              <div class="text-left">
                <span class="block text-[10px] font-black uppercase tracking-wider text-sky-700">Family Code</span>
                <span id="profile-card-code" class="text-sm font-black font-mono text-slate-800"><?= htmlspecialchars($parent['parent_code']) ?></span>
              </div>
              <button
                onclick="copyParentCode()"
                id="btn-copy-code"
                class="btn-chunky btn-white text-[11px] py-1.5 px-2.5 rounded-xl font-black text-sky-700 hover:text-sky-800"
                title="Copy Family Code">
                <i class="fa-regular fa-copy"></i> Copy
              </button>
            </div>

            <div class="w-full mt-5 pt-4 border-t border-slate-100 text-left space-y-2.5 text-xs">
              <div class="flex items-center justify-between">
                <span class="text-slate-400 font-bold">Email:</span>
                <span id="profile-card-email" class="font-bold text-slate-700 truncate max-w-[170px]"><?= htmlspecialchars($parent['email']) ?></span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-400 font-bold">Phone:</span>
                <span id="profile-card-phone" class="font-bold text-slate-700"><?= htmlspecialchars($parent['phone'] ?: 'Not provided') ?></span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-400 font-bold">Account Type:</span>
                <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Verified Parent</span>
              </div>
            </div>
          </div>

          <!-- Safe Family Tip -->
          <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-[2rem] border-2 border-amber-200 p-5 text-xs text-amber-900 space-y-2">
            <h4 class="font-black text-amber-950 flex items-center gap-1.5 text-sm">
              <span>🛡️</span> Family Security Notice
            </h4>
            <p class="font-semibold text-amber-800 leading-relaxed">
              Your parent credentials allow you to create or delete your children's profiles and view their test records. Keep your password safe. Your children do not need your password to take quizzes.
            </p>
          </div>
        </div>

        <!-- Right Column: Profile Edit Form -->
        <div class="lg:col-span-2">
          <form id="parent-profile-form" onsubmit="handleSaveParentProfile(event)" class="bg-white rounded-[2rem] border-2 border-slate-200 p-6 md:p-8 shadow-sm space-y-6">

            <!-- Section 1: Contact Information -->
            <div>
              <h3 class="text-base font-black text-slate-800 flex items-center gap-2 mb-1">
                <span>👤</span> Personal & Contact Details
              </h3>
              <p class="text-xs font-bold text-slate-400 mb-4">
                Your name and contact details are used for family reports and recovery.
              </p>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Full Name *</label>
                  <input
                    type="text"
                    id="profile-name"
                    required
                    value="<?= htmlspecialchars($parent['full_name']) ?>"
                    class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>

                <div>
                  <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Login Username *</label>
                  <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-mono text-sm">@</span>
                    <input
                      type="text"
                      id="profile-username"
                      required
                      value="<?= htmlspecialchars($parent['username']) ?>"
                      class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl pl-8 pr-4 py-3 text-sm font-bold font-mono text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Email Address *</label>
                  <input
                    type="email"
                    id="profile-email"
                    required
                    value="<?= htmlspecialchars($parent['email']) ?>"
                    class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>

                <div>
                  <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                  <input
                    type="text"
                    id="profile-phone"
                    value="<?= htmlspecialchars($parent['phone']) ?>"
                    placeholder="e.g. +6012-3456789"
                    class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
              </div>
            </div>

            <!-- Section 2: Change Password -->
            <div class="pt-6 border-t-2 border-slate-100">
              <h3 class="text-base font-black text-slate-800 flex items-center gap-2 mb-1">
                <span>🔒</span> Change Parent Password
              </h3>
              <p class="text-xs font-bold text-slate-400 mb-4">
                Leave blank if you do not want to change your password.
              </p>

              <div class="space-y-4">
                <div>
                  <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Current Password</label>
                  <input
                    type="password"
                    id="profile-cur-pass"
                    placeholder="Required only when setting a new password"
                    class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">New Password</label>
                    <input
                      type="password"
                      id="profile-new-pass"
                      placeholder="Min. 6 characters"
                      class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                  </div>

                  <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                    <input
                      type="password"
                      id="profile-conf-pass"
                      placeholder="Re-type new password"
                      class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                  </div>
                </div>
              </div>
            </div>

            <!-- Buttons -->
            <div class="pt-6 border-t-2 border-slate-100 flex items-center justify-end gap-3">
              <button
                type="submit"
                id="btn-save-profile"
                class="btn-chunky btn-primary py-3 px-6 rounded-2xl text-sm font-black shadow-md flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Save Profile Changes</span>
              </button>
            </div>

          </form>
        </div>

      </div>

    </div>

    <!-- ================= TAB 4: PARENT USER GUIDE (Requirement 4: Parent User Guide) ================= -->
    <div id="tab-content-guide" class="hidden space-y-6">

      <!-- Top Action Banner -->
      <div class="bg-gradient-to-r from-sky-500 to-indigo-600 p-6 md:p-8 rounded-[2rem] text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
          <span class="inline-flex items-center gap-1.5 bg-white/20 text-white text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider mb-2">
            <span>📖</span> Parent Academy & Help Center
          </span>
          <h2 class="text-2xl md:text-3xl font-black">NextGrade Parent User Guide</h2>
          <p class="text-xs md:text-sm font-bold text-sky-100 mt-1 max-w-2xl">
            Everything you need to know about setting up your children's profiles, understanding Kindergarten 3 (KG3) questions, tracking quiz attempts, and closing learning gaps.
          </p>
        </div>

        <button
          onclick="switchTab('kids')"
          class="bg-white hover:bg-slate-50 text-sky-700 font-black text-xs py-3 px-5 rounded-2xl shadow-md transition-all flex items-center gap-2 shrink-0 cursor-pointer">
          <span>🧒</span> <span>Go to My Kids</span>
        </button>
      </div>

      <!-- Guide Cards Grid -->
      <div class="space-y-6">

        <!-- Guide Step 1: Setting Up Kid Accounts -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-sky-700 font-black flex items-center justify-center text-lg shrink-0">
              1
            </div>
            <div>
              <h3 class="text-lg md:text-xl font-black text-slate-800">Setting Up Your Children's Profiles (CRUD)</h3>
              <p class="text-xs font-bold text-slate-400">Add, edit, or customize accounts for each child in your family.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs text-slate-600 font-semibold space-y-2">
              <div class="text-2xl">🏷️</div>
              <strong class="text-slate-800 font-black block">Simple Lowercase Username</strong>
              <p>Choose an easy-to-remember username like <code class="text-sky-600 font-mono font-bold">lana</code> or <code class="text-sky-600 font-mono font-bold">adam</code> that your child can easily type if prompted.</p>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs text-slate-600 font-semibold space-y-2">
              <div class="text-2xl">🔢</div>
              <strong class="text-slate-800 font-black block">Memorable 4-Digit PIN</strong>
              <p>Assign a simple 4-digit PIN (default <code class="text-amber-600 font-mono font-bold">1234</code>) so young children can log in independently without complex passwords.</p>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs text-slate-600 font-semibold space-y-2">
              <div class="text-2xl">🎨</div>
              <strong class="text-slate-800 font-black block">Fun Cartoon Avatar</strong>
              <p>Pick a fun profile character: Star Kid ⭐, Bunny 🐰, Astronaut 🚀, Dino 🦖, Kitten 🐱, or Unicorn 🦄 so your child can spot their account immediately.</p>
            </div>
          </div>
        </div>

        <!-- Guide Step 2: Child Login Experience -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 font-black flex items-center justify-center text-lg shrink-0">
              2
            </div>
            <div>
              <h3 class="text-lg md:text-xl font-black text-slate-800">Child Login Experience on Tablets & Phones</h3>
              <p class="text-xs font-bold text-slate-400">Zero complicated passwords or email logins for children.</p>
            </div>
          </div>

          <div class="bg-emerald-50/70 border-2 border-emerald-200 rounded-2xl p-5 text-xs text-emerald-900 font-semibold space-y-3 leading-relaxed">
            <p>
              When your child visits the NextGrade home page, they are greeted by the friendly <strong>Child Login Gate</strong>. All family children appear on screen as colorful cards.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
              <div class="bg-white p-3.5 rounded-xl border border-emerald-200">
                <span class="font-black text-emerald-800 block mb-1">Step 1: Tap Avatar</span>
                <span>Your child taps on their friendly avatar photo.</span>
              </div>
              <div class="bg-white p-3.5 rounded-xl border border-emerald-200">
                <span class="font-black text-emerald-800 block mb-1">Step 2: Enter PIN</span>
                <span>They tap in their 4-digit PIN code (<code class="font-mono text-emerald-700">1234</code>).</span>
              </div>
              <div class="bg-white p-3.5 rounded-xl border border-emerald-200">
                <span class="font-black text-emerald-800 block mb-1">Step 3: Start Quizzing!</span>
                <span>Instant access to interactive quizzes with speech audio!</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Guide Step 3: Curriculums Notice (KG3 & Year 6 PSR) -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-800 font-black flex items-center justify-center text-lg shrink-0">
              3
            </div>
            <div>
              <h3 class="text-lg md:text-xl font-black text-slate-800">Understanding Curriculums: KG3 & Year 6 (PSR Brunei)</h3>
              <p class="text-xs font-bold text-slate-400">Strictly segregated question banks for early learners and PSR candidates.</p>
            </div>
          </div>

          <div class="space-y-3 text-xs text-slate-600 font-semibold leading-relaxed">
            <p>
              NextGrade provides two comprehensive, strictly isolated syllabuses with over <strong>1,500+ questions</strong>. Grade isolation is absolute—younger children are never overwhelmed with Year 6 questions, and PSR candidates only practice authentic Year 6 exam questions:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="p-4 bg-sky-50 rounded-2xl border border-sky-200 space-y-2">
                <div class="flex items-center gap-2">
                  <span class="text-xl">⭐</span>
                  <strong class="text-sky-950 font-black text-sm">Kindergarten 3 (KG3):</strong>
                </div>
                <p class="text-sky-900">
                  Children enrolled under <strong>Kindergarten 3 (KG3)</strong> access early learning modules across 5 foundational subjects: Bahasa Melayu, English, Mathematics, Science, and ICT (~919 questions, 27 topics).
                </p>
              </div>

              <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 space-y-2">
                <div class="flex items-center gap-2">
                  <span class="text-xl">🇧🇳</span>
                  <strong class="text-emerald-950 font-black text-sm">Year 6 (PSR Brunei):</strong>
                </div>
                <p class="text-emerald-900">
                  Candidates prepping for the <strong>Penilaian Sekolah Rendah (PSR)</strong> exam access 5 core Brunei SPN21 subjects: Mathematics, Science, English, Bahasa Melayu, and Melayu Islam Beraja (MIB) with <strong>at least 30 questions per topic</strong> (630 questions, 21 topics).
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Guide Step 4: Progress Tracking & GAP Inspector -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 font-black flex items-center justify-center text-lg shrink-0">
              4
            </div>
            <div>
              <h3 class="text-lg md:text-xl font-black text-slate-800">Tracking Progress & Using the GAP Analysis Inspector</h3>
              <p class="text-xs font-bold text-slate-400">Discover where your child excels and where they need a helping hand.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs text-slate-600 font-semibold leading-relaxed">
            <div class="space-y-3">
              <h4 class="font-black text-slate-800 text-sm flex items-center gap-2">
                <span class="text-amber-500">🎯</span>
                <span>Identified Learning Gaps (Accuracy &lt; 70%)</span>
              </h4>
              <p>
                NextGrade monitors your child's accuracy across every topic. If their average accuracy on any topic drops below <strong>70%</strong>, the system marks it as an <strong>Identified GAP</strong>.
              </p>
              <p>
                Each gap comes with practical <strong>Parent Guidance Tips</strong> (e.g. how to teach letter blending or telling time) and a link to a bite-sized <strong>5-minute interactive revision</strong> module.
              </p>
            </div>

            <div class="space-y-3">
              <h4 class="font-black text-slate-800 text-sm flex items-center gap-2">
                <span class="text-sky-500">🔍</span>
                <span>The Question Inspector Tool</span>
              </h4>
              <p>
                In the <strong>Recent Quiz Attempts</strong> table on the dashboard, click the <strong class="text-sky-600">"Inspect Answers"</strong> button next to any quiz attempt.
              </p>
              <p>
                The Inspector modal opens to reveal:
              </p>
              <ul class="list-disc pl-4 space-y-1 text-slate-500">
                <li>Every single question prompt and image.</li>
                <li>Your child's chosen answer vs. the correct answer.</li>
                <li>Whether your child clicked the audio hint.</li>
                <li>Color-coded green (correct) and red (incorrect) badges.</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Guide Step 5: Handwriting Worksheets -->
        <div class="bg-white p-6 md:p-8 rounded-[2rem] border-2 border-slate-200 shadow-sm space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-700 font-black flex items-center justify-center text-lg shrink-0">
              5
            </div>
            <div>
              <h3 class="text-lg md:text-xl font-black text-slate-800">Printable 3-Line Handwriting Worksheets</h3>
              <p class="text-xs font-bold text-slate-400">Encouraging offline penmanship and fine motor skills.</p>
            </div>
          </div>

          <div class="bg-indigo-50/60 border border-indigo-200 rounded-2xl p-5 text-xs text-indigo-950 font-semibold space-y-2 leading-relaxed">
            <p>
              NextGrade isn't just about screens! Every topic includes a printable worksheet feature formatted for standard A4 paper with traditional primary school 3-line ruling guides.
            </p>
            <p>
              To print: Go to the student topic list or quiz screen, tap <strong>"Print Worksheet"</strong>, and press <strong>Print Now</strong>. Your child can practice neat pencil writing away from screens!
            </p>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- ================= MODALS ================= -->

  <!-- 1. ATTEMPT QUESTION-BY-QUESTION BREAKDOWN MODAL (Requirement 3: See correct and incorrect answers) -->
  <div id="attempt-inspector-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-3 md:p-6 hidden">
    <div class="bg-white border-3 border-slate-200 rounded-[2.5rem] max-w-3xl w-full p-6 md:p-8 shadow-2xl relative max-h-[92vh] overflow-y-auto flex flex-col justify-between">

      <div>
        <!-- Modal Header -->
        <div class="flex items-start justify-between pb-4 border-b-2 border-slate-100 mb-6">
          <div class="flex items-center gap-3">
            <span id="inspect-avatar" class="text-4xl p-2 rounded-2xl bg-slate-50 border border-slate-200">⭐</span>
            <div>
              <div class="flex items-center gap-2">
                <h3 id="inspect-student-name" class="text-xl md:text-2xl font-black text-slate-800">Child's Quiz Attempt</h3>
                <span id="inspect-accuracy-badge" class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800">
                  100%
                </span>
              </div>
              <p id="inspect-meta-subtitle" class="text-xs font-bold text-slate-400 mt-0.5">
                Subject • Topic • Date
              </p>
            </div>
          </div>

          <button
            onclick="closeAttemptModal()"
            class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold text-lg flex items-center justify-center transition-colors">
            ✕
          </button>
        </div>

        <!-- Quick Summary HUD -->
        <div class="grid grid-cols-3 gap-3 mb-6 text-center">
          <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
            <span class="block text-[11px] font-black text-slate-400 uppercase">Score</span>
            <span id="inspect-score-text" class="text-xl font-black text-sky-600">0 / 10</span>
          </div>
          <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-200">
            <span class="block text-[11px] font-black text-emerald-700 uppercase">Correct</span>
            <span id="inspect-correct-count" class="text-xl font-black text-emerald-600">0</span>
          </div>
          <div class="p-3 bg-rose-50 rounded-2xl border border-rose-200">
            <span class="block text-[11px] font-black text-rose-700 uppercase">Incorrect (Gaps)</span>
            <span id="inspect-incorrect-count" class="text-xl font-black text-rose-600">0</span>
          </div>
        </div>

        <!-- Question-by-Question List -->
        <div class="mb-2">
          <h4 class="text-base font-black text-slate-800 flex items-center gap-2 mb-3">
            <span>🔍</span> Full Question-by-Question Review:
          </h4>
          <div id="inspector-questions-container" class="space-y-4">
            <div class="text-center py-8 text-slate-400 font-bold">Loading questions...</div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="pt-5 mt-6 border-t-2 border-slate-100 flex items-center justify-between">
        <span class="text-xs font-bold text-slate-400">Green = Correct • Red = Incorrect</span>
        <button
          onclick="closeAttemptModal()"
          class="btn-chunky btn-white text-xs py-2 px-5 rounded-xl font-black">
          Close Review
        </button>
      </div>

    </div>
  </div>

  <!-- 2. ADD / EDIT KID MODAL (Requirement 1: Kid CRUD) -->
  <div id="kid-form-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white border-3 border-slate-200 rounded-[2.5rem] max-w-lg w-full p-6 md:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">

      <div class="flex items-center justify-between pb-4 border-b-2 border-slate-100 mb-5">
        <h3 id="kid-modal-title" class="text-xl font-black text-slate-800 flex items-center gap-2">
          <span>🧒</span> <span>Add Child Profile</span>
        </h3>
        <button onclick="closeKidModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">
          ✕
        </button>
      </div>

      <div id="kid-modal-alert" class="hidden mb-4 p-3.5 rounded-xl text-xs font-bold"></div>

      <form id="kid-crud-form" onsubmit="handleSaveKid(event)" class="space-y-5">
        <input type="hidden" id="form-kid-id" value="">

        <div>
          <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Child's Name *</label>
          <input
            type="text"
            id="form-kid-name"
            required
            placeholder="e.g. Sarah Marissa"
            oninput="suggestKidUsername(this.value)"
            class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">
              Simple Username (For Login) *
            </label>
            <input
              type="text"
              id="form-kid-username"
              required
              placeholder="e.g. sarah"
              class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold font-mono text-sky-600 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            <span class="text-[10px] text-slate-400 font-bold">Simple lowercase name for child to type</span>
          </div>

          <div>
            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">
              Simple 4-Digit PIN *
            </label>
            <input
              type="text"
              id="form-kid-pin"
              required
              maxlength="6"
              placeholder="e.g. 1234"
              class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-black font-mono text-amber-600 text-center tracking-widest focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            <span class="text-[10px] text-slate-400 font-bold">Easy for child to tap on touchscreen</span>
          </div>
        </div>

        <div>
          <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Grade / Learning Level</label>
          <select
            id="form-kid-grade"
            class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            <option value="Kindergarten 3 (KG3)" selected>Kindergarten 3 (KG3) - Active Questions (Ages 5-6)</option>
            <option value="Year 6 (PSR Brunei)">Year 6 (PSR Brunei) - PSR Exam Prep 🇧🇳</option>
            <option value="Year 1">Year 1 (Standard 1) - Coming Soon</option>
            <option value="Year 2">Year 2 (Standard 2) - Coming Soon</option>
            <option value="Year 3">Year 3 (Standard 3) - Coming Soon</option>
          </select>
          <span class="text-[10px] text-sky-700 font-bold block mt-1">
            💡 Active Curriculums: Kindergarten 3 (KG3) and Year 6 (PSR Brunei). Questions are strictly segregated by grade.
          </span>
        </div>

        <div>
          <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">Account Status</label>
          <select
            id="form-kid-status"
            class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
            <option value="active" selected>Active (Child can log in & practice)</option>
            <option value="inactive">Disabled (Child account locked)</option>
          </select>
          <span class="text-[10px] text-slate-400 font-bold block mt-1">
            When disabled, your child will not be able to log in or take quizzes until re-enabled.
          </span>
        </div>

        <!-- Avatar Picker -->
        <div>
          <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Choose Avatar:</label>
          <div class="grid grid-cols-6 gap-2">
            <?php
            $avList = [
              'star_kid' => '⭐',
              'bunny' => '🐰',
              'astronaut' => '🚀',
              'dino' => '🦖',
              'kitten' => '🐱',
              'unicorn' => '🦄'
            ];
            foreach ($avList as $key => $emo):
            ?>
              <label class="cursor-pointer">
                <input type="radio" name="kid_avatar" value="<?= $key ?>" class="peer sr-only" <?= $key === 'star_kid' ? 'checked' : '' ?>>
                <div class="text-3xl p-2.5 rounded-2xl border-2 border-slate-200 bg-slate-50 peer-checked:border-sky-500 peer-checked:bg-sky-100 peer-checked:scale-110 transition-all flex items-center justify-center">
                  <?= $emo ?>
                </div>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="pt-4 border-t-2 border-slate-100 flex items-center justify-end gap-3">
          <button
            type="button"
            onclick="closeKidModal()"
            class="btn-chunky btn-white text-xs py-2.5 px-4 rounded-xl font-bold">
            Cancel
          </button>
          <button
            type="submit"
            id="btn-save-kid"
            class="btn-chunky btn-success text-xs py-2.5 px-5 rounded-xl font-black">
            Save Child Profile
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- Modal: Quick Change PIN for Child -->
  <div id="parent-pin-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white border-3 border-amber-300 rounded-[2.5rem] max-w-sm w-full p-6 text-center shadow-2xl relative">
      <div class="text-4xl mb-2">🔑</div>
      <h3 id="parent-pin-modal-title" class="text-xl font-black text-slate-800">Change Child PIN</h3>
      <p class="text-xs font-bold text-slate-400 mb-4">Set a simple 4-digit PIN for your child's login:</p>

      <div id="parent-pin-modal-alert" class="hidden mb-3 p-2.5 rounded-xl text-xs font-bold"></div>

      <input type="hidden" id="parent-pin-kid-id" value="">
      <div class="mb-4">
        <input 
          type="text" 
          id="parent-pin-input" 
          maxlength="6"
          placeholder="e.g. 1234"
          class="w-full bg-slate-50 border-3 border-amber-200 rounded-2xl py-3 text-center text-2xl font-black font-mono text-amber-600 tracking-widest focus:outline-none focus:border-amber-400 focus:bg-white transition-all"
        />
        <div class="flex items-center justify-center gap-1.5 mt-2">
          <span class="text-[10px] text-slate-400 font-bold">Presets:</span>
          <button type="button" onclick="document.getElementById('parent-pin-input').value='1234'" class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 cursor-pointer">1234</button>
          <button type="button" onclick="document.getElementById('parent-pin-input').value='0000'" class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 cursor-pointer">0000</button>
          <button type="button" onclick="document.getElementById('parent-pin-input').value=String(Math.floor(1000 + Math.random() * 9000))" class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800 hover:bg-amber-200 cursor-pointer">🎲 Random</button>
        </div>
      </div>

      <div class="flex items-center justify-center gap-2">
        <button 
          type="button" 
          onclick="closeParentPinModal()" 
          class="btn-chunky btn-white text-xs py-2.5 px-4 rounded-xl font-bold cursor-pointer"
        >
          Cancel
        </button>
        <button 
          type="button" 
          id="btn-save-parent-pin"
          onclick="submitParentChangePin()" 
          class="btn-chunky btn-primary text-xs py-2.5 px-5 rounded-xl font-black cursor-pointer"
        >
          Update PIN
        </button>
      </div>
    </div>
  </div>

  <script>
    let activeKidFilter = '';
    let cachedKids = [];
    const avatarMap = {
      'star_kid': '⭐',
      'bunny': '🐰',
      'astronaut': '🚀',
      'dino': '🦖',
      'kitten': '🐱',
      'unicorn': '🦄'
    };

    function isKG3(grade) {
      if (!grade) return false;
      const g = String(grade).toLowerCase();
      return g.includes('kg3') || g.includes('kindergarten 3') || g.includes('kindergarden 3');
    }

    function isYear6(grade) {
      if (!grade) return false;
      const g = String(grade).toLowerCase();
      return g.includes('year 6') || g.includes('psr') || g.includes('tahun 6');
    }

    function switchTab(tab) {
      SoundEffects.playPop();
      const tabs = {
        insights: {
          content: document.getElementById('tab-content-insights'),
          btn: document.getElementById('tab-btn-insights')
        },
        kids: {
          content: document.getElementById('tab-content-kids'),
          btn: document.getElementById('tab-btn-kids')
        },
        profile: {
          content: document.getElementById('tab-content-profile'),
          btn: document.getElementById('tab-btn-profile')
        },
        guide: {
          content: document.getElementById('tab-content-guide'),
          btn: document.getElementById('tab-btn-guide')
        }
      };

      const activeBtnClass = 'px-5 py-2.5 rounded-2xl font-black text-sm flex items-center gap-2 bg-sky-500 text-white shadow-md transition-all cursor-pointer shrink-0';
      const inactiveBtnClass = 'px-5 py-2.5 rounded-2xl font-black text-sm flex items-center gap-2 bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer shrink-0';

      Object.keys(tabs).forEach(k => {
        const item = tabs[k];
        if (!item.content || !item.btn) return;
        if (k === tab) {
          item.content.classList.remove('hidden');
          item.btn.className = activeBtnClass;
        } else {
          item.content.classList.add('hidden');
          item.btn.className = inactiveBtnClass;
        }
      });

      if (tab === 'insights') {
        loadAttemptsData(activeKidFilter);
      } else if (tab === 'kids') {
        loadKidsProfiles();
      } else if (tab === 'profile') {
        loadParentProfile();
      } else if (tab === 'guide') {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      }
    }

    // Parent Profile & Credentials Handlers (Requirement 1)
    async function loadParentProfile() {
      try {
        const resp = await fetch('api/parent_profile.php');
        const data = await resp.json();
        if (data.success && data.parent) {
          const p = data.parent;
          document.getElementById('profile-name').value = p.full_name || '';
          document.getElementById('profile-username').value = p.username || '';
          document.getElementById('profile-email').value = p.email || '';
          document.getElementById('profile-phone').value = p.phone || '';

          document.getElementById('profile-card-name').textContent = p.full_name || '';
          document.getElementById('profile-card-user').textContent = `@${p.username || ''}`;
          document.getElementById('profile-card-code').textContent = p.parent_code || '';
          document.getElementById('profile-card-email').textContent = p.email || '';
          document.getElementById('profile-card-phone').textContent = p.phone || 'Not provided';
          document.getElementById('header-parent-name').textContent = p.full_name || '';
        }
      } catch (err) {
        console.error(err);
      }
    }

    async function handleSaveParentProfile(e) {
      e.preventDefault();
      SoundEffects.playPop();

      const alertBox = document.getElementById('profile-status-alert');
      const btnSave = document.getElementById('btn-save-profile');
      alertBox.className = 'hidden';

      const fullName = document.getElementById('profile-name').value.trim();
      const username = document.getElementById('profile-username').value.trim();
      const email = document.getElementById('profile-email').value.trim();
      const phone = document.getElementById('profile-phone').value.trim();
      const currentPassword = document.getElementById('profile-cur-pass').value;
      const newPassword = document.getElementById('profile-new-pass').value;
      const confirmPassword = document.getElementById('profile-conf-pass').value;

      if (!fullName || !username || !email) {
        showProfileAlert('Please fill in all required fields (Full Name, Username, Email).', 'error');
        return;
      }

      if (newPassword) {
        if (!currentPassword) {
          showProfileAlert('Please enter your current password to set a new password.', 'error');
          return;
        }
        if (newPassword.length < 6) {
          showProfileAlert('New password must be at least 6 characters long.', 'error');
          return;
        }
        if (newPassword !== confirmPassword) {
          showProfileAlert('New password and confirmation do not match.', 'error');
          return;
        }
      }

      btnSave.disabled = true;
      btnSave.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Saving...</span>';

      try {
        const resp = await fetch('api/parent_profile.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            full_name: fullName,
            username: username,
            email: email,
            phone: phone,
            current_password: currentPassword,
            new_password: newPassword,
            confirm_password: confirmPassword
          })
        });

        const data = await resp.json();

        if (data.success) {
          showProfileAlert(data.message || 'Profile updated successfully!', 'success');
          document.getElementById('profile-card-name').textContent = fullName;
          document.getElementById('profile-card-user').textContent = `@${username}`;
          document.getElementById('profile-card-email').textContent = email;
          document.getElementById('profile-card-phone').textContent = phone || 'Not provided';
          document.getElementById('header-parent-name').textContent = fullName;
          document.getElementById('profile-cur-pass').value = '';
          document.getElementById('profile-new-pass').value = '';
          document.getElementById('profile-conf-pass').value = '';
        } else {
          showProfileAlert(data.error || 'Failed to update profile.', 'error');
        }
      } catch (err) {
        console.error(err);
        showProfileAlert('Network error occurred. Please try again.', 'error');
      } finally {
        btnSave.disabled = false;
        btnSave.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> <span>Save Profile Changes</span>';
      }
    }

    function showProfileAlert(msg, type) {
      const alertBox = document.getElementById('profile-status-alert');
      alertBox.classList.remove('hidden');
      if (type === 'success') {
        alertBox.className = 'p-4 rounded-2xl text-xs font-bold border bg-emerald-50 border-emerald-300 text-emerald-800';
        alertBox.innerHTML = `<i class="fa-solid fa-circle-check mr-2"></i> ${msg}`;
      } else {
        alertBox.className = 'p-4 rounded-2xl text-xs font-bold border bg-rose-50 border-rose-300 text-rose-800';
        alertBox.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-2"></i> ${msg}`;
      }
      alertBox.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });
    }

    function copyParentCode() {
      SoundEffects.playPop();
      const code = document.getElementById('profile-card-code').textContent.trim();
      if (navigator.clipboard) {
        navigator.clipboard.writeText(code).then(() => {
          const btn = document.getElementById('btn-copy-code');
          btn.innerHTML = '<i class="fa-solid fa-check text-emerald-600"></i> Copied!';
          setTimeout(() => {
            btn.innerHTML = '<i class="fa-regular fa-copy"></i> Copy';
          }, 2000);
        });
      }
    }

    // 1. Load Attempts & GAP Data
    async function loadAttemptsData(kidId = '') {
      try {
        let url = 'api/parent_attempts.php';
        if (kidId) {
          url += `?student_id=${encodeURIComponent(kidId)}`;
        }

        const resp = await fetch(url);
        const data = await resp.json();

        if (!data.success) return;

        // Populate filter dropdown
        const select = document.getElementById('filter-kid-select');
        const curVal = kidId || select.value;
        select.innerHTML = '<option value="">All My Kids</option>' +
          (data.kids_list || []).map(k => `
          <option value="${k.id}" ${k.id == curVal ? 'selected' : ''}>
            ${avatarMap[k.avatar] || '⭐'} ${k.name}
          </option>
        `).join('');

        document.getElementById('tab-kids-badge').textContent = (data.kids_list || []).length;

        // Summary Stats
        const sum = data.summary;
        document.getElementById('stat-total-sessions').textContent = sum.total_sessions;
        document.getElementById('stat-overall-accuracy').textContent = `${sum.overall_accuracy}%`;
        document.getElementById('stat-total-questions').textContent = sum.total_questions;
        document.getElementById('stat-total-time').textContent = sum.formatted_time;

        // Render GAP Analysis Widget
        const gapContainer = document.getElementById('gap-analysis-container');
        const gaps = data.gap_analysis || [];
        document.getElementById('gaps-count-badge').textContent = `${gaps.length} Gaps Found`;

        if (gaps.length > 0) {
          gapContainer.innerHTML = gaps.map(g => `
          <div class="bg-white border-2 border-amber-200 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                  <span class="text-2xl">${g.subject_icon}</span>
                  <div>
                    <h4 class="font-black text-slate-800 text-sm md:text-base">${g.topic_name}</h4>
                    <span class="text-[11px] font-bold text-amber-700 block">${g.subject_name}</span>
                  </div>
                </div>
                <span class="bg-rose-100 text-rose-800 font-black text-xs px-2.5 py-1 rounded-xl">
                  ${g.avg_score}% Accuracy
                </span>
              </div>
              <p class="text-xs text-slate-600 font-semibold bg-amber-50/70 p-3 rounded-xl border border-amber-100 mb-3 leading-relaxed">
                💡 <strong class="text-amber-900">Parent Guidance:</strong> ${g.guidance_tip}
              </p>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs font-bold text-slate-500">
              <span>${g.mistakes_count} mistake(s) logged</span>
              <a 
                href="${g.revision_url}" 
                target="_blank"
                class="btn-chunky btn-amber text-xs py-1.5 px-3 rounded-xl flex items-center gap-1 font-bold"
              >
                <span>5-Min Revision</span> ➔
              </a>
            </div>
          </div>
        `).join('');
        } else {
          gapContainer.innerHTML = `
          <div class="col-span-full bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-6 text-center">
            <span class="text-4xl block mb-2">🎉</span>
            <h4 class="font-black text-emerald-900 text-base">Fantastic! No Critical Learning Gaps Detected</h4>
            <p class="text-xs font-bold text-emerald-700 mt-1">All attempted topics currently maintain a high mastery score above 70%.</p>
          </div>
        `;
        }

        // Subject Progress Breakdown
        const subContainer = document.getElementById('subject-breakdown-list');
        subContainer.innerHTML = (data.subject_stats || []).map(s => `
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <div class="flex items-center gap-2">
              <span class="text-xl">${s.icon}</span>
              <span class="font-black text-slate-800 text-sm">${s.name}</span>
            </div>
            <div class="text-xs font-black text-slate-500">
              <span class="text-sky-600 font-extrabold">${s.accuracy}%</span> 
              (${s.correct_answers} / ${s.questions_attempted} Qs)
            </div>
          </div>
          <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden border border-slate-200">
            <div class="h-full rounded-full transition-all duration-500" style="width: ${s.accuracy}%; background-color: ${s.color};"></div>
          </div>
        </div>
      `).join('');

        // Strengths List
        const strContainer = document.getElementById('strengths-list');
        const strengths = data.insights?.strengths || [];
        if (strengths.length > 0) {
          strContainer.innerHTML = strengths.map(st => `
          <div class="p-2.5 bg-emerald-50/80 border border-emerald-200 rounded-xl flex items-center justify-between text-xs">
            <span class="font-black text-emerald-900">${st.topic_name}</span>
            <span class="bg-emerald-600 text-white font-black px-2 py-0.5 rounded-lg text-[10px]">
              ${Math.round(st.avg_score)}%
            </span>
          </div>
        `).join('');
        } else {
          strContainer.innerHTML = '<p class="text-xs font-bold text-slate-400 py-3 text-center">Complete more quizzes to identify top strengths.</p>';
        }

        // Recent Sessions Table
        const tbBody = document.getElementById('sessions-table-body');
        const sessions = data.recent_sessions || [];

        if (sessions.length > 0) {
          tbBody.innerHTML = sessions.map(sess => {
            const mins = Math.floor(sess.time_spent_seconds / 60);
            const secs = sess.time_spent_seconds % 60;
            const timeStr = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            const isHigh = sess.percentage >= 70;

            return `
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="py-3 px-3 text-xs text-slate-500 font-mono">${sess.completed_at ? sess.completed_at.substring(0, 16) : '-'}</td>
              <td class="py-3 px-3">
                <div class="flex items-center gap-1.5">
                  <span>${avatarMap[sess.student_avatar] || '⭐'}</span>
                  <span class="font-black text-slate-800">${sess.student_name}</span>
                </div>
              </td>
              <td class="py-3 px-3 text-xs">
                <div class="text-slate-800 font-bold">${sess.subject_icon} ${sess.subject_name}</div>
                <div class="text-slate-400">${sess.topic_name}</div>
              </td>
              <td class="py-3 px-3 text-center font-black text-sky-600 font-mono">${sess.score} / ${sess.total_questions}</td>
              <td class="py-3 px-3 text-center">
                <span class="px-2 py-0.5 rounded-full text-xs font-black ${isHigh ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}">
                  ${sess.percentage}%
                </span>
              </td>
              <td class="py-3 px-3 text-center font-mono text-xs text-slate-400">${timeStr}</td>
              <td class="py-3 px-3 text-right">
                <button 
                  onclick="inspectAttemptDetails(${sess.id})"
                  class="btn-chunky btn-white text-xs py-1.5 px-3 rounded-xl border border-sky-300 text-sky-700 hover:bg-sky-50 flex items-center gap-1 ml-auto font-black"
                >
                  <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                  <span>Inspect Answers</span>
                </button>
              </td>
            </tr>
          `;
          }).join('');
        } else {
          tbBody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-slate-400 font-bold">No quiz sessions recorded for this selection yet.</td></tr>';
        }

      } catch (err) {
        console.error(err);
      }
    }

    function onKidFilterChange(val) {
      SoundEffects.playPop();
      activeKidFilter = val;
      loadAttemptsData(val);
    }

    // 2. Load Kids Profiles (CRUD Tab)
    async function loadKidsProfiles() {
      const grid = document.getElementById('kids-cards-grid');
      grid.innerHTML = '<div class="col-span-full text-center py-8 text-slate-400 font-bold"><i class="fa-solid fa-circle-notch fa-spin text-xl mb-2 block text-sky-500"></i>Loading kids...</div>';

      try {
        const resp = await fetch('api/parent_kids.php');
        const data = await resp.json();

        if (!data.success || !data.kids) {
          grid.innerHTML = '<p class="text-rose-500 font-bold text-center col-span-full">Failed to load kids.</p>';
          return;
        }

        cachedKids = data.kids;
        document.getElementById('tab-kids-badge').textContent = cachedKids.length;

        if (cachedKids.length === 0) {
          grid.innerHTML = `
          <div class="col-span-full bg-white rounded-3xl p-10 border-2 border-slate-200 text-center">
            <span class="text-5xl block mb-3">🧒</span>
            <h3 class="text-xl font-black text-slate-700">No Child Profiles Created Yet</h3>
            <p class="text-xs font-bold text-slate-400 max-w-md mx-auto mt-1 mb-5">
              Add your children's profiles so they can start practicing interactive quizzes, listening to questions, and mastering their learning.
            </p>
            <button onclick="openAddKidModal()" class="btn-chunky btn-primary text-sm py-2.5 px-5 rounded-2xl">
              + Add First Child Profile
            </button>
          </div>
        `;
          return;
        }

        grid.innerHTML = cachedKids.map(k => {
          const isInactive = k.status === 'inactive';
          return `
        <div class="bg-white rounded-[2rem] p-6 border-3 ${isInactive ? 'border-rose-200 bg-rose-50/20' : 'border-slate-200'} shadow-md hover:border-sky-300 transition-all flex flex-col justify-between relative overflow-hidden">
          
          <div>
            <!-- Kid Header -->
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <span class="text-4xl p-2.5 rounded-2xl bg-slate-50 border border-slate-100 shadow-inner">
                  ${avatarMap[k.avatar] || '⭐'}
                </span>
                <div>
                  <h3 class="text-xl font-black text-slate-800">${escapeHtml(k.name)}</h3>
                  <div class="flex flex-wrap items-center gap-1.5 mt-0.5">
                    <span class="text-xs font-black ${isYear6(k.grade_level) ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : (isKG3(k.grade_level) ? 'text-sky-700 bg-sky-50 border border-sky-200' : 'text-amber-800 bg-amber-50 border border-amber-200')} px-2 py-0.5 rounded-md inline-block">
                      ${k.grade_level || 'Kindergarten 3 (KG3)'}
                    </span>
                    ${isYear6(k.grade_level) 
                      ? '<span class="text-[10px] text-emerald-700 font-extrabold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">🇧🇳 630 PSR Questions</span>' 
                      : (isKG3(k.grade_level) 
                          ? '<span class="text-[10px] text-sky-700 font-extrabold bg-sky-50 px-1.5 py-0.5 rounded border border-sky-200">⭐ ~900 KG3 Questions</span>' 
                          : '<span class="text-[10px] text-amber-700 font-extrabold bg-amber-100 px-1.5 py-0.5 rounded" title="Content for this grade coming soon">🔒 Coming Soon</span>'
                        )}
                  </div>
                </div>
              </div>
              <div>
                ${isInactive 
                  ? '<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 inline-flex items-center gap-1 shadow-xs"><i class="fa-solid fa-ban text-[9px]"></i> Disabled</span>'
                  : '<span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1 shadow-xs"><i class="fa-solid fa-check text-[9px]"></i> Active</span>'
                }
              </div>
            </div>

            <!-- Credentials Box (Simple Kid Access) -->
            <div class="bg-slate-50 border-2 border-slate-200 rounded-2xl p-3.5 space-y-2 mb-4">
              <div class="flex items-center justify-between">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">
                  Kid Login Access:
                </span>
                <span class="text-[10px] font-bold ${isInactive ? 'text-rose-500' : 'text-emerald-600'}">
                  ${isInactive ? 'Access Blocked' : 'Login Permitted'}
                </span>
              </div>
              <div class="flex items-center justify-between text-xs font-mono">
                <span class="text-slate-500 font-bold">Username:</span>
                <span class="font-black text-sky-700 bg-white px-2 py-0.5 rounded-lg border border-slate-200">
                  ${k.username || 'Not set'}
                </span>
              </div>
              <div class="flex items-center justify-between text-xs font-mono">
                <span class="text-slate-500 font-bold">4-Digit PIN:</span>
                <div class="flex items-center gap-1.5">
                  <span class="font-black text-amber-700 bg-white px-2 py-0.5 rounded-lg border border-slate-200">
                    ${k.pin_code || '1234'}
                  </span>
                  <button 
                    type="button" 
                    onclick="openParentChangePin(${k.id}, '${escapeHtml(k.name)}', '${escapeHtml(k.pin_code || '1234')}')"
                    class="text-[10px] font-sans font-bold px-2 py-0.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 cursor-pointer flex items-center gap-1 transition-all"
                    title="Change 4-digit PIN"
                  >
                    <i class="fa-solid fa-key text-[9px]"></i> Change
                  </button>
                </div>
              </div>
            </div>

            <!-- Learning Stats -->
            <div class="grid grid-cols-2 gap-2 text-center mb-4">
              <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="block text-[10px] font-black uppercase text-slate-400">Quizzes</span>
                <span class="text-lg font-black text-slate-700">${k.sessions_count}</span>
              </div>
              <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                <span class="block text-[10px] font-black uppercase text-slate-400">Avg Accuracy</span>
                <span class="text-lg font-black text-emerald-600">${Math.round(k.avg_score)}%</span>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="space-y-2 pt-3 border-t border-slate-100">
            <!-- Start Child Session (if active) -->
            ${isInactive 
              ? `<button disabled class="w-full text-xs py-2.5 rounded-xl font-bold flex items-center justify-center gap-1.5 bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed">
                  <i class="fa-solid fa-lock text-xs"></i>
                  <span>Disabled - Child cannot login</span>
                </button>`
              : `<button 
                  onclick="launchKidSession(${k.id}, '${escapeHtml(k.name)}')"
                  class="btn-chunky btn-success w-full text-xs py-2.5 rounded-xl font-black flex items-center justify-center gap-1.5 shadow-sm cursor-pointer"
                  title="Launch tablet learning session for this child"
                >
                  <span>🚀</span>
                  <span>Start Learning as ${escapeHtml(k.name)}</span>
                </button>`
            }

            <!-- Status Toggle, Edit & Delete Row -->
            <div class="flex items-center gap-2">
              <button 
                type="button"
                onclick="toggleKidStatus(${k.id})"
                class="flex-1 btn-chunky ${isInactive ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-300' : 'bg-slate-50 text-slate-600 hover:bg-rose-50 hover:text-rose-600 border border-slate-200'} text-xs py-2 px-2.5 rounded-xl font-bold flex items-center justify-center gap-1 cursor-pointer transition-colors"
                title="${isInactive ? 'Click to enable child account' : 'Click to disable child account'}"
              >
                <i class="fa-solid ${isInactive ? 'fa-toggle-off text-rose-500' : 'fa-toggle-on text-emerald-500'} text-sm"></i>
                <span>${isInactive ? 'Enable' : 'Disable'}</span>
              </button>
              <button 
                type="button"
                onclick="openEditKidModal(${k.id})"
                class="flex-1 btn-chunky btn-white text-xs py-2 px-2.5 rounded-xl font-bold flex items-center justify-center gap-1 cursor-pointer"
              >
                <span>✏️</span> <span>Edit</span>
              </button>
              <button 
                type="button"
                onclick="confirmDeleteKid(${k.id}, '${escapeHtml(k.name)}')"
                class="btn-chunky btn-white text-xs py-2 px-2.5 rounded-xl font-bold text-rose-600 hover:bg-rose-50 border-rose-200 flex items-center justify-center cursor-pointer"
                title="Remove child"
              >
                <i class="fa-solid fa-trash"></i>
              </button>
            </div>
          </div>

        </div>
      `;
        }).join('');

      } catch (err) {
        console.error(err);
        grid.innerHTML = '<p class="text-rose-500 font-bold text-center col-span-full">Network error loading kids.</p>';
      }
    }

    // Toggle Kid Active/Inactive Status
    async function toggleKidStatus(id) {
      SoundEffects.playPop();
      try {
        const resp = await fetch('api/parent_kids.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'toggle_status', id })
        });
        const data = await resp.json();
        if (data.success) {
          SoundEffects.playChime();
          loadKidsProfiles();
        } else {
          SoundEffects.playBoop();
          alert(data.error || 'Could not change child account status.');
        }
      } catch (err) {
        console.error(err);
        alert('Network error updating child status.');
      }
    }

    // Kid PIN Modal Handlers
    function openParentChangePin(id, name, currentPin) {
      SoundEffects.playPop();
      document.getElementById('parent-pin-kid-id').value = id;
      document.getElementById('parent-pin-modal-title').textContent = `Change PIN for ${name}`;
      document.getElementById('parent-pin-input').value = currentPin || '1234';
      const alertBox = document.getElementById('parent-pin-modal-alert');
      alertBox.className = 'hidden';
      document.getElementById('parent-pin-modal').classList.remove('hidden');
      document.getElementById('parent-pin-input').focus();
      document.getElementById('parent-pin-input').select();
    }

    function closeParentPinModal() {
      document.getElementById('parent-pin-modal').classList.add('hidden');
    }

    async function submitParentChangePin() {
      SoundEffects.playPop();
      const id = document.getElementById('parent-pin-kid-id').value;
      const pin = document.getElementById('parent-pin-input').value.trim();
      const alertBox = document.getElementById('parent-pin-modal-alert');
      const btn = document.getElementById('btn-save-parent-pin');

      if (!pin || pin.length < 4) {
        alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-50 border border-rose-200 text-rose-800 block';
        alertBox.textContent = 'Please enter a PIN of 4 to 6 characters/digits.';
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Saving...';
      try {
        const resp = await fetch('api/parent_kids.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'update_pin', id: parseInt(id), pin_code: pin })
        });
        const data = await resp.json();
        if (data.success) {
          SoundEffects.playChime();
          alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-800 block';
          alertBox.textContent = data.message;
          setTimeout(() => {
            closeParentPinModal();
            loadKidsProfiles();
          }, 500);
        } else {
          SoundEffects.playBoop();
          alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-50 border border-rose-200 text-rose-800 block';
          alertBox.textContent = data.error || 'Failed to update PIN.';
        }
      } catch (err) {
        console.error(err);
        alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-50 border border-rose-200 text-rose-800 block';
        alertBox.textContent = 'Network error.';
      } finally {
        btn.disabled = false;
        btn.textContent = 'Update PIN';
      }
    }

    // Kid Add/Edit Modal Handlers
    function openAddKidModal() {
      SoundEffects.playPop();
      document.getElementById('kid-modal-title').innerHTML = '<span>🧒</span> <span>Add Child Profile</span>';
      document.getElementById('form-kid-id').value = '';
      document.getElementById('form-kid-name').value = '';
      document.getElementById('form-kid-username').value = '';
      document.getElementById('form-kid-pin').value = '1234';
      document.getElementById('form-kid-grade').value = 'Kindergarten 3 (KG3)';
      document.getElementById('form-kid-status').value = 'active';
      document.querySelector('input[name="kid_avatar"][value="star_kid"]').checked = true;

      document.getElementById('kid-modal-alert').className = 'hidden';
      document.getElementById('kid-form-modal').classList.remove('hidden');
    }

    function openEditKidModal(id) {
      SoundEffects.playPop();
      const k = cachedKids.find(x => x.id == id);
      if (!k) return;

      document.getElementById('kid-modal-title').innerHTML = `<span>✏️</span> <span>Edit: ${k.name}</span>`;
      document.getElementById('form-kid-id').value = k.id;
      document.getElementById('form-kid-name').value = k.name;
      document.getElementById('form-kid-username').value = k.username || '';
      document.getElementById('form-kid-pin').value = k.pin_code || '1234';
      document.getElementById('form-kid-grade').value = k.grade_level || 'Kindergarten 3 (KG3)';
      document.getElementById('form-kid-status').value = k.status || 'active';

      const avRadio = document.querySelector(`input[name="kid_avatar"][value="${k.avatar}"]`);
      if (avRadio) avRadio.checked = true;

      document.getElementById('kid-modal-alert').className = 'hidden';
      document.getElementById('kid-form-modal').classList.remove('hidden');
    }

    function closeKidModal() {
      document.getElementById('kid-form-modal').classList.add('hidden');
    }

    function suggestKidUsername(name) {
      if (!document.getElementById('form-kid-id').value) {
        const clean = name.toLowerCase().replace(/[^a-z0-9]/g, '');
        if (clean) {
          document.getElementById('form-kid-username').value = clean;
        }
      }
    }

    async function handleSaveKid(e) {
      e.preventDefault();
      SoundEffects.playPop();
      const id = document.getElementById('form-kid-id').value;
      const isEdit = !!id;

      const payload = {
        action: isEdit ? 'update' : 'create',
        id: id ? parseInt(id) : undefined,
        name: document.getElementById('form-kid-name').value.trim(),
        username: document.getElementById('form-kid-username').value.trim(),
        pin_code: document.getElementById('form-kid-pin').value.trim(),
        grade_level: document.getElementById('form-kid-grade').value,
        status: document.getElementById('form-kid-status').value || 'active',
        avatar: document.querySelector('input[name="kid_avatar"]:checked')?.value || 'star_kid'
      };

      const alertBox = document.getElementById('kid-modal-alert');
      const submitBtn = document.getElementById('btn-save-kid');

      alertBox.className = 'hidden';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Saving...';

      try {
        const resp = await fetch('api/parent_kids.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        });
        const data = await resp.json();

        if (data.success) {
          SoundEffects.playChime();
          alertBox.className = 'mb-4 p-3.5 rounded-xl text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-800 block';
          alertBox.textContent = data.message;
          setTimeout(() => {
            closeKidModal();
            loadKidsProfiles();
          }, 500);
        } else {
          SoundEffects.playBoop();
          alertBox.className = 'mb-4 p-3.5 rounded-xl text-xs font-bold bg-rose-50 border border-rose-200 text-rose-800 block';
          alertBox.textContent = data.error || 'Failed to save child.';
        }
      } catch (err) {
        console.error(err);
        alertBox.className = 'mb-4 p-3.5 rounded-xl text-xs font-bold bg-rose-50 border border-rose-200 text-rose-800 block';
        alertBox.textContent = 'Connection error.';
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save Child Profile';
      }
    }

    async function confirmDeleteKid(id, name) {
      if (!confirm(`Are you sure you want to remove profile for ${name}?`)) return;
      SoundEffects.playPop();

      try {
        const resp = await fetch('api/parent_kids.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'delete',
            id
          })
        });
        const data = await resp.json();
        if (data.success) {
          SoundEffects.playChime();
          loadKidsProfiles();
        } else {
          alert(data.error || 'Could not delete.');
        }
      } catch (err) {
        console.error(err);
      }
    }

    async function launchKidSession(id, name) {
      SoundEffects.playPop();
      try {
        const resp = await fetch('api/parent_kids.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            action: 'launch_kid_session',
            id
          })
        });
        const data = await resp.json();
        if (data.success && data.redirect) {
          window.location.href = data.redirect;
        } else {
          alert(data.error || 'Could not launch child session.');
        }
      } catch (err) {
        console.error(err);
      }
    }

    // 3. ATTEMPT QUESTION-BY-QUESTION INSPECTOR (Requirement 3)
    async function inspectAttemptDetails(sessionId) {
      SoundEffects.playPop();
      const container = document.getElementById('inspector-questions-container');
      container.innerHTML = '<div class="text-center py-10 text-slate-400 font-bold"><i class="fa-solid fa-circle-notch fa-spin text-2xl mb-2 block text-sky-500"></i>Retrieving question breakdown & answers...</div>';
      document.getElementById('attempt-inspector-modal').classList.remove('hidden');

      try {
        const resp = await fetch(`api/parent_attempts.php?action=attempt_details&session_id=${sessionId}`);
        const data = await resp.json();

        if (!data.success || !data.session) {
          container.innerHTML = '<p class="text-rose-500 font-bold text-center py-6">Could not load attempt details.</p>';
          return;
        }

        const sess = data.session;
        const bd = data.breakdown;

        document.getElementById('inspect-avatar').textContent = avatarMap[sess.student_avatar] || '⭐';
        document.getElementById('inspect-student-name').textContent = `${sess.student_name}'s Quiz Review`;
        document.getElementById('inspect-accuracy-badge').textContent = `${sess.percentage}% Accuracy`;
        document.getElementById('inspect-accuracy-badge').className = `px-2.5 py-0.5 rounded-full text-xs font-black ${sess.percentage >= 70 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}`;

        document.getElementById('inspect-meta-subtitle').textContent = `${sess.subject_name || 'Mixed'} • ${sess.topic_name || 'All Topics'} • Attempted: ${sess.completed_at}`;
        document.getElementById('inspect-score-text').textContent = `${sess.score} / ${sess.total_questions}`;
        document.getElementById('inspect-correct-count').textContent = bd.correct_count;
        document.getElementById('inspect-incorrect-count').textContent = bd.incorrect_count;

        // Render each question
        container.innerHTML = data.answers.map(ans => {
          const isCorrect = ans.is_correct;
          const borderClass = isCorrect ? 'border-emerald-300 bg-emerald-50/20' : 'border-rose-300 bg-rose-50/30';
          const badgeClass = isCorrect ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white';
          const badgeIcon = isCorrect ? '✓ Correct' : '✗ Incorrect';

          return `
          <div class="border-2 ${borderClass} rounded-2xl p-4 md:p-5 transition-all">
            
            <!-- Question Header -->
            <div class="flex items-start justify-between gap-3 mb-2.5">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-xl bg-slate-800 text-white text-xs font-black flex items-center justify-center shrink-0">
                  ${ans.question_number}
                </span>
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400">
                  Question ${ans.question_number} of ${data.answers.length}
                </span>
              </div>

              <span class="px-2.5 py-1 rounded-xl text-xs font-black ${badgeClass} shadow-sm flex items-center gap-1">
                ${badgeIcon}
              </span>
            </div>

            <!-- Question Prompt Text -->
            <h4 class="text-base font-black text-slate-800 mb-2 leading-snug">
              ${ans.question_text}
            </h4>

            ${ans.image_url ? `
              <div class="my-2.5 p-2 bg-white rounded-xl border border-slate-200 inline-block">
                <img src="${ans.image_url}" alt="Illustration" class="max-h-36 w-auto object-contain rounded-lg">
              </div>
            ` : ''}

            <!-- Answer Comparison Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3 pt-3 border-t border-slate-200/60 text-xs">
              
              <!-- Child's Given Answer -->
              <div class="p-3 rounded-xl ${isCorrect ? 'bg-emerald-100/60 border border-emerald-200 text-emerald-900' : 'bg-rose-100/70 border border-rose-200 text-rose-900'}">
                <span class="block text-[10px] font-black uppercase tracking-wider ${isCorrect ? 'text-emerald-700' : 'text-rose-700'} mb-1">
                  Child's Given Answer:
                </span>
                <span class="text-sm font-black">${ans.student_answer || '<span class="italic text-slate-400">No answer given</span>'}</span>
              </div>

              <!-- Correct Answer -->
              <div class="p-3 rounded-xl bg-sky-50 border border-sky-200 text-sky-900">
                <span class="block text-[10px] font-black uppercase tracking-wider text-sky-700 mb-1">
                  Correct Answer:
                </span>
                <span class="text-sm font-black text-sky-950">${ans.correct_answer}</span>
              </div>

            </div>

            ${ans.hint_text ? `
              <div class="mt-2 text-[11px] text-slate-500 font-bold flex items-center gap-1.5">
                <span>💡 Hint provided:</span>
                <span class="italic text-slate-600">"${ans.hint_text}"</span>
                ${ans.used_hint ? '<span class="bg-amber-100 text-amber-800 text-[10px] px-1.5 py-0.5 rounded font-black ml-1">Hint was used</span>' : ''}
              </div>
            ` : ''}

          </div>
        `;
        }).join('');

      } catch (err) {
        console.error(err);
        container.innerHTML = '<p class="text-rose-500 font-bold text-center py-6">Network error retrieving attempt review.</p>';
      }
    }

    function closeAttemptModal() {
      document.getElementById('attempt-inspector-modal').classList.add('hidden');
    }

    function escapeHtml(str) {
      if (!str) return '';
      return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadAttemptsData();
    });
  </script>

</body>

</html>