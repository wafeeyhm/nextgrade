<?php
// NextGrade - System Admin Dashboard Portal
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();

// Fetch summary metrics
$totalParents = (int)$pdo->query("SELECT COUNT(*) FROM parents")->fetchColumn();
$activeParents = (int)$pdo->query("SELECT COUNT(*) FROM parents WHERE status = 'active'")->fetchColumn();
$totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totalSessions = (int)$pdo->query("SELECT COUNT(*) FROM quiz_sessions")->fetchColumn();
$avgAccuracy = (float)$pdo->query("SELECT COALESCE(AVG(percentage), 0) FROM quiz_sessions")->fetchColumn();
$totalQuestionsAns = (int)$pdo->query("SELECT COALESCE(SUM(total_questions), 0) FROM quiz_sessions")->fetchColumn();

// Recent 10 quiz sessions across the platform
$recentSessions = $pdo->query("
    SELECT qs.*, s.name as student_full_name, p.full_name as parent_full_name, sub.name as subject_name
    FROM quiz_sessions qs
    LEFT JOIN students s ON s.id = qs.student_id
    LEFT JOIN parents p ON p.id = s.parent_id
    LEFT JOIN subjects sub ON sub.id = qs.subject_id
    ORDER BY qs.completed_at DESC
    LIMIT 10
")->fetchAll();

// Recent Parents registered
$recentParents = $pdo->query("
    SELECT p.*, COUNT(s.id) as kids_count
    FROM parents p
    LEFT JOIN students s ON s.parent_id = p.id
    GROUP BY p.id
    ORDER BY p.id DESC
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Admin Portal - NextGrade</title>
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
<body class="admin-dark min-h-screen bg-slate-900 text-slate-100 flex flex-col">

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
          <a href="index.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-chart-pie mr-1.5 text-indigo-400"></i> Dashboard
          </a>
          <a href="parents.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Parents Accounts (CRUD)
          </a>
          <a href="kids.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-graduation-cap mr-1.5 text-slate-400"></i> Students & Kids
          </a>
          <a href="guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-book-open mr-1.5 text-slate-400"></i> System Guide
          </a>
          <a href="profile.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-user-gear mr-1.5 text-slate-400"></i> My Profile
          </a>
        </div>
      </div>

      <!-- Admin Status & Actions -->
      <div class="flex items-center gap-3">
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
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8 space-y-8">

    <!-- Welcome Hero Banner -->
    <div class="bg-gradient-to-r from-indigo-900/60 via-slate-800 to-slate-800 border border-indigo-500/30 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-xl relative overflow-hidden">
      <div class="relative z-10">
        <div class="inline-flex items-center gap-2 bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-black px-3.5 py-1 rounded-full uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
          System Online • Database Connected
        </div>
        <h1 class="text-2xl md:text-4xl font-black text-white tracking-tight">
          Welcome back, <?= htmlspecialchars($admin['full_name']) ?>! 👋
        </h1>
        <p class="text-sm font-semibold text-slate-300 mt-1 max-w-xl">
          System Admin Overview for NextGrade early learning platform. Manage parents, monitor platform-wide student performance, and view quiz analytics.
        </p>
      </div>

      <div class="relative z-10 flex flex-wrap gap-3">
        <a 
          href="parents.php" 
          class="bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-600 hover:to-sky-600 text-white font-black text-sm py-3 px-5 rounded-2xl shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2"
        >
          <i class="fa-solid fa-users"></i>
          <span>Manage Parents Accounts</span>
        </a>
      </div>

      <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <!-- 1. Key Metrics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4">
        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Parents</span>
        <span class="text-2xl md:text-3xl font-black text-indigo-400"><?= $totalParents ?></span>
        <span class="text-[10px] text-slate-400 block mt-1"><?= $activeParents ?> active accounts</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4">
        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Kids</span>
        <span class="text-2xl md:text-3xl font-black text-amber-400"><?= $totalStudents ?></span>
        <span class="text-[10px] text-slate-400 block mt-1">Across all families</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4">
        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Quiz Sessions</span>
        <span class="text-2xl md:text-3xl font-black text-emerald-400"><?= $totalSessions ?></span>
        <span class="text-[10px] text-slate-400 block mt-1">10-question quizzes</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4">
        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Avg Accuracy</span>
        <span class="text-2xl md:text-3xl font-black text-sky-400"><?= round($avgAccuracy, 1) ?>%</span>
        <span class="text-[10px] text-slate-400 block mt-1">Platform average</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4">
        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Questions Done</span>
        <span class="text-2xl md:text-3xl font-black text-purple-400"><?= $totalQuestionsAns ?></span>
        <span class="text-[10px] text-slate-400 block mt-1">Child responses</span>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4">
        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Question Bank</span>
        <span class="text-2xl md:text-3xl font-black text-rose-400">1,350+</span>
        <span class="text-[10px] text-slate-400 block mt-1">Curated KSSR/Pre-K</span>
      </div>
    </div>

    <!-- 2. Two-Column Dashboard Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Left Column (2 cols): Recent Quiz Activity -->
      <div class="lg:col-span-2 bg-slate-800/90 border border-slate-700/80 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-700">
          <h2 class="text-lg font-black text-white flex items-center gap-2">
            <span>📜</span> Recent Student Quiz Activity
          </h2>
          <span class="text-xs font-bold text-slate-400">Latest Completed</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs font-semibold">
            <thead class="text-slate-400 uppercase text-[10px] font-black tracking-wider border-b border-slate-700/60">
              <tr>
                <th class="py-2.5 px-3">Time</th>
                <th class="py-2.5 px-3">Student</th>
                <th class="py-2.5 px-3">Parent</th>
                <th class="py-2.5 px-3">Subject</th>
                <th class="py-2.5 px-3 text-center">Score</th>
                <th class="py-2.5 px-3 text-right">Result</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/40 text-slate-200">
              <?php if (empty($recentSessions)): ?>
                <tr><td colspan="6" class="text-center py-6 text-slate-400">No quiz sessions recorded yet.</td></tr>
              <?php else: ?>
                <?php foreach ($recentSessions as $sess): ?>
                  <tr class="hover:bg-slate-700/20 transition-colors">
                    <td class="py-3 px-3 font-mono text-[11px] text-slate-400"><?= substr($sess['completed_at'], 5, 11) ?></td>
                    <td class="py-3 px-3 font-black text-white"><?= htmlspecialchars($sess['student_name']) ?></td>
                    <td class="py-3 px-3 text-slate-400"><?= htmlspecialchars($sess['parent_full_name'] ?? 'Direct / Legacy') ?></td>
                    <td class="py-3 px-3 text-slate-300"><?= htmlspecialchars($sess['subject_name'] ?? 'Mixed Quiz') ?></td>
                    <td class="py-3 px-3 text-center font-bold font-mono text-indigo-300"><?= $sess['score'] ?>/<?= $sess['total_questions'] ?></td>
                    <td class="py-3 px-3 text-right">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-black <?= $sess['percentage'] >= 70 ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-rose-500/20 text-rose-300 border border-rose-500/40' ?>">
                        <?= $sess['percentage'] ?>%
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Right Column (1 col): Recent Parents Registered -->
      <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-700">
          <h2 class="text-lg font-black text-white flex items-center gap-2">
            <span>👨‍👩‍👧</span> Recent Parents
          </h2>
          <a href="parents.php" class="text-xs font-bold text-indigo-400 hover:text-indigo-300">View All ➔</a>
        </div>

        <div class="space-y-3">
          <?php foreach ($recentParents as $p): ?>
            <div class="bg-slate-900/60 border border-slate-700/60 rounded-xl p-3 flex items-center justify-between">
              <div>
                <span class="font-bold text-sm text-white block"><?= htmlspecialchars($p['full_name']) ?></span>
                <span class="text-xs text-slate-400 font-mono">@<?= htmlspecialchars($p['username']) ?> • <span class="text-indigo-400"><?= $p['parent_code'] ?></span></span>
              </div>
              <div class="text-right">
                <span class="inline-block px-2 py-0.5 rounded-lg text-xs font-black bg-indigo-500/20 text-indigo-300">
                  <?= $p['kids_count'] ?> <?= $p['kids_count'] == 1 ? 'Kid' : 'Kids' ?>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="pt-4 border-t border-slate-700">
          <a 
            href="parents.php" 
            class="w-full bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-bold py-2.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-2"
          >
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Add / Edit Parents in CRUD Portal</span>
          </a>
        </div>
      </div>

    </div>

  </main>

  <footer class="bg-slate-950 border-t border-slate-800 py-4 px-6 text-center text-xs text-slate-500">
    NextGrade System Administration Portal • Role: Full Access System Admin • Database: MySQL 8.2 (nextgrade_db)
  </footer>

</body>
</html>
