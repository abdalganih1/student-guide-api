@extends('admin.layouts.app')

@section('title', 'إدارة الاختصاصات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">الاختصاصات الأكاديمية</h1>
        <a href="{{ route('admin.specializations.create') }}" class="btn btn-primary">إضافة اختصاص جديد</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
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
                            <td>{{ $specialization->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.specializations.edit', $specialization) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                                <form action="{{ route('admin.specializations.destroy', $specialization) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الاختصاص؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد اختصاصات متاحة حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- روابط التقسيم لصفحات --}}
            <div class="d-flex justify-content-center">
                {{ $specializations->links() }}
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