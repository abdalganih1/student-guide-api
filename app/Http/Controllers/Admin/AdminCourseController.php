<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Specialization; // Needed for dropdowns
use App\Models\Faculty;        // Needed for dropdowns
use App\Models\CourseResource; // Needed for managing resources
// --- استيراد Form Requests ---
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
// ---------------------------
use Illuminate\Http\Request; // May be needed for resource handling
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB; // For transaction if needed
use Illuminate\Support\Facades\Log; // For logging errors

class AdminCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Course::with(['specialization', 'faculty']); // Eager load relationships

        // Optional: Filter by specialization
        if ($request->filled('specialization_id')) {
            $query->where('specialization_id', $request->specialization_id);
        }

        $courses = $query->orderBy('specialization_id')->orderBy('code')->paginate(15);
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id'); // For filter dropdown

        return view('admin.courses.index', compact('courses', 'specializations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $faculty = Faculty::orderBy('name_ar')->pluck('name_ar', 'id');
        return view('admin.courses.create', compact('specializations', 'faculty'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction(); // Start transaction for atomicity
        try {
            // Create the course
            $course = Course::create($validated);

            // Attach faculty members (Many-to-Many)
            if ($request->has('faculty_ids')) {
                // semester is required in pivot table, get it from validated data
                $pivotData = array_fill(0, count($validated['faculty_ids']), ['semester' => $validated['semester']]);
                $syncData = array_combine($validated['faculty_ids'], $pivotData);
                $course->faculty()->sync($syncData);
                // Note: If 'role' is needed in pivot, handle it here
            } else {
                $course->faculty()->sync([]); // Detach all if none selected
            }

            // Add Course Resources (One-to-Many)
            if ($request->has('resources')) {
                foreach ($validated['resources'] as $resourceData) {
                    // Ensure required fields are present
                    if (!empty($resourceData['title_ar']) && !empty($resourceData['url']) && !empty($resourceData['type'])) {
                         // Add semester to resource data before creating
                         $resourceData['semester'] = $validated['semester'];
                         $course->resources()->create($resourceData);
                    }
                }
            }

            DB::commit(); // Commit transaction

            return redirect()->route('admin.courses.index')
                             ->with('success', 'تمت إضافة المقرر بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            Log::error('Error storing course: ' . $e->getMessage()); // Log the error
            return redirect()->back()
                             ->with('error', 'حدث خطأ أثناء إضافة المقرر: ' . $e->getMessage())
                             ->withInput();
        }
    }


    /**
     * Display the specified resource. (Optional in admin)
      public function show(Course $course): View
      {
           $course->load(['specialization', 'faculty', 'resources']);
           return view('admin.courses.show', compact('course'));
      }
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): View
    {
        $course->load('faculty', 'resources'); // Load relationships needed for the form
        $specializations = Specialization::orderBy('name_ar')->pluck('name_ar', 'id');
        $faculty = Faculty::orderBy('name_ar')->pluck('name_ar', 'id');
        $selectedFaculty = $course->faculty->pluck('id')->toArray(); // Get IDs of currently associated faculty

        return view('admin.courses.edit', compact('course', 'specializations', 'faculty', 'selectedFaculty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

         DB::beginTransaction();
        try {
            // Update course details
            $course->update($validated);

            // Sync faculty members
            if ($request->has('faculty_ids')) {
                $pivotData = array_fill(0, count($validated['faculty_ids']), ['semester' => $validated['semester']]);
                $syncData = array_combine($validated['faculty_ids'], $pivotData);
                $course->faculty()->sync($syncData);
            } else {
                 $course->faculty()->sync([]);
            }

             // Handle Course Resources (More complex: Add new, maybe update existing, implicitly removes missing ones if managed fully here)
             // Simple approach: Delete old, add new based on request
             // $course->resources()->delete(); // Delete all existing first (be careful with this)
            // A better approach involves tracking IDs to update/delete specific ones.
            // For simplicity here, we'll focus on adding new/keeping existing structure from request.
            // This part needs careful implementation based on exact UI for resource management.
            // Assuming the request sends a full list of desired resources:
            $existingResourceIds = $course->resources->pluck('id')->toArray();
            $submittedResourceData = $validated['resources'] ?? [];
            $submittedResourceIds = [];

            foreach ($submittedResourceData as $index => $resourceData) {
                 // Add semester if missing (important for new/updated resources)
                if (!isset($resourceData['semester'])) {
                    $resourceData['semester'] = $validated['semester'];
                }

                if (!empty($resourceData['title_ar']) && !empty($resourceData['url']) && !empty($resourceData['type'])) {
                    if (isset($resourceData['id']) && in_array($resourceData['id'], $existingResourceIds)) {
                        // Update existing resource
                        $resource = CourseResource::find($resourceData['id']);
                        if ($resource) {
                            $resource->update($resourceData);
                            $submittedResourceIds[] = $resource->id;
                        }
                    } else {
                        // Create new resource
                        $newResource = $course->resources()->create($resourceData);
                        $submittedResourceIds[] = $newResource->id;
                    }
                }
            }
             // Delete resources that were present before but not submitted now
            $idsToDelete = array_diff($existingResourceIds, $submittedResourceIds);
            if (!empty($idsToDelete)) {
                 CourseResource::destroy($idsToDelete);
            }


            DB::commit();

            return redirect()->route('admin.courses.index')
                             ->with('success', 'تم تعديل المقرر بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
             Log::error('Error updating course: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'حدث خطأ أثناء تعديل المقرر: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
         try {
            // Add checks if needed (e.g., if students are enrolled, etc.)
            // Need to detach related faculty and delete related resources first
            DB::transaction(function () use ($course) {
                $course->faculty()->detach(); // Detach faculty links
                $course->resources()->delete(); // Delete related resources
                $course->delete(); // Delete the course itself
            });

            return redirect()->route('admin.courses.index')
                             ->with('success', 'تم حذف المقرر وجميع موارده بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting course: ' . $e->getMessage());
            return redirect()->route('admin.courses.index')
                             ->with('error', 'حدث خطأ أثناء حذف المقرر: ' . $e->getMessage());
        }
    }

     /**
     * Remove a specific course resource.
     */
    public function destroyResource(Course $course, CourseResource $resource): RedirectResponse
    {
        // Optional: Add authorization check if needed
        try {
            $resource->delete();
            return redirect()->route('admin.courses.edit', $course) // Redirect back to edit page
                             ->with('success', 'تم حذف المورد بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting course resource: ' . $e->getMessage());
             return redirect()->route('admin.courses.edit', $course)
                             ->with('error', 'حدث خطأ أثناء حذف المورد.');
        }
    }
}