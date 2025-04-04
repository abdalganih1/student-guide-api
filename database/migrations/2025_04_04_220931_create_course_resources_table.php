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
        Schema::create('course_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->onDelete('cascade');
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('url'); // رابط المحاضرة، الدورة، الملف..
            $table->enum('type', ['lecture', 'training_course', 'document', 'link']); // نوع المورد
            $table->text('description')->nullable();
            $table->string('semester'); // الفصل الدراسي المرتبط به المورد
            $table->timestamps();

            $table->index('type');
            $table->index('semester');
             // course_id is already indexed by constrained()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_resources');
    }
};