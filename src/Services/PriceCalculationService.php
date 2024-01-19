<?php

namespace H4zz4rdDev\Seat\SeatBuyback\Services;

use H4zz4rdDev\Seat\SeatBuyback\Item\PriceableEveItem;
use H4zz4rdDev\Seat\SeatBuyback\Models\BuybackMarketConfig;

class PriceCalculationService
{
    private SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * @param PriceableEveItem $item
     * @return void
     */
    public function calculateItemPrice(PriceableEveItem $item): void
    {
        $marketConfig = $this->resolveBuybackMarketConfig($item->getTypeID());

        if ($marketConfig == null) {
            return;
        }

        if ($marketConfig->price > 0) {
            $item->setSum($item->getAmount() * $marketConfig->price);
            return;
        }

        $priceSum = $item->getAmount() * $item->price;

        $pricePercentage = $marketConfig->getMarketOperator() * $priceSum * $marketConfig->getPercentage();

        $item->setSum($priceSum + $pricePercentage);
    }

    public function resolveBuybackMarketConfig(int $typeID): ?BuybackMarketConfig
    {
        $marketConfig = BuybackMarketConfig::where('typeId', $typeID)->first();

        if ($marketConfig == null) {
            if ($this->settingsService->defaultPricesAllowed()) {
                $marketConfig = new BuybackMarketConfig();
                $marketConfig->price = 0;
                $marketConfig->marketOperationType = $this->settingsService->defaultPricesMarketOperationType();
                $marketConfig->percentage = $this->settingsService->defaultPricesPercentage();
            }
        }

        return $marketConfig;
    }
}