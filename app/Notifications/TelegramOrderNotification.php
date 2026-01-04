<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramOrderNotification extends Notification
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        $botToken = app('global_telegram_bot_api') ?? null;
        $channelId = app('global_telegram_channel') ?? null;

        if (!$botToken || !$channelId) {
            return null;
        }

        $message = "📦 <b>NEW ORDER!</b>\n\n";
        $message .= "🆔 <b>ORDER NUMBER</b> <code>{$this->order->order_number}</code>\n";
        $message .= "👤 <b>Client:</b> {$this->order->account->name}\n";
        $message .= "📧 <b>Email:</b> {$this->order->account->email}\n";
        $message .= "☎️ <b>Phone:</b> {$this->order->account->phone}\n\n";
        $message .= "📋 <b>Details:</b>\n";
        $message .= "💰 <b>Subtotal:</b> {$this->order->sum_amount} {$this->order->currency->name}\n";
        $message .= "🚚 <b>Shipping:</b> {$this->order->delivery_price} {$this->order->currency->name}\n";
        
        if ($this->order->voucher_value > 0) {
            $message .= "🎟️ <b>Discount:</b> -{$this->order->voucher_value} {$this->order->currency->name}\n";
        }
        
        $message .= "💳 <b>TOTAL:</b> <u>{$this->order->final_amount} {$this->order->currency->name}</u>\n\n";
        
        $message .= "📍 <b>SHIP TO:</b>\n";
        $message .= "{$this->order->shipping->first_name} {$this->order->shipping->last_name}\n";
        $message .= "{$this->order->shipping->address1}\n";
        $message .= "{$this->order->shipping->city}, {$this->order->shipping->county}\n";
        $message .= "{$this->order->shipping->country}\n\n";

        $message .= "💬 <b>Payment Method:</b> {$this->order->payment->name}\n";

        return TelegramMessage::create()
            ->to($channelId)
            ->parseMode('HTML')
            ->content($message);
    }
}