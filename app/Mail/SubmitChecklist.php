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

    public $periodInfo;
    public $checklistdetail;
    public $emailSubmitter;

    public function __construct($periodInfo, $checklistdetail, $emailSubmitter)
    {
        $this->periodInfo = $periodInfo;
        $this->checklistdetail = $checklistdetail;
        $this->emailSubmitter = $emailSubmitter;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[SUBMIT RESPONSE CHECKLIST - INTERNAL AUDITOR]";

        $email = $this->view('mail.submitResponseChecklist')->subject($subject);

        return $email;
    }
}
