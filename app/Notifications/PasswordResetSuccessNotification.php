<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetSuccessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->subject('পাসওয়ার্ড সফলভাবে পরিবর্তিত - DC Relief Management System')
            ->greeting('প্রিয় ' . $notifiable->name . ',')
            ->line('আপনার পাসওয়ার্ড সফলভাবে পরিবর্তিত হয়েছে।')
            ->line('নতুন পাসওয়ার্ড দিয়ে আপনি এখন সিস্টেমে লগইন করতে পারবেন।')
            ->action('সিস্টেমে লগইন করুন', route('login'))
            ->line('যদি আপনি এই পরিবর্তনটি না করে থাকেন, তাহলে দয়া করে আমাদের সাথে যোগাযোগ করুন।')
            ->line('আপনার অ্যাকাউন্টের নিরাপত্তার জন্য আপনার পাসওয়ার্ড গোপন রাখুন।')
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
            'message' => 'Password reset successful',
            'user_id' => $notifiable->id,
            'reset_at' => now(),
        ];
    }
}
