# Smart Exam — Full Course Management API Documentation

Laravel 12 | MySQL 8 | Sanctum Auth

---

## Tech Stack

| Layer    | Teknologi                         |
|----------|-----------------------------------|
| Backend  | PHP 8.2+, Laravel 12              |
| Database | MySQL 8.0                         |
| Auth     | Laravel Sanctum (Bearer Token)    |

---

## Struktur Project (API Layer)

```
smart-exam/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── CourseController.php
│   │   │   ├── CourseCategoryController.php
│   │   │   ├── CourseSessionController.php
│   │   │   ├── EnrollmentController.php
│   │   │   ├── UserController.php
│   │   │   └── ActivityLogController.php
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── StoreCourseRequest.php
│   │       ├── UpdateCourseRequest.php
│   │       ├── BulkEnrollRequest.php
│   │       ├── EnrollUserRequest.php
│   │       ├── StoreCourseCategoryRequest.php
│   │       ├── UpdateCourseCategoryRequest.php
│   │       ├── StoreCourseSessionRequest.php
│   │       ├── UpdateCourseSessionRequest.php
│   │       ├── LoginRequest.php
│   │       └── RegisterRequest.php
│   │   └── Resources/
│   │       ├── UserResource.php
│   │       ├── CourseResource.php
│   │       ├── CourseCategoryResource.php
│   │       ├── CourseSessionResource.php
│   │       ├── CourseMaterialResource.php
│   │       ├── CourseExerciseResource.php
│   │       └── EnrollmentResource.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── CourseCategory.php
│   │   ├── CourseSession.php
│   │   ├── CourseMaterial.php
│   │   ├── CourseExercise.php
│   │   ├── Enrollment.php
│   │   ├── SessionCategory.php
│   │   └── CourseActivityLog.php
│   └── Services/
│       ├── CourseService.php
│       ├── EnrollmentService.php
│       └── ImportService.php
├── database/
│   ├── migrations/          (11 migration files)
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   ├── api.php              ← API routes (Sanctum)
│   └── web.php              ← Web routes (Admin/Teacher/Student)
└── bootstrap/
    └── app.php              ← Middleware registration (Laravel 12 style)
```

---

## Skema Database

| Tabel                    | Keterangan                                   |
|--------------------------|----------------------------------------------|
| `users`                  | Akun Admin, Teacher, Student                 |
| `course_categories`      | Kategori Mata Kuliah                         |
| `courses`                | Daftar Mata Kuliah (full settings)           |
| `course_teachers`        | Pivot: course ↔ teacher (with role)          |
| `enrollments`            | Pendaftaran student ke course                |
| `course_sessions`        | Daftar Sesi per course                       |
| `session_categories`     | Kategori Sesi                                |
| `course_materials`       | Materi per sesi                              |
| `course_exercises`       | Latihan/ujian per sesi                       |
| `course_activity_logs`   | Activity tracking                            |
| `personal_access_tokens` | Sanctum tokens                               |

---

## Instalasi

### 1. Clone atau ekstrak project

```bash
git clone <repo-url> smart-exam
cd smart-exam
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Konfigurasi `.env`

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

### 4. Konfigurasi Middleware (Laravel 12)

Di Laravel 12, middleware alias didaftarkan di `bootstrap/app.php`, **bukan** di `Kernel.php`.
File ini sudah dikonfigurasi dalam project:

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

Tidak perlu mengubah file apapun — konfigurasi sudah tersedia.

### 5. Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

### 6. Jalankan server

```bash
php artisan serve
```

---

## Akun Default (Seeder)

| Role    | Email                   | Password  |
|---------|-------------------------|-----------|
| Admin   | admin@smartexam.id      | password  |
| Teacher | albert@smartexam.id     | password  |
| Student | madeleine@smartexam.id  | password  |

---

## Autentikasi

Semua endpoint (kecuali login dan register) membutuhkan header:

```
Authorization: Bearer {token}
```

Token diperoleh dari endpoint login.

---

## Daftar Endpoint API

### Auth

| Method | Endpoint           | Deskripsi              | Auth |
|--------|--------------------|------------------------|------|
| POST   | /api/auth/register | Daftar akun baru       | Tidak |
| POST   | /api/auth/login    | Login, dapatkan token  | Tidak |
| POST   | /api/auth/logout   | Logout, revoke token   | Ya   |
| GET    | /api/auth/me       | Data user yang login   | Ya   |

---

### Kategori (Publik)

| Method | Endpoint                    | Deskripsi               | Auth |
|--------|-----------------------------|-------------------------|------|
| GET    | /api/categories             | Daftar semua kategori   | Tidak |
| GET    | /api/categories/{id}        | Detail kategori         | Tidak |

---

### Admin — Kategori

| Method | Endpoint                    | Deskripsi               | Role  |
|--------|-----------------------------|-------------------------|-------|
| POST   | /api/admin/categories       | Buat kategori baru      | Admin |
| PUT    | /api/admin/categories/{id}  | Update kategori         | Admin |
| DELETE | /api/admin/categories/{id}  | Hapus kategori          | Admin |

---

### Admin — Users

| Method | Endpoint                        | Deskripsi                         | Role  |
|--------|---------------------------------|-----------------------------------|-------|
| GET    | /api/admin/users                | Daftar semua user (paginated)     | Admin |
| POST   | /api/admin/users                | Buat user baru                    | Admin |
| GET    | /api/admin/users/{id}           | Detail user                       | Admin |
| PUT    | /api/admin/users/{id}           | Update user                       | Admin |
| DELETE | /api/admin/users/{id}           | Hapus user                        | Admin |
| GET    | /api/admin/teachers             | Daftar user dengan role teacher   | Admin |
| GET    | /api/admin/users/{id}/courses   | Daftar course yang diikuti user   | Admin |

---

### Admin — Courses

| Method | Endpoint                           | Deskripsi                             | Role  |
|--------|------------------------------------|---------------------------------------|-------|
| GET    | /api/admin/courses                 | List Standar (dengan filter)          | Admin |
| GET    | /api/admin/courses/management      | List Manajemen (teacher, last access) | Admin |
| POST   | /api/admin/courses                 | Buat course baru                      | Admin |
| GET    | /api/admin/courses/{id}            | Detail course                         | Admin |
| PUT    | /api/admin/courses/{id}            | Update course (semua field)           | Admin |
| PATCH  | /api/admin/courses/{id}            | Update course (sebagian field)        | Admin |
| DELETE | /api/admin/courses/{id}            | Hapus course (soft delete)            | Admin |
| POST   | /api/admin/courses/{id}/duplicate  | Duplikasi course                      | Admin |
| POST   | /api/admin/courses/{id}/publish    | Publish course                        | Admin |
| POST   | /api/admin/courses/{id}/unpublish  | Unpublish course                      | Admin |

**Query parameters untuk GET /api/admin/courses:**

| Parameter        | Tipe    | Keterangan                                      |
|------------------|---------|-------------------------------------------------|
| keyword          | string  | Cari berdasarkan judul atau kode                |
| title            | string  | Filter judul (LIKE)                             |
| code             | string  | Filter kode (LIKE)                              |
| category_id      | integer | Filter kategori                                 |
| language         | string  | `id` atau `en`                                  |
| access_type      | string  | Tipe akses course                               |
| is_registered    | boolean | Filter terdaftar (1/0)                          |
| is_allowed       | boolean | Filter akses terbatas (1/0)                     |
| allow_unsubscribe| boolean | Filter izin berhenti langganan (1/0)            |
| sort             | string  | Kolom untuk sorting (default: `created_at`)     |
| direction        | string  | `asc` atau `desc` (default: `desc`)             |
| per_page         | integer | Jumlah item per halaman (default: 10)           |

**Body untuk POST/PUT /api/admin/courses:**

```json
{
  "title": "Pemrograman Web",
  "code": "PW01",
  "category_id": 1,
  "language": "id",
  "teacher_ids": [2, 3],
  "description": "Deskripsi mata kuliah",
  "access_type": "open",
  "is_registered": true,
  "is_allowed": false,
  "allow_unsubscribe": true,
  "video_url": "https://youtube.com/..."
}
```

---

### Admin — Enrollments

| Method | Endpoint                                          | Deskripsi                                 | Role  |
|--------|---------------------------------------------------|-------------------------------------------|-------|
| GET    | /api/admin/courses/{id}/enrollments               | Daftar peserta di course tertentu         | Admin |
| POST   | /api/admin/courses/{id}/enrollments               | Daftarkan users ke course ini             | Admin |
| DELETE | /api/admin/courses/{id}/enrollments/{userId}      | Keluarkan user dari course                | Admin |
| PATCH  | /api/admin/courses/{id}/enrollments/{userId}/complete | Tandai enrollment sebagai selesai     | Admin |
| POST   | /api/admin/enrollments/bulk                       | Bulk enroll: banyak user ke banyak course | Admin |

**Body untuk POST /api/admin/courses/{id}/enrollments:**

```json
{
  "user_ids": [3, 4, 5]
}
```

**Body untuk POST /api/admin/enrollments/bulk:**

```json
{
  "user_ids": [3, 4, 5],
  "course_ids": [1, 2, 3]
}
```

---

### Admin — Sessions

| Method | Endpoint                                          | Deskripsi                  | Role  |
|--------|---------------------------------------------------|----------------------------|-------|
| GET    | /api/admin/courses/{id}/sessions                  | Daftar sesi course         | Admin |
| POST   | /api/admin/courses/{id}/sessions                  | Tambah sesi baru           | Admin |
| GET    | /api/admin/courses/{id}/sessions/{sessionId}      | Detail sesi                | Admin |
| PUT    | /api/admin/courses/{id}/sessions/{sessionId}      | Update sesi                | Admin |
| DELETE | /api/admin/courses/{id}/sessions/{sessionId}      | Hapus sesi                 | Admin |
| POST   | /api/admin/courses/{id}/sessions/reorder          | Urutkan ulang sesi         | Admin |

---

### Teacher

| Method | Endpoint                                        | Deskripsi                          | Role           |
|--------|-------------------------------------------------|------------------------------------|----------------|
| GET    | /api/teacher/courses/catalog                    | Daftar course yang diampu guru     | Admin, Teacher |
| GET    | /api/teacher/courses/{id}                       | Detail course                      | Admin, Teacher |
| GET    | /api/teacher/courses/{id}/sessions              | Daftar sesi                        | Admin, Teacher |
| POST   | /api/teacher/courses/{id}/sessions              | Tambah sesi                        | Admin, Teacher |
| PUT    | /api/teacher/courses/{id}/sessions/{sessionId}  | Update sesi                        | Admin, Teacher |
| DELETE | /api/teacher/courses/{id}/sessions/{sessionId}  | Hapus sesi                         | Admin, Teacher |
| POST   | /api/teacher/courses/{id}/sessions/reorder      | Urutkan ulang sesi                 | Admin, Teacher |
| GET    | /api/teacher/courses/{id}/enrollments           | Daftar peserta di course           | Admin, Teacher |

---

### Student

| Method | Endpoint                                | Deskripsi                           | Role                    |
|--------|-----------------------------------------|-------------------------------------|-------------------------|
| GET    | /api/student/courses                    | Daftar course yang diikuti          | Admin, Teacher, Student |
| GET    | /api/student/courses/{id}/detail        | Detail course (materi + latihan)    | Admin, Teacher, Student |
| DELETE | /api/student/courses/{id}/unenroll      | Berhenti dari course                | Admin, Teacher, Student |
| GET    | /api/student/enrollments                | Semua enrollment user ini           | Admin, Teacher, Student |
| GET    | /api/student/activity/recent            | Course yang baru dikunjungi         | Admin, Teacher, Student |
| GET    | /api/student/activity/history           | Riwayat aktivitas course            | Admin, Teacher, Student |

---

### Activity Log

| Method | Endpoint                        | Deskripsi                      | Auth |
|--------|---------------------------------|--------------------------------|------|
| POST   | /api/courses/{id}/activity      | Catat kunjungan ke course      | Ya   |

---

## Contoh Request & Response

### Login

**Request:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@smartexam.id","password":"password"}'
```

**Response:**
```json
{
  "message": "Login berhasil.",
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@smartexam.id",
    "role": "admin"
  }
}
```

---

### List Courses (dengan filter)

```bash
curl "http://localhost:8000/api/admin/courses?language=id&category_id=1&per_page=20" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

### Buat Course

```bash
curl -X POST http://localhost:8000/api/admin/courses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Pemrograman Web","code":"PW01","teacher_ids":[2],"language":"id"}'
```

---

### Enroll Users ke Course

```bash
curl -X POST http://localhost:8000/api/admin/courses/1/enrollments \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"user_ids":[3,4,5]}'
```

---

### Bulk Enroll

```bash
curl -X POST http://localhost:8000/api/admin/enrollments/bulk \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"user_ids":[3,4,5],"course_ids":[1,2]}'
```

---

### Duplikasi Course

```bash
curl -X POST http://localhost:8000/api/admin/courses/1/duplicate \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

### Publish Course

```bash
curl -X POST http://localhost:8000/api/admin/courses/1/publish \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## Alur Utama (Flow)

```
[Admin buka halaman Course]
       ↓
GET  /api/admin/courses          ← List Standar
GET  /api/admin/courses/management ← List Manajemen
       ↓
POST /api/admin/courses          ← Buat Mata Kuliah Baru
       ↓
POST /api/admin/courses/{id}/enrollments   ← Daftarkan Pengguna
atau
POST /api/admin/enrollments/bulk           ← Bulk Enrollment
       ↓
POST /api/admin/courses/{id}/publish       ← Publish
       ↓
[Course tersedia untuk Student]
       ↓
GET  /api/student/courses                  ← Daftar Mata Kuliah Saya
GET  /api/student/courses/{id}/detail      ← Detail (Materi + Latihan)
```

---

## Catatan Penting

- Semua endpoint API menggunakan prefix `/api/` yang dikonfigurasi di `bootstrap/app.php`.
- Middleware `role` dikonfigurasi di `bootstrap/app.php` (bukan `Kernel.php` — Laravel 12 tidak menggunakan Kernel.php).
- Soft delete aktif pada model `Course` — data yang dihapus tidak hilang permanen dari database.
- Impor data (CSV/Excel) hanya tersedia di Web interface, bukan API.
- Ekspor data (CSV/Excel) hanya tersedia di Web interface, bukan API.