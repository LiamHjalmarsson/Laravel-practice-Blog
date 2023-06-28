<x-profile :sharedData="$sharedData" doctitle="{{ $sharedData['username'] }}'s Profile">
{{-- <x-profile :avatar="$avatar" :username="$username" :currentlyFollow="$currentlyFollow" :postCount="$postCount"> --}}

    @include("profile-posts-only")

</x-profile>