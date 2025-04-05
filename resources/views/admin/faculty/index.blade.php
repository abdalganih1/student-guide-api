@extends('admin.layouts.app')
@section('title', 'إدارة الكادر التدريسي')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">الكادر التدريسي</h1>
        <a href="{{ route('admin.faculty.create') }}" class="btn btn-primary"> <i class="fas fa-plus fa-sm"></i> إضافة عضو جديد</a>
    </div>

    {{-- عرض رسائل النجاح أو الخطأ --}}
    @include('admin.partials.alerts')

    {{-- Search Form (Optional) --}}
     <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.faculty.index') }}" class="mb-0">
                <div class="row align-items-end">
                    <div class="col-md-6 mb-2">
                        <label for="search" class="form-label">بحث (بالاسم أو البريد)</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="ادخل اسم العضو أو بريده الإلكتروني...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-secondary w-100"> <i class="fas fa-search fa-sm"></i> بحث</button>
                    </div>
                     <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.faculty.index') }}" class="btn btn-outline-secondary w-100"> <i class="fas fa-redo fa-sm"></i> إظهار الكل</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow mb-4">
         <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة أعضاء هيئة التدريس</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم (عربي)</th>
                            <th>اللقب</th>
                            <th>البريد الإلكتروني</th>
                            <th>المكتب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Note: Make sure the variable passed from controller is $facultyMembers --}}
                        @forelse ($facultyMembers as $member)
                        <tr>
                            <td>{{ $loop->iteration + ($facultyMembers->currentPage() - 1) * $facultyMembers->perPage() }}</td>
                            <td>{{ $member->name_ar }}</td>
                            <td>{{ $member->title ?? '-' }}</td>
                            <td>{{ $member->email ?? '-' }}</td>
                            <td>{{ $member->office_location ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.faculty.edit', $member->id) }}" class="btn btn-sm btn-warning me-1" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.faculty.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف عضو هيئة التدريس هذا؟ تأكد من أنه غير مرتبط بأي مقررات أو مشاريع حالية.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                {{-- <a href="{{ route('admin.faculty.show', $member->id) }}" class="btn btn-sm btn-info ms-1" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">لا يوجد أعضاء هيئة تدريس حالياً يطابقون معايير البحث.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{-- Make sure to append search query if using search --}}
                {{ $facultyMembers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection