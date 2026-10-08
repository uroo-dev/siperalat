# Uroo UI — Icons (Lucide)

Icons dimuat **lokal** (offline) dari `assets/js/lucide.js` (berisi seluruh 2044 icon Lucide):

```html
<script src="assets/js/lucide.js"></script>
```

> Sesuaikan path dengan kedalaman file: `index.html` → `assets/js/lucide.js`,
> `examples/` → `../assets/js/lucide.js`, `components/<nama>/` → `../../assets/js/lucide.js`.

Kemudian gunakan elemen dengan atribut `data-lucide` dan inisialisasi saat runtime:

```html
<i data-lucide="settings" class="w-4 h-4"></i>
```

```js
lucide.createIcons();
```

Daftar lengkap nama icon: [lucide.dev/icons](https://lucide.dev/icons)

> Setiap `lucide.createIcons()` menggantikan `<i>` dengan SVG. Panggil ulang setelah DOM berubah atau setelah toggle tema agar ikon tetap tersinkron.
