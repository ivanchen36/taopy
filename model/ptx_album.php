<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_album extends spModel
{
	public $pk = 'album_id';
	public $table = 'ptx_album';
	var $linker = array(
		'user'=>array(
			'type' => 'hasone',  
			'map' => 'user',
			'mapkey' => 'user_id', 
			'fclass' => 'ptx_user',
			'fkey' => 'user_id',
			'enabled' => true
	),
		'category'=>array(
			'type' => 'hasone',  
			'map' => 'category',
			'mapkey' => 'category_id', 
			'fclass' => 'ptx_category',
			'fkey' => 'category_id',
			'enabled' => true
	),
	);

	var $select_fields = " ptx_album.*,category.category_name_cn,user.email,user.nickname,user.user_title,user.total_likes,user.avatar_remote ";

	private function init_conditions($conditions){
		$conditions_album = NULL;
		if(isset($conditions['keyword'])){
			$keyword = $this->escape($conditions['keyword']);
			$conditions_album .= "AND MATCH (ptx_album.album_title) AGAINST ('{$keyword}' IN BOOLEAN MODE) ";
		}
		if(isset($conditions['category_id'])){
			$category_id = $this->escape($conditions['category_id']);
			$conditions_album .= "AND ptx_album.category_id={$category_id} ";
		}
		if(isset($conditions['user_id'])){
			$user_id = $this->escape($conditions['user_id']);
			$conditions_album .= "AND ptx_album.user_id={$user_id} ";
		}
		if(isset($conditions['total_share_num'])){
			$limit_num = $this->escape($conditions['total_share_num']);
			$conditions_album .= "AND ptx_album.total_share>={$limit_num} ";
		}
		if(strpos($conditions_album, 'AND') === 0){
			$conditions_album = substr($conditions_album, 3);
		}
		return $conditions_album;
	}

	public function search($conditions=NULL,$page,$pagesize,$fields = null,$sort=null){
		$conditions = $this->init_conditions($conditions);
		if(!$sort)
			$sort = " ptx_album.album_id DESC ";
		if(!$fields){
			$fields = $this->select_fields;
		}
		return $this->spPager($page, $pagesize)->findAllJoin($conditions,$sort,$fields);
	}

	public function search_no_page($conditions=NULL,$fields = null,$sort=null,$limit){
		$conditions = $this->init_conditions($conditions);
		if(!$sort)
			$sort = " ptx_album.album_id DESC ";
		if(!$fields){
			$fields = $this->select_fields;
		}
		return $this->findAllJoin($conditions,$sort,$fields,$limit);
	}

	public function find_one($conditions=NULL){
		return $this->findJoin($conditions);
	}

	public function add_one($data){
		if($this->check_value($data)){
			$data['create_time'] = mktime();
			$result = $this->create($data);
			$ptx_user = spClass('ptx_user');
			$ptx_user->add_album($data['user_id']);
			return $result;
		}
		return false;
	}

	private function check_value($data){
		if(!is_numeric($data['category_id'])){
			return false;
		}
		return true;
	}

	public function add_like($album_id)
	{
		return $this->runSql("UPDATE {$this->tbl_name} SET total_like=total_like+1 WHERE album_id='{$album_id}'");
	}
	public function remove_like($album_id)
	{
		return $this->runSql("UPDATE {$this->tbl_name} SET total_like=total_like-1 WHERE album_id='{$album_id}'");
	}

	function update_album_cover($album_id){
		$ptx_share = spClass('ptx_share');
		$conditions['album_id'] = $album_id;
		$shares = $ptx_share->search_no_page($conditions,' ptx_share.share_id,detail.image_path ',null,9);
		$data['total_share'] = $ptx_share->findCount($conditions);
		$data['album_cover'] = str_replace("http://", "", arr_list_to_str($shares));
		return $this->update($conditions,$data);
	}

}
