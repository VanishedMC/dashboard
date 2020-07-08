<?php

namespace App\Jobs;

use App\Events\ReminderSent;
use App\Reminder;
use App\Repositories\DiscordRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDiscordReminder implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $reminderId;

  public function __construct($reminderId) {
    $this->queue = "high";
    $this->reminderId = $reminderId;
  }

  public function handle(DiscordRepository $repo) {
    try {
      $reminder = Reminder::where('id', $this->reminderId)->first();

      if ($reminder == null || !$reminder->exists()) {
        return false;
      }

      $repo->sendPrivateMessage($reminder->getUser(), $reminder->message);
      $reminder->delete();
      ReminderSent::dispatch($reminder->user_id);
      return true;
    } catch (ModelNotFoundException $ex) {
      return false;
    }
  }
}
