<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitReviewChecklist extends Mailable
{
    use Queueable, SerializesModels;

    public $periodinfo;
    public $checklistdetail;
    public $emailsubmitter;
    public $note;

    public function __construct($periodinfo, $checklistdetail, $emailsubmitter, $note)
    {
        $this->periodinfo = $periodinfo;
        $this->checklistdetail = $checklistdetail;
        $this->emailsubmitter = $emailsubmitter;
        $this->note = $note;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[DECISION REVIEW CHECKLIST - ASSESSOR]";

        $email = $this->view('mail.submitReviewChecklist')->subject($subject);

        return $email;
    }
}
