# Task List Project Website PT. Syirkah Mandiri Artomoro

Dokumen ini disusun berdasarkan:

- `docs/PRD_PT_Syirkah_Mandiri_Artomoro.md`
- `docs/HOME PAGE.txt`
- Struktur project saat ini: Laravel 12, Blade, Vite, Tailwind CSS 4

Tujuan task list ini adalah menjadi backlog development untuk membangun website company profile, product showcase, principal showcase, dan inquiry channel PT. Syirkah Mandiri Artomoro.

---

## Status Eksekusi - 30 September 2026

Pembaruan 5 Oktober 2026: kartu katalog kini memiliki area gambar 4:3 dan tujuh gambar referensi sementara, terhubung ke CMS. Foto oil boom diberi label ilustrasi, bukan produk BLU-C. Sumber dan batas penggunaan dicatat di [PRODUCT_IMAGE_REFERENCES.md](PRODUCT_IMAGE_REFERENCES.md). Foto pilihan owner dan izin publikasi production tetap belum final.

Revisi header 5 Oktober 2026: seluruh halaman publik, termasuk detail produk/brand dan 404, menggunakan desain header beranda. Logo factory navy, wordmark oranye, font, lebar konten, tombol kontak melingkar, dan divider penggaris kini konsisten. Utility bar versi lama dihapus; menu aktif, navigasi mobile, keyboard, dan reduced motion tetap berfungsi. Gaya bersama berada di `resources/css/header.css`; konten halaman, footer, dan admin tidak diubah.

- Milestone 1 sudah diimplementasikan dan diverifikasi di lokal: halaman publik, katalog tujuh kategori, detail produk/brand, solusi, kontak, informasi legal terbatas, responsive UI, dan SEO dasar.
- Pencarian, filter bidang produk, dan empty state dari Milestone 2 sudah tersedia.
- Form inquiry sudah menyimpan permintaan ke database dan dashboard admin, dengan konteks produk, validasi, CSRF, honeypot, serta throttle. Link email tetap tersedia; notifikasi email otomatis belum diaktifkan.
- Konten Bahasa Indonesia sekarang dikelola melalui CMS database, dengan seed awal dari `config/company.php`. Identitas visual, foto ilustratif, serta representasi brand berbasis teks bersifat sementara sampai asset resmi tersedia.
- Nomor legal tidak dipublikasikan. Tabel spesifikasi dan download datasheet didukung template, tetapi data/PDF resmi belum tersedia.
- Homepage telah disesuaikan dengan `HOMEPAGE_DESIGN_SPEC.md`: hero putih, navy/oranye, font Outfit/Plus Jakarta Sans lokal, CTA melingkar, foto melengkung, panel statistik, carousel nilai, dan kartu layanan bergambar.
- Milestone 3 tersedia di `/admin`: login, dashboard, pengelolaan produk/kategori/brand, upload gambar/PDF, konten perusahaan, legalitas, solusi, dan inquiry.
- Verifikasi revisi header: 40 test Laravel (438 assertions), 25 test browser publik dan 5 test header pada lima viewport lulus; build production berhasil. Uji browser admin tidak diulang pada revisi tampilan header ini.
- Deployment production belum dikerjakan. Checkbox kosong tetap menjadi backlog atau menunggu data/keputusan owner.
- Panduan terkini: [ADMIN_CMS.md](ADMIN_CMS.md). Riwayat website publik: [IMPLEMENTATION_MILESTONE_1.md](IMPLEMENTATION_MILESTONE_1.md).

---

## 1. Scope dan Prioritas

### MVP / Must Have

- Homepage
- About Company
- Products & Solutions
- Product Detail
- Brands / Principals
- Contact
- Legal Information
- Responsive Design
- SEO basic

### Phase 2 / Should Have

- Product Inquiry
- Product search
- Product category filter
- Download product datasheet
- Admin CMS

### Phase 3 / Could Have

- WhatsApp CTA
- Product comparison
- Industry-based product recommendation
- News / Articles
- Project / Portfolio
- Multilingual website

---

## 2. Open Questions untuk Owner

- [ ] Konfirmasi apakah website hanya company profile atau sekaligus katalog produk.
- [ ] Konfirmasi apakah semua produk pada company profile harus ditampilkan.
- [ ] Konfirmasi level detail spesifikasi produk yang boleh dipublikasikan.
- [ ] Konfirmasi ketersediaan datasheet PDF untuk setiap produk.
- [ ] Konfirmasi apakah inquiry dikirim ke email, WhatsApp, admin dashboard, atau kombinasi.
- [x] Konfirmasi apakah Admin CMS wajib untuk versi awal (diminta untuk dieksekusi).
- [ ] Konfirmasi logo resmi, warna brand, font, dan brand guideline.
- [ ] Konfirmasi foto resmi produk, kantor, tim, proyek, atau dokumentasi perusahaan.
- [ ] Konfirmasi apakah legalitas NPWP, SK Kemenkumham, dan NIB boleh ditampilkan publik.
- [ ] Konfirmasi bahasa website: Indonesia saja atau bilingual Indonesia-Inggris.
- [ ] Konfirmasi apakah brand/principal tambahan perlu ditambahkan.
- [ ] Konfirmasi apakah project/portfolio perlu masuk ke website.

---

## 3. Foundation Project

- [x] Audit struktur Laravel saat ini dan hapus ketergantungan tampilan default `welcome.blade.php` jika sudah tidak diperlukan.
- [x] Tentukan struktur folder Blade untuk layout, partial, page, dan component.
- [x] Buat base layout publik: `head`, header, footer, main content, dan asset loading Vite.
- [x] Buat konfigurasi design token Tailwind untuk warna, typography, spacing, dan radius.
- [x] Adaptasi gaya dari `HOME PAGE.txt` ke identitas PT. Syirkah Mandiri Artomoro.
- [x] Tentukan pendekatan asset gambar: local assets, external image, atau placeholder sementara.
- [x] Buat data source awal untuk company profile, focus area, products, brands, solutions, legal, dan contact.
- [x] Tentukan apakah data awal disimpan sebagai config/static array, database seeder, atau CMS.
- [x] Siapkan route publik sesuai sitemap PRD.
- [x] Pastikan struktur URL SEO-friendly untuk produk dan brand.

Acceptance criteria:

- Project memiliki layout publik yang rapi dan reusable.
- Halaman default Laravel tidak lagi menjadi halaman utama.
- Route utama website dapat dibuka tanpa error.
- Data dasar dapat dipakai lintas halaman tanpa duplikasi besar.

---

## 4. Information Architecture dan Routing

- [x] Buat route `GET /` untuk homepage.
- [x] Buat route `GET /about` untuk About Company.
- [x] Buat route `GET /products` untuk Products & Solutions.
- [x] Buat route `GET /products/{slug}` untuk Product Detail.
- [x] Buat route `GET /brands` untuk Brands / Principals.
- [x] Buat route `GET /brands/{slug}` jika halaman detail brand dibutuhkan.
- [x] Buat route `GET /industries` atau `GET /solutions` untuk Industrial Solutions.
- [x] Buat route `GET /contact` untuk Contact.
- [x] Sediakan form inquiry di `/contact#inquiry` dan endpoint `POST /inquiry`; halaman terpisah tidak diperlukan.
- [x] Tentukan canonical navigation dan footer links.
- [x] Buat active state navigation untuk halaman yang sedang dibuka.
- [x] Buat fallback atau 404 yang rapi untuk slug produk/brand tidak ditemukan.

Acceptance criteria:

- Semua halaman must-have tersedia.
- Menu header dan footer mengarah ke URL yang benar.
- Slug produk dan brand konsisten, mudah dibaca, dan tidak duplikat.

---

## 5. Content dan Copywriting

- [x] Ubah seluruh konten template `ApexBuild` menjadi konten PT. Syirkah Mandiri Artomoro.
- [x] Tulis hero headline yang menonjolkan "Industrial Supply & Integrated Technical Solutions".
- [x] Tulis supporting copy untuk positioning: General Supplier, Engineering, Mechanical, Electrical, Instrumentation.
- [x] Buat intro perusahaan berdasarkan PRD.
- [x] Buat copy untuk focus area: Engineering, Mechanical, Electrical, Instrumentation, Oil Spill Response & Prevention.
- [x] Buat value proposition: Reliability, Efficiency, Operational Continuity, Safety, Environmental Protection.
- [x] Buat copy untuk setiap kategori produk.
- [x] Buat copy untuk setiap brand/principal.
- [x] Buat copy untuk halaman contact dan inquiry.
- [x] Buat microcopy validasi form inquiry dan success state.
- [x] Review tone copy agar profesional, industrial, dan tidak terasa seperti template konstruksi umum.

Acceptance criteria:

- Tidak ada sisa konten `ApexBuild`, lorem ipsum, atau data dummy konstruksi.
- Copy sesuai bidang industrial equipment, spare parts, dan engineering solutions.
- CTA jelas: lihat produk, request inquiry, hubungi perusahaan.

---

## 6. Homepage

- [x] Buat header sticky dengan logo/nama perusahaan.
- [x] Buat navigation: Home, About Us, Products & Solutions, Brands, Industries/Solutions, Contact.
- [x] Buat hero section dengan positioning perusahaan dan CTA utama.
- [x] Tambahkan service/focus tags: Engineering, Mechanical, Electrical, Instrumentation, Oil Spill Response.
- [x] Adaptasi visual hero dari desain home page agar relevan untuk industrial supply dan technical solutions.
- [x] Buat company introduction section.
- [x] Buat product category preview.
- [x] Buat brand/principal preview.
- [x] Buat value proposition section.
- [x] Buat contact CTA section.
- [x] Buat footer dengan alamat, phone, email, dan quick links.
- [x] Buat responsive mobile navigation.

Acceptance criteria:

- Homepage menjelaskan siapa perusahaan, apa bidangnya, produk apa yang disediakan, dan cara menghubungi.
- Tampilan desktop, tablet, dan mobile rapi.
- CTA utama mudah ditemukan pada first viewport dan footer.

---

## 7. About Company

- [x] Buat halaman About Us.
- [x] Buat section Company Profile.
- [x] Buat section background/latar belakang perusahaan.
- [x] Buat section Our Focus.
- [x] Buat section Our Value.
- [x] Buat section Legal Information.
- [x] Tambahkan CTA ke Products dan Contact.
- [x] Pastikan legal information dapat disembunyikan atau ditampilkan berdasarkan keputusan owner.

Acceptance criteria:

- Halaman about menjelaskan profil, fokus, value, dan legalitas.
- Legal information tidak menampilkan data sensitif sebelum dikonfirmasi owner.

---

## 8. Products & Solutions

- [x] Buat halaman Products & Solutions.
- [x] Buat daftar kategori produk:
  - Electric Motors & Generators
  - Vibration Technology
  - Chemical Metering Pump
  - Hose Pump
  - Industrial Hose
  - Air Compressor
  - Oil Spill Response & Prevention
- [x] Buat card produk/kategori dengan nama, brand, ringkasan, dan CTA detail.
- [x] Tambahkan gambar pada tujuh kartu katalog, rasio konsisten, lazy loading, dan tautan gambar ke detail.
- [x] Buat filter kategori produk jika masuk phase 2.
- [x] Buat pencarian produk jika masuk phase 2.
- [x] Buat empty state untuk hasil pencarian/filter kosong.
- [x] Pastikan produk memiliki hubungan ke brand/principal.

Acceptance criteria:

- Pengunjung bisa memahami kategori produk dan brand terkait.
- Struktur data mendukung penambahan produk baru.
- Katalog tetap rapi ketika jumlah produk bertambah.

---

## 9. Product Detail

- [x] Buat template Product Detail.
- [x] Tampilkan nama produk/kategori.
- [x] Tampilkan brand/principal.
- [x] Tampilkan product image atau placeholder.
- [x] Tampilkan deskripsi produk.
- [x] Tampilkan application/use case.
- [x] Tampilkan technical specification dalam format table ketika diisi melalui CMS; data resmi masih menunggu owner.
- [x] Tambahkan CTA Request Inquiry.
- [x] Tambahkan CTA Download Datasheet jika datasheet tersedia melalui upload CMS.
- [x] Tambahkan related products atau related categories.
- [x] Buat metadata SEO dinamis berdasarkan produk.

Acceptance criteria:

- Setiap produk dapat punya halaman detail yang informatif.
- Spesifikasi teknis mudah dibaca di desktop dan mobile.
- Inquiry dari product detail dapat membawa konteks produk.

---

## 10. Brands / Principals

- [x] Buat halaman Brands / Principals.
- [x] Buat daftar brand:
  - OLI
  - Wolong
  - Qdos
  - Bredel
  - Aflex
  - Tecbell
  - BLU-C
- [x] Tampilkan fokus setiap brand.
- [x] Hubungkan brand ke kategori produk terkait.
- [ ] Siapkan asset logo brand jika tersedia.
- [x] Buat halaman detail brand jika konten brand cukup banyak.

Acceptance criteria:

- Pengunjung bisa melihat principal/brand yang ditangani perusahaan.
- Brand dapat dikaitkan ke produk dan kategori produk.

---

## 11. Industries / Solutions

- [x] Buat halaman Industries atau Solutions.
- [x] Jelaskan manfaat solusi perusahaan untuk kebutuhan industri.
- [x] Tampilkan value: reliability, efficiency, operational continuity, safety, environmental protection.
- [ ] Buat section aplikasi industri, misalnya manufacturing, oil & gas, chemical, mining, marine, dan construction jika disetujui owner.
- [x] Hubungkan solusi industri ke produk relevan.
- [x] Tambahkan CTA konsultasi/inquiry.

Acceptance criteria:

- Halaman ini tidak hanya mengulang daftar produk, tetapi menjelaskan manfaat solusi.
- Pengunjung dapat diarahkan ke produk atau inquiry yang relevan.

---

## 12. Contact

- [x] Buat halaman Contact.
- [x] Tampilkan alamat:
  - Jalan Teladan No. 7, RT 04/RW 10
  - Kel. Simpang Baru, Kec. Bina Widya
  - Kota Pekanbaru, Provinsi Riau 28293
- [x] Tampilkan phone: `+62 812-6672-3815`.
- [x] Tampilkan email:
  - `doni.rahmat@smartomoro.com`
  - `admin@smartomoro.com`
- [x] Tambahkan CTA email dan phone clickable.
- [ ] Tambahkan map embed jika owner menyetujui.
- [x] Tambahkan contact form atau link inquiry jika phase 2 dikerjakan.

Acceptance criteria:

- Informasi kontak jelas dan mudah diakses.
- Link phone/email berfungsi pada desktop dan mobile.

---

## 13. Product Inquiry

Implementasi saat ini menggunakan database/admin. Konfirmasi owner untuk notifikasi tambahan tetap menjadi backlog.

- [ ] Konfirmasi channel penerimaan inquiry: email, WhatsApp, database/admin, atau kombinasi.
- [x] Buat form inquiry dengan field:
  - Nama
  - Perusahaan
  - Email
  - Nomor Telepon
  - Produk
  - Kebutuhan
  - Pesan
- [x] Buat validasi server-side untuk semua field wajib.
- [x] Buat validasi email dan nomor telepon.
- [x] Buat proteksi spam, misalnya honeypot, throttle, atau CAPTCHA jika diperlukan.
- [x] Buat success message setelah submit.
- [x] Buat error state yang jelas.
- [ ] Kirim notifikasi inquiry ke email perusahaan jika channel email dipilih.
- [x] Simpan inquiry ke database jika admin dashboard dipilih.
- [x] Tambahkan product prefill ketika user klik inquiry dari Product Detail.
- [x] Tambahkan test untuk submit inquiry valid dan invalid.

Acceptance criteria:

- Inquiry valid dapat dikirim.
- Inquiry invalid ditolak dengan pesan yang jelas.
- Data produk asal inquiry tetap terbawa.
- Form tidak mudah disalahgunakan untuk spam.

---

## 14. Admin CMS

Implementasi custom Laravel/Blade. Profil dan kontak berupa editor dokumen tunggal; solusi dan legal berupa daftar item. Tidak ada manajemen role/user melalui UI.

- [x] Tentukan apakah CMS dibuat custom Laravel atau menggunakan admin panel package.
- [x] Buat autentikasi admin, logout, ganti password, dan pemulihan via CLI.
- [x] Buat dashboard admin.
- [x] Buat pengelolaan Company Profile.
- [x] Buat CRUD Product Categories.
- [x] Buat CRUD Products, termasuk draft/publik.
- [x] Buat CRUD Product Specifications.
- [x] Buat upload/change product image dan datasheet PDF.
- [x] Buat CRUD Brands / Principals.
- [x] Buat CRUD Industries / Solutions.
- [x] Buat CRUD Legal Information.
- [x] Buat pengelolaan Contact Information.
- [x] Buat daftar Inquiry dengan pencarian/filter.
- [x] Buat detail Inquiry dan hapus permanen.
- [x] Buat status Inquiry: new, contacted, closed, serta catatan internal.
- [x] Tambahkan authorization agar hanya admin yang dapat mengelola CMS.
- [x] Tambahkan audit sederhana: created_at, updated_at, dan admin updater (bukan riwayat audit lengkap).

Acceptance criteria:

- Admin dapat memperbarui konten utama tanpa mengubah kode.
- Data publik di frontend berubah sesuai update admin.
- Endpoint admin terlindungi autentikasi dan authorization.

---

## 15. Data Model

Model dan migration menggunakan tabel relasional untuk katalog/inquiry serta dokumen JSON tervalidasi untuk konten kecil:

- [x] `CompanyProfile` melalui `SiteContent` key `company`.
- [x] `ProductCategory` melalui `Category`.
- [x] `Product`.
- [x] `ProductSpecification` melalui field JSON `Product.specifications`.
- [x] `Brand`.
- [ ] `Industry` terpisah, menunggu kategori industri yang disetujui owner.
- [x] `Solution` melalui `SiteContent` key `solutions` dengan relasi ID produk.
- [x] `Inquiry`.
- [x] `LegalInformation` melalui `SiteContent` key `legal`.
- [x] `ContactInformation` melalui dokumen profil/kontak `company`.
- [x] Field datasheet pada `Product`.
- [x] Seeder data awal berdasarkan PRD, tidak menimpa perubahan admin saat diulang.
- [x] Factory user/admin dan fixture katalog berbasis seeder untuk test data.

Acceptance criteria:

- Data produk, brand, inquiry, dan konten perusahaan punya struktur yang jelas.
- Seeder dapat mengisi konten awal untuk development/demo.

---

## 16. Asset dan Media

- [ ] Kumpulkan logo resmi PT. Syirkah Mandiri Artomoro.
- [ ] Kumpulkan logo brand/principal.
- [ ] Kumpulkan foto produk resmi.
- [ ] Kumpulkan datasheet PDF jika tersedia.
- [ ] Kumpulkan foto kantor, tim, proyek, atau dokumentasi lapangan jika tersedia.
- [x] Buat naming convention untuk asset.
- [x] Optimasi gambar untuk web.
- [x] Siapkan fallback image untuk produk tanpa foto.
- [x] Pastikan alt text relevan untuk setiap gambar penting.

Acceptance criteria:

- Gambar tidak memperlambat loading secara signifikan.
- Tidak ada gambar template yang tidak relevan dengan perusahaan.
- Setiap gambar penting memiliki alt text yang deskriptif.

---

## 17. Responsive UI dan Accessibility

- [x] Buat layout responsive untuk desktop, tablet, dan mobile.
- [x] Pastikan mobile navigation bisa dibuka/tutup dengan jelas.
- [x] Pastikan ukuran teks nyaman dibaca di mobile.
- [x] Pastikan table spesifikasi produk responsif.
- [x] Pastikan CTA tidak saling bertumpuk pada viewport kecil.
- [x] Pastikan warna memiliki kontras yang cukup.
- [x] Pastikan keyboard focus state tersedia untuk link, button, dan form.
- [x] Pastikan form label terhubung dengan input.
- [x] Pastikan gambar dekoratif tidak mengganggu screen reader.

Acceptance criteria:

- Halaman utama tidak pecah pada mobile.
- Navigasi dan form dapat digunakan dengan keyboard.
- Konten penting tetap terbaca tanpa overlap.

---

## 18. SEO dan Metadata

- [x] Buat title dan meta description untuk setiap halaman utama.
- [x] Buat metadata dinamis untuk product detail.
- [x] Buat canonical URL.
- [x] Buat Open Graph metadata untuk homepage dan product detail.
- [x] Buat sitemap.xml jika halaman sudah final.
- [x] Buat robots.txt.
- [x] Gunakan heading hierarchy yang benar.
- [ ] Optimasi keyword dasar:
  - Industrial supplier Indonesia
  - Industrial equipment Pekanbaru
  - Engineering supplier
  - Electric motor supplier
  - Industrial pump supplier
  - Oil spill equipment
- [x] Tambahkan structured data Organization jika relevan.

Acceptance criteria:

- Setiap halaman penting punya title dan description yang unik.
- Struktur heading tidak lompat-lompat.
- Search engine dapat menemukan halaman utama dan produk.

---

## 19. Performance

- [x] Pastikan asset CSS/JS dibundle dengan Vite.
- [x] Hindari Tailwind CDN untuk production.
- [x] Optimasi image size dan format.
- [ ] Lazy load gambar di bawah first viewport.
- [x] Minimalkan JavaScript interaktif yang tidak diperlukan.
- [x] Jalankan build production.
- [ ] Cek halaman dengan Lighthouse atau browser performance tools.
- [ ] Pastikan halaman katalog tetap cepat saat jumlah produk bertambah.

Acceptance criteria:

- Build production berhasil.
- Homepage dan product listing tetap ringan.
- Tidak ada asset template besar yang tidak digunakan.

---

## 20. Security

- [x] Validasi semua input form di server-side.
- [x] Terapkan CSRF protection pada form.
- [x] Terapkan rate limiting untuk form inquiry dan login.
- [x] Escape output yang berasal dari admin/CMS menggunakan Blade.
- [x] Batasi upload file berdasarkan tipe, ukuran, dan lokasi penyimpanan.
- [x] Pastikan halaman admin hanya bisa diakses user authorized.
- [ ] Pastikan `.env` tidak terekspos.
- [ ] Pastikan konfigurasi mail dan database tidak masuk repository.

Acceptance criteria:

- Form inquiry tidak menerima payload invalid.
- Upload file tidak menerima file berbahaya.
- Admin dan data sensitif tidak terbuka publik.

---

## 21. Testing

- [x] Test route homepage berhasil.
- [x] Test route About berhasil.
- [x] Test route Products berhasil.
- [x] Test route Product Detail berhasil untuk slug valid.
- [x] Test Product Detail menampilkan 404 untuk slug invalid.
- [x] Test route Brands berhasil.
- [x] Test route Contact berhasil.
- [x] Test form inquiry valid dapat submit.
- [x] Test form inquiry invalid menampilkan error.
- [ ] Test inquiry email/notification jika fitur email diaktifkan.
- [x] Test admin authorization jika CMS dibuat.
- [x] Test responsive layout secara manual di desktop dan mobile.
- [x] Jalankan `php artisan test`.
- [x] Jalankan `npm run build`.

Acceptance criteria:

- Test automated utama lulus.
- Build frontend berhasil.
- Tidak ada error runtime pada halaman utama.

---

## 22. Deployment dan Launch

- [ ] Tentukan environment hosting.
- [ ] Set production `.env`.
- [ ] Set database production jika CMS/inquiry database dipakai.
- [ ] Set mail configuration.
- [ ] Jalankan migration production.
- [ ] Jalankan seeder konten awal jika diperlukan.
- [ ] Jalankan `npm run build`.
- [ ] Upload atau publish asset storage jika ada.
- [ ] Set cache Laravel: config, route, view jika sesuai.
- [ ] Verifikasi HTTPS.
- [ ] Verifikasi form inquiry di production.
- [ ] Verifikasi phone/email link.
- [ ] Verifikasi halaman 404.
- [ ] Verifikasi sitemap dan robots.txt.
- [x] Buat checklist handover untuk owner/admin di `ADMIN_CMS.md`; verifikasi production tetap belum dilakukan.

Acceptance criteria:

- Website dapat diakses publik melalui domain production.
- Fitur kontak dan inquiry berjalan.
- Owner menerima akses admin jika CMS dibuat.

---

## 23. Suggested Milestones

### Milestone 1 - Public Website Static MVP

- Foundation project
- Routing public pages
- Homepage
- About
- Products listing
- Product detail static/data-driven
- Brands
- Contact
- Responsive design
- Basic SEO

### Milestone 2 - Inquiry dan Product Utility

- Product inquiry form
- Email notification atau database storage
- Product search
- Product category filter
- Datasheet download
- Spam protection

### Milestone 3 - Admin CMS

- Admin authentication
- CRUD products, categories, brands, company profile, contact, legal
- Inquiry management
- Upload image/datasheet

### Milestone 4 - Optimization dan Launch

- Content finalization
- Asset optimization
- Security pass
- Testing
- Production deployment
- Owner handover

---

## 24. Definition of Done

- [x] Semua halaman must-have tersedia dan dapat diakses dari navigation.
- [x] Semua konten template telah diganti dengan konten PT. Syirkah Mandiri Artomoro.
- [ ] Produk, brand, contact, dan legal information sesuai PRD atau hasil validasi owner.
- [x] Website responsive pada desktop, tablet, dan mobile.
- [x] Build production berhasil.
- [x] Test utama lulus.
- [x] Tidak ada error console atau error server pada flow utama.
- [x] SEO basic sudah terpasang.
- [x] Form inquiry dilindungi CSRF, throttle, honeypot, dan validasi server.
- [x] Dokumentasi handover tersedia jika CMS dibuat.
