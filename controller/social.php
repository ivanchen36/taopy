<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class social extends basecontroller {

	public function __construct() {
		parent::__construct();
	}

	public function go(){
		$vendor = $this->spArgs("vendor");
		$connector = spClass("Connector");
		$connector->connect($vendor);
	}
	
	public function callback()
	{
		$vendor = $this->spArgs("vendor");
		$connector = spClass("Connector");
		$token = $connector->get_accesstoken($vendor);
		$userinfo = $connector->get_userinfo($vendor);
		$userinfo['vendor'] = $vendor;
		$this->session->set_data('social_info',$userinfo);
		$this->social_info = $userinfo;
		$this->bind();
	}
	public function unbind()
	{
		if(!$this->is_login()){
			$this->error(T('need_login'),spUrl('welcome','index'));
			//$this->error(T('need_login'),spUrl('webuser','login'));
		}
		$ptx_connector = spClass("ptx_connector");
		$ptx_user = spClass("ptx_user");
		$vendor = $this->spArgs("vendor");
		if(!$vendor){
			$this->error(T('not_support_vendor_redirect'));
		}
		$ptx_connector->del_connector_by_vendor_uid($vendor,$this->current_user['user_id']);
		$this->success(T('cancel_succeed'),spUrl('my','setting_bind'));
	}

	public function social_login(){
		$this->ouput("/social/index.php");
	}

	public function bind()
    {
        $ptx_connector = spClass("ptx_connector");
        $ptx_user = spClass("ptx_user");
        if($this->social_info){
            $social_user_info = $this->social_info;
        }else{
            $social_user_info = $this->session->get_data('social_info');
        }
        $vendor = $social_user_info['vendor'];
        if(!$vendor){
            $this->error(T('your_social_info_invalid'),spUrl('pin','index'));
        }
        /**
        if($nickname = $this->spArgs("nickname")){
            //if($this->check_nickname($nickname)){
                //$data['email'] = md5(random_string('alnum', 5)).'@'.T('domain.com');
                $data['nickname'] = $nickname;
                $data['avatar_remote'] = $social_user_info['avatar'];
                $data['gender'] = $social_user_info['gender'];
                $data['location'] = $social_user_info['location'];
                $data['bio'] = $social_user_info['description'];
                //$data['passwd'] = md5(random_string('alnum', 8));
                $data['create_time'] = mktime();
                $data['is_active'] = 1;
                $data['is_social'] = 1;
                $uid = $ptx_user->add_one($data);

                $connector_data['user_id'] = $uid;
                $connector_data['social_userid'] = $social_user_info['uid'];
                $connector_data['vendor'] = $vendor;
                $connector_data['vendor_info'] = serialize($this->session->get_data('social_'.$vendor.'_info'));
                $connector_data['username'] = $social_user_info['screen_name'];
                $connector_data['name'] = $social_user_info['name'];
                $connector_data['description'] = $social_user_info['description'];
                $connector_data['homepage'] = $social_user_info['url'];
                $connector_data['avatar'] = $social_user_info['avatar'];
                $connector_data['email'] = $social_user_info['email'];
                $connector_data['gender'] = $social_user_info['gender'];
                $connector_data['location'] = $social_user_info['location'];
                $ptx_connector->create($connector_data);

                $this->save_remote_avatar($social_user_info['avatar'], $uid);
                $user = $ptx_user->getuser_byid($uid);
                $this->user_lib->set_session($user);
                $this->clear_socialinfo($vendor);
                $this->ajax_success_response(null, T('login_succeed'));
                return;
            //}else{
            //	$this->ajax_failed_response(T('nick_already_existed'));
            //}
        }
         */
        $social_connector = $ptx_connector->get_bind_by_vendor_and_suid($vendor,$social_user_info['uid']);
        if($social_connector){
            if($this->current_user['user_id']&&$social_connector['user_id']!=$this->current_user['user_id']){
                $this->error(T('social_account_already_bind'),spUrl('pin','index'));
            }else{
                $update_connect_data['avatar'] = $social_user_info['avatar'];
                $ptx_connector->update(array('connect_id'=>$social_connector['connect_id']),$update_connect_data);
                $local_user = $ptx_user->getuser_byid($social_connector['user_id']);
                $this->user_lib->set_session($local_user);
                $this->clear_socialinfo($vendor);
                $this->jump(spUrl('pin','index'));
            }
        }else{
            if($this->current_user['user_id']){
                $connector_data['user_id'] = $this->current_user['user_id'];
                $connector_data['social_userid'] = $social_user_info['uid'];
                $connector_data['vendor'] = $vendor;
                $connector_data['vendor_info'] = serialize($this->session->get_data('social_'.$vendor.'_info'));
                $connector_data['username'] = $social_user_info['screen_name'];
                $connector_data['name'] = $social_user_info['name'];
                $connector_data['description'] = $social_user_info['description'];
                $connector_data['homepage'] = $social_user_info['url'];
                $connector_data['avatar'] = $social_user_info['avatar'];
                $connector_data['email'] = $social_user_info['email'];
                $connector_data['gender'] = $social_user_info['gender'];
                $connector_data['location'] = $social_user_info['location'];
                $ptx_connector->create($connector_data);
            }else{
                $data['nickname'] = $social_user_info['screen_name'];
                $data['avatar_remote'] = $social_user_info['avatar'];
                $data['gender'] = $social_user_info['gender'];
                $tmp = explode(" ", $social_user_info['location']);
                $data['province'] = $tmp[0];
                $data['city'] = $tmp[1];
                $data['bio'] = $social_user_info['description'];
                //$data['passwd'] = md5(random_string('alnum', 8));
                $data['create_time'] = mktime();
                $data['is_active'] = 1;
                $data['is_social'] = 1;
                $uid = $ptx_user->add_one($data);

                $connector_data['user_id'] = $uid;
                $connector_data['social_userid'] = $social_user_info['uid'];
                $connector_data['vendor'] = $vendor;
                $connector_data['vendor_info'] = serialize($this->session->get_data('social_'.$vendor.'_info'));
                $connector_data['username'] = $social_user_info['screen_name'];
                $connector_data['name'] = $social_user_info['name'];
                $connector_data['description'] = $social_user_info['description'];
                $connector_data['homepage'] = $social_user_info['url'];
                $connector_data['avatar'] = $social_user_info['avatar'];
                $connector_data['email'] = $social_user_info['email'];
                $connector_data['gender'] = $social_user_info['gender'];
                $connector_data['location'] = $social_user_info['location'];
                $ptx_connector->create($connector_data);

                $this->save_remote_avatar($social_user_info['avatar'], $uid);
                $user = $ptx_user->getuser_byid($uid);
                $this->user_lib->set_session($user);
                $this->clear_socialinfo($vendor);
            }
            $this->jump(spUrl('pin','index'));
        }
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
			$user_update['avatar_local'] = $avatar_info['dir'].$avatar_info['filename'];
			return $ptx_user->update(array('user_id'=>$uid),$user_update);
		}else{
			$user_update['avatar_local'] = $this->user_lib->create_default_avatar($uid);
			return $ptx_user->update(array('user_id'=>$uid),$user_update);
		}
		return false;
	}

	private function clear_socialinfo($vendor){
		$this->session->unset_data(
		array(
				'social_api_info'=>'',
				'social_user_info'=>'',
				'social_'.$vendor.'_info'=>''
				));
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
