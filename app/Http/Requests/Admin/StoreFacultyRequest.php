<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'title' => 'required|string|max:100',
            'email' => 'required|string|email|max:191|unique:faculty,email',
            'office_location' => 'nullable|string|max:191',
        ];
    }
}