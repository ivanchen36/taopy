<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class social extends basecontroller {
    private $cate;

	public function __construct() {
		parent::__construct();
        $this->cate = $GLOBALS['G_SP']["category"];
	}

	public function go(){
		$weibo = spClass("Weibo");
        $weibo->goto_loginpage();
    }

    public function callback()
	{
		$vendor = $this->spArgs("vendor");
		$weibo = spClass("Weibo");
        $ptx_user = spClass("ptx_user");
		$token = $weibo->get_accesstoken();
		$social_user_info = $weibo->get_userinfo();
        if (!$social_user_info['uid']) 
        {
            $this->error(T('your_social_info_invalid'),spUrl('pin','index'));
        }

        $social_connector = $ptx_user->getuser_bysuid($social_user_info['uid']);
        if($social_connector){
            $update_connect_data['avatar'] = $social_user_info['avatar'];
            $update_connect_data['access_token'] = $token;
            $update_connect_data['description'] = $social_user_info['description'];
            $update_connect_data['homepage'] = $social_user_info['homepage'];
            $ptx_user->update(array('user_id'=>$social_connector['user_id']),$update_connect_data);
            $this->user_lib->set_session($update_connect_data);
            $this->jump(spUrl('pin','index'));
        }else{

            $data['nickname'] = $social_user_info['screen_name'];
            $data['social_userid'] = $social_user_info['uid'];
            $data['access_token'] = $token;
            $data['avatar_remote'] = $social_user_info['avatar'];
            $data['gender'] = $social_user_info['gender'];
            $data['homepage'] = $social_user_info['url'];
            $tmp = explode(" ", $social_user_info['location']);
            $data['province'] = $tmp[0];
            $data['city'] = $tmp[1];
            $data['bio'] = $social_user_info['description'];
            $data['create_time'] = mktime();
            $data['is_active'] = 1;
            $uid = $ptx_user->add_one($data);

            $user = $ptx_user->getuser_byid($uid);
            $this->user_lib->set_session($user);

            $ptx_album = spClass('ptx_album');
            foreach ($this->cate as $key=>$val) {
                $album['user_id'] = $uid;
                $album['album_title'] = $val;
                $album['category_id'] = $key;
                $album_id = $ptx_album->add_one($album);
            }
            $this->jump(spUrl('pin','index'));
        }
	}

	public function social_login(){
        $this->ouput("/social/index.php");
    }

    private function save_remote_avatar($url,$uid)
    {
        $content = get_contents($url);

        $ptx_user = spClass("ptx_user");

		$avatar_info = $this->user_lib->get_avatarinfo($uid);

		$avatar_dir = APP_PATH.$avatar_info['dir'];
		(!is_dir($avatar_dir))&&@mkdir($avatar_dir,0777,true);

		file_exists($avatar_dir.$avatar_info['orgin']) && unlink($avatar_dir.$avatar_info['orgin']);
		file_exists($avatar_dir.$avatar_info['large']) && unlink($avatar_dir.$avatar_info['large']);
		file_exists($avatar_dir.$avatar_info['middle']) && unlink($avatar_dir.$avatar_info['middle']);
		file_exists($avatar_dir.$avatar_info['small']) && unlink($avatar_dir.$avatar_info['small']);

		$file_path = $avatar_dir.$avatar_info['orgin'];
		if(!empty($content) && @file_put_contents($file_path,$content) > 0)
		{
			$imagelib = spClass('ImageLib');
			$imagelib->create_thumb($file_path, 'large', 150,150);
			$imagelib->create_thumb($file_path, 'middle', 50,50);
			$imagelib->create_thumb($file_path, 'small', 16,16);
			//update local avatar
			//$user_update['avatar_local'] = $avatar_info['dir'].$avatar_info['filename'];
			return $ptx_user->update(array('user_id'=>$uid),$user_update);
		}else{
			//$user_update['avatar_local'] = $this->user_lib->create_default_avatar($uid);
			return $ptx_user->update(array('user_id'=>$uid),$user_update);
		}
		return false;
	}

	function check_nickname($nickname){
		$ptx_user = spClass('ptx_user');
		$user = $ptx_user->find(array('nickname'=>$nickname));
		if($user){
			return FALSE;
		}else{
			return TRUE;
		}
	}

}
