<?php

return [
    'seat_buyback_new_buyback_notification' => [
        'label' => 'buyback::notifications.seat_buyback_new_buyback_notification_label',
        'handlers' => [
            'discord' => \H4zz4rdDev\Seat\SeatBuyback\Notifications\Discord\NewBuybackDiscordNotification::class
        ]
    ]
];