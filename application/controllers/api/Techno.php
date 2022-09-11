<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Techno extends REST_Controller {

   function __construct()
    {
        parent::__construct();
        $this->load->model('Techno_model');
    }

    private function bad($message)
    {
      $this->response([
            'status' => FALSE,
            'code' => 500,
            'message' => $message
      ], REST_Controller::HTTP_BAD_REQUEST);
    }

    private function ok($data, $message)
    {
      $this->response([
            'status' => TRUE,
            'code' => 200,
            'data' => $data,
            'message' => $message,
      ], REST_Controller::HTTP_OK);
    }

    private function notfound($message)
    {
      $this->response([
            'status' => FALSE,
            'code' => 404,
            'message' => $message
      ], REST_Controller::HTTP_NOT_FOUND);
    }

    public function login_post()
    {
          $data = $this->Techno_model->login($this->post('username'), $this->post('password'));
          $data ? $this->ok($data, 'Berhasil Login') : $this->notfound('Akun anda belum aktif atau Password anda salah');
    }

    public function index_get()
    {
      $data = $this->Techno_model->getContact();
      $this->ok($data, 'Data berhasil diakses');  
    }

    public function contactById_get($id)
    {
      $data = $this->Techno_model->getContactById($id);
      $this->ok($data, 'Data berhasil diakses');  
    }

    public function index_post()
    {
          $data = $this->Techno_model->save($this->post());
          $data ? $this->ok($data, 'Berhasil Disimpan') : $this->notfound('Gagal Disimpan');
    }

    public function update_post()
    {
      $data = $this->Techno_model->update($this->post());
      $data ? $this->ok($data, 'Berhasil Diubah') : $this->notfound('Gagal Diubah');
    }


    public function delete_post()
    {
      $data = $this->Techno_model->delete($this->post('id'));
      $data == 1 ? $this->ok($data, 'Berhasil Dihapus') : $this->notfound('Gagal Dihapus');
    }

}