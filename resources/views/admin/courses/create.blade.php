@extends('admin.layouts.app')

@section('title', 'إضافة مقرر جديد')

@push('styles')
    {{-- Add styles for Select2 or other libraries if used --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <style>
        .resource-entry { border: 1px solid #eee; padding: 10px; margin-bottom: 10px; border-radius: 5px; background: #f9f9f9; }
        .resource-entry .btn-danger { float: left; } /* Position remove button */
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">إضافة مقرر جديد</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf

                {{-- Course Details --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="specialization_id" class="form-label">الاختصاص <span class="text-danger">*</span></label>
                        <select class="form-select @error('specialization_id') is-invalid @enderror" id="specialization_id" name="specialization_id" required>
                            <option value="" disabled selected>-- اختر الاختصاص --</option>
                            @foreach($specializations as $id => $name)
                            <option value="{{ $id }}" {{ old('specialization_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('specialization_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">رمز المقرر <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">اسم المقرر (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required>
                         @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">اسم المقرر (إنجليزي)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}">
                         @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (عربي)</label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3">{{ old('description_ar') }}</textarea>
                     @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (إنجليزي)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3">{{ old('description_en') }}</textarea>
                     @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                 <div class="row">
                    <div class="col-md-6 mb-3">
                         <label for="semester" class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
                         {{-- You might want a more dynamic way to get semesters --}}
                        <input type="text" class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" value="{{ old('semester', 'ربيع 2025') }}" placeholder="مثال: ربيع 2025" required>
                        @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                         <label for="year_level" class="form-label">مستوى السنة</label>
                        <input type="number" class="form-control @error('year_level') is-invalid @enderror" id="year_level" name="year_level" value="{{ old('year_level') }}" min="1" max="6">
                        @error('year_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Faculty Selection --}}
                <div class="mb-3">
                    <label for="faculty_ids" class="form-label">الكادر التدريسي</label>
                    <select class="form-select @error('faculty_ids') is-invalid @enderror" id="faculty_ids" name="faculty_ids[]" multiple size="5">
                         {{-- Use 'multiple' for multi-select --}}
                         {{-- Add '[]' to the name for array submission --}}
                        @foreach($faculty as $id => $name)
                        <option value="{{ $id }}" {{ in_array($id, old('faculty_ids', [])) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                     @error('faculty_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     <div class="form-text">اضغط Ctrl (أو Cmd) لتحديد أكثر من أستاذ.</div>
                </div>

                <hr>
                {{-- Course Resources --}}
                <h4>موارد المقرر</h4>
                <div id="resources-container">
                    {{-- Existing resources from 'old' input if validation failed --}}
                     @if(old('resources'))
                         @foreach(old('resources') as $key => $resource)
                            <div class="resource-entry row mb-2">
                                <input type="hidden" name="resources[{{$key}}][id]" value="{{ $resource['id'] ?? '' }}"> {{-- Hidden ID for existing resources during update --}}
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
                                     <button type="button" class="btn btn-sm btn-danger remove-resource-btn">X</button>
                                </div>
                                 <div class="col-md-12 mt-1">
                                    <label class="form-label small">وصف المورد (اختياري)</label>
                                    <input type="text" name="resources[{{$key}}][description_ar]" class="form-control form-control-sm" value="{{ $resource['description_ar'] ?? '' }}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{-- Placeholder for JS to add new entries --}}
                </div>
                <button type="button" id="add-resource-btn" class="btn btn-sm btn-outline-primary mt-2">إضافة مورد جديد</button>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">حفظ المقرر</button>
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // // Initialize Select2 if used
        // $('#faculty_ids').select2();

        let resourceIndex = {{ old('resources') ? count(old('resources')) : 0 }}; // Start index after old inputs
        const resourcesContainer = document.getElementById('resources-container');
        const addResourceBtn = document.getElementById('add-resource-btn');

        addResourceBtn.addEventListener('click', function() {
            const newEntry = document.createElement('div');
            newEntry.classList.add('resource-entry', 'row', 'mb-2');
            newEntry.innerHTML = `
                <input type="hidden" name="resources[${resourceIndex}][id]" value=""> {{-- No ID for new entries --}}
                <div class="col-md-4">
                    <label class="form-label small">عنوان المورد (عربي) <span class="text-danger">*</span></label>
                    <input type="text" name="resources[${resourceIndex}][title_ar]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small">رابط المورد (URL) <span class="text-danger">*</span></label>
                    <input type="url" name="resources[${resourceIndex}][url]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">النوع <span class="text-danger">*</span></label>
                    <select name="resources[${resourceIndex}][type]" class="form-select form-select-sm" required>
                        <option value="lecture">محاضرة</option>
                        <option value="training_course">دورة تدريبية</option>
                        <option value="document">مستند</option>
                        <option value="link">رابط خارجي</option>
                    </select>
                </div>
                <div class="col-md-1 align-self-end">
                     <button type="button" class="btn btn-sm btn-danger remove-resource-btn">X</button>
                </div>
                 <div class="col-md-12 mt-1">
                    <label class="form-label small">وصف المورد (اختياري)</label>
                    <input type="text" name="resources[${resourceIndex}][description_ar]" class="form-control form-control-sm">
                </div>
            `;
            resourcesContainer.appendChild(newEntry);
            resourceIndex++;
        });

        // Event listener for removing resource entries (using event delegation)
        resourcesContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-resource-btn')) {
                event.target.closest('.resource-entry').remove();
            }
        });
    });
</script>
@endpush