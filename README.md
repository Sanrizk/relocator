# Sistem Pendukung Keputusan (SPK) Pemilihan Lokasi Cabang Ritel Baru
### Integrasi Metode ROC (Rank Order Centroid) & SAW (Simple Additive Weighting)

Aplikasi web Sistem Pendukung Keputusan (SPK) berbasis **Laravel** untuk membantu manajemen dan pengambil keputusan (Pimpinan/Direksi) dalam menentukan lokasi pembangunan cabang ritel baru yang paling optimal, objektif, dan transparan.

---

## 📌 Fitur Utama

1. **Role-Based Access Control (RBAC):**
   - **Admin:** Memiliki akses penuh mengelola Kriteria, Alternatif Lokasi, Matriks Penilaian, dan melihat hasil SPK.
   - **Pimpinan:** Mengakses dashboard ringkasan eksekutif, melihat hasil rekomendasi, serta mencetak laporan PDF.
2. **Pembobotan Kriteria dengan ROC (Rank Order Centroid):**
   - Admin hanya perlu menentukan peringkat prioritas kriteria ($1, 2, \dots, N$).
   - Sistem secara otomatis menghitung bobot kriteria ($W_j$) secara matematis dan proporsional.
3. **Perankingan dengan SAW (Simple Additive Weighting):**
   - Menghitung normalisasi matriks ($R_{ij}$) untuk tipe kriteria *Benefit* dan *Cost*.
   - Menghitung nilai preferensi akhir ($V_i$) secara transparan langkah demi langkah.
4. **Matriks Penilaian Interaktif:**
   - Input nilai skor mentah setiap alternatif secara langsung melalui antarmuka tabel dinamis.
5. **Dashboard & Visualisasi Hasil:**
   - Ringkasan top rekomendasi lokasi cabang ritel terbaik.
6. **Cetak Laporan PDF:**
   - Laporan resmi siap cetak / simpan ke PDF lengkap dengan tabel perankingan, bobot, normalisasi, serta lembar tanda tangan pimpinan.

---

## 🧮 Rumus & Metode yang Digunakan

### 1. Metode ROC (Rank Order Centroid)
$$W_j = \frac{1}{N} \sum_{k=j}^{N} \frac{1}{k}$$
*Di mana:*
- $N$ = Total jumlah kriteria.
- $j$ = Nomor urutan peringkat kriteria ($1 \le j \le N$).
- Kriteria peringkat 1 memiliki bobot paling besar, dan $\sum W_j = 1$.

### 2. Metode SAW (Simple Additive Weighting)
- **Normalisasi Matriks ($R_{ij}$):**
  - Kriteria **Benefit**:
    $$R_{ij} = \frac{X_{ij}}{\max(X_j)}$$
  - Kriteria **Cost**:
    $$R_{ij} = \frac{\min(X_j)}{X_{ij}}$$
- **Nilai Preferensi ($V_i$):**
  $$V_i = \sum_{j=1}^{n} W_j \cdot R_{ij}$$
- **Perankingan:** Alternatif dengan nilai $V_i$ tertinggi merupakan rekomendasi cabang ritel terbaik.

---

## 🛠️ Kebutuhan Sistem (Prerequisites)

- **PHP:** Versi 8.2 atau lebih baru (disarankan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`).
- **Composer:** Versi 2.x
- **Database:** MySQL 5.7+ / MariaDB 10.3+ (atau SQLite).
- **Web Browser Modern** (Chrome, Firefox, Edge, Safari).

---

## 🚀 Panduan Instalasi (Step-by-Step)

### 1. Clone Repositori
```bash
git clone https://github.com/username/relocator.git
cd relocator
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi contoh:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan pengaturan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_spk_ritel
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan:** Pastikan database `db_spk_ritel` telah dibuat di MySQL/phpMyAdmin Anda:
> ```sql
> CREATE DATABASE db_spk_ritel;
> ```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi & Database Seeder
Perintah ini akan membuat semua tabel dan mengisi data awal (akun pengguna, kriteria contoh, alternatif ritel, dan penilaian sampel):
```bash
php artisan migrate --seed
```

### 6. Jalankan Local Server
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser di:
👉 **`http://127.0.0.1:8000`**

---

## 🔑 Akun Default (Demo)

Gunakan kredensial berikut untuk masuk ke sistem:

| Role | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@ritel.com` | `password` | CRUD Kriteria, Alternatif, Matriks Nilai, Hasil SPK, Cetak PDF |
| **Pimpinan** | `pimpinan@ritel.com` | `password` | Dashboard, Hasil Rekomendasi SAW, Cetak PDF |

---

## 📖 Alur & Tata Cara Penggunaan

### 1. Login
Masuk melalui halaman login menggunakan akun **Admin** atau **Pimpinan**.

### 2. Kelola Kriteria (Hanya Admin)
- Buka menu **Kriteria (ROC)**.
- Tambah atau sesuaikan kriteria penilaian:
  - **Nama Kriteria**: misal *Kepadatan Penduduk*, *Biaya Sewa Lahan*, *Aksesibilitas*.
  - **Jenis / Atribut**: 
    - `Benefit` jika semakin tinggi nilai semakin baik.
    - `Cost` jika semakin rendah nilai semakin baik (contoh: biaya sewa).
  - **Peringkat**: Tentukan urutan prioritas (Peringkat 1 adalah kriteria paling penting).
- Klik tombol **"Hitung Ulang Bobot ROC"** untuk memperbarui bobot otomatis sesuai formula matematis ROC.

### 3. Kelola Alternatif Lokasi (Hanya Admin)
- Buka menu **Alternatif Lokasi**.
- Daftarkan calon lokasi cabang ritel baru beserta alamat lengkapnya.

### 4. Isi Matriks Penilaian (Hanya Admin)
- Buka menu **Matriks Penilaian**.
- Masukkan nilai skor mentah dari masing-masing alternatif lokasi untuk setiap kriteria.
- Klik **"Simpan Matriks Penilaian"**.

### 5. Lihat Proses & Hasil Keputusan (Admin & Pimpinan)
- Buka menu **Hasil & Rekomendasi (SAW)**.
- Sistem akan menampilkan:
  1. **Tabel Rekomendasi Utama & Perankingan:** Peringkat 1 dengan nilai preferensi ($V_i$) tertinggi ditandai sebagai rekomendasi utama.
  2. **Tabel Bobot Kriteria ROC ($W_j$)**.
  3. **Tabel Matriks Normalisasi ($R_{ij}$)**.
  4. **Tabel Matriks Terbobot ($W_j \times R_{ij}$)**.

### 6. Cetak Laporan PDF
- Klik tombol **"Cetak / Export PDF"** di halaman hasil SPK.
- Gunakan fitur print browser (`Ctrl + P` atau tombol yang disediakan) untuk mencetak langsung atau memilih opsi *Save as PDF*.

---

## 🧪 Menjalankan Pengujian Otomatis (Tests)

Proyek ini telah dilengkapi dengan Unit & Feature Test (menggunakan Pest PHP):
```bash
php artisan test
```

---

## 📁 Struktur Direktori Penting

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AlternatifController.php   # Manajemen Alternatif
│   │   ├── AuthController.php         # Autentikasi Login/Logout
│   │   ├── DashboardController.php    # Ringkasan Eksekutif
│   │   ├── KriteriaController.php     # Manajemen Kriteria & ROC
│   │   ├── PenilaianController.php    # Matriks Input Penilaian
│   │   └── SpkController.php          # Output Perhitungan SAW & Cetak PDF
│   └── Middleware/
│       └── RoleMiddleware.php         # Penjaga Hak Akses RBAC
├── Models/
│   ├── Alternatif.php
│   ├── HasilSpk.php
│   ├── Kriteria.php
│   ├── Penilaian.php
│   └── User.php
└── Services/
    └── SpkService.php                 # Service Inti Logika Algoritma ROC & SAW
database/
├── migrations/                        # Skema tabel database
└── seeders/DatabaseSeeder.php         # Data inisialisasi akun & sampel data
resources/views/                       # Tampilan Blade & Tailwind CSS UI
tests/Feature/SpkCalculationTest.php   # Pengujian matematis ROC & SAW
```

---

## 📄 Lisensi
Proyek ini bersifat *open-source* di bawah lisensi [MIT License](LICENSE).
