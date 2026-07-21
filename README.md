# Global Supply Chain Risk Intelligence Platform

Platform Laravel untuk memantau ekonomi, cuaca, kurs, berita, sentimen, risiko negara, serta jaringan pelabuhan dan rute logistik global.

## Kapabilitas utama

- Dashboard command center dengan snapshot data nyata.
- Master 250 negara dan teritori berbasis kode ISO2.
- 12.000 pelabuhan dari NGA World Port Index dan UNECE UN/LOCODE.
- Open-Meteo, World Bank, exchange-rate API, dan GNews.
- Fallback cache berita saat kuota GNews habis.
- Weighted risk model: cuaca 30%, inflasi 20%, berita 40%, kurs 10%.
- Newsroom bergambar dengan lexicon-based sentiment analysis.
- Perbandingan negara dengan radar breakdown risiko.
- Multimodal route planner: truck, pesawat, dan kapal.
- Estimasi jarak, waktu, biaya, kapasitas, dan emisi CO2.
- Watchlist per pengguna dan dashboard administrator.
- Settings center untuk profil, keamanan, dan preferensi operasional.
- Report Center dengan ekspor CSV dan halaman siap cetak/Save PDF.
- REST API serta dokumentasi di `/api-docs`.

## Instalasi

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

Atur koneksi database pada `.env`. Isi `GNEWS_API_KEY` untuk berita live. Jangan commit atau menampilkan API key pada halaman aplikasi.

## Menyiapkan data

```bash
php artisan countries:import --replace-generated
php artisan ports:import-wpi --replace
php artisan ports:import-unlocode --target=12000
php artisan data:sync ID
php artisan data:sync DE
php artisan data:sync CN
php artisan data:sync AU
php artisan data:sync SG
php artisan data:sync JP
```

`data:sync --all` tersedia tetapi membutuhkan waktu dan banyak request API. Untuk presentasi, enam negara di atas sudah mencukupi.

## Akun administrator

Daftarkan akun melalui `/register`, kemudian jalankan:

```bash
php artisan admin:promote email@example.com
```

## Laporan

- Buka `/reports` dan pilih negara.
- Gunakan **Download CSV** untuk data terstruktur.
- Gunakan **Cetak / Save PDF**, pilih printer `Save as PDF` pada browser.

## Pengujian

```bash
php -l app/Http/Controllers/ReportController.php
php artisan view:cache
php artisan test
php artisan route:list
```

## Batasan penting

- Estimasi rute merupakan model operasional, bukan jadwal atau quotation carrier.
- Sentimen memakai kamus lexicon dan bukan model bahasa kontekstual.
- Negara yang belum disinkronkan dapat belum memiliki snapshot lengkap.
- GNews memiliki kuota harian; cache dipakai otomatis ketika kuota habis.
