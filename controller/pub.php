<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class pub extends baseuser {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->shares();
	}

	public function shares(){
		$this->parameter_need('user_id');
		if($this->page==1){
			$this->userControlPub($this->user_id);
		}
		parent::shares($this->user_id);
	}

	public function album(){
		$this->parameter_need('user_id');
		if($this->page==1){
			$this->userControlPub($this->user_id);
		}
		parent::album($this->user_id);
	}

}

