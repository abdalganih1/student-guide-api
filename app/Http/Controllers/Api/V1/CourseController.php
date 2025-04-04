<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Specialization; // سنحتاجها في indexBySpecialization
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/courses",
     *     summary="Get list of courses (optionally filtered by specialization)",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="specialization_id",
     *         in="query",
     *         required=false,
     *         description="Filter courses by specialization ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search courses by name or code",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CourseResource"))
     *     )
     * )
     * Display a listing of the resource.
     * عرض قائمة بالمقررات (مع إمكانية الفلترة حسب الاختصاص أو البحث).
     */
    public function index(Request $request)
    {
        $query = Course::query()->with(['specialization', 'faculty']); // تحميل العلاقات الأساسية

        // الفلترة حسب الاختصاص إذا تم توفير specialization_id
        $query->when($request->filled('specialization_id'), function ($q) use ($request) {
            return $q->where('specialization_id', $request->input('specialization_id'));
        });

        // البحث بالاسم أو الكود
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->input('search') . '%';
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('name_ar', 'like', $searchTerm)
                         ->orWhere('name_en', 'like', $searchTerm)
                         ->orWhere('code', 'like', $searchTerm);
            });
        });

        // يمكنك إضافة ترتيب هنا، مثلاً حسب الكود أو السنة
        // $query->orderBy('year_level')->orderBy('code');

        return new CourseCollection($query->paginate(15)); // استخدام paginate للنتائج الكثيرة
        // أو return new CourseCollection($query->get()); إذا كانت القائمة قصيرة
    }

    /**
     * عرض المقررات الخاصة باختصاص معين.
     * هذا يتوافق بشكل أفضل مع واجهة "تفاصيل الاختصاص".
     */
    public function indexBySpecialization(Specialization $specialization)
    {
        // تحميل أعضاء هيئة التدريس المرتبطين بالمقررات
        $courses = $specialization->courses()->with('faculty')->get();
        return new CourseCollection($courses);
    }


    /**
     * Store a newly created resource in storage.
     * (غير مستخدمة في API الطالب)
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }

    /**
      * @OA\Get(
      *     path="/api/v1/courses/{course}",
      *     summary="Get specific course details including faculty and resources",
      *     tags={"Courses"},
      *     @OA\Parameter(
      *         name="course",
      *         in="path",
      *         required=true,
      *         description="ID of the course",
      *         @OA\Schema(type="integer")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Successful operation",
      *         @OA\JsonContent(ref="#/components/schemas/CourseResource")
      *     ),
      *     @OA\Response(response=404, description="Course not found")
      * )
     * Display the specified resource.
     * عرض تفاصيل مقرر محدد مع الأساتذة والموارد.
     */
    public function show(Course $course) // Route Model Binding
    {
        // تحميل العلاقات اللازمة لعرض تفاصيل المقرر
        $course->load(['specialization', 'faculty', 'resources']);
        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     * (غير مستخدمة في API الطالب)
     */
    public function update(Request $request, Course $course)
    {
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }

    /**
     * Remove the specified resource from storage.
     * (غير مستخدمة في API الطالب)
     */
    public function destroy(Course $course)
    {
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }
}