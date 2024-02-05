@extends('web::layouts.grids.12')

@section('title', trans('buyback::global.admin_group_title'))
@section('page_header', trans('buyback::global.admin_group_title'))

@push('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('web/css/buyback.css') }}"/>
@endpush

@php
    use Seat\Services\Settings\Profile;
        $thousand_separator = Profile::get('thousand_seperator');
        $decimal_separator = Profile::get('decimal_seperator');
@endphp

@section('full')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('buyback::global.admin_group_title') }}</h3>
                </div>
                <div class="card-body">
                    <table id="items" class="table .table-sm">
                        <thead>
                        <tr>
                            <th>{{ trans('buyback::global.admin_group_table_item_name') }}</th>
                            <th class="text-center"><i class="fas fa-arrow-down"></i>/<i
                                        class="fas fa-arrow-up">{{ trans('buyback::global.admin_group_table_jita') }}
                            </th>
                            <th class="text-center">{{ trans('buyback::global.admin_group_table_percentage') }}</th>
                            <th class="text-center">{{ trans('buyback::global.admin_group_table_price') }}</th>
                            <th>{{ trans('buyback::global.admin_group_table_market_name') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($marketConfigs) > 0)
                            @foreach($marketConfigs as $config)
                                <tr>
                                    @csrf
                                    <td class="align-middle">
                                        @include('web::partials.type', ['type_id' => $config->invType->typeID, 'type_name' => ucwords($config->invType->typeName)])
                                    </td>
                                    <td class="text-center align-middle">{!! $config->marketOperationType == 0 ? '<i class="fas fa-arrow-down"></i>' : '<i class="fas fa-arrow-up"></i>' !!}</td>
                                    <td class="text-center align-middle">{{ ($config->price <= 0) ? $config->percentage . " %" : "-" }}</td>
                                    <td class="text-center align-middle">{{ ($config->price > 0) ? number_format($config->price,0,$decimal_separator, $thousand_separator) . " ISK" : "-"}}</td>
                                    <td class="align-middle">{{ $config->invType->group->groupName }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <br/>
                </div>
            </div>
        </div>
    </div>
@stop
@push('javascript')
    <script>
        $('.groupsearch').select2({
            placeholder: '{{ trans('buyback::global.admin_select_placeholder') }}',
            ajax: {
                url: '/autocomplete',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $(document).ready(function () {
            $('#items').DataTable({
                order: [0],
                columnDefs: [
                    {"width": 160, "targets": 4}
                ],
                fixedColumn: true
            });
        });
    </script>
@endpush