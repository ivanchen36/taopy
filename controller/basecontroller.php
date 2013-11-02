<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class basecontroller extends spController
{
	public function __construct() {
		parent::__construct();
		$this->session = spClass('Session');
		$this->cookie = spClass('Cookie');
		$this->user_lib = spClass('UserLib');
		$this->ptx_settings = spClass('ptx_settings');
		$this->settings = $this->ptx_settings->getSettings();
		$this->check_language();
		$this->fetch_global();
		$this->current_user = $this->user_lib->get_session();
		$this->themes = $this->settings['ui_styles']['style'];
		$this->version = $GLOBALS['G_SP']['tkversion'];
		$this->release = $GLOBALS['G_SP']['tkrelease'];
		$this->vendors = $GLOBALS['G_SP']['vendors'];
		$ptx_category = spClass('ptx_category');
		$this->categories = $ptx_category->get_category_top();
		$this->is_editer = $this->is_editer()?true:false;
		$this->hash = random_string(alnum,5);
		$this->prepare_parameter();
		if($this->settings['basic_setting']['site_close']&&!$this->is_admin()&&$this->current_action!='login'){
			$this->showError(T('site_closed'));
		}
		if($this->settings['basic_setting']['forbid_user_post']){
			$this->can_post = ($this->is_editer())?true:false;
		}else{
			$this->can_post = true;
		}
	}

	private function check_language(){
		$lang = ($this->settings['basic_setting']['lang'])?$this->settings['basic_setting']['lang']:'zh_cn';
		$this->lang = ($language=$this->session->get_data('lang'))?$language:$lang;
		$this->setLang($this->lang);
	}

	private function showError($message=''){
		$this->message=$message;
		$this->ouput('/errorpage/error.php');
		exit();
	}

	public function fetch_global(){
		GLOBAL $__controller, $__action;
		$this->current_controller = $__controller;
		$this->current_action = $__action;
	}

	public function set_header(){
		$this->prepare_nav();
		$this->tpl_header = $this->render('/common/header.php');
	}

	public function set_footer(){
		$this->set_js_tpl();
		$this->tpl_footer =  $this->render('/common/footer.php');
	}

	public function prepare_nav(){
		$this->tpl_nav = $this->render('/common/nav.php');
	}

	public function set_js_tpl($tpl=null){
		$this->tpl_js = $this->render('/js_tpl/login_box_tpl.php');
		//$this->tpl_js .= $this->render('/js_tpl/register_box_tpl.php');
		$this->tpl_js .= $this->render('/js_tpl/publish_tpl.php');
		$this->tpl_js .= $this->render('/js_tpl/comment_tpl.php');
		//$this->tpl_js .= $this->render('/js_tpl/avatar_tpl.php');
		$this->tpl_js .= $this->render('/js_tpl/crop_dialog_tpl.php');
		$this->tpl_js .= $this->render('/js_tpl/push_dialog_tpl.php');
		//$this->tpl_js .= $this->render('/js_tpl/tags_pop_tpl.php');
		$this->tpl_js .= $this->render('/js_tpl/user_profile_tpl.php');
	}

	public function render($tpl){
		return $this->display($this->themes.$tpl,false);
	}

	public function ouput($tpl,$need_header_footer=true){
		if($need_header_footer){
			$this->set_header();
			$this->set_footer();
		}
		$this->display($this->themes.$tpl,true);
	}

	public function set_tagcloud(){
		return $this->render('/common/tagcloud.php');
	}

	public function waterfallView($arr,$type='pin'){
		switch ($type) {
			case 'pin':
				$this->shares = $arr;
				break;
			case 'album':
				$this->albums = $arr;
				break;
		}
		$this->tpl_waterfall = $this->render('/waterfall/'.$type.'.php');
	}

	public function prepare_parameter(){
		$cat_id =  $this->spArgs("cat");
		if($cat_id&&is_numeric($cat_id)){
			$this->category_id = $cat_id;
		}
		$category_id =  $this->spArgs("category_id");
		if($category_id&&is_numeric($category_id)){
			$this->category_id = $category_id;
		}
		$cid =  $this->spArgs("cid");
		if($cid&&is_numeric($cid)){
			$this->category_id = $cid;
		}
		$uid =  $this->spArgs("uid");
		if($uid&&is_numeric($uid)){
			$this->user_id = $uid;
		}
		$album_id =  $this->spArgs("album_id");
		if($album_id&&is_numeric($album_id)){
			$this->album_id = $album_id;
		}
		$aid =  $this->spArgs("aid");
		if($aid&&is_numeric($aid)){
			$this->album_id = $aid;
		}
		$share_id = $this->spArgs("share_id");
		if($share_id&&is_numeric($share_id)){
			$this->share_id = $share_id;
		}
		$post_sid = $this->spArgs("sid",null,'post');
		if($post_sid&&is_numeric($post_sid)){
			$this->share_id = $post_sid;
		}
		$get_sid = $this->spArgs("sid",null,'get');
		if($get_sid&&is_numeric($get_sid)){
			$this->share_id = $get_sid;
		}
		$page = $this->spArgs("page");
		if($page&&is_numeric($page)){
			$this->page = $page;
		}else{
			$this->page = 1;
		}
		$txt = $this->spArgs("txt");
		if($txt){
			$this->txt = $txt;
		}
	}

	function parameter_need($param=''){
		$p = $this->$param;
		if(!$p||$p==NULL){
			spError(T('lost_param'));
		}
	}

	function check_admin()
	{
		if(!$this->is_admin())
		{
			$this->jump(spUrl('admin', 'login'));
			return false;
		}else{
			return true;
		}
	}

	function check_login(){
		if(!$this->is_login())
		{
            $this->jump(spUrl('welcome', 'index'),1,'');
			//$this->jump(spUrl('webuser', 'login'));
			return false;
		}else{
			return true;
		}
	}

	function is_admin(){
		$local_user = $this->current_user;
		if($local_user&&$local_user['user_type']==3){
			return true;
		}else {
			return false;
		}
	}

	function is_editer(){
		$local_user = $this->current_user;
		if($local_user&&$local_user['user_type']>1){
			return true;
		}else {
			return false;
		}
	}

	function is_login()
	{
		if($this->current_user){
			return true;
		}else {
			return false;
		}
	}

	function ajax_check_editer()
	{
		if($this->is_editer()){
			return true;
		}else {
			$this->ajax_failed_response('no-permission');
		}
	}

	function ajax_check_login()
	{
		if($this->is_login()){
			return true;
		}else {
			$this->ajax_failed_response('not-login');
		}
	}

	function ajax_check_can_post()
	{
		if(!$this->can_post){
			$this->ajax_failed_response(T('can_not_post'));
		}
	}

	public function parse_tag($message){
		$taglist = $taglist_tmp = array();
		preg_match_all("/#([^\r\n]*?)#/i", $message.' ', $taglist_tmp);
		$taglist_tmp = array_slice(array_unique($taglist_tmp[1]), 0, 6);
		$taglist_tmp = implode($taglist_tmp, ' ');
		return $taglist_tmp;
	}

	public function seo_title($title){
		$this->page_title = sysSubStr($title,100,false).' '.$this->page_title;
	}

	public function seo_description($description){
		$this->page_description = sysSubStr($description,200,false).' '.$this->page_description;
	}

	public function seo_keyword($keyword){
		$this->page_keyword = sysSubStr($keyword,200,false).' '.$this->page_keyword;
	}
}
