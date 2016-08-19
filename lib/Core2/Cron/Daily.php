<?php

class Core2_Cron_Daily implements Core_Cron_Interface 
{
	/**
	 *  执行
	 */
	public function run()
	{
		$db = Zend_Registry::get('db');
		
		/* 清理 app session */
		
		/* 红包自动过期 */
		
		$db->update('coupon_user',array('status' => -1),array('deadline < ?' => SCRIPT_TIME));
		
// 		/* 商品权重自动减少 */
		
// 		$db->update('product',array('weight' => new Zend_Db_Expr('weight - 50')),array('status = ?' => 2,'weight > ?' => 0));
		
		/* 活动 */
		
		// 生效
		$db->update('activity',array('status' => 1),array('status = ?' => 0,'start_time < ?' => SCRIPT_TIME));
		// 过期
		$db->update('activity',array('status' => 2),array('status = ?' => 1,'end_time < ?' => SCRIPT_TIME));
		
		/* 投票 */
		
		// 自动开始
		$db->update('vote',array('status' => 1),array('status = ?' => 0,'start_time < ?' => SCRIPT_TIME));
		// 自动结束
		$db->update('vote',array('status' => 2),array('status = ?' => 1,'vend_time < ?' => SCRIPT_TIME));
		
		/* 刮刮卡*/
		
		// 自动开始
		$db->update('scrath',array('status' => 1),array('status = ?' => 0,'start_time < ?' => SCRIPT_TIME));
		// 自动结束
		$db->update('scrath',array('status' => 2),array('status = ?' => 1,'end_time < ?' => SCRIPT_TIME));
		
	}
	
	/**
	 *  下次时间
	 */
	public function nextTime()
	{
		$date = getdate();
		return mktime(0,0,0,$date['mon'],$date['mday'],$date['year']) + 86400;
	}
}

?>