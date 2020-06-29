<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller {
    
  public function listUsers(Request $request) {
    $users = User::get();
    return $users;
  }

  public function getUserDetails(Request $request, $id) {
    $user = User::where('id', $id)->first()->load('permissions');
    return $user;
  }

}
