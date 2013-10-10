<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_category extends spModel
{
	public $pk = 'category_id';
	public $table = 'ptx_category';

	var $linker = array(
		'tag' => array(
			'type' => 'hasmany',   
			'map' => 'has_tags',    
			'mapkey' => 'category_id', 
			'fclass' => 'ptx_tag', 
			'fkey' => 'category_id',    
			'enabled' => false     
	),
		'share' => 	array(
				'type' => 'hasmany',   		
				'map' => 'has_share',    	
				'mapkey' => 'category_id', 	
				'fclass' => 'ptx_share', 	
				'fkey' => 'category_id',    
				'limit' => '4', 			
				'sort' => 'share_id DESC', 	
				'enabled' => false     		
	)
	);

	var $select_fields = " ptx_category.category_id,ptx_category.category_name_cn ";


	public function get_category($conditions = null, $sort = null, $fields = null, $limit = null){
		if(!$sort){
			$sort = ' display_order ASC ';
		}
		$results = parent::findAll($conditions,$sort, $fields, $limit);
		return $results;
	}

	public function find_one($conditions=NULL){
		$fields = $this->select_fields;
		return $this->findJoin($conditions,null,$fields);
	}

	public function get_category_with_tag($conditions = null, $sort = null, $fields = null, $limit = null){
		$results = parent::spLinker()->findAll($conditions,$sort, $fields, $limit);
		return $results;
	}

	public function delete_category($conditions){

		$category_id = $this->escape($conditions['category_id']);

		if($category_id){
			$dbpre = $GLOBALS['G_SP']['db']['prefix'];

			//first update item to deleted
			$update_item_sql = " UPDATE `{$dbpre}ptx_item` SET is_deleted=1 WHERE item_id IN (SELECT t2.item_id FROM `{$dbpre}ptx_share` t2 WHERE t2.category_id={$category_id}) ";
			$this->runSql($update_item_sql);

			//second delete user favorite
			$update_favorite_sql = " DELETE FROM `{$dbpre}ptx_favorite_sharing` WHERE share_id IN (SELECT t2.share_id FROM `{$dbpre}ptx_share` t2 WHERE t2.category_id={$category_id}) ";
			$this->runSql($update_favorite_sql);

			//second delete album favorite
			$update_favorite_album_sql = " DELETE FROM `{$dbpre}ptx_favorite_album` WHERE album_id IN (SELECT t2.album_id FROM `{$dbpre}ptx_album` t2 WHERE t2.category_id={$category_id}) ";
			$this->runSql($update_favorite_album_sql);

			//second delete album
			$update_album_sql = " DELETE FROM `{$dbpre}ptx_album` WHERE category_id={$category_id} ";
			$this->runSql($update_album_sql);

			//second delete comment
			$update_comment_sql = " DELETE FROM `{$dbpre}ptx_comment` WHERE share_id IN (SELECT t2.share_id FROM `{$dbpre}ptx_share` t2 WHERE t2.category_id={$category_id}) ";
			$this->runSql($update_comment_sql);

			//second delete user share
			$update_share_sql = " DELETE FROM `{$dbpre}ptx_share` WHERE category_id={$category_id} ";
			$this->runSql($update_share_sql);

			$this->delete($conditions);
			$this->update_category_top();
		}
	}


	public function get_category_top(){
		$results = spAccess('r','category_top');
		if(!$results){
			$this->update_category_top();
			$results = spAccess('r','category_top');
		}
		return $results;
	}

	public function find_category_byid($cid){
		$categories = $this->get_category_top();
		foreach ($categories as $category) {
			if($category['category_id']==$cid){
				return $category;
			}
		}
	}

	public function update_category_top(){
		$linker = $this->linker;
		$linker['share']['enabled'] = false;
		$linker['tag']['enabled'] = false;
		$linker['item']['condition'] = array('is_deleted'=>0);
		$this->linker = $linker;

		$results = $this->findAll(array('is_open'=>1),' display_order ASC ');
		$ptx_share = spClass('ptx_share');

		foreach($results as $key => $cate){
			$home_shares = unserialize($cate['category_home_shares']);
			if(is_array($home_shares)){
				foreach ($home_shares as $share_key=>$share_value){
					if($share_key=='style'){
						$results[$key][$share_key] = $share_value;
					}else{
						$results[$key][$share_key] = $ptx_share->findJoin(array('share_id' => $share_value));
					}
				}
			}
		}

		spAccess('w','category_top',$results);
	}

}
