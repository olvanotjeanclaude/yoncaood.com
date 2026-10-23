<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PassengerInformation;

class PaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public  string $orderNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Bilet Bilgileriniz.',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $passengers =  PassengerInformation::with(["contact", "originLocation", "destinationLocation"])
            ->where("orderid", $this->orderNumber)
            ->get();

        return new Content(
            view: 'mails.paymentSuccess',
            with: [
                'passengers' => $passengers
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
