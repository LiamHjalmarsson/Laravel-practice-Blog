<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
// A migration has to do with the database.

// A model also has to do with the database.

// So a migration is how we create tables and add columns to tables and remove columns from tables.

// A model, on the other hand, is how we actually perform CRUD operations on the data that lives in those

// So a CRUD operation crud stands for create, read, update and delete.

// A model also is how we define our relationships.

use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function loginApi (Request $request) {
        $inComingFileds = $request->validate(
            [
                "username" => "required",
                "password" => "required",
            ]
        );

        if (auth()->attempt($inComingFileds)) {
            $user = User::where("username", $inComingFileds["username"])->first();
            $token = $user->createToken("ourapptoken")->plainTextToken;
            return $token;
        }

        return "Sorry";
    }

    public function showCorrectHomePage () {
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
        } else {
            // if (Cache::has('postCount')) {
            //     $postCount = Cache::get('postCount');
            // } else {
            //     sleep(5);
            //     $postCount = Post::count();
            //     Cache::put("postCount", $postCount, 10);
            // }

            $postCount = Cache::remember("postCount", 10, function () {
                return Post::count();
            });

            return view("homepage", [
                "postCount" => $postCount,
            ]);
        }
    }

    public function register (Request $request) {
        $incomingFields = $request->validate([
            "username" => ["required", "min:3", "max:20", Rule::unique("users", "username")],
            "email" => ["required", "email", Rule::unique("users", "email")],
            "password" => ["required", "min:3", "confirmed"],
        ]);

        $incomingFields["password"] = bcrypt($incomingFields["password"]);

        $user = User::create($incomingFields);
        
        auth()->login($user);
        return redirect("/")->with("success", "User was created successfully");
    }

    public function login (Request $request) {
        $incomingFileds = $request->validate([
            "loginusername" => "required",
            "loginpassword" => "required"
        ]);

        if (auth()->attempt(["username" => $incomingFileds["loginusername"], "password" => $incomingFileds["loginpassword"]])) {
            $request->session()->regenerate(); // cookie the value in cookie proves to laravel server that we are the user that we just logged in with // its presistent // send all cookies 
            // if correct pw and username give user a session value and tell the browser to store it and then the browser is going to send us back that cookie on every incomning request 
            // the server can trust that the visitor is who they are 

            event(new OurExampleEvent(["username" => auth()->user()->username, "action" => "login"]));
            return redirect("/")->with("success", "You have successfully logged in");
        } else {    
            return redirect("/")->with("error", "Invalid username or password");
        }
    }

    public function logout () {
        event(new OurExampleEvent(["username" => auth()->user()->username, "action" => "logout"]));
        auth()->logout();
        return redirect("/")->with("success", "You are now logged out");
    }   

    public function showAvatarForm () {
        return view("avatarForm");
    }

    public function storeAvatarForm (Request $request) {
        $request->validate(
            [
                "avatar" => "required|image|max:3000"
            ]
        );

        $user = auth()->user();

        $filename = $user->id . "-" . uniqid() . ".jpg";

        $img = Image::make($request->file("avatar"))->fit(120)->encode("jpg");
        Storage::put("public/avatars/{$filename}", $img);

        $oldImg = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldImg !== "/fallback-avatar.jph") {
            Storage::delete(str_replace("/storage/", "public/", $oldImg));
        }

        return back()->with("success", "New avatar appleyd");
        // $request->file("avatar")->store("public/avatars");
    }

}
