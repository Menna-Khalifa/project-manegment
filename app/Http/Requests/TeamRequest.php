<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $teamId = $this->route('team');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $teamId],
            'phone' => ['required', 'numeric', 'digits:10', 'unique:users,phone,' . $teamId],
            'password' => ['nullable', 'string', 'min:6'], // جعل كلمة المرور اختيارية
            'confirm-password' => ['nullable', 'string', 'min:6', 'same:password'], // جعل التأكيد اختياريًا
            'group_id' => ['required', 'exists:groups,id'],
            'type' => ['required', 'in:team'],
            'status' => 'nullable',
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required'),
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'email.unique' => __('validation.unique'),
            'phone.required' => __('validation.required'),
            'phone.numeric' => __('validation.numeric'),
            'phone.digits' => __('validation.digits'),
            'password.required' => __('validation.required'),
            'password.min' => __('validation.min.numeric'),
            'confirm-password.required' => __('validation.required'),
            'confirm-password.min' => __('validation.min.numeric'),
            'group_id.required' => __('validation.required'),
            'group_id.exists' => __('validation.exists'),
            'status.nullable' => __('validation.nullable'),
            'type.required' => __('validation.required'),
            'type.in' => __('validation.in'),
            'photo.image' => __('validation.image'),
            'photo.mimes' => __('validation.mimes'),
        ];
    }
}
