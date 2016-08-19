<?php

class Core2_Cron_Minute implements Core_Cron_Interface 
{
	/**
	 *  执行
	 */
	public function run()
	{
		$db = Zend_Registry::get('db');
		
		/* 清除短信验证码 */
		
		$db->delete('app_smscode',array('dateline < ?' => SCRIPT_TIME - (60*5)));
		
		/* 推荐位自动上架*/
		
		$db->update('position_data',array('status' => 1),array('status = ?' => 0,'up_time < ?' => SCRIPT_TIME,'up_time != ?' => 0));
		
		/* 推荐位自动下架*/
		
		$db->update('position_data',array('status' => 0),array('status = ?' => 1,'down_time < ?' => SCRIPT_TIME,'down_time != ?' => 0));
		
// 		/* 期数上架*/
		
// 		$db->update('one_phase',array('status' => 1),array('status = ?' => 0,'start_time < ?' => SCRIPT_TIME,'start_time > ?' => 0));
		
// 		/* 期数到期*/
		
// 		$model = new Model_OnePhase();
// 		$rowSet = $model->fetchAll(
// 			$model->select()
// 				->where('status in (?)',array(0,1))
// 				->where('end_time < ?',SCRIPT_TIME)
// 				->where('end_time > ?',0)
// 				->limit(10,0)
// 		);
// 		if ($rowSet->count() > 0)
// 		{
// 			foreach ($rowSet as $r)
// 			{
// 				$r->status = 4;
// 				$r->save();
// 			}
			
// 			$row = $model->fetchRow(
// 				$model->select()
// 					->where('product_id = ?',$this->_data['product_id'])
// 					->where('status = ?',0)
// 					->order('no ASC')
// 			);
// 			//自动开始下一期
// 			if (!empty($row) && $row->start_time == 0)
// 			{
// 				$row->status = 1;
// 				$row->save();
// 			}
// 		}
	}
	
	/**
	 *  下次时间
	 */
	public function nextTime()
	{
		return SCRIPT_TIME + 60;
	}
}

?>