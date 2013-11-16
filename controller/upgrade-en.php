<?php
class upgrade extends spController {

	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /></head><body><font color='red'>Update, please backup your database ! Please do not close your browser while upgrading process</font><br/><a href='"
		.spUrl('upgrade','step1',array('do_next'=>1))."'>Click To Update</a></body></html>";
		exit;
	}

	public function step1(){
		$config = spClass('Options');
		if($this->spArgs("do_next")==1){
			if($config->load('config.php')){
				$db = $config->get_item('db');
				$do_upgrade = $this->do_upgrade($db);
				if($do_upgrade['status'] != 200) {
					$this->error = $do_upgrade;
				}else{
					$config->set_item('product_info',array('version' => '3.0 Basic','build' => '0617'));
					$config->set_item('lang',array('en' => APP_PATH.'/lang/en/lang.php','zh_cn' => APP_PATH.'/lang/zh_cn/lang.php'));
					$config->save('config.php');
					$this->jump(spUrl('upgrade', 'step2'));
				}
			}else{
				$this->error = array('status'=>500,'msg'=> 'config.php not exist,pls check');
			}
		}
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /></head><body><h1>Error!</h1><font color='red'>{$this->error['msg']}</font><br/></body></html>";
		exit;
	}

	private function do_upgrade($db_config){

		$link = mysql_connect($db_config['host'].':'.$db_config['port'], $db_config['login'],  $db_config['password']);
		if (!$link) {
			return array('status'=>500,'msg'=>'Cannot connect the database:<br />'. mysql_error());
		}
		if(!mysql_select_db($db_config['database'], $link)){
			return array('status'=>500,'msg'=>'Database not existed!');
		}
		mysql_query("SET NAMES utf8");

		$sql = explode(";",file_get_contents(APP_PATH.'/controller/upgrade.sql'));
		foreach($sql as $query){
			if(trim($query) == '') continue;
			$query = str_replace('{dbpre}', $db_config['prefix'], $query);
			$result = mysql_query($query);
			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n<br />";
				$message .= 'Whole query: ' . $query;
				return array('status'=>500,'msg'=> $message );
			}
		}
		return array('status'=>200,'msg'=> 'Database Update succeed!' );
	}
	public function step2(){
		$page = $this->spArgs("page",1);
		$ptx_category = spClass('ptx_category');
		$category = $ptx_category->find(array('is_system'=>1));
		if($category){
			$ptx_user = spClass('ptx_user');
			$users = $ptx_user->search(null,$page,50,null,NULL);
			foreach ($users as $user) {
				$ptx_album = spClass('ptx_album');
				$data['user_id'] = $user['user_id'];
				$data['album_title'] = 'Default';
				$data['category_id'] = $category['category_id'];
				$album = $ptx_album->find_one($data);
				if(!$album){
					$album_id = $ptx_album->add_one($data);
					
				}else{
					$album_id = $album['album_id'];
				}
				$ptx_share = spClass('ptx_share');
				$share_update['album_id']=$album_id;
				$share_update['category_id']=$category['category_id'];
				$ptx_share->update(array('user_id'=>$user['user_id']),$share_update);
				$ptx_album->update_album_cover($album_id);
			}
			if(array_length($users)>0){
				$start=($page-1)*50;
				$end=$start+50;
				$str = "Processing {$start}----{$end}";
				$url = spUrl('upgrade', 'step2',array('page'=>$page+1));
				echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><meta http-equiv='refresh' content='2;url={$url}'></head><body>{$str}</body></html>";
				exit;
				return;
			}
			echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /></head><body>Congratulations on your successful update, pls delete upgrade.php</body></html>";
			exit;
		}
	}
}