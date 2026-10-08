# Uroo UI — Layouts

Layout adalah rangka (shell) aplikasi lengkap: sidebar + topbar + area konten.

| Layout | File | Keterangan |
|--------|------|------------|
| App | `layouts/app/app.html` | Shell aplikasi umum: sidebar collapse + drawer mobile |
| Admin | `layouts/admin/admin.html` | Shell admin: manajemen users/roles, KPI, tabel |
| Dashboard | `layouts/dashboard/dashboard.html` | Shell analitik: charts SVG, activity feed |
| Auth | `layouts/auth/auth.html` | Card login sentral di atas branding panel |
| Marketing | `layouts/marketing/marketing.html` | Navbar glass on-scroll, hero, pricing, footer |
| Ecommerce | `layouts/ecommerce/ecommerce.html` | Storefront: navbar + grid produk + cart |
| Checkout | `layouts/checkout/checkout.html` | 2 kolom: form langkah + ringkasan pesanan |
| Print | `layouts/print/print.html` | Layout printable dengan `window.print()` |

Semua layout menggunakan pattern sidebar/topbar konsisten, tema gelap/terang, dan ikon Lucide yang valid.
