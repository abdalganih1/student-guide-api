<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GraduationProject;
use App\Models\Specialization; // Needed for forms
use App\Models\Faculty; // Needed for forms
use App\Http\Requests\Admin\StoreGraduationProjectRequest;
use App\Http\Requests\Admin\UpdateGraduationProjectRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage; // If handling PDF uploads

class AdminGraduationProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = GraduationProject::with(['specialization', 'supervisor']); // Eager load

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title_ar', 'like', "%{$searchTerm}%")
                  ->orWhere('title_en', 'like', "%{$searchTerm}%")
                  ->orWhere('student_name', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $projects = $query->orderBy('year', 'desc')->orderBy('title_ar')->paginate(15);
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $years = GraduationProject::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');


        return view('admin.projects.index', compact('projects', 'specializations', 'years'));
    }

    public function create(): View
    {
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $supervisors = Faculty::orderBy('name_ar')->pluck('name_ar', 'id');
        return view('admin.projects.create', compact('specializations', 'supervisors'));
    }

    public function store(StoreGraduationProjectRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // Handle PDF upload if 'pdf_url' is a file input
        // if ($request->hasFile('pdf_file')) { // Assuming input name is 'pdf_file'
        //     $path = $request->file('pdf_file')->store('project_pdfs', 'public');
        //     $validatedData['pdf_url'] = $path;
        // }

        GraduationProject::create($validatedData);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'تم إضافة مشروع التخرج بنجاح.');
    }

    public function show(GraduationProject $project): View
    {
        $project->load(['specialization', 'supervisor']);
        return view('admin.projects.show', compact('project')); // Create this view if needed
    }

    public function edit(GraduationProject $project): View
    {
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $supervisors = Faculty::orderBy('name_ar')->pluck('name_ar', 'id');
        return view('admin.projects.edit', compact('project', 'specializations', 'supervisors'));
    }

    public function update(UpdateGraduationProjectRequest $request, GraduationProject $project): RedirectResponse
    {
        $validatedData = $request->validated();

        // Handle PDF update/replacement if 'pdf_url' is a file input
        // if ($request->hasFile('pdf_file')) {
        //     // Delete old PDF if it exists
        //     if ($project->pdf_url && Storage::disk('public')->exists($project->pdf_url)) {
        //         Storage::disk('public')->delete($project->pdf_url);
        //     }
        //     $path = $request->file('pdf_file')->store('project_pdfs', 'public');
        //     $validatedData['pdf_url'] = $path;
        // }

        $project->update($validatedData);

        return redirect()->route('admin.projects.index')
                         ->with('success', 'تم تحديث مشروع التخرج بنجاح.');
    }

    public function destroy(GraduationProject $project): RedirectResponse
    {
        try {
            // Delete associated PDF file first
            // if ($project->pdf_url && Storage::disk('public')->exists($project->pdf_url)) {
            //     Storage::disk('public')->delete($project->pdf_url);
            // }

            $project->delete();
            return redirect()->route('admin.projects.index')
                             ->with('success', 'تم حذف مشروع التخرج بنجاح.');
        } catch (\Exception $e) {
             \Log::error("Error deleting project: " . $e->getMessage());
             return redirect()->route('admin.projects.index')
                             ->with('error', 'حدث خطأ أثناء حذف المشروع.');
        }
    }
}