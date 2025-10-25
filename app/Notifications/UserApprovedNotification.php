<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserApprovedNotification extends Notification
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
            ->subject('অ্যাকাউন্ট অনুমোদিত - DC Relief Management System')
            ->greeting('প্রিয় ' . $notifiable->name . ',')
            ->line('আপনার DC Relief Management System অ্যাকাউন্ট সফলভাবে অনুমোদিত হয়েছে!')
            ->line('এখন আপনি সিস্টেমে লগইন করে সকল সুবিধা ব্যবহার করতে পারবেন।')
            ->action('সিস্টেমে লগইন করুন', route('login'))
            ->line('আপনার অ্যাকাউন্টের তথ্য:')
            ->line('ইমেইল: ' . $notifiable->email)
            ->line('ফোন: ' . $notifiable->phone)
            ->line('অনুমোদনের সময়: ' . now()->format('d/m/Y H:i:s'))
            ->line('ধন্যবাদ, DC Relief Management System')
            ->salutation('শুভেচ্ছান্তে, প্রশাসনিক দল');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Account approved',
            'user_id' => $notifiable->id,
            'approved_at' => now(),
        ];
    }
}
