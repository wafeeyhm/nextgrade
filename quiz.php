<?php
// NextGrade - Interactive 10-Question Tablet Quiz Runner
require_once __DIR__ . '/db.php';
session_start();

$studentName = $_SESSION['student_name'] ?? $_COOKIE['student_name'] ?? 'Kawan Pintar';
$studentAvatar = $_SESSION['student_avatar'] ?? 'star_kid';

$topicId = $_GET['topic'] ?? $_GET['topic_id'] ?? null;
$subjectId = $_GET['subject'] ?? $_GET['subject_id'] ?? null;
$mode = $_GET['mode'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Interactive Quiz - NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
  <script src="js/sounds.js"></script>
  <script src="js/speech.js"></script>
</head>
<body class="min-h-screen flex flex-col justify-between p-3 md:p-6 select-none bg-slate-50">

<div class="max-w-4xl w-full mx-auto flex flex-col gap-4">

  <!-- 1. Top HUD Bar -->
  <header class="bg-white px-5 py-3 rounded-2xl shadow-sm border-2 border-slate-200 flex items-center justify-between gap-3">
    <!-- Back Button -->
    <a 
      href="javascript:history.back()" 
      onclick="NextGradeSpeech.stop(); SoundEffects.playPop();"
      class="btn-chunky btn-white text-xs md:text-sm py-2 px-3.5 rounded-xl flex items-center gap-1.5"
    >
      <span>⬅️</span>
      <span class="font-black">Back</span>
    </a>

    <!-- Question Tracker & Progress Bar -->
    <div class="flex-1 max-w-xs md:max-w-md flex flex-col items-center">
      <div class="flex items-center justify-between w-full text-xs font-black text-slate-500 mb-1">
        <span id="question-progress-label">Question 1 of 10</span>
        <span id="session-timer" class="font-mono text-sky-600">⏱️ 00:00</span>
      </div>
      <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden border border-slate-200">
        <div id="progress-bar-fill" class="bg-gradient-to-r from-sky-400 to-emerald-500 h-full w-0 transition-all duration-300"></div>
      </div>
    </div>

    <!-- Live Stars & Score -->
    <div class="bg-amber-50 border-2 border-amber-200 px-3 md:px-4 py-1.5 rounded-xl flex items-center gap-2">
      <span class="text-xl">⭐</span>
      <span id="live-score" class="text-xl md:text-2xl font-black text-amber-600">0</span>
    </div>
  </header>

  <!-- 2. Main Question Card -->
  <main id="quiz-card" class="bg-white rounded-[2rem] p-6 md:p-8 shadow-xl border-3 border-slate-200 relative overflow-hidden flex flex-col min-h-[500px] justify-between">

    <!-- Loading State -->
    <div id="quiz-loading" class="flex-1 flex flex-col items-center justify-center py-16 text-center">
      <div class="text-6xl mb-3 animate-spin">🌟</div>
      <h2 class="text-2xl font-black text-slate-700 mb-1">Preparing 10 Smart Questions...</h2>
      <p class="text-slate-400 font-bold text-sm">Get ready to show how smart you are!</p>
    </div>

    <!-- Question Content (Rendered dynamically) -->
    <div id="quiz-content" class="hidden flex-1 flex flex-col gap-6">

      <!-- Question Audio & Listen Button Header -->
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-3 border-b-2 border-slate-100">
        <div class="flex items-center gap-3">
          <!-- Big Click to Listen Button (Requirement 1.7) -->
          <button 
            id="btn-speak-question" 
            onclick="speakCurrentQuestion()"
            title="Click to listen to this question"
            class="btn-chunky btn-primary py-2 px-4 rounded-xl text-sm flex items-center gap-2 shadow-sm animate-pulse hover:animate-none"
          >
            <span class="text-xl">🔊</span>
            <span class="font-black">Listen</span>
          </button>

          <!-- Click Answers Hint Button (Requirement 1.6 & 1.8) -->
          <button 
            id="btn-toggle-hint" 
            onclick="toggleHint()"
            title="Click to see answer hint"
            class="btn-chunky btn-amber py-2 px-3.5 rounded-xl text-sm flex items-center gap-1.5 shadow-sm"
          >
            <span class="text-lg">💡</span>
            <span class="font-extrabold">Hint</span>
          </button>
        </div>

        <span id="question-type-badge" class="bg-sky-50 text-sky-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider">
          Multiple Choice
        </span>
      </div>

      <!-- Expandable Hint Box (Requirement 1.6 & 1.8) -->
      <div id="hint-box" class="hidden bg-amber-50 border-2 border-amber-300 rounded-2xl p-4 text-amber-900 flex items-center justify-between gap-4 transition-all">
        <div class="flex items-start gap-3">
          <span class="text-2xl">💡</span>
          <div>
            <h4 class="font-black text-sm uppercase tracking-wide text-amber-700">Answer Hint:</h4>
            <p id="hint-text-display" class="font-bold text-base text-amber-900">
              Think carefully!
            </p>
          </div>
        </div>
        <!-- Listen to Hint Button (Requirement 1.8) -->
        <button 
          onclick="speakCurrentHint()"
          title="Listen to audio hint"
          class="btn-chunky btn-white py-1.5 px-3 rounded-xl text-xs shrink-0 flex items-center gap-1 border-amber-300"
        >
          <span>🔊</span>
          <span class="font-bold">Listen</span>
        </button>
      </div>

      <!-- Reading Comprehension Passage Box (If applicable) -->
      <div id="passage-box" class="hidden bg-blue-50 border-2 border-blue-200 rounded-2xl p-4 md:p-6 text-slate-800">
        <div class="flex items-center gap-2 mb-2 text-sky-700 font-black text-sm uppercase tracking-wider">
          <span>📖</span> Read the Story Passage:
        </div>
        <p id="passage-text" class="text-base md:text-lg font-bold leading-relaxed text-slate-700 bg-white p-4 rounded-xl border border-blue-100 shadow-inner">
          Story passage...
        </p>
      </div>

      <!-- Question Text Prompt -->
      <div class="text-center sm:text-left">
        <h2 id="question-prompt" class="text-2xl md:text-3xl font-black text-slate-800 leading-snug">
          Question prompt...
        </h2>
      </div>

      <!-- Media Illustration (Image / Icons) -->
      <div id="question-media-wrapper" class="flex justify-center my-1">
        <div id="question-media-card" class="w-full max-w-md min-h-[130px] max-h-64 bg-slate-50 border-2 border-slate-200 rounded-2xl overflow-hidden flex items-center justify-center p-3 shadow-inner">
          <img id="question-image" src="" alt="Question Illustration" class="max-h-52 w-auto object-contain transition-transform hover:scale-105 duration-200">
          <div id="question-icons-display" class="hidden flex flex-wrap justify-center items-center gap-3.5 p-2 select-none w-full"></div>
        </div>
      </div>

      <!-- Interactive Answer Area -->
      <div id="options-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 my-2">
        <!-- Option buttons dynamically populated -->
      </div>

    </div>

    <!-- 3. Bottom Feedback & Next Button -->
    <div id="quiz-footer" class="pt-4 border-t-2 border-slate-100 flex items-center justify-between mt-auto">
      <div id="feedback-badge" class="font-black text-lg md:text-xl flex items-center gap-2">
        <span class="text-slate-400 text-sm font-bold">Tap an answer above 👆</span>
      </div>

      <button 
        id="btn-next-question" 
        onclick="nextQuestion()"
        disabled
        class="btn-chunky btn-primary py-3 px-6 rounded-2xl text-lg font-black opacity-50 cursor-not-allowed flex items-center gap-2 shadow-md"
      >
        <span>Next</span>
        <span>➔</span>
      </button>
    </div>

  </main>

  <!-- 4. Celebration Modal / End Session Result (Requirement 1.13) -->
  <div id="result-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] p-6 md:p-10 shadow-2xl border-4 border-emerald-300 max-w-lg w-full text-center relative overflow-hidden animate-popSuccess">
      
      <!-- Stars Glow -->
      <div id="result-stars" class="text-6xl md:text-7xl mb-2 flex justify-center gap-2 animate-bounce">
        ⭐ ⭐ ⭐
      </div>

      <h2 id="result-badge-title" class="text-3xl md:text-4xl font-black text-slate-800 mb-2">
        Superstar! 🏆
      </h2>
      <p id="result-message" class="text-slate-500 font-bold text-base md:text-lg mb-6">
        Great effort! You completed all 10 questions!
      </p>

      <!-- Score Card Summary -->
      <div class="bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-5 mb-6 grid grid-cols-3 gap-2">
        <div>
          <span class="block text-xs font-bold text-emerald-600 uppercase">Score</span>
          <span id="final-score" class="text-3xl md:text-4xl font-black text-emerald-700">10 / 10</span>
        </div>
        <div>
          <span class="block text-xs font-bold text-emerald-600 uppercase">Accuracy</span>
          <span id="final-percentage" class="text-3xl md:text-4xl font-black text-emerald-700">100%</span>
        </div>
        <div>
          <span class="block text-xs font-bold text-emerald-600 uppercase">Time</span>
          <span id="final-time" class="text-3xl md:text-4xl font-black text-emerald-700">01:45</span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3">
        <a 
          href="javascript:window.location.reload()" 
          onclick="SoundEffects.playPop();"
          class="btn-chunky btn-amber flex-1 py-3.5 rounded-xl text-base font-black shadow-md flex items-center justify-center gap-1.5"
        >
          <span>🔄</span>
          <span>Play Again</span>
        </a>
        <a 
          href="parent.php" 
          onclick="SoundEffects.playPop();"
          class="btn-chunky btn-purple flex-1 py-3.5 rounded-xl text-base font-black shadow-md flex items-center justify-center gap-1.5"
        >
          <span>👨‍👩‍👧</span>
          <span>Parent Portal</span>
        </a>
        <a 
          href="index.php" 
          onclick="SoundEffects.playPop();"
          class="btn-chunky btn-white flex-1 py-3.5 rounded-xl text-base font-black flex items-center justify-center gap-1.5"
        >
          <span>🏠</span>
          <span>Home</span>
        </a>
      </div>

    </div>
  </div>

</div>

<script>
  // State variables
  const studentName = "<?= htmlspecialchars($studentName) ?>";
  const topicId = "<?= htmlspecialchars($topicId ?? '') ?>";
  const subjectId = "<?= htmlspecialchars($subjectId ?? '') ?>";
  const quizMode = "<?= htmlspecialchars($mode ?? '') ?>";

  let questions = [];
  let currentIndex = 0;
  let score = 0;
  let answersRecord = [];
  let currentSelectedAnswer = null;
  let currentUsedHint = false;
  let timerSeconds = 0;
  let timerInterval = null;

  // Initialize Quiz
  async function initQuiz() {
    startTimer();
    let url = 'api/quiz.php?';
    if (topicId) {
      url += `topic_id=${encodeURIComponent(topicId)}`;
    } else if (subjectId) {
      url += `subject_id=${encodeURIComponent(subjectId)}`;
    } else {
      url += `mode=mixed`;
    }

    try {
      const resp = await fetch(url);
      const data = await resp.json();

      if (!data.success || !data.questions || data.questions.length === 0) {
        document.getElementById('quiz-loading').innerHTML = `
          <div class="text-4xl mb-3">⚠️</div>
          <h3 class="text-xl font-bold text-red-500">Questions could not be loaded.</h3>
          <a href="index.php" class="btn-chunky btn-primary mt-4 py-2 px-4 rounded-xl text-sm">Back to Home</a>
        `;
        return;
      }

      questions = data.questions;
      document.getElementById('quiz-loading').classList.add('hidden');
      document.getElementById('quiz-content').classList.remove('hidden');

      renderCurrentQuestion();

    } catch (err) {
      console.error(err);
      alert('Error loading quiz session.');
    }
  }

  function startTimer() {
    timerInterval = setInterval(() => {
      timerSeconds++;
      const mins = Math.floor(timerSeconds / 60);
      const secs = timerSeconds % 60;
      document.getElementById('session-timer').textContent = `⏱️ ${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }, 1000);
  }

  function renderCurrentQuestion() {
    currentSelectedAnswer = null;
    currentUsedHint = false;

    const q = questions[currentIndex];
    const total = questions.length;

    // Reset Hint box
    const hintBox = document.getElementById('hint-box');
    hintBox.classList.add('hidden');
    document.getElementById('hint-text-display').textContent = q.hint_text;

    // Progress updates
    document.getElementById('question-progress-label').textContent = `Question ${currentIndex + 1} of ${total}`;
    const fillPercent = ((currentIndex) / total) * 100;
    document.getElementById('progress-bar-fill').style.width = `${fillPercent}%`;

    // Prompt
    document.getElementById('question-prompt').textContent = q.question_text;

    // Passage (Troy's lollipop / Andy at the zoo)
    const passageBox = document.getElementById('passage-box');
    if (q.passage && q.passage.trim() !== '') {
      passageBox.classList.remove('hidden');
      document.getElementById('passage-text').textContent = q.passage;
    } else {
      passageBox.classList.add('hidden');
    }

    // Media & Icons Display
    const mediaWrapper = document.getElementById('question-media-wrapper');
    const imgElem = document.getElementById('question-image');
    const iconsElem = document.getElementById('question-icons-display');

    const countIcons = q.meta_data?.count_icons;
    if (countIcons) {
      mediaWrapper.classList.remove('hidden');
      imgElem.classList.add('hidden');
      iconsElem.classList.remove('hidden');
      const iconList = countIcons.trim().split(/\s+/);
      iconsElem.innerHTML = iconList.map((icon, idx) => `
        <span onclick="this.classList.toggle('scale-125'); this.classList.toggle('ring-4'); this.classList.toggle('ring-emerald-400'); this.classList.toggle('bg-emerald-50'); SoundEffects.playPop();" 
              class="w-14 h-14 md:w-16 md:h-16 text-3xl md:text-4xl rounded-2xl bg-white border-2 border-slate-200 shadow-sm flex items-center justify-center transform active:scale-95 transition-all duration-150 cursor-pointer select-none hover:shadow-md hover:border-amber-300" 
              title="Tap to count: ${idx + 1}">
          ${icon}
        </span>
      `).join('');
    } else if (q.image_url) {
      mediaWrapper.classList.remove('hidden');
      imgElem.classList.remove('hidden');
      iconsElem.classList.add('hidden');
      imgElem.src = q.image_url;
      imgElem.alt = q.question_text;
    } else {
      mediaWrapper.classList.add('hidden');
      imgElem.classList.add('hidden');
      iconsElem.classList.add('hidden');
    }

    // Reset Next Button & Feedback
    const nextBtn = document.getElementById('btn-next-question');
    nextBtn.disabled = true;
    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');

    document.getElementById('feedback-badge').innerHTML = `
      <span class="text-slate-400 text-sm font-bold">Choose the correct answer above 👆</span>
    `;

    // Render Options
    const optContainer = document.getElementById('options-container');
    optContainer.innerHTML = '';

    if (q.question_type === 'ordering') {
      // Interactive Sequence Tap to Order
      renderOrderingOptions(q, optContainer);
    } else {
      // Standard Multiple Choice / Syllable / Comprehension
      q.options.forEach((optText, optIdx) => {
        const btn = document.createElement('div');
        btn.className = 'option-card';
        btn.innerHTML = `
          <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center font-black text-base shrink-0">
            ${String.fromCharCode(65 + optIdx)}
          </span>
          <span class="flex-1">${optText}</span>
        `;
        btn.onclick = () => selectOption(btn, optText, q.correct_answer);
        optContainer.appendChild(btn);
      });
    }

    // Auto-read question gently on child tablet
    setTimeout(() => {
      speakCurrentQuestion();
    }, 300);
  }

  function selectOption(btnElem, selectedText, correctAnswer) {
    if (currentSelectedAnswer !== null) return; // Prevent multiple clicks on same question

    currentSelectedAnswer = selectedText;
    const isCorrect = (String(selectedText).trim().toLowerCase() === String(correctAnswer).trim().toLowerCase());

    const allCards = document.querySelectorAll('.option-card');
    allCards.forEach(c => c.style.pointerEvents = 'none');

    const feedbackBadge = document.getElementById('feedback-badge');
    const nextBtn = document.getElementById('btn-next-question');

    if (isCorrect) {
      btnElem.classList.add('selected-correct');
      SoundEffects.playChime();
      score++;
      document.getElementById('live-score').textContent = score;
      feedbackBadge.innerHTML = `<span class="text-emerald-600 flex items-center gap-1">🎉 Awesome! Correct!</span>`;
      confetti({ particleCount: 30, spread: 60, origin: { y: 0.8 } });
    } else {
      btnElem.classList.add('selected-wrong');
      SoundEffects.playBoop();
      feedbackBadge.innerHTML = `<span class="text-rose-600 flex items-center gap-1">❌ Correct Answer: <strong>${correctAnswer}</strong></span>`;
      
      // Highlight the correct card
      allCards.forEach(c => {
        if (c.textContent.includes(correctAnswer)) {
          c.classList.add('selected-correct');
        }
      });
    }

    // Record answer
    answersRecord.push({
      question_id: questions[currentIndex].id,
      student_answer: selectedText,
      is_correct: isCorrect,
      used_hint: currentUsedHint
    });

    // Enable Next Button
    nextBtn.disabled = false;
    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');

    if (currentIndex === questions.length - 1) {
      nextBtn.innerHTML = `<span>Finish & Save</span> <span>🏆</span>`;
    }
  }

  function renderOrderingOptions(q, container) {
    // Ordering handler: allow child to tap items into a sequence box
    const items = [...q.options];
    let selectedSequence = [];
    const targetLength = items.length;

    container.className = 'flex flex-col gap-4 w-full';
    container.innerHTML = `
      <div class="bg-sky-50 border-2 border-sky-200 rounded-2xl p-4 text-center">
        <span class="text-xs font-black text-sky-600 uppercase tracking-wider block mb-2">Your Answer Sequence:</span>
        <div id="sequence-display" class="flex flex-wrap items-center justify-center gap-2 min-h-[50px] p-2 bg-white rounded-xl border border-sky-100">
          <span class="text-slate-300 text-sm font-bold">Tap the cards below in order...</span>
        </div>
      </div>
      <div id="ordering-buttons" class="grid grid-cols-2 sm:grid-cols-4 gap-2.5"></div>
      <button id="btn-reset-order" class="btn-chunky btn-white text-xs py-2 px-3 rounded-xl mx-auto flex items-center gap-1">
        <span>🔄</span> <span>Reset Order</span>
      </button>
    `;

    const buttonsWrapper = container.querySelector('#ordering-buttons');
    const seqDisplay = container.querySelector('#sequence-display');
    const resetBtn = container.querySelector('#btn-reset-order');

    function updateSeq() {
      if (selectedSequence.length === 0) {
        seqDisplay.innerHTML = `<span class="text-slate-300 text-sm font-bold">Tap the cards below in order...</span>`;
      } else {
        seqDisplay.innerHTML = selectedSequence.map((item, i) => `
          <span class="bg-sky-500 text-white font-extrabold px-3 py-1.5 rounded-xl text-sm shadow-sm flex items-center gap-1.5">
            <span class="w-5 h-5 rounded-full bg-sky-700 text-xs flex items-center justify-center">${i + 1}</span>
            ${item}
          </span>
        `).join('');
      }

      if (selectedSequence.length === targetLength && currentSelectedAnswer === null) {
        checkOrderingAnswer(q, selectedSequence);
      }
    }

    items.forEach(text => {
      const b = document.createElement('button');
      b.className = 'btn-chunky btn-white text-base py-3 px-3 rounded-xl font-extrabold border-2';
      b.textContent = text;
      b.onclick = () => {
        if (!selectedSequence.includes(text)) {
          SoundEffects.playPop();
          selectedSequence.push(text);
          b.disabled = true;
          b.classList.add('opacity-40');
          updateSeq();
        }
      };
      buttonsWrapper.appendChild(b);
    });

    resetBtn.onclick = () => {
      if (currentSelectedAnswer !== null) return;
      SoundEffects.playPop();
      selectedSequence = [];
      buttonsWrapper.querySelectorAll('button').forEach(b => {
        b.disabled = false;
        b.classList.remove('opacity-40');
      });
      updateSeq();
    };
  }

  function checkOrderingAnswer(q, studentSeq) {
    currentSelectedAnswer = studentSeq;
    const correctSeq = Array.isArray(q.correct_answer) ? q.correct_answer : JSON.parse(q.correct_answer);
    const isCorrect = JSON.stringify(studentSeq) === JSON.stringify(correctSeq);

    const feedbackBadge = document.getElementById('feedback-badge');
    const nextBtn = document.getElementById('btn-next-question');

    if (isCorrect) {
      SoundEffects.playChime();
      score++;
      document.getElementById('live-score').textContent = score;
      feedbackBadge.innerHTML = `<span class="text-emerald-600">🎉 Perfect Order! Super smart!</span>`;
      confetti({ particleCount: 30, spread: 60, origin: { y: 0.8 } });
    } else {
      SoundEffects.playBoop();
      feedbackBadge.innerHTML = `<span class="text-rose-600">❌ Correct Sequence: ${correctSeq.join(' ➔ ')}</span>`;
    }

    answersRecord.push({
      question_id: q.id,
      student_answer: studentSeq,
      is_correct: isCorrect,
      used_hint: currentUsedHint
    });

    nextBtn.disabled = false;
    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');

    if (currentIndex === questions.length - 1) {
      nextBtn.innerHTML = `<span>Finish & Save</span> <span>🏆</span>`;
    }
  }

  function toggleHint() {
    SoundEffects.playPop();
    const box = document.getElementById('hint-box');
    box.classList.toggle('hidden');
    currentUsedHint = true;
    if (!box.classList.contains('hidden')) {
      speakCurrentHint();
    }
  }

  function speakCurrentQuestion() {
    const q = questions[currentIndex];
    if (!q) return;
    const textToSpeak = q.question_audio || q.question_text;
    const lang = q.lang || (q.subject_id === 'bahasa_melayu' ? 'ms' : 'en');
    
    const speakBtn = document.getElementById('btn-speak-question');
    speakBtn.classList.add('bg-sky-400');
    NextGradeSpeech.speak(textToSpeak, lang, null, () => {
      speakBtn.classList.remove('bg-sky-400');
    });
  }

  function speakCurrentHint() {
    const q = questions[currentIndex];
    if (!q) return;
    const hintText = q.hint_audio || q.hint_text;
    const lang = q.lang || (q.subject_id === 'bahasa_melayu' ? 'ms' : 'en');
    NextGradeSpeech.speak(hintText, lang);
  }

  function nextQuestion() {
    SoundEffects.playPop();
    NextGradeSpeech.stop();

    if (currentIndex < questions.length - 1) {
      currentIndex++;
      renderCurrentQuestion();
    } else {
      endQuizSession();
    }
  }

  async function endQuizSession() {
    clearInterval(timerInterval);
    SoundEffects.playFanfare();
    confetti({ particleCount: 100, spread: 80, origin: { y: 0.6 } });

    // Submit results to API (Requirement 1.13)
    const payload = {
      student_name: studentName,
      subject_id: subjectId || (questions[0] ? questions[0].subject_id : null),
      topic_id: topicId || null,
      total_questions: questions.length,
      time_spent_seconds: timerSeconds,
      answers: answersRecord
    };

    try {
      const resp = await fetch('api/quiz.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const result = await resp.json();

      // Populate Result Modal
      document.getElementById('final-score').textContent = `${result.score} / ${result.total}`;
      document.getElementById('final-percentage').textContent = `${result.percentage}%`;
      const mins = Math.floor(timerSeconds / 60);
      const secs = timerSeconds % 60;
      document.getElementById('final-time').textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
      
      document.getElementById('result-badge-title').textContent = result.badge;
      document.getElementById('result-message').textContent = result.message;

      // Stars display
      let starStr = '⭐';
      if (result.stars === 3) starStr = '⭐ ⭐ ⭐';
      else if (result.stars === 2) starStr = '⭐ ⭐';
      document.getElementById('result-stars').textContent = starStr;

      document.getElementById('result-modal').classList.remove('hidden');

    } catch (err) {
      console.error(err);
      alert('Session complete! Your score: ' + score + '/' + questions.length);
      window.location.href = 'index.php';
    }
  }

  document.addEventListener('DOMContentLoaded', initQuiz);
</script>

</body>
</html>
