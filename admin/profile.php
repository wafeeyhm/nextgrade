<?php
// NextGrade - System Admin: Edit Profile & Credentials
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();

// Fetch fresh details from database
$stmt = $pdo->prepare("SELECT id, username, full_name, email, created_at, last_login FROM admins WHERE id = ?");
$stmt->execute([$admin['id']]);
$adminData = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Profile & Credentials - System Admin | NextGrade</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../css/app.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body, html {
      background-color: #0b0f19 !important;
      background-image: 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, transparent 1.5px), 
        radial-gradient(rgba(51, 65, 85, 0.4) 1.5px, #0b0f19 1.5px) !important;
      color: #f1f5f9 !important;
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
            <i class="fa-solid fa-users mr-1.5 text-slate-400"></i> Parents Accounts (CRUD)
          </a>
          <a href="kids.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-graduation-cap mr-1.5 text-slate-400"></i> Students & Kids
          </a>
          <a href="guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-book-open mr-1.5 text-slate-400"></i> System Guide
          </a>
          <a href="developer_guide.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-code mr-1.5 text-slate-400"></i> Question & Dev Guide
          </a>
          <a href="profile.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-user-gear mr-1.5 text-indigo-400"></i> My Profile
          </a>
        </div>
      </div>

      <!-- Admin Status & Actions -->
      <div class="flex items-center gap-3">
        <a 
          href="guide.php" 
          title="System Admin Guide"
          class="bg-slate-700/70 hover:bg-slate-700 text-indigo-300 hover:text-white text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-book text-xs"></i>
          <span class="hidden sm:inline">System Guide</span>
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
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
          <span class="hidden sm:inline">Logout</span>
        </a>
      </div>

    </div>
  </header>

  <!-- Main Content Container -->
  <main class="flex-1 max-w-5xl w-full mx-auto px-4 md:px-8 py-8 space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight flex items-center gap-2.5">
          <span>⚙️</span>
          <span>System Admin Profile & Security</span>
        </h1>
        <p class="text-sm font-semibold text-slate-400 mt-1">
          Manage your administrator identity, contact email, username, and authentication credentials.
        </p>
      </div>

      <a 
        href="guide.php" 
        class="bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-500/30 text-indigo-300 text-xs font-bold py-2.5 px-4 rounded-xl transition-colors flex items-center gap-2"
      >
        <i class="fa-solid fa-circle-question"></i>
        <span>Admin Handbook</span>
      </a>
    </div>

    <!-- Alert Box -->
    <div id="status-alert" class="hidden p-4 rounded-2xl text-sm font-bold border transition-all"></div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <!-- Left Column: Admin Identity Badge & Metadata -->
      <div class="md:col-span-1 space-y-6">
        <div class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 shadow-xl text-center flex flex-col items-center">
          <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center text-4xl shadow-xl shadow-indigo-600/30 mb-4 border-2 border-indigo-400/30">
            🛡️
          </div>
          <h2 id="card-admin-name" class="text-lg font-black text-white"><?= htmlspecialchars($adminData['full_name']) ?></h2>
          <span id="card-admin-user" class="inline-block mt-1 font-mono text-xs font-bold px-3 py-1 bg-indigo-500/20 text-indigo-300 rounded-full border border-indigo-500/30">
            @<?= htmlspecialchars($adminData['username']) ?>
          </span>
          <span class="inline-block mt-2 text-[11px] font-black uppercase tracking-wider text-emerald-400 bg-emerald-500/10 px-3 py-0.5 rounded-md border border-emerald-500/20">
            Super Administrator
          </span>

          <div class="w-full mt-6 pt-5 border-t border-slate-700/60 text-left space-y-3 text-xs">
            <div class="flex items-center justify-between">
              <span class="text-slate-400 font-bold">Admin ID:</span>
              <span class="font-mono font-bold text-slate-200">#<?= htmlspecialchars($adminData['id']) ?></span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-400 font-bold">Role:</span>
              <span class="font-bold text-slate-200">System Admin</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-400 font-bold">Member Since:</span>
              <span class="font-bold text-slate-200"><?= date('M d, Y', strtotime($adminData['created_at'])) ?></span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-400 font-bold">Last Login:</span>
              <span class="font-bold text-slate-200">
                <?= $adminData['last_login'] ? date('M d, Y H:i', strtotime($adminData['last_login'])) : 'Active Now' ?>
              </span>
            </div>
          </div>
        </div>

        <!-- Security Best Practices Card -->
        <div class="bg-slate-800/50 border border-slate-700/70 rounded-3xl p-5 text-xs text-slate-300 space-y-2.5">
          <h3 class="font-black text-white flex items-center gap-2 text-sm">
            <i class="fa-solid fa-shield-halved text-indigo-400"></i>
            <span>Security Guidelines</span>
          </h3>
          <p class="text-slate-400 leading-relaxed font-semibold">
            As a Super Admin, your account has full control over all parents and student datasets. Use a strong password and keep your credentials confidential.
          </p>
        </div>
      </div>

      <!-- Right Column: Profile Edit & Password Change Form -->
      <div class="md:col-span-2 space-y-6">
        <form id="admin-profile-form" onsubmit="saveAdminProfile(event)" class="bg-slate-800/80 border border-slate-700 rounded-3xl p-6 md:p-8 shadow-xl space-y-6">
          
          <!-- Section 1: Basic Information -->
          <div>
            <h2 class="text-base font-black text-white flex items-center gap-2 mb-1">
              <i class="fa-solid fa-id-card text-indigo-400"></i>
              <span>Personal & Contact Information</span>
            </h2>
            <p class="text-xs text-slate-400 font-semibold mb-4">
              Update the name and official contact email displayed for the administrator account.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Full Name *</label>
                <input 
                  type="text" 
                  id="full_name" 
                  name="full_name" 
                  required 
                  value="<?= htmlspecialchars($adminData['full_name']) ?>"
                  class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-colors"
                >
              </div>

              <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Username *</label>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 font-mono text-sm">@</span>
                  <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required 
                    value="<?= htmlspecialchars($adminData['username']) ?>"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-8 pr-4 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-colors"
                  >
                </div>
              </div>

              <div class="sm:col-span-2">
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Email Address *</label>
                <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  required 
                  value="<?= htmlspecialchars($adminData['email']) ?>"
                  class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-colors"
                >
              </div>
            </div>
          </div>

          <!-- Section 2: Security & Password Update -->
          <div class="pt-6 border-t border-slate-700/70">
            <h2 class="text-base font-black text-white flex items-center gap-2 mb-1">
              <i class="fa-solid fa-lock text-indigo-400"></i>
              <span>Change Password</span>
            </h2>
            <p class="text-xs text-slate-400 font-semibold mb-4">
              Leave these fields blank if you do not wish to change your password.
            </p>

            <div class="space-y-4">
              <div>
                <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Current Admin Password</label>
                <input 
                  type="password" 
                  id="current_password" 
                  name="current_password" 
                  placeholder="Required only if changing password"
                  class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-colors"
                >
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">New Password</label>
                  <input 
                    type="password" 
                    id="new_password" 
                    name="new_password" 
                    placeholder="Min. 6 characters"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-colors"
                  >
                </div>
                <div>
                  <label class="block text-xs font-black text-slate-300 uppercase tracking-wider mb-2">Confirm New Password</label>
                  <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Re-type new password"
                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm transition-colors"
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="pt-6 border-t border-slate-700/70 flex items-center justify-end gap-3">
            <button 
              type="reset"
              class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-700/50 text-xs font-bold transition-colors cursor-pointer"
            >
              Reset Changes
            </button>
            <button 
              type="submit" 
              id="btn-save"
              class="bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-600 hover:to-sky-600 text-white font-black py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-500/25 transition-all flex items-center gap-2 cursor-pointer text-sm"
            >
              <i class="fa-solid fa-floppy-disk text-xs"></i>
              <span>Save Profile Changes</span>
            </button>
          </div>

        </form>
      </div>

    </div>

  </main>

  <script>
    async function saveAdminProfile(e) {
      e.preventDefault();
      const alertBox = document.getElementById('status-alert');
      const btnSave = document.getElementById('btn-save');

      alertBox.className = 'hidden';

      const fullName = document.getElementById('full_name').value.trim();
      const username = document.getElementById('username').value.trim();
      const email = document.getElementById('email').value.trim();
      const currentPassword = document.getElementById('current_password').value;
      const newPassword = document.getElementById('new_password').value;
      const confirmPassword = document.getElementById('confirm_password').value;

      if (!fullName || !username || !email) {
        showAlert('Please fill in all required profile fields.', 'error');
        return;
      }

      if (newPassword) {
        if (!currentPassword) {
          showAlert('Please enter your current password to set a new password.', 'error');
          return;
        }
        if (newPassword.length < 6) {
          showAlert('New password must be at least 6 characters long.', 'error');
          return;
        }
        if (newPassword !== confirmPassword) {
          showAlert('New password and confirmation do not match.', 'error');
          return;
        }
      }

      btnSave.disabled = true;
      btnSave.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Saving...</span>';

      try {
        const resp = await fetch('../api/admin_profile.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            full_name: fullName,
            username: username,
            email: email,
            current_password: currentPassword,
            new_password: newPassword,
            confirm_password: confirmPassword
          })
        });

        const data = await resp.json();

        if (data.success) {
          showAlert(data.message || 'Profile updated successfully!', 'success');
          document.getElementById('card-admin-name').textContent = fullName;
          document.getElementById('card-admin-user').textContent = `@${username}`;
          document.getElementById('current_password').value = '';
          document.getElementById('new_password').value = '';
          document.getElementById('confirm_password').value = '';
        } else {
          showAlert(data.error || 'Failed to update profile', 'error');
        }
      } catch (err) {
        showAlert('Network or server error occurred. Please try again.', 'error');
        console.error(err);
      } finally {
        btnSave.disabled = false;
        btnSave.innerHTML = '<i class="fa-solid fa-floppy-disk text-xs"></i> <span>Save Profile Changes</span>';
      }
    }

    function showAlert(msg, type) {
      const alertBox = document.getElementById('status-alert');
      alertBox.classList.remove('hidden');
      if (type === 'success') {
        alertBox.className = 'p-4 rounded-2xl text-sm font-bold border bg-emerald-500/10 border-emerald-500/30 text-emerald-300';
        alertBox.innerHTML = `<i class="fa-solid fa-circle-check mr-2"></i> ${msg}`;
      } else {
        alertBox.className = 'p-4 rounded-2xl text-sm font-bold border bg-rose-500/10 border-rose-500/30 text-rose-300';
        alertBox.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-2"></i> ${msg}`;
      }
      alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  </script>

</body>
</html>
