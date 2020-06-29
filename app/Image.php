<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {
    
  public function getPoster() {
    return $this->belongsTo(User::class);
  }
}
