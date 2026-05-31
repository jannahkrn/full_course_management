<?php

namespace App\Services;

use App\Models\{Course, CourseSession, Enrollment, User};
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CourseService
{

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

    public function listForManagement(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Course::with(['teachers', 'enrollments'])
            ->withCount(['enrollments as active_enrollments_count' => fn($q) => $q->where('status', 'active')]);

        $this->applyCommonFilters($query, $filters);

        return $query->orderBy($filters['sort'] ?? 'created_at', $filters['direction'] ?? 'desc')
                     ->paginate($perPage);
    }

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
        if (!empty($filters['language'])) {
            $query->byLanguage($filters['language']);
        }
        if (!empty($filters['access_type'])) {
            $query->where('access_type', $filters['access_type']);
        }
        if (isset($filters['is_registered']) && $filters['is_registered'] !== '') {
            $query->where('is_registered', (bool) $filters['is_registered']);
        }
        if (isset($filters['is_allowed']) && $filters['is_allowed'] !== '') {
            $query->where('is_allowed', (bool) $filters['is_allowed']);
        }
        if (isset($filters['allow_unsubscribe']) && $filters['allow_unsubscribe'] !== '') {
            $query->where('allow_unsubscribe', (bool) $filters['allow_unsubscribe']);
        }
    }


    public function create(array $data, ?UploadedFile $thumbnail = null): Course
    {
        return DB::transaction(function () use ($data, $thumbnail) {
            if ($thumbnail) {
                $data['thumbnail'] = $thumbnail->store('courses/thumbnails', 'public');
            }

            $teacherIds = $data['teacher_ids'] ?? [];
            unset($data['teacher_ids']);

            $course = Course::create($data);

            if (!empty($teacherIds)) {
                $pivotData = [];
                foreach ($teacherIds as $i => $id) {
                    $pivotData[$id] = ['role' => $i === 0 ? 'primary' : 'co_teacher', 'assigned_at' => now()];
                }
                $course->teachers()->sync($pivotData);
            }

            return $course;
        });
    }

    public function update(Course $course, array $data, ?UploadedFile $thumbnail = null): Course
    {
        return DB::transaction(function () use ($course, $data, $thumbnail) {
            if ($thumbnail) {
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                $data['thumbnail'] = $thumbnail->store('courses/thumbnails', 'public');
            }

            $teacherIds = $data['teacher_ids'] ?? null;
            unset($data['teacher_ids']);

            $course->update($data);

            if ($teacherIds !== null) {
                $pivotData = [];
                foreach ($teacherIds as $i => $id) {
                    $pivotData[$id] = ['role' => $i === 0 ? 'primary' : 'co_teacher', 'assigned_at' => now()];
                }
                $course->teachers()->sync($pivotData);
            }

            return $course->fresh();
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

    public function duplicate(Course $course): Course
    {
        return DB::transaction(function () use ($course) {
            $new = $course->replicate(['slug', 'published_at']);
            $new->title = "Salinan dari {$course->title}";
            $new->slug  = Str::slug($new->title) . '-' . Str::random(5);
            $new->is_active = false;
            $new->save();

            foreach ($course->teachers as $teacher) {
                $new->teachers()->attach($teacher->id, [
                    'role'        => $teacher->pivot->role,
                    'assigned_at' => now(),
                ]);
            }

            return $new;
        });
    }

    public function publish(Course $course): void
    {
        $course->update(['published_at' => now(), 'is_active' => true]);
    }

    public function unpublish(Course $course): void
    {
        $course->update(['published_at' => null]);
    }

    public function createFromImport(array $row): Course
    {
        return Course::create([
            'title'    => $row['title'],
            'code'     => $row['code'] ?? null,
            'language' => $row['language'] ?? 'en',
            'slug'     => Str::slug($row['title']) . '-' . Str::random(5),
            'is_active' => true,
        ]);
    }
}