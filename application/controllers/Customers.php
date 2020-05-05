<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/controllers/RESTResource.php';

class Customers extends RESTResource {
  public function service_get()
  {
    if($this->authenticate_client()) {
      $this->response(NULL, 204);
    } else {
      $this->response(NULL, 401);
    }
  }
}
