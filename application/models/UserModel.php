<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserModel extends CI_Model
{
	const TABLE_NAME = 'tb_users';

	protected $id;
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
		if(isset($attributes['id'])) { $instance->id = $attributes['id']; }
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

  public static function authenticate($username, $password)
  {
		$result = $this->db->select(self::TABLE_NAME.'.*')
											 ->whereRaw(sprintf('email = ? AND %s.token = ?', AuthenticationModel::TABLE_NAME), [$username, $password])
										 	 ->join(Authentication::TABLE_NAME, sprintf('%1$s.uid = %2$s.uid AND %1$s.provider = %2$s.provider', self::TABLE_NAME, AuthenticationModel::TABLE_NAME))
											 ->limit(1)
											 ->get()
											 ->result();
	  return $this->convert_result_to_model($result[0]);
  }

	function find_by_email($email)
	{
		$result = $this->db->where('email', $email)->limit(1)->get(self::TABLE_NAME)->result();

		if(!is_null($result)) {
			return $this->convert_result_to_model($result[0]);
		} else {
			return NULL;
		}
	}

	function find_by_uid($uid)
	{
		$result = $this->db->where('uid', $uid)->limit(1)->get(self::TABLE_NAME)->result();

		if(!is_null($result)) {
			return $this->convert_result_to_model($result[0]);
		} else {
			return NULL;
		}
	}

	function create($attributes = [])
	{
		$id = $attributes['id'];
		unset($attributes['id']);

		$this->db->insert(self::TABLE_NAME, $attributes);

		return $this->find_by_email($attributes['email']);
	}

	function is_email_taken($email)
	{
		return $this->db->select('id')->from(self::TABLE_NAME)->where('email', $email)->count_all_results() > 0;
	}

	private function convert_result_to_model($result) {
		if(is_null($result)) { return NULL; }

		$instance = NULL;
		$attrs = [];
		foreach($result as $key => $value) {
			$attrs[$key] = $value;
		}
		$instance = UserModel::build($attrs);

		return $instance;
	}
}
?>
