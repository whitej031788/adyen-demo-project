<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationEmailQrCode extends Mailable
{
    use Queueable, SerializesModels;

    public $merchantName;
    public $merchantLogoUrl;
    public $firstName;
    public $lastName;
    public $email;
    public $nfcUid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($merchantName, $merchantLogoUrl, $firstName, $lastName, $email, $nfcUid)
    {
      $this->merchantName = $merchantName;
      $this->merchantLogoUrl = $merchantLogoUrl;
      $this->firstName = $firstName;
      $this->lastName = $lastName;
      $this->email = $email;
      $this->nfcUid = $nfcUid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->merchantName . " Registration Confirmation")->view('emails.registrationconfirmation');
    }
}
