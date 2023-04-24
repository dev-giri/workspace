<?php

namespace Workspace\Http\Controllers;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index($username){
    	$user = config('workspace.user_model')::where('username', '=', $username)->firstOrFail();
    	return view('theme::profile', compact('user'));
    }
}
