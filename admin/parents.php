<?php
// NextGrade - System Admin: Parents CRUD Management
require_once __DIR__ . '/../auth_helper.php';
requireAdmin();

$admin = getAdminUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parents Management - System Admin Portal | NextGrade</title>
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
<body class="admin-dark min-h-screen bg-slate-900 text-slate-100 flex flex-col">

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

        <div class="hidden md:flex items-center gap-1 ml-6 border-l border-slate-700 pl-6">
          <a href="index.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-chart-pie mr-1.5 text-slate-400"></i> Dashboard
          </a>
          <a href="parents.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-indigo-600/30 border border-indigo-500/40 transition-colors">
            <i class="fa-solid fa-users mr-1.5 text-indigo-400"></i> Parents Accounts (CRUD)
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
          <a href="profile.php" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 hover:text-white hover:bg-slate-700/50 transition-colors">
            <i class="fa-solid fa-user-gear mr-1.5 text-slate-400"></i> My Profile
          </a>
        </div>
      </div>

      <!-- Admin Status & Actions -->
      <div class="flex items-center gap-3">
        <a 
          href="profile.php"
          class="hidden sm:flex flex-col text-right hover:opacity-80 transition-opacity"
          title="Click to edit admin profile"
        >
          <span class="text-xs font-black text-white"><?= htmlspecialchars($admin['full_name']) ?></span>
          <span class="text-[10px] font-bold text-indigo-400 font-mono">@<?= htmlspecialchars($admin['username']) ?></span>
        </a>

        <a 
          href="guide.php" 
          title="System Admin Guide"
          class="bg-slate-700/70 hover:bg-slate-700 text-indigo-300 hover:text-white text-xs font-bold py-2 px-3 rounded-xl border border-slate-600 transition-colors flex items-center gap-1.5"
        >
          <i class="fa-solid fa-book text-xs"></i>
          <span class="hidden sm:inline">Guide</span>
        </a>

        <a 
          href="../index.php" 
          target="_blank" 
          title="Open Student App in new tab"
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
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 md:px-8 py-8 space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight flex items-center gap-2.5">
          <span>👨‍👩‍👧‍👦</span>
          <span>Parent Accounts Management</span>
        </h1>
        <p class="text-sm font-semibold text-slate-400 mt-1">
          Full CRUD access: Create, view, update, deactivate, and manage all parent profiles and family codes.
        </p>
      </div>

      <button 
        onclick="openCreateParentModal()"
        class="bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-600 hover:to-sky-600 text-white font-black py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-500/20 transition-all flex items-center gap-2 shrink-0 cursor-pointer"
      >
        <i class="fa-solid fa-plus text-sm"></i>
        <span>Add New Parent</span>
      </button>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-users"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Total Parents</span>
          <span id="stat-total-parents" class="text-2xl font-black text-white">0</span>
        </div>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Active Parents</span>
          <span id="stat-active-parents" class="text-2xl font-black text-emerald-400">0</span>
        </div>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-child-reaching"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Linked Kids</span>
          <span id="stat-total-kids" class="text-2xl font-black text-amber-400">0</span>
        </div>
      </div>

      <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 border border-purple-500/30 flex items-center justify-center text-xl">
          <i class="fa-solid fa-chart-line"></i>
        </div>
        <div>
          <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Quiz Sessions</span>
          <span id="stat-total-sessions" class="text-2xl font-black text-purple-400">0</span>
        </div>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="relative w-full md:max-w-md">
        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
          <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input 
          type="text" 
          id="parent-search-input" 
          placeholder="Search by parent name, email, username, code..."
          oninput="debounceSearch()"
          class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-2 text-sm text-white font-medium placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 transition-colors"
        >
      </div>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <label for="status-filter" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status:</label>
        <select 
          id="status-filter" 
          onchange="fetchParents()"
          class="bg-slate-900 border border-slate-700 text-slate-200 text-xs font-bold rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-500"
        >
          <option value="">All Statuses</option>
          <option value="active">Active Only</option>
          <option value="inactive">Inactive Only</option>
        </select>

        <button 
          onclick="fetchParents()" 
          title="Refresh List"
          class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-bold px-3 py-2 rounded-xl transition-colors"
        >
          <i class="fa-solid fa-arrows-rotate"></i>
        </button>
      </div>
    </div>

    <!-- Parents Table Card -->
    <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl overflow-hidden shadow-xl">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-slate-900/80 border-b border-slate-700 text-slate-400 uppercase text-[11px] font-black tracking-wider">
            <tr>
              <th class="py-3.5 px-4">Code</th>
              <th class="py-3.5 px-4">Parent Details</th>
              <th class="py-3.5 px-4">Contact Info</th>
              <th class="py-3.5 px-4 text-center">Linked Kids</th>
              <th class="py-3.5 px-4 text-center">Status</th>
              <th class="py-3.5 px-4">Registered Date</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="parents-table-body" class="divide-y divide-slate-700/50 text-slate-200 font-medium">
            <tr>
              <td colspan="7" class="text-center py-12 text-slate-400 font-bold">
                <i class="fa-solid fa-circle-notch fa-spin text-2xl text-indigo-400 mb-2 block"></i>
                Loading parent accounts...
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- ================= MODALS ================= -->

  <!-- 1. CREATE / EDIT PARENT MODAL -->
  <div id="parent-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
      
      <div class="flex items-center justify-between pb-4 border-b border-slate-700 mb-5">
        <h3 id="modal-title" class="text-xl font-black text-white flex items-center gap-2">
          <span>👨‍👩‍👧</span>
          <span>Add New Parent Account</span>
        </h3>
        <button onclick="closeParentModal()" class="text-slate-400 hover:text-white transition-colors text-xl font-bold">
          ✕
        </button>
      </div>

      <div id="modal-alert" class="hidden mb-4 p-3.5 rounded-xl text-xs font-bold"></div>

      <form id="parent-form" onsubmit="handleSaveParent(event)" class="space-y-4">
        <input type="hidden" id="form-parent-id" value="">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Parent Code</label>
            <input 
              type="text" 
              id="form-parent-code" 
              placeholder="e.g. PAR-1003"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-indigo-300 font-mono font-bold focus:outline-none focus:border-indigo-500"
            >
            <span class="text-[10px] text-slate-400">Leave blank to auto-generate</span>
          </div>

          <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
            <select 
              id="form-parent-status"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold focus:outline-none focus:border-indigo-500"
            >
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Full Name *</label>
          <input 
            type="text" 
            id="form-parent-name" 
            required 
            placeholder="e.g. Puan Sarah Ahmad"
            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500"
          >
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Login Username *</label>
            <input 
              type="text" 
              id="form-parent-username" 
              required 
              placeholder="e.g. sarah"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 font-mono"
            >
          </div>

          <div>
            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Phone Number</label>
            <input 
              type="text" 
              id="form-parent-phone" 
              placeholder="e.g. +60123456789"
              class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500"
            >
          </div>
        </div>

        <div>
          <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-1.5">Email Address *</label>
          <input 
            type="email" 
            id="form-parent-email" 
            required 
            placeholder="e.g. sarah@example.com"
            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500"
          >
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider">
              Password <span id="pwd-required-label">*</span>
            </label>
            <button 
              type="button" 
              onclick="generatePassword()" 
              class="text-[11px] font-bold text-indigo-400 hover:text-indigo-300"
            >
              Generate Random
            </button>
          </div>
          <input 
            type="text" 
            id="form-parent-password" 
            placeholder="Set parent password"
            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-sm text-white font-mono font-bold placeholder:text-slate-500 focus:outline-none focus:border-indigo-500"
          >
          <p id="pwd-hint" class="text-[11px] text-slate-400 mt-1 hidden">Leave blank if you do not wish to change the password.</p>
        </div>

        <div class="pt-4 border-t border-slate-700 flex items-center justify-end gap-3">
          <button 
            type="button" 
            onclick="closeParentModal()"
            class="bg-slate-700 hover:bg-slate-600 text-slate-300 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors"
          >
            Cancel
          </button>
          <button 
            type="submit" 
            id="form-submit-btn"
            class="bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-600 hover:to-sky-600 text-white font-black text-xs py-2.5 px-5 rounded-xl shadow-lg transition-all"
          >
            Save Parent
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- 2. VIEW LINKED KIDS MODAL -->
  <div id="view-kids-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-xl w-full p-6 shadow-2xl relative max-h-[85vh] overflow-y-auto">
      
      <div class="flex items-center justify-between pb-4 border-b border-slate-700 mb-4">
        <div>
          <h3 class="text-xl font-black text-white flex items-center gap-2">
            <span>🧒</span>
            <span id="kids-modal-parent-name">Parent's Children</span>
          </h3>
          <p id="kids-modal-subtitle" class="text-xs text-slate-400 mt-0.5">Kids registered under this family account</p>
        </div>
        <button onclick="closeKidsModal()" class="text-slate-400 hover:text-white transition-colors text-xl font-bold">
          ✕
        </button>
      </div>

      <div id="kids-list-container" class="space-y-3">
        <!-- Rendered dynamically -->
      </div>

      <div class="mt-6 pt-4 border-t border-slate-700 flex justify-end">
        <button 
          onclick="closeKidsModal()"
          class="bg-slate-700 hover:bg-slate-600 text-slate-300 font-bold text-xs py-2 px-4 rounded-xl transition-colors"
        >
          Close
        </button>
      </div>

    </div>
  </div>

  <!-- 3. DELETE CONFIRMATION MODAL -->
  <div id="delete-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-slate-800 border border-slate-700 rounded-3xl max-w-md w-full p-6 shadow-2xl relative text-center">
      
      <div class="w-16 h-16 rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center text-2xl mx-auto mb-4">
        <i class="fa-solid fa-triangle-exclamation"></i>
      </div>

      <h3 class="text-xl font-black text-white mb-2">Delete Parent Account?</h3>
      <p id="delete-message" class="text-xs font-medium text-slate-300 mb-4 leading-relaxed">
        Are you sure you want to permanently delete this parent account?
      </p>

      <div id="delete-kids-option" class="mb-5 text-left bg-slate-900/60 p-3 rounded-xl border border-slate-700 text-xs">
        <label class="flex items-center gap-2 cursor-pointer font-bold text-slate-300">
          <input type="checkbox" id="check-delete-kids" class="rounded text-rose-500 focus:ring-0">
          <span>Also delete all children profiles linked to this parent</span>
        </label>
        <span class="block text-[10px] text-slate-400 ml-5 mt-0.5">(If unchecked, children will remain in system as independent profiles)</span>
      </div>

      <div class="flex items-center justify-center gap-3">
        <button 
          onclick="closeDeleteModal()"
          class="bg-slate-700 hover:bg-slate-600 text-slate-300 font-bold text-xs py-2.5 px-4 rounded-xl transition-colors"
        >
          Cancel
        </button>
        <button 
          id="btn-confirm-delete"
          onclick="executeDeleteParent()"
          class="bg-rose-600 hover:bg-rose-500 text-white font-black text-xs py-2.5 px-5 rounded-xl transition-colors shadow-lg shadow-rose-600/30"
        >
          Yes, Delete Parent
        </button>
      </div>

    </div>
  </div>

  <script>
    let currentParents = [];
    let searchTimeout = null;
    let pendingDeleteId = null;

    const avatarMap = {
      'star_kid': '⭐',
      'bunny': '🐰',
      'astronaut': '🚀',
      'dino': '🦖',
      'kitten': '🐱',
      'unicorn': '🦄'
    };

    async function fetchParents() {
      const search = document.getElementById('parent-search-input').value.trim();
      const status = document.getElementById('status-filter').value;
      const tbody = document.getElementById('parents-table-body');

      let url = `../api/admin_parents.php?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`;

      try {
        const resp = await fetch(url);
        const data = await resp.json();

        if (!data.success) {
          tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-rose-400 font-bold">${data.error || 'Failed to fetch parents.'}</td></tr>`;
          return;
        }

        currentParents = data.parents || [];

        // Update stats
        if (data.stats) {
          document.getElementById('stat-total-parents').textContent = data.stats.total_parents;
          document.getElementById('stat-active-parents').textContent = data.stats.active_parents;
          document.getElementById('stat-total-kids').textContent = data.stats.total_kids;
          document.getElementById('stat-total-sessions').textContent = data.stats.total_sessions;
        }

        if (currentParents.length === 0) {
          tbody.innerHTML = `<tr><td colspan="7" class="text-center py-12 text-slate-400 font-bold">No parent accounts found matching criteria.</td></tr>`;
          return;
        }

        tbody.innerHTML = currentParents.map(p => {
          const isActive = p.status === 'active';
          return `
            <tr class="hover:bg-slate-700/30 transition-colors">
              <td class="py-3.5 px-4 font-mono font-bold text-xs text-indigo-400">
                ${p.parent_code}
              </td>
              <td class="py-3.5 px-4">
                <div class="font-black text-white text-sm">${p.full_name}</div>
                <div class="text-xs text-slate-400 font-mono">@${p.username}</div>
              </td>
              <td class="py-3.5 px-4">
                <div class="text-xs text-slate-300">${p.email}</div>
                <div class="text-[11px] text-slate-400">${p.phone || '<span class="italic text-slate-500">No phone</span>'}</div>
              </td>
              <td class="py-3.5 px-4 text-center">
                <button 
                  onclick="viewParentKids(${p.id}, '${escapeHtml(p.full_name)}')"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-black ${p.kids_count > 0 ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 hover:bg-indigo-500/30' : 'bg-slate-700 text-slate-400'}"
                >
                  <span>👶</span>
                  <span>${p.kids_count} ${p.kids_count === 1 ? 'Kid' : 'Kids'}</span>
                </button>
              </td>
              <td class="py-3.5 px-4 text-center">
                <button 
                  onclick="toggleParentStatus(${p.id})"
                  title="Click to toggle status"
                  class="px-2.5 py-1 rounded-full text-[11px] font-black cursor-pointer transition-colors ${isActive ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/40 hover:bg-rose-500/30'}"
                >
                  <i class="fa-solid fa-circle text-[8px] mr-1 ${isActive ? 'text-emerald-400' : 'text-rose-400'}"></i>
                  ${isActive ? 'Active' : 'Inactive'}
                </button>
              </td>
              <td class="py-3.5 px-4 text-xs font-mono text-slate-400">
                ${p.created_at ? p.created_at.substring(0, 10) : '-'}
              </td>
              <td class="py-3.5 px-4 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <button 
                    onclick="openEditParentModal(${p.id})" 
                    title="Edit Parent"
                    class="p-2 rounded-lg bg-slate-700/60 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors"
                  >
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                  </button>
                  <button 
                    onclick="promptDeleteParent(${p.id}, '${escapeHtml(p.full_name)}', ${p.kids_count})" 
                    title="Delete Parent"
                    class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 transition-colors"
                  >
                    <i class="fa-solid fa-trash text-xs"></i>
                  </button>
                </div>
              </td>
            </tr>
          `;
        }).join('');

      } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-8 text-rose-400 font-bold">Network error while fetching parent accounts.</td></tr>`;
      }
    }

    function debounceSearch() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(fetchParents, 300);
    }

    function openCreateParentModal() {
      document.getElementById('modal-title').innerHTML = `<span>👨‍👩‍👧</span> <span>Add New Parent Account</span>`;
      document.getElementById('form-parent-id').value = '';
      document.getElementById('form-parent-code').value = '';
      document.getElementById('form-parent-name').value = '';
      document.getElementById('form-parent-username').value = '';
      document.getElementById('form-parent-email').value = '';
      document.getElementById('form-parent-phone').value = '';
      document.getElementById('form-parent-password').value = 'Parent@123';
      document.getElementById('form-parent-status').value = 'active';
      
      document.getElementById('pwd-required-label').style.display = 'inline';
      document.getElementById('pwd-hint').classList.add('hidden');
      document.getElementById('form-parent-password').required = true;

      document.getElementById('modal-alert').className = 'hidden';
      document.getElementById('parent-modal').classList.remove('hidden');
    }

    function openEditParentModal(id) {
      const p = currentParents.find(x => x.id == id);
      if (!p) return;

      document.getElementById('modal-title').innerHTML = `<span>✏️</span> <span>Edit Parent: ${p.full_name}</span>`;
      document.getElementById('form-parent-id').value = p.id;
      document.getElementById('form-parent-code').value = p.parent_code;
      document.getElementById('form-parent-name').value = p.full_name;
      document.getElementById('form-parent-username').value = p.username;
      document.getElementById('form-parent-email').value = p.email;
      document.getElementById('form-parent-phone').value = p.phone || '';
      document.getElementById('form-parent-password').value = '';
      document.getElementById('form-parent-status').value = p.status;

      document.getElementById('pwd-required-label').style.display = 'none';
      document.getElementById('pwd-hint').classList.remove('hidden');
      document.getElementById('form-parent-password').required = false;

      document.getElementById('modal-alert').className = 'hidden';
      document.getElementById('parent-modal').classList.remove('hidden');
    }

    function closeParentModal() {
      document.getElementById('parent-modal').classList.add('hidden');
    }

    function generatePassword() {
      const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
      let pass = '';
      for (let i = 0; i < 10; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      document.getElementById('form-parent-password').value = pass;
    }

    async function handleSaveParent(e) {
      e.preventDefault();
      const id = document.getElementById('form-parent-id').value;
      const isEdit = !!id;

      const payload = {
        action: isEdit ? 'update' : 'create',
        id: id ? parseInt(id) : undefined,
        parent_code: document.getElementById('form-parent-code').value.trim(),
        full_name: document.getElementById('form-parent-name').value.trim(),
        username: document.getElementById('form-parent-username').value.trim(),
        email: document.getElementById('form-parent-email').value.trim(),
        phone: document.getElementById('form-parent-phone').value.trim(),
        password: document.getElementById('form-parent-password').value,
        status: document.getElementById('form-parent-status').value
      };

      const alertBox = document.getElementById('modal-alert');
      const submitBtn = document.getElementById('form-submit-btn');

      alertBox.className = 'hidden';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Saving...';

      try {
        const resp = await fetch('../api/admin_parents.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const res = await resp.json();

        if (res.success) {
          alertBox.className = 'mb-4 p-3.5 rounded-xl text-xs font-bold bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 block';
          alertBox.textContent = res.message;
          setTimeout(() => {
            closeParentModal();
            fetchParents();
          }, 600);
        } else {
          alertBox.className = 'mb-4 p-3.5 rounded-xl text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
          alertBox.textContent = res.error || 'Failed to save parent.';
        }
      } catch (err) {
        console.error(err);
        alertBox.className = 'mb-4 p-3.5 rounded-xl text-xs font-bold bg-rose-500/20 border border-rose-500/40 text-rose-300 block';
        alertBox.textContent = 'Server connection error.';
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save Parent';
      }
    }

    async function toggleParentStatus(id) {
      try {
        const resp = await fetch('../api/admin_parents.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'toggle_status', id })
        });
        const res = await resp.json();
        if (res.success) {
          fetchParents();
        } else {
          alert(res.error || 'Could not change status.');
        }
      } catch (err) {
        console.error(err);
      }
    }

    async function viewParentKids(parentId, parentName) {
      document.getElementById('kids-modal-parent-name').textContent = `${parentName}'s Children`;
      const container = document.getElementById('kids-list-container');
      container.innerHTML = '<div class="text-center py-6 text-slate-400 font-bold"><i class="fa-solid fa-circle-notch fa-spin text-xl mb-2 block"></i>Loading children profiles...</div>';
      document.getElementById('view-kids-modal').classList.remove('hidden');

      try {
        const resp = await fetch(`../api/admin_parents.php?action=single&id=${parentId}`);
        const data = await resp.json();

        if (!data.success || !data.kids) {
          container.innerHTML = '<p class="text-rose-400 text-xs font-bold text-center py-4">Failed to load kids.</p>';
          return;
        }

        if (data.kids.length === 0) {
          container.innerHTML = `
            <div class="text-center py-8 text-slate-400">
              <span class="text-4xl block mb-2">🧒</span>
              <p class="font-bold text-sm">No children linked to this parent yet.</p>
              <p class="text-xs mt-1 text-slate-500">The parent can create kid profiles from the Parent Portal.</p>
            </div>
          `;
          return;
        }

        container.innerHTML = data.kids.map(k => `
          <div class="bg-slate-900/80 border border-slate-700 rounded-2xl p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <span class="text-3xl p-2 rounded-xl bg-slate-800 border border-slate-700">
                ${avatarMap[k.avatar] || '⭐'}
              </span>
              <div>
                <div class="font-black text-white text-sm">${k.name}</div>
                <div class="text-xs text-slate-400">
                  Username: <strong class="text-indigo-400 font-mono">${k.username || 'Not set'}</strong> • 
                  PIN: <strong class="text-amber-400 font-mono">${k.pin_code || '1234'}</strong>
                </div>
                <div class="text-[11px] text-slate-500 mt-0.5">
                  Grade: ${k.grade_level || 'Year 1'}
                </div>
              </div>
            </div>

            <div class="text-right">
              <span class="block text-xs font-black text-indigo-400">${k.quiz_count} Quizzes</span>
              <span class="block text-[11px] font-bold text-slate-400">${Math.round(k.avg_score)}% Avg Score</span>
            </div>
          </div>
        `).join('');

      } catch (err) {
        console.error(err);
        container.innerHTML = '<p class="text-rose-400 text-xs font-bold text-center py-4">Error loading children data.</p>';
      }
    }

    function closeKidsModal() {
      document.getElementById('view-kids-modal').classList.add('hidden');
    }

    function promptDeleteParent(id, name, kidsCount) {
      pendingDeleteId = id;
      document.getElementById('delete-message').innerHTML = `
        Are you sure you want to permanently delete parent account <strong class="text-white">${name}</strong>?
        ${kidsCount > 0 ? `<br><span class="text-amber-400 font-bold mt-1 inline-block">⚠️ This parent currently has ${kidsCount} registered child profile(s).</span>` : ''}
      `;
      document.getElementById('check-delete-kids').checked = false;
      document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
      document.getElementById('delete-modal').classList.add('hidden');
      pendingDeleteId = null;
    }

    async function executeDeleteParent() {
      if (!pendingDeleteId) return;

      const deleteKids = document.getElementById('check-delete-kids').checked;
      const btn = document.getElementById('btn-confirm-delete');
      btn.disabled = true;
      btn.textContent = 'Deleting...';

      try {
        const resp = await fetch('../api/admin_parents.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'delete', id: pendingDeleteId, delete_kids: deleteKids })
        });
        const res = await resp.json();

        if (res.success) {
          closeDeleteModal();
          fetchParents();
        } else {
          alert(res.error || 'Could not delete parent.');
        }
      } catch (err) {
        console.error(err);
        alert('Server error while deleting parent.');
      } finally {
        btn.disabled = false;
        btn.textContent = 'Yes, Delete Parent';
      }
    }

    function escapeHtml(str) {
      if (!str) return '';
      return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    document.addEventListener('DOMContentLoaded', fetchParents);
  </script>

</body>
</html>
