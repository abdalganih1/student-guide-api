<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityMedia;
use App\Http\Requests\Admin\StoreUniversityMediaRequest;
use App\Http\Requests\Admin\UpdateUniversityMediaRequest; // استخدم الـ Request الجديد
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
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
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('media_type')) {
            $query->where('media_type', $request->media_type);
        }

        $mediaItems = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = UniversityMedia::select('category')->distinct()->pluck('category')->filter();

        return view('admin.media.index', compact('mediaItems', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = UniversityMedia::select('category')->distinct()->pluck('category')->filter();
        return view('admin.media.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUniversityMediaRequest $request): RedirectResponse
    {
        $validated = $request->validated(); // Validation handled by request class

        // File presence check is already in StoreUniversityMediaRequest ('required')
        try {
            $folder = $validated['media_type'] === 'video' ? 'university_videos' : 'university_images';
            $filePath = $request->file('file')->store($folder, 'public');

            UniversityMedia::create([
                'title_ar' => $validated['title_ar'],
                'title_en' => $validated['title_en'] ?? null, // Handle optional fields
                'description_ar' => $validated['description_ar'] ?? null,
                'description_en' => $validated['description_en'] ?? null,
                'url' => $filePath,
                'media_type' => $validated['media_type'],
                'category' => $validated['category'] ?? null,
            ]);

            return redirect()->route('admin.media.index')
                             ->with('success', 'تم رفع الوسيط بنجاح.');

        } catch (\Exception $e) {
            Log::error("Error uploading media: " . $e->getMessage());
            // Redirect back with specific error for debugging if needed, or a general user message
             return redirect()->back()
                             ->with('error', 'حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى.') // General message for user
                             // ->with('error', 'حدث خطأ أثناء رفع الملف: ' . $e->getMessage()) // More specific for dev
                             ->withInput();
        }
        // Removed the 'else' for file check as 'required' handles it
    }

    /**
     * Display the specified resource. (اختياري)
     * Show a single media item details (maybe useful for videos).
     */
    public function show(UniversityMedia $medium): View // Note the parameter name Laravel uses
    {
        return view('admin.media.show', compact('medium')); // Create this view if needed
    }

    /**
     * Show the form for editing the specified resource.
     * عرض نموذج تعديل الوسيط.
     */
    public function edit(UniversityMedia $medium): View
    {
        $categories = UniversityMedia::select('category')->distinct()->pluck('category')->filter();
        return view('admin.media.edit', compact('medium', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     * تحديث بيانات الوسيط في قاعدة البيانات والملف إذا تم تغييره.
     */
    public function update(UpdateUniversityMediaRequest $request, UniversityMedia $medium): RedirectResponse
    {
        $validated = $request->validated(); // Validation handled by UpdateUniversityMediaRequest

        try {
            // Check if a new file was uploaded
            if ($request->hasFile('file')) {
                // 1. Delete the old file
                if ($medium->url && Storage::disk('public')->exists($medium->url)) {
                    Storage::disk('public')->delete($medium->url);
                }

                // 2. Store the new file
                $folder = $validated['media_type'] === 'video' ? 'university_videos' : 'university_images';
                $filePath = $request->file('file')->store($folder, 'public');
                $validated['url'] = $filePath; // Update the path for the database record
            } else {
                // If no new file, keep the existing URL, remove 'file' from validated data if present
                unset($validated['file']);
                // Ensure the media_type matches the existing file if type is changed without uploading new file (Edge case handling needed if type change is allowed without file change)
                 if (isset($validated['media_type']) && $validated['media_type'] !== $medium->media_type && !$request->hasFile('file')) {
                      return redirect()->back()
                             ->with('error', 'لتغيير نوع الوسيط، يرجى رفع ملف جديد بالنوع الصحيح.')
                             ->withInput();
                 }
            }

            // Update the database record
            $medium->update([
                'title_ar' => $validated['title_ar'],
                'title_en' => $validated['title_en'] ?? null,
                'description_ar' => $validated['description_ar'] ?? null,
                'description_en' => $validated['description_en'] ?? null,
                'url' => $validated['url'] ?? $medium->url, // Use new URL if uploaded, else keep old
                'media_type' => $validated['media_type'],
                'category' => $validated['category'] ?? null,
            ]);

            return redirect()->route('admin.media.index')
                             ->with('success', 'تم تحديث الوسيط بنجاح.');

        } catch (\Exception $e) {
            Log::error("Error updating media (ID: {$medium->id}): " . $e->getMessage());
             return redirect()->back()
                             ->with('error', 'حدث خطأ أثناء تحديث الوسيط: ' . $e->getMessage())
                             ->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UniversityMedia $medium): RedirectResponse
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
            Log::error("Error deleting media (ID: {$medium->id}): " . $e->getMessage());
            return redirect()->route('admin.media.index')
                             ->with('error', 'حدث خطأ أثناء حذف الوسيط.');
        }
    }
}