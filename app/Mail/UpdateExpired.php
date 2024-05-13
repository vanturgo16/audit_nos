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

    public $periodinfo;
    public $checklistdetail;
    public $emailsubmitter;

    public function __construct($periodinfo, $checklistdetail, $emailsubmitter)
    {
        $this->periodinfo = $periodinfo;
        $this->checklistdetail = $checklistdetail;
        $this->emailsubmitter = $emailsubmitter;
    }

    public function build()
    {
        //SUBJECT NAME
        $subject = "[UPDATE DATE PERIOD CHECKLIST - ASSESSOR]";

        $email = $this->view('mail.updateExpired')->subject($subject);

        return $email;
    }
}
