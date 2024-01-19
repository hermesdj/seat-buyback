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

namespace H4zz4rdDev\Seat\SeatBuyback\Http\Controllers;

use H4zz4rdDev\Seat\SeatBuyback\Exceptions\ItemParserBadFormatException;
use H4zz4rdDev\Seat\SeatBuyback\Helpers;
use H4zz4rdDev\Seat\SeatBuyback\Services\ItemService;
use H4zz4rdDev\Seat\SeatBuyback\Services\SettingsService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RecursiveTree\Seat\PricesCore\Exceptions\PriceProviderException;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class BuybackController.
 *
 * @package H4zz4rdDev\Seat\SeatBuyback\Http\Controllers
 */
class BuybackController extends Controller
{
    private const MaxContractIdLength = 6;

    /**
     * @var ItemService
     */
    public ItemService $itemService;

    /**
     * @var SettingsService
     */
    public SettingsService $settingsService;


    /**
     * Constructor
     */
    public function __construct(ItemService $itemService, SettingsService $settingsService)
    {
        $this->itemService = $itemService;
        $this->settingsService = $settingsService;
    }

    /**
     * @return View
     */
    public function getHome(): View
    {
        return view('buyback::buyback', [
            'maxAllowedItems' => $this->settingsService->getMaxAllowedItems()
        ]);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|Redirector|RedirectResponse
     */
    public function checkItems(Request $request): Application|Factory|View|Redirector|RedirectResponse
    {
        $request->validate([
            'items' => 'required',
        ]);

        try {
            $parsedItems = $this->itemService->parseEveItemData($request->get('items'));

            if ($parsedItems == null) {
                return redirect('buyback')->withErrors(
                    ['errors' => trans('buyback::global.error_price_provider_down')]);
            }

            if (!array_key_exists("parsed", $parsedItems)) {
                return redirect('buyback')->withErrors(['errors' => trans('buyback::global.error_empty_item_field')]);
            }

            $maxAllowedItems = $this->settingsService->getMaxAllowedItems();

            if ((is_countable($parsedItems["parsed"]) ? count($parsedItems["parsed"]) : 0) > $maxAllowedItems) {
                return redirect('buyback')->withErrors(
                    ['errors' => trans('buyback::global.error_too_much_items', ['count' => $maxAllowedItems])]);
            }

            $finalPrice = Helpers\PriceCalculationHelper::calculateFinalPrice($parsedItems["parsed"]);

            return view('buyback::buyback_check', [
                'eve_item_data' => $parsedItems,
                'maxAllowedItems' => $this->settingsService->getMaxAllowedItems(),
                'finalPrice' => $finalPrice,
                'contractTo' => $this->settingsService->getAdminContractTo(),
                'contractExpiration' => $this->settingsService->getAdminContractExpiration(),
                'contractId' => Helpers\MiscHelper::generateRandomString(self::MaxContractIdLength)
            ]);

        } catch (ItemParserBadFormatException $e) {
            Log::error($e->getMessage());
            return redirect('buyback')->withErrors(
                ['errors' => trans('buyback::global.error_item_parser_format')]
            );
        } catch (PriceProviderException $e) {
            Log::error($e->getMessage());
            return redirect('buyback')->withErrors(
                ['errors' => trans('buyback::global.error_price_provider_down')]
            );
        }
    }
}