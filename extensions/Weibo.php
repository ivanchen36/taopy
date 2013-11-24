<?php
include_once APP_PATH.'/extensions/connectors/oauth/OAuth_Sina.php';
class Weibo
{
	private $vendor='Sina';
	protected $oauth;
	protected $client;
    protected $info; 

    function __construct($token = null)
    {
        $this->info = $GLOBALS['G_SP']['weibo'];
        if(null == $token)
        {
            $this->oauth = new SaeTOAuthV2($this->info['APPKEY'], $this->info['APPSECRET']);
        }else
        {
            $this->client = new SaeTClientV2($this->info['APPKEY'], $this->info['APPSECRET'], $token);
        }
    }

    public function upload_weibo($content, $url)
    {
        $rs = $this->client->upload_url_text($content, $url);
        return $rs;
    }

    function goto_loginpage($state=NULL,$display=NULL)
    {
        $url = $this->oauth->getAuthorizeURL($this->info['CALLBACK'],'code',$state,$display);
        header('Location: '.$url);
die;
	}

	function get_accesstoken()
	{
		if(isset($_REQUEST['code'])){
			$token = array();
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $this->info['CALLBACK'];
            try 
            {  
			$temp = $this->oauth->getAccessToken('code',$keys);
            }catch (OAuthException $e){  
                echo $e->getMessage();

                return NULL;
            }
			$token['REFRESHTOKEN'] = $temp['refresh_token'];
            $token['ACCESSTOKEN'] = $temp['access_token'];
            $this->session->set_data('token', $token);
            return $token;
		}
		return NULL;
	}


	function get_userinfo()
	{
		if(!empty($this->info['ACCESSTOKEN']))
		{
			$userinfo = array();
			$this->client = new SaeTClientV2($this->info['APPKEY'], $this->info['APPSECRET'],$this->info['ACCESSTOKEN']);
			$array_uid = $this->client->get_uid();
			$temp = $this->client->show_user_by_id($array_uid['uid']);
			$userinfo['uid'] = $array_uid['uid'];
			$userinfo['screen_name'] = $temp['screen_name'];
			$userinfo['name'] = $temp['name'];
			$userinfo['avatar'] = $temp['avatar_large'];
			$userinfo['location'] = $temp['location'];
			$userinfo['description'] = $temp['description'];
			$userinfo['url'] = $temp['url'];
			$userinfo['homepage'] = "http://www.weibo.com/" . $temp['domain'];
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
