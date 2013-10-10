<?php  if (!defined('APP_PATH')) exit('No direct script access allowed');
/**
 * 		Primary from CI
 *     	Modified By [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class Options {

	var $config = array();


	public function __construct() {
	}


	function load($filename = '')
	{
		if (file_exists(APP_PATH.'/'.$filename))
		{
			include(APP_PATH.'/'.$filename);
			$this->config = $user_config;
			return true;
		}else{
			return false;
		}
	}


	function save($filename)
	{
		$out = "<?php".
				"\n\n\$user_config = " . $this->arrayeval($this->config).";\n";
		
		write_file(APP_PATH.'/'.$filename, $out);
	}


	function out()
	{
		return $this->config;
	}

	function get_item($item, $index = '')
	{
		if ($index == '')
		{
			if ( ! isset($this->config[$item]))
			{
				return FALSE;
			}

			$pref = $this->config[$item];
		}
		else
		{
			if ( ! isset($this->config[$index]))
			{
				return FALSE;
			}

			if ( ! isset($this->config[$index][$item]))
			{
				return FALSE;
			}

			$pref = $this->config[$index][$item];
		}

		return $pref;
	}
	

	function set_item($item, $value)
	{
		$this->config[$item] = $value;
	}


	function arrayeval($array, $level = 0) {
		if(!is_array($array)) {
			return "'".$array."'";
		}
		if(is_array($array) && function_exists('var_export')) {
			return var_export($array, true);
		}

		$space = '';
		for($i = 0; $i <= $level; $i++) {
			$space .= "\t";
		}
		$evaluate = "Array\n$space(\n";
		$comma = $space;
		if(is_array($array)) {
			foreach($array as $key => $val) {
				$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
				$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
				if(is_array($val)) {
					$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
				} else {
					$evaluate .= "$comma$key => $val";
				}
				$comma = ",\n$space";
			}
		}
		$evaluate .= "\n$space)";
		return $evaluate;
	}
}