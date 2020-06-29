<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ImageController extends Controller {
    
  public function upload(Request $request) {
    $request->validate([
      'image' => 'required|file'
    ]);

    $extension = $request->file('image')->getClientOriginalExtension();
 
    $uid = Str::random(8); 
    while(Image::where('uuid', $uid)->first() != null) {
      $uid = Str::random(8);
    }
    
    $path = 'storage/images' . $uid . '.' . $extension;
    $request->file('image')->storeAs(
      'public/images', $uid . '.' . $extension
    );

    $img = new Image();
    $img->user_id = Auth::user()->id;
    $img->uuid = $uid;
    $img->file = $path;
    $img->public = true;
    $img->save();

    return response($uid);
  }

  public function getImage(Request $request, $image) {
    $img = Image::where('uuid', $image)->first();
    if($img == null) {
      return response('Not found', 404);
    } else {
      return view('image', [
        'image_file' => $img->file,
        'url' => url('i'.$image)
      ]);
    }
  }

}
