<?php
class Driver_QQ
{
	private $vendor='QQ';
	
	protected $info;
	
	function accessTokenURL()  { return 'https://graph.qq.com/oauth2.0/token'; }
	
	function authorizeURL()    { return 'https://graph.qq.com/oauth2.0/authorize'; }

	function uidURL()    { return 'https://graph.qq.com/oauth2.0/me'; }
	
	function userinfoURL()    { return 'https://graph.qq.com/user/get_user_info'; }
	
	function __construct($info)
	{
		$this->info = $info;
	}

	function goto_loginpage($state=NULL,$display=NULL)
	{
		$url = $this->authorizeURL()."?response_type=code&client_id="
		. $this->info['social_api_info']['APPKEY'] . "&redirect_uri=" .urlencode($this->info['social_api_info']['CALLBACK']);
		header('Location: '.$url);
		die;
	}

	function get_accesstoken()
	{
		if(isset($_REQUEST['code'])){
		
			$token_url = $this->accessTokenURL()."?grant_type=authorization_code"
			. "&client_id=" . $this->info['social_api_info']['APPKEY']
			. "&redirect_uri=" . urlencode($this->info['social_api_info']['CALLBACK'])
			. "&client_secret=" . $this->info['social_api_info']['APPSECRET']
			. "&code=" . $_REQUEST['code'];
           	$response = get_contents($token_url);
           	$result = array();
           	parse_str($response,$result);
			$token = array();
			$social_vendor_info = 'social_'.$this->vendor.'_info';
			$token[$social_vendor_info]['ACCESSTOKEN'] = $result['access_token'];
			return $token;
		}
		return NULL;
	}

	private function get_uid()
	{
		$social_vendor_info = 'social_'.$this->vendor.'_info';
		$graph_url = $this->uidURL()."?access_token="
		. $this->info[$social_vendor_info]['ACCESSTOKEN'];

		$str  = get_contents($graph_url);
		if (strpos($str, "callback") !== false)
		{
			$lpos = strpos($str, "(");
			$rpos = strrpos($str, ")");
			$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
		}

		$user = json_decode($str);
		if (isset($user->error))
		{
			throw new Exception("get access token failed." . $user->error.'<br />'.$user->error_description);
		}
		return $user->openid;
	}

	function get_userinfo()
	{
		$social_vendor_info = 'social_'.$this->vendor.'_info';
		if(!empty($this->info[$social_vendor_info]['ACCESSTOKEN']))
		{
			$get_user_info = $this->userinfoURL()."?"
			. "access_token=" . $this->info[$social_vendor_info]['ACCESSTOKEN']
			. "&oauth_consumer_key=" . $this->info['social_api_info']['APPKEY']
			. "&openid=" . $this->get_uid()
			. "&format=json";

			$info = get_contents($get_user_info);
			$temp = json_decode($info, true);
			$userinfo = array();
			$userinfo['uid'] = $this->get_uid();
			$userinfo['screen_name'] = $temp['nickname'];
			$userinfo['name'] = '';
			$userinfo['avatar'] = $temp['figureurl'];
			$userinfo['location'] = '';
			$userinfo['description'] = '';
			$userinfo['url'] = '';
			if($temp['gender']=='男'){
				$userinfo['gender'] = 'male';
			}elseif ($temp['gender']=='女'){
				$userinfo['gender'] = 'female';
			}else{
				$userinfo['gender'] = 'none';
			}
			return $userinfo;
		}

		return array();
	}

}
