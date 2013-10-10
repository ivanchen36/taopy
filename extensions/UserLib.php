<?php if (!defined('APP_PATH')) exit('No direct script access allowed');
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class UserLib {

	var $session;
	var $cookie;
	var $ptx_user;

	public function __construct()
	{
		$this->session = spClass('Session');
		$this->cookie = spClass('Cookie');
		$this->ptx_user = spClass('ptx_user');
	}


	function refresh_session(){
		$user_session = $this->get_session();
		$user = $this->ptx_user->getuser_byid($user_session['user_id']);
		$this->set_session($user);
	}

	function set_session($user,$is_remember=FALSE){
		$auth_user = $this->init_auth($user);
		$this->session->set_data('user_auth',$auth_user);
		if($is_remember){
			$this->cookie->secure_set('user_auth',$auth_user,604800);
		}
	}

	private function init_auth($user){
		$avatar_info = $this->get_avatarinfo($user['user_id']);
		return array(
								'user_id'=>$user['user_id'],
								'nickname'=>$user['nickname'],
								'email'=>$user['email'],
								'avatar_remote'=>$user['avatar_remote'],
								'password'=>$user['password'],
								'uc_id'=>$user['uc_id'],
								'is_star'=>$user['is_star'],
								'is_social'=>$user['is_social'],
								'is_shop'=>$user['is_shop'],
								'user_type'=>$user['user_type']
		);
		
	}

	function set_cookie($user){
		$this->remove_session();
		$auth_user = $this->init_auth($user);
		$this->cookie->secure_set('user_auth',$auth_user,604800);
	}

	function remove_session(){
		$this->session->set_data('user_auth','');
		$this->cookie->delete_data('user_auth');
	}

	function get_session(){
		$user_auth = $this->session->get_data('user_auth');
		if($user_auth){
			$result = $this->ptx_user->is_banned($user_auth['user_id']);
			if($result){
				$this->remove_session();
				return FALSE;
			}
			return $user_auth;
		}
		$user_auth = $this->cookie->secure_get('user_auth');
		if($user_auth){
			$result = $this->ptx_user->login($user_auth);
			if($result['result']){
				return $user_auth;
			}
		}
		$this->remove_session();
		return false;
	}

	public function get_avatarinfo($uid){
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$info = array();
		$dir = '/data/avatars/'.$dir1.'/'.$dir2.'/'.$dir3.'/';
		$filename = substr($uid, -2).'_avatar';
		$info['dir'] = $dir;
		$info['filename'] = $filename;
		$info['orgin'] = $filename.'.jpg';
		$info['large'] = $filename.'_large.jpg';
		$info['middle'] = $filename.'_middle.jpg';
		$info['small'] = $filename.'_small.jpg';
		$info['banner'] = $filename.'_banner.jpg';
		return $info;
	}

	public function create_default_avatar($uid){
		$avatar_info = $this->get_avatarinfo($uid);
		$avatar_dir = APP_PATH.$avatar_info['dir'];
		(!is_dir($avatar_dir))&&@mkdir($avatar_dir,0777,true);

		file_exists($avatar_dir.$avatar_info['orgin']) && unlink($avatar_dir.$avatar_info['orgin']);
		file_exists($avatar_dir.$avatar_info['large']) && unlink($avatar_dir.$avatar_info['large']);
		file_exists($avatar_dir.$avatar_info['middle']) && unlink($avatar_dir.$avatar_info['middle']);
		file_exists($avatar_dir.$avatar_info['small']) && unlink($avatar_dir.$avatar_info['small']);

		$default_source_small = APP_PATH.'/assets/img/avatar_small.jpg';
		$default_source_middle = APP_PATH.'/assets/img/avatar_middle.jpg';
		$default_source_large = APP_PATH.'/assets/img/avatar_large.jpg';
		$default_source_orgin = APP_PATH.'/assets/img/avatar_large.jpg';

		@copy($default_source_orgin, $avatar_dir.$avatar_info['orgin']);
		@copy($default_source_small, $avatar_dir.$avatar_info['small']);
		@copy($default_source_middle, $avatar_dir.$avatar_info['middle']);
		@copy($default_source_large, $avatar_dir.$avatar_info['large']);

		return $avatar_info['dir'].$avatar_info['filename'];
	}

}
