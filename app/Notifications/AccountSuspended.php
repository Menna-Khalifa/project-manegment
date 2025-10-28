<?php

namespace App\Notifications;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Validation\Rules\In;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountSuspended extends Notification
{
    use Queueable;

    protected $subscription;
    protected $invoice;

    public function __construct(Subscription $subscription, Invoice $invoice)
    {
        $this->subscription = $subscription;
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Account Suspended',
            'body' => 'حساب المستخدم ' . $this->subscription->user->name . ' تم إيقافه بسبب عدم دفع الفاتورة ' . $this->invoice->invoice_number . ' الخاصة بالإشتراك #' . $this->subscription->sub_number . ' في خطة ' . $this->subscription->plan->name,
            'subscription_id' => $this->subscription->id,
            'suspended_at' => Carbon::now()->toDateTimeString(),
        ];
    }
}
