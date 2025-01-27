<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class PasswordResetSuccess extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return (new MailMessage)
            ->subject("Contraseña actualizada")
            ->greeting("Hola de nuevo!")
            ->line('Se cambió la contraseña correctamente')
            ->line('Si fuiste tú quien solicitó el cambio, no precisa que hagas mas nada.')
            ->line('En caso de no haber sido tu, por favor protege tu cuenta.')
            ->salutation(new HtmlString('<strong>Saludos,<br/>Banitot Team</strong>'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public
    function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
