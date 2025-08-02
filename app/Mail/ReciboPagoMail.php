<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Aportes;

class ReciboPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;
    public $reciboUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Aportes $pago, $reciboUrl)
    {
        $this->pago = $pago;
        $this->reciboUrl = $reciboUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo de Pago Confirmado - ' . $this->pago->codigopago,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recibo-pago',
        );
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
