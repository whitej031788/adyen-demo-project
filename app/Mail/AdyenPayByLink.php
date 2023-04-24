<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdyenPayByLink extends Mailable
{
    use SerializesModels;

    public $adyenUrl;
    public $merchantName;
    public $merchantReference;
    public $merchantLogoUrl;
    public $brandColorOne;
    public $brandColorTwo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $merchantName, $merchantReference, $merchantLogoUrl, $brandColorOne, $brandColorTwo)
    {
      $this->adyenUrl = $url;
      $this->merchantName = $merchantName;
      $this->merchantReference = $merchantReference;
      $this->merchantLogoUrl = $merchantLogoUrl;
      $this->brandColorOne = $brandColorOne;
      $this->brandColorTwo = $brandColorTwo;
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
