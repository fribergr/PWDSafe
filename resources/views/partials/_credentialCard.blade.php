<div class="card my-2">
    <div class="card-body">
        <h5 class="card-title">{{ $credential->site }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ $credential->username }}</h6>
        <p class="card-text">{{ $credential->notes }}</p>
    </div>
    <div class="card-footer">
        <div class="clearfix">
            <div class="float-left my-1">
                @if ($showGroupName)
                    @if ($credential->group->id === auth()->user()->primarygroup)
                        Private
                    @else
                        {{ $credential->group->name }}
                    @endif
                @endif
            </div>
            <div class="btn-group float-right">
                <button class="btn btn-outline-primary showPass" data-id="{{ $credential->id }}">
                    <i class="far fa-eye" title="Show"></i>
                </button>
                <button class="btn btn-outline-secondary copypwd" data-id="{{ $credential->id }}">
                    <i class="far fa-copy" title="Copy to clipboard"></i>
                </button>
                <button class="btn btn-outline-danger popconfirm credDelete" data-id="{{ $credential->id }}">
                    <i class="far fa-trash-alt" title="Delete"></i>
                </button>
            </div>
        </div>
    </div>
</div>
