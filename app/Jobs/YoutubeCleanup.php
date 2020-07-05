<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class YoutubeCleanup implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $id;

  public function __construct($id) {
    $this->id = $id;
  }

  public function handle() {
    if (file_exists(storage_path() . '/app/youtube/' . $this->id)) {
      Storage::deleteDirectory('youtube/' . $this->id);
    }
  }
}
