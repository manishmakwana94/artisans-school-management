<?php

namespace App\Helpers;

class SweetAlertHelper
{
    /**
     * Show a SweetAlert2 Toast notification.
     *
     * @param string $type
     * @param string $message
     * @param string $title
     * @return void
     */
    public static function showToast($type, $message, $title = '')
    {
        session()->flash('swal-toast', [
            'type' => $type,
            'message' => $message,
            'title' => $title,
        ]);
    }
}
