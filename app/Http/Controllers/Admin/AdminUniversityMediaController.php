<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityMedia;
use App\Http\Requests\Admin\StoreUniversityMediaRequest; // يجب إنشاؤه
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage; // لاستخدام تخزين الملفات
use Illuminate\Support\Facades\Log;

class AdminUniversityMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = UniversityMedia::query();

        // Optional filtering
        if($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        $mediaItems = $query->orderBy('created_at', 'desc')->paginate(12); // Show 12 per page (good for grid)

        // Get distinct categories for filter dropdown
        $categories = UniversityMedia::select('category')->distinct()->pluck('category')->filter();

        return view('admin.media.index', compact('mediaItems', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
         // Get distinct categories for dropdown suggestion
        $categories = UniversityMedia::select('category')->distinct()->pluck('category')->filter();
        return view('admin.media.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUniversityMediaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            try {
                // Define folder based on type (optional)
                $folder = $validated['media_type'] === 'video' ? 'university_videos' : 'university_images';

                // Store the file in the 'public' disk under the specified folder
                // The file name will be hashed automatically by store()
                $filePath = $request->file('file')->store($folder, 'public');

                // Create the record in the database
                UniversityMedia::create([
                    'title_ar' => $validated['title_ar'],
                    'title_en' => $validated['title_en'],
                    'description_ar' => $validated['description_ar'],
                    'description_en' => $validated['description_en'],
                    'url' => $filePath, // Save the relative path returned by store()
                    'media_type' => $validated['media_type'],
                    'category' => $validated['category'],
                ]);

                return redirect()->route('admin.media.index')
                                 ->with('success', 'تم رفع الوسيط بنجاح.');

            } catch (\Exception $e) {
                 Log::error("Error uploading media: " . $e->getMessage());
                 return redirect()->back()
                                 ->with('error', 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage())
                                 ->withInput();
            }
        } else {
             return redirect()->back()
                             ->with('error', 'الرجاء اختيار ملف للرفع.')
                             ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UniversityMedia $medium): RedirectResponse // Laravel 9+ uses 'medium' as variable name
    {
        try {
            // Delete the file from storage first
            if ($medium->url && Storage::disk('public')->exists($medium->url)) {
                Storage::disk('public')->delete($medium->url);
            }

            // Delete the database record
            $medium->delete();

            return redirect()->route('admin.media.index')
                             ->with('success', 'تم حذف الوسيط بنجاح.');
        } catch (\Exception $e) {
             Log::error("Error deleting media: " . $e->getMessage());
            return redirect()->route('admin.media.index')
                             ->with('error', 'حدث خطأ أثناء حذف الوسيط: ' . $e->getMessage());
        }
    }

    // show, edit, update are not needed based on Route::resource(...)->except(...)
}