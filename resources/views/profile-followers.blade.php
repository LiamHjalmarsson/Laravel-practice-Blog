<x-profile :sharedData="$sharedData" doctitle="{{ $sharedData['username'] }} Followers">
    @include("profile-followers-only")
</x-profile>