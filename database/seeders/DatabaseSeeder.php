<?php

namespace Database\Seeders;

use App\Models\{Course, CourseCategory, CourseSession, Enrollment, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'       => 'Super Admin',
            'email'      => 'admin@smartexam.id',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'is_active'  => true,
        ]);

        $teacher1 = User::create([
            'name'      => 'Albert Mandala, S. Pd',
            'email'     => 'albert@smartexam.id',
            'password'  => Hash::make('password'),
            'role'      => 'teacher',
            'is_active' => true,
        ]);

        $teacher2 = User::create([
            'name'      => 'Nurmala, S. Pd, M.T',
            'email'     => 'nurmala@smartexam.id',
            'password'  => Hash::make('password'),
            'role'      => 'teacher',
            'is_active' => true,
        ]);

        $teacher3 = User::create([
            'name'      => 'Sukha Pandji, S. Pd',
            'email'     => 'sukha@smartexam.id',
            'password'  => Hash::make('password'),
            'role'      => 'teacher',
            'is_active' => true,
        ]);

        $student = User::create([
            'name'      => 'Madeleine S',
            'email'     => 'madeleine@smartexam.id',
            'password'  => Hash::make('password'),
            'role'      => 'student',
            'is_active' => true,
        ]);

        $catProg  = CourseCategory::create(['name' => 'Pemrograman',  'slug' => 'pemrograman']);
        $catTeori = CourseCategory::create(['name' => 'Teori',        'slug' => 'teori']);
        $catProjek = CourseCategory::create(['name' => 'Projek',       'slug' => 'projek']);

        $courses = [
            [
                'title'       => 'AI for Beginners',
                'code'        => 'AIBEG01',
                'category_id' => $catProjek->id,
                'language'    => 'en',
                'teacher'     => $teacher1,
                'students'    => 34,
            ],
            [
                'title'       => 'Web Development',
                'code'        => 'WEBDEV01',
                'category_id' => $catProg->id,
                'language'    => 'en',
                'teacher'     => $teacher2,
                'students'    => 36,
            ],
            [
                'title'       => 'Dasar Perancangan Antarmuka',
                'code'        => 'DPA01',
                'category_id' => null,
                'language'    => 'id',
                'teacher'     => $teacher2,
                'students'    => 42,
            ],
            [
                'title'       => 'Dasar-dasar Pemrograman',
                'code'        => 'DDP01',
                'category_id' => $catProg->id,
                'language'    => 'en',
                'teacher'     => $teacher1,
                'students'    => 32,
            ],
            [
                'title'       => 'Algoritma Pemrograman',
                'code'        => 'ALGPROG01',
                'category_id' => $catProg->id,
                'language'    => 'en',
                'teacher'     => $teacher3,
                'students'    => 38,
            ],
            [
                'title'       => 'Pemrograman Berbasis Objek',
                'code'        => 'PBO01',
                'category_id' => $catProg->id,
                'language'    => 'en',
                'teacher'     => $teacher3,
                'students'    => 42,
            ],
            [
                'title'       => 'Matematika Dasar',
                'code'        => 'MATDAS01',
                'category_id' => $catTeori->id,
                'language'    => 'id',
                'teacher'     => $teacher2,
                'students'    => 42,
            ],
            [
                'title'       => 'Interaksi Manusia Komputer',
                'code'        => 'IMK01',
                'category_id' => $catProjek->id,
                'language'    => 'id',
                'teacher'     => $teacher2,
                'students'    => 38,
            ],
            [
                'title'       => 'Search Engine Optimization',
                'code'        => 'SEO01',
                'category_id' => $catProg->id,
                'language'    => 'en',
                'teacher'     => $teacher3,
                'students'    => 42,
            ],
        ];

        foreach ($courses as $courseData) {
            $teacher      = $courseData['teacher'];
            $studentCount = $courseData['students'];
            unset($courseData['teacher'], $courseData['students']);

            $courseData['is_active']    = true;
            $courseData['published_at'] = now();

            $course = Course::create($courseData);

            $course->teachers()->attach($teacher->id, [
                'role'        => 'primary',
                'assigned_at' => now(),
            ]);

            Enrollment::create([
                'course_id'   => $course->id,
                'user_id'     => $student->id,
                'status'      => 'active',
                'enrolled_at' => now(),
            ]);

            $session = CourseSession::create([
                'course_id'   => $course->id,
                'title'       => 'Sesi 1 - Pengenalan ' . $course->title,
                'description' => 'Sesi pertama dari mata kuliah ' . $course->title,
                'order'       => 1,
                'is_active'   => true,
            ]);

            $session->materials()->create([
                'title'     => 'Materi Pengenalan',
                'type'      => 'document',
                'order'     => 1,
                'is_active' => true,
            ]);

            $session->exercises()->create([
                'title'      => 'Latihan 1',
                'type'       => 'assignment',
                'max_score'  => 100,
                'passing_score' => 60,
                'order'      => 1,
                'is_active'  => true,
            ]);
        }
    }
}