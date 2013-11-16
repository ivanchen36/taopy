<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class baseuser extends basecontroller {

	public function __construct() {
		parent::__construct();
	}

	protected function userControl(){
		$ptx_user = spClass('ptx_user');
		$this->user = $ptx_user->getuser_byid($this->current_user['user_id']);
		$this->tpl_usercontrol = $this->render('/user/usercontrol.php');
	}

	protected function userControlPub($user_id){
		$ptx_user = spClass('ptx_user');
		$this->user = $ptx_user->getuser_byid($user_id);
		$this->tpl_usercontrol = $this->render('/user/usercontrol_pub.php');
	}

	public function shares($user_id){
		$num_per_page = 15;
		$args = array("page"=>"2");
		if($this->category_id){
			$conditions['category_id'] = $this->category_id;
			$args['cat']=$this->category_id;
		}
		if($user_id){
			$conditions['user_id'] = $user_id;
			$args['uid']=$user_id;
		}

		$this->nextpage_url = spUrl($this->current_controller,$this->current_action, $args);
		$ptx_share = spClass('ptx_share');
		$shares = $ptx_share->search($conditions,$this->page,$num_per_page);
		$this->waterfallView($shares,'pin');
		$this->ouput("/user/pin.php");
	}

	protected function favorite_share($user_id){
		$num_per_page = 15;
		$args = array("page"=>"2");
		if($this->category_id){
			$conditions['category_id'] = $this->category_id;
			$args['cat']=$this->category_id;
		}

		$ptx_favorite_sharing = spClass('ptx_favorite_sharing');
		$conditions['user_id'] = $user_id;

		$this->nextpage_url = spUrl($this->current_controller,$this->current_action, $args);
		$shares = $ptx_favorite_sharing->search($conditions,$this->page,$num_per_page);
		$this->waterfallView($shares,'pin');
		$this->ouput("/user/pin.php");
	}

	public function album($user_id){
		$num_per_page = 15;

		$ptx_album = spClass('ptx_album');
		$args = array("page"=>"2");
		if($this->category_id){
			$conditions['category_id'] = $this->category_id;
			$args['cat']=$this->category_id;
		}
		if($user_id){
			$conditions['user_id'] = $user_id;
			$args['uid']=$user_id;
		}

		$this->nextpage_url = spUrl($this->current_controller,$this->current_action, $args);
		$albums = $ptx_album->search($conditions,$this->page,$num_per_page);
		$this->waterfallView($albums,'album');
		$this->ouput("/user/album.php");
	}

	protected function favorite_album($user_id)
	{
		$num_per_page = 15;
		$ptx_favorite_album=spClass("ptx_favorite_album");
		$args = array("page"=>"2");
		if($user_id){
			$conditions['user_id'] = $user_id;
			$args['uid']=$user_id;
		}
		if($this->category_id){
			$conditions['category_id'] = $this->category_id;
			$args['cat']=$this->category_id;
		}
		$this->nextpage_url = spUrl($this->current_controller,$this->current_action, $args);
		$albums = $ptx_favorite_album->search($conditions,$this->page,$num_per_page);
		$this->waterfallView($albums,'album');
		$this->ouput("/user/album.php");
	}

	public function album_shares(){
		$num_per_page = 15;
		$ptx_album = spClass('ptx_album');
		$data['album_id'] = $this->album_id;
		if(!$this->album_id||!($album = $ptx_album->find($data))){
			$this->error(T('album_not_existed'));
			return ;
		}

		if($this->page == 1){
			if($this->is_login()&&$album['user_id']==$this->current_user['user_id']){
				$this->userControl();
			}else{
				$this->userControlPub($album['user_id']);
			}
		}

		$args = array("page"=>"2");
		if($this->category_id){
			$conditions['category_id'] = $this->category_id;
			$args['cat']=$this->category_id;
		}
		if($this->album_id){
			$conditions['album_id'] = $this->album_id;
			$args['aid']=$this->album_id;
		}

		$this->nextpage_url = spUrl($this->current_controller,$this->current_action, $args);
		$ptx_share = spClass('ptx_share');
		$shares = $ptx_share->search($conditions,$this->page,$num_per_page);
		$this->waterfallView($shares,'pin');
		$this->ouput("/user/pin.php");
	}
}

