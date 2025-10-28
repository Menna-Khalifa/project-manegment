<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Permission will be handled via middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'subscription_id' => ['nullable', 'exists:subscriptions,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', Rule::in(['fixed', 'percentage'])],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['unpaid', 'paid', 'overdue'])],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'paid_at' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_id' => __('invoices.user'),
            'subscription_id' => __('invoices.subscription'),
            'plan_id' => __('invoices.plan'),
            'amount' => __('invoices.amount'),
            'discount_amount' => __('invoices.discount_amount'),
            'discount_type' => __('invoices.discount_type'),
            'total_amount' => __('invoices.total_amount'),
            'status' => __('invoices.status'),
            'issue_date' => __('invoices.issue_date'),
            'due_date' => __('invoices.due_date'),
            'paid_at' => __('invoices.paid_at'),
            'payment_method' => __('invoices.payment_method'),
            'notes' => __('invoices.notes'),
        ];
    }
}
