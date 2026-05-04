<?php

namespace App\Services;

use App\Models\{Course, CourseSession, Enrollment, User};
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CourseService
{
    // ─── Listing & Search ─────────────────────────────────────────────

    /**
     * Get paginated course list with advanced filters (Admin – Standard View).
     */
    public function listForAdmin(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Course::with(['category', 'teachers', 'enrollments'])
            ->withCount(['enrollments as active_enrollments_count' => fn($q) => $q->where('status', 'active')]);

        $this->applyCommonFilters($query, $filters);

        $sortField = $filters['sort'] ?? 'created_at';
        $sortDir   = $filters['direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDir);

        return $query->paginate($perPage);
    }

    /**
     * Management view – includes teacher(s) and timestamps.
     */
    public function listForManagement(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Course::with(['teachers', 'enrollments'])
            ->withCount(['enrollments as active_enrollments_count' => fn($q) => $q->where('status', 'active')]);

        $this->applyCommonFilters($query, $filters);

        return $query->orderBy($filters['sort'] ?? 'created_at', $filters['direction'] ?? 'desc')
                     ->paginate($perPage);
    }

    /**
     * Catalog view for teacher – shows their own courses.
     */
    public function catalogForTeacher(User $teacher, array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Course::with(['category', 'enrollments'])
            ->withCount(['enrollments as active_enrollments_count' => fn($q) => $q->where('status', 'active')])
            ->whereHas('teachers', fn($q) => $q->where('user_id', $teacher->id));

        $this->applyCommonFilters($query, $filters);

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['direction'] ?? 'asc');
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    /**
     * Student's enrolled course list.
     */
    public function listForStudent(User $student, array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Course::with(['category', 'teachers'])
            ->withCount(['enrollments as active_enrollments_count' => fn($q) => $q->where('status', 'active')])
            ->whereHas('enrollments', fn($q) =>
                $q->where('user_id', $student->id)->where('status', 'active')
            );

        if (!empty($filters['keyword'])) {
            $query->search($filters['keyword']);
        }
        if (!empty($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }

        return $query->paginate($perPage);
    }

    private function applyCommonFilters(Builder $query, array $filters): void
    {
        if (!empty($filters['keyword'])) {
            $query->search($filters['keyword']);
        }
        if (!empty($filters['title'])) {
            $query->where('title', 'like', "%{$filters['title']}%");
        }
        if (!empty($filters['code'])) {
            $query->where('code', 'like', "%{$filters['code']}%");
        }
        if (!empty($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }
        if (isset($filters['language']) && $filters['language'] !== 'Semua') {
            $query->byLanguage($filters['language']);
        }
        if (isset($filters['access_type']) && $filters['access_type'] !== 'Semua') {
            $query->where('access_type', $filters['access_type']);
        }
        if (isset($filters['is_registered']) && $filters['is_registered'] !== '') {
            $query->where('is_registered', filter_var($filters['is_registered'], FILTER_VALIDATE_BOOLEAN));
        }
        if (isset($filters['is_allowed']) && $filters['is_allowed'] !== '') {
            $query->where('is_allowed', filter_var($filters['is_allowed'], FILTER_VALIDATE_BOOLEAN));
        }
        if (isset($filters['allow_unsubscribe']) && $filters['allow_unsubscribe'] !== '') {
            $query->where('allow_unsubscribe', filter_var($filters['allow_unsubscribe'], FILTER_VALIDATE_BOOLEAN));
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
        }
    }

    // ─── CRUD ─────────────────────────────────────────────────────────

    public function create(array $data, ?UploadedFile $thumbnail = null): Course
    {
        return DB::transaction(function () use ($data, $thumbnail) {
            if ($thumbnail) {
                $data['thumbnail'] = $thumbnail->store('courses/thumbnails', 'public');
            }

            $teacherIds = $data['teacher_ids'] ?? [];
            unset($data['teacher_ids']);

            $course = Course::create($data);

            // Attach teachers
            if (!empty($teacherIds)) {
                $this->syncTeachers($course, $teacherIds);
            }

            // If template course given, clone its sessions
            if (!empty($data['template_course_id'])) {
                $this->cloneSessionsFromTemplate($course, $data['template_course_id']);
            }

            return $course->load(['category', 'teachers']);
        });
    }

    public function update(Course $course, array $data, ?UploadedFile $thumbnail = null): Course
    {
        return DB::transaction(function () use ($course, $data, $thumbnail) {
            if ($thumbnail) {
                // Remove old thumbnail
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                $data['thumbnail'] = $thumbnail->store('courses/thumbnails', 'public');
            }

            $teacherIds = $data['teacher_ids'] ?? null;
            unset($data['teacher_ids']);

            $course->update($data);

            if ($teacherIds !== null) {
                $this->syncTeachers($course, $teacherIds);
            }

            return $course->fresh(['category', 'teachers', 'enrollments']);
        });
    }

    public function delete(Course $course): void
    {
        DB::transaction(function () use ($course) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $course->delete();
        });
    }

    public function duplicate(Course $source): Course
    {
        return DB::transaction(function () use ($source) {
            $source->load(['teachers', 'sessions.materials', 'sessions.exercises']);

            $newCourse = $source->replicate(['slug', 'code', 'published_at']);
            $newCourse->title     = "Salinan - {$source->title}";
            $newCourse->is_active = false;
            $newCourse->save();

            // Sync same teachers
            $teacherIds = $source->teachers->pluck('id')->toArray();
            $this->syncTeachers($newCourse, $teacherIds);

            // Clone sessions
            $this->cloneSessionsFromTemplate($newCourse, $source->id);

            return $newCourse->load(['category', 'teachers']);
        });
    }

    // ─── Teacher Management ───────────────────────────────────────────

    public function syncTeachers(Course $course, array $userIds): void
    {
        $pivotData = [];
        foreach ($userIds as $index => $userId) {
            $pivotData[$userId] = [
                'role'        => $index === 0 ? 'primary' : 'co_teacher',
                'assigned_at' => now(),
            ];
        }
        $course->teachers()->sync($pivotData);
    }

    // ─── Template cloning ─────────────────────────────────────────────

    private function cloneSessionsFromTemplate(Course $target, int $templateId): void
    {
        $template = Course::with(['sessions.materials', 'sessions.exercises'])->find($templateId);
        if (!$template) return;

        foreach ($template->sessions as $session) {
            $newSession = $target->sessions()->create([
                'title'       => $session->title,
                'description' => $session->description,
                'order'       => $session->order,
                'is_active'   => $session->is_active,
            ]);

            foreach ($session->materials as $material) {
                $newSession->materials()->create($material->only([
                    'title', 'description', 'type', 'file_url', 'order', 'is_active',
                ]));
            }

            foreach ($session->exercises as $exercise) {
                $newSession->exercises()->create($exercise->only([
                    'title', 'description', 'type', 'duration_minutes',
                    'max_score', 'passing_score', 'order', 'is_active',
                ]));
            }
        }
    }

    // ─── Session CRUD ─────────────────────────────────────────────────

    public function createSession(Course $course, array $data): CourseSession
    {
        if (!isset($data['order'])) {
            $data['order'] = $course->sessions()->max('order') + 1;
        }
        return $course->sessions()->create($data);
    }

    public function reorderSessions(Course $course, array $orderedIds): void
    {
        DB::transaction(function () use ($course, $orderedIds) {
            foreach ($orderedIds as $index => $sessionId) {
                $course->sessions()->where('id', $sessionId)->update(['order' => $index + 1]);
            }
        });
    }

    // ─── Publish / Unpublish ──────────────────────────────────────────

    public function publish(Course $course): Course
    {
        $course->update(['published_at' => now(), 'is_active' => true]);
        return $course;
    }

    public function unpublish(Course $course): Course
    {
        $course->update(['published_at' => null]);
        return $course;
    }
}