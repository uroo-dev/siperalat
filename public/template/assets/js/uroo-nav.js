/* ============================================================
   UROO UI - Global Navigation Engine
   Single source of truth for the site index sidebar.

   - Providers the dock (left vertical rail) + glass drawer overlay
     injected into every page (the OUTER index nav), so pages with
     their own inner sidebar keep it nested inside.
   - Renders the full grouped sidebar for index.html.

   Include before </body>:
     embedded pages : <script src="../assets/js/uroo-nav.js"></script>   (depth auto-detected)
     index.html     : <script src="assets/js/uroo-nav.js"></script>
   ============================================================ */

(function () {
  'use strict';

  /* ------------------------------------------------------------
     SINGLE SOURCE OF TRUTH
     urls are RELATIVE-TO-ROOT. Numeric prefix is added at render
     time from the detected depth.
     ------------------------------------------------------------ */
  var TREE = {
    core: {
      label: 'Core',
      icon: 'droplets',
      sections: [
        { label: 'Fundasi', links: [
          { label: 'colors', url: 'pages/utilities/colors.html' },
          { label: 'typography', url: 'pages/utilities/typography.html' },
          { label: 'spacing', url: 'pages/utilities/spacing.html' },
          { label: 'radius', url: 'pages/utilities/radius.html' },
          { label: 'shadows', url: 'pages/utilities/shadow.html' },
          { label: 'breakpoints', url: 'pages/utilities/breakpoints.html' },
          { label: 'z-index', url: 'pages/utilities/z-index.html' },
          { label: 'animations', url: 'pages/utilities/animations.html' }
        ] },
        { label: 'Tokens', links: [
          { label: 'design-tokens', url: 'pages/utilities/design-tokens.html' },
          { label: 'theme (light/dark/custom)', url: 'pages/utilities/themes.html' },
          { label: 'glass engine', url: 'pages/utilities/glass.html' },
          { label: 'icons', url: 'pages/utilities/icons.html' },
          { label: 'fonts', url: 'pages/utilities/fonts.html' }
        ] }
      ]
    },
    components: {
      label: 'Components',
      icon: 'box',
      sections: [ { label: '48 komponen', links: [
        { label: 'accordion', url: 'components/accordion/accordion.html' },
        { label: 'alert', url: 'components/alert/alert.html' },
        { label: 'avatar', url: 'components/avatar/avatar.html' },
        { label: 'badge', url: 'components/badge/badge.html' },
        { label: 'breadcrumb', url: 'components/breadcrumb/breadcrumb.html' },
        { label: 'button', url: 'components/button/button.html' },
        { label: 'calendar', url: 'components/calendar/calendar.html' },
        { label: 'card', url: 'components/card/card.html' },
        { label: 'carousel', url: 'components/carousel/carousel.html' },
        { label: 'checkbox', url: 'components/checkbox/checkbox.html' },
        { label: 'chip', url: 'components/chip/chip.html' },
        { label: 'command', url: 'components/command/command.html' },
        { label: 'context-menu', url: 'components/context-menu/context-menu.html' },
        { label: 'date-picker', url: 'components/date-picker/date-picker.html' },
        { label: 'dialog', url: 'components/dialog/dialog.html' },
        { label: 'divider', url: 'components/divider/divider.html' },
        { label: 'drawer', url: 'components/drawer/drawer.html' },
        { label: 'dropdown', url: 'components/dropdown/dropdown.html' },
        { label: 'empty-state', url: 'components/empty-state/empty-state.html' },
        { label: 'file-upload', url: 'components/file-upload/file-upload.html' },
        { label: 'hover-card', url: 'components/hover-card/hover-card.html' },
        { label: 'input', url: 'components/input/input.html' },
        { label: 'input-group', url: 'components/input-group/input-group.html' },
        { label: 'kbd', url: 'components/kbd/kbd.html' },
        { label: 'label', url: 'components/label/label.html' },
        { label: 'menu', url: 'components/menu/menu.html' },
        { label: 'modal', url: 'components/modal/modal.html' },
        { label: 'pagination', url: 'components/pagination/pagination.html' },
        { label: 'popover', url: 'components/popover/popover.html' },
        { label: 'progress', url: 'components/progress/progress.html' },
        { label: 'radio', url: 'components/radio/radio.html' },
        { label: 'rating', url: 'components/rating/rating.html' },
        { label: 'select', url: 'components/select/select.html' },
        { label: 'separator', url: 'components/separator/separator.html' },
        { label: 'skeleton', url: 'components/skeleton/skeleton.html' },
        { label: 'slider', url: 'components/slider/slider.html' },
        { label: 'spinner', url: 'components/spinner/spinner.html' },
        { label: 'stat-card', url: 'components/stat-card/stat-card.html' },
        { label: 'stepper', url: 'components/stepper/stepper.html' },
        { label: 'switch', url: 'components/switch/switch.html' },
        { label: 'table', url: 'components/table/table.html' },
        { label: 'tabs', url: 'components/tabs/tabs.html' },
        { label: 'textarea', url: 'components/textarea/textarea.html' },
        { label: 'timeline', url: 'components/timeline/timeline.html' },
        { label: 'toast', url: 'components/toast/toast.html' },
        { label: 'toggle', url: 'components/toggle/toggle.html' },
        { label: 'tooltip', url: 'components/tooltip/tooltip.html' },
        { label: 'tree', url: 'components/tree/tree.html' }
      ] } ]
    },
    layouts: {
      label: 'Layouts',
      icon: 'layout-dashboard',
      sections: [ { label: '8 layout', links: [
        { label: 'app', url: 'layouts/app/app.html' },
        { label: 'admin', url: 'layouts/admin/admin.html' },
        { label: 'dashboard', url: 'layouts/dashboard/dashboard.html' },
        { label: 'auth', url: 'layouts/auth/auth.html' },
        { label: 'marketing', url: 'layouts/marketing/marketing.html' },
        { label: 'ecommerce', url: 'layouts/ecommerce/ecommerce.html' },
        { label: 'checkout', url: 'layouts/checkout/checkout.html' },
        { label: 'print', url: 'layouts/print/print.html' }
      ] } ]
    },
    pages: {
      label: 'Pages',
      icon: 'file-text',
      sections: [
        { label: 'Landing', links: [
          { label: 'hero', url: 'pages/landing/hero.html' },
          { label: 'pricing', url: 'pages/landing/pricing.html' },
          { label: 'features', url: 'pages/landing/features.html' },
          { label: 'cta', url: 'pages/landing/cta.html' }
        ] },
        { label: 'Dashboard', links: [
          { label: 'overview', url: 'pages/dashboard/overview.html' },
          { label: 'analytics', url: 'pages/dashboard/analytics.html' },
          { label: 'sales', url: 'pages/dashboard/sales.html' }
        ] },
        { label: 'Autentikasi', links: [
          { label: 'login', url: 'pages/authentication/login.html' },
          { label: 'register', url: 'pages/authentication/register.html' },
          { label: 'forgot-password', url: 'pages/authentication/forgot-password.html' },
          { label: 'otp', url: 'pages/authentication/otp.html' },
          { label: '2fa', url: 'pages/authentication/2fa.html' }
        ] },
        { label: 'Ecommerce', links: [
          { label: 'catalog', url: 'pages/ecommerce/catalog.html' },
          { label: 'product-details', url: 'pages/ecommerce/product-details.html' },
          { label: 'cart', url: 'pages/ecommerce/cart.html' },
          { label: 'checkout', url: 'pages/ecommerce/checkout.html' },
          { label: 'order-tracking', url: 'pages/ecommerce/order-tracking.html' }
        ] },
        { label: 'Profil', links: [
          { label: 'user-profile', url: 'pages/profile/user-profile.html' },
          { label: 'security-settings', url: 'pages/profile/security-settings.html' }
        ] },
        { label: 'Pengaturan', links: [
          { label: 'general', url: 'pages/settings/general.html' },
          { label: 'billing', url: 'pages/settings/billing.html' },
          { label: 'notifications', url: 'pages/settings/notifications.html' },
          { label: 'team', url: 'pages/settings/team.html' }
        ] },
        { label: 'Invoice', links: [
          { label: 'receipt', url: 'pages/invoice/receipt.html' },
          { label: 'transaction-details', url: 'pages/invoice/transaction-details.html' }
        ] },
        { label: 'Error', links: [
          { label: '404', url: 'pages/errors/404.html' },
          { label: '500', url: 'pages/errors/500.html' },
          { label: '403', url: 'pages/errors/403.html' },
          { label: 'maintenance', url: 'pages/errors/maintenance.html' }
        ] },
        { label: 'Utilitas', links: [
          { label: 'blank-canvas', url: 'pages/utilities/blank-canvas.html' },
          { label: 'maintenance', url: 'pages/utilities/maintenance.html' }
        ] }
      ]
    },
    modules: {
      label: 'Modules',
      icon: 'layers',
      sections: [ { label: '8 modul', links: [
        { label: 'ecommerce', url: 'modules/ecommerce/ecommerce.html' },
        { label: 'finance', url: 'modules/finance/finance.html' },
        { label: 'inventory', url: 'modules/inventory/inventory.html' },
        { label: 'crm', url: 'modules/crm/crm.html' },
        { label: 'hr', url: 'modules/hr/hr.html' },
        { label: 'project-management', url: 'modules/project-management/project-management.html' },
        { label: 'analytics', url: 'modules/analytics/analytics.html' },
        { label: 'notification', url: 'modules/notification/notification.html' }
      ] } ]
    },
    themes: {
      label: 'Themes',
      icon: 'palette',
      sections: [ { label: 'Mode', links: [
        { label: 'light', url: 'pages/utilities/themes.html' },
        { label: 'dark', url: 'pages/utilities/themes.html' },
        { label: 'custom', url: 'pages/utilities/themes.html' }
      ] } ]
    },
    docs: {
      label: 'Docs & Contoh',
      icon: 'book-open',
      sections: [
        { label: 'Dokumentasi', links: [
          { label: 'docs', url: 'docs/index.html' }
        ] },
        { label: 'Contoh', links: [
          { label: 'index', url: 'examples/index.html' },
          { label: 'dashboard-showcase', url: 'examples/dashboard-showcase.html' },
          { label: 'auth-flow', url: 'examples/auth-flow.html' },
          { label: 'ecommerce-storefront', url: 'examples/ecommerce-storefront.html' }
        ] }
      ]
    }
  };

  /* ------------------------------------------------------------
     DEPTH DETECTION  (from this script's own src)
     src="assets/js/uroo-nav.js"          => depth 0  (index.html)
     src="../assets/js/uroo-nav.js"       => depth 1  (examples, docs)
     src="../../assets/js/uroo-nav.js"    => depth 2  (everything else)
     ------------------------------------------------------------ */
  function detectDepth() {
    var src = '';
    try { src = (document.currentScript && document.currentScript.getAttribute('src')) || ''; } catch (e) {}
    var m = src.match(/^(\.\.\/)+/);
    return m ? (m[0].match(/\//g).length) : 0;
  }
  var DEPTH = detectDepth();
  var PREFIX = Array(DEPTH + 1).join('../');
  var ROOT = PREFIX + 'index.html';
  var LOGO = PREFIX + 'assets/img/uroo_ui.png';

  /* Inject the Uroo UI logo as the default favicon for every page. */
  (function addFavicon() {
    var link = document.createElement('link');
    link.rel = 'icon';
    link.type = 'image/png';
    link.href = LOGO;
    document.head.appendChild(link);
  })();

  /* ------------------------------------------------------------
     RENDER HELPERS
     ------------------------------------------------------------ */
  function esc(s) {
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  function absUrl(url) { return PREFIX + url; }

  function linkHtml(link) {
    return '<a class="u-nav-link" href="' + esc(absUrl(link.url)) + '">' +
      '<span class="u-nav-dot"></span>' + esc(link.label) + '</a>';
  }

  function sectionHtml(section) {
    var h = '<div class="u-nav-sec">';
    h += '<p class="u-nav-seclabel">' + esc(section.label) + '</p>';
    h += '<div class="u-nav-links">' + section.links.map(linkHtml).join('') + '</div>';
    h += '</div>';
    return h;
  }

  function groupHeaderHtml(key, g) {
    return '<a class="u-nav-head" href="' + esc(ROOT) + '#g-' + key + '">' +
      '<i data-lucide="' + g.icon + '" class="u-nav-hicon"></i>' +
      '<span>' + esc(g.label) + '</span></a>';
  }

  function groupBodyHtml(g) { return g.sections.map(sectionHtml).join(''); }

  function fullSidebarHtml() {
    var h = '';
    Object.keys(TREE).forEach(function (key) {
      var g = TREE[key];
      h += '<div class="u-nav-group">' + groupHeaderHtml(key, g) + groupBodyHtml(g) + '</div>';
    });
    return h;
  }

  /* ------------------------------------------------------------
     DOCK + DRAWER (embedded pages)
     ------------------------------------------------------------ */
  var dockEl, drawerEl, drawerBody, drawerTitle, drawerBack, currentKey = null;

  function css() {
    var c = [
      /* dock */
      '#uroo-dock{position:fixed;left:14px;top:50%;transform:translateY(-50%) translateX(120%);' +
        'z-index:80;display:flex;flex-direction:column;gap:6px;padding:8px;border-radius:18px;' +
        'background:rgba(255,255,255,.6);backdrop-filter:blur(16px);border:1px solid rgba(0,0,0,.08);' +
        'box-shadow:0 8px 30px rgba(0,0,0,.08);transition:transform .32s cubic-bezier(.16,1,.3,1)}',
      'body.dark #uroo-dock{background:rgba(20,20,24,.6);border-color:rgba(255,255,255,.1);box-shadow:0 8px 30px rgba(0,0,0,.4)}',
      '#uroo-dock.un-visible{transform:translateY(-50%) translateX(0)}',
      '#uroo-dock .u-dock-btn{display:grid;place-items:center;width:40px;height:40px;border-radius:12px;' +
        'color:#52525b;cursor:pointer;transition:background-color .18s ease,color .18s ease,transform .18s ease;background:transparent;border:none;padding:0}',
      'body.dark #uroo-dock .u-dock-btn{color:#c9c9d0}',
      '#uroo-dock .u-dock-btn:hover{background:rgba(0,0,0,.06);transform:translateY(-1px)}',
      'body.dark #uroo-dock .u-dock-btn:hover{background:rgba(255,255,255,.1)}',
      '#uroo-dock .u-dock-btn.is-on{background:#18181b;color:#fafafa}',
      'body.dark #uroo-dock .u-dock-btn.is-on{background:#fafafa;color:#18181b}',
      '#uroo-dock .u-dock-btn svg{width:19px;height:19px}',
      '#uroo-dock .u-dock-home{margin-bottom:4px}',
      '#uroo-dock .u-dock-sep{height:1px;background:rgba(0,0,0,.1);margin:4px 6px}',
      'body.dark #uroo-dock .u-dock-sep{background:rgba(255,255,255,.12)}',
      /* drawer */
      '#uroo-drawer{position:fixed;left:70px;top:50%;transform:translateY(-46%) translateX(-16px);' +
        'z-index:79;width:270px;max-height:72vh;overflow:hidden;display:flex;flex-direction:column;' +
        'border-radius:20px;background:rgba(255,255,255,.72);backdrop-filter:blur(20px);' +
        'border:1px solid rgba(0,0,0,.08);box-shadow:0 20px 50px rgba(0,0,0,.14);' +
        'opacity:0;pointer-events:none;transition:opacity .24s ease,transform .3s cubic-bezier(.16,1,.3,1)}',
      'body.dark #uroo-drawer{background:rgba(22,22,26,.74);border-color:rgba(255,255,255,.1);box-shadow:0 20px 50px rgba(0,0,0,.5)}',
      '@media (max-width:480px){#uroo-drawer{left:64px;width:min(270px,calc(100vw - 80px))}}',
      '#uroo-drawer.un-open{opacity:1;pointer-events:auto;transform:translateY(-46%) translateX(0)}',
      '#uroo-drawer .u-drawer-head{display:flex;align-items:center;gap:10px;padding:14px 16px;' +
        'border-bottom:1px solid rgba(0,0,0,.07);font-size:13px;font-weight:800;letter-spacing:-.01em}',
      'body.dark #uroo-drawer .u-drawer-head{border-bottom-color:rgba(255,255,255,.08)}',
      '#uroo-drawer .u-drawer-head .u-title{display:flex;align-items:center;gap:8px;min-width:0;flex:1;overflow-wrap:anywhere;padding-right:6px}',
      '#uroo-drawer .u-drawer-x{display:grid;place-items:center;width:30px;height:30px;border-radius:9px;' +
        'cursor:pointer;color:#71717a;background:transparent;border:none;padding:0;flex-shrink:0}',
      '#uroo-drawer .u-drawer-x:hover{background:rgba(0,0,0,.06);color:#18181b}',
      'body.dark #uroo-drawer .u-drawer-x:hover{background:rgba(255,255,255,.1);color:#fafafa}',
      '#uroo-drawer .u-drawer-body{overflow-y:auto;padding:10px 10px 14px}',
      '#uroo-drawer .u-nav-sec{display:flex;flex-direction:column;gap:2px;margin-bottom:4px}',
      '#uroo-drawer .u-nav-seclabel{padding:6px 8px 3px;font-size:10px;font-weight:800;' +
        'text-transform:uppercase;letter-spacing:.14em;color:#8b8b94}',
      '#uroo-drawer .u-nav-link{display:flex;align-items:center;gap:9px;padding:6px 9px;border-radius:9px;' +
        'font-size:12.5px;font-weight:500;color:#52525b;transition:background-color .15s ease,color .15s ease}',
      'body.dark #uroo-drawer .u-nav-link{color:#c2c2ca}',
      '#uroo-drawer .u-nav-link:hover{background:rgba(0,0,0,.06);color:#18181b}',
      'body.dark #uroo-drawer .u-nav-link:hover{background:rgba(255,255,255,.1);color:#fafafa}',
      '#uroo-drawer .u-nav-dot{width:5px;height:5px;border-radius:999px;background:currentColor;opacity:.35;flex-shrink:0}',
      /* sidebar (index.html root) */
      '#uroo-sidebar-root .u-nav-group{margin-bottom:6px}',
      '#uroo-sidebar-root .u-nav-head{display:flex;align-items:center;gap:8px;padding:7px 8px;border-radius:10px;' +
        'font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;' +
        'color:#52525b;transition:background-color .15s ease,color .15s ease;margin-bottom:2px}',
      'body.dark #uroo-sidebar-root .u-nav-head{color:#b0b0b8}',
      '#uroo-sidebar-root .u-nav-head:hover{background:rgba(0,0,0,.05);color:#18181b}',
      'body.dark #uroo-sidebar-root .u-nav-head:hover{background:rgba(255,255,255,.07);color:#fafafa}',
      '#uroo-sidebar-root .u-nav-head svg{width:15px;height:15px}',
      '#uroo-sidebar-root .u-nav-sec{margin:0 0 6px 10px}',
      '#uroo-sidebar-root .u-nav-seclabel{padding:5px 8px 2px;font-size:10px;font-weight:700;' +
        'text-transform:uppercase;letter-spacing:.14em;color:#8b8b94}',
      '#uroo-sidebar-root .u-nav-link{display:flex;align-items:center;gap:9px;padding:5px 8px;border-radius:8px;' +
        'font-size:12.5px;color:#52525b;transition:background-color .15s ease,color .15s ease}',
      'body.dark #uroo-sidebar-root .u-nav-link{color:#bfbfc7}',
      '#uroo-sidebar-root .u-nav-link:hover{background:rgba(0,0,0,.05);color:#18181b}',
      'body.dark #uroo-sidebar-root .u-nav-link:hover{background:rgba(255,255,255,.07);color:#fafafa}',
      '#uroo-sidebar-root .u-nav-dot{width:4px;height:4px;border-radius:999px;background:currentColor;opacity:.4;flex-shrink:0}'
    ];
    var style = document.createElement('style');
    style.textContent = c.join('\n');
    document.head.appendChild(style);
  }

  function highlightActive() {
    var loc = window.location || {};
    var path = (loc.pathname || '').split('/').pop();
    if (!path) return;
    document.querySelectorAll('.u-nav-link[href]').forEach(function (a) {
      var href = a.getAttribute('href');
      if (href && href.split('/').pop() === path) a.style.background = 'rgba(0,0,0,.07)';
    });
  }

  function openDrawerFor(key) {
    var g = TREE[key];
    if (!g) return;
    currentKey = key;
    drawerTitle.innerHTML = '<i data-lucide="' + g.icon + '" style="width:17px;height:17px;flex-shrink:0"></i> ' + esc(g.label);
    drawerBody.innerHTML = groupBodyHtml(g);
    drawerEl.classList.add('un-open');
    if (window.lucide) lucide.createIcons();
    highlightActive();
    document.querySelectorAll('#uroo-dock .u-dock-btn').forEach(function (b) {
      b.classList.toggle('is-on', b.getAttribute('data-key') === key);
    });
  }

  function closeDrawer() {
    if (!drawerEl) return;
    drawerEl.classList.remove('un-open');
    document.querySelectorAll('#uroo-dock .u-dock-btn.is-on').forEach(function (b) { b.classList.remove('is-on'); });
    currentKey = null;
  }

  function buildDock() {
    dockEl = document.createElement('div');
    dockEl.id = 'uroo-dock';
    var home = document.createElement('a');
    home.href = ROOT;
    home.className = 'u-dock-btn u-dock-home';
    home.title = 'Ke Index (Uroo UI)';
    home.innerHTML =
      '<img src="' + LOGO + '" alt="Uroo UI" style="width:24px;height:24px;border-radius:7px;object-fit:cover;' +
      'box-shadow:0 1px 3px rgba(0,0,0,.2)">';
    dockEl.appendChild(home);
    var sep = document.createElement('div');
    sep.className = 'u-dock-sep';
    dockEl.appendChild(sep);
    Object.keys(TREE).forEach(function (key) {
      var g = TREE[key];
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'u-dock-btn';
      btn.setAttribute('data-key', key);
      btn.title = g.label;
      btn.innerHTML = '<i data-lucide="' + g.icon + '"></i>';
      btn.addEventListener('click', function () {
        if (currentKey === key) { closeDrawer(); } else { openDrawerFor(key); }
      });
      dockEl.appendChild(btn);
    });
    document.body.appendChild(dockEl);

    drawerEl = document.createElement('div');
    drawerEl.id = 'uroo-drawer';
    drawerTitle = document.createElement('div');
    drawerTitle.className = 'u-title';
    var head = document.createElement('div');
    head.className = 'u-drawer-head';
    var x = document.createElement('button');
    x.type = 'button';
    x.className = 'u-drawer-x';
    x.title = 'Tutup';
    x.innerHTML = '<i data-lucide="x"></i>';
    x.addEventListener('click', closeDrawer);
    head.appendChild(drawerTitle);
    head.appendChild(x);
    drawerBody = document.createElement('div');
    drawerBody.className = 'u-drawer-body';
    drawerEl.appendChild(head);
    drawerEl.appendChild(drawerBody);
    document.body.appendChild(drawerEl);

    /* backdrop clenup + esc */
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDrawer(); }, true);
    document.addEventListener('click', function (e) {
      if (!currentKey) return;
      if (dockEl.contains(e.target) || drawerEl.contains(e.target)) return;
      closeDrawer();
    });

    requestAnimationFrame(function () { dockEl.classList.add('un-visible'); });
    if (window.lucide) lucide.createIcons();
  }

  function buildSidebar(rootEl) {
    rootEl.innerHTML = fullSidebarHtml();
    if (window.lucide) lucide.createIcons();
    highlightActive();
  }

  /* ------------------------------------------------------------
     PUBLIC API
     ------------------------------------------------------------ */
  window.UrooNav = {
    TREE: TREE,
    ABS: absUrl,
    ROOT: ROOT,
    DEPTH: DEPTH,
    initDock: function () { css(); buildDock(); },
    renderSidebar: function (el) { css(); buildSidebar(el); },
    openGroup: openDrawerFor,
    close: closeDrawer
  };

  /* ------------------------------------------------------------
     AUTO BOOT
     index.html renders full sidebar if #uroo-sidebar-root exists,
     otherwise embedded pages get the dock.
     ------------------------------------------------------------ */
  function boot() {
    css();
    var sidebarRoot = document.getElementById('uroo-sidebar-root');
    if (sidebarRoot) {
      buildSidebar(sidebarRoot);
    } else {
      buildDock();
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
