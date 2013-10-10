<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_settings extends spModel
{
	public $pk = 'setting_id';
	public $table = 'ptx_settings';

	public function get_value($key){
		$settings = $this->getSettings();
		return unserialize($settings[$key]);
	}

	public function set_value($key,$value){
		$condition['set_key'] = $key;
		$setting = $this->find($condition);
		$data['set_value'] = serialize($value);
		if($setting){
			$this->update($condition,$data);
		}else{
			$data['set_key'] = $key;
			$this->create($data);
		}
		$this->updateSettings();
	}

	public function getSettings(){
		$result = spAccess('r', 'sys_settings');
		if(!$result){
			return $this->updateSettings();
		}
		return $result;
	}

	public function updateSettings(){
		$result = $this->findAll();
		$arr = array();
		foreach ($result as $setting) {
			$arr[$setting['set_key']]=unserialize($setting['set_value']);
		}
		spAccess('w','sys_settings',$arr);
		return $arr;
	}


}
