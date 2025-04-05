<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Http\Requests\Admin\StoreFacultyRequest; // يجب إنشاؤه
use App\Http\Requests\Admin\UpdateFacultyRequest; // يجب إنشاؤه
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminFacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $facultyMembers = Faculty::orderBy('name_ar')->paginate(15);
        return view('admin.faculty.index', compact('facultyMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.faculty.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacultyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        Faculty::create($validated);
        return redirect()->route('admin.faculty.index')
                         ->with('success', 'تمت إضافة عضو هيئة التدريس بنجاح.');
    }

    /**
     * Display the specified resource. (Not typically needed in admin)
      public function show(Faculty $faculty): View
      {
          // return view('admin.faculty.show', compact('faculty'));
      }
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faculty $faculty): View
    {
        return view('admin.faculty.edit', compact('faculty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacultyRequest $request, Faculty $faculty): RedirectResponse
    {
        $validated = $request->validated();
        $faculty->update($validated);
        return redirect()->route('admin.faculty.index')
                         ->with('success', 'تم تعديل بيانات عضو هيئة التدريس بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty): RedirectResponse
    {
        try {
            // Check relationships before deleting
             if ($faculty->courses()->exists()) {
                 return redirect()->route('admin.faculty.index')
                                 ->with('error', 'لا يمكن حذف عضو هيئة التدريس لوجود مقررات مرتبطة به.');
            }
             if ($faculty->supervisedProjects()->exists()) {
                 return redirect()->route('admin.faculty.index')
                                 ->with('error', 'لا يمكن حذف عضو هيئة التدريس لوجود مشاريع تخرج يشرف عليها.');
            }

            $faculty->delete();
            return redirect()->route('admin.faculty.index')
                             ->with('success', 'تم حذف عضو هيئة التدريس بنجاح.');
        } catch (\Exception $e) {
             return redirect()->route('admin.faculty.index')
                             ->with('error', 'حدث خطأ أثناء حذف عضو هيئة التدريس: ' . $e->getMessage());
        }
    }
}