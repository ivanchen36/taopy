<?php
include_once APP_PATH.'/extensions/connectors/oauth/OAuth_Sina.php';
class Driver_Sina
{
	private $vendor='Sina';
	protected $oauth;
	protected $client;
	protected $info;
	function __construct($info)
	{
		$this->info = $info;
		$this->oauth = new SaeTOAuthV2($this->info['social_api_info']['APPKEY'], $this->info['social_api_info']['APPSECRET']);
	}


	function goto_loginpage($state=NULL,$display=NULL)
	{
		$url = $this->oauth->getAuthorizeURL($this->info['social_api_info']['CALLBACK'],'code',$state,$display);
		header('Location: '.$url);
		die;
	}

	function get_accesstoken()
	{
		if(isset($_REQUEST['code'])){
			$token = array();
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $this->info['social_api_info']['CALLBACK'];
            try 
            {  
			$temp = $this->oauth->getAccessToken('code',$keys);
            }catch (OAuthException $e){  
                echo $e->getMessage();

                return NULL;
            }
			$social_vendor_info = 'social_'.$this->vendor.'_info';
			$token[$social_vendor_info]['ACCESSTOKEN'] = $temp['access_token'];
			//$token['REFRESHTOKEN'] = $temp['refresh_token'];
			return $token;
		}
		return NULL;
	}


	function get_userinfo()
	{
		$social_vendor_info = 'social_'.$this->vendor.'_info';
		if(!empty($this->info[$social_vendor_info]['ACCESSTOKEN']))
		{
			$userinfo = array();
			$this->client = new SaeTClientV2($this->info['social_api_info']['APPKEY'], $this->info['social_api_info']['APPSECRET'],$this->info[$social_vendor_info]['ACCESSTOKEN']);
			$array_uid = $this->client->get_uid();
			//print_r($array_uid);
			//exit();
			$temp = $this->client->show_user_by_id($array_uid['uid']);
			$userinfo['uid'] = $array_uid['uid'];
			$userinfo['screen_name'] = $temp['screen_name'];
			$userinfo['name'] = $temp['name'];
			$userinfo['avatar'] = $temp['profile_image_url'];
			$userinfo['location'] = $temp['location'];
			$userinfo['description'] = $temp['description'];
			$userinfo['url'] = $temp['url'];
			if($temp['gender']=='m'){
				$userinfo['gender'] = 'male';
			}elseif ($temp['gender']=='f'){
				$userinfo['gender'] = 'female';
			}else{
				$userinfo['gender'] = 'none';
			}
			return $userinfo;
		}

		return null;

	}

}
