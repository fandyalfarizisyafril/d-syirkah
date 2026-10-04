# Admin CMS

Implementasi lokal: 30 September 2026. CMS custom Laravel/Blade ini melanjutkan Milestone 1. Konten website sekarang bersumber dari database setelah migration dan seeder dijalankan.

## Akses Lokal

- Website: http://127.0.0.1:8000
- Admin: http://127.0.0.1:8000/admin
- Akun awal lokal: `admin@smartomoro.com`. Password acak diberikan terpisah saat handover, tidak disimpan di dokumen atau kode.
- Setelah masuk, buka menu **Akun** untuk mengganti password. Tidak ada pendaftaran publik atau password default.

## Fitur

| Menu | Pengelolaan |
| --- | --- |
| Dashboard | Jumlah produk, draft, brand, inquiry baru, inquiry terkini |
| Produk | Tambah/edit/hapus, slug unik, draft/publik, brand, kategori, deskripsi, aplikasi, spesifikasi, gambar, PDF |
| Brand | Nama, slug, deskripsi, logo |
| Kategori | Pengelompokan bidang produk |
| Profil & Kontak | Nama, wordmark, tagline, profil, alamat, lokasi, telepon, dua email |
| Bidang Fokus / Nilai | Tambah, edit, hapus item konten |
| Solusi Industri | Konten dan pilihan produk terkait |
| Legalitas | Nama dokumen, nomor/nilai, pilihan tampil publik per item |
| Inquiry | Cari/filter, detail kontak dan kebutuhan, status Baru/Dihubungi/Selesai, catatan internal, hapus permanen |
| Akun | Ganti password dengan verifikasi password saat ini |

Perubahan tersimpan langsung tercermin pada website. Draft produk tidak muncul di katalog, sitemap, atau pilihan inquiry. Brand/kategori yang masih digunakan produk tidak dapat dihapus. Hubungan solusi memakai ID produk, sehingga perubahan slug tidak merusak hubungan tersebut.

Profil dan kontak merupakan dokumen tunggal, bukan daftar perusahaan. Tujuh produk awal masih mewakili keluarga produk, bukan SKU individual. Empat kategori awal adalah Electrical, Mechanical, Fluid Handling, dan Environmental.

## Instalasi dan Pemulihan Akses

Pada lingkungan baru, siapkan dependency, application key, dan database sesuai konfigurasi Laravel. Jangan menimpa `.env` yang sudah ada. Backup database sebelum migration.

```powershell
php artisan migrate
php artisan db:seed --class=WebsiteContentSeeder
npm.cmd ci
npm.cmd run build
php artisan admin:create admin@example.com --name="Administrator"
php artisan serve --host=127.0.0.1 --port=8000
```

Perintah pembuatan akun meminta password tanpa menampilkannya. Gunakan password minimal 12 karakter, mengandung huruf dan angka. Opsi `--generate-password` membuat password acak dan menampilkannya sekali di terminal; gunakan hanya pada terminal privat. Akun yang sudah ada tidak ditimpa atau dipromosikan otomatis.

Pemulihan oleh operator yang memiliki akses server:

```powershell
php artisan admin:reset-password admin@example.com
```

Perintah reset hanya berlaku untuk akun admin. Token login dirotasi dan sesi admin lama ditolak pada permintaan berikutnya. Tidak ada reset via email karena pengiriman email belum diaktifkan.

Seeder mengambil data awal dari `config/company.php` hanya ketika profil CMS belum ada. Mengulang seeder tidak menimpa perubahan admin atau memulihkan produk yang sudah dihapus. Setelah CMS aktif, lakukan perubahan melalui admin, bukan config. Fallback config dipakai bila tabel/konten CMS belum diinisialisasi; koneksi database tetap diperlukan untuk aplikasi ini.

## Media dan Inquiry

Pembaruan 5 Oktober 2026: tujuh produk awal telah diberi foto referensi sementara. Kartu katalog, halaman brand, dan detail memakai gambar produk dari CMS. Foto dapat diganti melalui editor produk; lihat [PRODUCT_IMAGE_REFERENCES.md](PRODUCT_IMAGE_REFERENCES.md) untuk sumber dan catatan izin sebelum production.

- Gambar produk/logo menerima JPG, PNG, WebP, maksimal 2 MB. Datasheet menerima PDF maksimal 2 MB. SVG/script ditolak.
- Upload disimpan di disk `local`, folder `cms` (`storage/app/private/cms` pada konfigurasi bawaan), di luar webroot. Tidak memerlukan `storage:link`.
- Route `/media/{filename}` menyajikan media dengan nama acak; PDF dikirim sebagai unduhan. File ini dapat diakses publik oleh pemilik URL, termasuk media produk draft. Jangan unggah dokumen rahasia.
- Penggantian/penghapusan media membersihkan berkas sebelumnya. Backup database **dan** folder media bersama-sama.
- Form inquiry berada di `/contact#inquiry`. Tautan dari detail produk membawa pilihan produk otomatis. Inquiry disimpan di database, tidak mengirim email atau WhatsApp otomatis.
- Inquiry memakai validasi server, CSRF, honeypot, dan batas lima kiriman per menit per IP. Nama produk disalin saat submit agar konteks tetap ada setelah produk dihapus.
- Data kontak dan catatan inquiry hanya ditampilkan kepada admin. Penghapusan inquiry bersifat permanen; tetapkan kebijakan retensi sebelum production.
- Nomor legal tetap tidak dipublikasikan secara default. Aktifkan hanya setelah mendapat persetujuan owner.

## Struktur dan Keamanan

Data relasional menggunakan `Product`, `Brand`, `Category`, dan `Inquiry`. Profil, kontak, fokus, nilai, solusi, dan legal menggunakan dokumen JSON tervalidasi pada `SiteContent`; spesifikasi tersimpan sebagai pasangan label/nilai pada produk. Timestamp dan `updated_by` merekam perubahan terakhir, bukan riwayat audit lengkap.

Semua route pengelolaan memakai pemeriksaan `is_admin`, autentikasi sesi, CSRF, dan escaping Blade. Login memiliki pembatasan percobaan dan regenerasi sesi. Halaman admin diberi `noindex` dan header tanpa cache. User biasa tidak otomatis mendapat hak admin. Tidak ada pengelolaan role/user melalui UI pada tahap ini.

File utama: `routes/admin.php`, `app/Http/Controllers/Admin`, `app/Services/WebsiteContent.php`, `app/Services/CmsMedia.php`, `resources/views/admin`, `resources/css/admin.css`, dan `resources/js/admin.js`.

## Verifikasi

```powershell
php artisan test
npm.cmd run build
```

Pengujian browser memerlukan server lokal, Chrome, dan akun admin lokal. Set `CMS_TEST_EMAIL` dan `CMS_TEST_PASSWORD` dalam environment terminal privat, lalu jalankan `npm.cmd run test:browser`. Tanpa variabel tersebut, pengujian admin dilewati. Jangan memasukkan kredensial ke repo atau menjalankan uji tulis pada production.

Browser suite memeriksa lima viewport (320, 390, 768, 1440, 1920 px), navigasi publik, editor admin, repeater, CSRF, logout, dan overflow. Alur tulis desktop membuat produk/inquiry sementara, mengubah status, kemudian membersihkannya. Screenshot berada di `test-results/`.

Hasil lokal: 37 test Laravel lulus (372 assertions), 26 test browser lulus, empat pengulangan alur tulis dilewati, build Vite berhasil. Ini bukan audit keamanan menyeluruh atau pengujian production.

## Checklist Handover dan Production

- [x] CMS dan seed konten tersedia pada database lokal.
- [x] Akun lokal dibuat dengan password acak, tanpa menyimpan password di source.
- [x] Panduan edit konten dan pemulihan password tersedia.
- [ ] Owner mengganti password dan meninjau konten/nomor legal.
- [ ] Lengkapi logo, foto produk, spesifikasi, dan datasheet resmi.
- [ ] Tentukan penerima/notifikasi inquiry dan kebijakan retensi data kontak.
- [ ] Siapkan hosting, backup, database production, HTTPS, serta akun admin production terpisah.
- [ ] Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` yang benar, secure session cookie, dan document root ke `public`.
- [ ] Pastikan `.env` tidak dapat diakses publik; direktori storage/cache dapat ditulis oleh aplikasi.
- [ ] Jalankan migration, seed awal, build, cache Laravel, dan verifikasi ulang di production.

Deployment, SMTP, MFA, audit perubahan lengkap, serta penggantian foto ilustrasi homepage belum termasuk implementasi ini.
