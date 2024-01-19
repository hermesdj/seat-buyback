<?php

/*
This file is part of SeAT

Copyright (C) 2015 to 2020  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace H4zz4rdDev\Seat\SeatBuyback\Services;

use H4zz4rdDev\Seat\SeatBuyback\Exceptions\ItemParserBadFormatException;
use H4zz4rdDev\Seat\SeatBuyback\Item\PriceableEveItem;
use H4zz4rdDev\Seat\SeatBuyback\Parser\InventoryParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RecursiveTree\Seat\PricesCore\Exceptions\PriceProviderException;
use RecursiveTree\Seat\PricesCore\Facades\PriceProviderSystem;

/**
 * Class ItemService
 */
class ItemService
{
    private SettingsService $settingsService;
    private PriceCalculationService $priceService;

    /**
     */
    public function __construct(SettingsService $settingsService, PriceCalculationService $priceService)
    {
        $this->settingsService = $settingsService;
        $this->priceService = $priceService;
    }

    /**
     * @param string $item_string
     * @return array
     * @throws ItemParserBadFormatException
     * @throws PriceProviderException
     */
    public function parseEveItemData(string $item_string): array
    {
        if (empty($item_string)) {
            throw new ItemParserBadFormatException("Empty string not supported");
        }

        $parser_result = InventoryParser::parseItems($item_string, PriceableEveItem::class);

        if ($parser_result->items->isEmpty()) {
            throw new ItemParserBadFormatException("Could not parse provided string or item list is empty");
        }
        
        $priceProviderId = $this->settingsService->getPriceProviderId();

        if ($priceProviderId == null) {
            throw new PriceProviderException("Price provider not configured");
        }

        PriceProviderSystem::getPrices($priceProviderId, $parser_result->items);

        foreach ($parser_result->items as $item) {
            $this->priceService->calculateItemPrice($item);
        }

        return $this->categorizeItems($parser_result->items);
    }

    /**
     * @param Collection<PriceableEveItem> $itemData
     * @return array|null
     */
    private function categorizeItems(Collection $itemData): ?array
    {
        $parsedItems = [];
        foreach ($itemData as $key => $item) {
            $result = DB::table('invTypes as it')
                ->join('invGroups as ig', 'it.groupID', '=', 'ig.GroupID')
                ->select(
                    'it.typeID as typeID',
                    'it.typeName as typeName',
                    'it.description as description',
                    'ig.GroupName as groupName',
                    'ig.GroupID as groupID',
                    'it.volume as volume'
                )
                ->where('it.typeID', '=', $item->getTypeID())
                ->first();

            $marketConfig = DB::table('buyback_market_config as bmc')
                ->select(
                    'percentage',
                    'marketOperationType'
                )
                ->where('bmc.typeId', $item->getTypeID())
                ->first();

            if (empty($marketConfig)) {
                $marketConfig = [
                    'percentage' => $this->settingsService->defaultPricesPercentage(),
                    'marketOperationType' => $this->settingsService->defaultPricesMarketOperationType(),
                ];
            }

            if (empty($result)) {
                Log::debug("Ignore item .$item->typeModel->typeName");
                $parsedItems["ignored"][] = [
                    'ItemId' => $item->getTypeID(),
                    'ItemName' => $item->typeModel->typeName,
                    'ItemQuantity' => $item->getAmount()
                ];

                continue;
            }

            Log::debug(print_r($result, true));

            if (!array_key_exists($result->groupID, $parsedItems)) {
                Log::debug("Found item .$item->typeModel->typeName");
                $parsedItems["parsed"][$key]["typeId"] = $item->typeModel->typeID;
                $parsedItems["parsed"][$key]["typeName"] = $item->typeModel->typeName;
                $parsedItems["parsed"][$key]["typeQuantity"] = $item->getAmount();
                $parsedItems["parsed"][$key]["typeSum"] = $item->sum;
                $parsedItems["parsed"][$key]["groupId"] = $item->typeModel->groupID;
                $parsedItems["parsed"][$key]["marketGroupName"] = $result->groupName;
                $parsedItems["parsed"][$key]["volume"] = $result->volume;
                $parsedItems["parsed"][$key]["marketConfig"] = [
                    'percentage' => $marketConfig["percentage"],
                    'marketOperationType' => $marketConfig["marketOperationType"]
                ];
            }
        }

        return $parsedItems;
    }
}
