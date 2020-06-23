<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OrderProcessing extends Notification
{
    use Queueable;

    private $orden;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($orden)
    {
        $this->orden = $orden;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = "http://localhost:8080/usuario/perfil";

        return (new MailMessage)
            ->subject("Pago recibido")
            ->greeting('Gracias por tu compra!')
            ->line("Se esta procesando la orden #" . $this->orden->id . ". Te informaremos cuando haya sido despachada.")
            ->action('Ver ordenes', url($url))
            ->salutation(new HtmlString('<strong>Gracias por elegirnos!,<br/>Banitot Team</strong>'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
