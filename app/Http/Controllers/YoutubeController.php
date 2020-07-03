<?php

namespace App\Http\Controllers;

use App\YoutubeInformation;
use Illuminate\Http\Request;
use App\Jobs\YoutubeCleanup;
use App\Jobs\YoutubeStartDownload;
use App\Jobs\YoutubeGetInformation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class YoutubeController extends Controller {
  
  public function getVideoInformation(Request $request) {
    $information = Auth::user()->getYoutubeInformation();

    //return $information;

    if($information == null) {
      return response('No information available', 201);
    } else {
      return $information;
    } 
  }

  public function postVideoInformation(Request $request) {
    $request->validate([
      'url' => [
        'required', 'string', 'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/'
      ],
    ]);

    $information = Auth::user()->getYoutubeInformation();
    if($information != null) {
      return response("information already exists", 500);
    }

    $url = $request->input('url');

    $information = new YoutubeInformation();
    $information->user_id = Auth::user()->id;
    $information->url = $url;
    // status 0 = download has not started
    // status 1 = downloading
    // status 2 = download finished, user can get file
    $information->status = 0;
    $information->save();

    YoutubeGetInformation::dispatch(Auth::user(), $url);

    return response("ok", 200);
  }

  public function getDownload(Request $request) {
    // Download final file to user
    $information = Auth::user()->getYoutubeInformation();
    if($information == null) {
      return response('No download found', 404);
    }

    $file = $information->file();
    YoutubeCleanup::dispatch($information->id)->delay(now()->addMinutes(30));
    $information->delete();

    if($information->status != 2) {
      return response('Your download is not ready yet', 404);
    }

    return response()->download($file);
  }

  public function postDownload() {
    $user = Auth::user();
    $information = $user->getYoutubeInformation();
    
    if($information->data != null && $information->status == 0) {
      YoutubeStartDownload::dispatch($information);
    }

    return response('Ok', 200);
  }

  public function reset(Request $request) {
    $user = Auth::user();
    $information = $user->getYoutubeInformation();

    if ($information != null) {
      YoutubeCleanup::dispatch($information->id)->delay(now()->addMinutes(30));
      $information->delete();
      return response('ok', 200);
    } else {
      return response('Nothing to reset', 404);
    }
  }
}
