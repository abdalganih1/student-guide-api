<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'title_en' => 'required|string|max:255',
            'student_name' => 'required|string|max:191',
            'supervisor_id' => 'required|integer|exists:faculty,id',
            'year' => 'required|integer|digits:4|min:2000', // افترضنا أن السنة 4 أرقام
            'abstract_ar' => 'nullable|string',
            'abstract_en' => 'nullable|string',
            'pdf_url' => 'nullable|string|max:255', // أو file إذا كان سيتم رفعه
            'keywords' => 'nullable|string',
        ];
    }
}