<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\View;


class ProfileController extends Controller
{

    private function getSharedData($profile) {
        $currentlyFollow = 0;

        if (auth()->check()) {
            $currentlyFollow = Follow::where(
                [["user_id", "=", auth()->user()->id], ["followeduser", "=", $profile->id]]
            )->count();
        }

        View::share("sharedData", [
            "avatar" => $profile->avatar,
            "username" => $profile->username, 
            "postCount" => $profile->posts()->count(), 
            "followerCount" => $profile->followers()->count(),
            "followingCount" => $profile->followingTheseUseres()->count(),
            "currentlyFollow" => $currentlyFollow,
        ]);
    }

    public function profile (User $profile) {
        $this->getSharedData($profile);

        return view("profile-posts", [
            "posts" => $profile->posts()->latest()->get(),
        ]);
    }

    public function profileRaw (User $profile) {
        return response()->json(
            [
                "theHTML" => view("profile-posts-only", ["posts" => $profile->posts()->latest()->get()])->render(),
                "docTitle" => $profile->username . " Profile"
            ]
        );
    }

    public function profileFollowers (User $profile) {

        $this->getSharedData($profile);

        return view("profile-followers", [
            "followers" => $profile->followers()->latest()->get(),
        ]);
    }

    public function profileFollowersRaw (User $profile) {
        return response()->json(
            [
                "theHTML" => view("profile-followers-only", ["followers" => $profile->followers()->latest()->get()])->render(),
                "docTitle" => $profile->username . " Followers"
            ]
        );
    }

    public function profileFollowing (User $profile) {

        $this->getSharedData($profile);

        return view("profile-following", [
            "following" => $profile->followingTheseUseres()->latest()->get(),
        ]);
    }

    public function profileFollowingRaw (User $profile) {
        return response()->json(
            [
                "theHTML" => view("profile-following-only", ["following" => $profile->followingTheseUseres()->latest()->get()])->render(),
                "docTitle" => $profile->username . " Follows"
            ]
        );
    }
}
