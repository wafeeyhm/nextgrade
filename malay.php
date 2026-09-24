<?php
session_start();
require_once 'db.php';

$student = $_SESSION['student'] ?? $_COOKIE['student'] ?? 'Kawan';
$category = $_GET['category'] ?? 'semua';

// Randomize and pull 10 questions per quiz session
if ($category !== 'semua') {
    $stmt = $pdo->prepare("SELECT * FROM MalayQuestion WHERE category = :cat ORDER BY RAND() LIMIT 10");
    $stmt->execute(['cat' => $category]);
} else {
    $stmt = $pdo->query("SELECT * FROM MalayQuestion ORDER BY RAND() LIMIT 10");
}

$questions = $stmt->fetchAll();

// Normalize image URLs using BASE_URL
foreach ($questions as &$q) {
    if (!empty($q['imageUrl'])) {
        $cleanPath = ltrim($q['imageUrl'], '/');
        if (str_starts_with($cleanPath, 'nextgrade/')) {
            $cleanPath = substr($cleanPath, strlen('nextgrade/'));
        }
        $q['imageUrl'] = BASE_URL . $cleanPath;
    }
}
unset($q);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bahasa Melayu - NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center p-6 select-none font-sans">

  <!-- Top Navigation -->
  <div class="w-full max-w-5xl flex justify-between items-center mb-6">
    <a href="malay_topics.php" class="bg-white px-6 py-3 rounded-2xl border-2 border-slate-200 font-bold text-slate-600 hover:bg-slate-100 transition-all flex items-center gap-2 shadow-sm">
        ⬅️ Topik
    </a>
    
    <!-- Active Voice Engine Diagnostic Indicator -->
    <div id="voice-indicator" class="text-xs bg-slate-200 text-slate-600 px-3 py-1.5 rounded-xl font-mono">
      Mengesan suara...
    </div>

    <div class="bg-white border-4 border-slate-200 rounded-2xl px-6 py-2 flex items-center gap-4 shadow-sm">
      <span class="text-xl font-bold text-slate-400 uppercase tracking-widest">Markah</span>
      <span id="score-display" class="text-4xl font-black text-sky-500">0</span>
    </div>
  </div>

  <!-- Learning Canvas Card -->
  <div id="quiz-container" class="w-full max-w-5xl bg-white rounded-[2rem] shadow-xl border-4 border-slate-200 flex flex-col items-center justify-start p-8 pb-16 relative">
    
    <!-- Image / Visual Block -->
    <div id="visual-box" class="relative flex flex-col items-center my-4">
      <div id="image-wrapper" class="w-60 h-60 md:w-64 md:h-64 rounded-3xl border-8 border-sky-200 shadow-inner overflow-hidden bg-slate-50 flex items-center justify-center">
        <img id="question-image" src="" alt="Soalan" class="w-full h-full object-cover hidden" />
        <span id="question-emoji" class="text-[100px]">❓</span>
      </div>
      <button 
        id="btn-listen-target" 
        type="button"
        class="absolute -bottom-5 bg-sky-400 hover:bg-sky-500 active:bg-sky-600 text-white px-6 py-2 rounded-full font-bold shadow-md border-b-4 border-sky-600 active:border-b-0 active:translate-y-1 transition-all flex items-center gap-2 cursor-pointer touch-manipulation"
      >
        <span>🔊</span> Dengar
      </button>
    </div>

    <!-- Question Prompt -->
    <div id="question-prompt" class="text-4xl md:text-5xl font-black text-slate-700 mt-6 mb-2 text-center max-w-4xl">
      Prompt
    </div>

    <button 
      id="btn-listen-prompt"
      type="button"
      class="mt-2 mb-4 flex items-center gap-2 bg-sky-100 hover:bg-sky-200 active:bg-sky-300 text-sky-700 font-bold px-5 py-2 rounded-full border-2 border-sky-300 active:translate-y-1 transition-all cursor-pointer touch-manipulation"
    >
      <span class="text-xl">🔊</span> Dengar Soalan
    </button>

    <!-- Multiple Choice Options -->
    <div id="options-box" class="flex flex-wrap justify-center gap-4 md:gap-6 mt-4 w-full max-w-4xl">
      <!-- Options rendered here -->
    </div>

    <!-- Solved Congratulations Banner -->
    <div id="solved-banner" class="hidden flex-col items-center mt-6">
      <div id="solved-text" class="text-4xl md:text-5xl font-extrabold text-emerald-500 drop-shadow-sm text-center mb-5">
        Pandai, <?= htmlspecialchars($student) ?>! 🎉
      </div>
      <button 
        id="btn-next"
        type="button"
        class="bg-emerald-400 hover:bg-emerald-500 active:bg-emerald-600 text-white px-8 py-4 rounded-3xl text-2xl md:text-3xl font-black shadow-lg border-b-[6px] border-emerald-600 active:border-b-0 active:translate-y-[6px] transition-all flex items-center gap-3 cursor-pointer touch-manipulation"
      >
        Soalan Seterusnya <span>➡️</span>
      </button>
    </div>

    <!-- Complete Screen -->
    <div id="complete-screen" class="hidden flex-col items-center justify-center py-12">
      <span class="text-[100px]">🏆</span>
      <h2 class="text-5xl md:text-6xl font-black text-emerald-500 mt-6 mb-3 text-center">Tahniah!</h2>
      <p class="text-2xl md:text-3xl text-slate-500 font-bold mb-10 text-center">Hebat sekali, <?= htmlspecialchars($student) ?>!</p>
      <a href="index.php" class="bg-sky-400 hover:bg-sky-500 text-white px-8 py-4 rounded-3xl text-2xl font-bold shadow-lg">
        Kembali ke Menu
      </a>
    </div>

  </div>

  <script>
    const questions = <?= json_encode($questions, JSON_UNESCAPED_SLASHES) ?>;
    const studentName = <?= json_encode($student) ?>;
    
    let currentIndex = 0;
    let score = parseInt(sessionStorage.getItem('score') || '0');
    let isSolved = false;
    let selectedMalayVoice = null;

    const emojiMap = {
      'kereta': '🚗', 'bas': '🚌', 'lori': '🚛', 'basikal': '🚲', 'motosikal': '🏍️', 
      'kucing': '🐱', 'anjing': '🐶', 'lembu': '🐮', 'ayam': '🐔', 'burung': '🐦',
      'baju': '👕', 'buku': '📚', 'bola': '⚽', 'roti': '🍞', 'susu': '🥛'
    };

    // --- STRICT BAHASA MELAYU RESOLVER ---
    function initVoices() {
      if (!('speechSynthesis' in window)) {
        document.getElementById('voice-indicator').textContent = 'Tiada TTS';
        return;
      }

      const voices = window.speechSynthesis.getVoices();
      if (!voices || voices.length === 0) return;

      // Filter out Malayalam (ml / ml-IN) completely
      const filtered = voices.filter(v => 
        !v.lang.toLowerCase().startsWith('ml') && 
        !v.name.toLowerCase().includes('malayalam')
      );

      // Match legitimate Bahasa Melayu (ms-MY) first, or Indonesian (id-ID) as backup
      selectedMalayVoice = filtered.find(v => 
        v.lang === 'ms-MY' || 
        v.lang === 'ms_MY' || 
        v.lang.toLowerCase().startsWith('ms') ||
        v.name.toLowerCase().includes('melayu') ||
        /\bmalay\b/i.test(v.name) // Matches "Malay" as a whole word only
      ) || filtered.find(v => 
        v.lang === 'id-ID' || 
        v.lang.toLowerCase().startsWith('id')
      );

      const indicator = document.getElementById('voice-indicator');
      if (selectedMalayVoice) {
        indicator.textContent = '🔊 ' + selectedMalayVoice.name + ' (' + selectedMalayVoice.lang + ')';
        indicator.className = 'text-xs bg-emerald-100 text-emerald-800 px-3 py-1.5 rounded-xl font-bold';
      } else {
        indicator.textContent = '⚠️ Tiada Suara Melayu Dikesan';
        indicator.className = 'text-xs bg-amber-100 text-amber-800 px-3 py-1.5 rounded-xl font-bold';
      }
    }

    if ('speechSynthesis' in window) {
      window.speechSynthesis.onvoiceschanged = initVoices;
      initVoices();
    }

    // Direct speech execution without fragile regex parsing
    function speakText(text) {
      if (!text || !('speechSynthesis' in window)) return;
      try {
        window.speechSynthesis.resume();

        // If voices loaded late, attempt to grab them now
        if (!selectedMalayVoice) initVoices();

        const utter = new SpeechSynthesisUtterance(text);
        utter.rate = 1.0;
        utter.pitch = 1.0;

        if (selectedMalayVoice) {
          utter.voice = selectedMalayVoice;
          utter.lang = selectedMalayVoice.lang;
        } else {
          utter.lang = 'ms-MY';
        }

        window.speechSynthesis.speak(utter);
      } catch (err) {
        console.warn("TTS bypass error:", err);
      }
    }

    function renderQuestion() {
      if (!questions || questions.length === 0) {
        document.getElementById('question-prompt').textContent = "Tiada soalan dalam topik ini.";
        return;
      }

      if (currentIndex >= questions.length) {
        document.getElementById('visual-box').classList.add('hidden');
        document.getElementById('question-prompt').classList.add('hidden');
        document.getElementById('btn-listen-prompt').classList.add('hidden');
        document.getElementById('options-box').classList.add('hidden');
        document.getElementById('solved-banner').classList.add('hidden');
        document.getElementById('complete-screen').classList.remove('hidden');
        document.getElementById('complete-screen').classList.add('flex');
        return;
      }

      isSolved = false;
      document.getElementById('solved-banner').classList.add('hidden');
      document.getElementById('options-box').classList.remove('hidden');
      document.getElementById('score-display').textContent = score;

      const q = questions[currentIndex];
      const promptEl = document.getElementById('question-prompt');
      const imgEl = document.getElementById('question-image');
      const emojiEl = document.getElementById('question-emoji');

      // --- DYNAMIC RENDERING BY QUESTION TYPE ---
      if (q.questionType === 'SYLLABLE') {
        promptEl.innerHTML = `
          <div class="flex items-center justify-center gap-3 md:gap-5 text-5xl md:text-7xl font-black text-slate-700">
            <span class="px-5 py-3 bg-slate-100 rounded-2xl border-4 border-slate-300 shadow-inner">${q.prompt}</span>
            <span class="text-slate-300">+</span>
            <span id="syllable-target" class="px-5 py-3 border-4 border-dashed border-slate-300 rounded-2xl text-slate-300 min-w-[90px] md:min-w-[120px] text-center">???</span>
            <span class="text-slate-300">=</span>
            <span id="syllable-result" class="text-slate-300 tracking-wide">${q.prompt}___</span>
          </div>
        `;
      } else {
        promptEl.textContent = q.prompt;
      }

      // Handle Image / Fallback Emoji
      if (q.imageUrl && q.imageUrl.trim() !== '') {
        imgEl.src = q.imageUrl;
        imgEl.classList.remove('hidden');
        emojiEl.classList.add('hidden');
        imgEl.onerror = () => {
          imgEl.classList.add('hidden');
          emojiEl.classList.remove('hidden');
          emojiEl.textContent = emojiMap[q.correctAnswer.toLowerCase()] || '❓';
        };
      } else {
        imgEl.classList.add('hidden');
        emojiEl.classList.remove('hidden');
        emojiEl.textContent = emojiMap[q.correctAnswer.toLowerCase()] || '❓';
      }

      // Build Option Buttons
      const opts = [q.correctAnswer, ...q.wrongOptions.split(',')].sort();
      const optionsBox = document.getElementById('options-box');
      optionsBox.innerHTML = '';

      opts.forEach(opt => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'text-3xl md:text-5xl font-bold px-8 py-5 md:px-12 md:py-6 rounded-3xl border-b-[8px] active:border-b-0 active:translate-y-[8px] transition-all shadow-md capitalize cursor-pointer bg-sky-200 border-sky-400 text-sky-800 hover:bg-sky-300 touch-manipulation';
        btn.textContent = opt;
        btn.onclick = () => handleChoice(opt, q.correctAnswer);
        optionsBox.appendChild(btn);
      });
    }

    function handleChoice(selected, correct) {
      if (isSolved) return;
      const q = questions[currentIndex];
      speakText(selected);

      if (selected.trim().toLowerCase() === correct.trim().toLowerCase()) {
        isSolved = true;
        score += 10;
        sessionStorage.setItem('score', score);
        document.getElementById('score-display').textContent = score;

        // Visual fill-in for Syllables
        if (q.questionType === 'SYLLABLE') {
          const targetEl = document.getElementById('syllable-target');
          const resultEl = document.getElementById('syllable-result');
          if (targetEl && resultEl) {
            targetEl.textContent = correct;
            targetEl.className = 'px-5 py-3 bg-emerald-100 border-4 border-emerald-400 rounded-2xl text-emerald-600 font-black min-w-[90px] md:min-w-[120px] text-center';
            resultEl.textContent = q.prompt + correct;
            resultEl.className = 'text-emerald-500 font-black scale-105 transition-all';
          }
        }

        document.getElementById('options-box').classList.add('hidden');
        document.getElementById('solved-banner').classList.remove('hidden');
        document.getElementById('solved-banner').classList.add('flex');

        speakText("Pandai!");
      }
    }

    document.getElementById('btn-listen-target').onclick = () => {
      const q = questions[currentIndex];
      speakText(q.correctAnswer);
    };

    document.getElementById('btn-listen-prompt').onclick = () => {
      const q = questions[currentIndex];
      // Use speechPrompt if populated; otherwise use prompt
      const textToSpeak = (q.speechPrompt && q.speechPrompt.trim() !== '') ? q.speechPrompt : q.prompt;
      speakText(textToSpeak);
    };

    document.getElementById('btn-next').onclick = () => {
      currentIndex++;
      renderQuestion();
    };

    renderQuestion();
  </script>
</body>
</html>