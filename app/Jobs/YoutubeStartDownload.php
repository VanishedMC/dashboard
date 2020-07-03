<?php

namespace App\Jobs;

use App\YoutubeInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\YoutubeStartedDownloading;
use App\Events\YoutubeFinishedDownloading;

class YoutubeStartDownload implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $information;

  public function __construct(YoutubeInformation $information) {
    $this->information = $information;
  }

  public function handle() {
    // Update status to downloading
    $this->information->status = 1;
    $this->information->save();
    event(new YoutubeStartedDownloading($this->information->getUser()));

    $workDirectory = storage_path() . '/app/youtube/' . $this->information->id;
    // fix something for this
    // Fetch type from YoutubeInformation, has to be added
    $type = (true) ? 'bestaudio' : 'bestvideo+bestaudio';

    if(file_exists($workDirectory)) {
      Storage::deleteDirectory('youtube/' . $this->information->id);
    }

    File::makeDirectory($workDirectory, true, true);

    $process = new Process(['youtube-dl', '--no-cache-dir', '--ignore-errors', '--no-part', '-f', $type, '-o', '%(title)s.%(ext)s', $this->information->url]);
    $process->setWorkingDirectory($workDirectory);
    $process->setTimeout(1800);
    $process->run();

    foreach(Storage::files('youtube/' . $this->information->id) as $file) {
      // Get the base, and new name
      $fileBaseName = pathinfo(storage_path() . $file, PATHINFO_BASENAME);

      // mp3 is user chose audio, mp4 if user chose video
      $fileNewName =  pathinfo(storage_path() . $file, PATHINFO_FILENAME) . (true ? '.mp3' : '.mp4' );

      // Use Ffmpeg to convert the video to its new type
      $process = new Process(['ffmpeg', '-i', $fileBaseName, $fileNewName, '-y']);
      $process->setWorkingDirectory($workDirectory);
      $process->setTimeout(1800);
      $process->run();

      // Delete the original file
      if($fileBaseName != $fileNewName) {
        Storage::delete($file);
      }
    }

    // After all files are ready, zip if playlist, then prepare download
    if(strpos($this->information->url, 'playlist') !== false) {
      // playlist
      $zipFile = $workDirectory . '/playlist.zip';
      $zip = new \ZipArchive();
      $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

      foreach(Storage::files('youtube/' . $this->information->id) as $file) {
        $fileName = pathinfo(storage_path() . $file, PATHINFO_BASENAME);
        $zip->addFile($workDirectory . "/$fileName", $fileName);
      }

      $zip->close();

      foreach(Storage::files('youtube/' . $this->information->id) as $file) {
        // Delete all files except the zip
        $fileName = pathinfo(storage_path() . $file, PATHINFO_BASENAME);
        if($fileName != 'playlist.zip') {
          Storage::delete($file);
        }
      }

      $this->information->file = $zipFile;
    } else {
      // single file
      $this->information->file = $workDirectory . '/' . pathinfo(Storage::files('youtube/' . $this->information->id)[0], PATHINFO_BASENAME);
    }

    $this->information->status = 2;
    $this->information->save();
    event(new YoutubeFinishedDownloading($this->information->getUser()));
  }
}
