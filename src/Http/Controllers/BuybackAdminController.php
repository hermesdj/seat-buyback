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

use H4zz4rdDev\Seat\SeatBuyback\Services\SettingsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class BuybackAdminController.
 *
 * @package H4zz4rdDev\Seat\SeatBuyback\Http\Controllers
 */
class BuybackAdminController extends Controller
{
    /**
     * @var SettingsService
     */
    public SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @return Application|View|Factory
     */
    public function getHome(): Application|View|Factory
    {
        return view('buyback::buyback_admin', [
            'settings' => $this->settingsService->getAll()
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateSettings(Request $request): RedirectResponse
    {

        $request->validate([
            'admin_max_allowed_items' => 'required|numeric|between:1,50',
            'admin_contract_contract_to' => 'required|max:128',
            'admin_contract_expiration' => 'required|max:32',
            'admin_price_provider' => 'required|numeric',
            'admin_allow_default_prices' => 'boolean',
            'admin_default_prices_percentage' => 'numeric|between:1,100',
            'admin_default_prices_operation_type' => 'numeric|between:0,1'
        ]);

        if ($request->all() == null) {
            return redirect()->back()
                ->with(['error' => trans('buyback::global.error')]);
        }

        $this->settingsService->setAll($request->all());

        return redirect()->back()
            ->with('success', trans('buyback::global.admin_success_config'));
    }
}
