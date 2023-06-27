<x-profile :sharedData="$sharedData" doctitle="{{ $sharedData['username'] }}'s Profile">
{{-- <x-profile :avatar="$avatar" :username="$username" :currentlyFollow="$currentlyFollow" :postCount="$postCount"> --}}

    <div class="list-group">

        @foreach ( $posts as $post )
            <x-post :post="$post" hideAuthor/>
        @endforeach
    </div>

</x-profile>