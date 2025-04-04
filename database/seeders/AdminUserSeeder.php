<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // مهم لتشفير كلمة المرور
use App\Models\AdminUser; // تأكد من استيراد المودل الصحيح

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminUser::create([
            'username' => 'superadmin',
            'email' => 'admin@yourapp.com', // استخدم بريدًا حقيقيًا أو مميزًا
            'password' => Hash::make('SecurePassword123!'), // *** استخدم كلمة مرور قوية جداً! ***
            'role' => 'superadmin' // أو 'admin' حسب ما حددت
        ]);

        // يمكنك إضافة المزيد من المستخدمين إذا أردت
         AdminUser::create([
             'username' => 'editor',
             'email' => 'editor@yourapp.com',
             'password' => Hash::make('AnotherPassword'),
             'role' => 'editor' // إذا كان لديك أدوار مختلفة
         ]);
    }
}