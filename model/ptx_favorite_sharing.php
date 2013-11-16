<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_favorite_sharing extends spModel
{
	public $pk = 'favorite_id';
	public $table = 'ptx_favorite_sharing';

	var $linker = array(
		'share' => 	array(
				'type' => 'hasone',   
				'map' => 'share',  
				'mapkey' => 'share_id', 
				'fclass' => 'ptx_share', 
				'fkey' => 'share_id', 
				'sort' => 'share_id DESC',
				'enabled' => true     	
		),
		'user' => 	array(
				'type' => 'hasone',   
				'map' => 'user',    
				'mapkey' => 'user_id', 
				'fclass' => 'ptx_user', 
				'fkey' => 'user_id',  
				'sort' => 'user_id DESC', 
				'enabled' => false    
		),
	);
	
	var $other_join = array(
		'item' => 	array(
				'main_table' => 'ptx_item', 
				'main_alias' => 'item', 	
				'main_fkey' => 'item_id', 	
				'sec_table_alias' => 'share', 
				'sec_mapkey' => 'item_id', 	
				'enabled' => true     		
		),
		'category' => 	array(
				'main_table' => 'ptx_category',   
				'main_alias' => 'category', 
				'main_fkey' => 'category_id', 	
				'sec_table_alias' => 'share',   
				'sec_mapkey' => 'category_id',
				'enabled' => true     	
		)
	);
	


	private function init_conditions($conditions){
		$conditions_ret = NULL;
		$conditions_share = NULL;
		$linker = $this->linker;
		if(isset($conditions['user_id'])){
			$conditions_ret .= 'AND ptx_favorite_sharing.user_id='.$conditions['user_id'].' ';
		}
	
		if(isset($conditions['category_id'])){
			$conditions_share .= 'AND share.category_id='.$conditions['category_id'].' ';
		}
	
		if(isset($conditions['share_id'])){
			$conditions_ret .= 'AND ptx_favorite_sharing.share_id='.$conditions['share_id'].' ';
		}
		
		if($conditions_ret){
			if(strpos($conditions_ret, 'AND') === 0){
				$conditions_ret = substr($conditions_ret, 3);
			}
		}
		
		if($conditions_share){
			if(strpos($conditions_share, 'AND') === 0){
				$conditions_share = substr($conditions_share, 3);
			}
			$linker['share']['enabled'] = true;
			$linker['share']['condition'] = $conditions_share;
		}
		$linker['user']['enabled'] = true;
		$this->linker = $linker;
		return $conditions_ret;
	}

	public function search($conditions=NULL,$page,$pagesize,$fields = null){
		$conditions_user = $this->init_conditions($conditions);
		$sort = " ptx_favorite_sharing.favorite_id DESC ";
		return $this->spPager($page, $pagesize)->findAllJoin($conditions_user,$sort,$fields);
	}
	
	public function add_one($data){
		if($this->check_value($data)){
			$data['create_time'] = mktime();
			return $this->create($data);
		}
		return false;
	}
	
	private function check_value($data){
		if(!is_numeric($data['share_id'])){
			return false;
		}
		if(!is_numeric($data['user_id'])){
			return false;
		}
		return true;
	}
	
}
