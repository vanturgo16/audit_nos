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
    public $periodInfo;
    public $checklistdetail;
    public $emailSubmitter;
    public $note;

    public function __construct($nextStatus, $periodInfo, $checklistdetail, $emailSubmitter, $note)
    {
        $this->nextStatus = $nextStatus;
        $this->periodInfo = $periodInfo;
        $this->checklistdetail = $checklistdetail;
        $this->emailSubmitter = $emailSubmitter;
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
