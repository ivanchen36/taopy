<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_smile extends spModel
{
	public $pk = 'smile_id';
	public $table = 'ptx_smile';

	public function getSmilies(){
		$result = spAccess('r', 'smilies');
		if(!$result){
			return $this->updateSmiliesCache();
		}
		return $result;
	}

	public function updateSmiliesCache(){
		$data = array();
		$data = array('searcharray' => array(), 'replacearray' => array(),  'smiles' => array());
		foreach($this->findAll() as $smiley) {
			$data['searcharray'][$smiley['smile_id']] = '/'.preg_quote(dhtmlspecialchars($smiley['code']), '/').'/';
			$data['replacearray'][$smiley['smile_id']] = '<img src="'.base_url().'assets/img/smiles/default/'.$smiley['url'].'" border="0" alt=""  onerror="this.src=\''.base_url().'assets/img/blank.png\'"/>';
			$data['smiles'][] = $smiley;
		}
		spAccess('w','smilies',$data);
		return $data;
	}

}
