<?php

class Model_Row_OnePhase extends Zend_Db_Table_Row_Abstract
{
	/**
	 *  数据插入前处理
	 *
	 *  @return void
	 */
	protected function _insert()
	{
		/* 当前人数*/
		
		$this->__set('now_num',0);
		
		/* 添加时间 */
		
		$this->__set('dateline',SCRIPT_TIME);

		/* 状态 */
		
		$this->__set('status','0');
	}
	
	/**
	 *  更新前
	 */
	protected function _update()
	{
		// 上架
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == 1)
		{
			//更新开始时间
			$this->__set('start_time',SCRIPT_TIME);
		}
		// 满人
		else if (in_array('now_num',$this->_modifiedFields) && $this->_data['now_num'] != $this->_cleanData['now_num'] && $this->_data['now_num'] >= $this->_data['need_num'] && $this->_data['status'] == 1)
		{
			//状态改为待揭晓
			$this->__set('status',2);
			//满人时间
			$this->__set('over_time',SCRIPT_TIME);
			//50个时间和
			$payTime = $this->_table->getAdapter()->select()
				->from(array('o' => 'one_order'),'pay_time')
				->where('o.pay_time < ?',SCRIPT_TIME)
				->where('o.status > ?',0)
				->where('o.type = ?',2)
				->order('o.pay_time DESC')
				->limit(50)
				->query()
				->fetchAll();
			$salt = 0;
			if (!empty($payTime))
			{
				foreach ($payTime as $time)
				{
					$salt += date("His",$time['pay_time']);
				}
			}
			$this->__set('salt',$salt);
			//重庆时时彩彩票开奖时间
			$lottery = array(
				'001' => '0005',
				'002' => '0010',
				'003' => '0015',
				'004' => '0020',
				'005' => '0025',
				'006' => '0030',
				'007' => '0035',
				'008' => '0040',
				'009' => '0045',
				'010' => '0050',
				'011' => '0055',
				'012' => '0100',
				'013' => '0105',
				'014' => '0110',
				'015' => '0115',
				'016' => '0120',
				'017' => '0125',
				'018' => '0130',
				'019' => '0135',
				'020' => '0140',
				'021' => '0145',
				'022' => '0150',
				'023' => '0155',
				'024' => '1000',
				'025' => '1010',
				'026' => '1020',
				'027' => '1030',
				'028' => '1040',
				'029' => '1050',
				'030' => '1100',
				'031' => '1110',
				'032' => '1120',
				'033' => '1130',
				'034' => '1140',
				'035' => '1150',
				'036' => '1200',
				'037' => '1210',
				'038' => '1220',
				'039' => '1230',
				'040' => '1240',
				'041' => '1250',
				'042' => '1300',
				'043' => '1310',
				'044' => '1320',
				'045' => '1330',
				'046' => '1340',
				'047' => '1350',
				'048' => '1400',
				'049' => '1410',
				'050' => '1420',
				'051' => '1430',
				'052' => '1440',
				'053' => '1450',
				'054' => '1500',
				'055' => '1510',
				'056' => '1520',
				'057' => '1530',
				'058' => '1540',
				'059' => '1550',
				'060' => '1600',
				'061' => '1610',
				'062' => '1620',
				'063' => '1630',
				'064' => '1640',
				'065' => '1650',
				'066' => '1700',
				'067' => '1710',
				'068' => '1720',
				'069' => '1730',
				'070' => '1740',
				'071' => '1750',
				'072' => '1800',
				'073' => '1810',
				'074' => '1820',
				'075' => '1830',
				'076' => '1840',
				'077' => '1850',
				'078' => '1900',
				'079' => '1910',
				'080' => '1920',
				'081' => '1930',
				'082' => '1940',
				'083' => '1950',
				'084' => '2000',
				'085' => '2010',
				'086' => '2020',
				'087' => '2030',
				'088' => '2040',
				'089' => '2050',
				'090' => '2100',
				'091' => '2110',
				'092' => '2120',
				'093' => '2130',
				'094' => '2140',
				'095' => '2150',
				'096' => '2200',
				'097' => '2205',
				'098' => '2210',
				'099' => '2215',
				'100' => '2220',
				'101' => '2225',
				'102' => '2230',
				'103' => '2235',
				'104' => '2240',
				'105' => '2245',
				'106' => '2250',
				'107' => '2255',
				'108' => '2300',
				'109' => '2305',
				'110' => '2310',
				'111' => '2315',
				'112' => '2320',
				'113' => '2325',
				'114' => '2330',
				'115' => '2335',
				'116' => '2340',
				'117' => '2345',
				'118' => '2350',
				'119' => '2355',
				'120' => '2400',
			);
			//年月日
			$nowDate = date("Ymd",SCRIPT_TIME);
			//时分
			$nowMinute = date("Hi",SCRIPT_TIME);
		
			foreach ($lottery as $ph => $ti)
			{
				//匹配下一期数据
				if ($ti >= $nowMinute)
				{
					//彩票期数
					$this->__set('lottery_phase',$nowDate.$ph);
					if ($ph == '120')
					{
						$this->__set('lottery_time',strtotime($nowDate.'2359')+240);
					}
					else
					{
						$this->__set('lottery_time',strtotime($nowDate.$ti)+180);
					}
					break;
				}
			}
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate()
	{
		//满人后开始新一期
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == 2)
		{
			$model = new Model_OnePhase();
			$row = $model->fetchRow(
				$model->select()
					->where('product_id = ?',$this->_data['product_id'])
					->where('status = ?',0)
					->order('no ASC')
			);
			if (!empty($row) && $row->start_time == 0)
			{
				$row->status = 1;
				$row->save();
			}
		}
		//揭晓后改变幸运号码
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == 3)
		{
			$model = new Model_OneLuckyNum();
			$row = $model->fetchRow(				
				$model->select()
					->where('phase_id = ?',$this->_data['id'])
					->where('lucky_num = ?',$this->_data['lucky_num'])
			);
			if (!empty($row))
			{
				$row->is_win = 1;
				$row->save();
			}
		}
		//到期后退币
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == 4)
		{
			//需退币订单
			$refundOrderList = $this->_table->getAdapter()->select()
				->from(array('o' => 'one_order'),array('amount','buyer_id'))
				->where('o.status > ?',0)
				->where('o.phase_id = ?',$this->_data['id'])
				->query()
				->fetchAll();
			
			if (!empty($refundOrderList))
			{
				$refundOrders = array();
				foreach ($refundOrderList as $refundOrder)
				{
					if (array_key_exists($refundOrder['buyer_id'], $refundOrders))
					{
						$refundOrders[$refundOrder['buyer_id']] += $refundOrder['amount'];
					}
					else
					{
						$refundOrders[$refundOrder['buyer_id']] = $refundOrder['amount'];
					}
				}
				
				$sql = "UPDATE member SET coin = CASE id ";
				$ids = implode(',', array_keys($refundOrders));
				foreach ($refundOrders as $id => $coin)
				{
					$sql .= sprintf("WHEN %d THEN coin+%d ", $id, $coin);
				}
				$sql .= "END WHERE id IN ($ids)";
				$db = Zend_Registry::get('db');
				$db->query($sql);
			}
		}
	}
}

?>