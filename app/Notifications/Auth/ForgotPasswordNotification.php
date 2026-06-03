<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $code
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('emails.auth.forgot_password.subject', ['code' => $this->code]))
            ->greeting(trans('emails.auth.forgot_password.greeting', ['name' => $notifiable->name]))
            ->line(trans('emails.auth.forgot_password.line_1'))
            ->line(trans('emails.auth.forgot_password.line_2', ['code' => $this->code]))
            ->line(trans('emails.auth.forgot_password.line_3'))
            ->line(trans('emails.auth.forgot_password.line_4'))
            ->salutation(trans('emails.auth.forgot_password.salutation', ['app_name' => config('app.name')]));
    }
}
