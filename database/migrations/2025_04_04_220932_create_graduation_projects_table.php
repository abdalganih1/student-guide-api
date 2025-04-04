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
        Schema::create('graduation_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialization_id')
                  ->constrained('specializations')
                  ->onDelete('cascade');
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->integer('year'); // سنة التخرج - فهرسة
            $table->string('semester'); // الفصل (خريف/ربيع) - فهرسة
            $table->string('student_name')->nullable(); // اسم الطالب (اختياري)

            // Foreign key for supervisor (nullable, set null on delete)
            $table->foreignId('supervisor_id')
                  ->nullable() // يمكن أن يكون المشروع بدون مشرف مسجل أو المشرف حذف
                  ->constrained('faculty') // يشير إلى جدول faculty
                  ->onDelete('set null'); // إذا حذف الأستاذ، يبقى المشروع ولكن supervisor_id يصبح NULL

            $table->text('abstract_ar')->nullable(); // الملخص
            $table->text('abstract_en')->nullable();
            $table->timestamps();

            $table->index('year');
            $table->index('semester');
            // specialization_id and supervisor_id are already indexed by constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_projects');
    }
};