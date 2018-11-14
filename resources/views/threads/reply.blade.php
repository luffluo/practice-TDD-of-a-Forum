<div class="row">
    <div class="col-md-8 col-md-offset-2">
        @foreach ($thread->replies as $reply)
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $reply->owner->name }} 回复于
                    {{ $reply->created_at->diffForHumans() }}
                </div>

                <div class="panel-body">
                    {{ $reply->body }}
                </div>
            </div>
        @endforeach
    </div>
</div>