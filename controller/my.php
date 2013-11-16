<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class my extends baseuser {

	public function __construct() {
		parent::__construct();
		$this->seo_title(T('my_pin'));
	}

	public function index(){
		$this->shares();
	}

	public function shares(){
		$this->check_login();
		if($this->page==1){
			$this->userControl();
		}
		parent::shares($this->current_user['user_id']);
	}


	public function favorite_share(){
		$this->check_login();
		if($this->page==1){
			$this->userControl();
		}
		parent::favorite_share($this->current_user['user_id']);
	}

	public function album(){
		$this->check_login();
		if($this->page==1){
			$this->userControl();
		}
		parent::album($this->current_user['user_id']);
	}

	public function favorite_album(){
		$this->check_login();
		if($this->page==1){
			$this->userControl();
		}
		parent::favorite_album($this->current_user['user_id']);
	}

	public function setting_basic(){
		$this->check_login();
		if($this->page==1){
			$this->userControl();
		}
		$this->ouput("/user/setting_basic.php");
	}


	public function setting_bind(){
		$this->check_login();
		if($this->page==1){
			$this->userControl();
		}
		$connector = spClass('ptx_connector');
		$this->bind_connectors = $connector->get_bind_connectors($this->user['user_id']);
		$cs = array();
		foreach ($this->bind_connectors as $c){
			$vendor = $c['vendor'];
			$cs[$vendor]['id'] = $c['connect_id'];
			$cs[$vendor]['username'] = $c['username'];
		}
		$this->cs = $cs;
		$this->ouput("/user/setting_bind.php");
	}

	public function setting_security(){
		$this->check_login();
		if($this->page==1){
			$this->userControl();
		}
		$this->ouput("/user/setting_security.php");
	}

}

