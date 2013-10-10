<?php
/**
 *     	[PinTuXiu] (C)2001-2099 ONightjar.com Pintuxiu.com.
 *      This is NOT a freeware, use is subject to license terms
 */
class ptx_connector extends spModel
{
	public $pk = 'connect_id';
	public $table = 'ptx_connector';

	var $linker = array(
	);


	function del_connector_by_vendor_uid($vendor,$uid)
	{
		$array = array('user_id' => $uid, 'vendor' => $vendor);
		$this->delete($array);
	}

	function get_bind_by_vendor_and_suid( $vendor, $suid )
	{
		$array = array('social_userid' => $suid, 'vendor' => $vendor);
		return $this->find($array);
	}

	function get_bind_connectors( $uid )
	{
		$array = array('user_id' => $uid);
		return $this->findAll($array);
	}


}
