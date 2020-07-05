<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionChange implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $queue = 'high';

  public $user;

  public function __construct(User $changed) {
    $this->user = $changed;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn() {
    return new PrivateChannel('admin.permissions.' . $this->user->id);
  }
}
