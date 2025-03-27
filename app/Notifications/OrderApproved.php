<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderApproved extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database']; // Gửi qua Email và lưu vào Database
    }

    /**
     * Get the mail representation of the notification.
     */
    // Gửi thông báo qua email
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Đơn hàng đã được duyệt')
                    ->line("Đơn hàng #{$this->order->id} của bạn đã được duyệt.")
                    ->action('Xem đơn hàng', url('/orders/' . $this->order->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Đơn hàng #{$this->order->id} đã được duyệt.",
            'order_id' => $this->order->id,
        ];
    }
}
