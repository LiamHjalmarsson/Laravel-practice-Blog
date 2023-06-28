<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// stay orgoniset mocve funtions to controller

// terminal php artisan make:controller name
// php artisan migrate:fresh
// Route laravel class 
// :: static function within class 
// get method 
// two arguments direction, functoin when visiting route 

// Admin routes
Route::get("/admins-only", [AdminController::class, "adminsOnly"])->middleware("can:visitAdminPages");

// User related routes
Route::get('/', [UserController::class, "showCorrectHomePage"])->name("login");
Route::post('/register', [UserController::class, "register"])->middleware("guest");
Route::post('/login', [UserController::class, "login"])->middleware("guest");
Route::post('/logout', [UserController::class, "logout"])->middleware("mustBeLoggedIn");
Route::get("/manage-avatar",[UserController::class, "showAvatarForm"])->middleware("mustBeLoggedIn");
Route::post("/manage-avatar",[UserController::class, "storeAvatarForm"])->middleware("mustBeLoggedIn");

// Follow reladet routes 
Route::post("/create-follow/{user:username}", [FollowController::class, "createFollow"])->middleware("mustBeLoggedIn");
Route::post("/remove-follow/{user:username}", [FollowController::class, "removeFollow"])->middleware("mustBeLoggedIn");

// Blog post related routes
Route::get("/create-post", [PostController::class, "showCreateForm"])->middleware("mustBeLoggedIn"); // Intercet / end or modify the http request diffrent layers tat a reuqest passes auth/guest amd many more 
Route::post("/create-post", [PostController::class, "storeNewPost"])->middleware("mustBeLoggedIn");
Route::get("/post/{post}", [PostController::class, "viewSinglePost"]);
Route::delete("/post/{post}", [PostController::class, "delete"])->middleware("can:delete,post");
Route::get("/post/{post}/edit", [PostController::class, "showEditForm"])->middleware("can:update,post");
Route::put("/post/{post}/edit", [PostController::class, "updateEditForm"])->middleware("can:update,post");
Route::get("/search/{term}", [PostController::class, "search"]);

// Profile releated routes 
Route::get("/profile/{profile:username}", [ProfileController::class, "profile"]);
Route::get("/profile/{profile:username}/followers",[ProfileController::class, "profileFollowers"]);
Route::get("/profile/{profile:username}/following",[ProfileController::class, "profileFollowing"]);

Route::middleware("cache.headers:public;max_age=20;etag")->group(function () {
    Route::get("/profile/{profile:username}/raw", [ProfileController::class, "profileRaw"]); //->middleware("cache.headers:public;max_age=20;etag");
    Route::get("/profile/{profile:username}/followers/raw",[ProfileController::class, "profileFollowersRaw"]);
    Route::get("/profile/{profile:username}/following/raw",[ProfileController::class, "profileFollowingRaw"]);
});


// Chat route
Route::post('/send-chat-message', function (Request $request) {
    $formFields = $request->validate([
        'textvalue' => 'required'
    ]);

    if (!trim(strip_tags($formFields['textvalue']))) {
        return response()->noContent();
    }

    broadcast(new ChatMessage(
        [
            'username' =>auth()->user()->username, 
            'textvalue' => strip_tags($request->textvalue), 
            'avatar' => auth()->user()->avatar
        ]
    ))->toOthers();
    return response()->noContent();

})->middleware('mustBeLoggedIn');

// php artisan make:middleware name
// php artisan make:controller  name 


// Policy reused in more places 
// write one check and then reference the check in methods or blade templaets routes etc 

// php artisan make:policy PostPolicy --model=Post // the extra flag add extra boilerplate so that our policy knows from the start that its tied to the post 


// php artisan make:migration name // create new file in migrations in database 

// php artisan make:model name

// php artisan make:migration add_isadmin_to_users_table --table=users // extra bolier plate code so that is already pointing or referencing the useres table 
// php artisan migrate


// Gate Admin only page simuallry to policy 
// policy tied to a modle och recourse 
// gate is 

// php artisan storage:link


// php artisan db:seed

// php artisan migrate:fresh --seed carfuel erase all tables and data '

//  php artisan event:generate

// php artisan queue:work