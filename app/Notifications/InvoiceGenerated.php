<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceGenerated extends Notification
{
    use Queueable;

    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Invoice Generated',
            'body' => 'تم إنشاء فاتورة جديدة للمستخدم ' . $this->invoice->user->name . ' بمبلغ ' . $this->invoice->amount . 'رقم الفاتورة ' . $this->invoice->invoice_number . ' الخاصه بالإشتراك #' . $this->invoice->subscription->sub_number . ' في خطة ' . $this->invoice->subscription->plan->name,
            'invoice_number' => $this->invoice->invoice_number,
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->amount,
            'due_date' => $this->invoice->due_date,
        ];
    }
}
