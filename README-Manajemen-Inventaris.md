# GudangKu — Sistem Manajemen Inventaris Gudang

Aplikasi web untuk membantu pengelolaan inventaris gudang, mulai dari pencatatan kategori dan produk, pemantauan stok, transaksi barang masuk/keluar, hingga pembuatan laporan inventaris.

## 1. Informasi Proyek

| Keterangan | Detail |
|---|---|
| Nama aplikasi | GudangKu — Sistem Manajemen Inventaris Gudang |
| Framework | Laravel 13 |
| Bahasa pemrograman | PHP 8.3 |
| Database | MySQL |
| Lingkungan pengembangan | Laragon |
| Antarmuka | Laravel Blade, CSS, Vite |
| Pola aplikasi | Model-View-Controller (MVC) |

## 2. Fitur Aplikasi

### Autentikasi dan hak akses
- Registrasi akun.
- Login dan logout.
- Pengelolaan sesi login.
- Pembatasan akses berdasarkan role `admin` dan `staff`.
- Halaman internal hanya dapat diakses setelah login.

### Dashboard
- Menampilkan jumlah produk.
- Menampilkan jumlah kategori.
- Menampilkan total unit stok.
- Menampilkan jumlah produk dengan stok menipis.
- Menampilkan daftar produk yang perlu diperhatikan.
- Menampilkan transaksi stok terbaru.

### Manajemen kategori
- Melihat daftar kategori.
- Menambahkan kategori.
- Mengubah kategori.
- Menghapus kategori yang belum digunakan oleh produk.

### Manajemen produk / inventaris
- Melihat daftar produk beserta kategori.
- Menambahkan produk.
- Mengubah data produk.
- Menghapus produk yang belum memiliki riwayat transaksi.
- Menyimpan SKU, nama produk, deskripsi, harga, stok, dan stok minimum.

### Transaksi stok
- Mencatat barang masuk.
- Mencatat barang keluar.
- Memperbarui jumlah stok produk secara otomatis.
- Menolak transaksi barang keluar apabila jumlahnya melebihi stok yang tersedia.
- Menyimpan petugas, waktu, jumlah, jenis transaksi, dan catatan.
- Melihat riwayat transaksi.

### Laporan inventaris
- Ringkasan jumlah produk, kategori, total stok, stok menipis, nilai persediaan, dan transaksi.
- Filter riwayat transaksi berdasarkan tanggal awal dan tanggal akhir.
- Laporan stok saat ini.
- Export laporan stok ke CSV.
- Export riwayat transaksi ke CSV.

**Catatan filter:** filter tanggal digunakan untuk ringkasan dan riwayat transaksi. Data stok pada laporan merupakan kondisi stok saat ini, bukan rekonstruksi stok pada tanggal lampau.

## 3. Role Pengguna

| Role | Hak akses |
|---|---|
| Admin | Melihat dashboard, inventaris, kategori, transaksi, dan laporan; mengelola (tambah, ubah, hapus) kategori serta produk. |
| Staff | Melihat dashboard, inventaris, kategori, transaksi, dan laporan; mencatat transaksi stok. |

Pembatasan akses dilakukan melalui middleware role pada route aplikasi.

## 4. Akun Tester

Gunakan akun berikut untuk pengujian pada database/demo yang telah disiapkan:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@gmail.com` | `Password123` |
| Staff | `kevin.staff@gmail.com` | `Password123` |

> **Penting:** akun di atas adalah akun pengujian yang diberikan untuk aplikasi/demo. Akun tersebut tidak dibuat otomatis oleh migration atau `DatabaseSeeder.php` pada source code ini. Pada instalasi database baru, buat akun terlebih dahulu atau masukkan akun tester ke database, lalu pastikan kolom `users.role` berisi `admin` atau `staff` sesuai akun. Jangan gunakan password demo ini untuk akun produksi.

## 5. Persyaratan Sistem

Sebelum menjalankan aplikasi, siapkan:
- PHP 8.3 atau versi yang sesuai dengan kebutuhan Laravel 13.
- Composer.
- MySQL dan phpMyAdmin melalui Laragon.
- Node.js dan npm (dibutuhkan jika ingin menjalankan atau membangun asset Vite).
- Web browser.
- Visual Studio Code atau editor lain (opsional).

Pastikan ekstensi PHP yang dibutuhkan Laravel, termasuk `pdo_mysql`, aktif pada konfigurasi PHP Laragon.

## 6. Instalasi dan Menjalankan Aplikasi di Laragon

### Langkah 1 — Siapkan source code
1. Ekstrak folder proyek ke direktori Laragon, misalnya `C:\laragon\www\Manajemen-Inventaris-uas`.
2. Buka folder proyek tersebut melalui Visual Studio Code.
3. Buka Terminal di folder utama proyek.

### Langkah 2 — Buat database MySQL
1. Jalankan Laragon dan aktifkan layanan Apache/Nginx serta MySQL.
2. Buka phpMyAdmin atau database client.
3. Buat database baru, misalnya `manajemen_inventaris`.
4. Gunakan nama database tersebut pada konfigurasi `.env`.

### Langkah 3 — Instal dependency PHP
Jalankan:

```bash
composer install
```

### Langkah 4 — Siapkan file environment
Salin `.env.example` menjadi `.env`.

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Windows Command Prompt:

```cmd
copy .env.example .env
```

Buka `.env`, lalu sesuaikan konfigurasi aplikasi dan database MySQL. Contoh:

```dotenv
APP_NAME="GudangKu"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manajemen_inventaris
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi MySQL Laragon di komputer masing-masing. Pada instalasi Laragon tertentu, user default dapat berupa `root` dengan password kosong, tetapi pastikan kembali konfigurasi lokal yang digunakan.

**Catatan:** `.env.example` pada source code awal menggunakan SQLite sebagai konfigurasi default. Untuk menjalankan proyek ini dengan MySQL Laragon, ubah `DB_CONNECTION` menjadi `mysql` dan isi pengaturan database seperti contoh di atas.

### Langkah 5 — Generate application key

```bash
php artisan key:generate
```

### Langkah 6 — Jalankan migration

```bash
php artisan migrate
```

Perintah tersebut membuat tabel aplikasi berdasarkan file migration, antara lain:
- `users`
- `categories`
- `products`
- `stock_transactions`

Jangan gunakan `php artisan migrate:fresh` pada database yang berisi data penting karena perintah tersebut menghapus tabel sebelum membuatnya kembali.

### Langkah 7 — Instal dan build asset frontend (jika diperlukan)

```bash
npm install
npm run build
```

Untuk pengembangan frontend, gunakan:

```bash
npm run dev
```

### Langkah 8 — Jalankan aplikasi

```bash
php artisan serve
```

Buka alamat berikut di browser:

```text
http://127.0.0.1:8000
```

Jika menggunakan virtual host Laragon, akses URL sesuai konfigurasi virtual host yang dibuat.

## 7. Menyiapkan Akun pada Database Baru

Akun tester pada bagian 4 tidak dibuat oleh seeder bawaan proyek. Pada database baru, lakukan registrasi melalui halaman aplikasi atau buat akun melalui mekanisme administrasi yang aman.

Registrasi pada aplikasi menetapkan role default `staff`. Untuk memberikan role admin kepada akun yang sudah terdaftar, jalankan:

```bash
php artisan tinker
```

Kemudian di prompt Tinker:

```php
$user = \App\Models\User::where('email', 'admin@gmail.com')->first();
$user->role = 'admin';
$user->save();
```

Pastikan akun tersebut sudah ada sebelum menjalankan perintah. Untuk akun staff, pastikan nilai role adalah `staff`:

```php
$user = \App\Models\User::where('email', 'kevin.staff@gmail.com')->first();
$user->role = 'staff';
$user->save();
```

Keluar dari Tinker:

```php
exit
```

Untuk kebutuhan pengujian, password akun dapat diatur melalui proses reset/password yang aman atau dibuat saat registrasi. Hindari menuliskan password asli ke source code, seeder publik, atau repository.

## 8. Struktur Direktori Utama

```text
Manajemen-Inventaris-uas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   └── Models/
├── bootstrap/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   └── views/
│       ├── auth/
│       ├── categories/
│       ├── layouts/
│       ├── products/
│       ├── reports/
│       ├── transactions/
│       └── dashboard.blade.php
├── routes/
│   └── web.php
├── .env.example
├── artisan
├── composer.json
└── package.json
```

## 9. Gambaran Database

Tabel utama dan relasinya:

- `users`: menyimpan data pengguna dan role.
- `categories`: menyimpan kategori produk.
- `products`: menyimpan produk dan terhubung ke kategori melalui `category_id`.
- `stock_transactions`: menyimpan transaksi stok dan terhubung ke produk melalui `product_id`, serta pengguna melalui `user_id`.

Relasi utama:
- Satu kategori dapat memiliki banyak produk.
- Satu produk dapat memiliki banyak transaksi stok.
- Satu pengguna dapat memiliki banyak transaksi stok.

### 9.1 Entity Relationship Diagram (ERD)

ERD berikut menggambarkan hubungan antar tabel utama pada aplikasi GudangKu. Diagram menggunakan sintaks Mermaid dan dapat ditampilkan langsung oleh GitHub pada file Markdown.

erDiagram
    CATEGORIES ||--o{ PRODUCTS : memiliki
    PRODUCTS ||--o{ STOCK_TRANSACTIONS : memiliki
    USERS ||--o{ STOCK_TRANSACTIONS : mencatat

    USERS {
        bigint id PK
        string name
        string email
        string password
        string role
    }

    CATEGORIES {
        bigint id PK
        string name
        text description
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        string sku
        string name
        text description
        decimal price
        integer stock
        integer minimum_stock
    }

    STOCK_TRANSACTIONS {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        string type
        integer quantity
        text notes
    }
    
### 9.2 Penjelasan Relasi

| Relasi | Kardinalitas | Penjelasan |
|---|---|---|
| `categories` → `products` | One-to-Many (1:N) | Satu kategori dapat memiliki banyak produk. Setiap produk terhubung ke kategori melalui `category_id`. |
| `products` → `stock_transactions` | One-to-Many (1:N) | Satu produk dapat memiliki banyak catatan transaksi stok. Setiap transaksi terhubung ke produk melalui `product_id`. |
| `users` → `stock_transactions` | One-to-Many (1:N) | Satu pengguna dapat mencatat banyak transaksi stok. Setiap transaksi menyimpan pengguna melalui `user_id`. |

### 9.3 Keterangan Primary Key dan Foreign Key

| Tabel | Primary Key | Foreign Key |
|---|---|---|
| `users` | `id` | - |
| `categories` | `id` | - |
| `products` | `id` | `category_id` → `categories.id` |
| `stock_transactions` | `id` | `product_id` → `products.id`; `user_id` → `users.id` |

### 9.4 Alur Data

1. Admin membuat kategori produk, misalnya Laptop.
2. Admin menambahkan produk dan menghubungkannya dengan kategori melalui `category_id`.
3. Admin atau staff yang memiliki akses mencatat transaksi barang masuk atau barang keluar.
4. Sistem menyimpan transaksi dengan menghubungkannya ke produk dan pengguna yang sedang login.
5. Stok produk diperbarui berdasarkan jenis dan jumlah transaksi.

**Catatan:** Diagram ini mengikuti tabel dan relasi yang dijelaskan pada README. Untuk dokumentasi final yang sepenuhnya identik dengan database, cocokkan kembali nama kolom dan tipe datanya dengan file migration pada folder `database/migrations`.

## 10. Route / Halaman Utama

| Halaman | URL |
|---|---|
| Login | `/login` |
| Register | `/register` |
| Dashboard | `/dashboard` |
| Inventaris | `/inventaris` |
| Kategori | `/kategori` |
| Transaksi stok | `/transaksi` |
| Tambah transaksi | `/transaksi/tambah` |
| Laporan inventaris | `/laporan` |
| Export laporan stok | `/laporan/export/stok` |
| Export laporan transaksi | `/laporan/export/transaksi` |

Route yang memerlukan autentikasi hanya dapat diakses setelah pengguna login. Route pengelolaan produk dan kategori dibatasi untuk role admin.

## 11. Pengujian Manual

Skenario pengujian dasar yang dapat dilakukan:

1. Login menggunakan akun admin dan staff.
2. Pastikan dashboard menampilkan ringkasan inventaris.
3. Sebagai admin, tambahkan kategori dan produk.
4. Ubah data produk dan kategori.
5. Sebagai staff, buka inventaris dan riwayat transaksi.
6. Catat transaksi barang masuk dan periksa apakah stok bertambah.
7. Catat transaksi barang keluar dan periksa apakah stok berkurang.
8. Coba barang keluar melebihi stok dan pastikan transaksi ditolak.
9. Buka laporan, gunakan filter tanggal, lalu export CSV.
10. Coba akses URL khusus admin menggunakan akun staff dan pastikan akses ditolak.

## 12. Keamanan dan Catatan Pengembangan

- Jangan membagikan file `.env` karena berisi konfigurasi dan kemungkinan kredensial lokal.
- Jangan commit `.env` ke repository. Gunakan `.env.example` yang berisi placeholder.
- Password pengguna disimpan menggunakan mekanisme hashing Laravel.
- Form Laravel menggunakan CSRF protection melalui mekanisme Blade/form Laravel.
- Validasi input dilakukan di controller.
- Role middleware digunakan untuk membatasi akses halaman.
- Transaksi perubahan stok dijalankan dalam database transaction dan mengunci baris produk agar perubahan stok lebih aman ketika terjadi permintaan bersamaan.
- Ganti password akun demo sebelum aplikasi dipakai di lingkungan publik/produksi.

## 13. Perintah Artisan yang Berguna

```bash
# Melihat daftar route
php artisan route:list

# Melihat status migration
php artisan migrate:status

# Menjalankan migration
php artisan migrate

# Membersihkan cache aplikasi
php artisan optimize:clear

# Membersihkan cache view
php artisan view:clear

# Menjalankan aplikasi lokal
php artisan serve
```

## 14. Pengembang

**Nama Proyek:** GudangKu — Sistem Manajemen Inventaris Gudang  
**Mata Kuliah:** Web Application Development (UAS)  
**Institusi:** Universitas Cakrawala

---

README ini menjelaskan konfigurasi dan fitur yang terdapat pada source code proyek. Konfigurasi server, akun, serta data produk dapat berbeda antara database lokal dan database demo yang digunakan saat presentasi.
