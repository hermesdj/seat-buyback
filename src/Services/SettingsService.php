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

use H4zz4rdDev\Seat\SeatBuyback\Exceptions\SettingsServiceException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class SettingsService
 */
class SettingsService
{

    private array $_settings;

    public function __construct()
    {
        $this->loadAll();
    }

    /**
     * @param string $setting
     * @return string
     * @throws SettingsServiceException
     */
    public function get(string $setting): string
    {
        if (!array_key_exists($setting, $this->_settings)) {
            throw new SettingsServiceException(trans('buyback::global.admin_setting_error', ['message' => $setting]));
        }
        return $this->_settings[$setting];
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     * @throws SettingsServiceException
     */
    public function set(string $key, string $value): void
    {
        if (!array_key_exists($key, $this->_settings)) {
            throw new SettingsServiceException(trans('buyback::global.admin_setting_error', ['message' => $key]));
        }

        try {
            DB::table('buyback_admin_config')
                ->where('name', $key)
                ->update(['value' => $value]);
            $this->_settings[$key] = $value;
        } catch (QueryException $e) {
            Log::error('QueryException: ' . $e->getMessage());
        }
    }

    /**
     * @param array $newSettings
     * @return void
     */
    public function setAll(array $newSettings): void
    {

        if (count($newSettings) <= 0) {
            return;
        }

        foreach ($newSettings as $key => $value) {

            if ($key != "_token") {
                try {
                    if ($this->_settings[$key] != $value) {
                        $this->set($key, $value);
                    }
                } catch (SettingsServiceException $e) {
                    Log::error('SettingException: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->_settings;
    }

    /**
     * @return int
     */
    public function getMaxAllowedItems(): int
    {
        return intval($this->_settings["admin_max_allowed_items"]);
    }

    public function getPriceProviderId(): int
    {
        return intval($this->_settings["admin_price_provider"]);
    }

    public function getAdminContractTo(): string
    {
        return $this->_settings["admin_contract_contract_to"];
    }

    public function getAdminContractExpiration(): int
    {
        return intval($this->_settings["admin_contract_expiration"]);
    }

    public function defaultPricesAllowed(): bool
    {
        return boolval($this->_settings["admin_allow_default_prices"]);
    }

    public function defaultPricesPercentage(): int
    {
        return intval($this->_settings["admin_default_prices_percentage"]);
    }

    public function defaultPricesMarketOperationType(): int
    {
        return intval($this->_settings["admin_default_prices_operation_type"]);
    }

    /**
     * @return void
     */
    private function loadAll(): void
    {
        try {
            $settingData = DB::table('buyback_admin_config')
                ->get();

            foreach ($settingData as $item) {
                $this->_settings[$item->name] = $item->value;
            }
        } catch (QueryException $e) {
            Log::error('QueryException: ' . $e->getMessage());
        }
    }
}
