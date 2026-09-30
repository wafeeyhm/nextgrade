<?php
require_once __DIR__ . '/../auth_helper.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    header('Location: ' . BASE_URL . 'admin/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Admin Login - NextGrade Portal</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/app.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-dark min-h-screen bg-slate-900 flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white">

  <!-- Background Glow & Grid -->
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-sky-600/20 rounded-full blur-3xl"></div>
  </div>

  <div class="max-w-md w-full relative z-10">
    <!-- Brand Header -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600 text-white text-3xl shadow-xl shadow-indigo-600/30 mb-4 border border-indigo-400/30">
        🛡️
      </div>
      <h1 class="text-3xl font-black text-white tracking-tight">System Admin Portal</h1>
      <p class="text-slate-400 text-sm font-semibold mt-1">NextGrade Core Management System</p>
    </div>

    <!-- Login Card -->
    <div class="bg-slate-800/90 backdrop-blur-xl border border-slate-700/80 rounded-3xl p-8 shadow-2xl">
      <div id="login-alert" class="hidden mb-6 p-4 rounded-xl text-sm font-bold"></div>

      <form id="admin-login-form" class="space-y-5">
        <div>
          <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Username or Admin Email</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm">
              <i class="fa-solid fa-user-shield"></i>
            </span>
            <input 
              type="text" 
              id="username" 
              name="username" 
              required 
              value="admin"
              placeholder="e.g. admin"
              class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm">
              <i class="fa-solid fa-lock"></i>
            </span>
            <input 
              type="password" 
              id="password" 
              name="password" 
              required 
              value="admin123"
              placeholder="••••••••"
              class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-10 pr-10 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
            >
            <button 
              type="button" 
              onclick="togglePassword()"
              class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200 transition-colors"
            >
              <i id="eye-icon" class="fa-solid fa-eye text-sm"></i>
            </button>
          </div>
        </div>

        <button 
          type="submit" 
          id="btn-submit"
          class="w-full bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-600 hover:to-sky-600 text-white font-black py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/25 transition-all flex items-center justify-center gap-2"
        >
          <span>Sign In to Admin Portal</span>
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
      </form>

      <!-- Demo Note -->
      <div class="mt-6 pt-4 border-t border-slate-700/60 text-center">
        <p class="text-xs text-slate-400 font-medium">Default Credentials: <strong class="text-indigo-400">admin</strong> / <strong class="text-indigo-400">admin123</strong></p>
      </div>
    </div>

    <!-- Quick Navigation Back -->
    <div class="mt-6 text-center flex items-center justify-center gap-4 text-xs font-bold text-slate-400">
      <a href="../index.php" class="hover:text-white transition-colors">
        <i class="fa-solid fa-house mr-1"></i> Student Learning Gate
      </a>
      <span>•</span>
      <a href="../parent_login.php" class="hover:text-white transition-colors">
        <i class="fa-solid fa-users mr-1"></i> Parent Portal
      </a>
    </div>
  </div>

  <script>
    function togglePassword() {
      const p = document.getElementById('password');
      const eye = document.getElementById('eye-icon');
      if (p.type === 'password') {
        p.type = 'text';
        eye.className = 'fa-solid fa-eye-slash text-sm';
      } else {
        p.type = 'password';
        eye.className = 'fa-solid fa-eye text-sm';
      }
    }

    document.getElementById('admin-login-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      const alertBox = document.getElementById('login-alert');
      const submitBtn = document.getElementById('btn-submit');
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;

      alertBox.className = 'hidden';
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Authenticating...';

      try {
        const resp = await fetch('../api/admin_auth.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'login', username, password })
        });
        const data = await resp.json();

        if (data.success) {
          alertBox.className = 'mb-6 p-4 rounded-xl text-sm font-bold bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 block';
          alertBox.textContent = 'Login successful! Redirecting to System Admin Portal...';
          setTimeout(() => {
            window.location.href = 'index.php';
          }, 600);
        } else {
          alertBox.className = 'mb-6 p-4 rounded-xl text-sm font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
          alertBox.textContent = data.error || 'Authentication failed. Please verify credentials.';
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<span>Sign In to Admin Portal</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
        }
      } catch (err) {
        console.error(err);
        alertBox.className = 'mb-6 p-4 rounded-xl text-sm font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
        alertBox.textContent = 'Server connection error. Please try again.';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>Sign In to Admin Portal</span> <i class="fa-solid fa-arrow-right text-xs"></i>';
      }
    });
  </script>
</body>
</html>
