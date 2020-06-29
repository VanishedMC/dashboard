<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function view(Request $request) {
      $user = auth()->user();
      $user->load('permissions');

      return view('dashboard', [
        'user' => $user
      ]);
    }
}
