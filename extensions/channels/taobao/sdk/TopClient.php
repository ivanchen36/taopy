<?php
include_once 'RequestCheckUtil.php';
class TopClient
{
	public $appkey;

	public $secretKey;

	public $gatewayUrl = "http://gw.api.taobao.com/router/rest";

	public $format = "xml";

	/** 是否打开入参check**/
	public $checkRequest = true;

	protected $signMethod = "md5";

	protected $apiVersion = "2.0";

	protected $sdkVersion = "top-sdk-php-20111114";

	protected function generateSign($params)
	{
		ksort($params);

		$stringToBeSigned = $this->secretKey;
		foreach ($params as $k => $v)
		{
			if("@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->secretKey;

		return strtoupper(md5($stringToBeSigned));
	}

	protected function curl($url, $postFields = null)
	{
        $ch = curl_init();
        $header[0] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: ";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 
        curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
        curl_setopt($ch, CURLOPT_REFERER, 'http://api.taobao.com/apitools/apiTools.htm?catId=4&apiName=taobao.item.get'); 
		curl_setopt($ch, CURLOPT_COOKIE, "ta_lvt_new=1384609725; cookie2=9f0bb8adc1ade8dd0942561bf73e55c6; t=b0d947613f74adfad6c8fd1e9068b864; v=0");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0");

		if (is_array($postFields) && 0 < count($postFields))
		{
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v)
			{
				if("@" != substr($v, 0, 1))//判断是不是文件上传
				{
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}
				else//文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_HTTPGET, true);
			//if ($postMultipart)
			//{
			//	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			//}
			//else
			//{
			//	curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			//}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch),0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}

	/*protected function logCommunicationError($apiName, $requestUrl, $errorCode, $responseTxt)
	{
		$localIp = isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : "CLI";
		$logger = new LtLogger;
		$logger->conf["log_file"] = rtrim(TOP_SDK_WORK_DIR, '\\/') . '/' . "logs/top_comm_err_" . $this->appkey . "_" . date("Y-m-d") . ".log";
		$logger->conf["separator"] = "^_^";
		$logData = array(
		date("Y-m-d H:i:s"),
		$apiName,
		$this->appkey,
		$localIp,
		PHP_OS,
		$this->sdkVersion,
		$requestUrl,
		$errorCode,
		str_replace("\n","",$responseTxt)
		);
		$logger->log($logData);
	}*/

	public function execute($request, $session = null)
	{
		//if($this->checkRequest) {
		//	try {
		//		$request->check();
		//	} catch (Exception $e) {
		//		$result->code = $e->getCode();
		//		$result->msg = $e->getMessage();
		//		return $result;
		//	}
		//}
		////组装系统参数
		//$sysParams["app_key"] = $this->appkey;
		//$sysParams["v"] = $this->apiVersion;
		//$sysParams["format"] = $this->format;
		//$sysParams["sign_method"] = $this->signMethod;
		//$sysParams["method"] = $request->getApiMethodName();
		//$sysParams["timestamp"] = date("Y-m-d H:i:s");
		//$sysParams["partner_id"] = $this->sdkVersion;
		//if (null != $session)
		//{
		//	$sysParams["session"] = $session;
		//}

		////获取业务参数
		//$apiParams = $request->getApiParas();

		////签名
		//$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));

		////系统参数放入GET请求串
		//$requestUrl = $this->gatewayUrl . "?";
		//foreach ($sysParams as $sysParamKey => $sysParamValue)
		//{
		//	$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		//}
		//$requestUrl = substr($requestUrl, 0, -1);

		//发起HTTP请求
        $requestUrl = "http://api.taobao.com/apitools/getResult.htm?format=json&method=taobao.item.get&restId=2&api_soure=0&app_key=%CF%B5%CD%B3%B7%D6%C5%E4&app_secret=%CF%B5%CD%B3%B7%D6%C5%E4&sip_http_method=GET&codeType=PHP&fields=detail_url,title,nick,pic_url,item_img.url,price&num_iid=" . $request->getNumIid() . "&track_iid=&session=&ua=059n%2BqZ9mgNqgJnCG0Yusy1z7bFvsu%2BxqMDow%3D%3D|nOiH84X%2BifCD%2Bo36gPuC91c%3D|neiHGXz1WexE7V%2Fkge6L%2FobjjOlz327Je8F3ErIS|mu6b9JHlk%2Bif5pXsm%2ByW7ZThnO%2Be6p3hmeOW5ZHimOya6ZPmleCFJQ%3D%3D|m%2B%2BT%2FGIWeQ1%2BB2gcawRhFogyj%2BSONpIBjTysA6vObs4%3D|mOOM6Yws|meWK74oq|luCPEWgdcgFuGmYVYwx3C38QZBhtHnEFdg1%2BEWUZbB9wBHcAc9Nz|l%2BOQ%2F5oEcQR%2BDXEegeSQ5p3qk%2BCZ7pnjmOGUK1gpXCpfKV4qUihRJlwqUCdQKlPMqca1FXDQ|lOOMEnfCatxky3zqQfNJLEM3QDdAL1gtWjVANEg8nDw%3D|leKNE3bDa91lyn3rQPJILUI2RT1HKF8jUD9KMEQwkDA%3D|kuWKFHHEbNpizXrsR%2FVPKkUxRTlNIlUjWDdBNUI1lTU%3D|k%2BuEGn8adeuS5Jj3j%2FqPL0AzXDlcM0c0TTZK6ko%3D|kOeU54j7lOGO%2Bo35luCY4o31ieac5Iv%2Bke2Y943im%2FSM45fhQQ%3D%3D";
        $this->format = "json";
		try
		{
			$resp = $this->curl($requestUrl, $apiParams);
            $resp = split("\^\|\^", $resp);
            $resp = $resp[1];
            $resp = str_replace("&quot;", '"', $resp);
		}
		catch (Exception $e)
		{
			//$this->logCommunicationError($sysParams["method"],$requestUrl,"HTTP_ERROR_" . $e->getCode(),$e->getMessage());
			$result->code = $e->getCode();
			$result->msg = $e->getMessage();
			return $result;
		}

		//解析TOP返回结果
		$respWellFormed = false;
		if ("json" == $this->format)
		{
			$respObject = json_decode($resp);
			if (null !== $respObject)
			{
				$respWellFormed = true;
				foreach ($respObject as $propKey => $propValue)
				{
					$respObject = $propValue;
				}
			}
		}
		else if("xml" == $this->format)
		{
			$respObject = @simplexml_load_string($resp);
			if (false !== $respObject)
			{
				$respWellFormed = true;
			}
		}

		//返回的HTTP文本不是标准JSON或者XML，记下错误日志
		if (false === $respWellFormed)
		{
			//$this->logCommunicationError($sysParams["method"],$requestUrl,"HTTP_RESPONSE_NOT_WELL_FORMED",$resp);
			$result->code = 0;
			$result->msg = "HTTP_RESPONSE_NOT_WELL_FORMED";
			return $result;
		}

		//如果TOP返回了错误码，记录到业务错误日志中
		/*if (isset($respObject->code))
		{
			$logger = new LtLogger;
			$logger->conf["log_file"] = rtrim(TOP_SDK_WORK_DIR, '\\/') . '/' . "logs/top_biz_err_" . $this->appkey . "_" . date("Y-m-d") . ".log";
			$logger->log(array(
				date("Y-m-d H:i:s"),
				$resp
			));
		}*/
		return $respObject;
	}

	public function exec($paramsArray)
	{
		if (!isset($paramsArray["method"]))
		{
			trigger_error("No api name passed");
		}
		$inflector = new LtInflector;
		$inflector->conf["separator"] = ".";
		$requestClassName = ucfirst($inflector->camelize(substr($paramsArray["method"], 7))) . "Request";
		if (!class_exists($requestClassName))
		{
			trigger_error("No such api: " . $paramsArray["method"]);
		}

		$session = isset($paramsArray["session"]) ? $paramsArray["session"] : null;

		$req = new $requestClassName;
		foreach($paramsArray as $paraKey => $paraValue)
		{
			$inflector->conf["separator"] = "_";
			$setterMethodName = $inflector->camelize($paraKey);
			$inflector->conf["separator"] = ".";
			$setterMethodName = "set" . $inflector->camelize($setterMethodName);
			if (method_exists($req, $setterMethodName))
			{
				$req->$setterMethodName($paraValue);
			}
		}
		return $this->execute($req, $session);
	}
}
