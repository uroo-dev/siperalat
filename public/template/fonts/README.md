# Uroo UI — Fonts

Font Inter dan Plus Jakarta Sans sudah di-download lokal (offline) sebagai file `woff2`
di `assets/fonts/`, dengan aturan `@font-face` di `assets/css/fonts.css`.

## Sumber
- Diunduh dari Google Fonts (offline-ready).
- Family: **Inter** (variable) & **Plus Jakarta Sans** (variable).
- Dideklarasikan ulang di `assets/css/fonts.css` dengan unicode-range.

## Inter
- Typeface sans-serif modern untuk UI dan data.
- Pakai untuk body, tabel, form, dashboard.

## Plus Jakarta Sans
- Sans-serif geometris dengan karakter hangat.
- Cocok untuk heading, hero, marketing, brand.

## Penggunaan
- `uroo.css` sudah melakukan `@import url('./fonts.css')`, jadi font otomatis termuat
  pada semua halaman yang menautkan `assets/css/uroo.css`.
- Body default: `font-family: var(--font-sans)` → `'Inter', 'Plus Jakarta Sans', ...`
- Kelas Tailwind: `font-sans antialiased`
- Mono: `font-mono` untuk kode & kbd.

Jika ingin menambah keluarga/huruf lain: taruh file `woff2` di `assets/fonts/` lalu
tambahkan `@font-face` di `assets/css/fonts.css`.
