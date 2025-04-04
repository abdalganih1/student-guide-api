<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * يقوم بإنشاء جدول 'faculty'.
     */
    public function up(): void
    {
        // اسم الجدول 'faculty' كما هو محدد في المودل $table = 'faculty';
        Schema::create('faculty', function (Blueprint $table) {
            $table->id(); // يُنشئ عمود 'id' من نوع BigInt Unsigned Auto-Increment (مفتاح أساسي)

            // الأعمدة بناءً على الـ $fillable في المودل والبيانات في الـ Seeder
            $table->string('name_ar'); // اسم عضو هيئة التدريس بالعربية
            $table->string('name_en'); // اسم عضو هيئة التدريس بالإنجليزية
            $table->string('title');   // اللقب العلمي (أستاذ مساعد، أستاذ مشارك، إلخ)
            $table->string('email')->unique(); // البريد الإلكتروني، يجب أن يكون فريداً
            $table->string('office_location')->nullable(); // موقع المكتب، قد يكون غير متوفر أحياناً لذا نجعله nullable

            // يُنشئ عمودي 'created_at' و 'updated_at' من نوع Timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * يقوم بحذف جدول 'faculty' إذا كان موجوداً.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};