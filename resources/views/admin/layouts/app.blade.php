<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- مهم لطلبات AJAX إذا استخدمت --}}

    <title>@yield('title', 'لوحة التحكم - دليل الطالب')</title>

    <!-- Bootstrap CSS (CDN RTL) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3flBFAEvoPGcrtMQCDXcKRSIGRjw" crossorigin="anonymous">

    {{-- يمكنك إضافة ملف CSS مخصص هنا --}}
    <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}"> {{-- مثال --}}

    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        .wrapper { display: flex; flex: 1; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar .sidebar-header { padding: 20px; background: #2c3136; text-align: center; }
        #sidebar ul.components { padding: 20px 0; border-bottom: 1px solid #47748b; }
        #sidebar ul p { color: #fff; padding: 10px; }
        #sidebar ul li a { padding: 10px; font-size: 1.1em; display: block; color: rgba(255,255,255,0.8); text-decoration: none; }
        #sidebar ul li a:hover { color: #fff; background: #495057; }
        #sidebar ul li.active > a, a[aria-expanded="true"] { color: #fff; background: #2c3136; }
        #content { width: 100%; padding: 20px; min-height: 100vh; transition: all 0.3s; }
        .navbar { padding: 15px 10px; background: #fff; border: none; border-radius: 0; margin-bottom: 20px; box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1); }
        .navbar-btn { box-shadow: none; outline: none !important; border: none; }
        /* يمكنك إضافة المزيد من التنسيقات حسب الحاجة */
    </style>
    @stack('styles') {{-- لإضافة styles من الواجهات الفرعية --}}
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>دليل الطالب</h3>
                <strong>دط</strong> {{-- اختصار عند تصغير الشريط الجانبي (مستقبلاً) --}}
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> {{-- مثال لاستخدام أيقونة FontAwesome --}}
                        لوحة التحكم
                    </a>
                </li>
                {{-- الروابط للأقسام الأخرى ستضاف هنا --}}
                <li class="{{ request()->routeIs('admin.specializations.*') ? 'active' : '' }}">
                     <a href="{{ route('admin.specializations.index') }}">الاختصاصات</a>
                </li>
                 <li class="{{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                     <a href="{{ route('admin.courses.index') }}">المقررات</a>
                </li>
                 <li class="{{ request()->routeIs('admin.faculty.*') ? 'active' : '' }}">
                     <a href="{{ route('admin.faculty.index') }}">الكادر التدريسي</a>
                </li>
                <li class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                     <a href="{{ route('admin.media.index') }}">وسائط الجامعة</a>
                </li>
                <li class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                     <a href="{{ route('admin.projects.index') }}">مشاريع التخرج</a>
                </li>
                 {{-- يمكن إضافة رابط لإدارة المستخدمين إذا لزم الأمر --}}
            </ul>

            {{-- يمكنك إضافة قوائم فرعية أو أقسام أخرى هنا --}}
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    {{-- يمكنك إضافة زر لفتح/إغلاق الشريط الجانبي هنا (يتطلب JS) --}}
                    <button type="button" id="sidebarCollapse" class="btn btn-info d-none">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>

                    <div class="collapse navbar-collapse d-flex justify-content-end">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                 <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::guard('admin')->user()->username }} {{-- عرض اسم المستخدم المدير --}}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    {{-- يمكنك إضافة رابط للملف الشخصي للمدير هنا --}}
                                    {{-- <li><a class="dropdown-item" href="#">الملف الشخصي</a></li> --}}
                                    {{-- <li><hr class="dropdown-divider"></li> --}}
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            تسجيل الخروج
                                        </a>
                                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            {{-- عرض رسائل الحالة (Flash Messages) --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
             @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
             @if ($errors->any() && !request()->routeIs('admin.login')) {{-- لا تعرض أخطاء التحقق العامة هنا إذا كنا في صفحة تسجيل الدخول --}}
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- المحتوى الرئيسي للصفحة --}}
            <div class="main-content">
                @yield('content')
            </div>

        </div>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    {{-- يمكنك إضافة FontAwesome أو مكتبات JS أخرى هنا --}}
    {{-- <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script> --}}

    @stack('scripts') {{-- لإضافة scripts من الواجهات الفرعية --}}
</body>
</html>