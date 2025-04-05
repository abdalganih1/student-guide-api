@extends('admin.layouts.app')
@section('title', 'إدارة الكادر التدريسي')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">الكادر التدريسي</h1>
        <a href="{{ route('admin.faculty.create') }}" class="btn btn-primary">إضافة عضو جديد</a>
    </div>
    <div class="card shadow mb-4">
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
                        @forelse ($facultyMembers as $member)
                        <tr>
                            <td>{{ $loop->iteration + ($facultyMembers->currentPage() - 1) * $facultyMembers->perPage() }}</td>
                            <td>{{ $member->name_ar }}</td>
                            <td>{{ $member->title ?? '-' }}</td>
                            <td>{{ $member->email ?? '-' }}</td>
                            <td>{{ $member->office_location ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.faculty.edit', $member) }}" class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.faculty.destroy', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف عضو هيئة التدريس هذا؟');">
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
                            <td colspan="6" class="text-center">لا يوجد أعضاء هيئة تدريس حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $facultyMembers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection