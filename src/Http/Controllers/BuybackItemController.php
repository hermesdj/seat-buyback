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


use H4zz4rdDev\Seat\SeatBuyback\Models\BuybackMarketConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class BuybackController.
 *
 * @package H4zz4rdDev\Seat\SeatBuyback\Http\Controllers
 */
class BuybackItemController extends Controller
{
    public function publicItems(): View {
        $marketConfigs = BuybackMarketConfig::with('invType')->get();
        return view('buyback::public_items', compact('marketConfigs'));
    }

    /**
     * @return View
     */
    public function getHome(): View
    {
        $marketConfigs = BuybackMarketConfig::with('invType')->get();
        return view('buyback::buyback_item', compact('marketConfigs'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addMarketConfig(Request $request): RedirectResponse
    {

        $request->validate([
            'admin-market-typeId' => 'required|max:255',
            'admin-market-operation' => 'required',
            'admin-market-percentage' => 'required|numeric|between:0,99.99',
            'admin-market-price' => 'required|numeric'
        ]);

        $item = BuybackMarketConfig::where('typeId', (int)$request->get('admin-market-typeId'))->first();

        if ($item != null) {
            return redirect()->route('buyback.item')
                ->with(['error' => trans('buyback::global.admin_error_config') . $item->typeId]);
        }

        $item = new BuybackMarketConfig([
            'typeId' => (int)$request->get('admin-market-typeId'),
            'marketOperationType' => (int)$request->get('admin-market-operation'),
            'percentage' => (int)$request->get('admin-market-percentage'),
            'price' => (int)$request->get("admin-market-price")
        ]);

        $item->save();

        return redirect()->route('buyback.item')
            ->with('success', trans('buyback::global.admin_success_market_add'));
    }

    /**
     * @param Request $request
     * @param int $typeId
     * @return RedirectResponse
     */
    public function removeMarketConfig(Request $request, int $typeId): RedirectResponse
    {

        if (!$request->isMethod('get') || empty($typeId) || !is_numeric($typeId)) {
            return redirect()->back()
                ->with(['error' => trans('buyback::global.error')]);
        }

        BuybackMarketConfig::destroy($typeId);

        return redirect()->back()
            ->with('success', trans('buyback::global.admin_success_market_remove'));
    }
}
