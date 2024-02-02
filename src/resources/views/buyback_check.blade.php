@extends('web::layouts.grids.8-4')

@section('title', trans('buyback::global.browser_title'))
@section('page_header', trans('buyback::global.page_title'))

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/buyback.css') }}"/>
@endpush

@php
    use H4zz4rdDev\Seat\SeatBuyback\Helpers\PriceCalculationHelper;use Seat\Services\Settings\Profile;

    $thousand_separator = Profile::get('thousand_seperator');
    $decimal_separator = Profile::get('decimal_seperator');

    $finalPrice = number_format(PriceCalculationHelper::calculateFinalPrice(
                    $eve_item_data["parsed"]),2,$decimal_separator, $thousand_separator);
    $finalVolume = number_format(PriceCalculationHelper::calculateFinalVolume(
                                        $eve_item_data["parsed"]),2,$decimal_separator, $thousand_separator);
@endphp

@if(!empty($eve_item_data))
    @section('left')
        <div class="card">
            <div class="card-body">
                <label for="items">{{ trans('buyback::global.step_two_label') }}</label>
                <p>{{ trans('buyback::global.step_two_introduction') }}</p>
                <table class="table">
                    <thead class="thead bg-primary">
                    <tr>
                        <th scope="col" class="align-centered"
                            colspan="2">{{ trans('buyback::global.step_two_item_table_title') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($eve_item_data["parsed"] as $item)
                        @include('buyback::partials.contract-row', [
                            'typeId' => $item["typeId"],
                            'quantity' => $item["typeQuantity"],
                            'typeName' => $item["typeName"],
                            'marketOperationType' => $item["marketConfig"]["marketOperationType"],
                            'percentage' => $item["marketConfig"]["percentage"],
                            'price' => number_format($item["typeSum"],2,$decimal_separator, $thousand_separator)])
                    @endforeach
                    @include('buyback::partials.contract-footer', ['finalPrice' => $finalPrice, 'finalVolume' => $finalVolume])
                    </tbody>
                </table>
            </div>
        </div>
        @if(array_key_exists("ignored", $eve_item_data) && count($eve_item_data["ignored"]) > 0)
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <table class="table table-borderless">
                            <thead class="thead">
                            <tr>
                                <th class="align-centered bg-red">
                                    <span class="ml-2"><i class='fas fa-ban'></i>{{ trans('buyback::global.step_two_ignored_table_title') }}</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($eve_item_data["ignored"] as $item)
                                <tr>
                                    <td>
                                        <b>x{{ number_format($item["ItemQuantity"],0,$decimal_separator, $thousand_separator) }}</b>
                                        @include('web::partials.type', ['type_id' => $item["ItemId"], 'type_name' => ucwords($item["ItemName"])])
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @stop
    @section('right')
        <div class="card">
            <div class="card-body">
                <label for="items">{{ trans('buyback::global.step_three_label') }}</label>
                <p>{{ trans('buyback::global.step_three_introduction') }}</p>
                <form action="{{ route('buyback.contracts.insert') }}" method="post" id="contract-insert"
                      name="contract-insert">
                    @csrf
                    @include('buyback::partials.contract-summary')
                    <div>
                        <span><b>{{ trans('buyback::global.step_three_contract_tip_title') }}</b></span>
                        <p>{{ trans('buyback::global.step_three_contract_tip') }}</p>
                    </div>
                    <input type="hidden" value="{{ $contractId }}" name="contractId" id="contractId">
                    <input type="hidden" value="{{ json_encode($eve_item_data) }}" name="contractData"
                           id="contractId">
                    <input type="hidden" value="99" name="contractItemCount" id="contractItemCount">
                    <input type="hidden" value="{{ $finalPrice }}" name="contractFinalPrice"
                           id="contractFinalPrice">
                    <button type="submit"
                            class="btn btn-primary mb-2">{{ trans('buyback::global.step_three_button') }}</button>
                </form>
            </div>
        </div>
    @stop
@endif
