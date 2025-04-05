<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController; // استيراد المتحكم
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminSpecializationController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminFacultyController;
use App\Http\Controllers\Admin\AdminUniversityMediaController;
use App\Http\Controllers\Admin\AdminGraduationProjectController;
// use App\Http\Controllers\Admin\AdminUserController;
// ... (مسار الـ Welcome الافتراضي) ...
Route::get('/', function () {
    // يمكنك توجيه المستخدم للوحة تحكم المدير إذا كان مسجل دخوله
    if (auth()->guard('admin')->check()) {
         return redirect()->route('admin.dashboard');
     }
     // أو توجيهه لصفحة تسجيل دخول المدير
     return redirect()->route('admin.login');
    // أو عرض صفحة welcome الافتراضية إذا أردت
    // return view('welcome');
})->name('home'); // تسمية المسار الرئيسي

// --- مسارات توثيق المدير ---
Route::prefix('admin')->name('admin.')->group(function () {
    // عرض نموذج تسجيل الدخول (إذا لم يكن مسجل دخوله)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest:admin')->name('login');
    // معالجة طلب تسجيل الدخول
    Route::post('/login', [LoginController::class, 'login'])->middleware('guest:admin');
    // معالجة طلب تسجيل الخروج (يجب أن يكون مسجل دخوله للوصول لهذا المسار)
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:admin')->name('logout');

    // --- هنا سنضيف مسارات لوحة التحكم المحمية لاحقاً ---

});

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // إدارة الاختصاصات (CRUD)
    Route::resource('specializations', AdminSpecializationController::class);

    // إدارة المقررات (CRUD)
    Route::resource('courses', AdminCourseController::class);
    // قد تحتاج مسارات إضافية لإدارة موارد المقرر داخل المقرر نفسه
    Route::delete('courses/{course}/resources/{resource}', [AdminCourseController::class, 'destroyResource'])->name('courses.resources.destroy');

    // إدارة الكادر التدريسي (CRUD)
    Route::resource('faculty', AdminFacultyController::class);

    // إدارة وسائط الجامعة (قد تحتاج فقط لـ index, create, store, destroy)
    Route::resource('media', AdminUniversityMediaController::class);

    // إدارة مشاريع التخرج (CRUD)
    Route::resource('projects', AdminGraduationProjectController::class);

    // (اختياري) إدارة مستخدمي لوحة التحكم
    // Route::resource('users', AdminUserController::class);

}); // نهاية مجموعة المسارات المحمية