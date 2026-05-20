<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Web\Admin\CourseCategoryController as AdminCategoryController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use App\Http\Controllers\Web\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Web\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Web\Student\CourseController as StudentCourseController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Redirect root → role-based dashboard
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!auth()->check()) return redirect()->route('login');
    return match(auth()->user()->role) {
        'admin'   => redirect()->route('admin.courses.index'),
        'teacher' => redirect()->route('teacher.courses.index'),
        default   => redirect()->route('student.courses.index'),
    };
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

    // ── Courses ───────────────────────────────────────────────────────
    Route::get('courses',                         [AdminCourseController::class, 'index'])    ->name('courses.index');
    Route::get('courses/create',                  [AdminCourseController::class, 'create'])   ->name('courses.create');
    Route::post('courses',                        [AdminCourseController::class, 'store'])    ->name('courses.store');
    Route::get('courses/{course}',                [AdminCourseController::class, 'show'])     ->name('courses.show');
    Route::get('courses/{course}/edit',           [AdminCourseController::class, 'edit'])     ->name('courses.edit');
    Route::put('courses/{course}',                [AdminCourseController::class, 'update'])   ->name('courses.update');
    Route::delete('courses/{course}',             [AdminCourseController::class, 'destroy'])  ->name('courses.destroy');
    Route::post('courses/{course}/duplicate',     [AdminCourseController::class, 'duplicate'])->name('courses.duplicate');
    Route::post('courses/{course}/publish',       [AdminCourseController::class, 'publish'])  ->name('courses.publish');
    Route::post('courses/{course}/unpublish',     [AdminCourseController::class, 'unpublish'])->name('courses.unpublish');

    // ── Courses Export / Import ────────────────────────────────────────
    Route::get('courses/export',                  [AdminCourseController::class, 'export'])   ->name('courses.export');
    Route::post('courses/import',                 [AdminCourseController::class, 'import'])   ->name('courses.import');

    // ── Enrollments ───────────────────────────────────────────────────
    Route::get('courses/{course}/enrollments',            [AdminEnrollmentController::class, 'index'])         ->name('enrollments.index');
    Route::post('courses/{course}/enrollments',           [AdminEnrollmentController::class, 'enroll'])        ->name('enrollments.enroll');
    Route::delete('courses/{course}/enrollments/{userId}',[AdminEnrollmentController::class, 'unenroll'])      ->name('enrollments.unenroll');
    Route::get('enrollments/add-users',                   [AdminEnrollmentController::class, 'addUsersForm'])  ->name('enrollments.add-users');
    Route::post('enrollments/bulk',                       [AdminEnrollmentController::class, 'bulkEnroll'])    ->name('enrollments.bulk');

    // ── Categories ────────────────────────────────────────────────────
    Route::resource('categories', AdminCategoryController::class);

    // ── Users ─────────────────────────────────────────────────────────
    Route::resource('users', AdminUserController::class);
    Route::post('users/import',                   [AdminUserController::class, 'import'])     ->name('users.import');
});

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')
    ->middleware(['auth', 'role:admin,teacher'])
    ->name('teacher.')
    ->group(function () {

    Route::get('courses',          [TeacherCourseController::class, 'index']) ->name('courses.index');
    Route::get('courses/{course}', [TeacherCourseController::class, 'show'])  ->name('courses.show');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')
    ->middleware(['auth', 'role:admin,teacher,student'])
    ->name('student.')
    ->group(function () {

    Route::get('courses',                      [StudentCourseController::class, 'index'])        ->name('courses.index');
    Route::get('courses/{course}',             [StudentCourseController::class, 'show'])         ->name('courses.show');
    Route::delete('courses/{course}/unenroll', [StudentCourseController::class, 'unenroll'])     ->name('courses.unenroll');
});