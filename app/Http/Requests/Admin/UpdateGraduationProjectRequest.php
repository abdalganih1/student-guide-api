<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateGraduationProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'specialization_id' => 'required|integer|exists:specializations,id',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'student_name' => 'required|string|max:191',
            'supervisor_id' => 'required|integer|exists:faculty,id',
            'year' => 'required|integer|digits:4|min:2000',
            // --- أضف السطر التالي ---
            'semester' => ['required', 'string', 'max:50', Rule::in(['خريف', 'ربيع', 'صيف'])],
            // ------------------------
            'abstract_ar' => 'nullable|string',
            'abstract_en' => 'nullable|string',
            'pdf_url' => 'nullable|string|max:255',
            'keywords' => 'nullable|string',
        ];
    }

     public function messages(): array
    {
        return [
            'semester.required' => 'حقل الفصل الدراسي مطلوب.',
            'semester.in' => 'قيمة الفصل الدراسي غير صالحة. القيم المسموحة: خريف, ربيع, صيف.',
        ];
    }
}