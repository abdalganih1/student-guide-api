<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UniversityMedia;
use App\Http\Resources\UniversityMediaResource; // ستحتاج لإنشائه
use App\Http\Resources\UniversityMediaCollection; // ستحتاج لإنشائه
use Illuminate\Http\Request;

class UniversityMediaController extends Controller
{
    /**
      * @OA\Get(
      *     path="/api/v1/media",
      *     summary="Get list of university media (images/videos, optionally filtered by category)",
      *     tags={"University Media"},
      *     @OA\Parameter(
      *         name="category",
      *         in="query",
      *         required=false,
      *         description="Filter media by category (e.g., 'مخبر', 'قاعة')",
      *         @OA\Schema(type="string")
      *     ),
       *     @OA\Parameter(
      *         name="media_type",
      *         in="query",
      *         required=false,
      *         description="Filter by media type ('image' or 'video')",
      *         @OA\Schema(type="string", enum={"image", "video"})
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Successful operation",
      *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UniversityMediaResource"))
      *     )
      * )
     * Display a listing of the resource.
     * عرض قائمة بوسائط الجامعة (صور وفيديوهات) مع إمكانية الفلترة حسب التصنيف والنوع.
     */
    public function index(Request $request)
    {
        $query = UniversityMedia::query();

        // الفلترة حسب التصنيف
        $query->when($request->filled('category'), function ($q) use ($request) {
            return $q->where('category', $request->input('category'));
        });

        // الفلترة حسب نوع الوسيط
        $query->when($request->filled('media_type'), function ($q) use ($request) {
            return $q->where('media_type', $request->input('media_type'));
        });

        // الترتيب الأحدث أولاً
        $query->orderBy('created_at', 'desc');

        return new UniversityMediaCollection($query->paginate(20));
         // أو return new UniversityMediaCollection($query->get());
    }

    // الدوال الأخرى (store, show, update, destroy) ليست ضرورية هنا لـ API الطالب
    public function store(Request $request) { return response()->json(['message' => 'Not Implemented'], 501); }
    public function show(UniversityMedia $medium) { return new UniversityMediaResource($medium); } // قد لا تحتاجها
    public function update(Request $request, UniversityMedia $medium) { return response()->json(['message' => 'Not Implemented'], 501); }
    public function destroy(UniversityMedia $medium) { return response()->json(['message' => 'Not Implemented'], 501); }

}