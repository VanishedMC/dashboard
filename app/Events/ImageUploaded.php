<?php

namespace App\Events;

use App\User;
use App\Image;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImageUploaded implements ShouldBroadcast { 
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = 'high';

    public $image;
    private $user;

    public function __construct(Image $image, User $user) {
      $this->image = $image;
      $this->user = $user;
    }

    public function broadcastOn() {
        return new PrivateChannel('image.uploaded.' . $this->user->id);
    }
}
