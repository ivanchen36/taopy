<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_favorite_album extends spModel
{
	public $pk = 'favorite_id';
	public $table = 'ptx_favorite_album';

	var $linker = array(
		'album' => 	array(
				'type' => 'hasone',   		
				'map' => 'album',    	
				'mapkey' => 'album_id', 	
				'fclass' => 'ptx_album', 	
				'fkey' => 'album_id',   
				'enabled' => true
	),
		'user' => 	array(
				'type' => 'hasone',   		
				'map' => 'user',    	
				'mapkey' => 'user_id', 	
				'fclass' => 'ptx_user', 	
				'fkey' => 'user_id',  
				'enabled' => false
	)
	);

	var $other_join = array(
		'owner' => 	array(
				'main_table' => 'ptx_user',    	
				'main_alias' => 'owner', 	
				'main_fkey' => 'user_id', 	
				'sec_table_alias' => 'album',    
				'sec_mapkey' => 'user_id', 	
				'enabled' => true	
	),
		'category' => 	array(
				'main_table' => 'ptx_category',    	
				'main_alias' => 'category', 	
				'main_fkey' => 'category_id', 	
				'sec_table_alias' => 'album',    
				'sec_mapkey' => 'category_id', 	
				'enabled' => true
	)
	);

	var $select_fields = " album.*,owner.user_id,owner.nickname,owner.user_title,category.category_id,category.category_name_cn ";


	private function init_conditions($conditions){
		$conditions_album = NULL;
		if(isset($conditions['user_id'])){
			$conditions_album .= 'AND ptx_favorite_album.user_id='.$conditions['user_id'].' ';
		}

		if(isset($conditions['category_id'])){
			$conditions_album .= 'AND album.category_id='.$conditions['category_id'].' ';
		}

		if($conditions_album){
			if(strpos($conditions_album, 'AND') === 0){
				$conditions_album = substr($conditions_album, 3);
			}
		}
		return $conditions_album;
	}

	public function search($conditions=NULL,$page,$pagesize,$fields = null){
		$conditions_album = $this->init_conditions($conditions);
		$sort = " ptx_favorite_album.create_time DESC ";
		if(!$fields){
			$fields = $this->select_fields;
		}
		return $this->spPager($page, $pagesize)->findAllJoin($conditions_album,$sort,$fields);
	}

	public function find_one($conditions=NULL){
		$fields = $this->select_fields;
		return $this->findJoin($conditions,null,$fields);
	}

	public function add_one($data){
		if($this->check_value($data)){
			$data['create_time'] = mktime();
			return $this->create($data);
		}
		return false;
	}

	private function check_value($data){
		if(!is_numeric($data['user_id'])){
			return false;
		}
		if(!is_numeric($data['album_id'])){
			return false;
		}
		if($this->find_one($data)){
			return false;
		}
		
		return true;
	}

}
