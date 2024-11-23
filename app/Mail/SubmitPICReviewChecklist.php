<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitPICReviewChecklist extends Mailable
{
    use Queueable, SerializesModels;

    public $nextStatus;
    public $periodinfo;
    public $checklistdetail;
    public $emailsubmitter;
    public $note;

    public function __construct($nextStatus, $periodinfo, $checklistdetail, $emailsubmitter, $note)
    {
        $this->nextStatus = $nextStatus;
        $this->periodinfo = $periodinfo;
        $this->checklistdetail = $checklistdetail;
        $this->emailsubmitter = $emailsubmitter;
        $this->note = $note;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[DECISION REVIEW CHECKLIST - PIC NOS MD]";

        $email = $this->view('mail.submitPICReviewChecklist')->subject($subject);

        return $email;
    }
}
