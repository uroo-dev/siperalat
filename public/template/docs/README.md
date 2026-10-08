# Uroo UI — Design System

**Uroo UI** adalah design system dan UI library berbasis **Monochromatic Liquid Glassmorphism**.
Dibangun dengan HTML5, Tailwind CSS v3, Lucide Icons, dan mesin glass kustom.

---

## 1. Stack

| Lapisan | Teknologi |
|---------|-----------|
| Markup | HTML5 |
| Styling | Tailwind CSS v3 (**lokal**, `assets/js/tailwind.js`) + Custom CSS (`.glass-panel`) |
| Icons | Lucide (**lokal**, `assets/js/lucide.js`) + `lucide.createIcons()` |
| Font | Inter / Plus Jakarta Sans (**lokal**, `assets/css/fonts.css`) |
| Konsep | Monochromatic Liquid Glassmorphism |

> **Fully offline**: seluruh dependency (Tailwind, Lucide, font, gambar demo) sudah di-download ke `assets/`. Tidak ada panggilan CDN sama sekali — buka langsung di browser tanpa internet.

---

## 2. Struktur Folder

```
uroo-ui/
├── core/          Tokens: colors, typography, spacing, radius, shadows,
│                  breakpoints, z-index, animations, design-tokens.css/js
├── components/   48+ komponen standalone (button, modal, tabs, tree, ...)
├── layouts/      8 layout engine (app, admin, dashboard, auth, ...)
├── pages/        31 halaman penuh (landing, dashboard, ecommerce, ...)
├── modules/      8 modul fungsional (ecommerce, crm, hr, finance, ...)
├── themes/       light, dark, custom
├── icons/        Dokumentasi icon set
├── fonts/        Dokumentasi font
├── assets/       Shared CSS (uroo.css) & JS (uroo.js)
├── docs/         Dokumentasi resmi
└── examples/     Kumpulan contoh siap pakai
```

---

## 3. Cara Menggunakan

Semua file **standalone** dan siap dibuka langsung di browser — **100% offline** (Tailwind, Lucide, dan font sudah di-download lokal). Pola boilerplate yang sama dipakai di seluruh library:

```html
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
  <script src="../../assets/js/tailwind.js"></script>
  <script src="../../assets/js/lucide.js"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: { extend: { colors: { glass: {
        light: 'rgba(255,255,255,0.65)',
        'light-border': 'rgba(255,255,255,0.8)',
        dark: 'rgba(18,18,20,0.65)',
        'dark-border': 'rgba(255,255,255,0.1)'
      } } } }
    };
  </script>
  <link rel="stylesheet" href="../../assets/css/uroo.css">
</head>
<body class="min-h-screen font-sans antialiased text-zinc-800 dark:text-zinc-100">
  <!-- Konten -->
  <script>
    lucide.createIcons();
    function toggleTheme() {
      const h = document.documentElement, b = document.body;
      h.classList.toggle('dark'); b.classList.toggle('dark');
      lucide.createIcons();
    }
  </script>
</body>
</html>
```

> Catatan path: karena contoh di atas adalah file di kedalaman 2 (`components/<nama>/`), prefix asset-nya `../../assets/`. Untuk `index.html` di root pakai `assets/`, untuk `examples/` pakai `../assets/`.

### Glass Engine
Gunakan class `.glass-panel` untuk efek kaca:

```css
.glass-panel {
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
}
```

### Toggle Theme
Panggil `toggleTheme()` untuk beralih gelap/terang. Kelas `dark` diterapkan ke `<html>` dan `<body>` sehingga background gradient ikut berubah.

---

## 4. Navigasi Cepat

| Area | Lokasi |
|------|--------|
| Components | `components/` — lihat `docs/components.md` |
| Layouts | `layouts/` — lihat `docs/layouts.md` |
| Pages | `pages/` — lihat `docs/pages.md` |
| Modules | `modules/` — lihat `docs/modules.md` |
| Contoh Lengkap | `examples/` |
