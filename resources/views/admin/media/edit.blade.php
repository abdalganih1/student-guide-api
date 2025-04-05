{{-- resources/views/admin/media/edit.blade.php --}}

@extends('admin.layouts.app') {{-- افترض أن لديك layout رئيسي --}}

@section('title', 'تعديل وسيط')

@section('content')
<div class="container mt-4">
    <h1>تعديل وسيط</h1>

    {{-- عرض رسائل الأخطاء العامة --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- عرض أخطاء التحقق من الصحة --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- لاحظ تغيير الـ action و method و إضافة enctype للتعامل مع الملفات --}}
    <form action="{{ route('admin.media.update', $medium->id) }}" method="POST" enctype="multipart/form-data">
        @csrf {{-- حماية ضد هجمات CSRF --}}
        @method('PUT') {{-- تحديد طريقة HTTP للتحديث --}}

        <div class="mb-3">
            <label for="title_ar" class="form-label">العنوان (عربي) <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" value="{{ old('title_ar', $medium->title_ar) }}" required>
            @error('title_ar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="title_en" class="form-label">العنوان (إنجليزي)</label>
            <input type="text" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" value="{{ old('title_en', $medium->title_en) }}">
            @error('title_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description_ar" class="form-label">الوصف (عربي)</label>
            <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" rows="3">{{ old('description_ar', $medium->description_ar) }}</textarea>
            @error('description_ar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description_en" class="form-label">الوصف (إنجليزي)</label>
            <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" rows="3">{{ old('description_en', $medium->description_en) }}</textarea>
            @error('description_en')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

         <div class="mb-3">
            <label for="media_type" class="form-label">نوع الوسيط <span class="text-danger">*</span></label>
            <select class="form-select @error('media_type') is-invalid @enderror" id="media_type" name="media_type" required>
                <option value="image" {{ old('media_type', $medium->media_type) == 'image' ? 'selected' : '' }}>صورة</option>
                <option value="video" {{ old('media_type', $medium->media_type) == 'video' ? 'selected' : '' }}>فيديو</option>
            </select>
             @error('media_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
             <small class="form-text text-muted">إذا قمت بتغيير النوع، يجب رفع ملف جديد من النوع الصحيح.</small>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">التصنيف</label>
            {{-- استخدام datalist لتوفير اقتراحات مع السماح بإدخال جديد --}}
            <input list="categories-list" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $medium->category) }}">
            <datalist id="categories-list">
                @foreach($categories as $categoryOption)
                    <option value="{{ $categoryOption }}">
                @endforeach
            </datalist>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
             <label class="form-label">الملف الحالي:</label>
             @if($medium->url)
                 @if($medium->media_type == 'image')
                     <img src="{{ Storage::url($medium->url) }}" alt="{{ $medium->title_ar }}" style="max-height: 150px; display: block; margin-bottom: 10px;">
                 @elseif($medium->media_type == 'video')
                     <video controls width="300" style="display: block; margin-bottom: 10px;">
                         <source src="{{ Storage::url($medium->url) }}" type="video/mp4"> {{-- قد تحتاج لتحديد النوع الصحيح --}}
                         متصفحك لا يدعم عرض الفيديو.
                     </video>
                 @endif
                 <small class="form-text text-muted">المسار: {{ $medium->url }}</small>
             @else
                 <p class="text-muted">لا يوجد ملف حالي.</p>
             @endif
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">رفع ملف جديد (اختياري - سيحل محل الملف الحالي)</label>
            <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file">
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">اتركه فارغًا للاحتفاظ بالملف الحالي.</small>
        </div>


        <button type="submit" class="btn btn-primary">تحديث الوسيط</button>
        <a href="{{ route('admin.media.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection