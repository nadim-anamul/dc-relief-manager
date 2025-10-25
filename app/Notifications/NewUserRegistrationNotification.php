<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistrationNotification extends Notification
{
    use Queueable;

    protected $newUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
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
        return (new MailMessage)
            ->subject('নতুন ব্যবহারকারী নিবন্ধন - DC Relief Management System')
            ->greeting('প্রিয় প্রশাসক,')
            ->line('DC Relief Management System এ একজন নতুন ব্যবহারকারী নিবন্ধন করেছেন।')
            ->line('ব্যবহারকারীর তথ্য:')
            ->line('নাম: ' . $this->newUser->name)
            ->line('ইমেইল: ' . $this->newUser->email)
            ->line('ফোন: ' . $this->newUser->phone)
            ->line('নিবন্ধনের সময়: ' . $this->newUser->created_at->format('d/m/Y H:i:s'))
            ->action('ব্যবহারকারী অনুমোদন করুন', route('admin.users.index'))
            ->line('অনুগ্রহ করে ব্যবহারকারীর অ্যাকাউন্ট পর্যালোচনা করে অনুমোদন বা প্রত্যাখ্যান করুন।')
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
            'user_id' => $this->newUser->id,
            'user_name' => $this->newUser->name,
            'user_email' => $this->newUser->email,
            'registration_time' => $this->newUser->created_at,
        ];
    }
}
