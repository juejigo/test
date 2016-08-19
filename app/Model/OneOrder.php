<?php

class Model_OneOrder extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'one_order';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_OneOrder';
	
	

	/**
	 *  生成订单号
	 */
	public function createId()
	{
		$y = date('Y',SCRIPT_TIME);
		$z = date('z',SCRIPT_TIME);
		$id = $y . str_pad($z,3,'0',STR_PAD_LEFT) . str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
	
		$exists = $this->fetchRow(
			$this->select()
				->where('id = ?',$id)
		);
		if (empty($exists))
		{
			return $id;
		}
	
		return $this->createId();
	}
}

?>