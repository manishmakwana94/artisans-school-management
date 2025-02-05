<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $recipientName;
    public $recipientEmail;
    public $senderName;
    public $subject;
    public $content;

    /**
     * Create a new message instance.
     */
    public function __construct($recipientName, $recipientEmail, $senderName, $subject, $content)
    {
        $this->recipientName = $recipientName;
        $this->recipientEmail = $recipientEmail;
        $this->senderName = $senderName;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement',
            with: [
                'recipientName' => $this->recipientName,
                'recipientEmail' => $this->recipientEmail,
                'senderName' => $this->senderName,
                'content' => $this->content
            ]
        );
    }
}
