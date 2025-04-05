@extends('admin.layouts.app')

@section('title', 'رفع وسيط جديد')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">رفع وسيط جديد (صورة أو فيديو)</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
             {{-- Add enctype for file uploads --}}
             <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                 <div class="mb-3">
                    <label for="file" class="form-label">الملف <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required accept="image/*,video/mp4,video/quicktime,video/x-msvideo"> {{-- Limit accepted types --}}
                    @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">الأنواع المسموحة: صور (jpg, png, gif, webp), فيديو (mp4, mov, avi). الحد الأقصى للحجم: (راجع إعدادات PHP/Server).</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title_ar" class="form-label">العنوان (بالعربية)</label>
                        <input type="text" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" value="{{ old('title_ar') }}">
                         @error('title_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="title_en" class="form-label">العنوان (بالإنجليزية)</label>
                        <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en') }}">
                         @error('title_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                 <div class="mb-3">
                    <label for="description_ar" class="form-label">الوصف (بالعربية)</label>
                    <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="2">{{ old('description_ar') }}</textarea>
                    @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                 <div class="mb-3">
                    <label for="description_en" class="form-label">الوصف (بالإنجليزية)</label>
                    <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="2">{{ old('description_en') }}</textarea>
                    @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                 <div class="row">
                    <div class="col-md-6 mb-3">
                         <label for="media_type" class="form-label">نوع الوسيط <span class="text-danger">*</span></label>
                         <select class="form-select @error('media_type') is-invalid @enderror" id="media_type" name="media_type" required>
                            <option value="" disabled selected>-- حدد النوع --</option>
                            <option value="image" {{ old('media_type') == 'image' ? 'selected' : '' }}>صورة</option>
                            <option value="video" {{ old('media_type') == 'video' ? 'selected' : '' }}>فيديو</option>
                        </select>
                         @error('media_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6 mb-3">
                         <label for="category" class="form-label">التصنيف</label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" list="category-suggestions" placeholder="مثال: مختبر, قاعة, مكتبة...">
                         <datalist id="category-suggestions">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                         @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">رفع وحفظ</button>
                    <a href="{{ route('admin.media.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection