<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // علاقة ينتمي إلى
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // علاقة متعدد إلى متعدد
use Illuminate\Database\Eloquent\Relations\HasMany; // علاقة واحد إلى متعدد

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'specialization_id',
        'code',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'semester',
        'year_level',
    ];

    /**
     * Get the specialization that owns the course.
     * علاقة ينتمي إلى (المقرر ينتمي إلى اختصاص واحد)
     */
    public function specialization(): BelongsTo
    {
        // اسم المودل المرتبط، والمفتاح الأجنبي في هذا الجدول (course)
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

    /**
     * The faculty members who teach the course.
     * علاقة متعدد إلى متعدد (المقرر يدرسه العديد من الأساتذة)
     */
    public function faculty(): BelongsToMany
    {
        // اسم المودل المرتبط، اسم جدول الربط، المفتاح الأجنبي لهذا المودل في جدول الربط، المفتاح الأجنبي للمودل الآخر
        return $this->belongsToMany(Faculty::class, 'course_faculty', 'course_id', 'faculty_id')
                    ->withPivot('semester', 'role') // لجلب الأعمدة الإضافية من جدول الربط
                    ->withTimestamps(); // إذا كان جدول الربط يحتوي على timestamps
    }

    /**
     * Get the resources for the course.
     * علاقة واحد إلى متعدد (المقرر لديه العديد من الموارد)
     */
    public function resources(): HasMany
    {
        // المفتاح الأجنبي في جدول course_resources هو 'course_id'
        return $this->hasMany(CourseResource::class, 'course_id'); // لاحظ اسم المودل هنا
    }
}