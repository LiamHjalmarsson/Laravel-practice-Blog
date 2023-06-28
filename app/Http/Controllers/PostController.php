<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function search ($term) {

        $posts = Post::search($term)->get();
        $posts->load("user:id,username,avatar");
        return $posts; 
    }

    public function showCreateForm () {
        return view("create-post");
    }

    public function storeNewPost (Request $request) {
        $incomingFields = $request->validate([
            "title" => "required",
            "body" => "required",
        ]);

        $incomingFields["title"] = strip_tags($incomingFields["title"]);
        $incomingFields["body"] = strip_tags($incomingFields["body"]);
        $incomingFields["user_id"] = auth()->id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(
            [
                "sendTo" => auth()->user()->email, 
                "name" => auth()->user()->username, 
                "title" => $newPost->title 
            ]
        ));

        return redirect("/post/{$newPost->id}")->with("success", "New post created successfully"); 
    }

    public function viewSinglePost (Post $post) { // Typ hinting what ever is incomnig value we want to interpret it 
        // Larvel can look up approriate post in the database just based on the incoming id 

        // Mark down 
        $post["body"] = strip_tags(Str::markdown($post->body), "<p><ul><ol><bold>");
        
        return view("single-post", ["post" => $post]);
    }

    public function delete (Post $post) {

        // if (auth()->user()->cannot('delete', $post)) {
            // return 'You cannot do that';
        // }

        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successfully deleted.');
    }

    public function showEditForm (Post $post) {
        return view("edit-post", ["post" => $post]);
    }

    public function updateEditForm (Post $post, Request $request) {
        $incomingFields = $request->validate(
            [
                "title" => "required",
                "body" => "required"
            ]
        );

        $incomingFields["title"] = strip_tags($incomingFields["title"]);
        $incomingFields["body"] = strip_tags($incomingFields["body"]);

        $post->update($incomingFields);

        return back()->with("success", "Post successfully updated");
    }
}
