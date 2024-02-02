@php
    use Seat\Services\Settings\Profile;

         $thousand_separator = Profile::get('thousand_seperator');
         $decimal_separator = Profile::get('decimal_seperator');
         $finalPrice = number_format(H4zz4rdDev\Seat\SeatBuyback\Helpers\PriceCalculationHelper::calculateFinalPrice(
                        json_decode($contract->contractData, true)["parsed"]),2, $decimal_separator, $thousand_separator);
         $finalVolume = number_format(H4zz4rdDev\Seat\SeatBuyback\Helpers\PriceCalculationHelper::calculateFinalVolume(
            json_decode($contract->contractData, true)["parsed"]),2,$decimal_separator, $thousand_separator);
@endphp

<table class="table table-borderless">
    <tbody>
    @foreach(json_decode($contract->contractData)->parsed as $item )
        @include('buyback::partials.contract-row', [
            'typeId' => $item->typeId,
            'quantity' => $item->typeQuantity,
            'typeName' => $item->typeName,
            'marketOperationType' => $item->marketConfig->marketOperationType,
            'percentage' => $item->marketConfig->percentage,
            'price' => number_format($item->typeSum,2,$decimal_separator, $thousand_separator)])
    @endforeach
    @include('buyback::partials.contract-footer', ['finalPrice' => $finalPrice, 'finalVolume' => $finalVolume])
    </tbody>
</table>
