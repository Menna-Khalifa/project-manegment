<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $admin = auth()->user();
        // التحقق من صلاحيات المستخدم
        if (!$admin->hasRole('admin')) {
            $notifications = DatabaseNotification::where('notifiable_id', $admin->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $notifications = DatabaseNotification::orderBy('created_at', 'desc')
                ->get();
        }
        return view('dashboard.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $admin = auth()->user();
        // التحقق من صلاحيات المستخدم
        if (!$admin->hasRole('admin')) {
            $notifications = DatabaseNotification::where('notifiable_id', $admin->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $notifications = DatabaseNotification::orderBy('created_at', 'desc')
                ->get();
        }
        $notification = $notifications->where('id', $id)->first();

        if (!$notification) {
            return redirect()->route('notifications.index')->with('error', __('notifications.not_found'));
        }
        // تحديث حالة الإشعار إلى مقروء
        $notification->update(['read_at' => now()]);

        if ($notification->notifiable_type == 'App\Models\User') {
            return redirect()->route('users.show', $notification->notifiable_id);
        } elseif ($notification->notifiable_type == 'App\Models\SupportTicket') {
            return redirect()->route('support_tickets.show', $notification->data['ticket_id']);
        } elseif ($notification->notifiable_type == 'App\Models\Subscription') {
            return redirect()->route('subscriptions.show', $notification->data['subscription_id']);
        } elseif ($notification->notifiable_type == 'App\Models\Invoice') {
            return redirect()->route('invoices.show', $notification->data['invoice_id']);
        } elseif ($notification->notifiable_type == 'App\Models\Plan') {
            return redirect()->route('plans.show', $notification->data['plan_id']);
        } elseif ($notification->notifiable_type == 'App\Models\CalendarEvent') {
            return redirect()->route('calendar.show', $notification->data['event_id']);
        }

        return redirect()->route('notifications.index');
    }

    public function markAllAsRead()
    {
        $admin = auth()->user();
        // التحقق من صلاحيات المستخدم
        if (!$admin->hasRole('admin')) {
            $notifications = DatabaseNotification::where('notifiable_id', $admin->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $notifications = DatabaseNotification::orderBy('created_at', 'desc')
                ->get();
        }

        foreach ($notifications as $notification) {
            $notification->update(['read_at' => now()]);
        }
        notify(__('notifications.marked_all_as_read'), 'success');
        return back()->with('success', __('notifications.marked_all_as_read'));
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        // التحقق من صلاحيات المستخدم
        if (!$admin->hasRole('admin')) {
            $notifications = DatabaseNotification::where('notifiable_id', $admin->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $notifications = DatabaseNotification::orderBy('created_at', 'desc')
                ->get();
        }

        $notification = $notifications->where('id', $id)->first();
        $notification->delete();

        notify(__('notifications.deleted'), 'success');
        return back()->with('success', __('notifications.deleted'));
    }
}
