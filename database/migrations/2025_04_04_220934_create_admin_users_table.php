<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash; // لاستخدام Hash لعملية Seeding لاحقاً

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password'); // سيتم تخزين الـ Hash هنا
            $table->string('role')->default('admin'); // يمكن إضافة أدوار أخرى لاحقاً
            $table->rememberToken(); // لخاصية "تذكرني" في لوحة التحكم
            $table->timestamps();
        });

        // يمكنك إضافة مدير افتراضي هنا مباشرة أو يفضل عبر Seeder
        // \App\Models\AdminUser::create([
        //     'username' => 'superadmin',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('password'), // استبدل 'password' بكلمة مرور قوية
        //     'role' => 'superadmin'
        // ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};