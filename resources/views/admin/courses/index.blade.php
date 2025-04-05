@extends('admin.layouts.app')

@section('title', 'إدارة المقررات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">المقررات الدراسية</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary"> <i class="fas fa-plus fa-sm"></i> إضافة مقرر جديد</a>
    </div>

    {{-- عرض رسائل النجاح أو الخطأ --}}
    @include('admin.partials.alerts')

    {{-- Filter Form (Optional) --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.courses.index') }}" class="mb-0">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label for="specialization_id" class="form-label">الاختصاص</label>
                        <select name="specialization_id" id="specialization_id" class="form-select">
                            <option value="">-- اختر الاختصاص للفلترة --</option>
                            @foreach ($specializations as $id => $name)
                                <option value="{{ $id }}" {{ request('specialization_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-4 mb-2">
                        <label for="search" class="form-label">بحث (بالاسم أو الرمز)</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="ادخل اسم المقرر أو رمزه...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-secondary w-100"> <i class="fas fa-filter fa-sm"></i> فلترة</button>
                    </div>
                     <div class="col-md-2 mb-2">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary w-100"> <i class="fas fa-redo fa-sm"></i> إعادة تعيين</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المقررات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الرمز</th>
                            <th>الاسم (عربي)</th>
                            <th>الاختصاص</th>
                            <th>الفصل</th> {{-- تأكد من وجود بيانات لهذا العامود أو احذفه --}}
                            <th>الأساتذة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                        <tr>
                            <td>{{ $loop->iteration + ($courses->currentPage() - 1) * $courses->perPage() }}</td>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->name_ar }}</td>
                            <td>{{ $course->specialization->name_ar ?? '-' }}</td>
                            {{-- تأكد من أن لديك حقل 'semester' في جدول المقررات أو علاقة تجلبه --}}
                            <td>{{ $course->semester ?? '-' }}</td>
                            <td>
                                {{-- Display faculty names --}}
                                {{ $course->faculty->pluck('name_ar')->implode(', ') ?: '-' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-warning me-1" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المقرر؟ سيتم أيضاً فك ربطه بالأساتذة وحذف موارده.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                {{-- يمكنك إضافة زر عرض تفاصيل هنا إن لزم الأمر --}}
                                {{-- <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-info ms-1" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد مقررات متاحة حالياً تطابق معايير البحث.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{-- Append filter parameters to pagination links --}}
                {{ $courses->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection