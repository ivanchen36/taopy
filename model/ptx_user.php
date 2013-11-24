<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_user extends spModel
{
	public $pk = 'user_id';
	public $table = 'ptx_user';

	private function init_conditions($conditions){

		$conditions_user = NULL;
		if(isset($conditions['keyword'])){
			$keyword = $this->escape($conditions['keyword']);
			$conditions_user .= "AND MATCH (ptx_user.nickname) AGAINST ({$keyword} IN BOOLEAN MODE) OR MATCH (ptx_user.email) AGAINST ({$keyword} IN BOOLEAN MODE) ";
		}
		if(isset($conditions['user_type'])){
			$user_type = $this->escape($conditions['user_type']);
			$conditions_user .= "AND ptx_user.user_type={$user_type} ";
		}
		if(strpos($conditions_user, 'AND') === 0){
			$conditions_user = substr($conditions_user, 3);
		}
		return $conditions_user;
	}

	public function search($conditions=NULL,$page,$pagesize,$fields = null,$sort=null){
		$conditions = $this->init_conditions($conditions);
		if(!$sort)
		$sort = " ptx_user.user_id DESC ";
		if(!$fields)
		$fields = " ptx_user.* ";
		return $this->spPager($page, $pagesize)->findAllJoin($conditions,$sort,$fields);
	}

	public function search_no_page($conditions=NULL,$fields = null,$sort=null,$limit){
		if(!$sort)
		$sort = " ptx_user.user_id DESC ";
		return $this->findAllJoin($conditions,$sort,$fields,$limit);
	}

	public function find_userid_by_uname($name_arr){
		$ret .= is_array($name_arr) ? implode(',', self::quote($name_arr)) : self::quote($name_arr);
		return $this->findAll(" ptx_user.nickname in ({$ret}) ", null ,' ptx_user.user_id,ptx_user.nickname ');
	}


	public function logout($data)
	{
		spClass('UserLib')->remove_session();
		return true;
	}

	function getuser_byid($uid){
		if($uid)
		return $this->find(array('user_id'=>$uid));
		else
		return null;
	}

    function getuser_bysuid($uid){
        if($uid)
            return $this->find(array('social_userid'=>$uid));
        else
            return null;
    }

    function is_banned($uid){
        if($uid){
            return $this->find(array('user_id'=>$uid,'user_type'=>0));
        }
		return false;
	}

	function ban_user($uid){
		if($uid){
			return $this->update(array('user_id'=>$uid),array('user_type'=>0));
		}
		return false;
	}
	public function add_one($data){
		if($this->check_value($data)){
			$data['create_time'] = mktime();
			return $this->create($data);
		}
		return false;
	}

	private function check_value($data){
		if(!$data['nickname']){
			return false;
		}
		return true;
	}

	public function add_album($user_id)
	{
		return $this->runSql("UPDATE {$this->tbl_name} SET total_albums=total_albums+1 WHERE user_id='{$user_id}'");
	}
	public function del_album($user_id)
	{
		return $this->runSql("UPDATE {$this->tbl_name} SET total_albums=total_albums-1 WHERE user_id='{$user_id}'");
	}

	public function add_share($user_id)
	{
		$count = spClass('ptx_share')->findCount(array('user_id'=>$user_id));
		return $this->runSql("UPDATE {$this->tbl_name} SET total_shares={$count} WHERE user_id='{$user_id}'");
	}
	public function del_share($user_id)
	{
		$count = spClass('ptx_share')->findCount(array('user_id'=>$user_id));
		return $this->runSql("UPDATE {$this->tbl_name} SET total_shares={$count} WHERE user_id='{$user_id}'");
	}
	public function add_like($user_id)
	{
		return $this->runSql("UPDATE {$this->tbl_name} SET total_likes=total_likes+1 WHERE user_id='{$user_id}'");
	}

}
