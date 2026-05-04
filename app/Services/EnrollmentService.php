<?php

namespace App\Services;

use App\Models\{Course, Enrollment, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class EnrollmentService
{
    /**
     * Enroll one or multiple users into a course.
     * Returns ['enrolled' => [...], 'already_enrolled' => [...]]
     */
    public function enrollUsers(Course $course, array $userIds): array
    {
        $enrolled        = [];
        $alreadyEnrolled = [];

        DB::transaction(function () use ($course, $userIds, &$enrolled, &$alreadyEnrolled) {
            foreach ($userIds as $userId) {
                $existing = Enrollment::where('course_id', $course->id)
                                      ->where('user_id', $userId)
                                      ->first();

                if ($existing) {
                    if ($existing->status !== 'active') {
                        $existing->update(['status' => 'active', 'enrolled_at' => now(), 'dropped_at' => null]);
                        $enrolled[] = $userId;
                    } else {
                        $alreadyEnrolled[] = $userId;
                    }
                } else {
                    Enrollment::create([
                        'course_id'   => $course->id,
                        'user_id'     => $userId,
                        'status'      => 'active',
                        'enrolled_at' => now(),
                    ]);
                    $enrolled[] = $userId;
                }
            }
        });

        return ['enrolled' => $enrolled, 'already_enrolled' => $alreadyEnrolled];
    }

    /**
     * Enroll users into multiple courses at once (Admin bulk enroll).
     */
    public function bulkEnroll(array $userIds, array $courseIds): array
    {
        $results = [];
        foreach ($courseIds as $courseId) {
            $course = Course::find($courseId);
            if ($course) {
                $result            = $this->enrollUsers($course, $userIds);
                $results[$courseId] = $result;
            }
        }
        return $results;
    }

    /**
     * Unenroll (drop) a single user from a course.
     */
    public function unenrollUser(Course $course, int $userId): Enrollment
    {
        $enrollment = Enrollment::where('course_id', $course->id)
                                ->where('user_id', $userId)
                                ->where('status', 'active')
                                ->firstOrFail();

        if (!$course->allow_unsubscribe) {
            throw new \RuntimeException('Pengguna tidak diperbolehkan berhenti langganan mata kuliah ini.');
        }

        $enrollment->update(['status' => 'dropped', 'dropped_at' => now()]);
        return $enrollment;
    }

    /**
     * Student self-unenroll.
     */
    public function selfUnenroll(Course $course, User $user): void
    {
        if (!$course->allow_unsubscribe) {
            throw new \RuntimeException('Anda tidak diizinkan berhenti dari mata kuliah ini.');
        }

        $enrollment = Enrollment::where('course_id', $course->id)
                                ->where('user_id', $user->id)
                                ->where('status', 'active')
                                ->firstOrFail();

        $enrollment->update(['status' => 'dropped', 'dropped_at' => now()]);
    }

    /**
     * Get paginated enrollment list for a course.
     */
    public function getCourseEnrollments(Course $course, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $course->enrollments()->with('user');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['keyword'])) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', "%{$filters['keyword']}%")
                  ->orWhere('email', 'like', "%{$filters['keyword']}%")
            );
        }

        return $query->latest('enrolled_at')->paginate($perPage);
    }

    /**
     * Get all enrollments for a user (student dashboard).
     */
    public function getUserEnrollments(User $user, string $status = 'active'): \Illuminate\Database\Eloquent\Collection
    {
        return $user->enrollments()->with(['course.category', 'course.teachers'])
                    ->where('status', $status)
                    ->latest('enrolled_at')
                    ->get();
    }

    /**
     * Mark enrollment as completed.
     */
    public function completeEnrollment(Course $course, int $userId): Enrollment
    {
        $enrollment = Enrollment::where('course_id', $course->id)
                                ->where('user_id', $userId)
                                ->where('status', 'active')
                                ->firstOrFail();

        $enrollment->update(['status' => 'completed', 'completed_at' => now()]);
        return $enrollment;
    }
}