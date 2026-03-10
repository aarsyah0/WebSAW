import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
  // ----- Sidebar (app layout): toggle mobile, collapse desktop -----
  var sidebar = document.getElementById('sidebar');
  var sidebarOverlay = document.getElementById('sidebar-overlay');
  var sidebarToggleBtns = document.querySelectorAll('[data-action="sidebar-toggle"]');
  var sidebarCollapseBtn = document.querySelector('[data-action="sidebar-collapse"]');

  function sidebarOpen() {
    if (!sidebar || !sidebarOverlay) return;
    sidebar.classList.remove('-translate-x-full');
    sidebarOverlay.classList.add('active', 'opacity-100', 'pointer-events-auto');
  }

  function sidebarClose() {
    if (!sidebar || !sidebarOverlay) return;
    sidebar.classList.add('-translate-x-full');
    sidebarOverlay.classList.remove('active', 'opacity-100', 'pointer-events-auto');
  }

  function sidebarToggle() {
    if (!sidebar) return;
    if (sidebar.classList.contains('-translate-x-full')) {
      sidebarOpen();
    } else {
      sidebarClose();
    }
  }

  sidebarToggleBtns.forEach(function (btn) {
    btn.addEventListener('click', sidebarToggle);
  });
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', sidebarClose);
  }

  if (sidebarCollapseBtn) {
    sidebarCollapseBtn.addEventListener('click', function () {
      if (!sidebar) return;
      var collapsed = sidebar.getAttribute('data-collapsed') === 'true';
      sidebar.setAttribute('data-collapsed', !collapsed);
      sidebar.classList.toggle('sidebar-collapsed', !collapsed);
      try {
        localStorage.setItem('sidebarCollapsed', !collapsed);
      } catch (e) {}
    });
  }

  if (sidebar && window.matchMedia('(min-width: 768px)').matches) {
    try {
      if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.setAttribute('data-collapsed', 'true');
        sidebar.classList.add('sidebar-collapsed');
      }
    } catch (e) {}
  }

  // ----- Nav drawer (guest layout) -----
  var navToggle = document.getElementById('nav-toggle');
  var navClose = document.getElementById('nav-close');
  var navOverlay = document.getElementById('nav-overlay');
  var navDrawer = document.getElementById('nav-drawer');

  function navDrawerOpen() {
    if (!navDrawer || !navOverlay) return;
    navDrawer.classList.add('open');
    navDrawer.setAttribute('aria-hidden', 'false');
    navOverlay.classList.add('opacity-100', 'pointer-events-auto');
  }

  function navDrawerClose() {
    if (!navDrawer || !navOverlay) return;
    navDrawer.classList.remove('open');
    navDrawer.setAttribute('aria-hidden', 'true');
    navOverlay.classList.remove('opacity-100', 'pointer-events-auto');
  }

  if (navToggle) navToggle.addEventListener('click', navDrawerOpen);
  if (navClose) navClose.addEventListener('click', navDrawerClose);
  if (navOverlay) navOverlay.addEventListener('click', navDrawerClose);
  document.querySelectorAll('.nav-drawer-link').forEach(function (link) {
    link.addEventListener('click', navDrawerClose);
  });

  // ----- Shop filter toggle (mobile) -----
  var filterToggle = document.getElementById('filter-toggle');
  var filterPanel = document.getElementById('filter-panel');
  var filterIcon = document.getElementById('filter-icon');
  if (filterToggle && filterPanel) {
    filterToggle.addEventListener('click', function () {
      filterPanel.classList.toggle('hidden');
      if (filterIcon) filterIcon.classList.toggle('rotate-180');
    });
  }

  // ----- Recommendation sliders: update value display -----
  document.querySelectorAll('input[type="range"][name^="priorities"]').forEach(function (el) {
    var name = el.getAttribute('name');
    var key = name && name.match(/\[(\w+)\]$/) ? name.match(/\[(\w+)\]/)[1] : null;
    if (!key) return;
    var valEl = document.getElementById('val-' + key);
    function update() {
      if (valEl) valEl.textContent = el.value;
    }
    el.addEventListener('input', update);
    update();
  });

  // ----- Forms with data-confirm: confirm before submit -----
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var msg = form.getAttribute('data-confirm') || 'Lanjutkan?';
      if (!window.confirm(msg)) {
        e.preventDefault();
      }
    });
  });

  // ----- Print buttons (invoice) -----
  document.querySelectorAll('[data-action="print"]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      window.print();
    });
  });
});
