<?php

namespace H4zz4rdDev\Seat\SeatBuyback\Notifications\Discord;

use H4zz4rdDev\Seat\SeatBuyback\Notifications\NotificationHelper;
use Illuminate\Queue\SerializesModels;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

class NewBuybackDiscordNotification extends AbstractDiscordNotification
{
    use SerializesModels;

    private mixed $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    protected function populateMessage(DiscordMessage $message, $notifiable): void
    {
        $message
            ->success()
            ->embed(NotificationHelper::BuildDiscordNotificationEmbed($this->data));
    }
}