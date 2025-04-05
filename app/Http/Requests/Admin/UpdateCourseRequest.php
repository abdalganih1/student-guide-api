<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        $courseId = $this->route('course')->id;

        return [
            'specialization_id' => 'required|integer|exists:specializations,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'code')->ignore($courseId),
            ],
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'credits' => 'required|integer|min:0',
            'prerequisites_ar' => 'nullable|string',
            'prerequisites_en' => 'nullable|string',
            'faculty_ids' => 'nullable|array',
            'faculty_ids.*' => 'integer|exists:faculty,id',
        ];
    }
}