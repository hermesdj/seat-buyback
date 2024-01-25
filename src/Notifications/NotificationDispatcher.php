<?php

namespace H4zz4rdDev\Seat\SeatBuyback\Notifications;

use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;

class NotificationDispatcher
{
    use NotificationDispatchTool;

    public static function dispatchNewBuyback($contractId, $finalPrice, $itemCount): void
    {
        $dispatcher = new NotificationDispatcher();

        $data = [
            'contractId' => $contractId,
            'finalPrice' => $finalPrice,
            'itemCount' => $itemCount
        ];

        $dispatcher->dispatch('seat_buyback_new_buyback_notification', $data);
    }

    private function dispatch($type, $data): void
    {
        $groups = NotificationGroup::with('alerts')
            ->whereHas('alerts', function ($query) use ($type) {
                $query->where('alert', $type);
            })->get();

        $this->dispatchNotifications($type, $groups, function ($constructor) use ($data) {
            return new $constructor($data);
        });
    }

}