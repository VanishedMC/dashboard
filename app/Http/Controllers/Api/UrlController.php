<?php

namespace App\Http\Controllers\Api;

use App\Events\ShortUrlCreated;
use App\Http\Controllers\Controller;
use App\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UrlController extends Controller {

  public function redirect(Request $request, $uuid) {
    $url = ShortUrl::where('uuid', $uuid)->first();
    if ($url == null) {
      return response('Url not found', 404);
    }
    return view('url')->with('url', $url->url);
  }

  public function create(Request $request) {
    $request->validate([
      // Thanks stackoverflow for this regex
      'url' => ['string', 'required', 'regex:/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/'],
    ]);

    // Generate a random UID that doesn't exist
    $uuid = Str::random(8);
    while (ShortUrl::where('uuid', $uuid)->first() != null) {
      $uuid = Str::random(8);
    }

    $url = new ShortUrl;
    $url->user_id = Auth::user()->id;
    $url->uuid = $uuid;
    $url->url = $request->input('url');
    $url->save();

    $result = env('SITE_URL') . 'u' . $uuid;

    event(new ShortUrlCreated(Auth::user(), $url));

    return response($result);
  }

  public function list(Request $request) {
    return Auth::user()->getShortUrls();
  }

  public function delete(Request $request, $id) {
    $url = ShortUrl::where('id', $id)->first();

    if ($url == null || $url->user_id != Auth::user()->id) {
      return response('Not found', 404);
    }

    $url->delete();
    return response('ok', 200);
  }
}
