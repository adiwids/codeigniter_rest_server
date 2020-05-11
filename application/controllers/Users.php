<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/controllers/RESTResource.php';

class Users extends RESTResource {
  public function service_get()
  {
    if($this->authenticate_client()) {
      $collection = $this->user->search($this->input->get('query'));
      $this->response($collection, parent::HTTP_OK);
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  public function service_post()
  {
    if(!is_null($this->get_credentials())) {
      list($email, $token) = array_values($this->get_credentials());
      $this->current_user = $this->user->register($this->get_create_params(), $token);
      if($this->current_user->get_id() > 0) {
        $this->response($this->current_user->to_array(), parent::HTTP_OK);
      } else {
        $this->response(NULL, parent::HTTP_UNPROCESSABLE_ENTITY);
      }
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  public function service_put()
  {
    if($this->authenticate_client()) {
      $id = $this->put('id');
      if(!is_null($id)) {
        if($this->user->is_exists($id)) {
          $user = $this->user->update($id, $this->get_update_params());
          if($user !== FALSE) {
            $this->response($user->to_array(), parent::HTTP_OK);
          } else {
            $this->response(NULL, parent::HTTP_UNPROCESSABLE_ENTITY);
          }
        } else {
          $this->response(NULL, parent::HTTP_NOT_FOUND);
        }
      } else {
        $this->response(NULL, parent::HTTP_NOT_FOUND);
      }
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  public function service_delete()
  {
    if($this->authenticate_client()) {
      $id = $this->delete('id');
      if(!is_null($id)) {
        if($this->user->is_exists($id)) {
          $result = $this->user->delete($id);
          if($result) {
            $this->response(NULL, parent::HTTP_OK);
          } else {
            $this->response(NULL, parent::HTTP_UNPROCESSABLE_ENTITY);
          }
        } else {
          $this->response(NULL, parent::HTTP_NOT_FOUND);
        }
      } else {
        $this->response(NULL, parent::HTTP_NO_CONTENT);
      }
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  private function get_create_params()
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

  private function get_update_params()
  {
    return [
      'nama_depan' => $this->put('nama_depan'),
      'nama_belakang' => $this->put('nama_belakang')
    ];
  }
}
