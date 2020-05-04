<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AuthenticationModel extends CI_Model
{
	const TABLE_NAME = 'tb_otentikasi';

	protected $id;
	public $email;
	public $uid;
	public $provider;
	public $token;

	public static function build($attributes = [])
	{
		$instance = new UserModel();
		if(isset($attributes['id'])) { $instance->id = $attributes['id']; }
		if(isset($attributes['email'])) { $instance->email = $attributes['email']; }
		if(isset($attributes['uid'])) { $instance->uid = $attributes['uid']; }
		if(isset($attributes['provider'])) { $instance->provider = $attributes['provider']; }
		if(isset($attributes['token'])) { $instance->token = $attributes['token']; }

		return $instance;
	}

  public function get_id() { return !is_null($this->id) ? intval($this->id) : 0; }

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

  function is_uid_taken($uid)
	{
		return $this->db->select('id')->from(self::TABLE_NAME)->where('uid', $uid)->count_all_results() > 0;
	}

	private function convert_result_to_model($result) {
		if(is_null($result)) { return NULL; }

		$instance = NULL;
		$attrs = [];
		foreach($result as $key => $value) {
			$attrs[$key] = $value;
		}
		$instance = AuthenticationModel::build($attrs);

		return $instance;
	}
}
?>
