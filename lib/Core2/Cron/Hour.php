<?php

class Core2_Cron_Hour implements Core_Cron_Interface 
{
	/**
	 *  执行
	 */
	public function run()
	{
		$db = Zend_Registry::get('db');
		
		/* 商品自动上架 */
		
		$db->update('product',array('status' => 2),array('status = ?' => 0,'parent_id = ?' => 0,'up_time < ?' => SCRIPT_TIME));
		
		/* 商品自动下架 */
		
		$db->update('product',array('status' => 3),array('status = ?' => 2,'parent_id = ?' => 0,'down_time < ?' => SCRIPT_TIME));
		
		/* 报名活动混淆排序 */
		
		$sql= "UPDATE `vote_player`
			inner join((SELECT `id`, cast(rand()*(999999-100000)+100000 AS Signed) AS `randnum` FROM `vote_player`)`R`)
			ON `vote_player`.`id` = `R`.`id`
			SET `vote_player`.`order`=`R`.`randnum`";
		$db->query($sql);
	}
	
	/**
	 *  下次时间
	 */
	public function nextTime()
	{
		return SCRIPT_TIME + 3600;
	}
}

?>