<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_comment extends spModel
{
	public $pk = 'comment_id';
	public $table = 'ptx_comment';
	var $linker = array(
		'user'=>array(
			'type' => 'hasone',  
			'map' => 'user',
			'mapkey' => 'user_id', 
			'fclass' => 'ptx_user',
			'fkey' => 'user_id',
			'enabled' => true
		),
		'share'=>array(
			'type' => 'hasone',  
			'map' => 'share',
			'mapkey' => 'share_id', 
			'fclass' => 'ptx_share',
			'fkey' => 'share_id',
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
				'enabled' => false
		)
	);
	
	var $select_fields = " ptx_comment.comment_id,ptx_comment.share_id,ptx_comment.comment_txt,ptx_comment.create_time,user.user_id,user.nickname,user.avatar_remote ";

	private function init_conditions($conditions){
		if(isset($conditions['keyword'])){
			$keword = $this->escape($conditions['keyword']);
			$conditions_rt .= "AND MATCH (ptx_comment.search_en) AGAINST ({$keword} IN BOOLEAN MODE) ";
		}
		if(isset($conditions['share_id'])){
			$share_id = $this->escape($conditions['share_id']);
			$conditions_rt .= "AND ptx_comment.share_id={$share_id} ";
		}
		if(isset($conditions['need_item'])){
			$linker = $this->linker;
			$linker['share']['enabled']=true;
			$this->linker = $linker;
			
			$other_join = $this->other_join;
			$other_join['item']['enabled']=true;
			$this->other_join = $other_join;
			$this->select_fields .= ",share.user_id as share_user_id,share.user_nickname as share_user_nickname,item.intro as item_intro";
		}
		if(strpos($conditions_rt, 'AND') === 0){
			$conditions_rt = substr($conditions_rt, 3);
		}
		return $conditions_rt;
	}

	public function search($conditions=NULL,$page,$pagesize,$fields = null,$sort=null){
		$conditions = $this->init_conditions($conditions);
		if(!$sort)
		$sort = " ptx_comment.comment_id DESC ";
		if(!$fields){
			$fields = $this->select_fields;
		}
		return $this->spPager($page, $pagesize)->findAllJoin($conditions,$sort,$fields);
	}

	public function search_no_page($conditions=NULL,$fields = null,$sort=null,$limit){
		$conditions = $this->init_conditions($conditions);
		if(!$sort)
		$sort = " ptx_comment.comment_id DESC ";

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
			$id = $this->create($data);
			$this->update_share($data['share_id']);
			return $id;
		}
		return false;
	}

	public function del_one($data){
		if($data['share_id']&&$data['comment_id']){
			$this->delete(array('comment_id'=>$data['comment_id']));
			$this->update_share($data['share_id']);
			return true;
		}
		return false;
	}

	private function check_value($data){
		if(!is_numeric($data['share_id'])){
			return false;
		}
		return true;
	}

	public function update_share($share_id){
		$ptx_share = spClass('ptx_share');
		$conditions['share_id'] = $share_id;
		$comments = $this->search_no_page($conditions,' ptx_comment.comment_id,ptx_comment.share_id,ptx_comment.comment_txt,ptx_comment.create_time,user.user_id,user.nickname ',null,5);
		$total_comments = $this->findCount($conditions);
		$ptx_share->update($conditions,array('total_comments'=>$total_comments,'comments'=>serialize($comments)));
	}


	function comment_count(){
		$count = $this->findSql("SELECT COUNT(*) AS cou FROM {$this->tbl_name}");
		return $count[0]['cou']?$count[0]['cou']:0;
	}

}
