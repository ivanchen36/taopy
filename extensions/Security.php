<?php if (!defined('APP_PATH')) exit('No direct script access allowed');
/**
 * 		Primary from CI
 *     	Modified By [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class Security {

	protected $_xss_hash			= '';
	protected $_csrf_hash			= '';
	protected $_csrf_expire			= 7200;
	protected $_csrf_token_name		= 'ci_csrf_token';
	protected $_csrf_cookie_name	= 'ci_csrf_token';
	protected $_never_allowed_str = array(
					'document.cookie'	=> '[removed]',
					'document.write'	=> '[removed]',
					'.parentNode'		=> '[removed]',
					'.innerHTML'		=> '[removed]',
					'window.location'	=> '[removed]',
					'-moz-binding'		=> '[removed]',
					'<!--'				=> '&lt;!--',
					'-->'				=> '--&gt;',
					'<![CDATA['			=> '&lt;![CDATA[',
					'<comment>'			=> '&lt;comment&gt;'
	);
	
	protected $_never_allowed_regex = array(
					"javascript\s*:"			=> '[removed]',
					"expression\s*(\(|&\#40;)"	=> '[removed]', // CSS and IE
					"vbscript\s*:"				=> '[removed]', // IE, surprise!
					"Redirect\s+302"			=> '[removed]'
	);
	
	var $cookie_prefix				= '';
	var $cookie_path				= '/';
	var $cookie_domain				= '';
	var $cookie_secure				= FALSE;

	public function __construct()
	{
		$params = spExt('sessionAndCookie');
		if(is_array($params)){

			foreach (array('cookie_path', 'cookie_domain', 'cookie_secure', 'cookie_prefix') as $key)
			{
				$this->$key = (isset($params[$key])) ? $params[$key] : $this->$key;
			}
		}
		// Append application specific cookie prefix
		if ($this->cookie_prefix)
		{
			$this->_csrf_cookie_name = $this->cookie_prefix.$this->_csrf_cookie_name;
		}
		$this->_csrf_set_hash();

	}

	public function csrf_verify()
	{
		// If no POST data exists we will set the CSRF cookie
		if (count($_POST) == 0)
		{
			return $this->csrf_set_cookie();
		}

		// Do the tokens exist in both the _POST and _COOKIE arrays?
		if ( ! isset($_POST[$this->_csrf_token_name]) OR
			 ! isset($_COOKIE[$this->_csrf_cookie_name]))
		{
			$this->csrf_show_error();
		}

		// Do the tokens match?
		if ($_POST[$this->_csrf_token_name] != $_COOKIE[$this->_csrf_cookie_name])
		{
			$this->csrf_show_error();
		}

		// We kill this since we're done and we don't want to
		// polute the _POST array
		unset($_POST[$this->_csrf_token_name]);

		// Nothing should last forever
		unset($_COOKIE[$this->_csrf_cookie_name]);
		$this->_csrf_set_hash();
		$this->csrf_set_cookie();


		return $this;
	}

	public function csrf_set_cookie()
	{
		$expire = time() + $this->_csrf_expire;
		$secure_cookie = ($this->cookie_secure === TRUE) ? 1 : 0;

		if ($secure_cookie)
		{
			$req = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : FALSE;

			if ( ! $req OR $req == 'off')
			{
				return FALSE;
			}
		}

		setcookie($this->_csrf_cookie_name, $this->_csrf_hash, $expire, $this->cookie_path, $this->cookie_domain, $secure_cookie);

		return $this;
	}

	public function csrf_show_error()
	{
		//show_error('The action you have requested is not allowed.');
	}

	public function get_csrf_hash()
	{
		return $this->_csrf_hash;
	}

	public function get_csrf_token_name()
	{
		return $this->_csrf_token_name;
	}

	public function xss_clean($str, $is_image = FALSE)
	{
		
		if (is_array($str))
		{
			while (list($key) = each($str))
			{
				$str[$key] = $this->xss_clean($str[$key]);
			}

			return $str;
		}

		
		$str = $this->remove_invisible_characters($str);

		// Validate Entities in URLs
		$str = $this->_validate_entities($str);

		
		$str = rawurldecode($str);

		$str = preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);

		$str = preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array($this, '_decode_entity'), $str);

		$str = $this->remove_invisible_characters($str);

		if (strpos($str, "\t") !== FALSE)
		{
			$str = str_replace("\t", ' ', $str);
		}

		$converted_string = $str;

		$str = $this->_do_never_allowed($str);

		if ($is_image === TRUE)
		{
			$str = preg_replace('/<\?(php)/i', "&lt;?\\1", $str);
		}
		else
		{
			$str = str_replace(array('<?', '?'.'>'),  array('&lt;?', '?&gt;'), $str);
		}

		$words = array(
				'javascript', 'expression', 'vbscript', 'script',
				'applet', 'alert', 'document', 'write', 'cookie', 'window'
			);

		foreach ($words as $word)
		{
			$temp = '';

			for ($i = 0, $wordlen = strlen($word); $i < $wordlen; $i++)
			{
				$temp .= substr($word, $i, 1)."\s*";
			}

			$str = preg_replace_callback('#('.substr($temp, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
		}

		do
		{
			$original = $str;

			if (preg_match("/<a/i", $str))
			{
				$str = preg_replace_callback("#<a\s+([^>]*?)(>|$)#si", array($this, '_js_link_removal'), $str);
			}

			if (preg_match("/<img/i", $str))
			{
				$str = preg_replace_callback("#<img\s+([^>]*?)(\s?/?>|$)#si", array($this, '_js_img_removal'), $str);
			}

			if (preg_match("/script/i", $str) OR preg_match("/xss/i", $str))
			{
				$str = preg_replace("#<(/*)(script|xss)(.*?)\>#si", '[removed]', $str);
			}
		}
		while($original != $str);

		unset($original);

		$str = $this->_remove_evil_attributes($str, $is_image);

		$naughty = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|isindex|layer|link|meta|object|plaintext|style|script|textarea|title|video|xml|xss';
		$str = preg_replace_callback('#<(/*\s*)('.$naughty.')([^><]*)([><]*)#is', array($this, '_sanitize_naughty_html'), $str);

		$str = preg_replace('#(alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
		$str = $this->_do_never_allowed($str);

		if ($is_image === TRUE)
		{
			return ($str == $converted_string) ? TRUE: FALSE;
		}

		return $str;
	}

	public function xss_hash()
	{
		if ($this->_xss_hash == '')
		{
			mt_srand();
			$this->_xss_hash = md5(time() + mt_rand(0, 1999999999));
		}

		return $this->_xss_hash;
	}

	public function entity_decode($str, $charset='UTF-8')
	{
		if (stristr($str, '&') === FALSE)
		{
			return $str;
		}

		$str = html_entity_decode($str, ENT_COMPAT, $charset);
		$str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
		return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
	}

	public function sanitize_filename($str, $relative_path = FALSE)
	{
		$bad = array(
						"../",
						"<!--",
						"-->",
						"<",
						">",
						"'",
						'"',
						'&',
						'$',
						'#',
						'{',
						'}',
						'[',
						']',
						'=',
						';',
						'?',
						"%20",
						"%22",
						"%3c",		// <
						"%253c",	// <
						"%3e",		// >
						"%0e",		// >
						"%28",		// (
						"%29",		// )
						"%2528",	// (
						"%26",		// &
						"%24",		// $
						"%3f",		// ?
						"%3b",		// ;
						"%3d"		// =
					);

		if ( ! $relative_path)
		{
			$bad[] = './';
			$bad[] = '/';
		}

		$str = $this->remove_invisible_characters($str, FALSE);
		return stripslashes(str_replace($bad, '', $str));
	}

	protected function _compact_exploded_words($matches)
	{
		return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
	}

	protected function _remove_evil_attributes($str, $is_image)
	{
		$evil_attributes = array('on\w*', 'style', 'xmlns', 'formaction');

		if ($is_image === TRUE)
		{
			unset($evil_attributes[array_search('xmlns', $evil_attributes)]);
		}
		
		do {
			$count = 0;
			$attribs = array();
			
			preg_match_all("/(".implode('|', $evil_attributes).")\s*=\s*([^\s]*)/is",  $str, $matches, PREG_SET_ORDER);
			
			foreach ($matches as $attr)
			{
				$attribs[] = preg_quote($attr[0], '/');
			}
			
			preg_match_all("/(".implode('|', $evil_attributes).")\s*=\s*(\042|\047)([^\\2]*?)(\\2)/is",  $str, $matches, PREG_SET_ORDER);

			foreach ($matches as $attr)
			{
				$attribs[] = preg_quote($attr[0], '/');
			}

			if (count($attribs) > 0)
			{
				$str = preg_replace("/<(\/?[^><]+?)([^A-Za-z\-])(".implode('|', $attribs).")([\s><])([><]*)/i", '<$1$2$4$5', $str, -1, $count);
			}
			
		} while ($count);
		
		return $str;
	}

	protected function _sanitize_naughty_html($matches)
	{
		// encode opening brace
		$str = '&lt;'.$matches[1].$matches[2].$matches[3];

		// encode captured opening or closing brace to prevent recursive vectors
		$str .= str_replace(array('>', '<'), array('&gt;', '&lt;'),
							$matches[4]);

		return $str;
	}

	protected function _js_link_removal($match)
	{
		$attributes = $this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]));

		return str_replace($match[1], preg_replace("#href=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
	}

	protected function _js_img_removal($match)
	{
		$attributes = $this->_filter_attributes(str_replace(array('<', '>'), '', $match[1]));

		return str_replace($match[1], preg_replace("#src=.*?(alert\(|alert&\#40;|javascript\:|livescript\:|mocha\:|charset\=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si", "", $attributes), $match[0]);
	}

	protected function _convert_attribute($match)
	{
		return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
	}

	protected function _filter_attributes($str)
	{
		$out = '';

		if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
		{
			foreach ($matches[0] as $match)
			{
				$out .= preg_replace("#/\*.*?\*/#s", '', $match);
			}
		}

		return $out;
	}

	protected function _decode_entity($match)
	{
		return $this->entity_decode($match[0], strtoupper('UTF-8'));
	}

	protected function _validate_entities($str)
	{

		$str = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', $this->xss_hash()."\\1=\\2", $str);

		$str = preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

		$str = preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);
		$str = str_replace($this->xss_hash(), '&', $str);

		return $str;
	}

	protected function _do_never_allowed($str)
	{
		foreach ($this->_never_allowed_str as $key => $val)
		{
			$str = str_replace($key, $val, $str);
		}

		foreach ($this->_never_allowed_regex as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		return $str;
	}

	protected function _csrf_set_hash()
	{
		if ($this->_csrf_hash == '')
		{
			// If the cookie exists we will use it's value.
			// We don't necessarily want to regenerate it with
			// each page load since a page could contain embedded
			// sub-pages causing this feature to fail
			if (isset($_COOKIE[$this->_csrf_cookie_name]) &&
				$_COOKIE[$this->_csrf_cookie_name] != '')
			{
				return $this->_csrf_hash = $_COOKIE[$this->_csrf_cookie_name];
			}

			return $this->_csrf_hash = md5(uniqid(rand(), TRUE));
		}

		return $this->_csrf_hash;
	}
	
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}

}
