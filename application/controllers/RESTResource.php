<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RESTServer.php';
use Lib\RESTServer;

class RESTResource extends RESTServer {
  protected $current_user;

  function __construct() {
    parent::__construct();
    $this->load->database('default');
    $this->load->model('UserModel', 'user');
  }

  protected function authenticate_client()
  {
    $credentials = $this->get_credentials();
    if(!is_null($credentials)) {
      $this->current_user = $this->user->authenticate($credentials['username'], $credentials['password']);
      if(!is_null($this->current_user) && $this->current_user !== FALSE) {
        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  protected function get_credentials()
  {
    if(!isset($_SERVER['HTTP_AUTHORIZATION'])) { return NULL; }

    list($basic, $encoded) = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
    list($username, $password) = explode(':', base64_decode($encoded));

    return [
      'username' => $username,
      'password' => $password
    ];
  }
}
