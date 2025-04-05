<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // لاستخدام Rule::unique

class UpdateSpecializationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // الحصول على ID الاختصاص الحالي من المسار
        $specializationId = $this->route('specialization')->id; // أو $this->specialization->id

        return [
            'name_ar' => [
                'required',
                'string',
                'max:255',
                // تجاهل السجل الحالي عند التحقق من التفرد
                Rule::unique('specializations', 'name_ar')->ignore($specializationId),
            ],
            'name_en' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('specializations', 'name_en')->ignore($specializationId),
            ],
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
        ];
    }

     // يمكنك إضافة رسائل مخصصة هنا أيضاً كما في StoreRequest
     public function messages(): array
     {
        // ...
        return [];
     }
}