# Portal ujian SMPN 11 Singkawang

Laravel 13 + Filament 5. Project ini masih **tahap pengembangan / hardening**, bukan aplikasi produksi yang sudah diaudit. API dan aplikasi siswa Android, anti-curang, audit keamanan menyeluruh, peran rinci per guru, dan integrasi Google Forms belum selesai. Status ujian dan kode kelas **belum membatasi Google Forms**.

## Prasyarat dan uji lokal

PHP 8.4, Composer, PHP extensions intl, mbstring, XML, curl, zip, PDO SQLite dan PDO PostgreSQL.

```sh
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan filament:assets
php artisan db:seed  # HANYA local/testing; jangan pernah production
php artisan serve
php artisan test
```

Demo lokal: `guru@demo.test` / `DemoGuru2026!`. Saat login pertama, daftarkan aplikasi autentikator dan simpan recovery codes. Seeder diblokir di production. Jangan pakai akun demo atau data siswa asli pada preview.

## Keamanan dasar yang sudah ditambahkan

- Akun guru memerlukan `is_teacher=true`; semua akun guru saat ini memiliki hak admin yang sama.
- Filament mewajibkan MFA aplikasi autentikator (TOTP) dengan recovery code. Secret dan recovery code dienkripsi oleh Laravel; lindungi dan backup `APP_KEY` secara rahasia dan jangan mengubahnya sembarangan.
- Kata sandi siswa disimpan sebagai hash. Kode ujian dibuat secara acak per kelas pada server.
- Migrasi PostgreSQL mengaktifkan RLS pada tabel project dan mencabut akses `PUBLIC`, `anon`, serta `authenticated`; tanpa policy untuk client. Koneksi server yang menjadi pemilik tabel / punya BYPASSRLS tetap melewati RLS: gunakan kredensial khusus server, bukan browser, dan rancang hak database seminimal mungkin. Data API yang tidak diperlukan sebaiknya dimatikan.
- Catatan aktivitas admin dasar untuk pembuatan/perubahan/penghapusan siswa dan ujian. Tidak mencatat kata sandi maupun nilai. Log ini belum mencatat login, kejadian di luar Eloquent, atau aktivitas Android; belum tahan pengubahan oleh administrator database dan belum ada retensi otomatis.
- Autentikasi dan validasi backend, proteksi CSRF dari framework. Pengujian otomatis lokal tidak membuktikan sistem bebas kerentanan.

## Database Supabase

Gunakan project khusus yang kosong. Skema dapat dibuat dengan `php artisan migrate --force` dari lingkungan tepercaya, atau SQL inisialisasi yang dikirim terpisah (`supabase-smpn11-terbaru.sql`) melalui SQL Editor. **Pilih hanya satu metode**, jangan gabungkan atau jalankan berulang. Backup terlebih dahulu, periksa skema dan hak akses sesudahnya. SQL telah diuji pada PostgreSQL lokal, belum pada Supabase Cloud milik Anda.

Jika menggunakan CLI migration: siapkan `.env` pribadi dengan koneksi PostgreSQL, misalnya Supabase session pooler atau koneksi langsung untuk migrasi, lalu `php artisan migrate --force`. Untuk aplikasi serverless, konsultasikan dokumentasi Supabase tentang transaction pooler dan uji kompatibilitas prepared statements, sesi, serta beban. Jangan menaruh URL database atau password dalam source, command history, GitHub, log, atau chat.

Buat guru secara interaktif dari mesin tepercaya yang terkoneksi database:

```sh
php artisan school:create-teacher
```

Kata sandi ditanyakan secara tersembunyi. Selalu atur MFA segera setelah login pertama di jaringan tepercaya; proses pendaftaran awal MFA tidak melindungi akun sebelum MFA didaftarkan. Belum ada halaman pemulihan akun administratif; simpan recovery code dengan aman. Jangan jalankan `db:seed` di production.

## GitHub dan Vercel: percobaan, belum terverifikasi end-to-end

Unggah isi root project ke repo private. Jangan unggah `.env`, SQLite, `vendor`, log atau kredensial. Perubahan project ini memastikan aset `public/css/filament`, `public/js/filament`, `public/fonts/filament` **tidak dikecualikan** oleh `.gitignore`; wajib ada pada repository untuk deployment. `composer.lock` dan `vercel.json` wajib ada.

Konfigurasi `vercel.json` memakai `vercel-php@0.8.0` (runtime komunitas, bukan runtime resmi Vercel). **Belum diuji di akun Vercel atau project Supabase Anda**, dan belum direkomendasikan untuk ujian nyata. Impor repository ke Vercel, framework Other; tidak perlu `npm run build`. Aset Filament disertakan di repo. Environment variables yang diperlukan, diatur pribadi di Vercel:

- `APP_ENV=production`, `APP_DEBUG=false`, `APP_LOCALE=id`, `APP_URL=https://domain-anda`, `APP_KEY` acak dan rahasia.
- `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_SSLMODE=require`; gunakan connection/pooler yang sesuai. Jangan masukkan `DB_URL` bersamaan jika memakai variabel terpisah.
- `SESSION_DRIVER=database`, `SESSION_SECURE_COOKIE=true`, `CACHE_STORE=database`, `QUEUE_CONNECTION=sync`, `LOG_CHANNEL=stderr`.
- `TRUSTED_PROXIES` harus sesuai proxy tepercaya. Jangan gunakan `*` kecuali akses langsung ke origin ditutup dan semua forwarded headers disaring oleh proxy tepercaya. Salah konfigurasi dapat membuat identitas IP atau URL mudah dipalsukan.

`/tmp` digunakan untuk compiled views yang bersifat sementara; database harus eksternal. Lakukan uji deployment end-to-end, RLS dengan role anon dan authenticated, MFA/login rate limiting, beban, pemulihan backup, keamanan, privasi dan izin penggunaan data anak sebelum penggunaan sekolah. Periksa `composer audit`, security advisor, patch dan dependencies secara berkala. Filament perlu aset CSS/JS yang dihasilkan paket; browser tetap memerlukan HTML/CSS meskipun panel dikonfigurasi dengan PHP.
