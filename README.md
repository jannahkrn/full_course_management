# Smart Exam — Full Course Management

Laravel 12 | MySQL 8 | Sanctum Auth | Tailwind CSS | Alpine.js

Fitur Full Course Management untuk platform Smart Exam, mencakup manajemen mata kuliah (CRUD), bulk enrollment pengguna, serta ekspor/impor data dalam format CSV dan Excel.

---

## Tech Stack

| Layer      | Teknologi                                   |
|------------|---------------------------------------------|
| Backend    | PHP 8.2+, Laravel 12                        |
| Database   | MySQL 8.0                                   |
| Frontend   | Blade, Tailwind CSS 3, Alpine.js 3          |
| Auth       | Laravel Sanctum (API) + Session (Web)       |
| Import     | phpoffice/phpspreadsheet (untuk baca Excel) |

---

## Struktur Project

```
smart-exam/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                         ← REST API controllers
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── CourseController.php
│   │   │   │   ├── CourseCategoryController.php
│   │   │   │   ├── CourseSessionController.php
│   │   │   │   ├── EnrollmentController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── ActivityLogController.php
│   │   │   └── Web/Admin/                   ← Web (Blade) controllers
│   │   │       ├── CourseController.php     ← CRUD, Export, Import Courses
│   │   │       ├── EnrollmentController.php ← Bulk Enroll
│   │   │       ├── UserController.php       ← CRUD + Import Users
│   │   │       └── CourseCategoryController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── StoreCourseRequest.php
│   │       ├── UpdateCourseRequest.php
│   │       ├── BulkEnrollRequest.php
│   │       ├── EnrollUserRequest.php
│   │       └── ... (request lainnya)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── CourseCategory.php
│   │   ├── Enrollment.php
│   │   └── ... (model lainnya)
│   └── Services/
│       ├── CourseService.php       ← Logika bisnis mata kuliah
│       ├── EnrollmentService.php   ← Logika enrollment
│       └── ImportService.php       ← Parsing CSV & Excel (phpspreadsheet)
├── resources/views/
│   ├── admin/courses/
│   │   ├── index.blade.php         ← Daftar MK (dual-view + Import/Export modal)
│   │   ├── create.blade.php        ← Form tambah MK
│   │   ├── edit.blade.php          ← Form edit MK
│   │   └── show.blade.php          ← Detail MK
│   ├── admin/enrollments/
│   │   ├── add-users.blade.php     ← Bulk enrollment (chip multi-select)
│   │   └── index.blade.php         ← Daftar peserta per MK
│   └── admin/users/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
├── routes/
│   ├── web.php                     ← Web routes (Admin, Teacher, Student)
│   └── api.php                     ← API routes (Sanctum)
├── database/
│   ├── migrations/                 ← 11 file migration
│   └── seeders/DatabaseSeeder.php
└── bootstrap/app.php               ← Konfigurasi middleware (Laravel 12)
```

---

## Skema Database

| Tabel                    | Keterangan                                    |
|--------------------------|-----------------------------------------------|
| `users`                  | Akun Admin, Teacher, Student                  |
| `course_categories`      | Kategori Mata Kuliah                          |
| `courses`                | Daftar Mata Kuliah (full settings)            |
| `course_teachers`        | Pivot: course ↔ teacher (with role)           |
| `enrollments`            | Pendaftaran student ke course                 |
| `course_sessions`        | Daftar Sesi per course                        |
| `session_categories`     | Kategori Sesi                                 |
| `course_materials`       | Materi per sesi                               |
| `course_exercises`       | Latihan/ujian per sesi                        |
| `course_activity_logs`   | Activity tracking                             |
| `personal_access_tokens` | Sanctum tokens                                |

---

## Instalasi

### 1. Clone / ekstrak project

```bash
git clone <repo-url> smart-exam
cd smart-exam
```

### 2. Install dependensi PHP

```bash
composer install
```

> Library `phpoffice/phpspreadsheet` sudah tercantum di `composer.json` dan akan otomatis terinstall.

### 3. Install dependensi Node.js

```bash
npm install
npm run build
```

### 4. Konfigurasi `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME="Smart Exam"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_exam
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

### 6. Buat symlink storage

```bash
php artisan storage:link
```

### 7. Jalankan server

```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

---

## Akun Default (Seeder)

| Role    | Email                   | Password  |
|---------|-------------------------|-----------|
| Admin   | admin@smartexam.id      | password  |
| Teacher | albert@smartexam.id     | password  |
| Student | madeleine@smartexam.id  | password  |

---

## Fitur Utama

### 1. Manajemen Mata Kuliah (CRUD)
- Halaman daftar dengan **dual-view**: List Standar dan List Manajemen
- Filter lanjutan: kategori, bahasa, status, tipe akses
- Tambah, ubah, hapus (soft delete) mata kuliah
- Duplikasi mata kuliah sebagai template
- Publish / Unpublish mata kuliah

### 2. Bulk Enrollment Pengguna
- Halaman khusus dengan UI chip multi-select
- Pilih banyak pengguna dan banyak mata kuliah dalam satu operasi
- Pencegahan pendaftaran ganda otomatis

### 3. Ekspor Data
- Ekspor daftar mata kuliah ke **CSV** (UTF-8 BOM)
- Ekspor daftar mata kuliah ke **Excel** (format SpreadsheetML .xls, tanpa library tambahan)
- Filter yang aktif di halaman turut diterapkan ke hasil ekspor

### 4. Impor Data
- Impor mata kuliah massal dari file **CSV**
- Impor daftar pengguna massal dari file **CSV** atau **Excel (.xlsx/.xls)**
- Baris kosong dan duplikat dilewati (bukan error fatal)
- Detail baris yang gagal ditampilkan sebagai notifikasi

---

## Format File CSV Import Mata Kuliah

Kolom (urutan harus sesuai):

| Kolom (Index) | Nama Kolom  | Keterangan                          |
|---------------|-------------|-------------------------------------|
| 0             | ID          | Diabaikan saat import               |
| 1             | Judul       | **Wajib**                           |
| 2             | Kode        | Opsional                            |
| 3             | Kategori    | Diabaikan saat import               |
| 4             | Bahasa      | `Bahasa Indonesia` atau `English`   |

> Tip: Gunakan hasil Export CSV sebagai template import. Format kolomnya sudah sesuai.

## Format File CSV/Excel Import Pengguna

| Kolom (Index) | Nama Kolom | Keterangan                                    |
|---------------|------------|-----------------------------------------------|
| 0             | Nama       | **Wajib**                                     |
| 1             | Email      | **Wajib**, harus unik                         |
| 2             | Role       | `admin`, `teacher`, atau `student` (default)  |
| 3             | Password   | Opsional (default: `password123`)             |

---

## Pengujian API dengan curl

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@smartexam.id","password":"password"}'
```

**List courses:**
```bash
curl http://localhost:8000/api/admin/courses \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Buat course:**
```bash
curl -X POST http://localhost:8000/api/admin/courses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Pemrograman Web","code":"PW01","teacher_ids":[2]}'
```

**Bulk enroll:**
```bash
curl -X POST http://localhost:8000/api/admin/enrollments/bulk \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"user_ids":[3,4,5],"course_ids":[1,2]}'
```

---

## Pembagian Fitur (Tim)

| Anggota            | Fitur                                                      |
|--------------------|------------------------------------------------------------|
| Jannah Kurniawati  | Export CSV/Excel, Import Mata Kuliah dari CSV              |
| Rizky Argo Pradana | Course List (dual-view, filter, search), Update Course     |
| Restu Erlangga     | Tambah Pengguna (Bulk Enrollment), Import Daftar Pengguna  |

---

## Hasil Pengujian

| Metode          | Hasil                                                               |
|-----------------|---------------------------------------------------------------------|
| Black Box Test  | 14/14 skenario berhasil                                             |
| Usability Test  | Rata-rata SUS 87,3% — kategori **Good** (threshold 80%)            |
| Skor tertinggi  | Fitur Ekspor CSV/Excel: 91,3%                                       |