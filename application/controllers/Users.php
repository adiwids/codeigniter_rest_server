<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/controllers/RESTResource.php';

class Users extends RESTResource {
  public function __construct()
  {
    parent::__construct();
    $this->load->model('AuthenticationModel', 'user_auth');
  }

  public function service_get()
  {
    if($this->authenticate_client()) {
      $this->response(NULL, 204);
    } else {

      $this->response(json_encode($this->current_user), 401);
    }
  }

  public function service_post()
  {
    $this->response(NULL, 204);
  }
}
