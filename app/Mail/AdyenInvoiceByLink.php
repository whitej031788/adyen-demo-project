<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class AdyenInvoiceByLink extends Mailable
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
        $pdf = PDF::loadView('templates.pdfinvoice', array(
          'merchantName' => $this->merchantName, 
          'merchantReference' => $this->merchantReference, 
          'adyenUrl' => $this->adyenUrl,
          'merchantLogoUrl' => $this->merchantLogoUrl, 
          'brandColorOne' => $this->brandColorOne, 
          'brandColorTwo' => $this->brandColorTwo,
        ));

        return $this->subject($this->merchantName . " Invoice - " . $this->merchantReference)
            ->view('emails.emailinvoice')
            ->attachData($pdf->output(), "Invoice - " . $this->merchantReference . ".pdf");
    }
}
