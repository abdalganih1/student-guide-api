<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        $facultyId = $this->route('faculty')->id;

        return [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'title' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
                Rule::unique('faculty', 'email')->ignore($facultyId),
            ],
            'office_location' => 'nullable|string|max:191',
        ];
    }
}