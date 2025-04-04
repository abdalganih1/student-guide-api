<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // استيراد العلاقة

class Specialization extends Model
{
    use HasFactory; // لتفعيل استخدام Factories (اختياري لكن مفيد)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
    ];

    /**
     * Get the courses for the specialization.
     * علاقة واحد إلى متعدد (الاختصاص لديه العديد من المقررات)
     */
    public function courses(): HasMany
    {
        // اسم المودل المرتبط، والمفتاح الأجنبي في جدول courses (افتراضيًا specialization_id)
        return $this->hasMany(Course::class);
    }

    /**
     * Get the graduation projects for the specialization.
     * علاقة واحد إلى متعدد (الاختصاص لديه العديد من مشاريع التخرج)
     */
    public function graduationProjects(): HasMany
    {
        return $this->hasMany(GraduationProject::class);
    }
}