<?php

namespace App\Mail;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnrollmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enrollment $enrollment) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->enrollment->status) {
            'enrolled'    => 'Congratulations! Your Enrollment is Approved',
            'disapproved' => 'Enrollment Update — Application Disapproved',
            default       => 'Enrollment Status Update',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.enrollment-status');
    }

    public function attachments(): array
    {
        return [];
    }
}