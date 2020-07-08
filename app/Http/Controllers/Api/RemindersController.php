<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendDiscordReminder;
use App\Reminder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemindersController extends Controller {

  public function list(Request $request) {
    return Auth::user()->getReminders();
  }

  public function cancel(Request $request) {
    $request->validate([
      'id' => 'required|integer',
    ]);

    $reminder = Reminder::where('id', $request->input('id'))->first();
    if ($reminder == null || $reminder->user_id != Auth::user()->id) {
      return response('Not found', 404);
    }

    $reminder->delete();
    return response('ok', 200);
  }

  public function set(Request $request) {
    $request->validate([
      'message' => 'string|required|min:1|max:2000',
      'diff' => 'required|integer',
      'due' => 'required|string',
    ]);

    $reminder = new Reminder();
    $reminder->message = $request->input('message');
    $reminder->due = $request->input('due');
    $reminder->user_id = Auth::user()->id;
    $reminder->save();

    $diff = $request->input('diff');
    SendDiscordReminder::dispatch($reminder->id)->delay(now()->addMilliseconds($diff));

    return response('ok', 200);
  }
}
