<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_share extends spModel
{
	public $pk = 'share_id';
	public $table = 'ptx_share';

	var $linker = array(
		'item'=>array(
			'type' => 'hasone',   
			'map' => 'detail',    
			'mapkey' => 'item_id', 
			'fclass' => 'ptx_item', 
			'fkey' => 'item_id',    
			'enabled' => true     
	),
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
			'enabled' => false     
	),
		'album'=>array(
			'type' => 'hasone',   
			'map' => 'album',    
			'mapkey' => 'album_id', 
			'fclass' => 'ptx_album', 
			'fkey' => 'album_id',    
			'enabled' => false     
	)
	);
	var $select_fields = " ptx_share.*,user.user_title,user.bio,user.avatar_remote,detail.item_id,detail.is_show,detail.img_pro,detail.images_array,detail.title,detail.intro,detail.keywords,detail.image_path,detail.share_attribute,detail.share_type,detail.price,detail.old_price,detail.reference_url,detail.promotion_url,detail.total_images,category.category_name_cn,album.album_title ";

	private function init_conditions($conditions){
		$conditions_item = ' detail.is_deleted=0 ';
		$conditions_user = ' user.user_type>0 ';
		$conditions_share = NULL;
		$conditions_category = NULL;
		$linker = $this->linker;

		$linker['user']['enabled'] = true;
		$linker['user']['condition'] = $conditions_user;
		if(isset($conditions['keyword'])){
			$keyword = self::quote($conditions['keyword']);
			$conditions_item .= "AND MATCH (detail.intro_search) AGAINST ({$keyword} IN BOOLEAN MODE) ";
		}
		if(isset($conditions['category_tag'])){
			$conditions_share .= 'AND ptx_share.category_tag=\''.$conditions['category_tag'].'\' ';
		}
		else if(isset($conditions['category_id'])){
			$conditions_share .= 'AND ptx_share.category_id=\''.$conditions['category_id'].'\' ';
		}
		if(isset($conditions['user_id'])){
			$conditions_share .= 'AND ptx_share.user_id=\''.$conditions['user_id'].'\' ';
		}
		if(isset($conditions['find_next'])){
			$conditions_share .= 'AND ptx_share.share_id > \''.$conditions['find_next'].'\' ';
		}
		if(isset($conditions['lt_time'])){
			$conditions_share .= 'AND ptx_share.create_time < \''.$conditions['lt_time'].'\' ';
		}
		if(isset($conditions['gt_time'])){
			$conditions_share .= 'AND ptx_share.create_time >= \''.$conditions['gt_time'].'\' ';
		}
		if(isset($conditions['find_pre'])){
			$conditions_share .= 'AND ptx_share.share_id < \''.$conditions['find_pre'].'\' ';
		}
		if(isset($conditions['orgin_post'])){
			$conditions_share .= 'AND ptx_share.user_id=ptx_share.poster_id ';
		}
		if(isset($conditions['album_id'])){
			$conditions_share .= 'AND ptx_share.album_id=\''.$conditions['album_id'].'\' ';
		}
		if(isset($conditions['share_id'])){
			$conditions_share .= 'AND ptx_share.share_id=\''.$conditions['share_id'].'\' ';
		}

		if(isset($conditions['id_sub'])){
			$conditions_share .= 'AND (';
			if($cs = $conditions['id_sub']['user_id']){
				$cond = array();
				foreach ($cs as $s) {
					$cond[] = ' (ptx_share.user_id IN ('.$s.')) ';
				}
				$conditions_share .= join(" OR ",$cond).') ';
			}
		}

		if(isset($conditions['is_show'])){
			$conditions_item .= 'AND detail.is_show='.$conditions['is_show'].' ';
		}else{
			$conditions_item .= 'AND detail.is_show>0 ';
		}
		//跟据分类英文名查找
		if(isset($conditions['category_name_en'])){
			$conditions_category .= 'AND category.category_name_en=\''.$conditions['category_name_en'].'\' ';
		}
		//查找所有商品
		if(isset($conditions['shopping'])){
			$conditions_item .= 'AND detail.price>0 ';
		}
		if($conditions_item){
			$linker['item']['enabled'] = true;
			$linker['item']['condition'] = $conditions_item;
		}

		$linker['album']['enabled'] = true;
		$linker['category']['enabled'] = true;
		if($conditions_category){
			if(strpos($conditions_category, 'AND') === 0){
				$conditions_category = substr($conditions_category, 2);
			}
			$linker['category']['condition'] = $conditions_category;
		}
		if(strpos($conditions_share, 'AND') === 0){
			$conditions_share = substr($conditions_share, 3);
		}
		$this->linker = $linker;
		return $conditions_share;
	}

	public function search($conditions=NULL,$page,$pagesize,$fields = null,$sort=null){
		$conditions_share = $this->init_conditions($conditions);
		if(!$sort)
		$sort = " ptx_share.share_id DESC ";
		if(!$fields)
		$fields = $this->select_fields;
		return $this->spPager($page, $pagesize)->findAllJoin($conditions_share,$sort,$fields);
	}

	public function search_no_page($conditions=NULL,$fields = null,$sort=null,$limit){
		$conditions_share = $this->init_conditions($conditions);
		if(!$sort)
		$sort = " ptx_share.share_id DESC ";
		if(!$fields)
		$fields = $this->select_fields;
		return $this->findAllJoin($conditions_share,$sort,$fields,$limit);
	}

	public function count($conditions,$sort=null){
		$conditions_share = $this->init_conditions($conditions);
		return $this->findCountJoin($conditions_share);
	}

	public function get_share_by_id($sid){
		$conditions['share_id'] = $sid;
		return $this->find_one($conditions);
	}

	public function find_one($conditions=NULL,$sort=NULL,$fields=NULL){
		$conditions_share = $this->init_conditions($conditions);
		if(!$fields)
		$fields = $this->select_fields;
		return $this->findJoin($conditions_share,$sort,$fields);
	}

	public function get_all($conditions = null, $page = 1,$sort = null, $fields = null, $limit = null){
		$results = $this->spLinker()->spPager($page, 20)->findAll($conditions,$sort, $fields, $limit);
		return $results;
	}

	public function get_one($conditions = null, $sort = null, $fields = null, $limit = null){
		$results = $this->spLinker()->find($conditions,$sort, $fields, $limit);
		return $results;
	}

	public function get_round($category_id,$limit = null){
		$dbpre = $GLOBALS['G_SP']['db']['prefix'];
		if($category_id)
		$where = " t1.category_id = {$category_id} AND t3.is_deleted=0 ORDER BY t1.share_id ";
		else
		$where = " t3.is_deleted=0 ORDER BY t1.share_id ";
		$sql = " SELECT *
				FROM  `{$dbpre}ptx_share` AS t1
				JOIN (
					SELECT ROUND(
								  RAND() * 
								  ((SELECT MAX( share_id ) FROM  `{$dbpre}ptx_share` ) - ( SELECT MIN( share_id ) FROM  `{$dbpre}ptx_share` )) 
								+ ( SELECT MIN( share_id ) FROM  `{$dbpre}ptx_share` )
								) AS share_rand_id
				) AS t2
				ON t1.share_id >= t2.share_rand_id
				LEFT JOIN  `{$dbpre}ptx_item` t3 ON t1.item_id = t3.item_id
				LEFT JOIN  `{$dbpre}ptx_category` t4 ON t1.category_id = t4.category_id
				LEFT JOIN  `{$dbpre}ptx_album` t5 ON t1.album_id = t5.album_id
                LEFT JOIN  `{$dbpre}ptx_user` t6 ON t1.user_id = t6.user_id
				WHERE {$where}";

		($limit) ? $sql.= " LIMIT {$limit}" : '';

		$result = $this->findSql($sql);
		return $result;

	}

	function find_albums_by_item($item_id){
		$item_id = $this->escape($item_id);
		$sql = "SELECT DISTINCT album_id FROM {$this->tbl_name} WHERE item_id={$item_id}";
		$result = $this->findSql($sql);
		return $result;
	}

	function deleteByPk($pk){
		parent::deleteByPk($pk);
		//second delete comment
		$pk = $this->escape($pk);
		$update_comment_sql = " DELETE FROM `{$this->dbpre}ptx_comment` WHERE share_id=$pk ";
		$this->runSql($update_comment_sql);
	}

	function add_like($share_id)
	{
		return $this->runSql("UPDATE {$this->tbl_name} SET total_likes=total_likes+1 WHERE share_id='{$share_id}'");
	}

	function add_viewnum($share_id)
	{
		return $this->runSql("UPDATE {$this->tbl_name} SET total_clicks=total_clicks+1 WHERE share_id='{$share_id}'");
	}

	function view_count(){
		$count = $this->findSql("SELECT SUM(total_clicks) AS cou FROM {$this->tbl_name}");
		return $count[0]['cou']?$count[0]['cou']:0;
	}
	function like_count(){
		$count = $this->findSql("SELECT SUM(total_likes) AS cou FROM {$this->tbl_name}");
		return $count[0]['cou']?$count[0]['cou']:0;
	}
	function share_count(){
		$count = $this->findSql("SELECT COUNT(*) AS cou FROM {$this->tbl_name}");
		return $count[0]['cou']?$count[0]['cou']:0;
	}
}
