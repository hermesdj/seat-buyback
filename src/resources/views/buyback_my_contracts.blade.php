@extends('web::layouts.grids.8-4')

@section('title', trans('buyback::global.character_contract_browser_title'))
@section('page_header', trans('buyback::global.character_contract_page_title'))

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/buyback.css') }}"/>
@endpush

@php
    use H4zz4rdDev\Seat\SeatBuyback\Helpers\PriceCalculationHelper;use Seat\Services\Settings\Profile;
    $thousand_separator = Profile::get('thousand_seperator');
            $decimal_separator = Profile::get('decimal_seperator');
@endphp

@section('left')
    <div class="card">
        <div class="card-body">
            <span>{{ trans('buyback::global.my_contract_introduction') }}</span>
        </div>
    </div>
    <h5>{{ trans('buyback::global.my_contracts_open_title') }}</h5>
    @if($openContracts->isEmpty())
        <p>{{ trans('buyback::global.my_contracts_open_error') }}</p>
    @endif
    <div id="accordion-open">
        @foreach($openContracts as $contract)
            @php
                $contractFinalPrice = number_format(H4zz4rdDev\Seat\SeatBuyback\Helpers\PriceCalculationHelper::calculateFinalPrice(
                    json_decode($contract->contractData, true)["parsed"]),2,$decimal_separator, $thousand_separator);

                $contractFinalVolume = number_format(H4zz4rdDev\Seat\SeatBuyback\Helpers\PriceCalculationHelper::calculateFinalVolume(
                                                json_decode($contract->contractData, true)["parsed"]),2,$decimal_separator, $thousand_separator);
            @endphp
            <div class="card">
                <div class="card-header border-secondary" data-toggle="collapse"
                     data-target="#collapse_{{ $contract->contractId }}"
                     aria-expanded="true" aria-controls="collapse_{{ $contract->contractId }}"
                     id="heading_{{ $contract->contractId }}">
                    <div class="mb-0">
                        @include('buyback::partials.contract-heading', ['contract' => $contract, 'withDeleteButton' => true, 'withFinishButton' => false])
                    </div>
                </div>
                <div id="collapse_{{ $contract->contractId }}" class="collapse"
                     aria-labelledby="heading_{{ $contract->contractId }}" data-parent="#accordion-open">
                    <div class="card-body">
                        @include('buyback::partials.contract-details', ['contract' => $contract])
                    </div>
                </div>
            </div>
        @endforeach
        <h5>{{ trans('buyback::global.my_contracts_closed_title') }}</h5>
        @if($closedContracts->isEmpty())
            <p>{{ trans('buyback::global.my_contracts_closed_error') }}</p>
        @endif
        <div id="accordion-closed">
            @foreach($closedContracts as $contract)
                @php
                    $contractFinalPrice = number_format(PriceCalculationHelper::calculateFinalPrice(
                        json_decode($contract->contractData, true)["parsed"]),2,$decimal_separator, $thousand_separator);

                    $contractFinalVolume = number_format(PriceCalculationHelper::calculateFinalVolume(
                                                    json_decode($contract->contractData, true)["parsed"]),2,$decimal_separator, $thousand_separator);
                @endphp
                <div class="card">
                    <div
                            class="card-header border-secondary bg-success"
                            data-toggle="collapse"
                            data-target="#collapse_{{ $contract->contractId }}"
                            aria-expanded="true"
                            aria-controls="collapse_{{ $contract->contractId }}"
                            id="heading_{{ $contract->contractId }}"
                    >
                        <div class="mb-0">
                            @include('buyback::partials.contract-heading', ['contract' => $contract, 'withDeleteButton' => false, 'withFinishButton' => false])
                        </div>
                    </div>
                    <div
                            id="collapse_{{ $contract->contractId }}"
                            class="collapse"
                            aria-labelledby="heading_{{ $contract->contractId }}"
                            data-parent="#accordion-closed"
                    >
                        <div class="card-body">
                            @include('buyback::partials.contract-details', ['contract' => $contract])
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
@stop