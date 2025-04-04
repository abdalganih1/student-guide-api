<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // علاقة ينتمي إلى

class GraduationProject extends Model
{
    use HasFactory;

    protected $table = 'graduation_projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'specialization_id',
        'title_ar',
        'title_en',
        'year',
        'semester',
        'student_name',
        'supervisor_id', // المفتاح الأجنبي للمشرف
        'abstract_ar',
        'abstract_en',
    ];

    /**
     * Get the specialization that owns the project.
     * علاقة ينتمي إلى (المشروع ينتمي إلى اختصاص واحد)
     */
    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

    /**
     * Get the faculty member who supervised the project.
     * علاقة ينتمي إلى (المشروع لديه مشرف واحد)
     */
    public function supervisor(): BelongsTo
    {
        // المفتاح الأجنبي هو 'supervisor_id' ويرتبط بمودل Faculty
        return $this->belongsTo(Faculty::class, 'supervisor_id');
    }
}