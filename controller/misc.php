<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class misc extends basecontroller
{

	public function __construct() {
		parent::__construct();
	}

	public function smiles(){
		$ptx_smile = spClass('ptx_smile');
		$smiles = $ptx_smile->getSmilies();
		if($smiles){
			$this->ajax_success_response($smiles['smiles'], '');
		}else{
			$this->ajax_failed_response();
		}
	}

	public function adproxy(){
		$key = $this->spArgs("key");
		$ad_position = $this->spArgs("ad_position");
		$ads_array = $this->settings[$ad_position];
		foreach ($ads_array as $ads){
			if($ads['key'] == $key){
				$ads_show = $ads;
				break;
			}
		}
		$this->ad = $ads_show;
		$this->ouput('/misc/adproxy.php');
	}

}