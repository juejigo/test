<?php

class Model_AppSession extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'app_session';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_AppSession';
	
	/**
	 *  生成 ID
	 * 
	 */
	public function autoId()
	{
		$id = md5(SCRIPT_TIME . mt_rand());
		while ($this->find($id)->current()) 
		{
			$this->autoId();
		}
		
		return $id;
	}
}

?>