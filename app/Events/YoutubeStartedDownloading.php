<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class YoutubeStartedDownloading implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $queue = 'high';

  private $user;

  public function __construct(User $user) {
    $this->user = $user;
  }

  public function broadcastOn() {
    return new PrivateChannel('youtube.' . $this->user->id);
  }
}
