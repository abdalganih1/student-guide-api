<?php

namespace Database\Seeders;

// قم بإزالة السطر التالي إذا كان موجودًا، فهو ليس ضروريًا عادةً
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialization; // استيراد مودل الاختصاص

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // يمكنك مسح البيانات القديمة أولاً إذا أردت (اختياري)
        // Specialization::truncate(); // كن حذرًا عند استخدام truncate

        Specialization::create([
            'name_ar' => 'هندسة المعلوماتية',
            'name_en' => 'Informatics Engineering',
            'description_ar' => 'يهدف الاختصاص إلى تخريج مهندسين قادرين على تحليل وتصميم وتطوير الأنظمة البرمجية والشبكات وقواعد البيانات.',
            'description_en' => 'Aims to graduate engineers capable of analyzing, designing, and developing software systems, networks, and databases.',
        ]);

        Specialization::create([
            'name_ar' => 'هندسة الاتصالات',
            'name_en' => 'Telecommunications Engineering',
            'description_ar' => 'يركز على دراسة تصميم وتطوير وتشغيل أنظمة وشبكات الاتصالات السلكية واللاسلكية.',
            'description_en' => 'Focuses on the study, design, development, and operation of wired and wireless communication systems and networks.',
        ]);

        Specialization::create([
            'name_ar' => 'إدارة الأعمال',
            'name_en' => 'Business Administration',
            'description_ar' => 'يزود الطلاب بالمعرفة والمهارات اللازمة لإدارة المؤسسات واتخاذ القرارات الاستراتيجية في بيئة الأعمال.',
            'description_en' => 'Equips students with the knowledge and skills needed to manage organizations and make strategic decisions in the business environment.',
        ]);

        Specialization::create([
            'name_ar' => 'الصيدلة',
            'name_en' => 'Pharmacy',
            'description_ar' => 'يهتم بتحضير الأدوية وتركيبها وصرفها وتقديم الاستشارات الدوائية للمرضى.',
            'description_en' => 'Concerned with the preparation, compounding, dispensing of medicines, and providing pharmaceutical consultations to patients.',
        ]);

         // أضف المزيد من الاختصاصات حسب الحاجة
    }
}