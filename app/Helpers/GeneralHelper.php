<?php

use Illuminate\Support\Facades\Session;

if (!function_exists('notify')) {
    /**
     * Store a notification message in the session.
     *
     * @param string $msg The notification message.
     * @param string $type The notification type (e.g., 'success', 'error').
     */
    function notify($msg, $type = 'success')
    {
        Session::flash('notification', [
            'msg' => $msg,
            'type' => $type,
        ]);
    }
}