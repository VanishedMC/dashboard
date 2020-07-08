<?php

namespace App\Http\Controllers;

use App\Repositories\DiscordRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller {
  private $repository;

  public function __construct(DiscordRepository $repository) {
    $this->repository = $repository;
  }

  public function view(Request $request) {
    $user = auth()->user();
    $user->in_guild = $this->repository->isUserInGuild($user) == true ? 1 : 0;
    $user->save();
    $user->load('permissions');

    return view('dashboard', [
      'user' => $user,
    ]);
  }
}
