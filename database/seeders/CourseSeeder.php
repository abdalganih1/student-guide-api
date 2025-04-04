<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course; // استيراد مودل المقرر
use App\Models\Specialization; // نحتاجه لجلب الـ ID
use App\Models\Faculty; // نحتاجه لجلب الـ ID وللربط
use App\Models\CourseResource; // استيراد مودل موارد المقرر

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Course::truncate(); // اختياري
        // CourseResource::truncate(); // مسح الموارد المرتبطة
        // \DB::table('course_faculty')->truncate(); // مسح جدول الربط

        // --- الحصول على IDs للاختصاصات والأساتذة ---
        // طريقة بسيطة (تفترض أن الـ IDs معروفة أو أنها 1، 2، 3...)
        $informaticsSpecId = Specialization::where('name_en', 'Informatics Engineering')->value('id') ?? 1;
        $telecomSpecId = Specialization::where('name_en', 'Telecommunications Engineering')->value('id') ?? 2;
        $businessSpecId = Specialization::where('name_en', 'Business Administration')->value('id') ?? 3;
        $pharmacySpecId = Specialization::where('name_en', 'Pharmacy')->value('id') ?? 4;

        $facultyAhmedId = Faculty::where('email', 'ahmed.m@university.edu')->value('id') ?? 1;
        $facultyFatimaId = Faculty::where('email', 'fatima.a@university.edu')->value('id') ?? 2;
        $facultyKhaledId = Faculty::where('email', 'khaled.y@university.edu')->value('id') ?? 3;
        $facultySaraId = Faculty::where('email', 'sara.i@university.edu')->value('id') ?? 4;
        $facultyOmarId = Faculty::where('email', 'omar.h@university.edu')->value('id') ?? 5;

        // --- تعريف الفصل الدراسي الحالي (كمثال) ---
        $currentSemester = "خريف 2024"; // أو أي قيمة مناسبة

        // --- إنشاء المقررات وربطها ---

        // == مقررات هندسة المعلوماتية ==
        $course1 = Course::create([
            'specialization_id' => $informaticsSpecId,
            'code' => 'CS101',
            'name_ar' => 'مقدمة في البرمجة',
            'name_en' => 'Introduction to Programming',
            'description_ar' => 'أساسيات البرمجة باستخدام لغة بايثون.',
            'semester' => $currentSemester,
            'year_level' => 1,
        ]);
        // ربط المقرر بالأساتذة في الفصل الحالي
        $course1->faculty()->attach([
            $facultyAhmedId => ['semester' => $currentSemester, 'role' => 'محاضر'],
            $facultyFatimaId => ['semester' => $currentSemester, 'role' => 'مساعد']
        ]);
        // إضافة موارد للمقرر
        CourseResource::create([
            'course_id' => $course1->id,
            'title_ar' => 'المحاضرة الأولى: المتغيرات وأنواع البيانات',
            'url' => 'http://example.com/lecture1.pdf',
            'type' => 'lecture',
            'semester' => $currentSemester,
        ]);
        CourseResource::create([
            'course_id' => $course1->id,
            'title_ar' => 'دورة بايثون للمبتدئين',
            'url' => 'http://example.com/python-course',
            'type' => 'training_course',
            'semester' => $currentSemester,
        ]);


        $course2 = Course::create([
            'specialization_id' => $informaticsSpecId,
            'code' => 'CS210',
            'name_ar' => 'هياكل البيانات والخوارزميات',
            'name_en' => 'Data Structures and Algorithms',
            'description_ar' => 'دراسة هياكل البيانات الأساسية وتصميم وتحليل الخوارزميات.',
            'semester' => $currentSemester, // قد يكون لفصل آخر في الواقع
            'year_level' => 2,
        ]);
        $course2->faculty()->attach([
            $facultyFatimaId => ['semester' => $currentSemester, 'role' => 'محاضر']
        ]);
        CourseResource::create([
            'course_id' => $course2->id,
            'title_ar' => 'شرح هياكل البيانات (فيديو)',
            'url' => 'http://example.com/ds-video',
            'type' => 'lecture', // أو 'link'
            'semester' => $currentSemester,
        ]);


        // == مقررات هندسة الاتصالات ==
        $course3 = Course::create([
            'specialization_id' => $telecomSpecId,
            'code' => 'TC101',
            'name_ar' => 'مبادئ الاتصالات',
            'name_en' => 'Principles of Communications',
            'description_ar' => 'مقدمة في نظم الاتصالات التماثلية والرقمية.',
            'semester' => $currentSemester,
            'year_level' => 1,
        ]);
        $course3->faculty()->attach([
            $facultyOmarId => ['semester' => $currentSemester, 'role' => 'محاضر']
        ]);

        // == مقررات إدارة الأعمال ==
        $course4 = Course::create([
            'specialization_id' => $businessSpecId,
            'code' => 'BA101',
            'name_ar' => 'مبادئ الإدارة',
            'name_en' => 'Principles of Management',
            'description_ar' => 'مقدمة في وظائف الإدارة الأساسية.',
            'semester' => $currentSemester,
            'year_level' => 1,
        ]);
        $course4->faculty()->attach([
            $facultyKhaledId => ['semester' => $currentSemester, 'role' => 'محاضر']
        ]);

        // == مقررات الصيدلة ==
         $course5 = Course::create([
            'specialization_id' => $pharmacySpecId,
            'code' => 'PH101',
            'name_ar' => 'مقدمة في الصيدلة',
            'name_en' => 'Introduction to Pharmacy',
            'description_ar' => 'نظرة عامة على مهنة الصيدلة ومجالاتها.',
            'semester' => $currentSemester,
            'year_level' => 1,
        ]);
        $course5->faculty()->attach([
            $facultySaraId => ['semester' => $currentSemester, 'role' => 'محاضر']
        ]);


        // أضف المزيد من المقررات والروابط والموارد حسب الحاجة
    }
}