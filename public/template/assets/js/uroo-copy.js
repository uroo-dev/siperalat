/* ============================================================
   UROO UI - Code Preview & Copy Engine
   Reusable: auto-adds "Lihat Kode" + "Salin" buttons to any
   element marked with data-copy.
   Include before </body> on every demo/component/foundation page:
     <script src="../../assets/js/uroo-copy.js"></script>
   ============================================================ */

(function () {
  'use strict';

  var CLS = {
    wrap: 'u-copy',
    btn: 'u-copy-btn',
    panel: 'u-copy-panel',
    code: 'u-copy-code',
    open: 'u-copy-open',
    copied: 'u-copy-copied'
  };

  var styles = [
    '.u-copy{position:relative}',
    '.u-copy-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .7rem;' +
      'border-radius:.6rem;font-size:.72rem;font-weight:600;cursor:pointer;' +
      'background:rgba(0,0,0,.05);color:#52525b;border:1px solid rgba(0,0,0,.08);' +
      'transition:background-color .2s ease,color .2s ease;line-height:1}',
    'body.dark .u-copy-btn{background:rgba(255,255,255,.08);color:#d4d4d8;border-color:rgba(255,255,255,.1)}',
    '.u-copy-btn:hover{background:rgba(0,0,0,.1)}',
    'body.dark .u-copy-btn:hover{background:rgba(255,255,255,.14)}',
    '.u-copy-panel{display:none;margin-top:.8rem;overflow:hidden;border-radius:.9rem;' +
      'background:#0c0c0f;border:1px solid rgba(255,255,255,.08)}',
    '.u-copy-panel.u-copy-open{display:block;animation:u-slide-down .25s cubic-bezier(.16,1,.3,1)}',
    '.u-copy-toolbar{display:flex;align-items:center;justify-content:space-between;padding:.4rem .8rem;' +
      'border-bottom:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.02)}',
    '.u-copy-lang{font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:#8b8b94;font-weight:700}',
    '.u-copy-copy{display:inline-flex;align-items:center;gap:.35rem;padding:.28rem .6rem;border-radius:.5rem;' +
      'font-size:.68rem;font-weight:600;cursor:pointer;background:rgba(255,255,255,.1);color:#e4e4e7;' +
      'border:1px solid rgba(255,255,255,.12);transition:background-color .2s ease}',
    '.u-copy-copy:hover{background:rgba(255,255,255,.18)}',
    '.u-copy-copied{background:rgba(16,185,129,.2)!important;color:#6ee7b7!important;border-color:rgba(16,185,129,.3)!important}',
    '.u-copy-code{max-height:340px;overflow:auto;padding:.9rem 1rem;margin:0;font-family:ui-monospace,SF Mono,monospace;' +
      'font-size:.72rem;line-height:1.6;color:#d4d4d8;white-space:pre;word-break:normal;tab-size:2}',
    '.u-copy-code::-webkit-scrollbar{width:6px;height:6px}',
    '.u-copy-code::-webkit-scrollbar-thumb{background:rgba(255,255,255,.15);border-radius:99px}',
    '.u-copy-swatch{display:grid;grid-template-columns:repeat(auto-fill,minmax(9rem,1fr));gap:.5rem;padding:.9rem 1rem}',
    '.u-copy-swatch div{font-family:ui-monospace,SF Mono,monospace;font-size:.66rem;color:#a1a1aa}' 
  ].join('\n');

  function injectStyles() {
    var id = 'u-copy-styles';
    if (document.getElementById(id)) return;
    var el = document.createElement('style');
    el.id = id;
    el.textContent = styles;
    document.head.appendChild(el);
  }

  function esc(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  function copy(text, btn, done) {
    function fallback() {
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      try { document.execCommand('copy'); done(); } catch (e) { /* ignore */ }
      document.body.removeChild(ta);
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(done, fallback);
    } else {
      fallback();
    }
  }

  function buildSectionHTML(section) {
    var clone = section.cloneNode(true);
    var props = clone.querySelector('.u-copy-props');
    if (props) props.remove();
    clone.removeAttribute('data-copy');
    var d = document.createElement('div');
    d.appendChild(clone);
    return d.innerHTML;
  }

  function decorate(section) {
    if (section.__uCopy) return;
    section.__uCopy = true;
    var wrap = document.createElement('div');
    wrap.className = CLS.wrap;

    // header toolbar
    var header = document.createElement('div');
    header.style.cssText = 'display:flex;align-items:center;gap:.5rem;margin-bottom:.6rem';
    var codeBtn = document.createElement('button');
    codeBtn.type = 'button';
    codeBtn.className = CLS.btn;
    codeBtn.innerHTML = '<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg> Lihat Kode';
    header.appendChild(codeBtn);

    // props block (if section has data-lang props)
    var props = section.getAttribute('data-props');
    if (props) {
      var propsEl = document.createElement('div');
      propsEl.className = 'u-copy-props';
      propsEl.style.cssText = 'font-size:.68rem;color:#71717a;font-family:ui-monospace,monospace';
      propsEl.textContent = props;
      header.appendChild(propsEl);
    }
    header.appendChild(document.createElement('div')).style.cssText = 'flex:1';

    // panel
    var panel = document.createElement('div');
    panel.className = CLS.panel;
    var toolbar = document.createElement('div');
    toolbar.className = 'u-copy-toolbar';
    var lang = document.createElement('span');
    lang.className = 'u-copy-lang';
    lang.textContent = section.getAttribute('data-lang') || 'html';
    var copyBtn = document.createElement('button');
    copyBtn.type = 'button';
    copyBtn.className = 'u-copy-copy';
    copyBtn.innerHTML = '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg> Salin';
    toolbar.appendChild(lang);
    toolbar.appendChild(copyBtn);
    var code = document.createElement('pre');
    code.className = CLS.code;
    panel.appendChild(toolbar);
    panel.appendChild(code);

    wrap.appendChild(header);
    wrap.appendChild(panel);

    // re-assemble: wrap children
    while (section.firstChild) wrap.appendChild(section.firstChild);
    section.appendChild(wrap);

    // events
    codeBtn.addEventListener('click', function () {
      var open = panel.classList.toggle(CLS.open);
      codeBtn.classList.toggle('u-copy-on', open);
      if (open) {
        code.textContent = section.getAttribute('data-code') || buildSectionHTML(section);
        codeBtn.querySelector('svg').style.transform = 'rotate(180deg)';
      } else {
        codeBtn.querySelector('svg').style.transform = '';
      }
      if (window.lucide) { try { lucide.createIcons(); } catch (e) {} }
    });

    copyBtn.addEventListener('click', function () {
      var text = section.getAttribute('data-code') || buildSectionHTML(section);
      var self = copyBtn;
      copy(text, self, function () {
        var original = self.innerHTML;
        self.classList.add(CLS.copied);
        self.innerHTML = '<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Disalin!';
        setTimeout(function () {
          self.classList.remove(CLS.copied);
          self.innerHTML = original;
        }, 1800);
      });
    });
  }

  function init() {
    injectStyles();
    document.querySelectorAll('[data-copy]').forEach(decorate);
    if (window.lucide) { try { lucide.createIcons(); } catch (e) {} }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
