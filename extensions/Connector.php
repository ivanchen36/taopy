<?php
class Connector {

	public function __construct()
	{
		$this->session = spClass('Session');
	}

	private function init_vendorinfo($vendor){
		if(isset($vendor)){
			$this->session->unset_data(
				array(
				'social_api_info'=>'',
				'social_user_info'=>'',
				'social_'.$vendor.'_info'=>''
				));
			
			$ptx_settings = spClass('ptx_settings');
			$settings = $ptx_settings->getSettings();
			$vendorinfo = $settings['api_setting'][$vendor];
			if(!$vendorinfo){
				spError('No social setting for:'.$vendor);
			}
			$this->session->set_data('social_api_info',$vendorinfo);
		}
	}

	private function get_vendorinfo($vendor){
		$api_info = $this->session->get_data('social_api_info');
		if(!$api_info){
			$this->init_vendorinfo($vendor);
		}
		$info = array(
			'social_api_info'=>$this->session->get_data('social_api_info'),
			'social_'.$vendor.'_info'=>$this->session->get_data('social_'.$vendor.'_info')
		);
		return $info;
	}

	public function connect($vendor){
		$driver_class = 'Driver_'.$vendor;
		if(file_exists($driver_class_path = APP_PATH.'/extensions/connectors/drivers/'.$driver_class.'.php')) {
			include_once $driver_class_path;
			if(class_exists($driver_class)){
				$this->init_vendorinfo($vendor);
				$info = $this->get_vendorinfo($vendor);
				$driver = new $driver_class($info);
				$driver->goto_loginpage();
			}else{
				spError('Class not Found:'.$driver_class);
			}
		}else{
			spError('File Not Found:'.$driver_class_path);
		}
	}
 
	public function get_accesstoken($vendor){
		$driver_class = 'Driver_'.$vendor;
		if(file_exists($driver_class_path = APP_PATH.'/extensions/connectors/drivers/'.$driver_class.'.php')) {
			include_once $driver_class_path;
			if(class_exists($driver_class)){
				$info = $this->get_vendorinfo($vendor);
				$driver = new $driver_class($info);
				$token = $driver->get_accesstoken();
				if(!token){
					spError('Can\'t get access token:'.$driver_class);
					return;
				}
				$this->session->set_data($token);
				return $token;
			}else{
				spError('Class not Found:'.$driver_class);
			}
		}else{
			spError('File Not Found:'.$driver_class_path);
		}
	}

	public function get_userinfo($vendor){
		$driver_class = 'Driver_'.$vendor;
		if(file_exists($driver_class_path = APP_PATH.'/extensions/connectors/drivers/'.$driver_class.'.php')) {
			include_once $driver_class_path;
			if(class_exists($driver_class)){
				$info = $this->get_vendorinfo($vendor);
				$driver = new $driver_class($info);
				$result = $driver->get_userinfo();
				if(!result||count($result)==0){
					spError('Can\'t get user info:'.$vendor);
					return;
				}
				return $result;
			}else{
				spError('Class not Found:'.$driver_class);
			}
		}else{
			spError('File Not Found:'.$driver_class_path);
		}
	}


}
