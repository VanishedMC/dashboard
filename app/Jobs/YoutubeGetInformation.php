<?php

namespace App\Jobs;

use App\Events\YoutubeInformationFinishedLoading;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class YoutubeGetInformation implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $url;
  private $user;
  private $videos = array();
  private $i = 0;

  public function __construct(User $user, $url) {
    $this->user = $user;
    $this->url = $url;
  }

  public function handle() {
    // Get information about the video(s)
    $process = new Process(['youtube-dl', '--no-cache-dir', '--flat-playlist', '--ignore-errors', '--dump-json', $this->url]);
    $process->setTimeout(1800);
    $process->run(function ($type, $buffer) {
      if ($type !== Process::ERR) {
        if (strpos($this->url, 'playlist') !== false) {
          // playlist
          $rawData = explode('}', $buffer);

          foreach ($rawData as $video) {
            $video = trim($video . "}");
            if (strlen($video) == 1 || $video == '}' || $video == null || empty($video)) {
              continue;
            }

            $video = json_decode($video);

            if ($video->title == '[Private video]' || $video->title == '[Deleted video]') {
              continue;
            }

            $videoData = new \stdClass();
            $videoData->title = $video->title;
            $videoData->id = $this->i;

            $this->i++;

            // Add to videos array
            array_push($this->videos, $videoData);
          }
        } else {
          // single video
          $video = json_decode($buffer);

          $videoData = new \stdClass();
          $videoData->title = $video->title;
          $videoData->id = $this->i;

          $this->i++;

          // Add to videos array
          array_push($this->videos, $videoData);
        }
      } else {
        Log::debug($buffer);
      }
    });

    $information = $this->user->getYoutubeInformation();
    $information->data = json_encode($this->videos);
    $information->save();

    event(new YoutubeInformationFinishedLoading($this->user));
  }
}
