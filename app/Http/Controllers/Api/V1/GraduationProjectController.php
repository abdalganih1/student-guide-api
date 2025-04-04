<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\GraduationProject;
use App\Http\Resources\GraduationProjectResource;
use App\Http\Resources\GraduationProjectCollection;
use Illuminate\Http\Request;

class GraduationProjectController extends Controller
{
    /**
      * @OA\Get(
      *     path="/api/v1/projects",
      *     summary="Get list of graduation projects (filtered by specialization, year, title)",
      *     tags={"Graduation Projects"},
      *     @OA\Parameter(
      *         name="specialization_id",
      *         in="query",
      *         required=false,
      *         description="Filter projects by specialization ID",
      *         @OA\Schema(type="integer")
      *     ),
       *     @OA\Parameter(
      *         name="year",
      *         in="query",
      *         required=false,
      *         description="Filter projects by graduation year",
      *         @OA\Schema(type="integer")
      *     ),
       *     @OA\Parameter(
      *         name="search",
      *         in="query",
      *         required=false,
      *         description="Search projects by title (Arabic/English)",
      *         @OA\Schema(type="string")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Successful operation",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/GraduationProjectResource"))
      *     )
      * )
     * Display a listing of the resource.
     * عرض قائمة بمشاريع التخرج مع الفلترة حسب الاختصاص، السنة، والبحث بالعنوان.
     */
    public function index(Request $request)
    {
        $query = GraduationProject::query()->with(['specialization', 'supervisor']); // تحميل العلاقات

        // الفلترة حسب الاختصاص
        $query->when($request->filled('specialization_id'), function ($q) use ($request) {
            return $q->where('specialization_id', $request->input('specialization_id'));
        });

        // الفلترة حسب السنة
        $query->when($request->filled('year'), function ($q) use ($request) {
            return $q->where('year', $request->input('year'));
        });

        // البحث بالعنوان (عربي/إنجليزي)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->input('search') . '%';
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('title_ar', 'like', $searchTerm)
                         ->orWhere('title_en', 'like', $searchTerm);
            });
        });

        // الترتيب حسب السنة ثم العنوان
        $query->orderBy('year', 'desc')->orderBy('title_ar');

        return new GraduationProjectCollection($query->paginate(15));
         // أو return new GraduationProjectCollection($query->get());
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
     *     path="/api/v1/projects/{project}",
     *     summary="Get specific graduation project details",
     *     tags={"Graduation Projects"},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         required=true,
     *         description="ID of the graduation project",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/GraduationProjectResource")
     *     ),
     *     @OA\Response(response=404, description="Project not found")
     * )
     * Display the specified resource.
     * عرض تفاصيل مشروع تخرج محدد (إذا لزم الأمر، قد لا تحتاجه الواجهة).
     */
    public function show(GraduationProject $project) // Route Model Binding
    {
        // تحميل العلاقات اللازمة
        $project->load(['specialization', 'supervisor']);
        return new GraduationProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     * (غير مستخدمة في API الطالب)
     */
    public function update(Request $request, GraduationProject $project)
    {
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }

    /**
     * Remove the specified resource from storage.
     * (غير مستخدمة في API الطالب)
     */
    public function destroy(GraduationProject $project)
    {
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }
}