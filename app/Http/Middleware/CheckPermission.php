<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission {
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next, $permission) {
    if (Auth::user()->hasPermission($permission)) {
      return $next($request);
    } else {
      return response('Not authorized', 403);
    }
  }
}
