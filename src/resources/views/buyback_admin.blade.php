@extends('web::layouts.grids.6-6')

@section('title', trans('buyback::global.admin_browser_title'))
@section('page_header', trans('buyback::global.admin_page_title'))

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/buyback.css') }}"/>
@endpush

@section('left')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans('buyback::global.admin_setting_title') }}</h3>
        </div>
        <form action="{{ route('buyback.admin.update') }}" method="post" id="admin-update" name="admin-update">
            <div class="card-body">
                {{ csrf_field() }}
                <div class="box-body">
                    <legend>{{ trans('buyback::global.admin_setting_first_title') }}</legend>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_max_allowed_items">{{ trans('buyback::global.admin_setting_allowed_items_label') }}</label>
                    <div class="col-md-6">
                        <input id="admin_max_allowed_items" name="admin_max_allowed_items" type="number"
                               class="form-control input-md w-25" value="{{ $settings["admin_max_allowed_items"] }}">
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_setting_allowed_items_description') }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_price_provider">{{ trans('buyback::global.admin_setting_price_provider_label') }}</label>
                    <div class="col-md-6">
                        @include("pricescore::utils.instance_selector",["id"=>"admin_price_provider","name"=>"admin_price_provider","instance_id"=>$settings["admin_price_provider"]])
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_setting_price_provider_description') }}
                        </p>
                    </div>
                </div>
                <div class="box-body">
                    <legend>{{ trans('buyback::global.admin_setting_second_title') }}</legend>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_contract_contract_to">{{ trans('buyback::global.admin_setting_contract_to_label') }}</label>
                    <div class="col-md-6">
                        <input id="admin_contract_contract_to" name="admin_contract_contract_to" type="text"
                               class="form-control input-md" value="{{ $settings["admin_contract_contract_to"] }}">
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_setting_contract_to_description') }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_contract_expiration">{{ trans('buyback::global.admin_setting_expiration_label') }}</label>
                    <div class="col-md-6">
                        <input id="admin_contract_expiration" name="admin_contract_expiration" type="text"
                               class="form-control input-md" value="{{ $settings["admin_contract_expiration"] }}">
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_setting_expiration_description') }}
                        </p>
                    </div>
                </div>
                <div class="box-body">
                    <legend>{{ trans('buyback::global.admin_setting_third_title') }}</legend>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_allow_default_prices">
                        {{ trans('buyback::global.admin_allow_default_prices') }}
                    </label>
                    <div class="col-md-6">
                        <select id="admin_allow_default_prices" name="admin_allow_default_prices"
                                class="form-control w-100">
                            <option value="0" {{ ((int)$settings["admin_allow_default_prices"] == 0) ? "selected" : '' }}>
                                Disabled
                            </option>
                            <option value="1" {{ ((int)$settings["admin_allow_default_prices"] >= 1) ? "selected" : '' }}>
                                Enabled
                            </option>
                        </select>
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_allow_default_prices_description') }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="admin_default_prices_operation_type">
                        <i class="fas fa-arrow-down"></i>/<i
                                class="fas fa-arrow-up"></i>{{ trans('buyback::global.admin_default_prices_operation_type') }}
                    </label>
                    <div class="col-md-6">
                        <div class="form-group mt-2">
                            <div class="form-check form-check-inline">
                                <input
                                        class="form-check-input" type="radio" name="admin_default_prices_operation_type"
                                        id="admin_default_prices_operation_type" value="0"
                                        {{ ((int)$settings["admin_default_prices_operation_type"] == 0) ? "checked" : '' }}
                                >
                                <label class="form-check-label" for="admin_default_prices_operation_type_1"><i
                                            class="fas fa-arrow-down"></i>{{ trans('buyback::global.admin_default_prices_operation_type_0') }}
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                        class="form-check-input" type="radio" name="admin_default_prices_operation_type"
                                        id="admin_default_prices_operation_type"
                                        value="1"
                                        {{ ((int)$settings["admin_default_prices_operation_type"] == 1) ? "checked" : '' }}
                                >
                                <label class="form-check-label" for="admin_default_prices_operation_type_2"><i
                                            class="fas fa-arrow-up"></i>{{ trans('buyback::global.admin_default_prices_operation_type_1') }}
                                </label>
                            </div>
                        </div>
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_default_prices_operation_type_description') }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_default_prices_percentage">{{ trans('buyback::global.admin_default_prices_percentage') }}</label>
                    <div class="col-md-6">
                        <input
                                name="admin_default_prices_percentage"
                                id="admin_default_prices_percentage"
                                type="number"
                                class="form-control w-25"
                                min="0"
                                max="100"
                                value="{{(int)$settings["admin_default_prices_percentage"]}}"
                                maxlength="2"
                        />
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_default_prices_percentage_description') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="submit"></label>
                    <div class="col-md-4">
                        <button id="submit" type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i>
                            {{ trans('buyback::global.admin_setting_button') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@section('right')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans('buyback::global.admin_discord_title') }}</h3>
        </div>
        <form action="{{ route('buyback.admin.update_discord') }}" method="post" id="admin-update" name="admin-update">
            <div class="card-body">
                {{ csrf_field() }}
                <div class="box-body">
                    <legend>{{ trans('buyback::global.admin_discord_first_title') }}</legend>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_discord_status">{{ trans('buyback::global.admin_discord_webhook_status_label') }}</label>
                    <div class="col-md-6">
                        <select id="admin_discord_status" name="admin_discord_status" class="form-control w-100">
                            <option value="0" {{ ((int)$settings["admin_discord_status"] == 0) ? "selected" : '' }}>
                                Disabled
                            </option>
                            <option value="1" {{ ((int)$settings["admin_discord_status"] >= 1) ? "selected" : '' }}>
                                Enabled
                            </option>
                        </select>
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_discord_webhook_status_description') }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_discord_webhook_url">{{ trans('buyback::global.admin_discord_webhook_url_label') }}</label>
                    <div class="col-md-6">
                        <input id="admin_discord_webhook_url" name="admin_discord_webhook_url" type="password"
                               class="form-control input-md" placeholder="http:\\"
                               value="{{ $settings["admin_discord_webhook_url"] }}">
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_discord_webhook_url_description') }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_discord_webhook_bot_name">{{ trans('buyback::global.admin_setting_bot_name_label') }}</label>
                    <div class="col-md-6">
                        <input id="admin_discord_webhook_bot_name" name="admin_discord_webhook_bot_name" type="text"
                               class="form-control input-md" value="{{ $settings["admin_discord_webhook_bot_name"] }}">
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_setting_bot_name_description') }}
                        </p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"
                           for="admin_discord_webhook_url">{{ trans('buyback::global.admin_discord_webhook_color_label') }}</label>
                    <div class="col-md-6">
                        <input id="admin_discord_webhook_color" name="admin_discord_webhook_color" type="color"
                               class="form-control input-md" placeholder="#ffffff"
                               value="{{ $settings["admin_discord_webhook_color"] }}">
                        <p class="form-text text-muted mb-0">
                            {{ trans('buyback::global.admin_discord_webhook_color_description') }}
                        </p>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="submit"></label>
                        <div class="col-md-4">
                            <button id="submit" type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i>
                                {{ trans('buyback::global.admin_discord_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop