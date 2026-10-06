<?php
// NextGrade - Main Dashboard & Simple Kid Login Gate
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helper.php';

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? '';
$studentAvatar = $_SESSION['student_avatar'] ?? 'star_kid';
$studentId = $_SESSION['student_id'] ?? null;
$studentGrade = $_SESSION['student_grade'] ?? null;

if ($studentId || $studentName) {
  $stmtChk = $pdo->prepare("
    SELECT s.*, p.status as parent_status 
    FROM students s 
    LEFT JOIN parents p ON p.id = s.parent_id 
    WHERE s.id = ? OR s.name = ? 
    LIMIT 1
  ");
  $stmtChk->execute([$studentId ?? 0, $studentName ?? '']);
  $sRow = $stmtChk->fetch();

  if (!$sRow || $sRow['status'] !== 'active' || (!empty($sRow['parent_id']) && $sRow['parent_status'] !== 'active')) {
    unset(
      $_SESSION['student_name'],
      $_SESSION['student_id'],
      $_SESSION['student_avatar'],
      $_SESSION['student_username'],
      $_SESSION['student_parent_id'],
      $_SESSION['student_grade']
    );
    setcookie('student_name', '', time() - 3600, '/');
    $studentName = '';
    $studentId = null;
  } else {
    $studentGrade = $sRow['grade_level'];
    $_SESSION['student_grade'] = $studentGrade;
  }
}
$isKG3 = empty($studentGrade) || isGradeKG3($studentGrade);
$isYear6 = isGradeYear6($studentGrade);
$hasActiveCurriculum = $isKG3 || $isYear6;

// Handle student reset / switch
if (isset($_GET['reset'])) {
  unset(
    $_SESSION['student_name'],
    $_SESSION['student_id'],
    $_SESSION['student_avatar'],
    $_SESSION['student_username'],
    $_SESSION['student_parent_id'],
    $_SESSION['student_grade']
  );
  setcookie('student_name', '', time() - 3600, '/');
  header("Location: index.php");
  exit;
}

// Avatars catalogue
$avatars = [
  'star_kid' => ['label' => 'Star Kid', 'emoji' => '⭐'],
  'bunny' => ['label' => 'Wonder Bunny', 'emoji' => '🐰'],
  'astronaut' => ['label' => 'Astro Hero', 'emoji' => '🚀'],
  'dino' => ['label' => 'Smart Dino', 'emoji' => '🦖'],
  'kitten' => ['label' => 'Cute Kitty', 'emoji' => '🐱'],
  'unicorn' => ['label' => 'Magic Unicorn', 'emoji' => '🦄']
];

// Fetch active kids (only if student AND parent are active)
$activeKids = [];
try {
  $activeKids = $pdo->query("
    SELECT s.id, s.name, s.username, s.avatar, s.grade_level, s.pin_code 
    FROM students s 
    LEFT JOIN parents p ON p.id = s.parent_id 
    WHERE s.status = 'active' AND (p.id IS NULL OR p.status = 'active') 
    ORDER BY s.id ASC LIMIT 12
  ")->fetchAll();
} catch (Exception $e) {
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>NextGrade - Interactive Learning for Primary & Kindergarten</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="js/sounds.js"></script>
  <script src="js/speech.js"></script>
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
</head>

<body class="min-h-screen flex flex-col justify-between p-3 md:p-8 select-none">

  <?php if (empty($studentName)): ?>
    <!-- === 1. SIMPLE KID LOGIN GATE (TABLET & TOUCH OPTIMIZED) === -->
    <div class="flex-1 flex items-center justify-center py-4">
      <div class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-2xl border-4 border-sky-200 text-center max-w-2xl w-full relative overflow-hidden">

        <!-- Decorative Bubbles -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-sky-100 rounded-full blur-xl opacity-60"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-amber-100 rounded-full blur-xl opacity-60"></div>

        <!-- App Header -->
        <div class="relative z-10">
          <div class="text-6xl md:text-7xl mb-2 animate-bounce inline-block">🌟</div>
          <h1 class="text-3xl md:text-5xl font-black text-slate-800 mb-1 tracking-tight">
            Next<span class="text-sky-500">Grade</span>
          </h1>
          <p class="text-base md:text-lg font-extrabold text-slate-400 mb-6">
            Who is learning today? Tap your profile! 👇
          </p>

          <!-- Mode A: Registered Kids Profile Grid (One-Tap Kid Login) -->
          <div id="profiles-picker-section">
            <?php if (!empty($activeKids)): ?>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 md:gap-4 mb-6">
                <?php foreach ($activeKids as $k): ?>
                  <button
                    type="button"
                    onclick="selectKidProfile(<?= $k['id'] ?>, '<?= htmlspecialchars(addslashes($k['name'])) ?>', '<?= $k['avatar'] ?>', '<?= !empty($k['pin_code']) ? 'yes' : 'no' ?>')"
                    class="btn-chunky btn-white flex flex-col items-center justify-center p-4 rounded-3xl border-3 hover:border-sky-400 hover:bg-sky-50 hover:scale-105 transition-all group">
                    <span class="text-4xl md:text-5xl mb-2 group-hover:animate-wiggle transition-transform">
                      <?= $avatars[$k['avatar']]['emoji'] ?? '⭐' ?>
                    </span>
                    <span class="font-black text-slate-800 text-sm md:text-base leading-tight">
                      <?= htmlspecialchars($k['name']) ?>
                    </span>
                    <span class="text-[10px] font-bold text-slate-500 mt-1 flex items-center justify-center gap-1 flex-wrap">
                      <?php if (isGradeYear6($k['grade_level'])): ?>
                        <span class="text-emerald-700 font-extrabold bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-300">🇧🇳 Year 6 PSR</span>
                      <?php elseif (isGradeKG3($k['grade_level'])): ?>
                        <span class="text-sky-700 font-extrabold bg-sky-100 px-2 py-0.5 rounded-full border border-sky-300">⭐ KG3</span>
                      <?php else: ?>
                        <span class="text-amber-700 font-bold bg-amber-100 px-2 py-0.5 rounded-full border border-amber-300"><?= htmlspecialchars($k['grade_level']) ?></span>
                      <?php endif; ?>
                    </span>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <!-- Alternative Buttons: Username/PIN or New Child -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
              <button
                type="button"
                onclick="showUsernamePinLogin()"
                class="btn-chunky btn-white text-xs py-2 px-4 rounded-xl font-bold flex items-center gap-1.5">
                <i class="fa-solid fa-key text-amber-500"></i>
                <span>Enter Username & PIN</span>
              </button>

              <button
                type="button"
                onclick="showNewNameOnboarding()"
                class="btn-chunky btn-white text-xs py-2 px-4 rounded-xl font-bold flex items-center gap-1.5">
                <span>✨</span>
                <span>New Kid Name</span>
              </button>
            </div>
          </div>

          <!-- Mode B: Username & PIN Direct Entry (Hidden by default) -->
          <div id="username-pin-section" class="hidden text-left bg-sky-50/60 border-2 border-sky-200 rounded-3xl p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-user-lock text-sky-500"></i> Child Login Access
              </h3>
              <button onclick="backToProfilePicker()" class="text-xs font-bold text-sky-600 hover:underline">
                ⬅️ Back to Profiles
              </button>
            </div>

            <form id="form-username-pin" onsubmit="handleUsernamePinLogin(event)" class="space-y-4">
              <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Child Username</label>
                <input
                  type="text"
                  id="kid-username-input"
                  required
                  placeholder="e.g. lana"
                  class="w-full bg-white border-2 border-slate-200 rounded-xl px-4 py-2.5 text-base font-bold text-slate-800 focus:border-sky-400 focus:outline-none font-mono">
              </div>
              <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Simple 4-Digit PIN</label>
                <input
                  type="password"
                  id="kid-pin-input"
                  required
                  placeholder="••••"
                  maxlength="6"
                  class="w-full bg-white border-2 border-slate-200 rounded-xl px-4 py-2.5 text-center text-lg font-black tracking-widest font-mono text-amber-600 focus:border-sky-400 focus:outline-none">
              </div>
              <button type="submit" class="btn-chunky btn-primary w-full text-base py-3 rounded-xl font-black">
                Start Learning! 🚀
              </button>
            </form>
          </div>

          <!-- Mode C: Instant Name Onboarding (Hidden by default) -->
          <div id="new-name-section" class="hidden text-left bg-slate-50 border-2 border-slate-200 rounded-3xl p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-black text-slate-800">Hello superstar! What is your name?</h3>
              <button onclick="backToProfilePicker()" class="text-xs font-bold text-sky-600 hover:underline">
                ⬅️ Back
              </button>
            </div>

            <form id="onboarding-form" class="space-y-4">
              <input
                type="text"
                id="student-name-input"
                name="name"
                required
                placeholder="Type your name here..."
                class="w-full text-xl font-extrabold text-center text-sky-600 bg-white border-3 border-sky-200 rounded-xl p-3 focus:border-sky-400 focus:outline-none transition-all placeholder:text-slate-300" />

              <div>
                <label class="block text-xs font-bold text-slate-400 mb-2 uppercase tracking-wider">Choose your avatar:</label>
                <div class="grid grid-cols-6 gap-2">
                  <?php foreach ($avatars as $key => $av): ?>
                    <label class="cursor-pointer">
                      <input type="radio" name="avatar" value="<?= $key ?>" class="peer sr-only" <?= $key === 'star_kid' ? 'checked' : '' ?>>
                      <div class="text-3xl p-2 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-sky-500 peer-checked:bg-sky-100 peer-checked:scale-110 transition-all flex items-center justify-center">
                        <?= $av['emoji'] ?>
                      </div>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <button type="submit" class="btn-chunky btn-success w-full text-lg py-3 rounded-xl font-black">
                Let's Learn! 🚀
              </button>
            </form>
          </div>

          <!-- Bottom Portals Bar -->
          <div class="mt-8 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between text-xs font-bold text-slate-400 gap-2">
            <span>Tablet & iPad Ready 📱</span>
            <div class="flex items-center gap-3">
              <a href="parent_login.php" class="text-sky-600 hover:underline font-black flex items-center gap-1">
                <span>👨‍👩‍👧</span> Parent Portal
              </a>
              <span>•</span>
              <a href="admin/login.php" class="text-indigo-600 hover:underline font-black flex items-center gap-1">
                <i class="fa-solid fa-shield-halved text-[10px]"></i> Admin Portal
              </a>
            </div>
          </div>

        </div>

      </div>
    </div>

    <!-- KID 4-DIGIT PIN ENTRY MODAL (TABLET OPTIMIZED TOUCHPAD) -->
    <div id="kid-pin-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
      <div class="bg-white border-3 border-sky-300 rounded-[2.5rem] max-w-sm w-full p-6 text-center shadow-2xl relative">

        <div id="pin-modal-avatar" class="text-6xl mb-2 animate-bounce">⭐</div>
        <h3 id="pin-modal-name" class="text-2xl font-black text-slate-800">Child Name</h3>
        <p class="text-xs font-bold text-slate-400 mb-4">Enter your 4-digit PIN code:</p>

        <div id="pin-alert" class="hidden mb-3 p-2.5 rounded-xl text-xs font-bold"></div>

        <!-- PIN Display Circles -->
        <div class="flex justify-center gap-3 mb-6">
          <div id="pin-dot-1" class="w-4 h-4 rounded-full border-2 border-sky-400 bg-slate-100 transition-all"></div>
          <div id="pin-dot-2" class="w-4 h-4 rounded-full border-2 border-sky-400 bg-slate-100 transition-all"></div>
          <div id="pin-dot-3" class="w-4 h-4 rounded-full border-2 border-sky-400 bg-slate-100 transition-all"></div>
          <div id="pin-dot-4" class="w-4 h-4 rounded-full border-2 border-sky-400 bg-slate-100 transition-all"></div>
        </div>

        <!-- Tablet Keypad 1-9 & 0 -->
        <div class="grid grid-cols-3 gap-2.5 max-w-[240px] mx-auto mb-4">
          <?php for ($i = 1; $i <= 9; $i++): ?>
            <button
              type="button"
              onclick="pressPinDigit('<?= $i ?>')"
              class="btn-chunky btn-white text-xl py-3 rounded-2xl font-black hover:bg-sky-50 hover:border-sky-300 active:scale-95">
              <?= $i ?>
            </button>
          <?php endfor; ?>
          <button
            type="button"
            onclick="clearPin()"
            class="btn-chunky btn-white text-xs py-3 rounded-2xl font-bold text-rose-500 hover:bg-rose-50">
            Clear
          </button>
          <button
            type="button"
            onclick="pressPinDigit('0')"
            class="btn-chunky btn-white text-xl py-3 rounded-2xl font-black hover:bg-sky-50 hover:border-sky-300 active:scale-95">
            0
          </button>
          <button
            type="button"
            onclick="closePinModal()"
            class="btn-chunky btn-white text-xs py-3 rounded-2xl font-bold text-slate-400 hover:bg-slate-100">
            ✕
          </button>
        </div>

        <div class="text-[11px] text-slate-400 font-bold">
          Default PIN: <strong class="text-amber-600 font-mono">1234</strong>
        </div>
      </div>
    </div>

    <script>
      let activeKidId = null;
      let activeKidName = '';
      let currentPinString = '';
      const avatarMap = {
        'star_kid': '⭐',
        'bunny': '🐰',
        'astronaut': '🚀',
        'dino': '🦖',
        'kitten': '🐱',
        'unicorn': '🦄'
      };

      function showUsernamePinLogin() {
        SoundEffects.playPop();
        document.getElementById('profiles-picker-section').classList.add('hidden');
        document.getElementById('username-pin-section').classList.remove('hidden');
        document.getElementById('new-name-section').classList.add('hidden');
      }

      function showNewNameOnboarding() {
        SoundEffects.playPop();
        document.getElementById('profiles-picker-section').classList.add('hidden');
        document.getElementById('username-pin-section').classList.add('hidden');
        document.getElementById('new-name-section').classList.remove('hidden');
      }

      function backToProfilePicker() {
        SoundEffects.playPop();
        document.getElementById('profiles-picker-section').classList.remove('hidden');
        document.getElementById('username-pin-section').classList.add('hidden');
        document.getElementById('new-name-section').classList.add('hidden');
      }

      function selectKidProfile(id, name, avatar, hasPin) {
        SoundEffects.playPop();
        activeKidId = id;
        activeKidName = name;
        currentPinString = '';
        updatePinDots();

        document.getElementById('pin-modal-avatar').textContent = avatarMap[avatar] || '⭐';
        document.getElementById('pin-modal-name').textContent = name;
        document.getElementById('pin-alert').className = 'hidden';
        document.getElementById('kid-pin-modal').classList.remove('hidden');
      }

      function closePinModal() {
        SoundEffects.playPop();
        document.getElementById('kid-pin-modal').classList.add('hidden');
        currentPinString = '';
        updatePinDots();
      }

      function pressPinDigit(digit) {
        if (currentPinString.length < 4) {
          SoundEffects.playPop();
          currentPinString += digit;
          updatePinDots();

          if (currentPinString.length === 4) {
            submitKidPin();
          }
        }
      }

      function clearPin() {
        SoundEffects.playPop();
        currentPinString = '';
        updatePinDots();
      }

      function updatePinDots() {
        for (let i = 1; i <= 4; i++) {
          const dot = document.getElementById(`pin-dot-${i}`);
          if (i <= currentPinString.length) {
            dot.className = 'w-4 h-4 rounded-full bg-sky-500 scale-125 border-2 border-sky-600 transition-all';
          } else {
            dot.className = 'w-4 h-4 rounded-full border-2 border-sky-400 bg-slate-100 transition-all';
          }
        }
      }

      async function submitKidPin() {
        const alertBox = document.getElementById('pin-alert');
        alertBox.className = 'hidden';

        try {
          const resp = await fetch('api/auth.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'kid_login',
              student_id: activeKidId,
              pin_code: currentPinString
            })
          });
          const data = await resp.json();

          if (data.success) {
            SoundEffects.playFanfare();
            alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 block';
            alertBox.textContent = `Welcome, ${activeKidName}! 🌟`;
            NextGradeSpeech.speak(`Welcome, ${activeKidName}! Let's learn!`, 'en');
            setTimeout(() => {
              window.location.reload();
            }, 600);
          } else {
            SoundEffects.playBoop();
            alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-100 text-rose-800 block';
            alertBox.textContent = data.error || 'Incorrect PIN code.';
            currentPinString = '';
            updatePinDots();
          }
        } catch (err) {
          console.error(err);
          alertBox.className = 'mb-3 p-2.5 rounded-xl text-xs font-bold bg-rose-100 text-rose-800 block';
          alertBox.textContent = 'Connection error.';
          currentPinString = '';
          updatePinDots();
        }
      }

      async function handleUsernamePinLogin(e) {
        e.preventDefault();
        SoundEffects.playPop();
        const username = document.getElementById('kid-username-input').value.trim();
        const pin_code = document.getElementById('kid-pin-input').value.trim();

        try {
          const resp = await fetch('api/auth.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              action: 'kid_login',
              username,
              pin_code
            })
          });
          const data = await resp.json();
          if (data.success) {
            SoundEffects.playFanfare();
            NextGradeSpeech.speak(`Welcome, ${data.student.name}!`, 'en');
            setTimeout(() => {
              window.location.reload();
            }, 600);
          } else {
            SoundEffects.playBoop();
            alert(data.error || 'Login failed.');
          }
        } catch (err) {
          console.error(err);
          alert('Server error.');
        }
      }

      document.getElementById('onboarding-form')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        SoundEffects.playPop();
        const name = document.getElementById('student-name-input').value.trim();
        const avatar = document.querySelector('input[name="avatar"]:checked')?.value || 'star_kid';

        if (!name) return;

        try {
          const resp = await fetch('api/auth.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              name,
              avatar
            })
          });
          const data = await resp.json();
          if (data.success) {
            SoundEffects.playChime();
            NextGradeSpeech.speak(`Welcome, ${name}! Let's have fun learning together!`, 'en');
            setTimeout(() => {
              window.location.reload();
            }, 600);
          }
        } catch (err) {
          console.error(err);
        }
      });
    </script>

  <?php else: ?>
    <!-- === 2. STUDENT LEARNING HUB (DASHBOARD) === -->
    <div class="max-w-6xl w-full mx-auto flex flex-col gap-6">

      <!-- Top Navigation Bar -->
      <header class="bg-white px-5 py-3.5 rounded-3xl shadow-sm border-2 border-slate-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <a href="index.php" class="flex items-center gap-2 text-2xl md:text-3xl font-black text-slate-800 tracking-tight">
            <span class="text-3xl md:text-4xl animate-bounce">🌟</span>
            <span>Next<span class="text-sky-500">Grade</span></span>
          </a>
          <span class="hidden sm:inline-block <?= $isYear6 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-sky-100 text-sky-700' ?> text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">
            <?= $isYear6 ? 'Brunei PSR Exam Hub 🇧🇳' : 'Early Learning Hub' ?>
          </span>
        </div>

        <div class="flex items-center gap-3">
          <!-- Student Badge & Switcher -->
          <div class="flex items-center gap-2 bg-slate-100 px-4 py-1.5 rounded-2xl border border-slate-200">
            <span class="text-2xl"><?= $avatars[$studentAvatar]['emoji'] ?? '⭐' ?></span>
            <div>
              <span class="font-extrabold text-slate-800 text-sm md:text-base block leading-none"><?= htmlspecialchars($studentName) ?></span>
              <span class="text-[10px] font-black <?= $isYear6 ? 'text-emerald-700' : ($isKG3 ? 'text-sky-600' : 'text-amber-700') ?>">
                <?= htmlspecialchars($studentGrade ?? 'Kindergarten 3 (KG3)') ?> <?= $isYear6 ? '🇧🇳 PSR' : ($isKG3 ? '⭐' : '(Non-KG3)') ?>
              </span>
            </div>
            <a
              href="index.php?reset=1"
              onclick="SoundEffects.playPop();"
              title="Switch Child"
              class="text-xs text-sky-600 hover:text-red-500 font-black ml-2 transition-colors bg-white px-2 py-1 rounded-lg border border-slate-200">
              Switch
            </a>
          </div>

          <!-- Parent Portal Button -->
          <a
            href="parent.php"
            onclick="SoundEffects.playPop();"
            class="btn-chunky btn-white text-xs md:text-sm py-2 px-3.5 rounded-2xl flex items-center gap-1.5"
            title="Parent Portal">
            <span>👨‍👩‍👧</span>
            <span class="hidden md:inline font-bold">Parents</span>
          </a>
        </div>
      </header>

      <!-- Welcome Hero & Mixed Quick Challenge -->
      <div class="bg-gradient-to-r <?= $isYear6 ? 'from-emerald-500 via-teal-600 to-indigo-700' : 'from-sky-400 via-blue-500 to-indigo-600' ?> rounded-[2.5rem] p-6 md:p-10 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="relative z-10 text-center md:text-left">
          <span class="bg-white/20 backdrop-blur-md text-white text-xs md:text-sm font-black px-4 py-1.5 rounded-full uppercase tracking-widest inline-block mb-3">
            <?= $isYear6 ? 'Brunei PSR Exam Prep 🇧🇳' : 'Hello Superstar! 👋' ?>
          </span>
          <h1 class="text-3xl md:text-5xl font-black mb-2 leading-tight">
            Welcome, <?= htmlspecialchars($studentName) ?>!
          </h1>
          <p class="text-sky-100 text-base md:text-xl font-bold max-w-xl">
            <?= $isYear6
              ? 'Prepare for your Brunei Primary School Assessment (PSR) with over 630 syllabus questions, 5-minute revision guides, and practice tests!'
              : 'Choose a subject below to start a 5-minute revision, practice 10 questions, or print handwriting worksheets!' ?>
          </p>
        </div>

        <!-- Quick Play Mixed 10 Questions -->
        <div class="relative z-10 shrink-0">
          <?php if ($hasActiveCurriculum): ?>
            <a
              href="quiz.php?mode=mixed"
              onclick="SoundEffects.playPop();"
              class="btn-chunky <?= $isYear6 ? 'btn-amber' : 'btn-amber' ?> text-lg md:text-xl py-4 px-6 rounded-2xl shadow-xl flex items-center gap-3">
              <span class="text-2xl md:text-3xl"><?= $isYear6 ? '🇧🇳' : '🎯' ?></span>
              <div class="text-left leading-tight">
                <span class="block text-xs uppercase tracking-wider text-amber-100 font-bold"><?= $isYear6 ? 'PSR Exam Challenge' : 'Smart Challenge' ?></span>
                <span class="font-black"><?= $isYear6 ? 'Mixed 10 PSR Questions!' : 'Mixed 10 Questions!' ?></span>
              </div>
            </a>
          <?php else: ?>
            <button
              type="button"
              onclick="SoundEffects.playBoop(); alert('Questions in NextGrade are currently prepared for Kindergarten 3 (KG3) and Year 6 (PSR Brunei). Questions for <?= htmlspecialchars(addslashes($studentGrade)) ?> are coming soon!');"
              class="bg-white/20 hover:bg-white/25 border-2 border-white/30 rounded-2xl p-4 text-center text-white cursor-pointer transition-all active:scale-95">
              <span class="text-2xl md:text-3xl block mb-1">🔒</span>
              <span class="text-xs font-black uppercase tracking-wider block">KG3 & PSR Only</span>
              <span class="text-[11px] opacity-85"><?= htmlspecialchars($studentGrade) ?> Content Coming Soon</span>
            </button>
          <?php endif; ?>
        </div>

        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
      </div>

      <?php if (!$hasActiveCurriculum): ?>
        <!-- Non-Supported Grade Curriculum Notice Banner -->
        <div class="bg-amber-50 border-3 border-amber-300 rounded-[2rem] p-5 md:p-6 shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3.5 text-center sm:text-left">
            <span class="text-4xl p-2.5 bg-amber-100 rounded-2xl shrink-0">🔒</span>
            <div>
              <div class="flex items-center gap-2 justify-center sm:justify-start">
                <span class="bg-amber-500 text-white text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider">Curriculum Notice</span>
                <h3 class="text-lg font-black text-amber-950">Questions are for KG3 and Year 6 (PSR Brunei)</h3>
              </div>
              <p class="text-xs font-bold text-amber-800 mt-1 leading-relaxed">
                Hello <strong><?= htmlspecialchars($studentName) ?></strong>! NextGrade currently features full question banks for <strong>Kindergarten 3 (KG3)</strong> and <strong>Year 6 (PSR Brunei)</strong>. Questions for your grade level (<strong><?= htmlspecialchars($studentGrade) ?></strong>) are in development and will be released soon!
              </p>
            </div>
          </div>
          <a
            href="index.php?reset=1"
            onclick="SoundEffects.playPop();"
            class="btn-chunky btn-primary text-xs py-2.5 px-4 rounded-xl font-black shrink-0 flex items-center gap-1.5 shadow-sm">
            <span>🔄</span> <span>Switch Student Profile</span>
          </a>
        </div>
      <?php endif; ?>

      <!-- Subjects Grid -->
      <div>
        <div class="flex items-center justify-between mb-4 px-2">
          <h2 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-2">
            <span>📚</span> <?= $isYear6 ? 'Brunei PSR Exam Subjects' : 'Choose a Subject' ?>
          </h2>
          <span class="text-sm font-bold text-slate-400">
            <?= $isYear6 ? '5 PSR SPN21 Core Subjects (Brunei)' : '5 Learning Subjects' ?>
          </span>
        </div>

        <div id="subjects-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="col-span-full text-center py-12 text-slate-400 font-bold">
            <div class="text-5xl mb-2 animate-spin">⏳</div>
            Loading subjects...
          </div>
        </div>
      </div>

    </div>

    <script>
      const isKG3 = <?= json_encode($isKG3) ?>;
      const isYear6 = <?= json_encode($isYear6) ?>;
      const hasActiveCurriculum = <?= json_encode($hasActiveCurriculum) ?>;

      async function loadSubjects() {
        try {
          const resp = await fetch('api/subjects.php');
          const data = await resp.json();
          const container = document.getElementById('subjects-container');

          if (!data.success || !data.subjects) {
            container.innerHTML = '<p class="text-red-500 font-bold col-span-full">Failed to load subjects.</p>';
            return;
          }

          container.innerHTML = data.subjects.map(s => `
          <a 
            href="topics.php?subject=${s.id}" 
            onclick="SoundEffects.playPop();"
            class="group bg-white rounded-[2rem] p-6 shadow-md border-3 border-slate-200 hover:border-sky-400 hover:shadow-xl transition-all flex flex-col justify-between min-h-[220px] relative overflow-hidden"
          >
            <div class="h-3 w-full bg-gradient-to-r ${s.theme_gradient} absolute top-0 left-0 right-0"></div>

            <div>
              <div class="flex items-center justify-between mb-3">
                <span class="text-5xl p-2 rounded-2xl bg-slate-50 border border-slate-100 group-hover:scale-110 transition-transform">
                  ${s.icon}
                </span>
                <span class="bg-slate-100 text-slate-600 font-black text-xs px-3 py-1.5 rounded-full uppercase tracking-wider">
                  ${s.topic_count} Topics
                </span>
              </div>
              <h3 class="text-2xl font-black text-slate-800 group-hover:text-sky-600 transition-colors mb-1">
                ${s.name}
              </h3>
              <p class="text-slate-500 font-bold text-sm line-clamp-2">
                ${s.description}
              </p>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-black text-slate-400">
              <span>${hasActiveCurriculum ? `${s.total_questions} Questions Ready` : '<span class="text-amber-500 font-extrabold">🔒 Coming Soon</span>'}</span>
              <span class="text-sky-500 group-hover:translate-x-1 transition-transform font-extrabold flex items-center gap-1">
                ${hasActiveCurriculum ? 'Explore Topics ➔' : 'View ➔'}
              </span>
            </div>
          </a>
        `).join('');

        } catch (err) {
          console.error(err);
        }
      }

      document.addEventListener('DOMContentLoaded', loadSubjects);
    </script>
  <?php endif; ?>

  <!-- Footer -->
  <footer class="text-center text-xs font-bold text-slate-400 mt-8 flex flex-col sm:flex-row items-center justify-center gap-2">
    <span>NextGrade • Designed with ❤️ for Children's Learning & Writing Skills</span>
    <span>•</span>
    <a href="admin/login.php" class="text-indigo-500 hover:text-indigo-700 font-extrabold flex items-center gap-1">
      <i class="fa-solid fa-shield-halved text-[10px]"></i> System Admin
    </a>
  </footer>

</body>

</html>