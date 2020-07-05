<?php

namespace App\Http\Controllers\Api;

use App\Events\PermissionChange;
use App\Http\Controllers\Controller;
use App\Permission;
use App\User;
use App\UserPermission;
use Illuminate\Http\Request;

class PermissionController extends Controller {
  public function list(Request $request) {
    $permissions = Permission::get();
    return $permissions;
  }

  public function grant(Request $request) {
    $request->validate([
      'user_id' => 'required|integer',
      'permission_id' => 'required|integer',
    ]);

    $user = $request->input('user_id');
    $perm = $request->input('permission_id');
    $targetUser = User::where('id', $user)->first()->load('permissions');

    if (UserPermission::where('user_id', $user)->where('permission_id', $perm)->first() == null) {
      $up = new UserPermission();
      $up->user_id = $request->input('user_id');
      $up->permission_id = $request->input('permission_id');
      $up->save();

      broadcast(new PermissionChange($targetUser))->toOthers();
    } else {
      return response('duplicate permission', 500);
    }
  }

  public function revoke(Request $request) {
    $request->validate([
      'user_id' => 'required|integer',
      'permission_id' => 'required|integer',
    ]);

    $user = $request->input('user_id');
    $perm = $request->input('permission_id');
    $targetUser = User::where('id', $user)->first()->load('permissions');

    $up = UserPermission::where('user_id', $user)->where('permission_id', $perm)->first();
    if ($up != null) {
      $up->delete();
      broadcast(new PermissionChange($targetUser))->toOthers();
    } else {
      return response('permission does not exist', 500);
    }
  }
}
