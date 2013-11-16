<?php
include_once APP_PATH.'/extensions/connectors/oauth/OAuth_Renren.php';
class Driver_Renren
{
	private $vendor='Renren';
	protected $oauth;
	protected $client;
	protected $info;
	function __construct($info)
	{
		$this->info = $info;
	}


	function goto_loginpage($state=NULL,$display=NULL)
	{
		$url = "https://graph.renren.com/oauth/authorize?client_id=".$this->info['social_api_info']['APPKEY']."&redirect_uri=".$this->info['social_api_info']['CALLBACK']."&response_type=code";
		header('Location: '.$url);
		die;
	}

	function get_accesstoken()
	{
		if(isset($_REQUEST['code'])){
			$url = "https://graph.renren.com/oauth/token?client_id=" . $this->info['social_api_info']['APPKEY']
			. "&client_secret=" . $this->info['social_api_info']['APPSECRET']
			. "&redirect_uri=".$this->info['social_api_info']['CALLBACK']
			. "&grant_type=authorization_code&code=".$_REQUEST['code'];
			$json = json_decode ( get_contents( $url ) );
			if($json)
			{
				$social_vendor_info = 'social_'.$this->vendor.'_info';
				$token = array();
				$token[$social_vendor_info]['ACCESSTOKEN'] = $json->access_token;
				$url = "https://graph.renren.com/renren_api/session_key?oauth_token=" . $json->access_token;
				$temp_info = json_decode(get_contents( $url ),true);
				if($temp_info)
				{
					$token[$social_vendor_info]['session_secret'] = $temp_info['renren_token']['session_secret'];
					$token[$social_vendor_info]['expires_in'] = $temp_info['renren_token']['expires_in'];
					$token[$social_vendor_info]['session_key'] = $temp_info['renren_token']['session_key'];
					$token[$social_vendor_info]['oauth_token'] = $temp_info['oauth_token'];
					$token[$social_vendor_info]['user_id'] = $temp_info['user']['id'];
				}
				return $token;
			}
		}
		return NULL;
	}


	function get_userinfo()
	{
		$social_vendor_info = 'social_'.$this->vendor.'_info';
		if(isset($this->info[$social_vendor_info]['user_id']))
		{
			$this->oauth=new RenRenOauth($this->info['social_api_info']['APPKEY'], $this->info['social_api_info']['APPSECRET'],$this->info[$social_vendor_info]['session_key']);
			$temp_user=$this->oauth->users('getInfo',array('uids'=>$this->info[$social_vendor_info]['user_id']));
			if($temp_user && isset($temp_user[0]['uid']))
			{

				$userinfo = array();
				$userinfo['uid'] = $temp_user[0]['uid'];
				$userinfo['screen_name'] = $temp_user[0]['name'];
				$userinfo['name'] = $temp_user[0]['name'];
				$userinfo['avatar'] = $temp_user[0]['tinyurl'];
				$userinfo['location'] = $temp_user[0]['hometown_location']['province'].$temp_user[0]['hometown_location']['city'];
				$userinfo['description'] = '';
				$userinfo['url'] = '';
				if($temp_user[0]['sex']=='1'){
					$userinfo['gender'] = 'male';
				}elseif ($temp_user[0]['sex']=='0'){
					$userinfo['gender'] = 'female';
				}else{
					$userinfo['gender'] = 'none';
				}
				return $userinfo;
			}
		}

		return array();

	}

}
