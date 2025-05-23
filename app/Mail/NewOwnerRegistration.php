<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOwnerRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $newOwner;

    public function __construct(User $newOwner)
    {
        $this->newOwner = $newOwner;
    }

    public function build()
    {
        return $this->markdown('emails.new-owner-registration')
                    ->subject('New Owner Registration');
    }
}