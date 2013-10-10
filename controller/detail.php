<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class detail extends basecontroller
{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$share_id = $this->share_id;
		$ptx_share = spClass('ptx_share');
		$ptx_favorite_sharing = spClass('ptx_favorite_sharing');
		$ptx_category = spClass('ptx_category');
		$share_conditions['share_id'] = $share_id;
		$ptx_share->add_viewnum($share_id);
		$this->share = $ptx_share->find_one($share_conditions);
		if($this->share){
			$this->next_share = $ptx_share->find_one(array('find_next'=>$share_id),'ptx_share.share_id ASC','share_id');
			$this->pre_share = $ptx_share->find_one(array('find_pre'=>$share_id),'ptx_share.share_id DESC','share_id');
			$logged_user_id = $this->current_user?$this->current_user['user_id']:0;
			$this->categories = $ptx_category->get_category_top();
			$this->page_title = sysSubStr($this->share['title'],100,false).' '.$this->share['category_name_cn'];
			$this->page_description = sysSubStr($this->share['intro'],400,false);
			$this->page_keyword = sysSubStr($this->share['keywords'],200,false).' '.$this->share['category_name_cn'];
			$this->favorite_list = $ptx_favorite_sharing->search(array('share_id'=>$share_id),1,20,' user.user_id as uid,user.avatar_local as avatar,user.nickname as nickname, ptx_favorite_sharing.create_time as create_time ');
			$this->share_intro = parse_message($this->share['intro'],true);
			if($this->settings['ui_detail']['detail_may_like']){
				$round_shares = $ptx_share->get_round($this->share['category_id'],30);
				$this->waterfallView($round_shares,'pin');
			}
			if($this->settings['ui_detail']['detail_same_from']){
				$this->shares_same_user = $ptx_share->search_no_page(array('user_id'=>$this->share['user_id']),null,null,9);
			}
			if($this->settings['ui_detail']['detail_history']){
				$this->add_view_history($this->share);
				$this->view_history = array_reverse($this->get_view_history());
			}
			if($this->settings['ui_detail']['detail_ad']){
				$this->ads = $this->settings['detailpage_ad'];
			}
			if($this->settings['ui_detail']['detail_album']){
				$ptx_album = spClass('ptx_album');
				$this->album = $ptx_album->find_one(array('album_id'=>$this->share['album_id']));
			}
			$this->ouput("/detail/index.php");
		}
	}


	public function get_comments(){
		$share_id = $this->share_id;
		$page = $this->page;
		if($share_id){
			$ptx_comment = spClass('ptx_comment');
			$conditions['share_id'] = $share_id;
			$rs = $ptx_comment->search($conditions,$page,5);
			$comments = array();
			foreach ($rs as $comment) {
				$comment['post_time_friend'] = friendlyDate($comment['create_time']);
				$comment['user_avatar'] = useravatar($comment['user_id'], 'middle');
				$comment['comment_txt'] = parse_message($comment['comment_txt']);
				$comments[] = $comment;
			}
			$this->ajax_success_response($comments, '');
			return;
		}
		$this->ajax_failed_response(T('get_comment_failed'));
	}

	function get_view_history(){
		$view_history = $this->cookie->get_data('view_history');
		$arr = array();

		if($view_history){
			$f_array = explode('|', $view_history);
			if(is_array($f_array)){
				foreach ($f_array as $f) {
					array_push($arr, $this->str_to_arr($f,$coma=','));
				}
			}else{
				array_push($arr, $this->str_to_arr(str_replace('|', '', $view_history),$coma=','));
			}
			return $arr;
		}else{
			return array();
		}
	}

	function array_to_str($arr,$coma=','){
		$pro = array();
		foreach ($arr as $k=>$v) {
			$pro[] = "{$k}:{$v}";
		}
		$text = implode(",", $pro);
		return $text;
	}

	function clear(){
		$this->cookie->set_data('view_history','',604800);
	}

	function str_to_arr($str,$coma=','){
		$arr = array();
		$f_array = explode($coma, $str);
		foreach ($f_array as $f) {
			$s_array = explode(':', $f);
			$arr[$s_array[0]] = $s_array[1];
		}
		return $arr;
	}

	function add_view_history($share){
		$history = $this->get_view_history();
		$value['s'] =  $share['share_id'];
		$value['p'] = $share['image_path'];
		$value['t'] = sysSubStr($share['title'],40,true);
		$value['m'] = $share['total_comments'];
		$value['k'] = $share['total_likes'];

		foreach ($history as $i=>$his) {
			if($his['s']==$value['s']){
				$index = $i;
				break;
			}
		}
		if(is_numeric($index)){
			array_splice($history, $index,1);
		}

		if(count($history)>4){
			array_shift($history);
		}
		array_push($history, $value);

		$str = array();
		foreach ($history as $v) {
			$str[] = $this->array_to_str($v);
		}
		$text = implode("|", $str);

		$this->cookie->set_data('view_history',$text,604800);
	}

}