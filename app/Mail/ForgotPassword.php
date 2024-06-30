<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $otp;
    public $get_user_mail;
    public $get_name_user;

    public function __construct($otp, $get_user_mail, $get_name_user)
    {
        $this->otp = $otp;
        $this->get_user_mail = $get_user_mail;
        $this->get_name_user = $get_name_user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Forgot Password Verification Code : ' . $this->otp,
        );
    }

    public function build()
    {
        // return new Content(
        //     view: 'emails.otp',
        // );
        return $this->subject('Your Otp Code')
            ->view('emails.otp')
            ->with([
                'otp' => $this->otp,
                // 'get_user_mail' => $this->get_user_mail,
                'get_name_user' => $this->get_name_user
            ]);
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            // view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
