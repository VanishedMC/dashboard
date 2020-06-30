<?php

namespace App\Http\Middleware;

use Closure;
use App\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ImageAccess {
  
    public function handle($request, Closure $next) {
        $path = $request->path();

        if(Str::startsWith($path, 'image')) {
          $uuid = substr($request->path(), 5);
        } else {
          $uuid = substr($request->path(), 1);
        }

        $image = Image::where('uuid', $uuid)->first();
        if($image == null) {
          return response('Not found', 404);
        }

        if($image->public == 0) {
          if(Auth::user() && $image->user_id == Auth::user()->id) {
            return $next($request);
          } else {
            return response('Not found', 404);
          }
        }

        return $next($request);
    }
}
