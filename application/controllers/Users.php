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
      $this->response($this->current_user->to_array(), parent::HTTP_OK);
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  public function service_post()
  {
    if(!is_null($this->get_credentials())) {
      list($email, $token) = array_values($this->get_credentials());
      $this->current_user = $this->user->register($this->get_params(), $token);
      if($this->current_user->get_id() > 0) {
        $this->response(json_encode($this->current_user), parent::HTTP_OK);
      } else {
        $this->response(NULL, parent::HTTP_UNPROCESSABLE_ENTITY);
      }
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  private function get_params()
  {
    return [
      'uid' => $this->input->post('uid'),
      'email' => $this->input->post('email'),
      'provider' => $this->input->post('provider'),
      'nama_lengkap' => $this->input->post('nama_lengkap'),
      'nama_depan' => $this->input->post('nama_depan'),
      'nama_belakang' => $this->input->post('nama_belakang'),
      'foto' => $this->input->post('foto')
    ];
  }
}
