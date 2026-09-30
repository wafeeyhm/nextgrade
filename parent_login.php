<?php
require_once __DIR__ . '/auth_helper.php';

// Redirect if already logged in as parent
if (isParentLoggedIn()) {
    header('Location: ' . BASE_URL . 'parent.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Parent Portal Login - NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/app.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="js/sounds.js"></script>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">

  <div class="max-w-md w-full relative">
    
    <!-- Top Brand Logo -->
    <div class="text-center mb-6">
      <a href="index.php" class="inline-flex items-center gap-2 text-3xl font-black text-slate-800 tracking-tight">
        <span class="text-4xl">🌟</span>
        <span>Next<span class="text-sky-500">Grade</span></span>
      </a>
      <div class="mt-2 inline-flex items-center gap-2 bg-sky-100 text-sky-800 text-xs font-black px-3.5 py-1 rounded-full uppercase tracking-wider">
        <span>👨‍👩‍👧</span> Parent Insights & Management Portal
      </div>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-[2rem] p-7 md:p-8 shadow-xl border-3 border-slate-200 relative overflow-hidden">
      
      <div class="text-center mb-6">
        <h2 class="text-2xl font-black text-slate-800">Welcome, Parent! 👋</h2>
        <p class="text-xs font-bold text-slate-400 mt-1">Sign in to manage your kids' access, review quiz attempts & track learning gaps.</p>
      </div>

      <div id="login-alert" class="hidden mb-5 p-3.5 rounded-xl text-xs font-bold"></div>

      <form id="parent-login-form" class="space-y-4">
        <div>
          <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">
            Email or Parent Username
          </label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
              <i class="fa-solid fa-envelope"></i>
            </span>
            <input 
              type="text" 
              id="login-input" 
              required 
              value="parent"
              placeholder="e.g. parent or sarah@example.com"
              class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl pl-10 pr-4 py-3 text-slate-800 font-bold placeholder:text-slate-400 focus:outline-none focus:border-sky-500 focus:bg-white transition-all text-sm"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-1.5">
            Password
          </label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
              <i class="fa-solid fa-lock"></i>
            </span>
            <input 
              type="password" 
              id="password-input" 
              required 
              value="parent123"
              placeholder="••••••••"
              class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl pl-10 pr-10 py-3 text-slate-800 font-bold placeholder:text-slate-400 focus:outline-none focus:border-sky-500 focus:bg-white transition-all text-sm"
            >
            <button 
              type="button" 
              onclick="togglePwd()"
              class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600"
            >
              <i id="eye-icon" class="fa-solid fa-eye text-xs"></i>
            </button>
          </div>
        </div>

        <button 
          type="submit" 
          id="btn-login"
          class="btn-chunky btn-primary w-full text-base py-3.5 rounded-xl shadow-md mt-2 flex items-center justify-center gap-2"
        >
          <span>Sign In to Parent Portal</span>
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
      </form>

      <!-- Quick Demo Credentials Selector -->
      <div class="mt-6 pt-5 border-t border-slate-100">
        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block text-center mb-2.5">
          Quick Demo Accounts (Click to Fill)
        </span>
        <div class="grid grid-cols-2 gap-2">
          <button 
            type="button" 
            onclick="fillDemo('parent', 'parent123')"
            class="p-2.5 rounded-xl border border-sky-200 bg-sky-50/50 hover:bg-sky-100/60 text-left transition-colors"
          >
            <span class="block text-xs font-black text-sky-800">Puan Sarah Ahmad</span>
            <span class="block text-[10px] text-slate-500 font-mono">parent / parent123</span>
          </button>

          <button 
            type="button" 
            onclick="fillDemo('azman', 'parent123')"
            class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-left transition-colors"
          >
            <span class="block text-xs font-black text-slate-800">Encik Azman</span>
            <span class="block text-[10px] text-slate-500 font-mono">azman / parent123</span>
          </button>
        </div>
      </div>

    </div>

    <!-- Quick Navigation Links -->
    <div class="mt-6 text-center flex items-center justify-center gap-4 text-xs font-bold text-slate-400">
      <a href="index.php" class="hover:text-sky-600 transition-colors flex items-center gap-1">
        <span>⬅️</span> Student Learning Hub
      </a>
      <span>•</span>
      <a href="admin/login.php" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
        <i class="fa-solid fa-shield-halved"></i> System Admin Portal
      </a>
    </div>

  </div>

  <script>
    function togglePwd() {
      const p = document.getElementById('password-input');
      const eye = document.getElementById('eye-icon');
      if (p.type === 'password') {
        p.type = 'text';
        eye.className = 'fa-solid fa-eye-slash text-xs';
      } else {
        p.type = 'password';
        eye.className = 'fa-solid fa-eye text-xs';
      }
    }

    function fillDemo(login, pwd) {
      SoundEffects.playPop();
      document.getElementById('login-input').value = login;
      document.getElementById('password-input').value = pwd;
    }

    document.getElementById('parent-login-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      SoundEffects.playPop();
      const login = document.getElementById('login-input').value.trim();
      const password = document.getElementById('password-input').value;
      const alertBox = document.getElementById('login-alert');
      const submitBtn = document.getElementById('btn-login');

      alertBox.className = 'hidden';
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-sm"></i> <span>Verifying...</span>';

      try {
        const resp = await fetch('api/parent_auth.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'login', login, password })
        });
        const data = await resp.json();

        if (data.success) {
          SoundEffects.playChime();
          alertBox.className = 'mb-5 p-3.5 rounded-xl text-xs font-bold bg-emerald-50 border border-emerald-200 text-emerald-800 block';
          alertBox.textContent = 'Welcome back! Redirecting to Parent Portal...';
          setTimeout(() => {
            window.location.href = 'parent.php';
          }, 500);
        } else {
          SoundEffects.playBoop();
          alertBox.className = 'mb-5 p-3.5 rounded-xl text-xs font-bold bg-rose-50 border border-rose-200 text-rose-800 block';
          alertBox.textContent = data.error || 'Authentication failed.';
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<span>Sign In to Parent Portal</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
        }
      } catch (err) {
        console.error(err);
        alertBox.className = 'mb-5 p-3.5 rounded-xl text-xs font-bold bg-rose-50 border border-rose-200 text-rose-800 block';
        alertBox.textContent = 'Connection error. Please try again.';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>Sign In to Parent Portal</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
      }
    });
  </script>

</body>
</html>
