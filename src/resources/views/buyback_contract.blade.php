@extends('web::layouts.grids.8-4')

@section('title', trans('buyback::global.contract_browser_title'))
@section('page_header', trans('buyback::global.contract_page_title'))

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/buyback.css') }}"/>
@endpush

@php
    use H4zz4rdDev\Seat\SeatBuyback\Helpers\PriceCalculationHelper;
    use Seat\Services\Settings\Profile;

     $thousand_separator = Profile::get('thousand_seperator');
     $decimal_separator = Profile::get('decimal_seperator');
@endphp

@section('left')
    <div class="card">
        <div class="card-body">
            <span>{{ trans('buyback::global.contract_introduction') }}</span>
        </div>
    </div>
    @if($contracts->isEmpty())
        <h5>{{ trans('buyback::global.contract_error_no_items') }}</h5>
    @else
        <div id="accordion">
            @foreach($contracts as $contract)
                @php
                    $contractFinalPrice = number_format(PriceCalculationHelper::calculateFinalPrice(
                        json_decode($contract->contractData, true)["parsed"]),2,$decimal_separator, $thousand_separator);
                    $contractFinalVolume = number_format(PriceCalculationHelper::calculateFinalVolume(
                                                json_decode($contract->contractData, true)["parsed"]),2,$decimal_separator, $thousand_separator);
                @endphp
                <div class="card">
                    <div
                            class="card-header border-secondary" data-toggle="collapse"
                            data-target="#collapse_{{ $contract->contractId }}"
                            aria-expanded="true" aria-controls="collapse_{{ $contract->contractId }}"
                            id="heading_{{ $contract->contractId }}"
                    >
                        <div class="mb-0">
                            @include('buyback::partials.contract-heading', ['contract' => $contract, 'withDeleteButton' => true, 'withFinishButton' => true])
                        </div>
                    </div>
                    <div id="collapse_{{ $contract->contractId }}" class="collapse"
                         aria-labelledby="heading_{{ $contract->contractId }}" data-parent="#accordion">
                        <div class="card-body">
                            @include('buyback::partials.contract-details', ['contract' => $contract])
                        </div>
                    </div>
                </div>
        @endforeach
    @endif
@stop