@extends('admin.layouts.app')

@section('title', 'إدارة الاختصاصات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">الاختصاصات الأكاديمية</h1>
        <a href="{{ route('admin.specializations.create') }}" class="btn btn-primary"> <i class="fas fa-plus fa-sm"></i> إضافة اختصاص جديد</a>
    </div>

     {{-- عرض رسائل النجاح أو الخطأ --}}
    @include('admin.partials.alerts')

    {{-- Search Form (Optional) --}}
     <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.specializations.index') }}" class="mb-0">
                <div class="row align-items-end">
                    <div class="col-md-6 mb-2">
                        <label for="search" class="form-label">بحث (بالاسم العربي أو الإنجليزي)</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="ادخل اسم الاختصاص...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-secondary w-100"> <i class="fas fa-search fa-sm"></i> بحث</button>
                    </div>
                     <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.specializations.index') }}" class="btn btn-outline-secondary w-100"> <i class="fas fa-redo fa-sm"></i> إظهار الكل</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
         <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة الاختصاصات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم (عربي)</th>
                            <th>الاسم (إنجليزي)</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($specializations as $specialization)
                        <tr>
                            <td>{{ $loop->iteration + ($specializations->currentPage() - 1) * $specializations->perPage() }}</td>
                            <td>{{ $specialization->name_ar }}</td>
                            <td>{{ $specialization->name_en ?? '-' }}</td>
                            <td>{{ $specialization->created_at ? $specialization->created_at->format('Y-m-d') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.specializations.edit', $specialization->id) }}" class="btn btn-sm btn-warning me-1" title="تعديل">
                                    <i class="fas fa-edit"></i> {{-- تعديل --}}
                                </a>
                                <form action="{{ route('admin.specializations.destroy', $specialization->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الاختصاص؟ تأكد من عدم وجود مقررات مرتبطة به أولاً.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i> {{-- حذف --}}
                                    </button>
                                </form>
                                {{-- <a href="{{ route('admin.specializations.show', $specialization->id) }}" class="btn btn-sm btn-info ms-1" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد اختصاصات متاحة حالياً تطابق معايير البحث.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- روابط التقسيم لصفحات --}}
            <div class="d-flex justify-content-center mt-3">
                 {{-- Make sure to append search query if using search --}}
                {{ $specializations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @push('scripts')
    <script>
        // يمكنك هنا إضافة JS خاص بـ DataTables إذا أردت
    </script>
@endpush --}}