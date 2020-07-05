<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

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
