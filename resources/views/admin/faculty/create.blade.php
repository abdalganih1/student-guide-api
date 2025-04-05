@extends('admin.layouts.app')
@section('title', 'إضافة عضو هيئة تدريس')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">إضافة عضو هيئة تدريس جديد</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.faculty.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_ar" class="form-label">الاسم (بالعربية) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required>
                        @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">الاسم (بالإنجليزية)</label>
                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en') }}">
                         @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">اللقب العلمي</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                 <div class="mb-3">
                    <label for="office_location" class="form-label">موقع المكتب</label>
                    <input type="text" class="form-control @error('office_location') is-invalid @enderror" id="office_location" name="office_location" value="{{ old('office_location') }}">
                    @error('office_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">حفظ</button>
                    <a href="{{ route('admin.faculty.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection