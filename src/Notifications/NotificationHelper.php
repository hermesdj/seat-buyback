<?php

namespace H4zz4rdDev\Seat\SeatBuyback\Notifications;

use Closure;
use H4zz4rdDev\Seat\SeatBuyback\Models\BuybackContract;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;

class NotificationHelper
{
    public static function BuildDiscordNotificationEmbed($data): Closure
    {
        $contract = BuybackContract::where('contractId', $data['contractId'])->first();

        return function (DiscordEmbed $embed) use ($data, $contract) {
            $finalPrice = $data['finalPrice'];
            $itemCount = $data['itemCount'];

            $embed
                ->title(trans('buyback::notifications.new_buyback_title'))
                ->description(trans('buyback::notifications.new_buyback_desc'))
                ->field(trans('buyback::notifications.new_buyback_field_value'), number_format($finalPrice, 0, ',', '.') . " ISK")
                ->field(trans('buyback::notifications.new_buyback_field_item_count'), trans('buyback::notifications.new_buyback_field_item_value', ['itemCount' => $itemCount]))
                ->field(trans('buyback::notifications.new_buyback_field_contract_id'), $contract->contractId)
                ->author($contract->issuer->name, config('buyback.discord.eve.imageServerUrl') . $contract->issuer->character_id . "/portrait");
        };
    }
}