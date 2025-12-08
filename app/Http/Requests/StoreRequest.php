<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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

        $storeId = optional($this->route('store'))->id;
        
        return [
            'brand_id' => 'required|exists:brands,id',
            'uuid' => 'required|string|unique:stores,uuid,' . $storeId,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
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
            'brand_id.required' => 'يجب اختيار العلامة',
            'brand_id.exists' => 'العلامة المختارة غير موجودة',
            'uuid.required' => 'يجب ادخال معرف المتجر',
            'uuid.unique' => 'معرف المتجر هذا مستخدم بالفعل',
            'name.required' => 'اسم المتجر مطلوب',
            'name.max' => 'اسم المتجر يجب أن لا يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صالحًا',
            'email.max' => 'البريد الإلكتروني يجب أن لا يتجاوز 255 حرف',
            'phone.max' => 'رقم الهاتف يجب أن لا يتجاوز 20 حرف',
            'country.max' => 'الدولة يجب أن لا تتجاوز 255 حرف',
            'city.max' => 'المدينة يجب أن لا تتجاوز 255 حرف',
            'state.max' => 'الولاية يجب أن لا تتجاوز 255 حرف',
            'address.max' => 'العنوان يجب أن لا يتجاوز 255 حرف',
            'zip.max' => 'الرمز البريدي يجب أن لا يتجاوز 20 حرف',
        ];
    }
}
