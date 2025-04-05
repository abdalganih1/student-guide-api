<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'specialization_id' => 'required|integer|exists:specializations,id',
            'code' => 'required|string|max:50|unique:courses,code',
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'credits' => 'required|integer|min:0',
            'prerequisites_ar' => 'nullable|string',
            'prerequisites_en' => 'nullable|string',
            // حقل ربط الأساتذة سيتم التعامل معه بشكل منفصل في المتحكم
            'faculty_ids' => 'nullable|array', // للتحقق من أن faculty_ids مصفوفة إذا أرسلت
            'faculty_ids.*' => 'integer|exists:faculty,id', // للتحقق من أن كل عنصر في المصفوفة هو id موجود
        ];
    }
}