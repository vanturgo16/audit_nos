<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitChecklist extends Mailable
{
    use Queueable, SerializesModels;

    public $test;
    public $emailsubmitter;

    public function __construct($test, $emailsubmitter)
    {
        $this->test = $test;
        $this->emailsubmitter = $emailsubmitter;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[INTERNAL AUDITOR - SUBMIT CHECKLIST AUDIT]";

        $email = $this->view('mail.submitChecklist')->subject($subject);

        return $email;
    }
}
