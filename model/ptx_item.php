<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_item extends spModel
{
	public $pk = 'item_id';
	public $table = 'ptx_item';
	var $linker = array(
		'share'=>array(
			'type' => 'hasmany',
			'map' => 'share',
			'mapkey' => 'item_id', 
			'fclass' => 'ptx_share',
			'fkey' => 'item_id',
			'enabled' => TRUE
	)
	);


	private function init_conditions($conditions){
		$conditions_item = ' ptx_item.is_deleted=0 ';
		if(isset($conditions['keyword'])){
			$keyword = $this->escape($conditions['keyword']);
			$conditions_item .= "AND MATCH (ptx_item.intro_search) AGAINST ({$keyword} IN BOOLEAN MODE) ";
		}

		if(isset($conditions['is_show'])){
			$conditions_item .= 'AND ptx_item.is_show='.$conditions['is_show'].' ';
		}
		
		if(isset($conditions['shopping'])){
			$conditions_item .= 'AND ptx_item.price>0 ';
		}

		if(strpos($conditions_item, 'AND') === 0){
			$conditions_item = substr($conditions_item, 3);
		}
		return $conditions_item;
	}

	public function search($conditions=NULL,$page,$pagesize,$fields = null){
		$conditions_item = $this->init_conditions($conditions);
		$sort = " ptx_item.item_id DESC ";
		return $this->spPager($page, $pagesize)->findAllJoin($conditions_item,$sort,$fields);
	}

	public function get_item_by_id($mid,$fields=NULL){
		$conditions['item_id'] = $mid;
		$sort = " ptx_item.item_id DESC ";
		return $this->findJoin($conditions,$sort,$fields);
	}

	public function find_one($conditions=NULL,$sort=NULL,$fields=NULL){
		$conditions = $this->init_conditions($conditions);
		return $this->findJoin($conditions,$sort,$fields);
	}

	public function get_one($conditions = null, $sort = null, $fields = null, $limit = null){
		$results = $this->spLinker()->find($conditions,$sort, $fields, $limit);
		return $results;
	}


}
