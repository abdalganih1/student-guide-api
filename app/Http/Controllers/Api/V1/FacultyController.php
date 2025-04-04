<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\FacultyCollection;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
      * @OA\Get(
      *     path="/api/v1/faculty",
      *     summary="Get list of faculty members (optionally filtered by name/title)",
      *     tags={"Faculty"},
      *     @OA\Parameter(
      *         name="search",
      *         in="query",
      *         required=false,
      *         description="Search faculty by name (Arabic/English) or title",
      *         @OA\Schema(type="string")
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Successful operation",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/FacultyResource"))
      *     )
      * )
     * Display a listing of the resource.
     * عرض قائمة بأعضاء هيئة التدريس (مع إمكانية البحث بالاسم أو اللقب).
     */
    public function index(Request $request)
    {
        $query = Faculty::query();

        // البحث بالاسم (عربي/إنجليزي) أو اللقب
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->input('search') . '%';
            return $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('name_ar', 'like', $searchTerm)
                         ->orWhere('name_en', 'like', $searchTerm)
                         ->orWhere('title', 'like', $searchTerm);
            });
        });

        // يمكنك إضافة تحميل مسبق لعدد المقررات أو المشاريع إذا أردت عرضها في القائمة
        // $query->withCount(['courses', 'supervisedProjects']);

        return new FacultyCollection($query->paginate(20));
        // أو return new FacultyCollection($query->get());
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
     *     path="/api/v1/faculty/{faculty}",
     *     summary="Get specific faculty member details (optional, includes courses)",
     *     tags={"Faculty"},
     *     @OA\Parameter(
     *         name="faculty",
     *         in="path",
     *         required=true,
     *         description="ID of the faculty member",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/FacultyResource")
     *     ),
     *     @OA\Response(response=404, description="Faculty member not found")
     * )
     * Display the specified resource.
     * عرض تفاصيل عضو هيئة تدريس محدد (إذا كانت الواجهة تتطلب شاشة ملف شخصي).
     */
    public function show(Faculty $faculty) // Route Model Binding
    {
        // تحميل المقررات التي يدرسها والمشاريع التي يشرف عليها (إن لزم الأمر)
        $faculty->load(['courses', 'supervisedProjects']);
        return new FacultyResource($faculty);
    }

    /**
     * Update the specified resource in storage.
     * (غير مستخدمة في API الطالب)
     */
    public function update(Request $request, Faculty $faculty)
    {
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }

    /**
     * Remove the specified resource from storage.
     * (غير مستخدمة في API الطالب)
     */
    public function destroy(Faculty $faculty)
    {
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }
}