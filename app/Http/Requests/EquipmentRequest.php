<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'count' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم القسم مطلوب',
            'name.max' => 'اسم القسم يجب أن لا يتجاوز 255 حرف',
            'description.nullable' => 'الوصف غير مطلوب',
            'description.max' => 'الوصف يجب أن لا يتجاوز 255 حرف',
            'description.string' => 'الوصف يجب أن يكون نصًا',
            'count.required' => 'عدد القسم مطلوب',
            'count.integer' => 'عدد القسم يجب أن يكون رقمًا',
            'count.min' => 'عدد القسم يجب أن يكون أكبر من أو يساوي 0',
        ];
    }
}
