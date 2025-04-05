@extends('admin.layouts.app')
@section('title', 'إدارة مشاريع التخرج')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">مشاريع التخرج</h1>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">إضافة مشروع جديد</a>
    </div>

     {{-- Filter Form --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.projects.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                         <label for="specialization_id" class="form-label">الاختصاص</label>
                        <select name="specialization_id" id="specialization_id" class="form-select">
                            <option value="">-- كل الاختصاصات --</option>
                            @foreach ($specializations as $id => $name)
                                <option value="{{ $id }}" {{ request('specialization_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="year" class="form-label">السنة</label>
                         <select name="year" id="year" class="form-select">
                            <option value="">-- كل السنوات --</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                     <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-secondary w-100">فلترة</button>
                    </div>
                     <div class="col-md-2 mb-2">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary w-100">إعادة تعيين</a>
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
                            <th>العنوان (عربي)</th>
                            <th>الاختصاص</th>
                            <th>السنة</th>
                            <th>الفصل</th>
                            <th>المشرف</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                        <tr>
                            <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}</td>
                            <td>{{ $project->title_ar }}</td>
                            <td>{{ $project->specialization->name_ar ?? '-' }}</td>
                            <td>{{ $project->year }}</td>
                            <td>{{ $project->semester }}</td>
                            <td>{{ $project->supervisor->name_ar ?? '-' }}</td>
                            <td>
                                {{-- <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-info" title="عرض التفاصيل"><i class="fas fa-eye"></i></a> --}}
                                <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
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
                            <td colspan="7" class="text-center">لا توجد مشاريع تخرج متاحة حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $projects->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection