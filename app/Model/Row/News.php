<?php

class Model_Row_News extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 时间戳 */
		$this->__set('dateline',SCRIPT_TIME);
		
		/* 状态 */
		$this->__set('status','1');
	}
	
	/**
	 *  数据插入后处理
	 * 
	 *  @return void
	 */
	protected function _postInsert()
	{
		$newsDataModel = new Model_NewsData();
		$newsDataModel->createRow(array(
			'news_id' => $this->_data['id']
		))->save();
	}
}

?>