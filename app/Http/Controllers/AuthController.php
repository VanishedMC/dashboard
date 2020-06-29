<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facade\Log;

class AuthController extends Controller {
  public function redirect() {
      return redirect(env('DISCORD_OAUTH_URL'));
  }

  public function login(Request $request) {
      $this->validate($request, [
          'code' => 'required'
      ]);

      $data = [
          'client_id' => env('DISCORD_CLIENT_ID'),
          'client_secret' => env('DISCORD_CLIENT_SECRET'),
          'code' => $request->input('code'),
          'grant_type' => 'authorization_code',
          'redirect_uri' => env('DISCORD_REDIRECT_URL'),
          'scope' => 'identify',
      ];

      $client = new Client;

      $res = $client->post('https://discordapp.com/api/oauth2/token', [
          'headers' => [
              'content-type: application/x-www-form-urlencoded',
          ],
          'form_params' => $data
      ]);

      if ($res->getStatusCode() !== 200) {
          return redirect('/login')->with([
              'login_error' => 'Discord authorization failed.'
          ]);
      }

      $response = json_decode($res->getBody()->getContents());

      $res = $client->get('https://discordapp.com/api/v6/users/@me', [
          'headers' => [
              'Authorization' => 'Bearer ' . $response->access_token,
          ]
      ]);

      $discordUser = json_decode($res->getBody()->getContents());

      if (($user = User::where('discord_id', $discordUser->id)->first()) == null) {
          // Create a new account
          $u = new User;
          $u->discord_id = $discordUser->id;
          $u->api_token = Str::random(80);
          $u->name = $discordUser->username . '#' . $discordUser->discriminator;
          $u->avatar_url = 'https://cdn.discordapp.com/avatars/'.$discordUser->id.'/'.$discordUser->avatar.(Str::startsWith($discordUser->avatar, 'a_') ? '.gif' : '.png');
          $u->save();

          $user = User::where('discord_id', $discordUser->id)->first();
      } else {
          // Update avatar
          $user->avatar_url = 'https://cdn.discordapp.com/avatars/'.$discordUser->id.'/'.$discordUser->avatar.(Str::startsWith($discordUser->avatar, 'a_') ? '.gif' : '.png');
          $user->name = $discordUser->username . '#' . $discordUser->discriminator;
          $user->save();
      }

      auth()->login($user, true);

      return redirect()->route('home');
  }

  public function logout() {
      auth()->logout();
      return redirect()->route('login');
  }
}
