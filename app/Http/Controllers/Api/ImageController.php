<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller {
    
  public function upload(Request $request) {
    Log::debug($request);

    $request->validate([
      'image' => 'required|file|mimes:jpeg,jpg,png,gif',
      'title' => 'string|min:1|max:200',
      'public' => 'boolean'
    ]);

    $title = $request->get('title');
    $public = $request->get('public', true);

    $extension = $request->file('image')->getClientOriginalExtension();
 
    $uid = Str::random(8); 
    while(Image::where('uuid', $uid)->first() != null) {
      $uid = Str::random(8);
    }
    
    $path = $uid . '.' . $extension;
    $request->file('image')->storeAs(
      'images', $path
    );

    $img = new Image();
    $img->user_id = Auth::user()->id;
    $img->uuid = $uid;
    $img->title = $title;
    $img->file = $path;
    $img->public = $public;
    $img->save();

    return response(url('i' . $uid));
  }

  public function getImage(Request $request, $image) {
    $img = Image::where('uuid', $image)->first();
    if($img == null) {
      return response('Not found', 404);
    }

    return Storage::download('images/' . $img->file);
  }

  public function getImageView(Request $request, $image) {
    $img = Image::where('uuid', $image)->first();
    if($img == null) {
      return response('Not found', 404);
    }

    return view('image', [
      'image_url' => url('image' . $image),
      'url' => url('i'.$image),
      'title' => $img->title
    ]);
  }
}
