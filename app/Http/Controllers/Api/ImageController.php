<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\ImageUploaded;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller {
    
  // Upload new image
  public function upload(Request $request) {
    $request->validate([
      'image' => 'required|file|mimes:jpeg,jpg,png,gif',
      'title' => 'string|min:1|max:200',
      'public' => 'boolean'
    ]);

    $title = $request->get('title');
    $public = $request->get('public', true);

    $extension = $request->file('image')->getClientOriginalExtension();
 
    // Generate a random UID that doesn't exist
    $uid = Str::random(8); 
    while(Image::where('uuid', $uid)->first() != null) {
      $uid = Str::random(8);
    }
    
    $path = $uid . '.' . $extension;

    // Store the file in the public or private folder
    $request->file('image')->storeAs(
      $public ? 'public/images' : 'images', $path
    );

    // Save the image model
    $img = new Image();
    $img->user_id = Auth::user()->id;
    $img->uuid = $uid;
    $img->title = $title;
    $img->file = $path;
    $img->public = $public;
    $img->save();

    // Fire the event
    event(new ImageUploaded($img, Auth::user()));

    $url = env('IMAGE_URL') . 'i' . $uid;

    return response($url);
  }

  // Update an existing image
  public function update(Request $request, $id) {
    $image = Image::where('id', $id)->first();

    if($image == null) {
      return response('Not found', 404);
    }

    if($image->user_id != Auth::user()->id) {
      return response('Unauthorized', 401);
    }

    $errors = $request->validate([
      'title'  => 'nullable|string|max:200',
      'uuid'   => 'required|string|min:2|max:20|regex:/^\S*$/u',
      'public' => 'required|boolean'
    ]);

    $uid = $request->get('uuid');
    $title = $request->get('title');
    $public = $request->get('public');

    if(Str::length($title) == 0) $title = null;

    $oldUid = $image->uuid;
    if($oldUid != $uid) {
      if(Auth::user()->hasPermission('IMAGE_CUSTOM_UID')) {
        if(Image::where('uuid', $uid)->first() != null) {
          return response('uuid is taken', 422);
        }
      } else {
        return response('Not authorized', 401);
      }
    }

    // Current public value for the image
    $oldPublic = $image->public;

    // If the image was public and now is private, move file
    if($oldPublic && !$public) {
      Storage::move('public/images/' . $image->file, 'images/' . $image->file);
    }

    // If the image was private and now is public, move file
    if(!$oldPublic && $public) {
      Storage::move('images/' . $image->file, 'public/images/' . $image->file);
    }

    $image->uuid = $uid;
    $image->title = $title;
    $image->public = $public;
    $image->save();

    return json_encode([
      'success' => 'Image updated'
    ]);
  }

  public function delete(Request $request, $id) {
    $image = Image::where('id', $id)->first();

    if($image == null) {
      return response('Not found', 404);
    }

    if($image->user_id != Auth::user()->id) {
      return response('Unauthorized', 401);
    }

    if($image->public) {
      Storage::delete('public/images/' . $image->file);
    } else {
      Storage::delete('images/' . $image->file);
    }

    $image->delete();
    return response('Image deleted', 200);
  }

  // Get image by ID
  public function get(Request $request, $id) {
    $image = Image::where('id', $id)->first();

    if($image == null) {
      return response('Not found', 404);
    }

    if($image->user_id == Auth::user()->id) {
      return $image;
    } else {
      return response('Not found', 404);
    }
  }

  // Get image by UID
  public function getByUid(Request $request, $image) {    
    $img = Image::where('uuid', $image)->first();

    if($img == null) {
      return response('Not found', 404);
    }

    if($img->public) {
      return redirect(url('storage/images/' . $img->file));
    }

    return Storage::download('images/' . $img->file);
  }

  // Get all images for authenticated user
  public function list(Request $request) {
    $images = Image::where('user_id', Auth::user()->id)->get();
    return $images;
  }

  // Get the view for an image
  public function getImageView(Request $request, $image) {
    $img = Image::where('uuid', $image)->first();
    if($img == null) {
      return response('Not found', 404);
    }

    if($img->public) {
      return view('image', [
        'image_url' => url('storage/images/' . $img->file),
        'url' => url('i'.$image),
        'title' => $img->title
      ]);
    } else {
      return view('image', [
        'image_url' => url('image' . $image),
        'url' => url('i'.$image),
        'title' => $img->title
      ]);
    }
  }
}
