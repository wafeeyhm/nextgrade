// NextGrade Speech Synthesis Helper (Click to Listen)
const NextGradeSpeech = {
  synth: window.speechSynthesis,
  voices: [],

  init() {
    if (!this.synth) return;
    this.loadVoices();
    if (this.synth.onvoiceschanged !== undefined) {
      this.synth.onvoiceschanged = () => this.loadVoices();
    }
  },

  loadVoices() {
    if (!this.synth) return [];
    this.voices = this.synth.getVoices();
    return this.voices;
  },

  speak(text, lang = 'en', onStart = null, onEnd = null) {
    if (!this.synth) {
      console.warn("Speech synthesis not supported in this browser.");
      return;
    }

    this.synth.cancel(); // Stop any ongoing speech

    if (!text || text.trim() === '') return;

    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 0.9; // Slightly slower, clear pace for children
    utterance.pitch = 1.1; // Cheerful friendly pitch

    // Select suitable voice based on language
    const cleanLang = lang.toLowerCase();
    let selectedVoice = null;

    if (cleanLang === 'ms' || cleanLang.startsWith('ms')) {
      selectedVoice = this.voices.find(v => v.lang.startsWith('ms') || v.lang.startsWith('id')) ||
                      this.voices.find(v => v.name.toLowerCase().includes('malay') || v.name.toLowerCase().includes('indonesia'));
      utterance.lang = selectedVoice ? selectedVoice.lang : 'ms-MY';
    } else {
      selectedVoice = this.voices.find(v => v.lang.startsWith('en') && (v.name.includes('Natural') || v.name.includes('Female') || v.name.includes('Google'))) ||
                      this.voices.find(v => v.lang.startsWith('en'));
      utterance.lang = selectedVoice ? selectedVoice.lang : 'en-US';
    }

    if (selectedVoice) {
      utterance.voice = selectedVoice;
    }

    if (onStart) utterance.onstart = onStart;
    if (onEnd) utterance.onend = onEnd;
    utterance.onerror = () => { if (onEnd) onEnd(); };

    this.synth.speak(utterance);
  },

  stop() {
    if (this.synth) {
      this.synth.cancel();
    }
  }
};

document.addEventListener('DOMContentLoaded', () => {
  NextGradeSpeech.init();
});
