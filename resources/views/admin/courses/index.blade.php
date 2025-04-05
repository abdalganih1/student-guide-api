@extends('admin.layouts.app')

@section('title', 'إدارة المقررات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">المقررات الدراسية</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">إضافة مقرر جديد</a>
    </div>

    {{-- Filter Form (Optional) --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.courses.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <select name="specialization_id" class="form-select">
                            <option value="">-- اختر الاختصاص للفلترة --</option>
                            @foreach ($specializations as $id => $name)
                                <option value="{{ $id }}" {{ request('specialization_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-secondary">فلترة</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الرمز</th>
                            <th>الاسم (عربي)</th>
                            <th>الاختصاص</th>
                            <th>الفصل</th>
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
                            <td>{{ $course->semester }}</td>
                            <td>
                                {{-- Display faculty names --}}
                                {{ $course->faculty->pluck('name_ar')->implode(', ') ?: '-' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا المقرر وجميع موارده؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">لا توجد مقررات متاحة حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{-- Append filter parameters to pagination links --}}
                {{ $courses->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection