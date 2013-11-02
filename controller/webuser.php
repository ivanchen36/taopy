<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class webuser extends basecontroller
{

	public function __construct() {
		parent::__construct();
	}

	public function logout(){
		$ptx_user = spClass('ptx_user');
		$result = $ptx_user->logout();
		$this->jump(spUrl('welcome', 'index'),1,'');
	}

	public function login(){
		$this->jump(spUrl('welcome', 'index'),1,'');

        /*
		if($data['email'] = $this->spArgs("email")){
			$data['password'] = $this->spArgs('password');
			$data['is_remember'] = $this->spArgs('is_remember');
			$ptx_user = spClass('ptx_user');
			$result = $ptx_user->login($data);
			$this->ajax_response($result);
			return;
		}
		$this->ouput("/login/index.php");
		return;
        */
	}

	public function banuser(){
		$this->ajax_check_editer();
		if($this->user_id){
			$ptx_user = spClass('ptx_user');
			$ptx_user->ban_user($this->user_id);
			$this->jump(spUrl('pin','index'));
			return;
		}
		$this->ajax_failed_response();
	}
	public function user_profile(){
		$uid =  $this->spArgs("dataid");
		if($uid&&is_numeric($uid)){
			$this->user_id = $uid;
		}
		if($this->user_id){
			$ptx_share = spClass('ptx_share');
			$ptx_user = spClass('ptx_user');
			$user = $ptx_user->getuser_byid($this->user_id);
			$user['passwd'] = '';
			$user['avatar'] = $user['avatar_remote'];
			$user['home'] = spUrl('pub','index',array('uid'=>$this->user_id));
			$shares = $ptx_share->search_no_page(array('user_id'=>$this->user_id),null,null,12);
			foreach ($shares as $key=>$s) {
				$shares[$key]['link'] = spUrl('detail','index',array('share_id'=>$s['share_id']));
			}
			$slink = spUrl('detail','index',array('sid'=>''));
			$this->ajax_success_response(array('user'=>$user,'shares'=>$shares),'');
			return;
		}
		$this->ajax_failed_response();
	}
	public function update_userinfo(){
		$this->ajax_check_login();
		if($this->spArgs()){
			$data['nickname'] = $this->spArgs('nickname');
			$data['gender'] = $this->spArgs('gender','none');
			$data['province'] = $this->spArgs('province');
			$data['user_title'] = $this->spArgs('usertitle');
			$data['city'] = $this->spArgs('city');
			$data['location'] = $this->spArgs('location');
			$data['bio'] = $this->spArgs('bio');
			if ($data['nickname']!=$this->current_user['nickname']&&!$this->nick_check($data['nickname'])) {
				$this->ajax_failed_response(T('user_nick_invalid'));
				return;
			}

			$ptx_user = spClass('ptx_user');
			if($ptx_user->update(array('user_id'=>$this->current_user['user_id']),$data)){
				$userlib = spClass('UserLib');
				$userlib->refresh_session();
				$this->ajax_success_response(null, T('operate_succeed'));
			}else{
				$this->ajax_failed_response(T('error_input'));
			}
		}

	}

	public function reset_passwd(){
		$this->ajax_check_login();
		if($this->spArgs()){
			$ptx_user = spClass('ptx_user');
			if($this->current_user['is_social']){
				$data['email'] = $this->spArgs('email');
				$new_passwd = $this->spArgs('new_passwd');
				$data['password'] = $new_passwd;
				$data['is_social'] = 0;
					
				$response = $ptx_user->reset_passwd($this->current_user['user_id'],$data,$new_passwd,TRUE);
				$this->ajax_response($response);
				return;
			}else {
				$org_passwd = $this->spArgs('org_passwd');
				$new_passwd = $this->spArgs('new_passwd');
				if($org_passwd==$new_passwd){
					$this->ajax_failed_response(T('password_can_not_same'));
					return;
				}
				$user = $ptx_user->getuser_byid($this->current_user['user_id']);
				if($user['passwd'] != md5($org_passwd)){
					$this->ajax_failed_response(T('orgin_password_wrong'));
					return;
				}
				$data['password'] = $new_passwd;
				$response = $ptx_user->reset_passwd($this->current_user['user_id'],$data,$new_passwd);
				$this->ajax_response($response);
				return;
			}
		}
	}

	public function ajax_register(){
		$data['email'] = $this->spArgs("email");
		$data['nickname'] = $this->spArgs('nickname');
		$data['password'] = $this->spArgs('password');
		$data['is_active'] = 1;
		$data['user_type'] = 1;
		$ptx_user = spClass('ptx_user');
		$response = $ptx_user->register($data);
		$this->ajax_response($response);
	}


	public function nickname_update_check()
	{
		$this->ajax_check_login();
		$nickname = $this->spArgs('nickname');
		if ($nickname!=$this->current_user['nickname']&&!$this->nick_check($nickname)) {
			$this->ajax_echo('false');
		}else{
			$this->ajax_echo('true');
		}
	}

	public function email_check($email) {
		$ptx_user = spClass('ptx_user');
		$user = $ptx_user->find(array('email'=>$email));
		return $user?false:true;
	}

	public function nick_check($nickname) {
		$ptx_user = spClass('ptx_user');
		$user = $ptx_user->find(array('nickname'=>$nickname));
		return $user?false:true;
	}

	public function ajax_email_check()
	{
		$ptx_user = spClass('ptx_user');
		$email = $this->spArgs("email");
		if($this->is_login()&&$this->current_user['email']==$email){
			$this->ajax_echo('true');
			return;
		}
		if($ptx_user->checkemail($email,true)){
			$this->ajax_echo('true');
		}else{
			$this->ajax_echo('false');
		}
	}

	public function ajax_nick_check()
	{
		$ptx_user = spClass('ptx_user');
		$nickname = $this->spArgs("nickname");
		if($ptx_user->checknick($nickname,true)){
			$this->ajax_echo('true');
		}else{
			$this->ajax_echo('false');
		}
	}


	public function upload_avatar(){
		$this->ajax_check_login();
		import(APP_PATH.'/include/ajaxuploader.php');
		$settings =  $this->settings;
		if($settings['file_setting']){
			$allowedExtensions = explode('|',$settings['file_setting']['upload_file_type']);
			$sizeLimit = $settings['file_setting']['upload_file_size']*1024;
		}else{
			$allowedExtensions = array('jpg','jpeg','gif','png');
			$sizeLimit = 2 * 1024 * 1024;
		}
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$temp_dir = '/data/attachments/tmp/';
		(!is_dir(APP_PATH.$temp_dir))&&@mkdir(APP_PATH.$temp_dir,0777,true);
		$result = $uploader->handleUpload(APP_PATH.$temp_dir);
		echo $this->ajax_response($result);

	}

	public function save_avatar(){
		$this->ajax_check_login();
		$x = $this->spArgs("x");
		$y = $this->spArgs("y");
		$w = $this->spArgs("w");
		$h = $this->spArgs("h");
		$js_w = $this->spArgs("js_w");
		$js_h = $this->spArgs("js_h");
		$type = $this->spArgs("type");
		$filename = $this->spArgs("filename");
		$temp_dir = '/data/attachments/tmp/';
		$imagepath = APP_PATH.$temp_dir.$filename;
		
		if($filename&&filename_check($filename)&&file_exists($imagepath)){
			$imagelib = spClass("ImageLib");
			$image_size=getimagesize($imagepath);
			$weight=$image_size[0];
			$height=$image_size[1];
			if($js_w<$weight){
				$scale = $js_w/$weight;
			}elseif ($js_h<$height){
				$scale = $js_h/$height;
			}else{
				$scale = 1;
			}
			$x = $x/$scale;
			$y = $y/$scale;
			$w = $w/$scale;
			$h = $h/$scale;

			$imagelib->crop_image($imagepath,$imagepath,$x,$y,$w,$h);

			$ptx_user = spClass('ptx_user');
			$avatar_info = $this->user_lib->get_avatarinfo($this->current_user['user_id']);
			$avatar_dir = APP_PATH.$avatar_info['dir'];
			(!is_dir($avatar_dir))&&@mkdir($avatar_dir,0777,true);
			if($type=='avatar'){
				file_exists($avatar_dir.$avatar_info['orgin']) && unlink($avatar_dir.$avatar_info['orgin']);
				file_exists($avatar_dir.$avatar_info['large']) && unlink($avatar_dir.$avatar_info['large']);
				file_exists($avatar_dir.$avatar_info['middle']) && unlink($avatar_dir.$avatar_info['middle']);
				file_exists($avatar_dir.$avatar_info['small']) && unlink($avatar_dir.$avatar_info['small']);

				@copy($imagepath, $avatar_dir.$avatar_info['orgin']);
				$imagelib->create_thumb($imagepath,NULL,150,150,$avatar_dir.$avatar_info['large']);
				$imagelib->create_thumb($imagepath,NULL,50,50,$avatar_dir.$avatar_info['middle']);
				$imagelib->create_thumb($imagepath,NULL,16,16,$avatar_dir.$avatar_info['small']);
				unlink($imagepath);
			}else if($type=='banner'){
				file_exists($avatar_dir.$avatar_info['banner']) && unlink($avatar_dir.$avatar_info['banner']);
				$imagelib->create_thumb($imagepath,NULL,950,300,$avatar_dir.$avatar_info['banner']);
				unlink($imagepath);
			}
			//update local avatar
			$user_update['avatar_local'] = $avatar_info['dir'].$avatar_info['filename'];
			$ptx_user->update(array('user_id'=>$this->current_user['user_id']),$user_update);
			$this->user_lib->refresh_session();

			$data['avatar_local']= $avatar_info['dir'].$avatar_info['filename'];
			$data['hash'] = uniqid();
			$this->ajax_success_response($data, T('operate_succeed'));
			return;
		}
		$this->ajax_failed_response(T('operate_failed'));
		return;
	}

}
