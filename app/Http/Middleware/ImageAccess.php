<?php

namespace App\Http\Middleware;

use App\Image;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ImageAccess {

  public function handle($request, Closure $next) {
    $path = $request->path();

    if (Str::startsWith($path, 'image')) {
      $uuid = substr($request->path(), 5);
    } else {
      $uuid = substr($request->path(), 1);
    }

    $image = Image::where('uuid', $uuid)->first();
    if ($image == null) {
      return response('Not found', 404);
    }

    if ($image->public == 0) {
      if (Auth::user() && $image->user_id == Auth::user()->id) {
        return $next($request);
      } else {
        return response('Not found', 404);
      }
    }

    return $next($request);
  }
}
