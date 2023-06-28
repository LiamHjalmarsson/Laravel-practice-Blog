<div class="list-group">
    @foreach ( $following as $follow )
        
        <a href="/profile/{{ $follow->userbeeingFollowed->username }}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{ $follow->userbeeingFollowed->avatar }}" />
            {{ $follow->userbeeingFollowed->username }}
        </a>

    @endforeach
</div>