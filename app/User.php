<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function hasPermission($permission) {
    foreach ($this->permissions()->get() as $perm) {
      if ($perm->name == $permission) {
        return true;
      }
    }

    return false;
  }

  public function getReminders() {
    return $this->hasMany('App\Reminder')->get();
  }

  public function getShortUrls() {
    return $this->hasMany('App\ShortUrl')->get();
  }

  public function permissions() {
    return $this->belongsToMany(Permission::class, 'user_permission');
  }

  public function getImages() {
    return $this->hasMany(Image::class);
  }

  public function getYoutubeInformation() {
    return $this->hasMany(YoutubeInformation::class)->first();
  }
}
