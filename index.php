<?php
session_start();

// Handle name reset
if (isset($_GET['reset'])) {
    unset($_SESSION['student']);
    setcookie('student', '', time() - 3600, '/');
    header("Location: index.php");
    exit;
}

// Handle name submission
if (!empty($_POST['student'])) {
    $name = trim($_POST['student']);
    $_SESSION['student'] = $name;
    setcookie('student', $name, time() + (86400 * 365), '/');
    header("Location: index.php");
    exit;
}

$student = $_SESSION['student'] ?? $_COOKIE['student'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-6 select-none font-sans">

<?php if (empty($student)): ?>
  <!-- === 1. ONBOARDING GATE (ENGLISH) === -->
  <div class="bg-white p-8 md:p-14 rounded-[3rem] shadow-xl border-4 border-slate-200 text-center max-w-xl w-full">
    <div class="text-8xl mb-4">👋</div>
    <h1 class="text-4xl md:text-5xl font-black text-slate-800 mb-2 tracking-tight">Hi there!</h1>
    <p class="text-xl md:text-2xl font-bold text-slate-400 mb-8">What is your name?</p>

    <form method="POST" action="index.php" class="flex flex-col gap-6 items-center w-full">
      <input 
        type="text" 
        name="student" 
        required 
        placeholder="Type your name here..."
        class="w-full text-3xl md:text-4xl font-black text-center text-sky-600 bg-slate-50 border-4 border-slate-200 rounded-2xl p-5 focus:border-sky-400 focus:outline-none transition-all placeholder:text-slate-300"
        autocomplete="off"
        autofocus
      />
      <button 
        type="submit"
        class="w-full bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white font-black text-2xl md:text-3xl py-5 rounded-2xl shadow-lg border-b-[6px] border-emerald-700 active:border-b-0 active:translate-y-1 transition-all cursor-pointer"
      >
        Let's Learn! 🚀
      </button>
    </form>
    <div class="mt-8 text-xs font-bold text-slate-300 uppercase tracking-widest">NextGrade • PHP Native</div>
  </div>

<?php else: ?>
  <!-- === 2. MAIN DASHBOARD (ENGLISH) === -->
  <div class="w-full max-w-4xl flex flex-col items-center bg-white p-10 md:p-14 rounded-[3rem] shadow-xl border-4 border-slate-200 text-center relative">
    <a 
      href="index.php?reset=1"
      class="absolute top-6 right-6 text-slate-400 hover:text-sky-600 font-bold text-sm md:text-base bg-slate-100 hover:bg-sky-50 px-4 py-2 rounded-full transition-all"
    >
      Change Student
    </a>

    <div class="text-8xl mb-3">🌟</div>
    <h1 class="text-4xl md:text-6xl font-black text-slate-800 mb-3 tracking-tight">
      Welcome, <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-blue-600"><?= htmlspecialchars($student) ?></span>!
    </h1>
    <p class="text-xl md:text-2xl font-bold text-slate-400 mb-10">
      Pick a subject below to start your learning journey!
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-2xl">
      <a href="malay_topics.php" class="w-full block">
        <div class="bg-sky-50 hover:bg-sky-100 p-8 rounded-[2rem] border-4 border-sky-200 border-b-[10px] hover:border-sky-300 active:border-b-4 active:translate-y-1 transition-all flex flex-col items-center gap-3 cursor-pointer group">
          <div class="text-7xl group-hover:scale-110 transition-transform">📚</div>
          <h2 class="text-3xl font-black text-sky-800">Bahasa Melayu</h2>
          <span class="bg-sky-200 text-sky-700 font-bold px-5 py-1 rounded-full text-sm uppercase tracking-wider">KG3 Syllabus</span>
        </div>
      </a>

      <div class="bg-slate-50 p-8 rounded-[2rem] border-4 border-slate-200 border-b-[10px] flex flex-col items-center gap-3 opacity-60 cursor-not-allowed">
        <div className="text-7xl">🔢</div>
        <h2 class="text-3xl font-black text-slate-500">Mathematics</h2>
        <span class="bg-slate-200 text-slate-400 font-bold px-5 py-1 rounded-full text-sm uppercase tracking-wider">Coming Soon</span>
      </div>
    </div>
  </div>
<?php endif; ?>

</body>
</html>