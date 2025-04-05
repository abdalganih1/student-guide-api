<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // للتحقق من صلاحية المستخدم

class StoreSpecializationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // تأكد من أن المستخدم المسجل هو مدير
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // بناءً على مخطط قاعدة البيانات
        return [
            'name_ar' => 'required|string|max:255|unique:specializations,name_ar', // تأكد من عدم تكرار الاسم العربي
            'name_en' => 'nullable|string|max:255|unique:specializations,name_en', // تأكد من عدم تكرار الاسم الإنجليزي
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name_ar.required' => 'اسم الاختصاص بالعربية مطلوب.',
            'name_ar.unique' => 'اسم الاختصاص بالعربية موجود مسبقاً.',
            'name_en.unique' => 'اسم الاختصاص بالإنجليزية موجود مسبقاً.',
            'description_ar.required' => 'وصف الاختصاص بالعربية مطلوب.',
        ];
    }
}