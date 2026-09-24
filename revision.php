<?php
// NextGrade - 5-Minute Topic Revision Section
require_once __DIR__ . '/db.php';
session_start();

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? 'Kawan Pintar';
$topicId = $_GET['topic'] ?? $_GET['topic_id'] ?? 'bm_kenderaan';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>5-Minute Revision - NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <script src="js/sounds.js"></script>
  <script src="js/speech.js"></script>
</head>
<body class="min-h-screen flex flex-col justify-between p-4 md:p-6 select-none bg-slate-50">

<div class="max-w-4xl w-full mx-auto flex flex-col gap-5">

  <!-- 1. Header with Live 5-Minute Countdown Timer (Requirement 1.11) -->
  <header class="bg-white px-6 py-4 rounded-3xl shadow-sm border-2 border-slate-200 flex flex-wrap items-center justify-between gap-4">
    <a 
      href="javascript:history.back()" 
      onclick="NextGradeSpeech.stop(); SoundEffects.playPop();"
      class="btn-chunky btn-white text-sm py-2 px-4 rounded-2xl flex items-center gap-1.5"
    >
      <span>⬅️</span>
      <span class="font-extrabold">Back</span>
    </a>

    <!-- 5-Minute Countdown Timer Widget -->
    <div class="flex items-center gap-3 bg-purple-50 border-2 border-purple-200 px-5 py-2 rounded-2xl">
      <div class="relative w-8 h-8 flex items-center justify-center">
        <span class="text-xl animate-pulse">⏳</span>
      </div>
      <div>
        <span class="block text-[10px] font-black uppercase tracking-wider text-purple-600">5-Minute Time Limit</span>
        <span id="countdown-timer" class="font-mono text-2xl font-black text-purple-900 leading-none">05:00</span>
      </div>
    </div>

    <!-- Direct Jump to Quiz Button -->
    <a 
      id="jump-to-quiz-btn"
      href="quiz.php?topic=<?= urlencode($topicId) ?>"
      onclick="NextGradeSpeech.stop(); SoundEffects.playPop();"
      class="btn-chunky btn-success text-sm py-2.5 px-5 rounded-2xl flex items-center gap-2 shadow-sm"
    >
      <span>🚀</span>
      <span class="font-black">Start Quiz Now</span>
    </a>
  </header>

  <!-- 2. Main Revision Card -->
  <main class="bg-white rounded-[2.5rem] p-6 md:p-10 shadow-xl border-3 border-slate-200 relative overflow-hidden flex flex-col justify-between min-h-[500px]">

    <div id="revision-header" class="mb-6 pb-4 border-b-2 border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
      <div>
        <span class="bg-purple-100 text-purple-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider inline-block mb-1">
          Quick Revision Module
        </span>
        <h1 id="revision-title" class="text-2xl md:text-3xl font-black text-slate-800">
          Loading Revision...
        </h1>
        <p id="revision-summary" class="text-slate-500 font-bold text-sm md:text-base mt-1">
          Please wait...
        </p>
      </div>

      <!-- Listen to Card Narration -->
      <button 
        id="btn-listen-card" 
        onclick="speakActiveCard()"
        class="btn-chunky btn-primary py-2.5 px-4 rounded-2xl text-sm flex items-center gap-2 shrink-0 shadow-sm"
      >
        <span class="text-xl">🔊</span>
        <span class="font-black">Listen to Card</span>
      </button>
    </div>

    <!-- Active Flashcard Display Area -->
    <div id="card-display-area" class="flex-1 flex flex-col items-center justify-center p-4 md:p-8 bg-gradient-to-br from-purple-50/50 to-sky-50/50 rounded-3xl border-2 border-purple-100 my-2 text-center transition-all">
      <div id="card-icon" class="text-7xl md:text-8xl mb-4 animate-float">
        💡
      </div>
      <h2 id="card-title" class="text-2xl md:text-3xl font-black text-slate-800 mb-3">
        Card Title
      </h2>
      <p id="card-text" class="text-lg md:text-xl font-bold text-slate-600 max-w-xl leading-relaxed">
        Key concepts will appear here to help the child understand before testing knowledge with questions.
      </p>
    </div>

    <!-- Flashcard Pagination & Navigator -->
    <div class="pt-6 border-t-2 border-slate-100 flex items-center justify-between mt-auto">
      <button 
        id="btn-prev-card" 
        onclick="prevCard()"
        class="btn-chunky btn-white py-3 px-5 rounded-2xl text-base font-extrabold flex items-center gap-1.5"
      >
        <span>⬅️</span>
        <span>Previous</span>
      </button>

      <!-- Card Dots Indicator -->
      <div id="card-dots" class="flex items-center gap-2">
        <!-- Dots rendered dynamically -->
      </div>

      <button 
        id="btn-next-card" 
        onclick="nextCard()"
        class="btn-chunky btn-purple py-3 px-5 rounded-2xl text-base font-extrabold flex items-center gap-1.5 shadow-sm"
      >
        <span>Next</span>
        <span>➔</span>
      </button>
    </div>

  </main>

  <!-- 3. Time's Up Modal -->
  <div id="timeout-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl border-4 border-purple-300 max-w-md w-full text-center">
      <div class="text-6xl mb-3">⏰</div>
      <h3 class="text-3xl font-black text-slate-800 mb-2">5 Minutes Complete!</h3>
      <p class="text-slate-500 font-bold text-base mb-6">
        Great job! Your 5-minute revision is complete. Now let's try 10 questions to test your smart skills!
      </p>
      <a 
        href="quiz.php?topic=<?= urlencode($topicId) ?>"
        onclick="SoundEffects.playFanfare();"
        class="btn-chunky btn-success w-full py-4 text-xl font-black rounded-2xl shadow-lg block"
      >
        Start Quiz Now 🚀
      </a>
    </div>
  </div>

</div>

<script>
  const topicId = "<?= htmlspecialchars($topicId) ?>";
  let cards = [];
  let currentCardIndex = 0;
  let remainingSeconds = 300; // 5 minutes limit (Requirement 1.11)
  let timerInterval = null;

  async function loadRevision() {
    startCountdown();

    try {
      const resp = await fetch(`api/revision.php?topic_id=${encodeURIComponent(topicId)}`);
      const data = await resp.json();

      if (!data.success || !data.revision) {
        document.getElementById('revision-title').textContent = 'Failed to load revision notes.';
        return;
      }

      const rev = data.revision;
      document.getElementById('revision-title').textContent = rev.title;
      document.getElementById('revision-summary').textContent = rev.summary;

      cards = rev.cards && rev.cards.length > 0 ? rev.cards : [
        { title: 'Core Concepts', text: rev.summary, icon: '💡' }
      ];

      renderCard();
      renderDots();

    } catch (err) {
      console.error(err);
    }
  }

  function startCountdown() {
    timerInterval = setInterval(() => {
      remainingSeconds--;
      if (remainingSeconds <= 0) {
        clearInterval(timerInterval);
        remainingSeconds = 0;
        SoundEffects.playChime();
        document.getElementById('timeout-modal').classList.remove('hidden');
      }

      const mins = Math.floor(remainingSeconds / 60);
      const secs = remainingSeconds % 60;
      document.getElementById('countdown-timer').textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }, 1000);
  }

  function renderCard() {
    const card = cards[currentCardIndex];
    if (!card) return;

    document.getElementById('card-title').textContent = card.title;
    document.getElementById('card-text').textContent = card.text;
    document.getElementById('card-icon').textContent = card.icon || '💡';

    // Update buttons
    document.getElementById('btn-prev-card').disabled = (currentCardIndex === 0);
    document.getElementById('btn-prev-card').classList.toggle('opacity-40', currentCardIndex === 0);

    const nextBtn = document.getElementById('btn-next-card');
    if (currentCardIndex === cards.length - 1) {
      nextBtn.innerHTML = `<span>Finish</span> <span>✅</span>`;
    } else {
      nextBtn.innerHTML = `<span>Next</span> <span>➔</span>`;
    }

    updateDots();

    // Auto read card aloud
    setTimeout(() => {
      speakActiveCard();
    }, 200);
  }

  function renderDots() {
    const container = document.getElementById('card-dots');
    container.innerHTML = cards.map((_, idx) => `
      <div class="w-3 h-3 rounded-full transition-all ${idx === 0 ? 'bg-purple-600 w-6' : 'bg-slate-200'}" id="dot-${idx}"></div>
    `).join('');
  }

  function updateDots() {
    cards.forEach((_, idx) => {
      const dot = document.getElementById(`dot-${idx}`);
      if (dot) {
        if (idx === currentCardIndex) {
          dot.className = 'h-3 rounded-full transition-all bg-purple-600 w-6';
        } else {
          dot.className = 'w-3 h-3 rounded-full transition-all bg-slate-200';
        }
      }
    });
  }

  function nextCard() {
    SoundEffects.playPop();
    NextGradeSpeech.stop();
    if (currentCardIndex < cards.length - 1) {
      currentCardIndex++;
      renderCard();
    } else {
      // Completed all cards -> prompt to start quiz
      SoundEffects.playChime();
      window.location.href = `quiz.php?topic=${encodeURIComponent(topicId)}`;
    }
  }

  function prevCard() {
    SoundEffects.playPop();
    NextGradeSpeech.stop();
    if (currentCardIndex > 0) {
      currentCardIndex--;
      renderCard();
    }
  }

  function speakActiveCard() {
    const card = cards[currentCardIndex];
    if (!card) return;
    const narration = `${card.title}. ${card.text}`;
    // Malay speech detection: ONLY for bm_ (Bahasa Melayu module)
    const isMalay = topicId.startsWith('bm_');
    NextGradeSpeech.speak(narration, isMalay ? 'ms' : 'en');
  }

  document.addEventListener('DOMContentLoaded', loadRevision);
</script>

</body>
</html>
