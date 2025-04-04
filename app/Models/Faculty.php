<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // علاقة متعدد إلى متعدد
use Illuminate\Database\Eloquent\Relations\HasMany; // علاقة واحد إلى متعدد

class Faculty extends Model
{
    use HasFactory;

    // اسم الجدول، إذا كان مختلفًا عن الجمع التلقائي لاسم المودل (faculty -> faculties)
    // في هذه الحالة، اسم الجدول هو 'faculty' وليس 'faculties'
    protected $table = 'faculty'; // حدد اسم الجدول صراحةً

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'title',
        'email',
        'office_location',
    ];

    /**
     * The courses that the faculty member teaches.
     * علاقة متعدد إلى متعدد (الأستاذ يدرس العديد من المقررات، والمقرر يدرسه العديد من الأساتذة)
     */
    public function courses(): BelongsToMany
    {
        // اسم المودل المرتبط، اسم جدول الربط، المفتاح الأجنبي لهذا المودل في جدول الربط، المفتاح الأجنبي للمودل الآخر
        return $this->belongsToMany(Course::class, 'course_faculty', 'faculty_id', 'course_id')
                    ->withPivot('semester', 'role') // لجلب الأعمدة الإضافية من جدول الربط
                    ->withTimestamps(); // إذا كان جدول الربط يحتوي على timestamps
    }

    /**
     * Get the graduation projects supervised by the faculty member.
     * علاقة واحد إلى متعدد (الأستاذ يشرف على العديد من المشاريع)
     */
    public function supervisedProjects(): HasMany
    {
        // المفتاح الأجنبي في جدول graduation_projects هو 'supervisor_id'
        return $this->hasMany(GraduationProject::class, 'supervisor_id');
    }
}