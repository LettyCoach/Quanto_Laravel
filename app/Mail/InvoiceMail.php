<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $sendName;
    public $data;

    public function __construct($clientName, $sendName, $data)
    {
        $this->clientName = $clientName;
        $this->sendName = $sendName;
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.invoiceMail')
                    ->subject("【Quanto】請求書送信のお知らせ");
    }
}
