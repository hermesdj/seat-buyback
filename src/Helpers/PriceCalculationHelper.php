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

namespace H4zz4rdDev\Seat\SeatBuyback\Helpers;

/**
 * Class PriceCalculationHelper
 *
 * @package H4zz4rdDev\Seat\SeatBuyback\Helpers
 */
class PriceCalculationHelper
{

    /**
     * @param array $itemData
     * @return float|null
     */
    public static function calculateFinalPrice(array $itemData): ?float
    {

        $finalPrice = 0;

        foreach ($itemData as $item) {
            $finalPrice += $item["typeSum"];
        }

        return $finalPrice;

    }

    /**
     * @param array $itemData
     * @return int|null
     */
    public static function calculateFinalVolume(array $itemData): ?int
    {
        $finalVolume = 0;

        foreach ($itemData as $item) {
            $finalVolume += $item["typeQuantity"] * $item["volume"];
        }

        return $finalVolume;
    }
}