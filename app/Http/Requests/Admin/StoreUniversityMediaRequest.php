<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUniversityMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'title_ar' => 'required|string|max:191',
            'title_en' => 'nullable|string|max:191',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'media_type' => 'required|string|in:image,video', // يجب أن يكون إما صورة أو فيديو
            'category' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200', // مثال: 50MB max, حدد الأنواع المسموحة والحجم الأقصى
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'file.required' => 'الرجاء اختيار ملف للرفع.',
            'file.mimes' => 'نوع الملف غير مدعوم. الأنواع المسموحة: jpg, png, gif, mp4, mov, avi.',
            'file.max' => 'حجم الملف كبير جدًا. الحجم الأقصى المسموح هو 50 ميجابايت.',
            'media_type.in' => 'نوع الوسيط يجب أن يكون صورة أو فيديو.',
        ];
    }
}