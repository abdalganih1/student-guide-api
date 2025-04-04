<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Http\Resources\SpecializationResource;
use App\Http\Resources\SpecializationCollection;
use Illuminate\Http\Request; // لا نحتاجها هنا بالضرورة لكن قد تُستخدم للفلترة مستقبلاً

class SpecializationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/specializations",
     *     summary="Get list of all specializations",
     *     tags={"Specializations"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SpecializationResource"))
     *     )
     * )
     * Display a listing of the resource.
     * عرض قائمة بالاختصاصات.
     */
    public function index()
    {
        // لا حاجة للـ eager loading هنا عادةً إلا إذا أردت عرض عدد المقررات مثلاً
        return new SpecializationCollection(Specialization::all());
    }

    /**
     * Store a newly created resource in storage.
     * (غير مستخدمة في API الطالب)
     */
    public function store(Request $request)
    {
        // Not applicable for student API
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/specializations/{specialization}",
     *     summary="Get specific specialization details including its courses",
     *     tags={"Specializations"},
     *     @OA\Parameter(
     *         name="specialization",
     *         in="path",
     *         required=true,
     *         description="ID of the specialization",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SpecializationResource")
     *     ),
     *     @OA\Response(response=404, description="Specialization not found")
     * )
     * Display the specified resource.
     * عرض تفاصيل اختصاص محدد مع مقرراته.
     */
    public function show(Specialization $specialization) // Route Model Binding
    {
        // تحميل المقررات المرتبطة بهذا الاختصاص
        // قد تحتاج لتحميل علاقات إضافية داخل المقررات إذا كان الـ Resource يتطلبها
        $specialization->load('courses'); // أو 'courses.faculty' إذا لزم الأمر

        // استخدام SpecializationResource لتنسيق الإخراج
        return new SpecializationResource($specialization);
    }

    /**
     * Update the specified resource in storage.
     * (غير مستخدمة في API الطالب)
     */
    public function update(Request $request, Specialization $specialization)
    {
        // Not applicable for student API
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }

    /**
     * Remove the specified resource from storage.
     * (غير مستخدمة في API الطالب)
     */
    public function destroy(Specialization $specialization)
    {
        // Not applicable for student API
        return response()->json(['message' => 'Method Not Allowed'], 405);
    }
}