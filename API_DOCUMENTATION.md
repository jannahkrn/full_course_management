# Smart Exam — BR-005 Course Management Backend

Laravel 10+ | MySQL | Sanctum Auth

---

## Project Structure

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
│   │   ├── Requests/
│   │   │   ├── StoreCourseRequest.php
│   │   │   ├── UpdateCourseRequest.php
│   │   │   └── CourseRequests.php  (shared form requests)
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
│       └── EnrollmentService.php
├── database/
│   ├── migrations/          (11 migration files)
│   └── seeders/
│       └── DatabaseSeeder.php
└── routes/
    └── api.php
```

---

## Database Schema

| Table | Description |
|-------|-------------|
| `users` | Admin, Teacher, Student accounts |
| `course_categories` | Kategori Mata Kuliah |
| `courses` | Daftar Mata Kuliah (full settings) |
| `course_teachers` | Pivot: course ↔ teacher (with role) |
| `enrollments` | Student enrollment per course |
| `course_sessions` | Daftar Sesi per course |
| `session_categories` | Kategori Sesi |
| `course_materials` | Materi per sesi |
| `course_exercises` | Latihan/ujian per sesi |
| `course_activity_logs` | Activity tracking (visited, etc.) |
| `personal_access_tokens` | Sanctum tokens |

---

## Installation

### 1. Create Laravel project and copy files
```bash
composer create-project laravel/laravel smart-exam-app
cd smart-exam-app
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 2. Copy all files from this package into your Laravel project.
- Copy `app/` → project's `app/`
- Copy `database/` → project's `database/`
- Copy `routes/api.php` → project's `routes/api.php`

### 3. Configure `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_exam
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Register middleware in `app/Http/Kernel.php`
In `$middlewareAliases`:
```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

### 5. Register Form Requests
The file `CourseRequests.php` contains multiple classes. Move each class
to its own file, or use a single file with PHP's class autoloading.
Alternatively, all requests in `CourseRequests.php` can be split into:
- `EnrollUserRequest.php`
- `BulkEnrollRequest.php`
- `StoreCourseCategoryRequest.php`
- `UpdateCourseCategoryRequest.php`
- `StoreCourseSessionRequest.php`
- `UpdateCourseSessionRequest.php`
- `LoginRequest.php`
- `RegisterRequest.php`

### 6. Run migrations and seeders
```bash
php artisan migrate --seed
```

### 7. Run the server
```bash
php artisan serve
```

---

## Testing with curl

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

**Create course:**
```bash
curl -X POST http://localhost:8000/api/admin/courses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"New Course","code":"NC01","teacher_ids":[2]}'
```

**Enroll users:**
```bash
curl -X POST http://localhost:8000/api/admin/courses/1/enrollments \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"user_ids":[3,4,5]}'
```

---

## Flow (BR-005)

```
[Admin buka Course]
       ↓
[GET /admin/courses]          ← List Standar / List Manajemen
       ↓
[POST /admin/courses]         ← Create Course (Tambah Mata Kuliah)
       ↓
[POST /admin/courses/{id}/enrollments]  ← Enroll User
       ↓
[POST /admin/courses/{id}/publish]      ← Publish
       ↓
[Course Available to Students]
       ↓
[GET /student/courses]        ← Daftar Mata Kuliah Saya
[GET /student/courses/{id}/detail]  ← Detail (Materi + Latihan)
```