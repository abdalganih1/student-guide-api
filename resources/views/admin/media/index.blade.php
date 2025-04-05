@extends('admin.layouts.app')

@section('title', 'إدارة وسائط الجامعة')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">وسائط الجامعة (صور وفيديوهات)</h1>
        <a href="{{ route('admin.media.create') }}" class="btn btn-primary"> <i class="fas fa-upload fa-sm"></i> رفع وسيط جديد</a>
    </div>

     {{-- عرض رسائل النجاح أو الخطأ --}}
    @include('admin.partials.alerts')

     {{-- Filter Form --}}
    <div class="card shadow mb-4">
         <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">فلترة الوسائط</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.media.index') }}" class="mb-0">
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
                    <div class="col-md-3 mb-2">
                         <label for="media_type" class="form-label">نوع الوسيط</label>
                         <select name="media_type" id="media_type" class="form-select">
                            <option value="">-- الكل --</option>
                            <option value="image" {{ request('media_type') == 'image' ? 'selected' : '' }}>صورة</option>
                            <option value="video" {{ request('media_type') == 'video' ? 'selected' : '' }}>فيديو</option>
                        </select>
                    </div>
                     <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-secondary w-100"> <i class="fas fa-filter fa-sm"></i> فلترة</button>
                    </div>
                     <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary w-100"> <i class="fas fa-redo fa-sm"></i> إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

     <div class="card shadow mb-4">
         <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">معرض الوسائط</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse ($mediaItems as $item)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        @if($item->media_type == 'image' && $item->url)
                            <a href="{{ Storage::url($item->url) }}" target="_blank" data-lightbox="gallery" data-title="{{ $item->title_ar }}">
                                <img src="{{ Storage::url($item->url) }}" class="card-img-top" alt="{{ $item->title_ar }}" style="height: 200px; object-fit: cover; cursor: pointer;">
                            </a>
                        @elseif($item->media_type == 'video' && $item->url)
                            {{-- Placeholder for video or link to video --}}
                            <div class="text-center d-flex justify-content-center align-items-center bg-light position-relative" style="height: 200px;">
                                <i class="fas fa-video fa-3x text-gray-400"></i>
                                <a href="{{ Storage::url($item->url) }}" target="_blank" class="stretched-link" title="تشغيل الفيديو"></a>
                            </div>
                        @else
                             <div class="text-center d-flex justify-content-center align-items-center bg-light" style="height: 200px;">
                                <i class="fas fa-image-slash fa-3x text-gray-400"></i> {{-- Placeholder for missing file --}}
                            </div>
                        @endif
                        <div class="card-body pb-2">
                            <h5 class="card-title fs-6 text-truncate" title="{{ $item->title_ar }}">{{ $item->title_ar ?: 'بدون عنوان' }}</h5>
                            <p class="card-text small text-muted mb-1 text-truncate" title="{{ $item->description_ar }}">{{ $item->description_ar ?: 'لا يوجد وصف' }}</p>
                            <p class="card-text mb-0"><small class="text-muted">التصنيف: {{ $item->category ?? 'غير مصنف' }}</small></p>
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0 d-flex justify-content-end">
                            {{-- زر التعديل --}}
                            <a href="{{ route('admin.media.edit', $item->id) }}" class="btn btn-sm btn-outline-warning me-2" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            {{-- زر الحذف --}}
                            <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الوسيط؟ سيتم حذفه نهائياً من الخادم.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">لا توجد وسائط متاحة حالياً تطابق معايير البحث.</div>
                </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $mediaItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div> {{-- End Card --}}

</div>
@endsection

{{-- Optional: Add lightbox CSS/JS if you want the image preview --}}
{{-- @push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush --}}