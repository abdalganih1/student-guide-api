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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            // Foreign Key for specialization (onDelete('cascade') means delete course if specialization is deleted)
            $table->foreignId('specialization_id')
                  ->constrained('specializations') // assumes table name is 'specializations'
                  ->onDelete('cascade');
            $table->string('code')->unique(); // رمز المقرر
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('semester'); // الفصل الدراسي (مثال: "خريف 2024") - فهرسة للبحث السريع
            $table->integer('year_level')->nullable(); // مستوى السنة (1, 2, 3...)
            $table->timestamps();

            // إضافة فهارس لتحسين أداء البحث والفلترة
            $table->index('semester');
            // specialization_id is already indexed by constrained()
            // code is already indexed by unique()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};