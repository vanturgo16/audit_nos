<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitAssignChecklist extends Mailable
{
    use Queueable, SerializesModels;

    public $periodinfo;
    public $emailsubmitter;

    public function __construct($periodinfo, $emailsubmitter)
    {
        $this->periodinfo = $periodinfo;
        $this->emailsubmitter = $emailsubmitter;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[SUBMIT ASSIGN CHECKLIST - ASSESSOR]";

        $email = $this->view('mail.submitAssignChecklist')->subject($subject);

        return $email;
    }
}
