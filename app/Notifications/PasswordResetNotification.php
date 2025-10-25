<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('পাসওয়ার্ড রিসেট - DC Relief Management System')
            ->greeting('প্রিয় ' . $notifiable->name . ',')
            ->line('আপনার পাসওয়ার্ড রিসেটের অনুরোধ পেয়েছি।')
            ->line('নিচের বাটনে ক্লিক করে আপনার পাসওয়ার্ড রিসেট করুন:')
            ->action('পাসওয়ার্ড রিসেট করুন', $url)
            ->line('এই লিঙ্কটি ' . config('auth.passwords.users.expire') . ' মিনিটের মধ্যে কার্যকর থাকবে।')
            ->line('যদি আপনি পাসওয়ার্ড রিসেটের অনুরোধ না করে থাকেন, তাহলে এই ইমেইলটি উপেক্ষা করুন।')
            ->salutation('ধন্যবাদ, DC Relief Management System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ];
    }
}
