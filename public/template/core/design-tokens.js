/* ============================================================
   UROO UI - Design Tokens (JavaScript)
   Dynamic glass tokens, dark/light switches, helpers
   ============================================================ */

window.Uroo = window.Uroo || {};

Uroo.tokens = {
  glass: {
    light: 'rgba(255, 255, 255, 0.65)',
    lightBorder: 'rgba(255, 255, 255, 0.8)',
    dark: 'rgba(18, 18, 20, 0.65)',
    darkBorder: 'rgba(255, 255, 255, 0.1)',
    blur: 'blur(20px) saturate(180%)',
  },
  colors: {
    light: {
      bg: '#f4f4f5',
      surface: '#ffffff',
      text: '#18181b',
      muted: '#71717a',
      primary: '#18181b',
    },
    dark: {
      bg: '#09090b',
      surface: '#18181b',
      text: '#fafafa',
      muted: '#a1a1aa',
      primary: '#fafafa',
    },
  },
  fonts: {
    sans: "'Inter', 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif",
    mono: "ui-monospace, 'SF Mono', monospace",
  },
  radius: { sm: 10, md: 14, lg: 20, xl: 24, '2xl': 32 },
  spacing: 4,
  zIndex: {
    dropdown: 1000, sticky: 1020, fixed: 1030,
    modalBackdrop: 1040, modal: 1050, popover: 1060,
    tooltip: 1070, toast: 1080,
  },
};

/* ---- Theme State ---- */
Uroo.theme = {
  current: () => (document.documentElement.classList.contains('dark') ? 'dark' : 'light'),

  set(theme) {
    const html = document.documentElement;
    html.classList.remove('light', 'dark');
    html.classList.add(theme);
    try {
      localStorage.setItem('uroo-theme', theme);
    } catch (e) { /* storage unavailable */ }
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

/* ---- Icon Engine ---- */
Uroo.icons = {
  init() {
    if (window.lucide) {
      try { lucide.createIcons(); } catch (e) { /* ignore */ }
    }
  },
};

/* ---- Reusable UI Helpers ---- */
Uroo.ui = {
  toggleClass(el, cls) {
    if (!el) return;
    el.classList.toggle(cls);
  },

  setHidden(el, hidden) {
    if (!el) return;
    el.classList.toggle('hidden', hidden);
  },

  closeOnClickOutside(el, onClose) {
    document.addEventListener('click', (e) => {
      if (el && !el.contains(e.target)) {
        onClose && onClose();
      }
    });
  },
};

/* ---- Auto Init on DOM Ready ---- */
document.addEventListener('DOMContentLoaded', () => {
  Uroo.theme.init();
  Uroo.icons.init();
});
