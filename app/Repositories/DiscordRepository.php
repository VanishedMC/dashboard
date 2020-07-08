<?php

namespace App\Repositories;

use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DiscordRepository {

  private $base_url;
  private $guild_id;
  private $headers;

  public function __construct() {
    $this->base_url = 'https://discord.com/api';
    $this->guild_id = env('DISCORD_GUILD_ID');
    $this->headers = ['headers' => [
      'Authorization' => 'Bot ' . env('DISCORD_BOT_TOKEN'),
      'Content-Type' => 'application/json',
    ]];
  }

  public function sendPrivateMessage(User $user, $message) {
    try {
      $client = new Client;
      $res = $client->post($this->base_url . '/users/@me/channels', array_merge($this->headers, [
        'json' => ['recipient_id' => $user->discord_id],
      ]));

      $privateChannel = json_decode($res->getBody())->id;

      $res = $client->post($this->base_url . "/channels/$privateChannel/messages", array_merge($this->headers, [
        'json' => ['content' => $message],
      ]));

      return true;
    } catch (RequestException $ex) {
      return false;
    }
  }

  public function isUserInGuild(User $user) {
    try {
      $res = (new Client)->get($this->base_url . "/guilds/$this->guild_id/members?limit=1000", $this->headers);
      $members = json_decode($res->getBody());

      foreach ($members as $member) {
        if ($member->user->id === $user->discord_id) {
          return true;
        }
      }

      return false;
    } catch (Exception $ex) {
      return false;
    }
  }

  public function addUserToGuild(User $user, $access_token) {
    try {
      $res = (new Client)->put($this->base_url . "/guilds/$this->guild_id/members/$user->discord_id", array_merge($this->headers, [
        'json' => ['access_token' => $access_token],
      ]));
      return true;
    } catch (RequestException $ex) {
      return false;
    }
  }
}
