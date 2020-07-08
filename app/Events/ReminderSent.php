<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReminderSent implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $queue = 'high';
  private $userId;

  public function __construct($userId) {
    $this->userId = $userId;
  }

  public function broadcastOn() {
    return new PrivateChannel('reminder.sent.' . $this->userId);
  }
}
