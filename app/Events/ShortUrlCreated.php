<?php

namespace App\Events;

use App\ShortUrl;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
