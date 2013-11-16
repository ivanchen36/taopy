<?php  if (!defined('APP_PATH')) exit('No direct script access allowed');
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class Cookie {

	var $cookie_prefix				= '';
	var $cookie_path				= '/';
	var $cookie_domain				= '';
	var $cookie_secure				= FALSE;
	var $security;
	var $encrypt;
	var $now;
	var $user_agent;
	var $ip_address;

	public function __construct()
	{
		$params = spExt('sessionAndCookie');
		if(is_array($params)){

			foreach (array('cookie_path', 'cookie_domain', 'cookie_secure', 'cookie_prefix') as $key)
			{
				$this->$key = (isset($params[$key])) ? $params[$key] : $this->$key;
			}
		}
		$this->security = spClass('Security');
		$this->encrypt = spClass('Encrypt');

	}

	function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE)
	{
		if ( ! isset($array[$index]))
		{
			return FALSE;
		}

		if ($xss_clean === TRUE)
		{
			return $this->security->xss_clean($array[$index]);
		}

		return $array[$index];
	}

	function get_data($index = '', $xss_clean = FALSE)
	{
		return $this->_fetch_from_array($_COOKIE, $this->cookie_prefix.$index, $xss_clean);
	}

	function secure_get($index = '', $xss_clean = FALSE)
	{
		// Fetch the cookie
		$cookie = $this->get_data($index);

		// No cookie?  Goodbye cruel world!...
		if ($cookie === FALSE)
		{
			return FALSE;
		}

		$cookie = $this->encrypt->decode($cookie);

		// Unserialize the session array
		$cookie = $this->_unserialize($cookie);

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($cookie) OR ! isset($cookie['cookie_id']))
		{
			$this->delete_data($index);
			return FALSE;
		}

		$userdata = $cookie['userdata'];
		unset($cookie);
		return $userdata;
	}


	private function create_secure_limit(){
		$id = '';
		while (strlen($id) < 32)
		{
			$id .= mt_rand(0, mt_getrandmax());
		}

		$secure_data = array('cookie_id'	=> md5(uniqid($id, TRUE)));

		return $secure_data;
	}

	// ------------------------------------------------------------------------


	function secure_set($name = '', $cookie_data = '', $expire = ''){
		$secure_data = $this->create_secure_limit();
		$secure_data['userdata'] = $cookie_data;
		$data = $this->_serialize($secure_data);
		$data = $this->encrypt->encode($data);
		$this->set_data($name,$data,$expire);
	}

	function set_data($name = '', $value = '', $expire = ''){
		if ( ! is_numeric($expire)){
			$expire = time() - 86500;
		}else{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}
		setcookie($this->cookie_prefix.$name, $value, $expire, $this->cookie_path, $this->cookie_domain, $this->cookie_secure);
	}
	function _unserialize($data)
	{
		$data = @unserialize(strip_slashes($data));

		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('{{slash}}', '\\', $val);
				}
			}

			return $data;
		}

		return (is_string($data)) ? str_replace('{{slash}}', '\\', $data) : $data;
	}
	function _serialize($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $val)
			{
				if (is_string($val))
				{
					$data[$key] = str_replace('\\', '{{slash}}', $val);
				}
			}
		}
		else
		{
			if (is_string($data))
			{
				$data = str_replace('\\', '{{slash}}', $data);
			}
		}

		return serialize($data);
	}

	function delete_data($name = '')
	{
		$this->set_data($name, '', '');
	}

	function user_agent()
	{
		if ($this->user_agent)
		{
			return $this->user_agent;
		}

		$this->user_agent = ( ! isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];

		return $this->user_agent;
	}


	function server($index = '', $xss_clean = FALSE)
	{
		return $this->_fetch_from_array($_SERVER, $index, $xss_clean);
	}

}
