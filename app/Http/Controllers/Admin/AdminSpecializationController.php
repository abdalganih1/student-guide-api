<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Http\Requests\Admin\StoreSpecializationRequest;
use App\Http\Requests\Admin\UpdateSpecializationRequest;
use Illuminate\Http\Request; // Keep Request for index filtering/search
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminSpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Specialization::query();

        // Example Search (optional)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name_ar', 'like', "%{$searchTerm}%")
                  ->orWhere('name_en', 'like', "%{$searchTerm}%");
            });
        }

        $specializations = $query->orderBy('name_ar')->paginate(15);
        return view('admin.specializations.index', compact('specializations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.specializations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSpecializationRequest $request): RedirectResponse
    {
        // Validation is handled by StoreSpecializationRequest
        $validatedData = $request->validated();

        // Add file handling logic here if 'image' or 'study_plan_url' are actual file uploads

        Specialization::create($validatedData);

        return redirect()->route('admin.specializations.index')
                         ->with('success', 'تم إنشاء الاختصاص بنجاح.');
    }

    /**
     * Display the specified resource. (Usually not needed for admin, edit is often sufficient)
     */
    public function show(Specialization $specialization): View
    {
         // You might load relations if needed for a detailed view
         // $specialization->load('courses');
         return view('admin.specializations.show', compact('specialization')); // Create this view if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialization $specialization): View
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSpecializationRequest $request, Specialization $specialization): RedirectResponse
    {
        $validatedData = $request->validated();

        // Add file handling logic here if 'image' or 'study_plan_url' are actual file uploads
        // Check if a new file was uploaded, delete the old one, save the new one, update path in $validatedData

        $specialization->update($validatedData);

        return redirect()->route('admin.specializations.index')
                         ->with('success', 'تم تحديث الاختصاص بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialization $specialization): RedirectResponse
    {
        try {
            // Add logic here to delete associated files (image, study plan) from storage first
            // Storage::disk('public')->delete($specialization->image);
            // Storage::disk('public')->delete($specialization->study_plan_url);

            $specialization->delete();
            return redirect()->route('admin.specializations.index')
                             ->with('success', 'تم حذف الاختصاص بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle potential foreign key constraint violation (e.g., if courses exist)
            return redirect()->route('admin.specializations.index')
                             ->with('error', 'لا يمكن حذف الاختصاص لوجود مقررات مرتبطة به.');
        } catch (\Exception $e) {
             \Log::error("Error deleting specialization: " . $e->getMessage());
             return redirect()->route('admin.specializations.index')
                             ->with('error', 'حدث خطأ أثناء حذف الاختصاص.');
        }
    }
}