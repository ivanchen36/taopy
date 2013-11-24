<?php
class Channel {

	public function __construct()
	{
	}

	private function get_channelinfo($channel){
		if(isset($channel)&&$channel!='others'){
			$ptx_settings = spClass('ptx_settings');
			$settings = $ptx_settings->getSettings();
			if(!$settings||!$settings['api_setting']['Taobao']){
				spError('您还没有配置淘宝APP key');
				return ;
			}
			switch ($channel)
			{
				case 'taobao':
					$channelinfo = array(
							 'APPKEY'=>$settings['api_setting']['Taobao']['APPKEY'],
							 'APPSECRET'=>$settings['api_setting']['Taobao']['APPSECRET'],
							 'PROMOTION_ID'=>$settings['api_setting']['Taobao']['PID'],
					);
					break;

				default:
					$channelinfo = null;
			}
			return $channelinfo;
		}

		return null;
	}

	public function search_gathering($channel,$param){
		$channel_class = 'Channel_'.$channel;
		$channel_class_path = APP_PATH.'/extensions/channels/'.$channel.'/'.$channel_class.'.php';
		if(file_exists($channel_class_path)) {
			include_once $channel_class_path;
			if(class_exists($channel_class)){
				$data = array();
				$info = $this->get_channelinfo($channel);
				$channel_instance = new $channel_class($info);
				if(method_exists($channel_instance, 'search_gathering')){
					$data = $channel_instance->search_gathering($param);
					return $data;
				}
			}else{
				spError('Class not Found:'.$channel_class);
			}
		}else{
			spError('File Not Found:'.$channel_class_path);
		}
	}

	public function fetch_categories($channel,$parent_id){
		$channel_class = 'Channel_'.$channel;
		$channel_class_path = APP_PATH.'/extensions/channels/'.$channel.'/'.$channel_class.'.php';
		if(file_exists($channel_class_path)) {
			include_once $channel_class_path;
			if(class_exists($channel_class)){
				$data = array();
				$info = $this->get_channelinfo($channel);
				$channel_instance = new $channel_class($info);
				if(method_exists($channel_instance, 'fetch_categories')){
					$data = $channel_instance->fetch_categories($parent_id);
					return $data;
				}
			}else{
				spError('Class not Found:'.$channel_class);
			}
		}else{
			spError('File Not Found:'.$channel_class_path);
		}
	}

	public function fetch_goodinfo($channel,$item_id){
		$channel_class = 'Channel_'.$channel;
		$channel_class_path = APP_PATH.'/extensions/channels/'.$channel.'/'.$channel_class.'.php';
		if(file_exists($channel_class_path)) {
			include_once $channel_class_path;
			if(class_exists($channel_class)){
				$data = array();
				$info = $this->get_channelinfo($channel);
				$channel_instance = new $channel_class($info);
				if(method_exists($channel_instance, 'fetch_goodinfo')){
					$good_info = $channel_instance->fetch_goodinfo($item_id);
					$promotion = $channel_instance->get_promotion_url($item_id);
					if(isset($good_info['orgin_img_url'])){
						$data = array();
						$data['type'] = 'channel';
						$data['channel'] = $channel;
						$data['item_id'] = $item_id;
						$data['name'] = $good_info['name'];
						$data['price'] = $good_info['price'];
						$data['orgin_url'] = $good_info['orgin_url'];
						$data['orgin_image_url'] = $good_info['orgin_img_url'];
						$data['orgin_image_url_small'] = $good_info['orgin_img_url_small'];
						$data['item_imgs'] = $good_info['item_imgs'];
						$data['shop_name'] = $good_info['shop_name'];
						if($promotion){
							$data['promotion_url'] = $promotion['click_url'];
						}else{
							$data['promotion_url'] = $good_info['orgin_url'];
						}
						return $data;
					}
				}
			}else{
				spError('Class not Found:'.$channel_class);
			}
		}else{
			spError('File Not Found:'.$channel_class_path);
		}
	}

	public function fetch_remoteinfo($channel,$url){
		$channel_class = 'Channel_'.$channel;
		$channel_class_path = APP_PATH.'/extensions/channels/'.$channel.'/'.$channel_class.'.php';
		if(file_exists($channel_class_path)) {
			include_once $channel_class_path;
			if(class_exists($channel_class)){
				$data = array();
				$info = $this->get_channelinfo($channel);
				$channel_instance = new $channel_class($info);
				if(method_exists($channel_instance, 'fetch_images')){
					$data = $channel_instance->fetch_images($url);
					return $data;
				}
				$item_id = $channel_instance->get_item_id($url);
				$good_info = $channel_instance->fetch_goodinfo($item_id);
				//$promotion = $channel_instance->get_promotion_url($item_id);
				if(isset($good_info['orgin_img_url'])){
					//$image_data = $this->save_remote_image($good_info['orgin_img_url']);
					$data = array();
					$data['type'] = 'channel';
					$data['channel'] = $channel;
					$data['item_id'] = $item_id;
					$data['name'] = $good_info['name'];
					$data['price'] = $good_info['price'];
					$data['orgin_url'] = $good_info['orgin_url'];
					$data['orgin_image_url'] = $good_info['orgin_img_url'];
					$data['orgin_image_url_small'] = $good_info['orgin_img_url_small'];
					$data['item_imgs'][0] = $good_info['item_imgs'][0];
					$data['shop_name'] = $good_info['shop_name'];
					/*$data['orgin_image'] = $image_data['orgin'];
					 $data['middle_image'] = $image_data['middle'];
					 $data['small_image'] = $image_data['small'];*/
					//if($promotion){
						//$data['promotion_url'] = $promotion['click_url'];
					//}else{
						$data['promotion_url'] = $good_info['orgin_url'];
					//}
					return $data;
				}
			}else{
				spError('Class not Found:'.$channel_class);
			}
		}else{
			spError('File Not Found:'.$channel_class_path);
		}
	}

}
