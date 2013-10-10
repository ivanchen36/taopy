<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class welcome extends basecontroller
{

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->albums = $this->getHotestAlbum();
		$this->shares = $this->getLastest20();
		$this->prepareView();
		$this->ouput("/welcome/index.php");
	}

	private function prepareView(){
		$categories = $this->categories;
		foreach ($categories as $key=>$category){
			$this->category = $category;
			$style = $category['style']?$category['style']:'home';
			$categories[$key]['home_view'] = $this->render('/common/'.$style.'.php');
		}
		$this->categories = $categories;
	}

	private function getLastest20(){
		$result = spAccess('r', 'lastest_shares');
		if(!$result){
			$ptx_share = spClass('ptx_share');
			$result = $ptx_share->search($conditions,1,20);
			spAccess('w','lastest_shares',$result,300);
		}
		return $result;
	}

	private function getHotestAlbum(){
		$result = spAccess('r', 'hotest_album');
		if(!$result){
			$ptx_album = spClass('ptx_album');
			$conditions['total_share_num'] = 9;
			$result = $ptx_album->search($conditions,1,4,NULL,' ptx_album.album_id DESC ');
			$time = $this->settings['optimizer_setting']['cache_time_album'];
			spAccess('w','hotest_album',$result,$time);
		}
		return $result;
	}

}
