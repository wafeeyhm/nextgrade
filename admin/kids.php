<?php
// NextGrade - System Admin: Students & Kids Overview
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();

// Fetch all students with parent info and quiz stats
$students = $pdo->query("
    SELECT 
        s.*, 
        p.full_name as parent_name, 
        p.parent_code,
        p.email as parent_email,
        COUNT(qs.id) as sessions_count,
        COALESCE(AVG(qs.percentage), 0) as avg_score,
        COALESCE(MAX(qs.completed_at), s.last_active) as latest_activity
    FROM students s
    LEFT JOIN parents p ON p.id = s.parent_id
    LEFT JOIN quiz_sessions qs ON qs.student_id = s.id
    GROUP BY s.id
    ORDER BY s.id DESC
")->fetchAll();

$avatarMap = [
    'star_kid' => '⭐',
    'bunny' => '🐰',
    'astronaut' => '🚀',
    'dino' => '🦖',
    'kitten' => '🐱',
    'unicorn' => '🦄'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students & Kids Overview - System Admin | NextGrade</title>
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

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight flex items-center gap-2.5">
          <span>🧒</span>
          <span>Students & Kids Directory</span>
        </h1>
        <p class="text-sm font-semibold text-slate-400 mt-1">
          Platform-wide view of all registered children, simple login usernames, PINs, and linked parents.
        </p>
      </div>

      <a 
        href="parents.php"
        class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2"
      >
        <i class="fa-solid fa-users"></i>
        <span>Manage via Parents</span>
      </a>
    </div>

    <!-- Table Card -->
    <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl overflow-hidden shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-900/80 border-b border-slate-700 text-slate-400 uppercase text-[11px] font-black tracking-wider">
            <tr>
              <th class="py-3.5 px-4">Kid Profile</th>
              <th class="py-3.5 px-4">Simple Access (Login)</th>
              <th class="py-3.5 px-4">Parent / Family Account</th>
              <th class="py-3.5 px-4 text-center">Grade Level</th>
              <th class="py-3.5 px-4 text-center">Quizzes Done</th>
              <th class="py-3.5 px-4 text-center">Avg Score</th>
              <th class="py-3.5 px-4 text-right">Last Active</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-700/50 text-slate-200 font-medium">
            <?php if (empty($students)): ?>
              <tr><td colspan="7" class="text-center py-10 text-slate-400 font-bold">No students registered.</td></tr>
            <?php else: ?>
              <?php foreach ($students as $s): ?>
                <tr class="hover:bg-slate-700/30 transition-colors">
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                      <span class="text-2xl p-1.5 rounded-xl bg-slate-900 border border-slate-700">
                        <?= $avatarMap[$s['avatar']] ?? '⭐' ?>
                      </span>
                      <div>
                        <span class="font-black text-white text-sm block"><?= htmlspecialchars($s['name']) ?></span>
                        <span class="text-[11px] text-slate-400">ID: #<?= $s['id'] ?></span>
                      </div>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-xs">
                    <div>User: <strong class="text-indigo-400"><?= htmlspecialchars($s['username'] ?? 'Not set') ?></strong></div>
                    <div>PIN: <strong class="text-amber-400"><?= htmlspecialchars($s['pin_code'] ?? '1234') ?></strong></div>
                  </td>
                  <td class="py-3.5 px-4 text-xs">
                    <?php if ($s['parent_id']): ?>
                      <div class="font-bold text-white"><?= htmlspecialchars($s['parent_name']) ?></div>
                      <div class="text-indigo-400 font-mono text-[11px]"><?= $s['parent_code'] ?></div>
                    <?php else: ?>
                      <span class="text-slate-500 italic">No parent linked</span>
                    <?php endif; ?>
                  <td class="py-3.5 px-4 text-center text-xs font-bold text-slate-300">
                    <?php if (isGradeYear6($s['grade_level'] ?? '')): ?>
                      <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 px-2.5 py-1 rounded-lg font-black inline-block">
                        🇧🇳 Year 6 (PSR)
                      </span>
                    <?php elseif (isGradeKG3($s['grade_level'] ?? '')): ?>
                      <span class="bg-sky-500/20 text-sky-300 border border-sky-500/40 px-2.5 py-1 rounded-lg font-black inline-block">
                        ⭐ KG3
                      </span>
                    <?php else: ?>
                      <span class="bg-slate-900 px-2.5 py-1 rounded-lg border border-slate-700 text-slate-400">
                        <?= htmlspecialchars($s['grade_level'] ?? 'Year 1') ?>
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="py-3.5 px-4 text-center font-black text-indigo-400 font-mono">
                    <?= $s['sessions_count'] ?>
                  </td>
                  <td class="py-3.5 px-4 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-black <?= $s['avg_score'] >= 70 ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' ?>">
                      <?= round($s['avg_score']) ?>%
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-right font-mono text-xs text-slate-400">
                    <?= substr($s['latest_activity'], 0, 16) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>

</body>
</html>
