@extends('admin.layouts.app')

@section('title', 'إدارة وسائط الجامعة')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">وسائط الجامعة (صور وفيديوهات)</h1>
        <a href="{{ route('admin.media.create') }}" class="btn btn-primary">رفع وسيط جديد</a>
    </div>

     {{-- Filter Form --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.media.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                         <label for="category" class="form-label">التصنيف</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">-- كل التصنيفات --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                         <label for="media_type" class="form-label">نوع الوسيط</label>
                         <select name="media_type" id="media_type" class="form-select">
                            <option value="">-- الكل --</option>
                            <option value="image" {{ request('media_type') == 'image' ? 'selected' : '' }}>صورة</option>
                            <option value="video" {{ request('media_type') == 'video' ? 'selected' : '' }}>فيديو</option>
                        </select>
                    </div>
                     <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-secondary w-100">فلترة</button>
                    </div>
                     <div class="col-md-2 mb-2">
                        <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary w-100">إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="row">
        @forelse ($mediaItems as $item)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                @if($item->media_type == 'image')
                    <img src="{{ Storage::url($item->url) }}" class="card-img-top" alt="{{ $item->title_ar }}" style="height: 200px; object-fit: cover;">
                @else
                    {{-- Placeholder for video or link to video --}}
                    <div class="text-center p-5 bg-light" style="height: 200px;">
                        <i class="fas fa-video fa-3x text-gray-400"></i>
                         <p><a href="{{ Storage::url($item->url) }}" target="_blank">تشغيل الفيديو</a></p>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title fs-6">{{ $item->title_ar ?: 'بدون عنوان' }}</h5>
                    <p class="card-text small text-muted">{{ $item->description_ar }}</p>
                     <p class="card-text"><small class="text-muted">التصنيف: {{ $item->category ?? 'غير مصنف' }}</small></p>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الوسيط؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">لا توجد وسائط متاحة حالياً.</div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $mediaItems->appends(request()->query())->links() }}
    </div>

</div>
@endsection