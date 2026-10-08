/* ============================================================
   UROO UI - Shared Application Script
   Theme engine + lucide icons + UI bootstrap
   Include this on every component/page via:
   <script src="../assets/js/uroo.js"></script>
   ============================================================ */

(function () {
  'use strict';

  window.Uroo = window.Uroo || {};

  /* ---- Theme Engine ---- */
  Uroo.theme = {
    current() {
      return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    },
    set(theme) {
      const html = document.documentElement;
      html.classList.remove('light', 'dark');
      html.classList.add(theme);
      try { localStorage.setItem('uroo-theme', theme); } catch (e) { /* noop */ }
      document.dispatchEvent(new CustomEvent('uroo:themechange', { detail: { theme } }));
    },
    toggle() {
      this.set(this.current() === 'dark' ? 'light' : 'dark');
    },
    init() {
      try {
        const saved = localStorage.getItem('uroo-theme');
        if (saved === 'light' || saved === 'dark') {
          this.set(saved);
          return;
        }
      } catch (e) { /* ignore */ }
      this.set('dark');
    },
  };

  /* ---- Icons ---- */
  Uroo.icons = {
    refresh() {
      if (window.lucide) { try { lucide.createIcons(); } catch (e) { /* noop */ } }
    },
  };

  /* ---- DOM Ready Bootstrapping ---- */
  function boot() {
    Uroo.theme.init();
    Uroo.icons.refresh();
  }

  document.addEventListener('DOMContentLoaded', boot);
  if (document.readyState === 'complete' || document.readyState === 'interactive') {
    // In case DOMContentLoaded already fired (CDN scripts delay), still boot.
    window.addEventListener('load', boot);
  }
})();
