<div class="row d-flex align-items-center">
    <div class="col-md-10 align-left">
        <div class="card-title">
            <i class="nav-icon fas fa-eye"></i>
            @if($contract->contractStatus === 1)
                <del>
                    @include('buyback::partials.contract-desc')
                </del>
            @else
                @include('buyback::partials.contract-desc')
            @endif
            @if($contract->contractStatus === 1)
                - <b> Finished: {{ date("d.m.Y", $contract->updated_at->timestamp) }}</b>
            @endif
        </div>
    </div>
    @if($withDeleteButton || $withFinishButton)
        <div class="ml-auto mr-2 align-right text-center align-centered">
            <div class="row">
                @if($withFinishButton)
                    <form action="{{ route('buyback.contracts.succeed', ['contractId' => $contract->contractId]) }}"
                          method="get" id="contract-success" name="contract-success">
                        <button class="btn btn-success">Finish</button>
                    </form>
                @endif
                @if($withDeleteButton)
                    <form class="ml-2"
                          action="{{ route('buyback.contracts.delete', ['contractId' => $contract->contractId]) }}"
                          method="get" id="contract-remove" name="contract-remove">
                        <button class="btn btn-danger">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>