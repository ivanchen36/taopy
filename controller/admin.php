<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class admin extends basecontroller {

	public function __construct() {
		parent::__construct();
		$this->themes = '';
		$this->ptx_settings = spClass('ptx_settings');
		$this->setting_nav = $this->render("/admin/setting_nav.php");
		$this->setting_header = $this->render("/admin/setting_header.php");
		$this->ui_nav = $this->render("/admin/ui_nav.php");
	}

	public function index()
	{
		if($this->check_admin()){
			$this->action = 'index';
			$this->display("/admin/index.php");
		}
	}

	public function login()
	{
		if($this->is_admin()){
			$this->jump(spUrl('admin', 'index'));
			return;
		}

		$this->user_lib->remove_session();
		if($email = $this->spArgs("email")){
			$password = md5($this->spArgs('password'));
			$ptx_user = spClass('ptx_user');
			$user = $ptx_user->find(array('email'=>$email));
			if($user){
				if( $user['passwd'] == $password&&$user['user_type']==3){
					$this->user_lib->set_session($user,false);
					$this->jump(spUrl('admin', 'index'));
					return true;
				}
			}
		}
		$this->display("/admin/login.php");

	}

	public function logout()
	{
		$this->check_admin();
		$this->user_lib->remove_session();
		$this->jump(spUrl('admin', 'login'));
	}

	public function dashboard()
	{
		if($this->check_admin()){
			$this->action = 'dashboard';
			$this->display("/admin/dashboard.php");
		}
	}
	
	private function open_gzip($open=FALSE,$level=9){
		$config = spClass('Options');
		if($config->load('config.php')){
			$gzip = array (
					'gzip' => $open,
					'gzip_compression_level'=>$level,
			);
			$config->set_item('optimizer',$gzip);
			$config->save('config.php');
		}
	}

	private function url_rewrite($open=FALSE){
		$config = spClass('Options');
		if($config->load('config.php')){
			$lanuch_rewrite = array(
								'router_prefilter' => array(
			array('spUrlRewrite', 'setReWrite'),
			),
								'function_url' => array(
			array("spUrlRewrite", "getReWrite"),
			),
			);
			if ($open) {
				$config->set_item('launch',$lanuch_rewrite);
				$config->save('config.php');
			}else{
				$config->set_item('launch','');
				$config->save('config.php');
			}
		}
	}


	public function setting_basic()
	{
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['site_name'] = $this->spArgs("site_name");
				$basic_setting['site_domain'] = $this->spArgs("site_domain");
				$basic_setting['site_beian'] = $this->spArgs("site_beian");
				$basic_setting['site_tongji'] = stripslashes($this->spArgs("site_tongji",'','POST','false'));
				$basic_setting['site_need_verify'] = $this->spArgs("site_need_verify");
				$basic_setting['forbid_user_post'] = $this->spArgs("forbid_user_post");
				$basic_setting['site_close'] = $this->spArgs("site_close");
				$basic_setting['lang'] = $this->spArgs("lang");
				$this->session->set_data('lang','');
				$this->ptx_settings->set_value('basic_setting',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','setting_basic'));
			}else{
				$locals_dir = APP_PATH.'/lang/';
				$file_list = get_dir_file_info($locals_dir);
				$dir = array();
				foreach ($file_list as $d) {
					$dir[] = $d['name'];
				}
				$this->dirs = $dir;
				$this->site_info = $this->settings['basic_setting'];
				$this->display("/admin/setting_basic.php");
			}
		}
	}

	public function setting_optimizer()
	{
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['cache_time_album'] = $this->spArgs("cache_time_album");
				$basic_setting['cache_time_count'] = $this->spArgs("cache_time_count");
				$basic_setting['gzip_level'] = $this->spArgs("gzip_level");
				$basic_setting['gzip_open'] = $this->spArgs("gzip_open");
				$basic_setting['rewrite_open'] = $this->spArgs("rewrite_open");
				$basic_setting['site_open'] = $this->spArgs("site_open");
				$this->ptx_settings->set_value('optimizer_setting',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->open_gzip($basic_setting['gzip_open'],$basic_setting['gzip_level']);
				$this->url_rewrite($basic_setting['rewrite_open']);
				$this->success(T('save_success'),spUrl('admin','setting_optimizer'));
			}else{
				$this->vsettings = $this->settings['optimizer_setting'];
				$this->display("/admin/setting_optimizer.php");
			}
		}
	}

	public function setting_seo()
	{
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['page_title'] = $this->spArgs("page_title");
				$basic_setting['page_keywords'] = $this->spArgs("page_keywords");
				$basic_setting['page_description'] = $this->spArgs("page_description");
				$this->ptx_settings->set_value('seo_setting',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','setting_seo'));
			}else{
				$this->vsettings = $this->settings['seo_setting'];
				$this->display("/admin/setting_seo.php");
			}
		}
	}


	public function setting_file()
	{
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['upload_file_size'] = $this->spArgs("upload_file_size");
				$basic_setting['upload_file_type'] = $this->spArgs("upload_file_type");
				$basic_setting['upload_image_size_h'] = $this->spArgs("upload_image_size_h");
				$basic_setting['upload_image_size_w'] = $this->spArgs("upload_image_size_w");
				$basic_setting['fetch_image_size_w'] = $this->spArgs("fetch_image_size_w");
				$basic_setting['fetch_image_size_h'] = $this->spArgs("fetch_image_size_h");
				$this->ptx_settings->set_value('file_setting',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','setting_file'));
			}else{
				$this->vsettings = $this->settings['file_setting'];
				$this->display("/admin/setting_file.php");
			}
		}
	}

	public function setting_api()
	{
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				foreach ($this->vendors as $vendor) {
					$lowerkey = strtolower($vendor);
					$basic_setting[$vendor]=array(
								'OPEN'=>$this->spArgs($lowerkey.'_open'),
								'APPKEY'=>$this->spArgs($lowerkey.'_appkey'),
								'APPSECRET'=>$this->spArgs($lowerkey.'_appsecret'),
								'CALLBACK'=>$this->spArgs($lowerkey.'_callback'),
								'PID'=>$this->spArgs($lowerkey.'_pid')
					);
				}

				$this->ptx_settings->set_value('api_setting',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','setting_api'));
			}else{
				$this->api = $this->settings['api_setting'];
				$this->api_callback = base_url().'index.php?c=social&a=callback&vendor=';
				$this->display("/admin/setting_api.php");
			}
		}
	}

	public function ui_layout(){
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['homepage_ad'] = $this->spArgs("homepage_ad");
				$basic_setting['pin_auto'] = $this->spArgs("pin_auto");
				$basic_setting['album_auto'] = $this->spArgs("album_auto");
				$basic_setting['user_pin_auto'] = $this->spArgs("user_pin_auto");
				$basic_setting['pin_pagenum'] = $this->spArgs("pin_pagenum");
				$basic_setting['login_reminder'] = $this->spArgs("login_reminder",0);

				$this->ptx_settings->set_value('ui_layout',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','ui_layout'));
			}else{
				$this->vsettings = $this->settings['ui_layout'];
				$this->display("/admin/ui_layout.php");
			}

		}
	}

	public function ui_pin(){
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['pin_commentnum'] = $this->spArgs("pin_commentnum");
				$basic_setting['pin_ad'] = $this->spArgs("pin_ad");
				$this->ptx_settings->set_value('ui_pin',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','ui_pin'));
			}else{
				$this->vsettings = $this->settings['ui_pin'];
				$this->display("/admin/ui_pin.php");
			}

		}
	}

	public function ui_album(){
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['album_covernum'] = $this->spArgs("album_covernum");
				$basic_setting['album_covertype'] = $this->spArgs("album_covertype",9);
				$this->ptx_settings->set_value('ui_album',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','ui_album'));
			}else{
				$this->vsettings = $this->settings['ui_album'];
				$this->display("/admin/ui_album.php");
			}

		}
	}

	public function ui_detail(){
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['detail_album'] = $this->spArgs("detail_album");
				$basic_setting['detail_same_from'] = $this->spArgs("detail_same_from");
				$basic_setting['detail_history'] = $this->spArgs("detail_history");
				$basic_setting['detail_may_like'] = $this->spArgs("detail_may_like");
				$basic_setting['detail_ad'] = $this->spArgs("detail_ad");
				$this->ptx_settings->set_value('ui_detail',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','ui_detail'));
			}else{
				$this->vsettings = $this->settings['ui_detail'];
				$this->display("/admin/ui_detail.php");
			}

		}
	}

	public function ui_styles(){
		if($this->check_admin()){
			$action = $this->spArgs("act");
			if($action=='save'){
				$basic_setting = array();
				$basic_setting['style'] = $this->spArgs("style");
				$basic_setting['color'] = $this->spArgs("color");
				$this->ptx_settings->set_value('ui_styles',$basic_setting);
				$this->ptx_settings->updateSettings();
				$this->success(T('save_success'),spUrl('admin','ui_styles'));
			}else{
				$themes_dir = APP_PATH.'/themes/';
				$file_list = get_dir_file_info($themes_dir);
				$dir = array();
				foreach ($file_list as $d) {
					if($d['name']!='admin'&&$d['name']!='install'){
						$dir[] = $d['name'];
					}
				}
				$this->dirs = $dir;
				$this->vsettings = $this->settings['ui_styles'];
				$this->display("/admin/ui_styles.php");
			}
		}
	}

	public function ads_manage(){
		if($this->check_admin()){
			$action = $this->spArgs("act");
			$this->positions = array('homepage_ad','pinpage_ad','detailpage_ad');
			if($action=='add'){
				$ad_position = $this->spArgs("ad_position");
				if(in_array($ad_position, $this->positions)){
					$ads = array();
					$ads['key']= mktime();
					$ads['ad_name'] = $this->spArgs("ad_name");
					$ads['width'] = $this->spArgs("width");
					$ads['height'] = $this->spArgs("height");
					$ads['ad_source'] = stripslashes($this->spArgs("ad_source",'','POST','false'));
					$ads_array = $this->settings[$ad_position];
					$ads_array = !$ads_array?array():$ads_array;
					array_push($ads_array, $ads);
					$this->ptx_settings->set_value($ad_position,$ads_array);
					$this->ptx_settings->updateSettings();
				}
				$this->success(T('save_success'),spUrl('admin','ads_manage'));
			}else if($action=='edit'){
				$key = $this->spArgs("key");
				$ad_position = $this->spArgs("ad_position");
				if(in_array($ad_position, $this->positions)){
					$ads_array = $this->settings[$ad_position];
					foreach ($ads_array as $ads){
						if($ads['key'] == $key){
							$ads_edit = $ads;
							break;
						}
					}
					$ads_edit['ad_position']=$ad_position;
					$this->ads_edit = $ads_edit;
					$this->display('/admin/ads_manage_edit.php');
				}
			}else if($action=='edit_submit'){
				$key = $this->spArgs("key");
				$ad_position = $this->spArgs("ad_position");
				if(in_array($ad_position, $this->positions)){
					$ads_array = $this->settings[$ad_position];
					foreach ($ads_array as $i=>$ads){
						if($ads['key'] == $key){
							$index = $i;
							break;
						}
					}
					$ads_array[$index]['key']= mktime();
					$ads_array[$index]['ad_name'] = $this->spArgs("ad_name");
					$ads_array[$index]['width'] = $this->spArgs("width");
					$ads_array[$index]['height'] = $this->spArgs("height");
					$ads_array[$index]['ad_source'] = stripslashes($this->spArgs("ad_source",'','POST','false'));
					$this->ptx_settings->set_value($ad_position,$ads_array);
					$this->ptx_settings->updateSettings();
				}
				$this->success(T('save_success'),spUrl('admin','ads_manage'));
				return;
			}else if($action=='delete'){
				$key = $this->spArgs("key");
				$ad_position = $this->spArgs("ad_position");
				if(in_array($ad_position, $this->positions)){
					$ads_array = $this->settings[$ad_position];
					foreach ($ads_array as $i=>$ads){
						if($ads['key'] == $key){
							$index = $i;
							break;
						}
					}
					array_splice($ads_array, $index,1);
					$this->ptx_settings->set_value($ad_position,$ads_array);
					$this->ptx_settings->updateSettings();
				}
				$this->success(T('del_succeed'),spUrl('admin','ads_manage'));
			}else{
				$this->homepage_ads = $this->settings['homepage_ad'];
				$this->pinpage_ads = $this->settings['pinpage_ad'];
				$this->detailpage_ads = $this->settings['detailpage_ad'];
				$this->display("/admin/ads_manage.php");
			}

		}
	}

	public function item_list()
	{
		if($this->check_admin()){
			$action = $this->spArgs("act");
			$item_id = $this->spArgs("item_id");
			$this->message = $this->spArgs("message");
			$ptx_item = spClass('ptx_item');
			$ptx_share = spClass('ptx_share');
			if($item_id){
				$conditions['item_id'] = $item_id;
				$this->item = $ptx_item->find($conditions);
				$this->share = $ptx_share->find($conditions);
			}

			if($action=='delete'&&$this->item){
				$ptx_item->update($conditions,array('is_deleted'=>1));
				$this->jump(spUrl('admin', 'item_list'));
				return;
			}else if($action=='push'&&$this->item){
				$ptx_item->update($conditions,array('is_show'=>2));
				$this->jump(spUrl('admin', 'item_list'));
				return;
			}else if($action=='depush'&&$this->item){
				$ptx_item->update($conditions,array('is_show'=>1));
				$this->jump(spUrl('admin', 'item_list'));
				return;
			}else if($action=='verify'&&$this->item){
				$ptx_item->update($conditions,array('is_show'=>1));
				$this->jump(spUrl('admin', 'item_list'));
				return;
			}else if($action=='deverify'&&$this->item){
				$ptx_item->update($conditions,array('is_show'=>0));
				$this->jump(spUrl('admin', 'item_list'));
				return;
			}else if($action=='edit'&&$this->item){
				$this->display("/admin/item_edit.php");
				return;
			}else if($action=='edit_save'&&$this->item){
				$segment = spClass('Segment');
				$update_data['intro'] = $this->spArgs('intro');
				$segment_str = $segment->segment($update_data['intro']);
				$update_data['intro_search'] = $segment_str['py'];
				$update_data['keywords'] = $segment_str['cn'];

				$update_data['price'] = $this->spArgs("price");
				$update_data['title'] = $this->spArgs("title");
				$update_data['promotion_url'] = $this->spArgs("promotion_url");
				$share_update_data['category_id'] = $this->spArgs("category_id");
				$ptx_share->update($conditions,$share_update_data);
				if($ptx_item->update($conditions,$update_data)){
					$this->jump(spUrl('admin', 'item_list'));
				}else{
					$this->jump(spUrl('admin','item_list', array('act'=>'edit','item_id'=>$item['item_id'],'message'=>'修改失败')));
					return;
				}
				return;
			}else if($action=='search'){
				$conditions['orgin_post']=1;
				$page = $this->spArgs("page",1);
				if(NULL!=$this->spArgs("is_show")){
					$conditions['is_show'] = $this->spArgs("is_show");
				}
				if($category_id = $this->spArgs("category_id")){
					$conditions['category_id'] = $category_id;
				}
				if($keyword = $this->spArgs("keyword")){
					$segment = spClass('Segment');
					$conditions['keyword'] = $segment->convert_to_py($keyword);
				}
				$this->items = $ptx_share->search($conditions,$page,15);
				$conditions['act'] = 'search';
				$this->pages = createPages($ptx_share->spPager()->getPager(), 'admin', 'item_list',$conditions);
				$this->display("/admin/item_list.php");
			}else{

				$conditions['orgin_post']=1;
				//$conditions['is_deleted'] = 0;
				$page = $this->spArgs("page",1);

				$this->items = $ptx_share->search($conditions,$page,15);
				//var_dump($this->shares);
				//$this->items = $ptx_item->spPager($page, 15)->findAll($conditions,' item_id DESC ');
				$this->pages = createPages($ptx_share->spPager()->getPager(), 'admin', 'item_list');
				$this->display("/admin/item_list.php");
			}
		}
	}

	public function gathering(){
		if($this->check_admin()){
			$act = $this->spArgs("act");
			$this->page = $this->spArgs("page",1);
			$channel_name = $this->spArgs("channel",'taobao');
			$channel = spClass("Channel");
			if($act=='search'){
				$this->keyword = $this->spArgs("keyword");
				$this->channel_category_id = $this->spArgs("channel_category_id",0);
				$param['channel_category_id'] = $this->channel_category_id;
				$param['keyword'] = $this->keyword;
				$param['page'] = $this->page;
				$param['num_per_page'] = 20;
				$this->items = $channel->search_gathering($channel_name,$param);
				$args = array();
				$args['channel_category_id'] = $this->channel_category_id;
				if($this->keyword) $args['keyword'] = $this->keyword;
				$args['page'] = $this->page;
				$args['act'] = 'search';
				$this->pages = multi('admin', 'gathering', $args, $this->items['total_results'], 20, $this->page);
			}elseif($act=='publish'){
				$num_iid_str = $this->spArgs("num_iid_str");
				$album_id = $this->spArgs("album_id");
				$category_id = $this->spArgs("category_id");
				$num_iid_arr = explode(',', $num_iid_str);
				foreach ($num_iid_arr as $num_iid){
					$pinfo = $channel->fetch_goodinfo($channel_name,$num_iid);
					if($pinfo){
						$this->save_share_fetch($pinfo);
						sleep(5);
					}
				}

				$ptx_album = spClass('ptx_album');
				$ptx_album->update_album_cover($album_id);
				ajax_success_response('', '');
			}elseif($act=='fetch_category'){
				$channel_categories = $channel->fetch_categories($channel_name,0);
				ajax_success_response($channel_categories, '');
			}
			//$this->channel_categories = $channel->fetch_categories($channel_name,0);
			$this->display("/admin/gathering.php");
		}
	}


	private function save_share_fetch($item){

		$date_dir = '/data/attachments/'.date("Y/m/d/");
		(!is_dir(APP_PATH.$date_dir))&&@mkdir(APP_PATH.$date_dir,0777,true);
		$file_name = $this->current_user['user_id'].'_'.time().'';

		$this->save_fetch_file($item['orgin_image_url'], $date_dir, $file_name, true);
		$img_array = array();
		foreach ($item['item_imgs'] as $key=>$up_image){
			if($up_image&&trim($up_image['url'])!=''){
				if($up_image['url']==$item['orgin_image_url']){
					$img_array[] = array('id'=>$key,'url'=>$date_dir.$file_name,'desc'=>'','cover'=>true);
					continue;
				}
				$this->save_fetch_file($up_image['url'], $date_dir, $file_name.'_'.$key, false);
				$img_array[] = array('id'=>$key,'url'=>$date_dir.$file_name.'_'.$key,'desc'=>'','cover'=>false);
			}
		}
		$this->create_share_item($item,$date_dir.$file_name,array_length($img_array),$img_array);
		return true;
	}

	private function save_fetch_file($url,$date_dir,$file_name,$is_cover=false){
		$content = get_contents($url);
		$file_path = APP_PATH.$date_dir.$file_name.'.jpg';
		if(!empty($content) && @file_put_contents($file_path,$content) > 0)
		{
			$imagelib = spClass('ImageLib');
			$imagelib->create_thumb($file_path, 'large', 600);
			$imagelib->crop_square($file_path, 100,'square_like');
			if($is_cover){
				$pin_width = $this->settings['ui_pin']['pin_imagewidth']?$this->settings['ui_pin']['pin_imagewidth']:200;
				$imagelib->create_thumb($file_path, 'middle', $pin_width);
				$imagelib->create_thumb($file_path, 'small', 150);
				$imagelib->crop_square($file_path, 62);
			}
			file_exists($file_path) && unlink($file_path);
			return true;
		}
	}

	private function create_share_item($item,$image_path,$image_num,$img_array){
		$local_user = $this->current_user;
		$segment = spClass('Segment');
		$img_pro = @getimagesize(APP_PATH.$image_path.'_middle.jpg');
		$img['width']=$img_pro['0'];
		$img['height']=$img_pro['1'];
		$data['img_pro'] = array_to_str($img, ',');
		$data['title'] = $item['name'];
		$data['category_id'] = $this->spArgs('category_id');
		$data['image_path'] = $image_path;
		$data['user_id'] = $local_user['user_id'];
		$data['intro'] = $item['name'];
		$segment_str = $segment->segment($data['intro']);
		$tag_parse = $this->parse_tag($data['intro']);

		$data['intro_search'] .= ' '.$segment->convert_to_py($tag_parse);;
		$data['intro_search'] .= $segment_str['py'];
		$data['keywords'] .= ' '.$tag_parse;
		$data['keywords'] .= ' '.$segment_str['cn'];

		$data['share_type'] = 'channel';
		$data['price'] = is_numeric($item['price'])?$item['price']:0;
		$data['is_show'] = 1;
		$data['reference_url'] = $item['orgin_url'];
		$data['reference_itemid'] = $item['item_id'];
		$data['reference_channel'] = 'taobao';
		$data['promotion_url'] = $item['promotion_url'];
		$data['total_images'] = $image_num;
		$data['images_array'] = serialize($img_array);

		$create_time = mktime();
		$data['create_time'] = $create_time;
		$share_data['create_time'] = $create_time;

		$share_data['poster_id'] = $local_user['user_id'];
		$share_data['poster_nickname'] = $local_user['nickname'];
		$share_data['original_id'] = 0;
		$share_data['user_id'] = $local_user['user_id'];
		$share_data['user_nickname'] = $local_user['nickname'];
		$share_data['total_comments'] = 0;
		$share_data['total_likes'] = 0;
		$share_data['total_clicks'] = 0;
		$share_data['total_forwarding'] = 0;
		$share_data['album_id'] = $this->spArgs('album_id');
		$share_data['category_id'] = $this->spArgs('category_id');

		$data['share'] = $share_data;
		$ptx_item = spClass('ptx_item');
		$ptx_item->linker['share']['enabled'] = true;
		$ptx_item->spLinker()->create($data);
	}

	public function smile_list()
	{
		if($this->check_admin()){
			$act = $this->spArgs("act");
			$smile_id = $this->spArgs("smile_id");
			$ptx_smile = spClass('ptx_smile');
			if($smile_id){
				$conditions['smile_id'] = $smile_id;
				$this->smile = $ptx_smile->find($conditions);
			}
			if($act=='delete'&&$this->smile){
				$ptx_smile->delete($conditions);
				$ptx_smile->updateSmiliesCache();
				$this->jump(spUrl('admin', 'smile_list'));
				return;
			}else if($act=='edit'&&$this->smile){
				if($data['code'] = $this->spArgs('code')){
					$data['displayorder'] = $this->spArgs('displayorder');
					$data['url'] = $this->spArgs('url');
					$ptx_smile->update($conditions,$data);
					$ptx_smile->updateSmiliesCache();
					$this->jump(spUrl('admin', 'smile_list'));
					return;
				}else{
					$this->display("/admin/smile_edit.php");
					return;
				}
			}else if($act=='add'){
				$data['code'] = $this->spArgs('code');
				$data['displayorder'] = $this->spArgs('displayorder');
				$data['url'] = $this->spArgs('url');
				$data['typeid'] = 1;
				$ptx_smile->create($data);
				$ptx_smile->updateSmiliesCache();
				$this->jump(spUrl('admin', 'smile_list'));
				return;
			}else{
				$this->smiles = $ptx_smile->findAll();
				$this->display("/admin/smile_list.php");
				return;
			}
		}
	}

	public function category_list()
	{
		if($this->check_admin()){
			$act = $this->spArgs("act");
			$category_id = $this->spArgs("catid");
			$category_model = spClass('ptx_category');
			if($category_id){
				$conditions['category_id'] = $category_id;
				$this->category = $category_model->find($conditions);
			}
			if($act=='delete'&&$this->category){
				$category_model->delete_category($conditions);
				$category_model->update_category_top();
				$this->jump(spUrl('admin', 'category_list'));
				return;
			}else if($act=='edit'&&$this->category){
				if($data['category_name_cn'] = $this->spArgs('category_name_cn')){
					$data['category_name_en'] = $this->spArgs('category_name_en');
					$data['category_hot_words'] = $this->spArgs('category_hot_words');
					$data['display_order'] = $this->spArgs('display_order');
					$data['is_open'] = $this->spArgs('is_open');
					$data['is_home'] = $this->spArgs('is_home');
					$category_model->update($conditions,$data);
					$category_model->update_category_top();
					$this->jump(spUrl('admin', 'category_list'));
					return;
				}else{
					$this->display("/admin/category_edit.php");
					return;
				}
			}else if($act=='add'){
				$data['category_name_cn'] = $this->spArgs('category_name_cn');
				$data['category_name_en'] = $this->spArgs('category_name_en');
				$data['category_hot_words'] = $this->spArgs('category_hot_words');
				$data['display_order'] = $this->spArgs('display_order');
				$category_model->create($data);
				$category_model->update_category_top();
				$this->jump(spUrl('admin', 'category_list'));
				return;
			}else{
				$this->categories = $category_model->get_category();
				$this->display("/admin/category_list.php");
				return;
			}
		}
	}

	public function user_list()
	{
		if($this->check_admin()){
			$act = $this->spArgs("act");
			$user_id = $this->spArgs("uid");
			$ptx_user = spClass('ptx_user');
			if($user_id){
				$conditions['user_id'] = $user_id;
				$this->user = $ptx_user->getuser_byid($user_id);
			}
			if($act=='delete'&&$this->user){
				$this->jump(spUrl('admin', 'user_list'));
				return;
			}else if($act=='search'){
				$page = $this->spArgs("page",1);
				if(NULL!=$this->spArgs("user_type")){
					$conditions['user_type'] = $this->spArgs("user_type");
				}
				if($search_txt = $this->spArgs("keyword")){
					$conditions['keyword'] = $search_txt;
				}
				$this->users = $ptx_user->search($conditions,$page,15,null,' ptx_user.user_id ASC ');
				$conditions['act'] = 'search';
				$this->pages = createTPages($ptx_user->spPager()->getPager(), 'admin', 'user_list',$conditions);
				$this->display("/admin/user_list.php");
			}else if($act=='edit'&&$this->user){
				if($this->spArgs("hash")){
					if($this->spArgs("password")){
						$data['passwd'] = md5($this->spArgs("password"));
					}
					$data['user_type'] = $this->spArgs("user_type");
					$data['user_title'] = $this->spArgs("user_title");
					$data['bio'] = $this->spArgs("bio");
					$ptx_user->update($conditions,$data);
					$this->success(T('save_success'),spUrl('admin','user_list'));
					return;
				}else{
					$this->display("/admin/user_edit.php");
					return;
				}
			}else{
				$page = $this->spArgs("page",1);
				$this->users = $ptx_user->search($conditions,$page,15,null,' ptx_user.user_id ASC ');
				$this->pages = createTPages($ptx_user->spPager()->getPager(), 'admin', 'user_list',$conditions);
				$this->display("/admin/user_list.php");
			}
		}
	}

	public function update_cache()
	{
		if($this->check_admin()){
			$act = $this->spArgs("act");
			if($act=='update'){
				$cat_cache = $this->spArgs("cat_cache");
				$smile_cache = $this->spArgs("smile_cache");
				$sys_cache = $this->spArgs("sys_cache");

				if($cat_cache){
					$ptx_category = spClass('ptx_category');
					$ptx_category->update_category_top();
				}
				if($sys_cache){
					$ptx_settings = spClass('ptx_settings');
					$ptx_settings->updateSettings();
				}
				if($smile_cache){
					$ptx_smile = spClass('ptx_smile');
					$ptx_smile->updateSmiliesCache();
				}
				$this->success(T('update_succeed'),spUrl('admin','update_cache'));
				return;
			}else{
				$this->display("/admin/update_cache.php");
			}
		}
	}

	public function tag_list()
	{
		if($this->check_admin()){
			$act = $this->spArgs("act");
			$tag_id = $this->spArgs("tagid");
			$tag_model = spClass('ptx_tag');
			$category_model = spClass('ptx_category');
			if($tag_id){
				$conditions['tag_id'] = $tag_id;
				$this->tag = $tag_model->find($conditions);
			}
			$this->categories = $category_model->findAll();

			if($act=='delete'&&$this->tag){
				$tag_model->delete($conditions);
				$this->jump(spUrl('admin', 'tag_list'));
				return;
			}else if($act=='edit'&&$this->tag){
				if($data['tag_group_name_cn'] = $this->spArgs('tag_group_name_cn')){
					$data['category_id'] = $this->spArgs('category_id');
					$data['tag_group_name_en'] = $this->spArgs('tag_group_name_en');
					$data['display_order'] = $this->spArgs('display_order');
					$data['tags'] = $this->spArgs('tags');
					$tag_model->update($conditions,$data);
					$this->jump(spUrl('admin', 'tag_list'));
					return;
				}else{
					$this->display("/admin/tag_edit.php");
					return;
				}

			}else if($act=='add'){
				$data['tag_group_name_cn'] = $this->spArgs('tag_group_name_cn');
				$data['category_id'] = $this->spArgs('category_id');
				$data['tag_group_name_en'] = $this->spArgs('tag_group_name_en');
				$data['display_order'] = $this->spArgs('display_order');
				$data['tags'] = $this->spArgs('tags');
				$tag_model->create($data);
				$this->jump(spUrl('admin', 'tag_list'));
				return;
			}else{
				$this->tags = $tag_model->spLinker()->findAll();
				$this->display("/admin/tag_list.php");
				return;
			}
		}
	}


	public function database_management()
	{
		if($this->check_admin()){
			$db = spClass('dbbackup', array(0=>$GLOBALS['G_SP']['db']));

			$this->table =  $db->showAllTable($this->spArgs('chk',0));
			$this->display("/admin/database_management.php");
		}
	}

	public function database_backup(){
		if($this->check_admin()){
			$db = spClass('dbbackup', array(0=>$GLOBALS['G_SP']['db']));
			$act = $this->spArgs("act");
			$table = $this->spArgs("table");
			if($act=='outall'&&$db){
				$db->outAllData();
			}elseif($act=='optimize'&&$db&&$table){
				$db->optimizeTable($table);
				$this->jump(spUrl('admin', 'database_management'));
			}elseif($act=='repair'&&$db&&$table){
				$db->repairTable($table);
				$this->jump(spUrl('admin', 'database_management'));
			}elseif($act=='outone'&&$db&&$table){
				$db->outTable($table);
			}
		}
	}

	public function database_download(){
		if($this->check_admin()){
			import(APP_PATH.'/include/download_functions.php');
			$file_name = $this->spArgs('fname');
			$dbbackup_dir = APP_PATH.'/data/database';
			$data = file_get_contents($dbbackup_dir."/".$file_name); // 读文件内容
			force_download($file_name, $data);
		}
	}

	public function system_update(){
		if($this->check_admin()){
			$localversion = $GLOBALS['G_SP']['tkversion'];
			$localrelease = $GLOBALS['G_SP']['tkrelease'];

			$vlink = "http://www.onightjar.com/index.php?c=pzupgrade&a=tkpatches&pzversion={$localversion}&pzrelease={$localrelease}";
			$json = get_contents($vlink);
			
			$this->releases = json_decode($json,true);
			
			$this->display("/admin/system_update.php");
		}
	}

	public function frindlink_list()
	{
		if($this->check_admin()){
			$act = $this->spArgs("act");
			$friendlinks = $this->settings['frindlink'];
			if($act=='add'){
				if($link_url = $this->spArgs("link_url")){
					$link = array(
						'key'=>mktime(),
						'link_url'=>$link_url,
						'link_name'=>$this->spArgs("link_name")
					);
					if(!is_array($friendlinks)){
						$friendlinks = array();
					}
					array_push($friendlinks, $link);
					$this->ptx_settings->set_value('frindlink',$friendlinks);
					$this->ptx_settings->updateSettings();
				}
			}elseif($act=='delete'){
				$key = $this->spArgs("key");
				foreach ($friendlinks as $i=>$frindlink){
					if($frindlink['key'] == $key){
						$index = $i;
						break;
					}
				}
				array_splice($friendlinks, $index,1);
				$this->ptx_settings->set_value('frindlink',$friendlinks);
				$this->ptx_settings->updateSettings();
			}
			$this->links = $friendlinks?$friendlinks:array();
			$this->display("/admin/frindlink_list.php");
		}
	}

	function homepage_slide(){
		if($this->check_admin()){
			$act = $this->spArgs("act");
			$homeslide = $this->settings['homeslide'];
			if($act=='add'){
				if($image_url = $this->spArgs("filename")){
					$slide_image = array(
						'key'=>mktime(),
						'image_url'=>'data/attachments/homeslide/'.$image_url,
						'link_url'=>$this->spArgs("link_url"),
						'order'=>$this->spArgs("order"),
						'desc'=>$this->spArgs("desc")
					);

					if(!is_array($homeslide)){
						$homeslide = array();
					}
					array_push($homeslide, $slide_image);
					$homeslide = sysSortArray($homeslide, 'order', 'SORT_ASC','SORT_NUMERIC');
					$this->ptx_settings->set_value('homeslide',$homeslide);
					$this->ptx_settings->updateSettings();
				}
			}elseif($act=='edit'){
				$key = $this->spArgs("key");
				foreach ($homeslide as $slide){
					if($slide['key'] == $key){
						$slide_edit = $slide;
						break;
					}
				}
				$this->slide = $slide_edit;
				$this->display("/admin/homepage_slide_edit.php");
				return;
			}elseif($act=='edit_submit'){
				$key = $this->spArgs("key");
				foreach ($homeslide as $i=>$slide){
					if($slide['key'] == $key){
						$index = $i;
						break;
					}
				}
				$homeslide[$index]['link_url'] = $this->spArgs("link_url");
				$homeslide[$index]['order'] = $this->spArgs("order");
				$homeslide[$index]['desc'] = $this->spArgs("desc");
				$homeslide = sysSortArray($homeslide, 'order', 'SORT_ASC','SORT_NUMERIC');
				$this->ptx_settings->set_value('homeslide',$homeslide);
				$this->ptx_settings->updateSettings();
				$this->jump(spUrl('admin','homepage_slide'));
				return;
			}elseif($act=='upload'){
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

				$temp_dir = '/data/attachments/homeslide/';
				(!is_dir(APP_PATH.$temp_dir))&&@mkdir(APP_PATH.$temp_dir,0777,true);
				$result = $uploader->handleUpload(APP_PATH.$temp_dir);
				$this->ajax_response($result);
				//echo json_encode($result);
				return;
			}elseif($act=='delete'){
				$key = $this->spArgs("key");
				foreach ($homeslide as $i=>$slide){
					if($slide['key'] == $key){
						$index = $i;
						$image_url = $slide['image_url'];
						break;
					}
				}
				array_splice($homeslide, $index,1);
				if($image_url){
					file_exists(APP_PATH.'/'.$image_url) && unlink(APP_PATH.'/'.$image_url);
				}
				$this->ptx_settings->set_value('homeslide',$homeslide);
				$this->ptx_settings->updateSettings();
			}
			$this->slides = $homeslide?$homeslide:array();
			$this->display("/admin/homepage_slide.php");
		}
	}

}

function multi($controller,$action,$args,$num, $perpage, $curpage, $maxpages = 0, $page = 10) {
	$realpages = 1;
	$page -= strlen($curpage) - 1;
	if($page <= 0) {
		$page = 1;
	}
	if($num > $perpage) {
		$offset = floor($page * 0.5);
		$realpages = @ceil($num / $perpage);
		$curpage = $curpage > $realpages ? $realpages : $curpage;
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;
		$des = T('total').' '.$num.' '.T('items').', '.T('total').' '.$realpages.' '.T('page').' ('.$perpage.'/'.T('page').'):';

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		if ($curpage != 1){
			$args['page'] = 1;
			$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('first_page').'</a> | ';
			$args['page'] = $curpage-1;
			$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('prev_page').'</a> | ';
		}
		for($i = $from; $i <= $to; $i++) {
			if ($i != $curpage) {
				$args['page'] = $i;
				$des .= '<a href="'.spUrl($controller,$action,$args).'">'.$i.'</a> ';;
			}else{
				$des .= '<b>'.$i.'</b> ';
			}
		}

		if ($curpage != $realpages){
			$args['page'] = $curpage + 1;
			$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('next_page').'</a> | ';
			$args['page'] = $realpages;
			$des .= '<a href="'.spUrl($controller,$action,$args).'">'.T('last_page').'</a>';
		}
	}
	return $des;
}