<?php

namespace H4zz4rdDev\Seat\SeatBuyback\Item;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use Seat\Services\Contracts\IPriceable;

class PriceableEveItem extends EveItem implements IPriceable
{
    public float $price;
    public float $sum;

    public function getTypeID(): int
    {
        return $this->typeModel->typeID;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price / $this->getAmount();
    }

    public function setSum(float $sum): void
    {
        $this->sum = $sum;
    }

    public function getSum(): float
    {
        return $this->sum ?? 0;
    }
}