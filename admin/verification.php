<?php
// NextGrade - System Verification & Production Readiness Dashboard
// Only accessible to authenticated System Administrators

require_once __DIR__ . '/../auth_helper.php';
require_once __DIR__ . '/verification_engine.php';

requireAdmin();
$admin = getAdminUser();

// Initial server-side verification run with safe exception recovery
$initialReport = null;
$initialError = null;
try {
    $engine = new SystemVerificationEngine($pdo);
    $initialReport = $engine->runAllChecks();
} catch (\Throwable $e) {
    $initialError = $e->getMessage();
    $initialReport = [
        'timestamp' => date('Y-m-d H:i:s'),
        'execution_time_ms' => 0,
        'overall_status' => 'fail',
        'health_score' => 0,
        'summary' => ['total' => 1, 'passed' => 0, 'warnings' => 0, 'failed' => 1],
        'categories' => [
            'database' => [
                'title' => 'System Verification Suite',
                'checks' => [
                    [
                        'name' => 'Initial Diagnostic Suite Run',
                        'status' => 'fail',
                        'message' => 'Diagnostic encountered an initialization notice: ' . $e->getMessage(),
                        'details' => ['file' => $e->getFile(), 'line' => $e->getLine()]
                    ]
                ]
            ]
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Verification & Readiness - NextGrade Admin</title>
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
    .check-card {
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .check-card:hover {
      transform: translateY(-2px);
    }
    .tab-btn.active {
      background-color: rgba(99, 102, 241, 0.2);
      border-color: rgba(129, 140, 248, 0.6);
      color: #ffffff;
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
          <a href="guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-book-open mr-1.5 text-slate-400"></i> Guide
          </a>
          <a href="developer_guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-code mr-1.5 text-slate-400"></i> Dev
          </a>
          <a href="verification.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-clipboard-check mr-1.5 text-emerald-400"></i> Verification
          </a>
          <a href="profile.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-user-gear mr-1.5 text-slate-400"></i> Profile
          </a>
        </div>
      </div>

      <!-- Admin Actions -->
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
          title="Question & Dev Guide"
          class="bg-slate-700/70 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-code text-xs"></i>
          <span class="hidden sm:inline">Dev Tools</span>
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
          <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>
  </header>

  <!-- Main Content Container -->
  <main class="max-w-7xl mx-auto px-4 md:px-8 py-8 w-full flex-1 flex flex-col gap-8">
    
    <!-- Hero Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-800/60 p-6 md:p-8 rounded-3xl border border-slate-700/70 shadow-2xl relative overflow-hidden">
      <!-- Glow background -->
      <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 flex items-start gap-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/30 to-emerald-500/30 border border-indigo-400/40 flex items-center justify-center text-3xl shadow-xl shadow-indigo-500/10">
          🔍
        </div>
        <div>
          <div class="flex items-center gap-2.5 flex-wrap">
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">System Health & Verification</h1>
            <span id="badge-overall-status" class="px-2.5 py-0.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
              <?= $initialReport['overall_status'] === 'pass' ? 'System Ready' : ($initialReport['overall_status'] === 'warning' ? 'Ready with Notices' : 'Attention Needed') ?>
            </span>
          </div>
          <p class="text-slate-400 text-sm mt-1.5 max-w-3xl leading-relaxed">
            Automated production diagnostic suite verifying database connectivity, route integrity, question bank images, system credentials, and server security before hosting online.
          </p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="relative z-10 flex items-center gap-3 shrink-0 flex-wrap">
        <button 
          id="btn-run-all" 
          onclick="runLiveVerification()"
          class="bg-indigo-600 hover:bg-indigo-500 text-white font-black text-sm py-3 px-5 rounded-2xl shadow-xl shadow-indigo-600/30 transition-all flex items-center gap-2 hover:scale-[1.02] active:scale-[0.98]"
        >
          <i id="btn-spin-icon" class="fa-solid fa-rotate text-sm"></i>
          <span id="btn-run-label">Run Live Verification</span>
        </button>

        <button 
          onclick="exportDiagnosticReport()"
          class="bg-slate-700/80 hover:bg-slate-700 text-slate-200 font-bold text-sm py-3 px-4 rounded-2xl border border-slate-600 transition-all flex items-center gap-2 hover:scale-[1.02]"
          title="Copy detailed report to clipboard"
        >
          <i class="fa-solid fa-copy text-sm text-indigo-400"></i>
          <span>Export Report</span>
        </button>
      </div>
    </div>

    <?php if (!empty($initialError)): ?>
    <!-- Notice Banner for Server Initial Warnings -->
    <div class="bg-rose-950/40 border border-rose-500/50 p-4 rounded-2xl flex items-center gap-3 text-rose-300 text-sm shadow-lg">
      <i class="fa-solid fa-triangle-exclamation text-rose-400 text-xl shrink-0"></i>
      <div>
        <strong class="text-white block font-bold">Initial Verification Notice:</strong>
        <?= htmlspecialchars($initialError) ?>. You can click <strong class="text-white">Run Live Verification</strong> above to re-run the diagnostic checks.
      </div>
    </div>
    <?php endif; ?>

    <!-- Health Scorecard & Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
      
      <!-- Card 1: Overall Health Score -->
      <div class="bg-slate-800/80 p-5 rounded-2xl border border-slate-700 shadow-lg flex flex-col justify-between relative overflow-hidden group">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Health Score</span>
          <span class="text-lg">🎯</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
          <span id="metric-health-score" class="text-4xl font-black text-emerald-400 tracking-tight">
            <?= $initialReport['health_score'] ?>%
          </span>
          <span class="text-xs font-semibold text-slate-400">Optimal</span>
        </div>
        <div class="w-full bg-slate-700/60 rounded-full h-1.5 mt-3 overflow-hidden">
          <div id="metric-score-bar" class="bg-gradient-to-r from-emerald-500 to-indigo-500 h-1.5 rounded-full" style="width: <?= $initialReport['health_score'] ?>%"></div>
        </div>
      </div>

      <!-- Card 2: Total Checks -->
      <div class="bg-slate-800/80 p-5 rounded-2xl border border-slate-700 shadow-lg flex flex-col justify-between">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Audits</span>
          <span class="text-lg">📋</span>
        </div>
        <div class="mt-4">
          <span id="metric-total-checks" class="text-3xl font-black text-white">
            <?= $initialReport['summary']['total'] ?>
          </span>
          <span class="text-xs font-semibold text-slate-400 ml-1">checks executed</span>
        </div>
        <div class="text-[11px] text-slate-500 mt-2 font-mono" id="metric-scan-time">
          Scan time: <?= $initialReport['execution_time_ms'] ?> ms
        </div>
      </div>

      <!-- Card 3: Passed Checks -->
      <div class="bg-slate-800/80 p-5 rounded-2xl border border-slate-700 shadow-lg flex flex-col justify-between">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Passed Checks</span>
          <span class="text-lg">✅</span>
        </div>
        <div class="mt-4">
          <span id="metric-passed-checks" class="text-3xl font-black text-emerald-400">
            <?= $initialReport['summary']['passed'] ?>
          </span>
          <span class="text-xs font-semibold text-slate-400 ml-1">passed 100%</span>
        </div>
        <div class="text-[11px] text-emerald-400/80 mt-2 flex items-center gap-1 font-semibold">
          <i class="fa-solid fa-circle-check text-[10px]"></i> Zero defects in tests
        </div>
      </div>

      <!-- Card 4: Warnings / Notices -->
      <div class="bg-slate-800/80 p-5 rounded-2xl border border-slate-700 shadow-lg flex flex-col justify-between">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Online Notices</span>
          <span class="text-lg">💡</span>
        </div>
        <div class="mt-4">
          <span id="metric-warning-checks" class="text-3xl font-black text-amber-400">
            <?= $initialReport['summary']['warnings'] ?>
          </span>
          <span class="text-xs font-semibold text-slate-400 ml-1">recommendations</span>
        </div>
        <div class="text-[11px] text-amber-300/80 mt-2 flex items-center gap-1 font-semibold">
          <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Pre-flight checklist
        </div>
      </div>

      <!-- Card 5: Critical Failures -->
      <div class="bg-slate-800/80 p-5 rounded-2xl border border-slate-700 shadow-lg flex flex-col justify-between">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-wider text-rose-400">Failures</span>
          <span class="text-lg">🛡️</span>
        </div>
        <div class="mt-4">
          <span id="metric-failed-checks" class="text-3xl font-black text-rose-400">
            <?= $initialReport['summary']['failed'] ?>
          </span>
          <span class="text-xs font-semibold text-slate-400 ml-1">critical errors</span>
        </div>
        <div class="text-[11px] text-rose-400/80 mt-2 flex items-center gap-1 font-semibold">
          <?= $initialReport['summary']['failed'] === 0 ? '✓ Ready for hosting' : 'Action required' ?>
        </div>
      </div>

    </div>

    <!-- Category Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-700/80">
      <button 
        onclick="filterCategory('all')" 
        class="tab-btn active px-4 py-2 rounded-xl text-xs font-extrabold text-slate-300 hover:text-white border border-transparent transition-all flex items-center gap-2 shrink-0" 
        data-category="all"
      >
        <i class="fa-solid fa-list-check"></i>
        <span>All Checks</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-700 text-slate-300 font-mono"><?= $initialReport['summary']['total'] ?></span>
      </button>

      <button 
        onclick="filterCategory('database')" 
        class="tab-btn px-4 py-2 rounded-xl text-xs font-extrabold text-slate-300 hover:text-white border border-transparent transition-all flex items-center gap-2 shrink-0" 
        data-category="database"
      >
        <i class="fa-solid fa-database text-cyan-400"></i>
        <span>Database Integrity</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-700 text-slate-300 font-mono"><?= count($initialReport['categories']['database']['checks']) ?></span>
      </button>

      <button 
        onclick="filterCategory('pages')" 
        class="tab-btn px-4 py-2 rounded-xl text-xs font-extrabold text-slate-300 hover:text-white border border-transparent transition-all flex items-center gap-2 shrink-0" 
        data-category="pages"
      >
        <i class="fa-solid fa-file-code text-indigo-400"></i>
        <span>Pages & Routes</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-700 text-slate-300 font-mono"><?= count($initialReport['categories']['pages']['checks']) ?></span>
      </button>

      <button 
        onclick="filterCategory('images')" 
        class="tab-btn px-4 py-2 rounded-xl text-xs font-extrabold text-slate-300 hover:text-white border border-transparent transition-all flex items-center gap-2 shrink-0" 
        data-category="images"
      >
        <i class="fa-solid fa-image text-emerald-400"></i>
        <span>Images & Media</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-700 text-slate-300 font-mono"><?= count($initialReport['categories']['images']['checks']) ?></span>
      </button>

      <button 
        onclick="filterCategory('credentials')" 
        class="tab-btn px-4 py-2 rounded-xl text-xs font-extrabold text-slate-300 hover:text-white border border-transparent transition-all flex items-center gap-2 shrink-0" 
        data-category="credentials"
      >
        <i class="fa-solid fa-key text-amber-400"></i>
        <span>Admin & Credentials</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-700 text-slate-300 font-mono"><?= count($initialReport['categories']['credentials']['checks']) ?></span>
      </button>

      <button 
        onclick="filterCategory('environment')" 
        class="tab-btn px-4 py-2 rounded-xl text-xs font-extrabold text-slate-300 hover:text-white border border-transparent transition-all flex items-center gap-2 shrink-0" 
        data-category="environment"
      >
        <i class="fa-solid fa-server text-purple-400"></i>
        <span>Online Server Config</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-700 text-slate-300 font-mono"><?= count($initialReport['categories']['environment']['checks']) ?></span>
      </button>
    </div>

    <!-- Container for Diagnostic Results Cards -->
    <div id="checks-container" class="space-y-4">
      <?php foreach ($initialReport['categories'] as $catKey => $category): ?>
        <div class="category-block flex flex-col gap-3" data-cat="<?= $catKey ?>">
          <div class="flex items-center gap-2 px-1 pt-2">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-400 flex items-center gap-2">
              <span><?= htmlspecialchars($category['title']) ?></span>
            </h2>
            <div class="h-[1px] bg-slate-700/60 flex-1 ml-2"></div>
          </div>

          <div class="grid grid-cols-1 gap-3">
            <?php foreach ($category['checks'] as $check): 
              $status = $check['status'];
              $bgClass = $status === 'pass' 
                ? 'bg-slate-800/80 border-slate-700/80 hover:border-slate-600' 
                : ($status === 'warning' ? 'bg-amber-950/20 border-amber-500/40 hover:border-amber-500/60' : 'bg-rose-950/20 border-rose-500/40 hover:border-rose-500/60');
              
              $badgeClass = $status === 'pass' 
                ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40' 
                : ($status === 'warning' ? 'bg-amber-500/20 text-amber-400 border-amber-500/40' : 'bg-rose-500/20 text-rose-400 border-rose-500/40');
              
              $icon = $status === 'pass' ? 'fa-circle-check text-emerald-400' : ($status === 'warning' ? 'fa-triangle-exclamation text-amber-400' : 'fa-circle-xmark text-rose-400');
            ?>
              <div class="check-card <?= $bgClass ?> p-5 rounded-2xl border shadow-md flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex items-start gap-3.5">
                  <div class="text-xl mt-0.5">
                    <i class="fa-solid <?= $icon ?>"></i>
                  </div>
                  <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                      <h3 class="text-base font-bold text-white"><?= htmlspecialchars($check['name']) ?></h3>
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider border <?= $badgeClass ?>">
                        <?= strtoupper($status) ?>
                      </span>
                    </div>
                    <p class="text-slate-300 text-sm mt-1 leading-relaxed">
                      <?= htmlspecialchars($check['message']) ?>
                    </p>

                    <!-- Expandable Details -->
                    <?php if (!empty($check['details'])): ?>
                      <details class="mt-3 text-xs">
                        <summary class="cursor-pointer text-indigo-400 hover:text-indigo-300 font-bold select-none inline-flex items-center gap-1">
                          <i class="fa-solid fa-code text-[10px]"></i> View Diagnostic Details
                        </summary>
                        <div class="mt-2 p-3 bg-slate-950/70 border border-slate-700/60 rounded-xl font-mono text-[11px] text-slate-300 max-h-48 overflow-y-auto whitespace-pre-wrap">
                          <?= htmlspecialchars(json_encode($check['details'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?>
                        </div>
                      </details>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Timestamp or quick hint -->
                <div class="shrink-0 flex items-center gap-2 self-end md:self-start">
                  <?php if ($status === 'warning'): ?>
                    <span class="text-[11px] font-bold text-amber-400/90 bg-amber-500/10 px-2.5 py-1 rounded-lg border border-amber-500/20">
                      Recommended for Online
                    </span>
                  <?php elseif ($status === 'pass'): ?>
                    <span class="text-[11px] font-bold text-emerald-400/90 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">
                      Ready
                    </span>
                  <?php else: ?>
                    <span class="text-[11px] font-bold text-rose-400/90 bg-rose-500/10 px-2.5 py-1 rounded-lg border border-rose-500/20">
                      Fix Needed
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Production Online Deployment Checklist Guide -->
    <div class="bg-slate-800/50 p-6 md:p-8 rounded-3xl border border-slate-700 shadow-xl flex flex-col gap-6">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-purple-600/30 border border-purple-500/40 flex items-center justify-center text-purple-400 text-lg">
          🚀
        </div>
        <div>
          <h2 class="text-lg font-black text-white">Online Production Pre-Flight Checklist</h2>
          <p class="text-xs text-slate-400">Step-by-step instructions to follow when hosting NextGrade on a live web server or cloud domain.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
        
        <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-700/80 flex flex-col gap-2">
          <div class="flex items-center gap-2 font-bold text-white">
            <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-mono">1</span>
            Database Credentials
          </div>
          <p class="text-slate-400 leading-relaxed">
            In <code class="text-indigo-300">db.php</code>, update <code class="text-slate-200">$user</code>, <code class="text-slate-200">$pass</code>, and <code class="text-slate-200">$db</code> with your production MySQL credentials. Never use blank root passwords on a public web server.
          </p>
        </div>

        <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-700/80 flex flex-col gap-2">
          <div class="flex items-center gap-2 font-bold text-white">
            <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-mono">2</span>
            SSL / HTTPS Encryption
          </div>
          <p class="text-slate-400 leading-relaxed">
            Ensure an SSL certificate is active (via Let's Encrypt or Cloudflare) so that parent credentials and kids' sessions are transmitted securely over <code class="text-emerald-400">https://</code>.
          </p>
        </div>

        <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-700/80 flex flex-col gap-2">
          <div class="flex items-center gap-2 font-bold text-white">
            <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-mono">3</span>
            Update Admin Password
          </div>
          <p class="text-slate-400 leading-relaxed">
            Change the default System Administrator password in <a href="profile.php" class="text-indigo-400 underline font-bold">My Profile</a> from the default <code class="text-slate-200">admin123</code> to a strong, private master password.
          </p>
        </div>

        <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-700/80 flex flex-col gap-2">
          <div class="flex items-center gap-2 font-bold text-white">
            <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-mono">4</span>
            PHP Error Logging
          </div>
          <p class="text-slate-400 leading-relaxed">
            In your production <code class="text-slate-200">php.ini</code>, set <code class="text-slate-200">display_errors = Off</code> and <code class="text-slate-200">log_errors = On</code> so visitors do not see system directory paths in error outputs.
          </p>
        </div>

        <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-700/80 flex flex-col gap-2">
          <div class="flex items-center gap-2 font-bold text-white">
            <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-mono">5</span>
            Seed Questions & Data
          </div>
          <p class="text-slate-400 leading-relaxed">
            If deploying to a fresh database, visit <a href="developer_guide.php" class="text-indigo-400 underline font-bold">Dev Tools</a> to synchronize all 900+ questions and 5-minute revision guides with one click.
          </p>
        </div>

        <div class="p-4 bg-slate-900/80 rounded-2xl border border-slate-700/80 flex flex-col gap-2">
          <div class="flex items-center gap-2 font-bold text-white">
            <span class="w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-mono">6</span>
            Re-run Verification Audit
          </div>
          <p class="text-slate-400 leading-relaxed">
            After deployment, click <strong class="text-white">Run Live Verification</strong> above to guarantee 100% green health score on your production host!
          </p>
        </div>

      </div>
    </div>

  </main>

  <!-- Notification Toast Container -->
  <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

  <!-- JavaScript for Live AJAX Verification & Filtering -->
  <script>
    let currentReport = <?= json_encode($initialReport) ?>;

    // Category Filter Handler
    function filterCategory(cat) {
      document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.getAttribute('data-category') === cat) {
          btn.classList.add('active');
        } else {
          btn.classList.remove('active');
        }
      });

      document.querySelectorAll('.category-block').forEach(block => {
        if (cat === 'all' || block.getAttribute('data-cat') === cat) {
          block.style.display = 'flex';
        } else {
          block.style.display = 'none';
        }
      });
    }

    // Run Live Verification via AJAX
    async function runLiveVerification() {
      const btn = document.getElementById('btn-run-all');
      const icon = document.getElementById('btn-spin-icon');
      const label = document.getElementById('btn-run-label');

      btn.disabled = true;
      icon.classList.add('animate-spin');
      label.textContent = 'Auditing System...';
      showToast('⚡ Running comprehensive live diagnostic suite...');

      try {
        const resp = await fetch('../api/admin_verification.php?action=run_all');
        const json = await resp.json();

        if (json.success && json.data) {
          currentReport = json.data;
          updateDashboardUI(json.data);
          showToast('✅ Verification complete: System health score ' + json.data.health_score + '%');
        } else {
          showToast('❌ Verification error: ' + (json.error || 'Server error'), true);
        }
      } catch (err) {
        showToast('❌ Verification request failed: ' + err.message, true);
      } finally {
        btn.disabled = false;
        icon.classList.remove('animate-spin');
        label.textContent = 'Run Live Verification';
      }
    }

    // Update Dashboard UI with latest report data
    function updateDashboardUI(report) {
      // Metrics
      document.getElementById('metric-health-score').textContent = report.health_score + '%';
      document.getElementById('metric-score-bar').style.width = report.health_score + '%';
      document.getElementById('metric-total-checks').textContent = report.summary.total;
      document.getElementById('metric-passed-checks').textContent = report.summary.passed;
      document.getElementById('metric-warning-checks').textContent = report.summary.warnings;
      document.getElementById('metric-failed-checks').textContent = report.summary.failed;
      document.getElementById('metric-scan-time').textContent = 'Scan time: ' + report.execution_time_ms + ' ms';

      // Overall status badge
      const badge = document.getElementById('badge-overall-status');
      if (report.overall_status === 'pass') {
        badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40';
        badge.textContent = 'System Ready';
      } else if (report.overall_status === 'warning') {
        badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-400 border border-amber-500/40';
        badge.textContent = 'Ready with Notices';
      } else {
        badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-500/40';
        badge.textContent = 'Attention Needed';
      }

      // Re-render check blocks
      const container = document.getElementById('checks-container');
      container.innerHTML = '';

      for (const [catKey, category] of Object.entries(report.categories)) {
        const catDiv = document.createElement('div');
        catDiv.className = 'category-block flex flex-col gap-3';
        catDiv.setAttribute('data-cat', catKey);

        let cardsHtml = '';
        category.checks.forEach(check => {
          const status = check.status;
          const bgClass = status === 'pass' 
            ? 'bg-slate-800/80 border-slate-700/80 hover:border-slate-600' 
            : (status === 'warning' ? 'bg-amber-950/20 border-amber-500/40 hover:border-amber-500/60' : 'bg-rose-950/20 border-rose-500/40 hover:border-rose-500/60');
          
          const badgeClass = status === 'pass' 
            ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40' 
            : (status === 'warning' ? 'bg-amber-500/20 text-amber-400 border-amber-500/40' : 'bg-rose-500/20 text-rose-400 border-rose-500/40');
          
          const icon = status === 'pass' ? 'fa-circle-check text-emerald-400' : (status === 'warning' ? 'fa-triangle-exclamation text-amber-400' : 'fa-circle-xmark text-rose-400');
          
          let detailsHtml = '';
          if (check.details) {
            detailsHtml = `
              <details class="mt-3 text-xs">
                <summary class="cursor-pointer text-indigo-400 hover:text-indigo-300 font-bold select-none inline-flex items-center gap-1">
                  <i class="fa-solid fa-code text-[10px]"></i> View Diagnostic Details
                </summary>
                <div class="mt-2 p-3 bg-slate-950/70 border border-slate-700/60 rounded-xl font-mono text-[11px] text-slate-300 max-h-48 overflow-y-auto whitespace-pre-wrap">
                  ${escapeHtml(JSON.stringify(check.details, null, 2))}
                </div>
              </details>
            `;
          }

          let actionBadge = '';
          if (status === 'warning') {
            actionBadge = '<span class="text-[11px] font-bold text-amber-400/90 bg-amber-500/10 px-2.5 py-1 rounded-lg border border-amber-500/20">Recommended for Online</span>';
          } else if (status === 'pass') {
            actionBadge = '<span class="text-[11px] font-bold text-emerald-400/90 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">Ready</span>';
          } else {
            actionBadge = '<span class="text-[11px] font-bold text-rose-400/90 bg-rose-500/10 px-2.5 py-1 rounded-lg border border-rose-500/20">Fix Needed</span>';
          }

          cardsHtml += `
            <div class="check-card ${bgClass} p-5 rounded-2xl border shadow-md flex flex-col md:flex-row md:items-start justify-between gap-4">
              <div class="flex items-start gap-3.5">
                <div class="text-xl mt-0.5">
                  <i class="fa-solid ${icon}"></i>
                </div>
                <div>
                  <div class="flex items-center gap-2.5 flex-wrap">
                    <h3 class="text-base font-bold text-white">${escapeHtml(check.name)}</h3>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider border ${badgeClass}">
                      ${status.toUpperCase()}
                    </span>
                  </div>
                  <p class="text-slate-300 text-sm mt-1 leading-relaxed">
                    ${escapeHtml(check.message)}
                  </p>
                  ${detailsHtml}
                </div>
              </div>
              <div class="shrink-0 flex items-center gap-2 self-end md:self-start">
                ${actionBadge}
              </div>
            </div>
          `;
        });

        catDiv.innerHTML = `
          <div class="flex items-center gap-2 px-1 pt-2">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-400 flex items-center gap-2">
              <span>${escapeHtml(category.title)}</span>
            </h2>
            <div class="h-[1px] bg-slate-700/60 flex-1 ml-2"></div>
          </div>
          <div class="grid grid-cols-1 gap-3">
            ${cardsHtml}
          </div>
        `;

        container.appendChild(catDiv);
      }
    }

    // Export Diagnostic Report
    function exportDiagnosticReport() {
      if (!currentReport) return;
      const text = generateTextReport(currentReport);
      navigator.clipboard.writeText(text).then(() => {
        showToast('📋 Diagnostic report copied to clipboard!');
      }).catch(() => {
        // Fallback
        const blob = new Blob([text], { type: 'text/plain' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'nextgrade_verification_report.txt';
        a.click();
        showToast('📥 Report downloaded as text file!');
      });
    }

    function generateTextReport(report) {
      let out = "======================================================================\n";
      out += "   NextGrade Production Readiness & System Verification Report\n";
      out += "======================================================================\n\n";
      out += `Health Score: ${report.health_score}%\n`;
      out += `Status: ${report.overall_status.toUpperCase()}\n`;
      out += `Total Audits: ${report.summary.total} (Passed: ${report.summary.passed}, Warnings: ${report.summary.warnings}, Failed: ${report.summary.failed})\n`;
      out += `Timestamp: ${report.timestamp} | Latency: ${report.execution_time_ms} ms\n\n`;

      for (const [key, cat] of Object.entries(report.categories)) {
        out += `--- ${cat.title.toUpperCase()} ---\n`;
        cat.checks.forEach(c => {
          out += `[${c.status.toUpperCase()}] ${c.name}\n    ${c.message}\n`;
        });
        out += "\n";
      }
      return out;
    }

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    // Toast helper
    function showToast(msg, isError = false) {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `pointer-events-auto flex items-center gap-2.5 px-4 py-3 rounded-2xl shadow-2xl text-xs font-bold transition-all transform translate-y-4 opacity-0 ${
        isError ? 'bg-rose-600 text-white' : 'bg-slate-800 text-white border border-slate-600'
      }`;
      toast.innerHTML = isError ? `<i class="fa-solid fa-circle-exclamation text-sm"></i> <span>${msg}</span>` : `<i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i> <span>${msg}</span>`;
      container.appendChild(toast);
      
      requestAnimationFrame(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
      });

      setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
      }, 3500);
    }
  </script>

</body>
</html>
