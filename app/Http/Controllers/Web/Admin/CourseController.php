<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseCategory, User};
use App\Services\CourseService;
use App\Http\Requests\{StoreCourseRequest, UpdateCourseRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Services\ImportService;

class CourseController extends Controller
{
    public function __construct(private readonly CourseService $courseService) {}

    public function index(Request $request)
    {
        $view    = $request->get('view', 'standar'); // standar | manajemen
        $perPage = (int) $request->get('per_page', 10);
        $filters = $request->only([
            'keyword','title','code','category_id','language',
            'access_type','is_registered','is_allowed',
            'allow_unsubscribe','is_active','sort','direction',
        ]);

        $courses = $view === 'manajemen'
            ? $this->courseService->listForManagement($filters, $perPage)
            : $this->courseService->listForAdmin($filters, $perPage);

        $categories = CourseCategory::active()->orderBy('name')->get();
        $teachers   = User::teachers()->active()->orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'categories', 'teachers', 'view', 'filters'));
    }

    public function create()
    {
        $categories      = CourseCategory::active()->orderBy('name')->get();
        $teachers        = User::teachers()->active()->orderBy('name')->get();
        $templateCourses = Course::active()->orderBy('title')->get(['id','title']);

        return view('admin.courses.create', compact('categories', 'teachers', 'templateCourses'));
    }


    public function store(StoreCourseRequest $request)
    {
        $course = $this->courseService->create(
            $request->validated(),
            $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('success', "Mata kuliah \"{$course->title}\" berhasil dibuat.");
    }


    public function show(Course $course)
    {
        $course->load(['category', 'teachers', 'sessions.materials', 'sessions.exercises', 'enrollments']);
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $course->load(['teachers', 'category']);
        $categories      = CourseCategory::active()->orderBy('name')->get();
        $teachers        = User::teachers()->active()->orderBy('name')->get();
        $templateCourses = Course::active()->where('id', '!=', $course->id)->orderBy('title')->get(['id','title']);

        return view('admin.courses.edit', compact('course', 'categories', 'teachers', 'templateCourses'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->courseService->update(
            $course,
            $request->validated(),
            $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
        );

        return redirect()
            ->route('admin.courses.index')
            ->with('success', "Mata kuliah \"{$course->title}\" berhasil diperbarui.");
    }

    public function destroy(Course $course)
    {
        $title = $course->title;
        $this->courseService->delete($course);
        return redirect()->route('admin.courses.index')
            ->with('success', "Mata kuliah \"{$title}\" berhasil dihapus.");
    }

    public function duplicate(Course $course)
    {
        $new = $this->courseService->duplicate($course);
        return redirect()->route('admin.courses.edit', $new)
            ->with('success', "Salinan dari \"{$course->title}\" berhasil dibuat.");
    }

    public function publish(Course $course)
    {
        $this->courseService->publish($course);
        return back()->with('success', "Mata kuliah berhasil dipublikasikan.");
    }

    public function unpublish(Course $course)
    {
        $this->courseService->unpublish($course);
        return back()->with('success', "Mata kuliah berhasil di-unpublish.");
    }

    public function export(Request $request)
    {
        $format  = in_array($request->get('format'), ['csv', 'xlsx']) ? $request->get('format') : 'csv';
        $filters = $request->only([
            'keyword','title','code','category_id','language',
            'access_type','is_registered','is_allowed','allow_unsubscribe','is_active',
        ]);

        $courses = Course::with(['category', 'teachers'])
            ->when($filters['keyword'] ?? null, fn($q, $v) => $q->search($v))
            ->when($filters['title'] ?? null,   fn($q, $v) => $q->where('title', 'like', "%{$v}%"))
            ->when($filters['code'] ?? null,    fn($q, $v) => $q->where('code', 'like', "%{$v}%"))
            ->when($filters['category_id'] ?? null, fn($q, $v) => $q->byCategory($v))
            ->when($filters['language'] ?? null,    fn($q, $v) => $q->byLanguage($v))
            ->when($filters['access_type'] ?? null, fn($q, $v) => $q->where('access_type', $v))
            ->when(isset($filters['is_registered']), fn($q) => $q->where('is_registered', $filters['is_registered']))
            ->orderBy('title')
            ->get();

        $header = ['ID', 'Judul', 'Kode', 'Kategori', 'Bahasa', 'Guru', 'Terdaftar', 'Akses Terbatas', 'Status', 'Tanggal Dibuat'];
        $rows   = [];

        foreach ($courses as $course) {
            $rows[] = [
                $course->id,
                $course->title,
                $course->code ?? '',
                $course->category?->name ?? '',
                $course->language === 'id' ? 'Bahasa Indonesia' : 'English',
                $course->teachers->pluck('name')->join(', '),
                $course->is_registered ? 'Ya' : 'Tidak',
                $course->is_allowed ? 'Ya' : 'Tidak',
                $course->is_active ? 'Aktif' : 'Nonaktif',
                $course->created_at->format('d/m/Y H:i'),
            ];
        }

        $filename = 'daftar-mata-kuliah-' . now()->format('Ymd-His');

        if ($format === 'csv') {
            $callback = function () use ($header, $rows) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, $header);
                foreach ($rows as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ]);
        }

        $xml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
              . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
        $xml .= '<Worksheet ss:Name="Daftar Mata Kuliah"><Table>';

        $xml .= '<Row>';
        foreach ($header as $h) {
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($h, ENT_XML1, 'UTF-8') . '</Data></Cell>';
        }
        $xml .= '</Row>';

        foreach ($rows as $row) {
            $xml .= '<Row>';
            foreach ($row as $cell) {
                $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string) $cell, ENT_XML1, 'UTF-8') . '</Data></Cell>';
            }
            $xml .= '</Row>';
        }

        $xml .= '</Table></Worksheet></Workbook>';

        return Response::make($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xls\"",
        ]);
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
    ], [
        'file.mimes' => 'File harus berformat CSV (.csv) atau Excel (.xlsx/.xls).',
    ]);

    $importService = new ImportService();
    $rows          = $importService->readFile($request->file('file'));

    $imported = 0;
    $skipped  = 0;
    $errors   = [];

    foreach ($rows as $index => $row) {
        $lineNo = $index + 2; // +2 karena index 0-based dan baris 1 adalah header

        if (empty(array_filter($row))) {
            $skipped++;
            continue;
        }

        $title    = trim($row[1] ?? '');
        $code     = trim($row[2] ?? '') ?: null;
        $language = (trim($row[4] ?? '') === 'Bahasa Indonesia') ? 'id' : 'en';

        if (empty($title)) {
            $skipped++;
            continue;
        }

        try {
            $this->courseService->createFromImport([
                'title'    => $title,
                'code'     => $code,
                'language' => $language,
            ]);
            $imported++;
        } catch (\Exception $e) {
            $errors[] = "Baris {$lineNo} (\"{$title}\"): " . $e->getMessage();
        }
    }

    $message = "{$imported} mata kuliah berhasil diimpor.";
    if ($skipped)       $message .= " {$skipped} baris dilewati.";
    if (count($errors)) $message .= " " . count($errors) . " baris gagal.";

    return redirect()->route('admin.courses.index')
        ->with('success', $message)
        ->with('import_errors', $errors);
}
}