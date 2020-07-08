<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model {
  public $timestamps = false;

  public function getUser() {
    return $this->belongsTo('App\User', 'user_id')->first();
  }
}
