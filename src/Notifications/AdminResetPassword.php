<?php

namespace Helori\LaravelCms\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
            ->subject(config('app.name').' : Ré-initialisation de votre mot de passe')
            ->line([
                'Vous recevez cet email car nous avons reçu une demande de ré-initialisation de mot de passe pour votre compte.',
                'Cliquez sur ce bouton pour ré-initialiser votre mot de passe :',
            ])
            ->action('Ré-initialiser le mot de passe', route('admin-password-reset', ['token' => $this->token]))
            ->line('Si vous n\'avez pas demandé à ré-initialiser votre mot de passe, alors vous n\'avez rien à faire.');
    }
}
