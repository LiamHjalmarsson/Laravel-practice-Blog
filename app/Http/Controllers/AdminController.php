<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function adminsOnly () {

        // if (Gate::allows("visitAdminPages")) {
            return view("adminsOnly");
        // } 

        // return redirect("/")->with("error", "You are not allowed to visit this page");
    }
}
