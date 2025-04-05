@extends('admin.layouts.app')

@section('title', 'تعديل المقرر: ' . $course->name_ar)

 @push('styles')
     <style>
        .resource-entry { border: 1px solid #eee; padding: 10px; margin-bottom: 10px; border-radius: 5px; background: #f9f9f9; }
        .resource-entry .btn-danger { float: left; } /* Position remove button */
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">تعديل المقرر: {{ $course->name_ar }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Course Details --}}
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="specialization_id" class="form-label">الاختصاص <span class="text-danger">*</span></label>
                        <select class="form-select @error('specialization_id') is-invalid @enderror" id="specialization_id" name="specialization_id" required>
                            <option value="" disabled>-- اختر الاختصاص --</option>
                            @foreach($specializations as $id => $name)
                            <option value="{{ $id }}" {{ old('specialization_id', $course->specialization_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('specialization_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">رمز المقرر <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $course->code) }}" required>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">اسم المقرر (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $course->name_ar) }}" required>
                         @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">اسم المقرر (إنجليزي)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $course->name_en) }}">
                         @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                 <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (عربي)</label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3">{{ old('description_ar', $course->description_ar) }}</textarea>
                     @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                 <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (إنجليزي)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3">{{ old('description_en', $course->description_en) }}</textarea>
                     @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                         <label for="semester" class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" value="{{ old('semester', $course->semester) }}" placeholder="مثال: ربيع 2025" required>
                        @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                         <label for="year_level" class="form-label">مستوى السنة</label>
                        <input type="number" class="form-control @error('year_level') is-invalid @enderror" id="year_level" name="year_level" value="{{ old('year_level', $course->year_level) }}" min="1" max="6">
                        @error('year_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                 {{-- Faculty Selection --}}
                <div class="mb-3">
                    <label for="faculty_ids" class="form-label">الكادر التدريسي</label>
                    <select class="form-select @error('faculty_ids') is-invalid @enderror" id="faculty_ids" name="faculty_ids[]" multiple size="5">
                        @foreach($faculty as $id => $name)
                        {{-- Check if this faculty ID is in the old input OR currently associated with the course --}}
                        <option value="{{ $id }}" {{ in_array($id, old('faculty_ids', $selectedFaculty)) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                     @error('faculty_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">اضغط Ctrl (أو Cmd) لتحديد أكثر من أستاذ.</div>
                </div>

                <hr>
                {{-- Course Resources --}}
                <h4>موارد المقرر</h4>
                 <div id="resources-container">
                    {{-- Loop through existing or old resources --}}
                    @php
                        $resources = old('resources', $course->resources->toArray());
                        // Ensure resources is an array even if empty
                        if (!is_array($resources)) $resources = [];
                    @endphp
                    @foreach($resources as $key => $resource)
                        <div class="resource-entry row mb-2">
                            {{-- Hidden ID for existing resources --}}
                            <input type="hidden" name="resources[{{$key}}][id]" value="{{ $resource['id'] ?? '' }}">
                            <div class="col-md-4">
                                <label class="form-label small">عنوان المورد (عربي) <span class="text-danger">*</span></label>
                                <input type="text" name="resources[{{$key}}][title_ar]" class="form-control form-control-sm @error('resources.'.$key.'.title_ar') is-invalid @enderror" value="{{ $resource['title_ar'] ?? '' }}" required>
                                @error('resources.'.$key.'.title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">رابط المورد (URL) <span class="text-danger">*</span></label>
                                <input type="url" name="resources[{{$key}}][url]" class="form-control form-control-sm @error('resources.'.$key.'.url') is-invalid @enderror" value="{{ $resource['url'] ?? '' }}" required>
                                @error('resources.'.$key.'.url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">النوع <span class="text-danger">*</span></label>
                                <select name="resources[{{$key}}][type]" class="form-select form-select-sm @error('resources.'.$key.'.type') is-invalid @enderror" required>
                                    <option value="lecture" {{ ($resource['type'] ?? '') == 'lecture' ? 'selected' : '' }}>محاضرة</option>
                                    <option value="training_course" {{ ($resource['type'] ?? '') == 'training_course' ? 'selected' : '' }}>دورة تدريبية</option>
                                    <option value="document" {{ ($resource['type'] ?? '') == 'document' ? 'selected' : '' }}>مستند</option>
                                    <option value="link" {{ ($resource['type'] ?? '') == 'link' ? 'selected' : '' }}>رابط خارجي</option>
                                </select>
                                @error('resources.'.$key.'.type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-1 align-self-end">
                                 {{-- Use the specific route for deleting existing resource via controller action --}}
                                 @if(isset($resource['id']))
                                    {{-- Form to delete specific resource (Alternative to JS removal if preferred) --}}
                                    {{-- <form action="{{ route('admin.courses.resources.destroy', [$course, $resource['id']]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المورد؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">X</button>
                                    </form> --}}
                                     <button type="button" class="btn btn-sm btn-danger remove-resource-btn">X</button>
                                 @else
                                    {{-- Button for removing newly added (not yet saved) entries --}}
                                    <button type="button" class="btn btn-sm btn-danger remove-resource-btn">X</button>
                                 @endif
                            </div>
                             <div class="col-md-12 mt-1">
                                <label class="form-label small">وصف المورد (اختياري)</label>
                                <input type="text" name="resources[{{$key}}][description_ar]" class="form-control form-control-sm" value="{{ $resource['description_ar'] ?? '' }}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-resource-btn" class="btn btn-sm btn-outline-primary mt-2">إضافة مورد جديد</button>


                <div class="mt-4">
                    <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

 @push('scripts')
{{-- Include the same JS as in create.blade.php for adding/removing resources --}}
<script>
     document.addEventListener('DOMContentLoaded', function() {
        let resourceIndex = {{ count($resources) }}; // Start index based on existing/old resources
        const resourcesContainer = document.getElementById('resources-container');
        const addResourceBtn = document.getElementById('add-resource-btn');

        addResourceBtn.addEventListener('click', function() {
            const newEntry = document.createElement('div');
            newEntry.classList.add('resource-entry', 'row', 'mb-2');
            newEntry.innerHTML = `
                <input type="hidden" name="resources[${resourceIndex}][id]" value="">
                <div class="col-md-4"><label class="form-label small">عنوان (عربي)*</label><input type="text" name="resources[${resourceIndex}][title_ar]" class="form-control form-control-sm" required></div>
                <div class="col-md-4"><label class="form-label small">رابط (URL)*</label><input type="url" name="resources[${resourceIndex}][url]" class="form-control form-control-sm" required></div>
                <div class="col-md-3"><label class="form-label small">النوع*</label><select name="resources[${resourceIndex}][type]" class="form-select form-select-sm" required><option value="lecture">محاضرة</option><option value="training_course">دورة</option><option value="document">مستند</option><option value="link">رابط</option></select></div>
                <div class="col-md-1 align-self-end"><button type="button" class="btn btn-sm btn-danger remove-resource-btn">X</button></div>
                <div class="col-md-12 mt-1"><label class="form-label small">وصف (اختياري)</label><input type="text" name="resources[${resourceIndex}][description_ar]" class="form-control form-control-sm"></div>
            `;
            resourcesContainer.appendChild(newEntry);
            resourceIndex++;
        });

         resourcesContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-resource-btn')) {
                // Find the resource entry div
                const resourceEntry = event.target.closest('.resource-entry');
                if (resourceEntry) {
                    // Optional: If it's an existing resource (has an ID), mark it for deletion instead of removing from DOM immediately
                    // This requires backend logic to handle deletion based on a flag or missing ID in the submission.
                    // Simple approach: Just remove from DOM. Backend update logic handles what's submitted.
                    resourceEntry.remove();
                }
            }
        });
    });
</script>
@endpush