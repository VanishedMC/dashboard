<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PreferencesController extends Controller {

  function askToInvite() {
    $user = Auth::user();
    $user->ask_invite = 0;
    $user->save();
    return response('ok', 200);
  }
}
