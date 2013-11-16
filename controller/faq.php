<?php
/**
 *      [PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class faq extends basecontroller
{

	public function __construct() {
		parent::__construct();
		$this->faq_nav();
	}
	
	private function faq_nav(){
		$this->fav_nav = $this->render("/faq/faq_nav.php");
	}

	public function about_us(){
		$this->seo_title(T('about_us'));
		$this->ouput("/faq/about_us.php");
	}
	
	public function agreement(){
		$this->seo_title(T('agreement'));
		$this->ouput("/faq/agreement.php");
	}
	
	public function contact_us(){
		$this->seo_title(T('contact_us'));
		$this->ouput("/faq/contact_us.php");
	}
}