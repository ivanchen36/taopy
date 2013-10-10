<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_user extends spModel
{
	public $pk = 'user_id';
	public $table = 'ptx_user';

	var $addrules = array(
		'rule_checknick' => array('ptx_user', 'checknick'), 
		'rule_checkemail' => array('ptx_user', 'checkemail'), 
	);

	var $verifier_register = array(
		"rules" => array(
			'nickname' => array(  
				'notnull' => TRUE, 
				'minlength' => 2, 
				'maxlength' => 20, 
				'rule_checknick' => TRUE, 
	),
			'password' => array(
				'notnull' => TRUE, 
				'minlength' => 6, 
				'maxlength' => 15,
	),
			'email' => array(  
				'notnull' => TRUE, 
				'email' => TRUE, 
				'minlength' => 5, 
				'maxlength' => 30,
				'rule_checkemail' => TRUE,
	),
	),
		"messages" => array( 
			'nickname' => array(  
				'notnull' => "用户名不能为空", 
				'minlength' => "用户名长度不能少于2",  
				'maxlength' => "用户名长度不能大于20个字符",
				'rule_checknick' => "用户名已存在", 
	),
			'password' => array(  
				'notnull' => "密码不能为空", 
				'minlength' => "密码长度不能少于6个字符",  
				'maxlength' => "密码长度不能大于15个字符", 
	),
			'email' => array(   
				'notnull' => "电子邮件不能为空",
				'email' => "电子邮件格式不正确",  
				'minlength' => "电子邮件长度不能少于5个字符",  
				'maxlength' => "电子邮件长度不能大于30个字符", 
				'rule_checkemail' => "该电子邮件已注册", 
	),
	),
	);


	var $verifier_resetpasswd = array(
		"rules" => array(
			'password' => array(
				'notnull' => TRUE, 
				'minlength' => 6, 
				'maxlength' => 15,
	),
			'email' => array(  
				'notnull' => TRUE, 
				'email' => TRUE, 
				'minlength' => 5, 
				'maxlength' => 30,
				'rule_checkemail' => TRUE,
	),
	),
		"messages" => array( 
			'password' => array(  
				'notnull' => "密码不能为空", 
				'minlength' => "密码长度不能少于6个字符",  
				'maxlength' => "密码长度不能大于15个字符", 
	),
			'email' => array(   
				'notnull' => "电子邮件不能为空",
				'email' => "电子邮件格式不正确",  
				'minlength' => "电子邮件长度不能少于5个字符",  
				'maxlength' => "电子邮件长度不能大于30个字符", 
				'rule_checkemail' => "该电子邮件已注册", 
	),
	),
	);

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


	public function checknick($val, $right)
	{
		return false == $this->find(array("nickname"=>$nickname));
	}

	public function checkemail($val, $right)
	{
		return false == $this->find(array("email"=>$val));
	}

	public function login($data)
	{
		$user = $this->find(array('email'=>$data['email']));
		if($user){
			if($user['user_type']==0){
				$response = array('success' => false, 'message' => T('account_has_been_banned'));
				return $response;
			}
			if( $user['passwd'] === md5($data['password'])){
				$user['password'] = $data['password'];
				spClass('UserLib')->set_session($user,$data['is_remember']);
				$response = array('success' => true, 'message' => T('login_succeed'));
			}else {
				$response = array('success' => false, 'message' => T('password_wrong'));
			}
		}else{
			$response = array('success' => false, 'message' => T('user_not_existed'));
		}
		return $response;
	}


	public function logout($data)
	{
		spClass('UserLib')->remove_session();
		return true;
	}

	public function register($values)
	{
		$this->verifier = $this->verifier_register;

		$verifier_result = $this->spVerifier($values);
		if( false == $verifier_result ){
			$values["passwd"] = md5($values["password"]);

			$user_id = $this->add_one($values);
			$userlib = spClass('UserLib');
			$update_data['avatar_local'] = $userlib->create_default_avatar($user_id);
			if($update_data['avatar_local']){
				$this->update(array('user_id'=>$user_id),$update_data);
			}
			$user = $this->getuser_byid($user_id);
			$user['password'] = $values["password"];
			$userlib->set_session($user);
			$response = array('success' => true, 'message' => T('register_succeed'));

			return $response;
		}else{
			foreach ($verifier_result as $error) {
				$msg = $error[0];
			}
			$response = array('success' => false, 'message' => $msg);
			return $response;
		}
	}

	public function reset_passwd($userid,$values,$password_uc,$is_social=FALSE){
		if($is_social){
			$this->verifier = $this->verifier_resetpasswd;
			$verifier_result = $this->spVerifier($values);
		}else{
			$verifier_result = false;
		}
		if( false == $verifier_result ){
			$values['passwd']=md5($values['password']);
			$this->update(array('user_id'=>$userid), $values);
			$userlib = spClass('UserLib');
			$userlib->refresh_session();
			$response = array('success' => true, 'message' => T('update_succeed'));
			return $response;
		}else{
			foreach ($verifier_result as $error) {
				$msg = $error[0];
			}
			$response = array('success' => false, 'message' => $msg);
			return $response;
		}
	}

	function getuser_byid($uid){
		if($uid)
		return $this->find(array('user_id'=>$uid));
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
