# Gambar Referensi Katalog

Tanggal: 5 Oktober 2026. User meminta tujuh gambar sementara sebelum foto resmi perusahaan tersedia.

## Sumber

Semua berkas disimpan lokal di `public/images/products`, tanpa hotlink saat pengunjung membuka website. Enam foto adalah referensi keluarga produk dari situs produsen, bukan konfirmasi model yang tersedia. Izin publikasi komersial foto produsen belum dikonfirmasi: review dengan principal atau ganti foto sebelum production.

| Produk | Berkas | Sumber dan objek |
| --- | --- | --- |
| Electric Motors & Generators | `wolong-motor.png` | [Wolong America](https://www.wolongamerica.com/products), GP Cast Iron Standard Motor |
| Vibration Technology | `oli-vibrator.jpg` | [OLI MVE-MICRO](https://www.olivibra.com/products/mve-micro/) |
| Chemical Metering Pump | `qdos-pump.png` | [Watson-Marlow Industrial](https://www.wmfts.com/en/industrial/), Qdos chemical metering pump |
| Hose Pump | `bredel-pump.jpg` | [Watson-Marlow Industrial](https://www.wmfts.com/en/industrial/), Bredel 10-50 |
| Industrial Hose | `aflex-hose.jpg` | [Aflex Corroline+](https://www.wmfts.com/fr-fr/produit/aflex/assemblages-de-tubes-flexibles/corroline/) |
| Air Compressor | `tecbell-oilfree.png` | [Tecbell oil-free screw compressor](https://www.tecbell.cn/product_10.html) |
| Oil Spill Response & Prevention | `oil-containment-boom.jpg` | [DVIDS, image 275764](https://www.dvidshub.net/image/275764/naval-air-station-pensacola-pollution-response-unit-deploys-oil-containment-boom-sherman-cove), U.S. Navy photo by Patrick Nichols, 4 Mei 2010, halaman sumber menyatakan public domain |

Foto oil boom hanya ilustrasi kategori, **bukan foto produk BLU-C**, dokumentasi proyek perusahaan, atau endorsement. Keterangan ini terlihat di kartu dan detail produk. Foto referensi lain diberi keterangan bahwa model harus dikonfirmasi.

## Implementasi

- Area gambar kartu berasio 4:3, `object-fit: contain`, lazy loading, alt text, dan link ke detail. Layout tetap tiga kolom desktop, dua kolom layar menengah, satu kolom ponsel.
- Komponen kartu dipakai bersama katalog, halaman brand, dan produk terkait. Foto detail menggunakan sumber CMS yang sama.
- `config/product_images.php` memetakan tujuh aset referensi. Keterangan sementara mengikuti path gambar, bukan slug; mengganti foto melalui CMS otomatis menghilangkan keterangan referensi lama.
- Instalasi baru mengambil foto melalui `WebsiteContentSeeder`. Untuk database yang sudah berisi katalog, jalankan `php artisan db:seed --class=ProductImageSeeder` satu kali. Hanya gambar `null` dengan slug produk dan brand yang sesuai yang diisi; foto milik admin tidak ditimpa.
- Seeder gambar tidak dijalankan otomatis pada setiap request. Menghapus foto melalui CMS tetap menghasilkan placeholder, tanpa mengembalikan foto referensi secara diam-diam. Jangan menjalankan ulang seeder gambar jika gambar memang sengaja dikosongkan.
- Ganti melalui **Admin > Produk > Edit > Media Produk**, upload JPG/PNG/WebP, lalu Simpan Produk. Tidak perlu mengubah kode.

Foto final, pemilihan model, serta izin penggunaan brand tetap perlu persetujuan owner sebelum peluncuran.

Pada database lokal, foto motor yang sudah diunggah sebelumnya tetap dipertahankan; foto referensi Wolong hanya digunakan untuk instalasi baru atau slot kosong yang sesuai. Verifikasi revisi: 39 test Laravel (398 assertions), 25 test browser publik pada lima viewport, dan build Vite lulus.
