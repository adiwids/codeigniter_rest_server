<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/RESTServer.php';
use Lib\RESTServer;

class Pelanggan extends RESTServer {
	function __construct() {
        parent::__construct();        
        $this->load->database();
    }

    //tampil data pelanggan
    function service_get() {    	
        
        //buat parameter untuk pencarian
        $src = $this->get('src');
        //jika "src" tidak diisi / kosong
        if($src == "")
        {
        	//tampilkan seluruh data "tb_pelanggan"
        	$hasil = $this->db->get('tb_pelanggan')->result();
        }
        //jika "src" diisi / tidak kosong
        else
        {
        	//tampilkan data "tb_pelanggan" berdasarkan nilai "src"
        	//$this->db->where("nik_pelanggan = '$src'");
        	//$this->db->where("nik_pelanggan LIKE '%$src%'");

        	//buat parameter untuk pencarian ke 2
        	$src2 = $this->get('src2');
        	//jika "src2" tidak diisi / kosong
        	if($src2 == "")
        	{
        		//tampilkan data "tb_pelanggan" berdasarkan nilai "src"
        		$this->db->where("nik_pelanggan = '$src' OR nama_pelanggan LIKE '%$src%'");
        	}
        	//jika "src2" diisi / tidak  kosong
        	else
        	{
        		//tampilkan data "tb_pelanggan" berdasarkan nilai "src" dan "src2"
        		$this->db->where("(nik_pelanggan = '$src' OR nama_pelanggan LIKE '%$src%') AND telepon_pelanggan LIKE '%$src2%'");
        	}	

        	
        	$hasil = $this->db->get('tb_pelanggan')->result();
        }
                
                
        $this->response($hasil, 200);
    }

    //simpan data pelanggan
    function service_post() {
        //baca data yang dikirim
        $data = array(
                    'nik_pelanggan' => $this->post('nik1'),
                    'nama_pelanggan' => $this->post('nama1'),
                    'telepon_pelanggan' => $this->post('telepon1'));
        //proses penyimpanan
        $insert = $this->db->insert('tb_pelanggan', $data);
        //jika penyimpanan berhasil
        if ($insert) {
            $this->response($data, 200);
        } 
        //jika penyimpanan gagal
        else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    //update data pelanggan
    function service_put() {
        //parameter untuk update data
        $id = $this->put('id');
        //baca data yang dikirim
        $data = array(
                    'nik_pelanggan' => $this->put('nik1'),
                    'nama_pelanggan' => $this->put('nama1'),
                    'telepon_pelanggan' => $this->put('telepon1'));
        //proses update data berdasarkan data dan parameter yang dikirim
        $this->db->where('nik_pelanggan', $id);
        $update = $this->db->update('tb_pelanggan', $data);
        //jika update berhasil
        if ($update) {
            $this->response($data, 200);
        } 
        //jika update gagal
        else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    //hapus data pelanggan
    function service_delete() {        
        //parameter untuk delete data
        $id = $this->delete('id');
        //proses delete data berdasarkan parameter yang dikirim
        $this->db->where('nik_pelanggan', $id);        
        $delete = $this->db->delete('tb_pelanggan');
        //jika delete berhasil
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } 
         //jika delete gagal
        else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
