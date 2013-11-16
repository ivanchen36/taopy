<?php
if (!defined('APP_PATH')) exit('No direct script access allowed');
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
function strip_slashes($str)
{
	if (is_array($str))
	{
		foreach ($str as $key => $val)
		{
			$str[$key] = strip_slashes($val);
		}
	}
	else
	{
		$str = stripslashes($str);
	}

	return $str;
}

function slash_item($item)
{
	if(!isset($item))
	{
		return FALSE;
	}
	if(trim($item) == '')
	{
		return '';
	}

	return rtrim($item, '/').'/';
}

function filename_check($filename){
	preg_match("/^([a-zA-Z0-9_]+)\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG)$/", $filename, $matches);
	return $matches?true:false;
}

function base_url($uri = '')
{
	if (isset($_SERVER['HTTP_HOST']))
	{
		$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
		$base_url .= '://'. $_SERVER['HTTP_HOST'];
		$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	}

	else
	{
		$base_url = 'http://localhost/';
	}
	return slash_item($base_url).ltrim($uri,'/');
}

function host_url($uri = '') {
	if (isset($_SERVER['HTTP_HOST']))
	{
		$host_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
		$host_url .= '://'. $_SERVER['HTTP_HOST'];
		//$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	}

	else
	{
		$host_url = 'http://localhost';
	}
	return $host_url.ltrim($uri,'');
}

function random_string($type = 'alnum', $len = 8)
{
	switch($type)
	{
		case 'basic'	: return mt_rand();
		break;
		case 'alnum'	:
		case 'numeric'	:
		case 'nozero'	:
		case 'alpha'	:

			switch ($type)
			{
				case 'alpha'	:	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
				case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
				case 'numeric'	:	$pool = '0123456789';
				break;
				case 'nozero'	:	$pool = '123456789';
				break;
			}

			$str = '';
			for ($i=0; $i < $len; $i++)
			{
				$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
			}
			return $str;
			break;
		case 'unique'	:
		case 'md5'		:

			return md5(uniqid(mt_rand()));
			break;
	}
}


function createPages($pager,$controller,$action,$args){
	if(!$pager)
	return '';
	$des = T('total').' '.$pager['total_count'].' '.T('items').', '.T('total').' '.$pager['total_page'].' '.T('page').' ('.$pager['page_size'].'/'.T('page').'):';
	if ($pager['current_page'] != $pager['first_page']){
		$args['page'] = $pager['first_page'];
		$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('first_page').'</a> | ';
		$args['page'] = $pager['prev_page'];
		$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('prev_page').'</a> | ';
	}

	foreach ($pager['all_pages'] as $thepage){
		if ($thepage != $pager['current_page']) {
			$args['page'] = $thepage;
			$des .= '<a href="'.spUrl($controller,$action,$args).'">'.$thepage.'</a> ';;
		}else{
			$des .= '<b>'.$thepage.'</b>';
		}
	}

	if ($pager['current_page'] != $pager['last_page']){
		$args['page'] = $pager['next_page'];
		$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('next_page').'</a> | ';
		$args['page'] = $pager['last_page'];
		$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('last_page').'</a>';
	}

	return $des;
}

function createTPages($pager,$controller,$action,$args){
	if(!$pager||$pager['total_page']==1)
	return '';
	$des = '<div class="pagination"><ul>';
	if ($pager['current_page'] != $pager['first_page']&&$pager['prev_page']>0){
		$args['page'] = $pager['prev_page'];
		$des .= '<li><a href="'.spUrl($controller,$action,$args).'">'.T('prev_page').'</a></li>';
	}

	foreach ($pager['all_pages'] as $thepage){
		if ($thepage != $pager['current_page']) {
			$args['page'] = $thepage;
			$des .= '<li><a href="'.spUrl($controller,$action,$args).'">'.$thepage.'</a></li>';;
		}else{
			$des .= '<li class="active"><a href="#">'.$thepage.'</a></li>';
		}
	}

	if ($pager['current_page'] != $pager['last_page']){
		$args['page'] = $pager['next_page'];
		$des .= '<li><a href="'.spUrl($controller,$action,$args).'">'.T('next_page').'</a></li>';
	}
	$des .= '</ul></div>';
	return $des;
}

function sysSortArray($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR")
{
	if(!is_array($ArrayData))
	{
		return $ArrayData;
	}

	// Get args number.
	$ArgCount = func_num_args();

	// Get keys to sort by and put them to SortRule array.
	for($I = 1;$I < $ArgCount;$I ++)
	{
		$Arg = func_get_arg($I);
		if(!eregi("SORT",$Arg))
		{
			$KeyNameList[] = $Arg;
			$SortRule[]    = '$'.$Arg;
		}
		else
		{
			$SortRule[]    = $Arg;
		}
	}

	// Get the values according to the keys and put them to array.
	foreach($ArrayData AS $Key => $Info)
	{
		foreach($KeyNameList AS $KeyName)
		{
			${$KeyName}[$Key] = $Info[$KeyName];
		}
	}

	// Create the eval string and eval it.
	$EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';
	eval ($EvalString);
	return $ArrayData;
}

function sysSubStr($string,$length,$append = false)
{
	if(strlen($string) <= $length )
	{
		return $string;
	}
	else
	{
		$i = 0;
		while ($i < $length)
		{
			$stringTMP = substr($string,$i,1);
			if ( ord($stringTMP) >=224 )
			{
				$stringTMP = substr($string,$i,3);
				$i = $i + 3;
			}
			elseif( ord($stringTMP) >=192 )
			{
				$stringTMP = substr($string,$i,2);
				$i = $i + 2;
			}
			else
			{
				$i = $i + 1;
			}
			$stringLast[] = $stringTMP;
		}
		$stringLast = implode("",$stringLast);
		if($append)
		{
			$stringLast .= "…";
		}
		return $stringLast;
	}
}

function friendlyDate($timestamp, $formats = null)
{

	$_DATE_FORMAT = array(
    'DAY'           => T('DAY'),  
    'DAY_HOUR'      => T('DAY_HOUR'),  
    'HOUR'          => T('HOUR'),  
    'HOUR_MINUTE'   => T('HOUR_MINUTE'),  
    'MINUTE'        => T('MINUTE'),  
    'MINUTE_SECOND' => T('MINUTE_SECOND'),  
    'SECOND'        => T('SECOND'),  
	);

	if ($formats == null) {
		$formats = $_DATE_FORMAT;
	}
	/* 计算出时间差 */
	$seconds = time() - $timestamp;
	$minutes = floor($seconds / 60);
	$hours   = floor($minutes / 60);
	$days    = floor($hours / 24);

	if ($days > 0 && $days<=3) {
		$diffFormat = 'DAY';
	} else if($days > 3){
		return date('Y-m-d',$timestamp);
	} else {
		$diffFormat = ($hours > 0) ? 'HOUR' : 'MINUTE';
		if ($diffFormat == 'HOUR') {
			$diffFormat .= ($minutes > 0 && ($minutes - $hours * 60) > 0) ? '_MINUTE' : '';
		} else {
			$diffFormat = (($seconds - $minutes * 60) > 0 && $minutes > 0)
			? $diffFormat.'_SECOND' : 'SECOND';
		}
	}

	$dateDiff = null;
	switch ($diffFormat) {
		case 'DAY':
			$dateDiff = sprintf($formats[$diffFormat], $days);
			break;
		case 'DAY_HOUR':
			$dateDiff = sprintf($formats[$diffFormat], $days, $hours - $days * 60);
			break;
		case 'HOUR':
			$dateDiff = sprintf($formats[$diffFormat], $hours);
			break;
		case 'HOUR_MINUTE':
			$dateDiff = sprintf($formats[$diffFormat], $hours, $minutes - $hours * 60);
			break;
		case 'MINUTE':
			$dateDiff = sprintf($formats[$diffFormat], $minutes);
			break;
		case 'MINUTE_SECOND':
			$dateDiff = sprintf($formats[$diffFormat], $minutes, $seconds - $minutes * 60);
			break;
		case 'SECOND':
			$dateDiff = sprintf($formats[$diffFormat], $seconds);
			break;
	}
	return $dateDiff;
}
/*
 * count() always return a wrong value
 * */
function array_length($arr){
	$num = 0;
	if($arr&&is_array($arr)&&!empty($arr)){
		foreach ($arr as $value) {
			if($value!=null&&$value!=''){
				$num++;
			}
		}
	}
	return $num;
}

function delete_html($str)
{
	$str = trim($str);
	$str = strip_tags($str,"");
	$str = ereg_replace("\t","",$str);
	$str = ereg_replace("\r\n","",$str);
	$str = ereg_replace("\r","",$str);
	$str = ereg_replace("\n","",$str);
	$str = ereg_replace(" "," ",$str);
	return trim($str);
}


function useravatar($uid,$type){
	$uid = abs(intval($uid));
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$info = array();
	$dir = 'data/avatars/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
	$filename = substr($uid, -2).'_avatar';
	return base_url().$dir.$filename."_{$type}.jpg";
}

function array_to_str($arr,$coma=','){
	$pro = array();
	foreach ($arr as $k=>$v) {
		if($k!=null&&$k!=''&&$v!=null&&$v!='')
		$pro[] = "{$k}:{$v}";
	}
	$text = implode(",", $pro);
	return $text;
}

function str_to_arr($str,$coma=','){
	$arr = array();
	$f_array = explode($coma, $str);
	foreach ($f_array as $f) {
		$s_array = explode(':', $f);
		$arr[$s_array[0]] = $s_array[1];
	}
	return $arr;
}

function str_to_arr_list($array_str){
	$arr = array();
	if($array_str){
		$f_array = explode('|', $array_str);
		if(is_array($f_array)){
			foreach ($f_array as $f) {
				array_push($arr, str_to_arr($f,$coma=','));
			}
		}else{
			array_push($arr, $this->str_to_arr(str_replace('|', '', $view_history),$coma=','));
		}
		return $arr;
	}else{
		return array();
	}
}

function arr_list_to_str($array_list){
	$str = array();
	foreach ($array_list as $v) {
		$str[] = array_to_str($v);
	}
	$text = implode("|", $str);
	return $text;
}

function dhtmlspecialchars($string, $flags = null) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val, $flags);
		}
	} else {
		if($flags === null) {
			$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
			if(strpos($string, '&amp;#') !== false) {
				$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
			}
		} else {
			if(PHP_VERSION < '5.4.0') {
				$string = htmlspecialchars($string, $flags);
			} else {
				if(strtolower(CHARSET) == 'utf-8') {
					$charset = 'UTF-8';
				} else {
					$charset = 'ISO-8859-1';
				}
				$string = htmlspecialchars($string, $flags, $charset);
			}
		}
	}
	return $string;
}

function deparse_message(&$message,$needbr=false) {
	$message = str_replace(array('[/at]'), array(''),preg_replace(array("/\[at=(\d+?)\]/i"),array(""), $message));
	return $message;
}

function parse_message(&$message,$needbr=false) {
	$ptx_smile = spClass('ptx_smile');
	$smiles = $ptx_smile->getSmilies();
	$message = preg_replace($smiles['searcharray'], $smiles['replacearray'], $message, 5);
	$userlink = spUrl('pub','index',array('uid'=>'\\1'));
	$taglink = spUrl('pin','index',array('tag'=>'\\1'));
	$message = preg_replace("/#([^#\r\n]+?)#/i", "<a href=\"$taglink\" target=\"_blank\">#\\1#</a>", $message, 5);
	$br = $needbr?'<br/>':'';
	$message = str_replace(array('[/at]',"\n"), array('</a>',$br),preg_replace(array("/\[at=(\d+?)\]/i"),array("<a href=\"$userlink\"  data-user-id=\"\\1\" data-user-profile=\"1\" target=\"_blank\">"), $message));
	return $message;
}

function unicode2utf($str){
	$result = '';
	if($str < 0x80){
		$result .= $str;
	}elseif($str < 0x800){
		$result .= chr(0xC0 | $str>>6);
		$result .= chr(0x80 | $str & 0x3F);
	}elseif($str < 0x10000){
		$result .= chr(0xE0 | $str>>12);
		$result .= chr(0x80 | $str>>6 & 0x3F);
		$result .= chr(0x80 | $str & 0x3F);
	} elseif($str < 0x200000) {
		$result .= chr(0xF0 | $str>>18);
		$result .= chr(0x80 | $str>>12 & 0x3F);
		$result .= chr(0x80 | $str>>6 & 0x3F);
		$result .= chr(0x80 | $str & 0x3F);
	}
	return $result;
}


function entities2utf8($unicode_c){
	$replacedString = preg_replace("/\\\\u([0-9abcdef]{4})/", "&#x$1;", $unicode_c);
	$unicode_c = mb_convert_encoding($replacedString, 'UTF-8', 'HTML-ENTITIES');
	return $unicode_c;
}

function rand_pop($arr){
	if($length = array_length($arr)){
		$i=rand(0,$length-1);
		return $arr[$i];
	}
	return null;
}
function get_contents($url,$retries=3){
	if(function_exists('curl_init')){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1");
		curl_setopt($ch, CURLOPT_AUTOREFERER,true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$content = false;
		while (($content === false) && (--$retries > 0))
		{
			$content = curl_exec($ch);
		}
		curl_close($ch);
	}else{
		set_time_limit(0);
		$content = false;
		while (($content === false) && (--$retries > 0))
		{
			$content = @file_get_contents($url);
		}

	}
	if($content){
		if($dehtml = pgzdecode($content))
			$content = $dehtml;
	}
	return $content;
}


function pfsget($path='/', $host='', $user_agent=''){
	if(!$path || !$host) return false;
	$user_agent = $user_agent ? $user_agent : "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1";

	$out = <<<HEADER
GET $path HTTP/1.1
Host: $host
User-Agent: $user_agent
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-cn,zh;q=0.5
Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n\r\n
HEADER;
	$fp = @fsockopen($host, 80, $errno, $errstr, 10);
	if (!$fp)  return false;
	if(!fputs($fp, $out)) return false;
	while ( !feof($fp) ) {
		$html .= fgets($fp, 1024);
	}
	fclose($fp);
	if($dehtml = pgzdecode($html))
		return $dehtml;
	else
		return $html;
}


function pgzdecode($data) {
	$len = strlen ( $data );
	if ($len < 18 || strcmp ( substr ( $data, 0, 2 ), "\x1f\x8b" )) {
		return null; // Not GZIP format (See RFC 1952)
	}
	$method = ord ( substr ( $data, 2, 1 ) ); // Compression method
	$flags = ord ( substr ( $data, 3, 1 ) ); // Flags
	if ($flags & 31 != $flags) {
		// Reserved bits are set -- NOT ALLOWED by RFC 1952
		return null;
	}
	// NOTE: $mtime may be negative (PHP integer limitations)
	$mtime = unpack ( "V", substr ( $data, 4, 4 ) );
	$mtime = $mtime [1];
	$xfl = substr ( $data, 8, 1 );
	$os = substr ( $data, 8, 1 );
	$headerlen = 10;
	$extralen = 0;
	$extra = "";
	if ($flags & 4) {
		// 2-byte length prefixed EXTRA data in header
		if ($len - $headerlen - 2 < 8) {
			return false; // Invalid format
		}
		$extralen = unpack ( "v", substr ( $data, 8, 2 ) );
		$extralen = $extralen [1];
		if ($len - $headerlen - 2 - $extralen < 8) {
			return false; // Invalid format
		}
		$extra = substr ( $data, 10, $extralen );
		$headerlen += 2 + $extralen;
	}

	$filenamelen = 0;
	$filename = "";
	if ($flags & 8) {
		// C-style string file NAME data in header
		if ($len - $headerlen - 1 < 8) {
			return false; // Invalid format
		}
		$filenamelen = strpos ( substr ( $data, 8 + $extralen ), chr ( 0 ) );
		if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
			return false; // Invalid format
		}
		$filename = substr ( $data, $headerlen, $filenamelen );
		$headerlen += $filenamelen + 1;
	}

	$commentlen = 0;
	$comment = "";
	if ($flags & 16) {
		// C-style string COMMENT data in header
		if ($len - $headerlen - 1 < 8) {
			return false; // Invalid format
		}
		$commentlen = strpos ( substr ( $data, 8 + $extralen + $filenamelen ), chr ( 0 ) );
		if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
			return false; // Invalid header format
		}
		$comment = substr ( $data, $headerlen, $commentlen );
		$headerlen += $commentlen + 1;
	}

	$headercrc = "";
	if ($flags & 1) {
		// 2-bytes (lowest order) of CRC32 on header present
		if ($len - $headerlen - 2 < 8) {
			return false; // Invalid format
		}
		$calccrc = crc32 ( substr ( $data, 0, $headerlen ) ) & 0xffff;
		$headercrc = unpack ( "v", substr ( $data, $headerlen, 2 ) );
		$headercrc = $headercrc [1];
		if ($headercrc != $calccrc) {
			return false; // Bad header CRC
		}
		$headerlen += 2;
	}

	// GZIP FOOTER - These be negative due to PHP's limitations
	$datacrc = unpack ( "V", substr ( $data, - 8, 4 ) );
	$datacrc = $datacrc [1];
	$isize = unpack ( "V", substr ( $data, - 4 ) );
	$isize = $isize [1];

	// Perform the decompression:
	$bodylen = $len - $headerlen - 8;
	if ($bodylen < 1) {
		// This should never happen - IMPLEMENTATION BUG!
		return null;
	}
	$body = substr ( $data, $headerlen, $bodylen );
	$data = "";
	if ($bodylen > 0) {
		switch ($method) {
			case 8 :
				// Currently the only supported compression method:
				$data = gzinflate ( $body );
				break;
			default :
				// Unknown compression method
				return false;
		}
	} else {
		//...
	}

	if ($isize != strlen ( $data ) || crc32 ( $data ) != $datacrc) {
		// Bad format!  Length or CRC doesn't match!
		return false;
	}
	return $data;
}

function safeEncoding($string,$outEncoding ='UTF-8'){
	$encoding = "UTF-8";
	for($i=0;$i<strlen($string);$i++){
		if(ord($string{$i})<128)
			continue;
		if((ord($string{$i})&224)==224) {
			$char = $string{++$i};
			if((ord($char)&128)==128){
				$char = $string{++$i};
				if((ord($char)&128)==128){
					$encoding = "UTF-8";
					break;
				}
			}
		}
		if((ord($string{$i})&192)==192){
			$char = $string{++$i};
			if((ord($char)&128)==128){
				$encoding = "GB2312";
				break;
			}
		}
	}

	if(strtoupper($encoding) == strtoupper($outEncoding))
		return $string;
	else
		return iconv($encoding,$outEncoding,$string);
}