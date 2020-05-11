<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/models/AuthenticationModel.php';

class UserModel extends CI_Model
{
	const TABLE_NAME = 'tb_users';

	public $id;
	public $email;
	public $uid;
	public $provider;
	public $nama_lengkap;
	public $nama_depan;
	public $nama_belakang;
	public $foto;

	public static function build($attributes = [])
	{
		$instance = new UserModel();
		if(isset($attributes['id'])) { $instance->set_id($attributes['id']); }
		if(isset($attributes['email'])) { $instance->email = $attributes['email']; }
		if(isset($attributes['uid'])) { $instance->uid = $attributes['uid']; }
		if(isset($attributes['provider'])) { $instance->provider = $attributes['provider']; }
		if(isset($attributes['nama_lengkap'])) { $instance->nama_lengkap = $attributes['nama_lengkap']; }
		if(isset($attributes['nama_depan'])) { $instance->nama_depan = $attributes['nama_depan']; }
		if(isset($attributes['nama_belakang'])) { $instance->nama_belakang = $attributes['nama_belakang']; }
		if(isset($attributes['foto'])) { $instance->foto = $attributes['foto']; }

		return $instance;
	}

	public function get_id() { return !is_null($this->id) ? intval($this->id) : 0; }

	public function set_id($id) {
		$this->id = intval($id);
	}

  public static function authenticate($username, $password)
  {
		$instance = new UserModel();
		$result = $instance->db->select(UserModel::TABLE_NAME.'.*')
													 ->where(sprintf('%s.uid', AuthenticationModel::TABLE_NAME), $username)
													 ->where(sprintf('%s.token', AuthenticationModel::TABLE_NAME), $password, TRUE)
													 ->join(AuthenticationModel::TABLE_NAME, sprintf('%1$s.uid = %2$s.uid AND %1$s.provider = %2$s.provider', UserModel::TABLE_NAME, AuthenticationModel::TABLE_NAME))
													 ->limit(1)
													 ->get(UserModel::TABLE_NAME)
													 ->result();

	  return count($result) > 0 ? $instance->convert_result_to_model($result[0]) : NULL;
	}

	public static function register($attributes, $password)
	{
		$instance = UserModel::build($attributes);
		$new_instance = NULL;
		if($instance->is_email_taken($attributes['email'])) {
			$new_instance = $instance->find_by_email($attributes['email']);
		} else {
			$new_instance = $instance->create($attributes);
		}

		$auth_attrs = [
			'uid' => $new_instance->uid,
			'provider' => $new_instance->provider,
			'email' => $new_instance->email,
			'token' => $password
		];
		$auth = new AuthenticationModel();
		$auth->create($auth_attrs);

		return $new_instance;
	}

	function find($id)
	{
		$result = $this->db->where('id', intval($id))->limit(1)->get(self::TABLE_NAME)->result();

		if(count($result) > 0) {
			return $this->convert_result_to_model($result[0]);
		} else {
			return NULL;
		}
	}

	function find_by_email($email)
	{
		$result = $this->db->where('email', $email)->limit(1)->get(self::TABLE_NAME)->result();

		if(count($result) > 0) {
			return $this->convert_result_to_model($result[0]);
		} else {
			return NULL;
		}
	}

	function find_by_uid($uid)
	{
		$result = $this->db->where('uid', $uid)->limit(1)->get(self::TABLE_NAME)->result();

		if(count($result) > 0) {
			return $this->convert_result_to_model($result[0]);
		} else {
			return NULL;
		}
	}

	function create($attributes = [])
	{
		if(array_key_exists('id', $attributes)) {
		  unset($attributes['id']);
		}

		$this->db->insert(self::TABLE_NAME, $attributes);

		return $this->find_by_email($attributes['email']);
	}

	function is_email_taken($email)
	{
		return $this->db->select('id')->from(self::TABLE_NAME)->where('email', $email)->count_all_results() > 0;
	}

	function is_uid_taken($uid)
	{
		return $this->db->select('id')->from(self::TABLE_NAME)->where('uid', $uid)->count_all_results() > 0;
	}

	function is_exists($id)
	{
		return $this->db->select('id')->from(self::TABLE_NAME)->where('id', intval($id))->count_all_results() > 0;
	}

	function convert_result_to_model($result) {
		if(is_null($result)) { return NULL; }

		$instance = NULL;
		$attrs = [];
		foreach($result as $key => $value) {
			$attrs[$key] = $value;
		}
		$instance = UserModel::build($attrs);

		return $instance;
	}

	function to_array()
	{
		return [
			"id" => $this->get_id(),
			"uid" => $this->uid,
			"email" => $this->email,
			"provider" => $this->provider,
			"nama_lengkap" => $this->nama_lengkap,
			"nama_depan" => $this->nama_depan,
			"nama_belakang" => $this->nama_belakang,
			"foto" => $this->foto
		];
	}

	function search($q = "")
	{
		$_q = trim($q);
		$collection = [];
		$query = $this->db;
		if(strlen($_q) > 0) {
			$query = $query->where('id', $_q)->or_where('uid', $_q)->or_like('nama_lengkap', $_q);
		}

		$results = $query->get(self::TABLE_NAME)->result();
		foreach($results as $result) {
			array_push($collection, $this->convert_result_to_model($result));
		}

		return $collection;
	}

	function update($id, $attributes)
	{
		$result = $this->db->set('nama_depan', $attributes['nama_depan'])
							    		 ->set('nama_belakang', $attributes['nama_belakang'])
										   ->set('nama_lengkap', sprintf("%s %s", $attributes['nama_depan'], $attributes['nama_belakang']))
										   ->where('id', intval($id))
											 ->update(self::TABLE_NAME);
		return $result ? $this->find($id) : FALSE;
	}

	function delete($id)
	{
		return $this->db->where('id', intval($id))->delete(self::TABLE_NAME);
	}
}
?>
