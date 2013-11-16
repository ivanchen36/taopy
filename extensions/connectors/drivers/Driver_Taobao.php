<?php
include APP_PATH.'/extensions/connectors/oauth/OAuth_Taobao.php';
class Driver_Taobao
{
	private $vendor='Taobao';
	protected $oauth;
	protected $client;
	protected $info;

	function __construct($info)
	{
		$this->info = $info;
		$this->oauth = new OAuth_Taobao($this->info['social_api_info']['APPKEY'], $this->info['social_api_info']['APPSECRET']);
	}

	function goto_loginpage($state=NULL,$display=NULL)
	{
		$url = "https://oauth.taobao.com/authorize?response_type=code&client_id=".$this->info['social_api_info']['APPKEY']."&redirect_uri=".$this->info['social_api_info']['CALLBACK']."&state=1";
		header('Location: '.$url);
		die;
	}

	function get_accesstoken()
	{

		if(isset($_REQUEST['code'])){

			$params=array(
      			'client_id'=>$this->info['social_api_info']['APPKEY'],
      			'client_secret'=>$this->info['social_api_info']['APPSECRET'],
      			'redirect_uri'=>urldecode($this->info['social_api_info']['CALLBACK']),
      			'code'=>$_REQUEST['code'],
      			'grant_type'=>'authorization_code',
			);
			$url = "https://oauth.taobao.com/token";
			$result = $this->oauth->curl( $url ,$params);
			$temp = json_decode ( $result ,true);
			$token = array();
			$social_vendor_info = 'social_'.$this->vendor.'_info';
			$token[$social_vendor_info]['ACCESSTOKEN'] = $temp['access_token'];
			return $token;
		}
		return NULL;
	}


	function get_userinfo()
	{
		$social_vendor_info = 'social_'.$this->vendor.'_info';
		if(!empty($this->info[$social_vendor_info]['ACCESSTOKEN']))
		{
			$request['action']='taobao.user.get';
			$request['paras']['fields']='avatar,email,user_id,uid,nick,sex,buyer_credit,seller_credit,location,created,last_visit,birthday,type,auto_repost,status,alipay_bind,promoted_type,alipay_account,alipay_no';
			$tb_user=$this->oauth->execute($request,$this->info[$social_vendor_info]['ACCESSTOKEN']);
			if($tb_user && isset($tb_user['user_get_response']['user']['user_id']))
			{
				$temp=$tb_user['user_get_response']['user'];
				$userinfo = array();
				$userinfo['uid'] = $temp['user_id'];
				$userinfo['screen_name'] = $temp['nick'];
				$userinfo['name'] = '';
				$userinfo['avatar'] = $temp['avatar'];
				$userinfo['location'] = $temp['location']['state'].$temp['location']['city'];
				$userinfo['description'] = '';
				$userinfo['url'] = '';
				if($temp['sex']=='m'){
					$userinfo['gender'] = 'male';
				}elseif ($temp['sex']=='f'){
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
