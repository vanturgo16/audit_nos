<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UpdateExpired extends Mailable
{
    use Queueable, SerializesModels;

    public $periodInfo;
    public $checklistDetail;
    public $emailSubmitter;

    public function __construct($periodInfo, $checklistDetail, $emailSubmitter)
    {
        $this->periodInfo = $periodInfo;
        $this->checklistDetail = $checklistDetail;
        $this->emailSubmitter = $emailSubmitter;
    }

    public function build()
    {
        return $this->view('mail.updateExpired')->subject("[UPDATE DATE PERIOD CHECKLIST - PIC DEALER]");
    }
}
