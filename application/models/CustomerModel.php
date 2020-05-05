<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
	const TABLE_NAME = 'tb_pelanggan';

	protected $nik_pelanggan;
	public $nama_pelanggan;
	public $telepon_pelanggan;

	public static function build($attributes = [])
	{
		$instance = new CustomerModel();
		if(isset($attributes['nik_pelanggan'])) { $instance->nik_pelanggan = $attributes['nik_pelanggan']; }
		if(isset($attributes['nama_pelanggan'])) { $instance->nama_pelanggan = $attributes['nama_pelanggan']; }
		if(isset($attributes['telepon_pelanggan'])) { $instance->telepon_pelanggan = $attributes['telepon_pelanggan']; }

		return $instance;
	}

  public function get_id() { return !is_null($this->nik_pelanggan) ? intval($this->nik_pelanggan) : NULL; }

	function find_by_nik($nik)
	{
		$result = $this->db->where('nik_pelanggan', $nik)->limit(1)->get(self::TABLE_NAME)->result();

		if(count($result) > 0) {
			return $this->convert_result_to_model($result[0]);
		} else {
			return NULL;
		}
	}

	function create($attributes = [])
	{
		$this->db->insert(self::TABLE_NAME, $attributes);

		return $this->find_by_nik($attributes['nik_pelanggan']);
	}

	function is_nik_taken($nik)
	{
		return $this->db->select('nik_pelanggan')->from(self::TABLE_NAME)->where('nik_pelanggan', $nik)->count_all_results() > 0;
	}

	private function convert_result_to_model($result) {
		if(is_null($result)) { return NULL; }

		$instance = NULL;
		$attrs = [];
		foreach($result as $key => $value) {
			$attrs[$key] = $value;
		}
		$instance = CustomerModel::build($attrs);

		return $instance;
	}
}
?>
