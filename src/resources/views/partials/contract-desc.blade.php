<b>{{ $contract->contractId }}</b>
| {{ date("d.m.Y", $contract->created_at->timestamp) }}
( {{ trans_choice('buyback::global.items', count(json_decode($contract->contractData, true)["parsed"]), ['count' => count(json_decode($contract->contractData, true)["parsed"])]) }} )
| <b>{{ $contract->issuer->name }}</b>
| <em>{{ trans('buyback::global.final_volume', ['volume' => $contractFinalVolume]) }}</em>
| <b><span class="isk-info">+{{ trans('buyback::global.final_price', ['price' => $contractFinalPrice]) }}</span></b>
