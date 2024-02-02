<tr>
    <td>
        <b>x{{ number_format($quantity,0,$decimal_separator, $thousand_separator) }}</b>
        @include('web::partials.type', ['type_id' => $typeId, 'type_name' => ucwords($typeName)])
        ( {!! $marketOperationType == 0 ? '-' : '+' !!}{{$percentage}}
        % )
    </td>
    <td class="isk-td">
        <span class="isk-info">+{{ trans('buyback::global.final_price', ['price' => $price]) }}</span>
    </td>
</tr>