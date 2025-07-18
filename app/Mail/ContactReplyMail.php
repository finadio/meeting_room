<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $userEmail;
    public $replyMessage;
    public $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userEmail, $replyMessage, $adminName)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->replyMessage = $replyMessage;
        $this->adminName = $adminName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact Reply Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact.reply',
        );
    }

    public function build()
    {
        return $this->subject('Balasan dari Admin - Kontak Meeting Room')
            ->markdown('emails.contact.reply')
            ->with([
                'userName' => $this->userName,
                'replyMessage' => $this->replyMessage,
                'adminName' => $this->adminName,
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
