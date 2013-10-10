<?php if (!defined('APP_PATH')) exit('No direct script access allowed');
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */

class Channel_others
{
	private $channel='others';

	function __construct($info=NULL)
	{
	}
	
	function get_headers($url){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($curl);
		if ($result !== false){
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($statusCode == 200)
			{
				$content['content_type'] = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
				$content['content_length'] = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
				switch($content['content_type']){
					case 'image/jpeg':
					case 'image/pjpeg':
						$content['ext']='jpg';
						break;
					case 'image/png':
					case 'image/x-png':
						$content['ext']='png';
						break;
					case 'image/gif':
						$content['ext']='gif';
						break;
					case 'image/bmp':
						$content['ext']='bmp';
						break;
					default:
						$content['ext']='html';
				}
			}
			curl_close($curl);
			return $content;
		}
	}
	
	function create_image_data($url,$headers){
		$img=$this->create_image($url, $headers);
		if($img){
			$images = array();
			array_push($images,$img);
			return $images;
		}
		return false;
	}
	
	function create_image($url,$headers){
		if($headers['content_length']<15240){
			return false;
		}
		$img['src'] = $url;
		$img['thumburl'] = $url;
		$img['ext'] = $headers['ext'];
		$img['filesize'] = $headers['content_length'];
		return $img;
	}
	
	
	public function fetch_images($url){
		$headers = $this->get_headers($url);
		if($headers){
			if($headers['ext']=='html'){
				$hdata = $this->fetch_images_from_html($url);
			}else{
				$hdata['images'] = $this->create_image_data($url,$headers);
			}
	
			$data = array();
			$data['title'] = safeEncoding($hdata['title']);
			$data['type'] = 'images';
			$data['url'] = $url;
			$data['images'] = $hdata['images'];
			return $data;
		}
		return false;
	}
	
	function fetch_images_from_html($url){
		$parsed_url = parse_url($url);
		$host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
		$port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
		$html = get_contents($url);
		$content = safeEncoding(stripslashes($html));
		//$pattern = "/<img[^>]*[^\.][src|file]\=[\"|\'](([^(>|\"|\'|\s)]+))[\"|\']/i";
		$pattern = "/<img([^>]*)\s*(src|file)=('|\")([^'\"]+)('|\")/i";
		//$pattern = "/<img[^>]*[^\.][src|file]\=[\"|\'](([^(>|\"|\')]*)(jpg|png|jpeg|gif|JPG|PNG|JPEG|GIF)(\?[^(>|\"|\')]*)?)[\"|\']/iU";
		preg_match("/<title>([^<]*)<\/title>/i", $content, $tmatches);
		$data['title'] = $tmatches[1];
	
		$images = array();
		$imageadded = array();
		preg_match_all($pattern, $content, $matches);
		$matchimgs = ($matches[4])?array_unique($matches[4]):array();
		foreach ($matchimgs as $value) {
			if(stripos($value,'http://') === false && stripos($value,'https://') === false){
				if(stripos($value,'//') == 0){
					$value = 'http:'.$value;
				}
				else{
					$value = 'http://'.$host.$port.'/'.$value;
				}
			}
			/*$imageheaders = $this->get_headers($value);
			 if($imageheaders&&!in_array($value, $imageadded)){
			array_push($imageadded,$value);
			$img=$this->create_image($value, $imageheaders);
			if($img){
			array_push($images,$img);
			}
			}*/
			if(!in_array($value, $imageadded)){
				array_push($imageadded,$value);
				$img['src'] = $value;
				$img['thumburl'] = $value;
				array_push($images,$img);
			}
		}
		$data['images'] = $images;
		return $data;
	}

	/*public function fetch_images($url){
		$html = $this->fetch_curl($url);
		$content = stripslashes($html);
		//$pattern = "/<img[^>]*src\=\"(([^>]*)(jpg|png|jpeg))\"/";   //获取所有图片标签的全部信息
		//$pattern = "/\<img\s[^\>]*?src=(\'|\")(.*?\.(jpg|jpeg|png))\\1/im";
		//$pattern = "/<img[^>]*[^\.]src\=[\"|\'](([^>]*)(jpg|png|jpeg))[\"|\']/iU";
		//$pattern = "/<img[^>]*[src|file]\=[\"\'](([^>]*)(jpg|png|jpeg))[\"\']/";
		$pattern = "/<img[^>]*[^\.][src|file]\=[\"|\'](([^>]*)(jpg|png|jpeg|JPG|PNG|JPEG))[\"|\']/iU";
		$images = array();
		preg_match_all($pattern, $content, $matches);
		foreach ($matches[1] as $value) {  					//$matches[1]中就是所想匹配的结果,结果为数组
			//判断是否为绝对路径，如果不是，为其补全为绝对地址
			if(stripos($value,'http://') === false){
				$parsed_url = parse_url($url);
				$host = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
				$port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
				$value = 'http://'.$host.$port.'/'.$value;
			}
			//log_message('error','img src='.$value);
			//$metadata = $this->get_image_size($value);
			//if($metadata['width'] > 150 || $metadata['height'] > 150) {
				$img['src'] = $value;
				//$img['width'] = $metadata['width'];
				//$img['height'] = $metadata['height'];
				array_push($images,$img);
			//}
		}
		
		$data = array();
		$data['images'] = $images; 
		$data['type'] = 'images';
		$data['url'] = $url;
		
		return $data;
	}*/

	/**
	 * Fetch file content by curl
	 *
	 * @access	public
	 * @return	bool
	 */
	function fetch_curl($url, $post = null, $retries = 3)
	{
		$curl = curl_init($url);

		if (is_resource($curl) === true)
		{
			curl_setopt($curl, CURLOPT_FAILONERROR, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			if (isset($post) === true)
			{
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, (is_array($post) === true) ? http_build_query($post, '', '&') : $post);
			}

			$result = false;

			while (($result === false) && (--$retries > 0))
			{
				$result = curl_exec($curl);
			}

			curl_close($curl);
		}

		return $result;
	}

	/**
	 * Get Images Size
	 *
	 * @access	public
	 * @return	bool
	 */

	function get_image_size($url){
		$headers = array(
	    "Range: bytes=0-32768"
	    );
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    $raw =  curl_exec($curl);
	    curl_close($curl);

	    $im = @imagecreatefromstring($raw);
	    $size['width'] = @imagesx($im);
	    $size['height'] = @imagesy($im);
	    unset($raw,$im);
	    return $size;
	}

	//备用函数，暂未使用
	private function fetch_fgc($url, $post = null, $retries = 3)
	{
	    $http = array
	    (
	        'method' => 'GET',
	    );
	
	    if (isset($post) === true)
	    {
	        $http['method'] = 'POST';
	        $http['header'] = 'Content-Type: application/x-www-form-urlencoded';
	        $http['content'] = (is_array($post) === true) ? http_build_query($post, '', '&') : $post;
	    }
	
	    $result = false;
	
	    while (($result === false) && (--$retries > 0))
	    {
	        $result = @file_get_contents($url, false, stream_context_create(array('http' => $http)));
	    }
	
	    return $result;
	}


}
