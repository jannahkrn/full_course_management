<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    CourseController,
    CourseCategoryController,
    CourseSessionController,
    EnrollmentController,
    UserController,
    ActivityLogController,
};

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

Route::get('categories', [CourseCategoryController::class, 'index']);
Route::get('categories/{category}', [CourseCategoryController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    Route::prefix('admin')->middleware('role:admin')->group(function () {

        Route::apiResource('users', UserController::class);
        Route::get('teachers',              [UserController::class, 'teachers']);
        Route::get('users/{user}/courses',  [UserController::class, 'courses']);

        Route::apiResource('categories', CourseCategoryController::class)
             ->except(['index', 'show']);

        Route::get('courses',            [CourseController::class, 'index']);
        Route::get('courses/management', [CourseController::class, 'management']);
        Route::get('courses/{course}',   [CourseController::class, 'show']);
        Route::post('courses',           [CourseController::class, 'store']);
        Route::put('courses/{course}',   [CourseController::class, 'update']);
        Route::patch('courses/{course}', [CourseController::class, 'update']);
        Route::delete('courses/{course}',[CourseController::class, 'destroy']);

        Route::post('courses/{course}/duplicate', [CourseController::class, 'duplicate']);
        Route::post('courses/{course}/publish',   [CourseController::class, 'publish']);
        Route::post('courses/{course}/unpublish', [CourseController::class, 'unpublish']);

        Route::get('courses/{course}/enrollments',                                [EnrollmentController::class, 'index']);
        Route::post('courses/{course}/enrollments',                               [EnrollmentController::class, 'enroll']);
        Route::delete('courses/{course}/enrollments/{userId}',                    [EnrollmentController::class, 'unenroll']);
        Route::patch('courses/{course}/enrollments/{userId}/complete',            [EnrollmentController::class, 'markCompleted']);
        Route::post('enrollments/bulk',                                           [EnrollmentController::class, 'bulkEnroll']);

        Route::apiResource('courses/{course}/sessions', CourseSessionController::class);
        Route::post('courses/{course}/sessions/reorder', [CourseSessionController::class, 'reorder']);
    });

    Route::prefix('teacher')->middleware('role:admin,teacher')->group(function () {

        Route::get('courses/catalog', [CourseController::class, 'teacherCatalog']);
        Route::get('courses/{course}', [CourseController::class, 'show']);

        Route::apiResource('courses/{course}/sessions', CourseSessionController::class);
        Route::post('courses/{course}/sessions/reorder', [CourseSessionController::class, 'reorder']);

        Route::get('courses/{course}/enrollments', [EnrollmentController::class, 'index']);
    });


    Route::prefix('student')->middleware('role:student,admin,teacher')->group(function () {

        Route::get('courses',                          [CourseController::class, 'studentCourses']);
        Route::get('courses/{course}/detail',          [CourseController::class, 'studentDetail']);
        Route::delete('courses/{course}/unenroll',     [EnrollmentController::class, 'selfUnenroll']);

        Route::get('enrollments', [EnrollmentController::class, 'myEnrollments']);

        Route::get('activity/recent',  [ActivityLogController::class, 'recentCourses']);
        Route::get('activity/history', [ActivityLogController::class, 'history']);
    });

    Route::post('courses/{course}/activity', [ActivityLogController::class, 'log']);
});