<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

class AdyenInvoiceByLink extends Mailable
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
        $pdf = PDF::loadView('templates.pdfinvoice', array('merchantName' => $this->merchantName, 'merchantReference' => $this->merchantReference, 'adyenUrl' => $this->adyenUrl));

        return $this->subject($this->merchantName . " Invoice - " . $this->merchantReference)
            ->view('emails.emailinvoice')
            ->attachData($pdf->output(), "Invoice - " . $this->merchantReference . ".pdf");
    }
}
