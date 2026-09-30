# Implementasi Milestone 1

Tanggal: 30 September 2026.

> Pembaruan: Admin CMS dan inquiry berbasis database sudah diimplementasikan setelah milestone ini. Panduan terkini berada di [ADMIN_CMS.md](ADMIN_CMS.md). Keterangan konten statis dan inquiry email-only di bawah adalah catatan historis Milestone 1.

## Penyesuaian Homepage Berdasarkan Design Spec

Homepage telah disesuaikan dengan `docs/HOMEPAGE_DESIGN_SPEC.md` setelah implementasi awal. Catatan desain awal di bawah bersifat historis; acuan tampilan homepage sekarang adalah spesifikasi tersebut.

- Hero putih dengan heading navy/oranye, deskripsi di kanan pada desktop, kategori berbentuk pill, dan foto bersudut lengkung di atas bidang navy.
- Statistik diambil dari data aktual katalog: 7 kategori, 7 brand/principal, dan 5 bidang fokus. Tidak memakai jumlah proyek, pelanggan, atau tahun pengalaman contoh.
- Font Outfit dan Plus Jakarta Sans dibundel lokal lewat Fontsource. Header memakai CTA melingkar dengan teks berputar, ditambah divider penggaris antarbagian.
- About memakai foto, badge bidang fokus, dekorasi crane dari referensi, dan carousel fokus/nilai perusahaan. Tombol panah bekerja dengan keyboard dan tinggi kartu tetap saat slide berganti.
- Tiga kartu layanan bergambar, tautan kategori produk, baris brand, teaser kontak, dan footer navy mempertahankan konten perusahaan serta tautan yang berfungsi.
- Statistik berpindah ke bawah foto pada mobile dengan tiga kolom ringkas. Semua animasi menghormati preferensi reduced motion.
- Video belum tersedia sehingga tidak ada tombol play tanpa fungsi. Foto ilustrasi dari ekspor desain disimpan lokal sebagai `engineering-team.jpg`, `engineering-partnership.jpg`, dan `engineering-planning.jpg`; belum merupakan dokumentasi resmi perusahaan.
- Gaya khusus berada di `resources/css/homepage.css`, dibatasi oleh kelas `homepage`. Katalog, detail, dan halaman publik lainnya mempertahankan gaya sebelumnya.
- Label kecil di panel oranye menggunakan navy dan tombol teks memakai oranye lebih gelap untuk menjaga kontras.

Verifikasi setelah penyesuaian: 18 test Laravel (144 assertions), 20 test browser pada lima viewport, dan build Vite berhasil. Bundle CSS sekitar 46.8 KB dan JavaScript 8.39 KB; font Latin yang dimuat berukuran sekitar 59.6 KB sebelum kompresi transport. Screenshot terbaru tersedia di `test-results/`.

## Hasil

Website publik PT. Syirkah Mandiri Artomoro menggunakan Laravel 12, Blade, Tailwind CSS 4, Vite, dan ikon Lucide. Bahasa konten awal adalah Indonesia; istilah kategori produk mengikuti PRD.

Halaman tersedia:

| URL | Isi |
| --- | --- |
| `/` | Homepage, positioning, fokus, kategori, brand, value, CTA |
| `/about` | Profil, fokus, value, informasi legal |
| `/products` | Tujuh kategori, pencarian, filter bidang, empty state |
| `/products/{slug}` | Detail kategori, brand, aplikasi, parameter kebutuhan, related products |
| `/brands` | Daftar tujuh brand |
| `/brands/{slug}` | Produk terkait brand |
| `/solutions` | Penggerak, fluid handling, utilitas, perlindungan lingkungan |
| `/contact` | Alamat, telepon, email, permintaan informasi via email |
| `/sitemap.xml` | Sitemap dinamis untuk 20 URL publik |
| `/robots.txt` | Aturan crawling dan URL sitemap |

Produk yang tidak ditemukan menghasilkan halaman 404 dengan navigasi kembali ke katalog. Halaman pencarian, kontak berkonteks produk, dan 404 memiliki metadata `noindex,follow`.

## Menjalankan Lokal

Dependency PHP sudah tersedia dalam workspace saat implementasi. Untuk instalasi baru, jalankan `composer install` dan siapkan `.env` beserta application key sesuai prosedur Laravel. Jangan menimpa `.env` yang sudah ada.

```powershell
npm.cmd ci
npm.cmd run build
php artisan serve --host=127.0.0.1 --port=8000
```

Buka `http://127.0.0.1:8000`. Gunakan port lain bila sudah terpakai. Server development pada sesi implementasi berjalan di port 8000; log berada di `storage/logs/dev-server.log` dan `storage/logs/dev-server-error.log`.

`npm.cmd` digunakan karena execution policy PowerShell di mesin ini memblokir wrapper `npm.ps1`. Tidak ada perubahan execution policy ataupun konfigurasi rahasia `.env`.

Untuk perubahan frontend dengan hot reload, jalankan `npm.cmd run dev` pada terminal terpisah. Saat memakai hasil build production lokal, Vite dev server tidak diperlukan.

## Struktur Implementasi

- `config/company.php`: profil, kontak, legal, fokus, value, kategori produk, hubungan brand, dan solusi.
- `app/Http/Controllers/WebsiteController.php`: pencarian/filter, resolusi slug, konteks email, sitemap.
- `resources/views/layouts`, `partials`, `components`, `pages`, `errors`: layout bersama dan halaman publik.
- `resources/css/app.css`: warna, tipografi, komponen, layout responsive, fokus keyboard, reduced motion.
- `resources/js/app.js`: ikon Lucide dan navigasi mobile dengan Escape, klik luar, serta status ARIA.
- `tests/Feature/PublicWebsiteTest.php`: pengujian perilaku aplikasi.
- `tests/Browser/public-site.spec.js`: pengujian alur dan layout browser.

Pencarian berjalan di server, menggabungkan query dan bidang produk. Pencarian meliputi nama kategori, brand, ringkasan, dan jenis produk. Filter bidang berisi Electrical, Mechanical, Fluid Handling, serta Environmental.

Daftar brand diturunkan dari data produk sehingga tautan brand dan kategori menggunakan sumber yang sama. Katalog awal berada pada level kategori, belum SKU/model individual.

## Memperbarui Konten

Konten awal diubah melalui `config/company.php`. Jalankan `php artisan config:clear` setelah perubahan apabila config pernah di-cache.

Setiap produk memiliki:

- `name`, `brand`, `brand_slug`, `group`, `summary`, `description`.
- `types`, `applications`, `requirements` untuk penjelasan kebutuhan pengadaan.
- `specifications`: array label/nilai, saat ini kosong sampai ada data resmi.
- `image`: path asset relatif terhadap `public`, atau `null` untuk placeholder ikon/kategori.
- `datasheet`: path PDF relatif terhadap `public`, atau `null` untuk menyembunyikan tombol download.

Simpan gambar resmi pada `public/images/products/<slug>.<extension>` dan PDF pada `public/datasheets/<slug>.pdf`. Pastikan asset benar-benar tersedia sebelum menambahkan path. Isi spesifikasi sesuai model aktual; jangan mengisi angka perkiraan pada level kategori.

Data legal menggunakan `name`, `value`, dan `public`. Nilai hanya tampil bila `public` bernilai `true` dan `value` terisi. Default seluruh nomor legal disembunyikan. Pengunjung diarahkan ke kontak administrasi tanpa klaim bahwa dokumen sudah diverifikasi.

## Alur Kontak

Tombol Minta Penawaran membawa slug ke `/contact?product=<slug>`. Tombol email membuka aplikasi email pengunjung dengan subjek dan body yang memuat kategori serta brand. Pengunjung masih perlu melengkapi dan mengirim email dari aplikasinya.

Tidak ada pengiriman email dari server, form submit, notifikasi, penyimpanan database inquiry, atau klaim sukses pengiriman. Konfigurasi SMTP dan keputusan channel inquiry diperlukan untuk Milestone 2. Situs tidak mengirim pesan kepada pihak luar selama pengujian.

## Asset dan Desain

Desain mengadaptasi aksen oranye, tipografi tegas, dan susunan profil/produk dari `HOME PAGE.txt`, dengan bidang terang, aksen hijau, dan warna charcoal untuk kebutuhan industrial supply. Konten konstruksi ApexBuild dan statistik dummy tidak dipakai.

Monogram SA dan nama brand berbasis teks adalah identitas sementara, bukan logo resmi atau pernyataan status authorized distributor. Foto produk asli, logo resmi perusahaan/principal, dan datasheet belum diberikan.

Hero menggunakan foto ilustratif fasilitas industri, disimpan lokal di `public/images/industrial-facility.jpg` (1600 x 1067; sekitar 385 KiB). Sumber: [foto oleh Catgirlmutant di Unsplash](https://unsplash.com/photos/a-factory-filled-with-lots-of-machines-and-equipment-jADekDuAPSA). Foto tersebut tidak menggambarkan fasilitas milik PT. Syirkah Mandiri Artomoro. Caption publik menyebutnya ilustrasi.

Tidak ada font, CSS CDN, atau gambar jarak jauh yang diperlukan saat website dibuka. Foto hero dimuat prioritas tinggi; belum ada foto lain di bawah first viewport yang memerlukan lazy loading.

## Verifikasi

```powershell
php artisan test
npm.cmd run build
npm.cmd run test:browser
```

Test browser memerlukan server Laravel berjalan dan Google Chrome terpasang. Mesin implementasi telah memiliki Chrome. Untuk Edge:

```powershell
$env:PLAYWRIGHT_CHANNEL = 'msedge'
npm.cmd run test:browser
```

Untuk port lain, set `PLAYWRIGHT_BASE_URL` sesuai URL server. Konfigurasi berada di `playwright.config.js`.

Hasil pada sesi implementasi:

- 18 test Laravel lulus, 144 assertions.
- 15 test Playwright lulus pada viewport 320x740, 390x844, 768x1024, 1440x1000, dan 1920x1080.
- Alur browser: pencarian, filter kombinasi, reset, detail produk ke email, navigasi mobile, Escape, serta pemulihan dari 404.
- Pemeriksaan delapan halaman representatif pada setiap viewport: tidak ada overflow horizontal, teks terlihat keluar container, gambar rusak, atau error JavaScript/console.
- Screenshot homepage, katalog, dan kontak berada di `test-results/`. Screenshot desktop dan mobile sudah ditinjau.
- Build Vite berhasil: CSS sekitar 29.73 KB dan JavaScript 7.13 KB sebelum gzip.
- Format PHP diperiksa dengan Laravel Pint.

Test Laravel menonaktifkan Vite agar tes backend tidak bergantung pada hasil build. Test browser menggunakan asset hasil build sesungguhnya. Hasil ini bukan audit Lighthouse, uji lintas semua browser, ataupun pengujian production.

## Pekerjaan Berikutnya

1. Finalisasi logo, foto produk, copy, data model/SKU, spesifikasi resmi, PDF, serta keputusan publikasi legal.
2. Implementasi form inquiry, validasi, antispam, penyimpanan/notifikasi sesuai channel penerimaan yang dipilih.
3. Admin CMS, autentikasi/authorization, pengelolaan konten, dan manajemen inquiry.
4. Audit performa, pengujian deployment, domain/HTTPS, konfigurasi production, dan handover owner.

CMS, database konten, dan deployment production belum termasuk hasil Milestone 1. Task list utama telah diperbarui untuk membedakan pekerjaan selesai dan backlog.
