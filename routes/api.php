<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- استيراد المتحكمات لـ API الإصدار الأول ---
// هذا يجعل استدعاء المتحكمات في تعريف المسارات أقصر وأنظف.
use App\Http\Controllers\Api\V1\SpecializationController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\FacultyController;
use App\Http\Controllers\Api\V1\UniversityMediaController;
use App\Http\Controllers\Api\V1\GraduationProjectController;
use App\Http\Controllers\Api\V1\SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| هنا يمكنك تسجيل مسارات الـ API لتطبيقك.
| يتم تحميل هذه المسارات بواسطة RouteServiceProvider
| ويتم تعيينها جميعًا إلى مجموعة middleware المسماة "api".
| middleware "api" يطبق ميزات مثل تحديد المعدل (throttling).
|
*/

// --- مسار Laravel Sanctum الافتراضي (اختياري لهذا المشروع) ---
// هذا المسار يُستخدم عادةً إذا كان لديك نظام توثيق للمستخدمين العاديين
// عبر API باستخدام Sanctum (مثل تسجيل دخول الطلاب لتطبيقهم).
// في حالتنا، API الطالب للقراءة فقط وقد لا تحتاج لتوثيق المستخدم هنا.
// يمكنك إبقاؤه أو حذفه إذا لم يكن هناك نظام تسجيل دخول للطلاب.
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// --- مسارات API الإصدار الأول (v1) ---
// نستخدم Route::prefix('v1') لتجميع كل مسارات الإصدار الأول تحت البادئة /api/v1/
// هذا مهم جداً لإدارة الإصدارات مستقبلاً. إذا قمت بتغييرات جذرية، يمكنك إنشاء v2.
// نستخدم أيضاً ->middleware('api') لضمان تطبيق middleware الـ API الافتراضي (مثل throttling).
// نستخدم ->name('api.v1.') لتعيين بادئة لأسماء المسارات، مما يسهل الإشارة إليها برمجياً (مثلاً في الاختبارات).
Route::prefix('v1')->name('api.v1.')->middleware('api')->group(function () {

    // --- نقاط النهاية للاختصاصات الأكاديمية (Specializations) ---
    // [GET] /api/v1/specializations
    // الغرض: جلب قائمة بجميع الاختصاصات المتاحة.
    // المتحكم: SpecializationController، الدالة: index.
    // الاستخدام في التطبيق: شاشة قائمة الاختصاصات.
    Route::get('/specializations', [SpecializationController::class, 'index'])
         ->name('specializations.index'); // اسم المسار: api.v1.specializations.index

    // [GET] /api/v1/specializations/{specialization}
    // الغرض: جلب تفاصيل اختصاص محدد (بناءً على الـ ID الخاص به).
    // يستخدم Route Model Binding: Laravel سيقوم تلقائيًا بجلب مودل Specialization المطابق للـ ID {specialization}.
    // المتحكم: SpecializationController، الدالة: show.
    // الاستخدام في التطبيق: شاشة تفاصيل الاختصاص (لعرض الوصف وقائمة المقررات لاحقاً).
    Route::get('/specializations/{specialization}', [SpecializationController::class, 'show'])
         ->where('specialization', '[0-9]+') // (اختياري) تقييد البارامتر ليكون أرقام فقط
         ->name('specializations.show'); // اسم المسار: api.v1.specializations.show

    // [GET] /api/v1/specializations/{specialization}/courses
    // الغرض: جلب قائمة المقررات الدراسية التابعة لاختصاص محدد.
    // يستخدم Route Model Binding لـ {specialization}.
    // المتحكم: CourseController، الدالة: indexBySpecialization (دالة مخصصة لهذا الغرض).
    // الاستخدام في التطبيق: ضمن شاشة تفاصيل الاختصاص لعرض قائمة المقررات الخاصة به.
    Route::get('/specializations/{specialization}/courses', [CourseController::class, 'indexBySpecialization'])
         ->where('specialization', '[0-9]+')
         ->name('specializations.courses.index'); // اسم المسار: api.v1.specializations.courses.index

    // --- نقاط النهاية للمقررات الدراسية (Courses) ---
    // [GET] /api/v1/courses
    // الغرض: جلب قائمة بجميع المقررات، مع إمكانية الفلترة (مثلاً عبر query parameters مثل /courses?specialization_id=1&search=math).
    // المتحكم: CourseController، الدالة: index.
    // الاستخدام في التطبيق: قد يُستخدم للبحث العام عن المقررات أو إذا كان هناك قسم لعرض كل المقررات.
    Route::get('/courses', [CourseController::class, 'index'])
         ->name('courses.index'); // اسم المسار: api.v1.courses.index

    // [GET] /api/v1/courses/{course}
    // الغرض: جلب تفاصيل مقرر محدد (الوصف، الأساتذة، الموارد).
    // يستخدم Route Model Binding لـ {course}.
    // المتحكم: CourseController، الدالة: show.
    // الاستخدام في التطبيق: شاشة تفاصيل المقرر.
    Route::get('/courses/{course}', [CourseController::class, 'show'])
         ->where('course', '[0-9]+')
         ->name('courses.show'); // اسم المسار: api.v1.courses.show

    // --- نقاط النهاية للكادر التدريسي (Faculty) ---
    // [GET] /api/v1/faculty
    // الغرض: جلب قائمة بأعضاء هيئة التدريس، مع إمكانية البحث (مثلاً /faculty?search=Ahmed).
    // المتحكم: FacultyController، الدالة: index.
    // الاستخدام في التطبيق: شاشة قائمة الكادر التدريسي.
    Route::get('/faculty', [FacultyController::class, 'index'])
         ->name('faculty.index'); // اسم المسار: api.v1.faculty.index

    // [GET] /api/v1/faculty/{faculty}
    // الغرض: جلب تفاصيل عضو هيئة تدريس محدد (إذا كانت الواجهة تتطلب ملف شخصي).
    // يستخدم Route Model Binding لـ {faculty}.
    // المتحكم: FacultyController، الدالة: show.
    // الاستخدام في التطبيق: شاشة ملف الأستاذ (إن وجدت).
    Route::get('/faculty/{faculty}', [FacultyController::class, 'show'])
        ->where('faculty', '[0-9]+')
        ->name('faculty.show'); // اسم المسار: api.v1.faculty.show

    // --- نقاط النهاية لوسائط الجامعة (University Media / Facilities) ---
    // [GET] /api/v1/media
    // الغرض: جلب قائمة بالصور والفيديوهات الخاصة بالمرافق، مع إمكانية الفلترة (مثلاً /media?category=lab&type=image).
    // المتحكم: UniversityMediaController، الدالة: index.
    // الاستخدام في التطبيق: شاشة المرافق الجامعية (لعرض المعرض).
    Route::get('/media', [UniversityMediaController::class, 'index'])
         ->name('media.index'); // اسم المسار: api.v1.media.index
    // ملاحظة: قد لا تحتاج لنقطة نهاية لعرض وسيط واحد (`show`) إذا كان العرض في التطبيق عبارة عن معرض فقط.

    // --- نقاط النهاية لمشاريع التخرج (Graduation Projects) ---
    // [GET] /api/v1/projects
    // الغرض: جلب قائمة بمشاريع التخرج، مع إمكانية الفلترة حسب الاختصاص والسنة والبحث (مثلاً /projects?specialization_id=2&year=2023&search=AI).
    // المتحكم: GraduationProjectController، الدالة: index.
    // الاستخدام في التطبيق: شاشة أرشيف مشاريع التخرج.
    Route::get('/projects', [GraduationProjectController::class, 'index'])
         ->name('projects.index'); // اسم المسار: api.v1.projects.index

    // [GET] /api/v1/projects/{project}
    // الغرض: جلب تفاصيل مشروع تخرج محدد (إذا كانت الواجهة تحتاج عرض تفاصيل أكثر للمشروع).
    // يستخدم Route Model Binding لـ {project}.
    // المتحكم: GraduationProjectController، الدالة: show.
    // الاستخدام في التطبيق: (اختياري) عند النقر على مشروع في القائمة لعرض تفاصيله (مثل الملخص).
    Route::get('/projects/{project}', [GraduationProjectController::class, 'show'])
         ->where('project', '[0-9]+')
         ->name('projects.show'); // اسم المسار: api.v1.projects.show


    // --- نقطة النهاية للبحث الشامل (Global Search) ---
    // [GET] /api/v1/search
    // الغرض: استقبال مصطلح بحث (query parameter 'q') والبحث في مختلف الجداول (الاختصاصات، المقررات، الأساتذة، المشاريع).
    // المتحكم: SearchController، الدالة: search.
    // الاستخدام في التطبيق: شريط البحث العلوي في الشاشة الرئيسية.
    Route::get('/search', [SearchController::class, 'search'])
         ->name('search'); // اسم المسار: api.v1.search

}); // نهاية مجموعة مسارات v1


// --- يمكنك إضافة مسارات إصدارات أخرى هنا مستقبلاً ---
// Route::prefix('v2')->name('api.v2.')->middleware('api')->group(function () {
//     // ... مسارات الإصدار الثاني ...
// });