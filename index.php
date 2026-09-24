<?php
// NextGrade - Main Dashboard & Child Entry Gate
require_once __DIR__ . '/db.php';
session_start();

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? '';
$studentAvatar = $_SESSION['student_avatar'] ?? 'star_kid';

// Handle student reset
if (isset($_GET['reset'])) {
    unset($_SESSION['student_name'], $_SESSION['student_id'], $_SESSION['student_avatar']);
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>NextGrade - Interactive Learning for Primary & Kindergarten</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <script src="js/sounds.js"></script>
  <script src="js/speech.js"></script>
</head>
<body class="min-h-screen flex flex-col justify-between p-4 md:p-8 select-none">

<?php if (empty($studentName)): ?>
  <!-- === 1. ONBOARDING GATE (CHILD-FRIENDLY ENTRY) === -->
  <div class="flex-1 flex items-center justify-center">
    <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl border-4 border-sky-200 text-center max-w-xl w-full relative overflow-hidden">
      <!-- Decorative Bubbles -->
      <div class="absolute -top-10 -right-10 w-32 h-32 bg-sky-100 rounded-full blur-xl opacity-60"></div>
      <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-amber-100 rounded-full blur-xl opacity-60"></div>

      <div class="text-7xl md:text-8xl mb-3 animate-float">🌟</div>
      <h1 class="text-3xl md:text-5xl font-black text-slate-800 mb-2 tracking-tight">
        Next<span class="text-sky-500">Grade</span>
      </h1>
      <p class="text-lg md:text-xl font-bold text-slate-400 mb-8">
        Hello superstar! What is your name?
      </p>

      <form id="onboarding-form" class="flex flex-col gap-6 items-center w-full">
        <input 
          type="text" 
          id="student-name-input"
          name="name" 
          required 
          placeholder="Type your name here..."
          class="w-full text-2xl md:text-3xl font-extrabold text-center text-sky-600 bg-sky-50/50 border-4 border-sky-200 rounded-2xl p-4 md:p-5 focus:border-sky-400 focus:bg-white focus:outline-none transition-all placeholder:text-slate-300"
          autocomplete="off"
          autofocus
        />

        <!-- Avatar Selection -->
        <div class="w-full">
          <label class="block text-sm font-bold text-slate-400 mb-2 uppercase tracking-wider">Choose your avatar:</label>
          <div class="grid grid-cols-6 gap-2">
            <?php foreach ($avatars as $key => $av): ?>
              <label class="cursor-pointer">
                <input type="radio" name="avatar" value="<?= $key ?>" class="peer sr-only" <?= $key === 'star_kid' ? 'checked' : '' ?>>
                <div class="text-3xl md:text-4xl p-2.5 rounded-2xl border-2 border-slate-200 bg-slate-50 peer-checked:border-sky-500 peer-checked:bg-sky-100 peer-checked:scale-110 transition-all flex items-center justify-center">
                  <?= $av['emoji'] ?>
                </div>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <button 
          type="submit"
          class="btn-chunky btn-success w-full text-2xl md:text-3xl py-4 md:py-5 rounded-2xl shadow-lg mt-2"
        >
          Let's Learn! 🚀
        </button>
      </form>

      <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-400">
        <span>Tablet & iPad Ready 📱</span>
        <a href="parent.php" class="text-sky-600 hover:underline">Parent Portal 👨‍👩‍👧</a>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('onboarding-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      SoundEffects.playPop();
      const name = document.getElementById('student-name-input').value.trim();
      const avatar = document.querySelector('input[name="avatar"]:checked')?.value || 'star_kid';

      if (!name) return;

      try {
        const resp = await fetch('api/auth.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, avatar })
        });
        const data = await resp.json();
        if (data.success) {
          SoundEffects.playChime();
          NextGradeSpeech.speak(`Welcome, ${name}! Let's have fun learning together!`, 'en');
          setTimeout(() => { window.location.reload(); }, 600);
        }
      } catch (err) {
        console.error(err);
        alert('Could not start session. Please try again!');
      }
    });
  </script>

<?php else: ?>
  <!-- === 2. STUDENT LEARNING HUB (DASHBOARD) === -->
  <div class="max-w-6xl w-full mx-auto flex flex-col gap-6">

    <!-- Top Navigation Bar -->
    <header class="bg-white px-6 py-4 rounded-3xl shadow-sm border-2 border-slate-200 flex flex-wrap items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <a href="index.php" class="flex items-center gap-2 text-2xl md:text-3xl font-black text-slate-800 tracking-tight">
          <span class="text-3xl md:text-4xl animate-bounce">🌟</span>
          <span>Next<span class="text-sky-500">Grade</span></span>
        </a>
        <span class="hidden sm:inline-block bg-sky-100 text-sky-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">
          Early Learning Hub
        </span>
      </div>

      <div class="flex items-center gap-3">
        <!-- Student Badge & Switcher -->
        <div class="flex items-center gap-2 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
          <span class="text-2xl"><?= $avatars[$studentAvatar]['emoji'] ?? '⭐' ?></span>
          <span class="font-extrabold text-slate-700 text-base md:text-lg"><?= htmlspecialchars($studentName) ?></span>
          <a href="index.php?reset=1" title="Change Student" class="text-xs text-slate-400 hover:text-red-500 font-bold ml-1 transition-colors">
            (Switch)
          </a>
        </div>

        <!-- Parent Portal Button -->
        <a 
          href="parent.php" 
          class="btn-chunky btn-white text-sm md:text-base py-2 px-4 rounded-2xl flex items-center gap-2"
        >
          <span>👨‍👩‍👧</span>
          <span class="hidden md:inline font-bold">Parents</span>
        </a>
      </div>
    </header>

    <!-- Welcome Hero & Mixed Quick Challenge -->
    <div class="bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-600 rounded-[2.5rem] p-6 md:p-10 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
      <div class="relative z-10 text-center md:text-left">
        <span class="bg-white/20 backdrop-blur-md text-white text-xs md:text-sm font-black px-4 py-1.5 rounded-full uppercase tracking-widest inline-block mb-3">
          Hello Superstar! 👋
        </span>
        <h1 class="text-3xl md:text-5xl font-black mb-2 leading-tight">
          Welcome, <?= htmlspecialchars($studentName) ?>!
        </h1>
        <p class="text-sky-100 text-base md:text-xl font-bold max-w-xl">
          Choose a subject below to start a 5-minute revision, practice 10 questions, or print handwriting worksheets!
        </p>
      </div>

      <!-- Quick Play Mixed 10 Questions -->
      <div class="relative z-10 shrink-0">
        <a 
          href="quiz.php?mode=mixed" 
          onclick="SoundEffects.playPop();"
          class="btn-chunky btn-amber text-lg md:text-xl py-4 px-6 rounded-2xl shadow-xl flex items-center gap-3"
        >
          <span class="text-2xl md:text-3xl">🎯</span>
          <div class="text-left leading-tight">
            <span class="block text-xs uppercase tracking-wider text-amber-100 font-bold">Smart Challenge</span>
            <span class="font-black">Mixed 10 Questions!</span>
          </div>
        </a>
      </div>

      <!-- Playful Background Circles -->
      <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Subjects Grid (Consumes api/subjects.php) -->
    <div>
      <div class="flex items-center justify-between mb-4 px-2">
        <h2 class="text-2xl md:text-3xl font-black text-slate-800 flex items-center gap-2">
          <span>📚</span> Choose a Subject
        </h2>
        <span class="text-sm font-bold text-slate-400">5 Learning Subjects</span>
      </div>

      <div id="subjects-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Subjects dynamically rendered via API -->
        <div class="col-span-full text-center py-12 text-slate-400 font-bold">
          <div class="text-5xl mb-2 animate-spin">⏳</div>
          Loading subjects...
        </div>
      </div>
    </div>

  </div>

  <script>
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
            <!-- Gradient Top Stripe -->
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
              <span>${s.total_questions} Questions Ready</span>
              <span class="text-sky-500 group-hover:translate-x-1 transition-transform font-extrabold flex items-center gap-1">
                Explore Topics ➔
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
<footer class="text-center text-xs font-bold text-slate-400 mt-8">
  NextGrade • Designed with ❤️ for Children's Learning & Writing Skills • 3-Tier Architecture
</footer>

</body>
</html>