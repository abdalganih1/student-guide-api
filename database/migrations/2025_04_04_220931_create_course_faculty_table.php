<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_faculty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->onDelete('cascade');
            $table->foreignId('faculty_id')
                  ->constrained('faculty')
                  ->onDelete('cascade'); // احذف الرابط إذا حذف الأستاذ
            $table->string('semester'); // ضروري لتحديد من درّس المادة في أي فصل
            $table->string('role')->nullable(); // مثال: 'محاضر', 'مساعد'
            $table->timestamps(); // اختياري، لتتبع متى تم الربط

            // ضمان عدم تكرار نفس الأستاذ لنفس المقرر في نفس الفصل
            $table->unique(['course_id', 'faculty_id', 'semester']);

            // إضافة فهرس للفصل الدراسي
            $table->index('semester');
            // course_id and faculty_id are already indexed by constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_faculty');
    }
};