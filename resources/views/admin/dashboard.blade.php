@extends('admin.layouts.app') {{-- يرث من الـ Layout الرئيسي --}}

@section('title', 'لوحة التحكم الرئيسية') {{-- تحديد عنوان الصفحة --}}

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">لوحة التحكم</h1>
            {{-- يمكنك إضافة زر إجراء سريع هنا --}}
            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
        </div>

        <!-- Content Row (Stats) -->
        <div class="row">

            <!-- Specializations Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                    الاختصاصات</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['specializations'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-graduation-cap fa-2x text-gray-300"></i> {{-- Icon Example --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-success shadow h-100 py-2">
                     <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                    المقررات الدراسية</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['courses'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faculty Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                 <div class="card border-start border-info shadow h-100 py-2">
                     <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                    الكادر التدريسي</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['faculty'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                 <div class="card border-start border-warning shadow h-100 py-2">
                     <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    مشاريع التخرج</div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $stats['projects'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row (Maybe charts or recent activity later) -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">مرحباً بك في لوحة التحكم</h6>
                    </div>
                    <div class="card-body">
                        <p>من هنا يمكنك إدارة محتوى دليل الطالب الجامعي.</p>
                        <p>استخدم الشريط الجانبي للتنقل بين الأقسام المختلفة.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

{{-- يمكنك إضافة أيقونات FontAwesome إذا أردت --}}
{{-- @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush --}}