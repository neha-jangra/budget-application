<?php

namespace App\Http\Trait;

trait ToastTrait
{
    public function toastMessage($title, $text, $redirectUrl = null, $status)
    {
        $this->emit('swal:alert', [
            'title' => $title,
            'text' => $text,
            'icon' => $status,
            'redirectUrl' => $redirectUrl,
            'status' => $status
        ]);
    }
}
