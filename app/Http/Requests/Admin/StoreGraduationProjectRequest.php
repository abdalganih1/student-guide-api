<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // قد تحتاجه لقيم محددة

class StoreGraduationProjectRequest extends FormRequest
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
            'title_en' => 'required|string|max:255', // جعلته مطلوباً بناءً على الكنترولر، أو اجعله nullable هنا وفي المايجريشن إذا كان اختياري
            'student_name' => 'required|string|max:191', // جعلته مطلوباً، أو nullable إذا كان اختياري كما في المايجريشن
            'supervisor_id' => 'required|integer|exists:faculty,id',
            'year' => 'required|integer|digits:4|min:2000', // افترضنا أن السنة 4 أرقام
            // --- أضف السطر التالي ---
            'semester' => ['required', 'string', 'max:50', Rule::in(['خريف', 'ربيع', 'صيف'])], // اجعل القيم المسموحة مناسبة لك
            // ------------------------
            'abstract_ar' => 'nullable|string',
            'abstract_en' => 'nullable|string',
            'pdf_url' => 'nullable|string|max:255', // أو file إذا كان سيتم رفعه
            'keywords' => 'nullable|string',
        ];
    }

     /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'semester.required' => 'حقل الفصل الدراسي مطلوب.',
            'semester.in' => 'قيمة الفصل الدراسي غير صالحة. القيم المسموحة: خريف, ربيع, صيف.',
        ];
    }
}