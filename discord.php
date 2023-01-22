<?php
/* Discord Oauth v.4.1
 * This file contains the core functions of the oauth2 script.
 * @author : MarkisDev
 * @copyright : https://markis.dev
 * @contributor: FoxWorn3365
*/

namespace Discord {
  class Auth {
    protected string $baseurl = "https://discord.com";
    protected string $bot_token;
    protected mixed $config;

    function __construct(array $config) {
      $this->config = (object)$config;
      $this->config->baseurl = $this->baseurl;
    }

    // Generate random string for state (for CSRF)
    protected static function state() {
      return bin2hex(openssl_random_pseudo_bytes(12));
    }

    // Go
    public function go(string $getState = null) {
      // use config from this
      $code = $_GET['code'];
      $state = $_GET['state'];
      if ($state !== NULL && $state !== $getState) {
        return;
      }
      $url = $this->baseurl . "/api/oauth2/token";
      $data = http_build_query(array(
          "client_id" => $this->config->client_id,
          "client_secret" => $this->config->client_secret,
          "grant_type" => "authorization_code",
          "code" => $code,
          "redirect_uri" => $this->config->redirect_url
      ));
      $options = stream_context_create(array( 
        'http' => array(
          'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
          'method' => 'POST',
          'content' => $data
        )
      )); 
      return new \Discord\PromiseManager(json_decode(file_get_contents($url, false, $options))->access_token, $this->config);
    }
  }

  class PromiseManager {
    public int $status;
    public string $token;
    public string $user;
    public mixed $config;

    function __construct(string $token, mixed $config) {
      $this->token = $token;
      $this->config = (object)$config;
    }

    public function then(mixed $callback) {
      return $callback((new \Discord\Get($this->token, $this->config)), (new \Discord\Set($this->token, $this->config)));
    }
 
    public function done(mixed $callback) {
      $this->then($callback);
    }
  }

  class Get {
    protected string $token;
    public mixed $config;

    function __construct(string $token, mixed $config) {
      $this->token = $token;
      $this->config = (object)$config;
    }

    public function user() {
      $url = $this->config->baseurl . "/api/users/@me";
      $options = stream_context_create([ 
        'http' => [
          'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                      "Authorization: Bearer {$this->token}\r\n",
          'method' => 'GET'
        ]
      ]);
      return json_decode(file_get_contents($url, false, $options));
    }

    public function guilds() {
      $url = $this->config->baseurl . "/api/users/@me/guilds";
      $options = stream_context_create([ 
        'http' => [
          'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                      "Authorization: Bearer {$this->token}\r\n",
          'method' => 'GET'
        ]
      ]);
      return json_decode(file_get_contents($url, false, $options));
    }

    public function guild(int $id) {
      $url = $this->config->baseurl . "/api/guilds/{$id}";
      $options = stream_context_create([ 
        'http' => [
          'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                      "Authorization: Bearer {$this->token}\r\n",
          'method' => 'GET'
        ]
      ]);
      return json_decode(file_get_contents($url, false, $options));
    }

    public function connections() {
      $url = $this->config->baseurl . "/api/users/@me/connections";
      $options = stream_context_create([ 
        'http' => [
          'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                      "Authorization: Bearer {$this->token}\r\n",
          'method' => 'GET'
        ]
      ]);
      return json_decode(file_get_contents($url, false, $options));
    }
  }
  
  class Set {
    public string $token;
    public object $config;

    function __construct(string $token, mixed $config) {
      $this->token = $token;
      $this->config = (object)$config;
    }

    public function role(int $guild, int $user, int $role) {
      $url = $this->config->baseurl . "/api/guilds/{$guild}/members/{$user}/roles/{$role}";
      $options = stream_context_create([ 
        'http' => [
          'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                      "Authorization: Bearer {$this->token}\r\n",
          'method' => 'PUT'
        ]
      ]);
      return json_decode(file_get_contents($url, false, $options));
    }
  }
}
