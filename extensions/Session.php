<?php if (!defined('APP_PATH')) exit('No direct script access allowed');
/**
 * 		Primary from CI
 *     	Modified By [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class Session {

	var $sess_encrypt_cookie		= FALSE;
	var $sess_use_database			= FALSE;
	var $sess_table_name			= '';
	var $sess_expiration			= 7200;
	var $sess_expire_on_close		= TRUE;
	var $sess_match_ip				= FALSE;
	var $sess_match_useragent		= TRUE;
	var $sess_cookie_name			= 'pt_session';
	var $cookie_prefix				= '';
	var $cookie_path				= '/';
	var $cookie_domain				= '';
	var $cookie_secure				= FALSE;
	var $sess_time_to_update		= 300;
	var $encryption_key				= 'dsgdsfghfhgh';
	var $flashdata_key				= 'flash';
	var $time_reference				= 'time';
	var $gc_probability				= 5;
	var $userdata					= array();
	var $now;
	var $user_agent;
	var $ip_address;
	var $security;
	var $encrypt;

	public function __construct($p=null)
	{
		$params = spExt('sessionAndCookie');
		if(!$p){
			array_merge($params, $p);
		}
		$this->init($params);
	}

	public function init($params){
		if(is_array($params)){
			foreach (array('sess_encrypt_cookie', 'sess_use_database', 'sess_table_name', 'sess_expiration', 'sess_expire_on_close', 'sess_match_ip', 'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key') as $key)
			{
				$this->$key = (isset($params[$key])) ? $params[$key] : $this->$key;
			}
		}
		$this->now = $this->_get_time();
		$this->security = spClass('Security');

		if ($this->sess_encrypt_cookie == TRUE)
		{
			$this->encrypt = spClass('Encrypt');
		}

		if ($this->sess_expiration == 0)
		{
			$this->sess_expiration = (60*60*24*365*2);
		}

		$this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;

		if ( ! $this->sess_read())
		{
			$this->sess_create();
		}
		else
		{
			$this->sess_update();
		}
		$this->_flashdata_sweep();
		$this->_flashdata_mark();
		$this->_sess_gc();
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

	function ip_address()
	{
		if ($this->ip_address)
		{
			return $this->ip_address;
		}
		if ($this->server('REMOTE_ADDR') AND $this->server('HTTP_CLIENT_IP'))
		{
			$this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ($this->server('REMOTE_ADDR'))
		{
			$this->ip_address = $_SERVER['REMOTE_ADDR'];
		}
		elseif ($this->server('HTTP_CLIENT_IP'))
		{
			$this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ($this->server('HTTP_X_FORWARDED_FOR'))
		{
			$this->ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		if ($this->ip_address === FALSE)
		{
			$this->ip_address = '0.0.0.0';
			return $this->ip_address;
		}

		if (strpos($this->ip_address, ',') !== FALSE)
		{
			$x = explode(',', $this->ip_address);
			$this->ip_address = trim(end($x));
		}

		if ( ! $this->valid_ip($this->ip_address))
		{
			$this->ip_address = '0.0.0.0';
		}

		return $this->ip_address;
	}

	function valid_ip($ip)
	{
		$ip_segments = explode('.', $ip);

		if (count($ip_segments) != 4)
		{
			return FALSE;
		}
		if ($ip_segments[0][0] == '0')
		{
			return FALSE;
		}
		foreach ($ip_segments as $segment)
		{
			if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
			{
				return FALSE;
			}
		}

		return TRUE;
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

	function cookie($index = '', $xss_clean = FALSE)
	{
		return $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
	}

	function sess_read()
	{
		$session = $this->cookie($this->sess_cookie_name);
		if ($session === FALSE)
		{
			return FALSE;
		}
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$session = $this->encrypt->decode($session);
		}
		else
		{
			$hash	 = substr($session, strlen($session)-32); // get last 32 chars
			$session = substr($session, 0, strlen($session)-32);

			if ($hash !==  md5($session.$this->encryption_key))
			{
				$this->sess_destroy();
				return FALSE;
			}
		}

		$session = $this->_unserialize($session);
		if ( ! is_array($session) OR ! isset($session['session_id']) OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) OR ! isset($session['last_activity']))
		{
			$this->sess_destroy();
			return FALSE;
		}

		if (($session['last_activity'] + $this->sess_expiration) < $this->now)
		{
			$this->sess_destroy();
			return FALSE;
		}

		if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->ip_address())
		{
			$this->sess_destroy();
			return FALSE;
		}

		if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->user_agent(), 0, 120)))
		{
			$this->sess_destroy();
			return FALSE;
		}

		$this->userdata = $session;
		unset($session);

		return TRUE;
	}

	function sess_write()
	{
		if ($this->sess_use_database === FALSE)
		{
			$this->_set_cookie();
			return;
		}
	}

	function sess_create()
	{
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}

		$sessid .= $this->ip_address();

		$this->userdata = array(
							'session_id'	=> md5(uniqid($sessid, TRUE)),
							'ip_address'	=> $this->ip_address(),
							'user_agent'	=> substr($this->user_agent(), 0, 120),
							'last_activity'	=> $this->now,
							'user_data'		=> ''
							);

							// Write the cookie
							$this->_set_cookie();
	}

	function sess_update()
	{
		if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
		{
			return;
		}

		$old_sessid = $this->userdata['session_id'];
		$new_sessid = '';
		while (strlen($new_sessid) < 32)
		{
			$new_sessid .= mt_rand(0, mt_getrandmax());
		}

		$new_sessid .= $this->ip_address();
		$new_sessid = md5(uniqid($new_sessid, TRUE));
		$this->userdata['session_id'] = $new_sessid;
		$this->userdata['last_activity'] = $this->now;
		$cookie_data = NULL;
		$this->_set_cookie($cookie_data);
	}
	function sess_destroy()
	{
		setcookie(
		$this->sess_cookie_name,
		addslashes(serialize(array())),
		($this->now - 31500000),
		$this->cookie_path,
		$this->cookie_domain,
		0
		);
	}

	function get_data($item)
	{
		return ( ! isset($this->userdata[$item])) ? FALSE : $this->userdata[$item];
	}

	function all_data()
	{
		return $this->userdata;
	}

	function set_data($newdata = array(), $newval = '')
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}

		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$this->userdata[$key] = $val;
			}
		}

		$this->sess_write();
	}

	function unset_data($newdata = array())
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => '');
		}

		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				unset($this->userdata[$key]);
			}
		}

		$this->sess_write();
	}

	function set_flashdata($newdata = array(), $newval = '')
	{
		if (is_string($newdata))
		{
			$newdata = array($newdata => $newval);
		}

		if (count($newdata) > 0)
		{
			foreach ($newdata as $key => $val)
			{
				$flashdata_key = $this->flashdata_key.':new:'.$key;
				$this->set_data($flashdata_key, $val);
			}
		}
	}

	function keep_flashdata($key)
	{
		$old_flashdata_key = $this->flashdata_key.':old:'.$key;
		$value = $this->get_data($old_flashdata_key);

		$new_flashdata_key = $this->flashdata_key.':new:'.$key;
		$this->set_data($new_flashdata_key, $value);
	}

	function flashdata($key)
	{
		$flashdata_key = $this->flashdata_key.':old:'.$key;
		return $this->get_data($flashdata_key);
	}

	function _flashdata_mark()
	{
		$userdata = $this->all_data();
		foreach ($userdata as $name => $value)
		{
			$parts = explode(':new:', $name);
			if (is_array($parts) && count($parts) === 2)
			{
				$new_name = $this->flashdata_key.':old:'.$parts[1];
				$this->set_data($new_name, $value);
				$this->unset_data($name);
			}
		}
	}

	function _flashdata_sweep()
	{
		$userdata = $this->all_data();
		foreach ($userdata as $key => $value)
		{
			if (strpos($key, ':old:'))
			{
				$this->unset_data($key);
			}
		}

	}

	function _get_time()
	{
		if (strtolower($this->time_reference) == 'gmt')
		{
			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
		}
		else
		{
			$time = time();
		}

		return $time;
	}

	function _set_cookie($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}

		// Serialize the userdata for the cookie
		$cookie_data = $this->_serialize($cookie_data);

		if ($this->sess_encrypt_cookie == TRUE)
		{
			$cookie_data = $this->encrypt->encode($cookie_data);
		}
		else
		{
			// if encryption is not used, we provide an md5 hash to prevent userside tampering
			$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
		}

		$expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();

		// Set the cookie
		setcookie(
		$this->sess_cookie_name,
		$cookie_data,
		$expire,
		$this->cookie_path,
		$this->cookie_domain,
		$this->cookie_secure
		);
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

	function _sess_gc()
	{
		return;
	}


}
