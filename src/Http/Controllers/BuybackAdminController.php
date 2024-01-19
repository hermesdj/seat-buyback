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

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateDiscordSettings(Request $request): RedirectResponse
    {
        if ($request->all() == null) {
            return redirect()->back()
                ->with(['error' => trans('buyback::global.error')]);
        }

        $request->validate([
            'admin_discord_status' => 'required|numeric|between:0,1',
            'admin_discord_webhook_url' => 'required|url',
            'admin_discord_webhook_bot_name' => 'required|max:32|min:3',
            'admin_discord_webhook_color' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ]
        ]);

        if ($this->getDomainName($request->get('admin_discord_webhook_url')) != 'discord.com') {
            return redirect()->back()
                ->with(['error' => trans('buyback::global.admin_discord_error_url')]);
        }

        $this->settingsService->setAll($request->all());

        return redirect()->back()
            ->with('success', trans('buyback::global.admin_success_config'));
    }

    /**
     * @param $url
     * @return false|string
     */
    function getDomainName($url): false|string
    {
        $pieces = parse_url((string)$url);
        $domain = $pieces['host'] ?? '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return FALSE;
    }
}
