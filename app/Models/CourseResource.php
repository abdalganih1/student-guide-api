<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // علاقة ينتمي إلى

class CourseResource extends Model
{
    use HasFactory;

    // تحديد اسم الجدول صراحةً إذا كان مختلفًا عن الجمع التلقائي ('courseresources')
    protected $table = 'course_resources';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title_ar',
        'title_en',
        'url',
        'type',
        'description',
        'semester',
    ];

    /**
     * Get the course that owns the resource.
     * علاقة ينتمي إلى (المورد ينتمي إلى مقرر واحد)
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}