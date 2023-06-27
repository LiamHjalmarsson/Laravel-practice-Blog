<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow (User $user) {

        if ($user->id === auth()->user()->id) {
            return back()->with("error", "You cant follow yourself");
        }

        $existFollowers = Follow::where(
            [["user_id", "=", auth()->user()->id], ["followeduser", "=", $user->id]]
        )->count();

        if ($existFollowers) {
            return back()->with("error", "You already follow this user");
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with("success", "Follow successfully");
    }

    public function removeFollow (User $user) {

        Follow::where(
            [["user_id", "=", auth()->user()->id], ["followeduser", "=", $user->id]]
        )->delete();
    
        return back()->with("success", "Unfollowed user!");
    }
}
