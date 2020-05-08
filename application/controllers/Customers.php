<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/controllers/RESTResource.php';

class Customers extends RESTResource {
  public function service_get()
  {
    if($this->authenticate_client()) {
      $this->response(NULL, parent::HTTP_NO_CONTENT);
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  public function service_post()
  {
    if($this->authenticate_client()) {
      $this->response(NULL, parent::HTTP_NO_CONTENT);
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  public function service_put()
  {
    if($this->authenticate_client()) {
      $this->response(NULL, parent::HTTP_NO_CONTENT);
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  public function service_delete()
  {
    if($this->authenticate_client()) {
      $this->response(NULL, parent::HTTP_NO_CONTENT);
    } else {
      $this->response(NULL, parent::HTTP_UNAUTHORIZED);
    }
  }

  private function get_params()
  {
    return [
      'nik_pelanggan' => $this->input->post('nik_pelanggan_new'),
      'nama_pelanggan' => $this->input->post('nama_pelanggan'),
      'telepon_pelanggan' => $this->input->post('telepon_pelanggan')
    ];
  }
}
