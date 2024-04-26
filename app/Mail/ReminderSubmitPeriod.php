<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderSubmitPeriod extends Mailable
{
    use Queueable, SerializesModels;

    public $periodinfo;

    public function __construct($periodinfo)
    {
        $this->periodinfo = $periodinfo;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[REMINDER SUBMIT PERIOD - SYSTEM AUDIT NOS]";

        $email = $this->view('mail.reminderSubmitPeriod')->subject($subject);

        return $email;
    }
}
