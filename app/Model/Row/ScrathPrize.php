<?php

class Model_Row_ScrathPrize extends Zend_Db_Table_Row_Abstract 
{	

	/**
	 *  更新后
	 *
	 *  @return void
	 */
	protected function _postUpdate()
	{
		if (in_array('is_deliver',$this->_modifiedFields) && $this->_data['is_deliver'] != $this->_cleanData['is_deliver'] && $this->_data['is_deliver'] == 1)
		{
			//更新后改变时间
			$this->_table->getAdapter()->update('scrath_prize',array('add_time' => SCRIPT_TIME),array('id = ?' => $this->_data['id']));
			
			// 消息提醒
			$wx = new Core2_Wx();
			$touser = $this->_table->getAdapter()->select()
				->from(array('m' => 'member'),array('openid'))
				->where('id = ?',$this->_data['member_id'])
				->query()
				->fetchColumn();
			
			
			$productInfo = $this->_table->getAdapter()->select()
				->from(array('p' => 'scrath_product'),array('product_level','product_name'))
				->where('p.id = ?',$this->_data['product_id'])
				->query()
				->fetch();
			$data = array(
					'first' => array(
							'value' => '恭喜您，中奖的货物已发货。',
							'color' => '#173177'
					),
					'keyword1' => array(
							'value' => $this->_data['invoice_number'].'(物流单号)',
							'color' => '#173177'
					),
					'keyword2' => array(
							'value' => $this->_data['express'],
							'color' => '#173177'
					),
					'keyword3' => array(
							'value' => '4006-117-121',
							'color' => '#173177'
					),
					'keyword4' => array(
							'value' => $productInfo['product_level'].':'.$productInfo['product_name'],
							'color' => '#173177'
					),
					'remark' => array(
							'value' => '众游网络感谢您的参与',
							'color' => '#173177'
					), 
			);
			$wx->sendMessageTpl($touser,'7cegf5TVCxLSWvCa7NuY2poeLOvrD6pp7YA6UYw1_Jc',DOMAIN . '/scrathuc/card',$data);
		}
	}
}

?>