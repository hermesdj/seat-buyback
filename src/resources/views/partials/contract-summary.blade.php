<table class="table">
    <tbody>
    <tr>
        <td>
            {{ trans('buyback::global.step_three_contract_type') }}
        </td>
        <td>
            <b>{{trans('buyback::global.item_exchange')}}</b>
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('buyback::global.step_three_contract_to') }}*
        </td>
        <td>
            <b
                    onClick="SelfCopy(this)"
                    data-container="body"
                    data-toggle="popover"
                    data-placement="top"
                    data-content="Copied!"
            >
                {{ $contractTo }}
            </b>
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('buyback::global.step_three_contract_receive') }}*
        </td>
        <td>
            <b
                    onClick="SelfCopy(this)"
                    data-container="body"
                    data-toggle="popover"
                    data-placement="top" data-content="Copied!"
            >
                <span class="isk-info">{{ $finalPrice }}</span>
            </b>
            <b>{{ trans('buyback::global.currency') }}</b>
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('buyback::global.step_three_contract_expiration') }}
        </td>
        <td>
            <b>{{ $contractExpiration }}</b>
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('buyback::global.step_three_contract_description') }}*
        </td>
        <td>
            <b
                    onClick="SelfCopy(this)"
                    data-container="body"
                    data-toggle="popover"
                    data-placement="top"
                    data-content="Copied!"
            >
                {{ $contractId }}
            </b>
        </td>
    </tr>
    </tbody>
</table>

@push('javascript')
    <script>
        function SelfCopy(object) {
            navigator.clipboard.writeText(object.innerText);

            $(object).popover().click(function () {
                setTimeout(function () {
                    $(object).popover('hide');
                }, 1000);
            });
        }
    </script>
@endpush