<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ajaxtpl extends basecontroller
{

	public function __construct() {
		parent::__construct();
	}

	public function render_tpl(){
		$tpl = $this->spArgs("tpl");
		$tpls = array('avatar_tpl','comment_tpl','crop_dialog_tpl','edit_album_tpl','forwarding','login_box_tpl','message_tpl',
				'publish_select','pubish_tpl','push_dialog_tpl','register_box_tpl','static_include_tpl','tags_pop_tpl','user_profile_tpl');
		if($tpl&&in_array($tpl, $tpls)){
			$data['tpl'] = $this->render('/js_tpl/'.$tpl.'.php');
			$this->ajax_success_response($data, '');
			return;
		}
		$this->ajax_failed_response('模板获取失败');
	}
	
}