<x-profile :sharedData="$sharedData" doctitle="{{ $sharedData['username'] }} Followings">
    @include("profile-following-only")
</x-profile>