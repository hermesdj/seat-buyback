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

use H4zz4rdDev\Seat\SeatBuyback\Models\BuybackContract;
use H4zz4rdDev\Seat\SeatBuyback\Notifications\NotificationDispatcher;
use H4zz4rdDev\Seat\SeatBuyback\Services\SettingsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use JsonException;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class BuybackContractController
 *
 * @package H4zz4rdDev\Seat\SeatBuyback\Http\Controllers
 */
class BuybackContractController extends Controller
{

    private SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @return View
     */
    public function getHome(): View
    {
        $contracts = BuybackContract::where('contractStatus', '=', 0)
            ->with('issuer')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyback::buyback_contract', [
            'contracts' => $contracts
        ]);
    }

    /**
     * @return Application|View|Factory
     */
    public function getCharacterContracts(): Application|View|Factory
    {
        $user = Auth::user();

        $openContracts = BuybackContract::where('contractStatus', 0)
            ->where('issuer_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $closedContracts = BuybackContract::where('contractStatus', 1)
            ->where('issuer_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('buyback::buyback_my_contracts', compact('openContracts', 'closedContracts'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws JsonException
     */
    public function insertContract(Request $request): RedirectResponse
    {

        $request->validate([
            'contractId' => 'required',
            'contractData' => 'required',
            'contractFinalPrice' => 'required'
        ]);

        $user = Auth::user();

        $contract = new BuybackContract();
        $contract->contractId = $request->get('contractId');
        $contract->issuer_id = $user->id;
        $contract->contractData = $request->get('contractData');
        $contractFinalPrice = (float)$request->get('contractFinalPrice');
        $contract->save();

        $itemCount = is_countable(json_decode((string)$contract->contractData, true, 512, JSON_THROW_ON_ERROR)['parsed']) ? count(json_decode((string)$contract->contractData, true, 512, JSON_THROW_ON_ERROR)['parsed']) : 0;

        NotificationDispatcher::dispatchNewBuyback($contract->contractId, $contractFinalPrice, $itemCount);

        return redirect()->route('buyback.character.contracts')
            ->with('success', trans('buyback::global.contract_success_submit', ['id' => $request->get('contractId')]));
    }

    /**
     * @param Request $request
     * @param string $contractId
     * @return RedirectResponse
     */
    public function deleteContract(Request $request, string $contractId): RedirectResponse
    {
        if (!$request->isMethod('get') || $contractId === '') {
            return redirect()->back()
                ->with(['error' => trans('buyback::global.error')]);
        }

        BuybackContract::destroy($contractId);

        return redirect()->back()
            ->with('success', trans('buyback::global.contract_success_deleted', ['id' => $contractId]));
    }

    /**
     * @param Request $request
     * @param string $contractId
     * @return RedirectResponse
     */
    public function succeedContract(Request $request, string $contractId): RedirectResponse
    {
        if (!$request->isMethod('get') || $contractId === '') {
            return redirect()->back()
                ->with(['error' => trans('buyback::global.error')]);
        }

        $contract = BuybackContract::where('contractId', $contractId)->first();
        if (!$contract->contractStatus) {

            $contract->contractStatus = 1;
            $contract->save();

            return redirect()->back()
                ->with('success', trans('buyback::global.contract_success_succeeded', ['id' => $contractId]));
        }

        return redirect()->back()
            ->with(['error' => trans('buyback::global.error')]);
    }
}
