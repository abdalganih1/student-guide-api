<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faculty; // استيراد مودل هيئة التدريس

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Faculty::truncate(); // اختياري

        Faculty::create([
            'name_ar' => 'د. أحمد محمد',
            'name_en' => 'Dr. Ahmed Mohamed',
            'title' => 'أستاذ مساعد',
            'email' => 'ahmed.m@university.edu',
            'office_location' => 'مبنى الهندسة - مكتب 201',
        ]);

        Faculty::create([
            'name_ar' => 'د. فاطمة علي',
            'name_en' => 'Dr. Fatima Ali',
            'title' => 'أستاذ مشارك',
            'email' => 'fatima.a@university.edu',
            'office_location' => 'مبنى الهندسة - مكتب 205',
        ]);

        Faculty::create([
            'name_ar' => 'م. خالد يوسف', // مهندس أو مدرس
            'name_en' => 'Eng. Khaled Yousef',
            'title' => 'مدرس',
            'email' => 'khaled.y@university.edu',
            'office_location' => 'مبنى الإدارة - مكتب 110',
        ]);

        Faculty::create([
            'name_ar' => 'د. سارة إبراهيم',
            'name_en' => 'Dr. Sara Ibrahim',
            'title' => 'أستاذ',
            'email' => 'sara.i@university.edu',
            'office_location' => 'مبنى الصيدلة - مكتب 303',
        ]);

        Faculty::create([
            'name_ar' => 'د. عمر حسن',
            'name_en' => 'Dr. Omar Hassan',
            'title' => 'أستاذ مساعد',
            'email' => 'omar.h@university.edu',
            'office_location' => 'مبنى الاتصالات - مكتب 102',
        ]);

        // أضف المزيد من أعضاء هيئة التدريس
    }
}