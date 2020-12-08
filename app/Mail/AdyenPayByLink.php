<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdyenPayByLink extends Mailable
{
    use Queueable, SerializesModels;

    public $adyenUrl;
    public $merchantName;
    public $merchantReference;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $merchantName, $merchantReference)
    {
      $this->adyenUrl = $url;
      $this->merchantName = $merchantName;
      $this->merchantReference = $merchantReference;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->merchantName . " Payment Request - " . $this->merchantReference)->view('emails.paybylink');
    }
}
