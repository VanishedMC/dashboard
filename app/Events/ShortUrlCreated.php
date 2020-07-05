<?php

namespace App\Events;

use App\User;
use App\ShortUrl;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ShortUrlCreated implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $queue = 'high';

  private $user;
  public $url;

  public function __construct(User $user, ShortUrl $url) {
    $this->user = $user;
    $this->url = $url;
  }

  public function broadcastOn() {
    return new PrivateChannel('url.created.' . $this->user->id);
  }
}
