<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('orders.show', ['order' => $this->order->id, 'token' => $this->order->public_token]));

        return (new MailMessage)
                    ->subject('Votre commande #' . $this->order->id)
                    ->greeting('Bonjour,')
                    ->line('Merci pour votre commande. Vous pouvez suivre l’état de livraison en cliquant sur le lien ci‑dessous :')
                    ->action('Suivre ma commande', $url)
                    ->line('Ce lien expirera le ' . ($this->order->public_token_expires_at ? $this->order->public_token_expires_at->toDayDateTimeString() : 'bientôt'))
                    ->line('Merci pour votre confiance.');
    }
}
