<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewSchedulingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $scheduling;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Models\Scheduling $scheduling)
    {
        $this->scheduling = $scheduling;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo Agendamento Realizado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_scheduling',
            with: [
                'clientName' => $this->scheduling->client->user->name,
                'startDate' => \Carbon\Carbon::parse($this->scheduling->start_date)->format('d/m/Y H:i'),
                'endDate' => \Carbon\Carbon::parse($this->scheduling->end_date)->format('d/m/Y H:i'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
