<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPendingApprovalNotification extends Notification
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
            ->subject('অ্যাকাউন্ট অনুমোদনের জন্য অপেক্ষা - DC Relief Management System')
            ->greeting('প্রিয় ' . $notifiable->name . ',')
            ->line('আপনার DC Relief Management System অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে।')
            ->line('তবে, সিস্টেম ব্যবহারের জন্য আপনার অ্যাকাউন্টটি প্রশাসক কর্তৃক অনুমোদিত হতে হবে।')
            ->line('আপনার অ্যাকাউন্ট অনুমোদিত হওয়ার পর আপনাকে একটি ইমেইল পাঠানো হবে।')
            ->line('অনুগ্রহ করে ধৈর্য্য ধারণ করুন।')
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
            'message' => 'Account pending admin approval',
            'user_id' => $notifiable->id,
        ];
    }
}
