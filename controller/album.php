<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class album extends basecontroller
{

	public function __construct() {
		parent::__construct();
		$this->seo_title(T('album'));
	}

	public function index(){
		$num_per_page = 15;
		$ptx_album = spClass('ptx_album');
		$args = array("page"=>"2");
		if($this->category_id){
			$conditions['category_id'] = $this->category_id;
			$args['cat']=$this->category_id;
		}
		$conditions['total_share_num'] = $this->settings['ui_album']['album_covernum']?$this->settings['ui_album']['album_covernum']:9;
		$this->nextpage_url = spUrl($this->current_controller,$this->current_action, $args);
		$albums = $ptx_album->search($conditions,$this->page,$num_per_page);
		$this->waterfallView($albums,'album');
		$this->ouput("/album/index.php");
	}

	public function album_list(){
		$this->ajax_check_login();
		if($this->category_id){
			$ptx_album = spClass('ptx_album');
			$condition['category_id'] = $this->category_id;
			if($this->user_id){
				$condition['user_id'] = $this->user_id;
			}else{
				$condition['user_id'] = $this->current_user['user_id'];
			}
			$albums = $ptx_album->findAll($condition,null,' album_id,album_title ');
		}
		if($albums){
			$this->ajax_success_response($albums, '');
		}else{
			$this->ajax_failed_response('');
		}
	}

	public function category_list(){
		if($this->categories){
			$this->ajax_success_response($this->categories, '');
		}else{
			$this->ajax_failed_response(T('get_categories_faild'));
		}
	}

	public function tag_list(){
		if($this->categories){
			foreach ($this->categories as $category) {
				if($category['category_id']==$this->category_id){
					$hotwords = $category['category_hot_words'];
					break;
				}
			}
		}
		if($hotwords){
			$hotwords = explode(',', $category['category_hot_words']);
			$hotwords = array_slice($hotwords,0,20);
			$this->ajax_success_response($hotwords, '');
		}

	}

	public function tags(){
		$cid =  $this->spArgs("dataid");
		if($cid&&is_numeric($cid)){
			$this->category_id = $cid;
		}
		if($this->category_id){
			$ptx_tag = spClass('ptx_tag');
			$tag_groups = $ptx_tag->get_tag_group(" ptx_tag.category_id = '".$this->category_id."'");

			foreach ($tag_groups as $key=>$tag_group){
				$tags = str_replace(", ", ',', $tag_group['tags']);
				$tag_groups[$key]['tags'] = explode(',',trim($tags));
			}
			if($tag_groups){
				$this->ajax_success_response($tag_groups,'');
			}else{
				$this->ajax_failed_response(T('remote_no_data'));
			}
			return;
		}
		$this->ajax_failed_response(T('remote_error'));
	}

	public function album_create(){
		$this->ajax_check_login();
		$album_title = $this->spArgs('album_title');
		$category_id = $this->spArgs('category_id');
		$ptx_user=spClass('ptx_user');
		$user = $ptx_user->find(array('user_id'=>$this->current_user['user_id']),' ptx_user.total_albums ');

		if($this->user_id&&!$this->is_editer()){
			$this->ajax_failed_response(T('no_permission'));
		}

		if($album_title){
			$ptx_album = spClass('ptx_album');
			$user_id = ($this->user_id)?$this->user_id:$this->current_user['user_id'];
			$data['user_id'] = $user_id;
			$data['album_title'] = $album_title;
			$data['category_id'] = $category_id;
			$album = $ptx_album->find_one($data);
			if($album){
				$this->ajax_failed_response(T('album_existed'));
				return;
			}
			$album_id = $ptx_album->add_one($data);
			if($album_id){
				$data['album_id'] = $album_id;
			}
		}
		if($data&&$album_id){
			$this->ajax_success_response($data, '');
		}else{
			$this->ajax_failed_response(T('album_can_not_null'));
		}

	}

	public function album_edit(){
		$this->ajax_check_login();
		$album_title = $this->spArgs('album_title');
		if(!$album_title){
			$this->ajax_failed_response(T('album name can not be null'));
			return;
		}

		$album_id = $this->album_id;
		$category_id = $this->category_id;
		$ptx_album = spClass('ptx_album');

		if($album_id){
			$condition['album_id'] = $album_id;
			//$condition['user_id'] = $this->current_user['user_id'];
			$album = $ptx_album->find($condition);
		}
		if($album){
			if($album['user_id']!=$this->current_user['user_id']&&!$this->is_editer()){
				$this->ajax_failed_response(T('no_permission'));
				return;
			}
			$data['album_title'] = $album_title;
			$data['category_id'] = $category_id;
			$ptx_album->update($condition,$data);
			$ptx_share = spClass('ptx_share');
			if($category_id!=$album['category_id']){
				$ptx_share->update(array('album_id'=>$album_id),array('category_id'=>$category_id));
			}
			$this->ajax_success_response(null, T('edit_succeed'));
			return;
		}
		$this->ajax_failed_response(T('edit_failed'));

	}

	public function album_delete(){
		$this->ajax_check_login();

		$album_id = $this->album_id;
		$ptx_album = spClass('ptx_album');

		if($album_id){
			$condition['album_id'] = $album_id;
			$album = $ptx_album->find($condition);
		}
		if($album){
			if($album['user_id']!=$this->current_user['user_id']&&!$this->is_editer()){
				$this->ajax_failed_response(T('no_permission'));
				return;
			}
			$ptx_share = spClass('ptx_share');
			$num = $ptx_share->findCount(array('album_id'=>$album_id));
			if($num>0){
				$this->ajax_failed_response(T('del_failed').' '.T('album_not_null'));
				return;
			}

			$ptx_album->deleteByPk($album_id);
			$ptx_favorite_album = spClass('ptx_favorite_album');
			$ptx_favorite_album->delete(array('album_id'=>$album_id));

			$this->ajax_success_response(null, T('del_succeed'));
			return;
		}
		$this->ajax_failed_response(T('edit_failed'));

	}

	public function remove_like()
	{
		$this->ajax_check_login();
		if($this->album_id){
			$user_id = $this->current_user['user_id'];

			$ptx_album = spClass('ptx_album');
			$condition['album_id'] = $this->album_id;
			$album = $ptx_album->find($condition,null,' user_id ');

			if($album['user_id'] == $user_id){
				$this->ajax_failed_response('like_self');
				return;
			}

			$data['user_id'] = $user_id;
			$data['album_id'] = $this->album_id;
			$ptx_favorite_album=spClass("ptx_favorite_album");
			$result = $ptx_favorite_album->find($data);
			if($result){
				$ptx_favorite_album->delete($data);
				$ptx_album->remove_like($this->album_id);
				$this->ajax_success_response(null, 'success');
			}else{
				$this->ajax_failed_response('not_liked');
			}
		}else{
			$this->ajax_failed_response(T('album_not_existed'));
		}
	}

	public function add_like()
	{
		$this->ajax_check_login();
		if($this->album_id){
			$user_id = $this->current_user['user_id'];

			$ptx_album = spClass('ptx_album');
			$condition['album_id'] = $this->album_id;
			$album = $ptx_album->find($condition,null,' user_id ');

			if($album['user_id'] == $user_id){
				$response = array('result' => false, 'message' => "like_self");
				echo json_encode($response);
				return;
			}

			$data['user_id'] = $user_id;
			$data['album_id'] = $this->album_id;
			$ptx_favorite_album=spClass("ptx_favorite_album");
			$result = $ptx_favorite_album->add_one($data);
			if($result){
				$ptx_album->add_like($this->album_id);
				$this->ajax_success_response(null, 'success');
			}else{
				$this->ajax_failed_response('like_already');
			}
		}else{
			$this->ajax_failed_response(T('album_not_existed'));
		}

	}

}