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

    public $periodInfo;
    public $groupTypeChecks;
    public $emailSubmitter;

    public function __construct($periodInfo, $groupTypeChecks, $emailSubmitter)
    {
        $this->periodInfo = $periodInfo;
        $this->groupTypeChecks = $groupTypeChecks;
        $this->emailSubmitter = $emailSubmitter;
    }

    public function build()
    {
        return $this->view('mail.submitAssignChecklist')->subject("[SUBMIT ASSIGN CHECKLIST - PIC DEALER]");
    }
}
