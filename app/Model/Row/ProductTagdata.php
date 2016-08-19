<?php
class Model_Row_ProductTagdata extends Zend_Db_Table_Row_Abstract
{
	/**
	 *  插入前
	 *
	 *  @return void
	 */
	protected function _insert()
	{
		/* 序号 */
	
		$this->__set('order',1);
		
		/* 排序*/
		
		$this->_table->getAdapter()->update('product_tagdata',array('order' => new Zend_Db_Expr("`order`+1")),array('tag_id = ?' => $this->_data['tag_id']));
	}
	
	/**
	 *  更新前
	 */
	public function _update()
	{
		if (in_array('order', $this->_modifiedFields) && $this->_data['order'] != $this->_cleanData['order'])
		{
			if ($this->_data['order'] > $this->_cleanData['order'])
			{
				$this->_table->getAdapter()->update('product_tagdata',array('order' => new Zend_Db_Expr("`order`-1")),array('`order` <= ?' => $this->_data['order'],'`order` > ?' => $this->_cleanData['order']));
			}
			else
			{
				$this->_table->getAdapter()->update('product_tagdata',array('order' =>  new Zend_Db_Expr("`order`+1")),array('`order` >= ?' => $this->_data['order'],'`order` < ?' => $this->_cleanData['order']));
			}
		}
	}
}

?>