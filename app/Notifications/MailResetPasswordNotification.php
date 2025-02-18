<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class MailResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
        //             $link = url( "/reset-password/".$this->token );
        $link = url( "/reset-password/".$this->token );
        // return ( new MailMessage )
        //             ->subject( 'Reset Password Notification' )
        //             ->line( "Hello! You are receiving this email because we received a password reset request for your account." )
        //             ->action( 'Reset Password', $link )
        //             ->line( "This password reset link will expire in ".config('auth.passwords.users.expire')." minutes" )
        //             ->line( "If you did not request a password reset, no further action is required." );
        return (new MailMessage)->view('email_template.forgot_password', ['link' => $link])
                                ->subject("Reset Password")
                                ->from($address = config('mail.from_address'), $name = 'Ress');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
