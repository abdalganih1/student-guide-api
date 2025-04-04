<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعِ الـ Seeders بالترتيب المنطقي (إذا كان هناك اعتماديات)
        $this->call([
            AdminUserSeeder::class, // مهم لإنشاء المدير أولاً
            SpecializationSeeder::class,
            FacultySeeder::class,
            CourseSeeder::class, // يمكنك تفعيله لاحقاً
            // ... أي Seeders أخرى
        ]);

        // مثال لاستخدام Factory إذا كنت قد أنشأتها
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}