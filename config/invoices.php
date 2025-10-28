<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Invoice Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for invoices in the application.
    |
    */

    // Default payment methods available
    'payment_methods' => [
        'manual' => 'Manual Payment',
        'bank_transfer' => 'Bank Transfer',
        'credit_card' => 'Credit Card',
        'paypal' => 'PayPal',
        'stripe' => 'Stripe',
        'other' => 'Other',
    ],

    // Number of days until an invoice is considered overdue
    'days_until_overdue' => 30,

    // Default time to wait before sending payment reminders (in days)
    'reminder_schedule' => [
        'first' => 3,  // 3 days before due date
        'second' => 1, // 1 day before due date
        'overdue' => [3, 7, 14], // days after due date
    ],

    // PDF settings
    'pdf' => [
        'paper_size' => 'a4',
        'orientation' => 'portrait',
        'show_vat' => true,
        'show_payment_instructions' => true,
    ],

    // Company information for invoices
    'company' => [
        'name' => env('COMPANY_NAME', 'AlBaytri'),
    ],
];
