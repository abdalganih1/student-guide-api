<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // لاستخدامه في توليد الـ URL إذا لزم الأمر

class UniversityMedia extends Model
{
    use HasFactory;

    protected $table = 'university_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'url', // قد يكون هذا هو المسار النسبي للملف في storage
        'media_type',
        'category',
    ];

    /**
     * Accessor to get the full URL for the media file.
     * (اختياري: طريقة لجلب الرابط الكامل للملف المخزن)
     *
     * @return string
     */
    // public function getFullUrlAttribute(): string
    // {
    //     // يفترض أن 'url' يخزن المسار داخل مجلد التخزين العام (public disk)
    //     // تأكد من تشغيل php artisan storage:link
    //     if ($this->url) {
    //         return Storage::disk('public')->url($this->url);
    //     }
    //     return ''; // أو رابط افتراضي لصورة/فيديو غير موجود
    // }
}