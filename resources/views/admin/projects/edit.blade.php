@extends('admin.layouts.app')
@section('title', 'تعديل مشروع: ' . $project->title_ar)
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">تعديل مشروع: {{ $project->title_ar }}</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
             <form action="{{ route('admin.projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Fields similar to create form, populated with $project data --}}
                {{-- Example for specialization dropdown --}}
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title_ar" class="form-label">عنوان المشروع (عربي) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" value="{{ old('title_ar', $project->title_ar) }}" required>
                         @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="title_en" class="form-label">عنوان المشروع (إنجليزي)</label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en', $project->title_en) }}">
                        @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="specialization_id" class="form-label">الاختصاص <span class="text-danger">*</span></label>
                        <select class="form-select @error('specialization_id') is-invalid @enderror" id="specialization_id" name="specialization_id" required>
                            <option value="" disabled>-- اختر الاختصاص --</option>
                            @foreach($specializations as $id => $name)
                            <option value="{{ $id }}" {{ old('specialization_id', $project->specialization_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                         @error('specialization_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                         <label for="supervisor_id" class="form-label">المشرف</label>
                        <select class="form-select @error('supervisor_id') is-invalid @enderror" id="supervisor_id" name="supervisor_id">
                            <option value="">-- اختر المشرف (اختياري) --</option>
                            @foreach($supervisors as $id => $name)
                            <option value="{{ $id }}" {{ old('supervisor_id', $project->supervisor_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                         @error('supervisor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
    <div class="col-md-6 mb-3">
        <label for="year" class="form-label">السنة <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year', date('Y')) }}" required min="2000">
        @error('year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    {{-- حقل الفصل الدراسي الجديد --}}
    <div class="col-md-6 mb-3">
        <label for="semester" class="form-label">الفصل الدراسي <span class="text-danger">*</span></label>
        <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
            <option value="" disabled {{ old('semester') ? '' : 'selected' }}>-- اختر الفصل --</option>
            <option value="خريف" {{ old('semester') == 'خريف' ? 'selected' : '' }}>خريف</option>
            <option value="ربيع" {{ old('semester') == 'ربيع' ? 'selected' : '' }}>ربيع</option>
            <option value="صيف" {{ old('semester') == 'صيف' ? 'selected' : '' }}>صيف</option> {{-- اختياري --}}
            {{-- أضف فصول أخرى إذا لزم الأمر --}}
        </select>
        @error('semester')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
                     <div class="col-md-4 mb-3">
                         <label for="student_name" class="form-label">اسم الطالب/الطلاب</label>
                        <input type="text" class="form-control @error('student_name') is-invalid @enderror" id="student_name" name="student_name" value="{{ old('student_name', $project->student_name) }}">
                         @error('student_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="abstract_ar" class="form-label">الملخص (عربي)</label>
                    <textarea class="form-control @error('abstract_ar') is-invalid @enderror" id="abstract_ar" name="abstract_ar" rows="4">{{ old('abstract_ar', $project->abstract_ar) }}</textarea>
                     @error('abstract_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                 <div class="mb-3">
                    <label for="abstract_en" class="form-label">الملخص (إنجليزي)</label>
                    <textarea class="form-control @error('abstract_en') is-invalid @enderror" id="abstract_en" name="abstract_en" rows="4">{{ old('abstract_en', $project->abstract_en) }}</textarea>
                     @error('abstract_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                <div class="mt-4">
                    <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection