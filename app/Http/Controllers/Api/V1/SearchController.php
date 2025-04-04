<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\GraduationProject;
use App\Http\Resources\SpecializationCollection;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\FacultyCollection;
use App\Http\Resources\GraduationProjectCollection;

class SearchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/search",
     *     summary="Perform a global search across specializations, courses, faculty, and projects",
     *     tags={"Search"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         description="The search term",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="specializations", type="array", @OA\Items(ref="#/components/schemas/SpecializationResource")),
     *             @OA\Property(property="courses", type="array", @OA\Items(ref="#/components/schemas/CourseResource")),
     *             @OA\Property(property="faculty", type="array", @OA\Items(ref="#/components/schemas/FacultyResource")),
     *             @OA\Property(property="projects", type="array", @OA\Items(ref="#/components/schemas/GraduationProjectResource"))
     *         )
     *     ),
     *     @OA\Response(response=400, description="Search term is required")
     * )
     * Perform a global search.
     * تنفيذ بحث شامل في الاختصاصات، المقررات، الأساتذة، والمشاريع.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->query('q');

        if (!$searchTerm) {
            return response()->json(['message' => 'Search term (q) is required.'], 400);
        }

        $likeTerm = '%' . $searchTerm . '%';
        $limit = 10; // تحديد عدد النتائج لكل قسم

        $specializations = Specialization::where('name_ar', 'like', $likeTerm)
                                         ->orWhere('name_en', 'like', $likeTerm)
                                         ->limit($limit)
                                         ->get();

        $courses = Course::with('specialization') // قد تحتاج لعرض الاختصاص مع المقرر في النتائج
                         ->where(function ($q) use ($likeTerm) {
                             $q->where('name_ar', 'like', $likeTerm)
                               ->orWhere('name_en', 'like', $likeTerm)
                               ->orWhere('code', 'like', $likeTerm);
                         })
                         ->limit($limit)
                         ->get();

        $faculty = Faculty::where(function ($q) use ($likeTerm) {
                            $q->where('name_ar', 'like', $likeTerm)
                              ->orWhere('name_en', 'like', $likeTerm)
                              ->orWhere('title', 'like', $likeTerm); // يمكن البحث باللقب أيضاً
                        })
                        ->limit($limit)
                        ->get();

        $projects = GraduationProject::with('specialization') // عرض الاختصاص مع المشروع
                          ->where(function ($q) use ($likeTerm) {
                              $q->where('title_ar', 'like', $likeTerm)
                                ->orWhere('title_en', 'like', $likeTerm);
                          })
                          ->limit($limit)
                          ->get();

        return response()->json([
            // استخدم الـ Collections لتحويل النتائج إلى التنسيق الصحيح
            'specializations' => new SpecializationCollection($specializations),
            'courses' => new CourseCollection($courses),
            'faculty' => new FacultyCollection($faculty),
            'projects' => new GraduationProjectCollection($projects),
        ]);
    }

     // الدوال الأخرى غير مستخدمة هنا
    public function index() { return response()->json(['message' => 'Use /api/v1/search?q=term'], 400); }
    public function store(Request $request) { return response()->json(['message' => 'Not Implemented'], 501); }
    public function show($id) { return response()->json(['message' => 'Not Implemented'], 501); }
    public function update(Request $request, $id) { return response()->json(['message' => 'Not Implemented'], 501); }
    public function destroy($id) { return response()->json(['message' => 'Not Implemented'], 501); }
}