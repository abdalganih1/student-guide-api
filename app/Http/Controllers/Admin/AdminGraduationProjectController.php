<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GraduationProject;
use App\Models\Specialization; // Needed for dropdowns
use App\Models\Faculty;        // Needed for dropdowns
use App\Http\Requests\Admin\StoreGraduationProjectRequest; // يجب إنشاؤه
use App\Http\Requests\Admin\UpdateGraduationProjectRequest; // يجب إنشاؤه
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminGraduationProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = GraduationProject::with(['specialization', 'supervisor']);

        // Optional Filtering
        if($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
         if($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $projects = $query->orderBy('year', 'desc')->orderBy('specialization_id')->paginate(15);
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $years = GraduationProject::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('admin.projects.index', compact('projects', 'specializations', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $supervisors = Faculty::orderBy('name_ar')->pluck('name_ar', 'id'); // Assuming any faculty can supervise
        return view('admin.projects.create', compact('specializations', 'supervisors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGraduationProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        GraduationProject::create($validated);
        return redirect()->route('admin.projects.index')
                         ->with('success', 'تمت إضافة مشروع التخرج بنجاح.');
    }

    /**
     * Display the specified resource. (Optional)
     public function show(GraduationProject $project): View
     {
        $project->load(['specialization', 'supervisor']);
        return view('admin.projects.show', compact('project'));
     }
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GraduationProject $project): View
    {
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $supervisors = Faculty::orderBy('name_ar')->pluck('name_ar', 'id');
        return view('admin.projects.edit', compact('project', 'specializations', 'supervisors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGraduationProjectRequest $request, GraduationProject $project): RedirectResponse
    {
        $validated = $request->validated();
        $project->update($validated);
        return redirect()->route('admin.projects.index')
                         ->with('success', 'تم تعديل مشروع التخرج بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GraduationProject $project): RedirectResponse
    {
        try {
            $project->delete();
            return redirect()->route('admin.projects.index')
                             ->with('success', 'تم حذف مشروع التخرج بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.projects.index')
                             ->with('error', 'حدث خطأ أثناء حذف المشروع: ' . $e->getMessage());
        }
    }
}