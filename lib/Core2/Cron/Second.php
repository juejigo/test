<?php

class Core2_Cron_Second implements Core_Cron_Interface 
{
	/**
	 *  执行
	 */
	public function run()
	{
		$db = Zend_Registry::get('db');
		
		/* 未付款订订单自动关闭 */
		
		$model = new Model_Order();
		$rowSet = $model->fetchAll(
			$model->select()
                ->where('status = ?',Model_Order::WAIT_BUYER_PAY)
                ->where('`from` = ?',0)
                ->where('clock < ?',SCRIPT_TIME)
                ->limit(10,0)
		);

		if ($rowSet->count() > 0) 
		{
			foreach ($rowSet as $r) 
			{
				$r->status = -1;
				$r->save();
			}
		}
		
// 		/* 未充值订单自动关闭 */
		
// 		$modelOne = new Model_OneOrder();
// 		$rowSet = $modelOne->fetchAll(
// 			$modelOne->select()
// 				->where('status = ?',0)
// 				->where('clock < ?',SCRIPT_TIME)
// 				->where('type = ?',1)
// 				->limit(10,0)
// 		);
// 		if ($rowSet->count() > 0)
// 		{
// 			foreach ($rowSet as $r)
// 			{
// 				$r->status = -1;
// 				$r->save();
// 			}
// 		}
		
// 		/* 期数开奖*/
		
// 		$modelPhase = new Model_OnePhase();
// 		$rowSet = $modelPhase->fetchAll(
// 			$modelPhase->select()
// 				->where('status = ?',2)
// 				->where('lottery_time < ?',SCRIPT_TIME)
// 				->limit(10,0)
// 		);
// 		if ($rowSet->count() > 0)
// 		{
// 			foreach ($rowSet as $r)
// 			{
// 				$lottery = array();
// 				if (array_key_exists($r->lottery_phase,$lottery))
// 				{
// 					$r->lottery_num = $lottery[$r->lottery_phase];
// 					$r->status = 3;
// 					$r->lottery_time = SCRIPT_TIME;
// 					$db = Zend_Registry::get('db');
// 					$luckyNum = ($r->salt + $r->lottery_num) % $r->need_num + 10000001;
// 					$winner = $db->select()
// 						->from(array('l' => 'one_lucky_num'))
// 						->joinLeft(array('o' => 'one_order'),'l.order_id = o.id','buyer_id')
// 						->where('l.lucky_num = ?',$luckyNum)
// 						->where('l.phase_id = ?',$r->id)
// 						->query()
// 						->fetch();
// 					$r->lucky_num = $luckyNum;
// 					$r->winner_id = $winner['buyer_id'];
// 					$r->save();
// 				}
// 				else
// 				{
// 					$client = new Zend_Http_Client(
// 						null,
// 						array(
// 							'adapter' => 'Zend_Http_Client_Adapter_Curl',
// 							'curloptions' => array(
// 								CURLOPT_SSL_VERIFYPEER => false,
// 								CURLOPT_SSL_VERIFYHOST => false))
// 					);
// 					$lotteryPhase = substr($r->lottery_phase,2);
// 					$client->setUri('http://caipiao.163.com/award/getAwardNumberInfo.html?gameEn=ssc&period='.$lotteryPhase);
// 					$response = $client->request(Zend_Http_Client::GET);
// 					$result = Zend_Json::decode($response->getBody());
// 					if (isset($result['status']) && $result['status'] == 0)
// 					{
// 						$lotteryNum = str_replace(' ','',$result['awardNumberInfoList'][0]['winningNumber']);
// 						$lottery[$r->lottery_phase] = $lotteryNum;
// 						$r->lottery_num = $lotteryNum;
// 						$r->status = 3;
// 						$r->lottery_time = SCRIPT_TIME;
// 						$db = Zend_Registry::get('db');
// 						$luckyNum = ($r->salt + $r->lottery_num) % $r->need_num + 10000001;
// 						$winner = $db->select()
// 							->from(array('l' => 'one_lucky_num'))
// 							->joinLeft(array('o' => 'one_order'),'l.order_id = o.id','buyer_id')
// 							->where('l.lucky_num = ?',$luckyNum)
// 							->where('l.phase_id = ?',$r->id)
// 							->query()
// 							->fetch();
// 						$r->lucky_num = $luckyNum;
// 						$r->winner_id = $winner['buyer_id'];
// 						$r->save();
// 					}
// 					else
// 					{
// 						$r->lottery_time = SCRIPT_TIME + 30;
// 						$r->save();
// 					}
// 				}
// 			}
// 		}
		
		/* 自动确认收货 */
		
		$model = new Model_Order();
		$rowSet = $model->fetchAll(
			$model->select()
				->where('status = ?',Model_Order::WAIT_BUYER_CONFIRM_GOODS)
				->where('clock < ?',SCRIPT_TIME)
				->limit(10,0)
		);
		if ($rowSet->count() > 0) 
		{
			foreach ($rowSet as $r) 
			{
				$r->status = Model_Order::TRADE_FINISHED;
				$r->save();
			}
		}
		
		/*自动发红包*/
		
	    $envelopescheduleModel = new Model_EnvelopeSchedule();
        //获取发红包计划
        $rowSet = $envelopescheduleModel->fetchRow(
            $envelopescheduleModel->select()
            ->where('send_time  < ?',SCRIPT_TIME)
            ->where('send_time != ?',0)
            ->limit(1,0)
        );
        
		if (count($rowSet) > 0) 
		{
		    //查询商户的open_id
		    $memberModel =  new Model_Member();
		    $member= $envelopescheduleModel->fetchRow(
		    $memberModel->select()
    		    ->where('id = ?',intval($rowSet['member_id']))
    		    ->limit(1,0)
		       );

		    $params=array();
		    $params['wishing']  =  '刮刮卡未中奖退还红包';
		    $params['act_name'] =   "刮刮卡未中奖退还红包";
		    $params['remark']   =   '请在24小时内领取';
		    $params['mch_billno'] = mt_rand(1,99999999);
		    $params['send_name'] = '众游网络';
		    
		    // 发送红包
		    $wx = new Core2_Wx();
		    $result = $wx->sendEnvelope($member['openid'],$rowSet['send_money'],$params);
		    
		    if ($result === true)
		    {
		        //修改红包计划表
		        $rowSet->send_time =0;
		        $rowSet->save();
		    
		        //修改红包发送总计划表金额
		        $envelopeschemeModel = new Model_EnvelopeScheme();
		         
		        $envelopeschemeRow = $envelopeschemeModel->fetchRow(
		            $envelopeschemeModel->select()
		            ->where('member_id = ?',$rowSet['member_id'])
		        );
		        $envelopeschemeRow->sent_money += $rowSet['send_money'];
		        $envelopeschemeRow->sent_num += 1;
		        $envelopeschemeRow->save();
		    }
		    else
		    {
		        //修改红包计划表
		        $rowSet->send_time +=600;
		        $rowSet->save();
		    }
		}

		$model = new Model_Product();
		
		/* 子商品自动上架*/
		
		$rowSet = $model->fetchAll(
			$model->select()
				->where('status = ?',0)
				->where('travel_date > ?',SCRIPT_TIME)
				->where('parent_id <> ?',0)
				->limit(10,0)
		);
		if ($rowSet->count() > 0)
		{
			foreach ($rowSet as $r)
			{
				$r->status = 2;
				$r->save();
			}
		}
		
		/* 子商品自动下架*/
		
		$rowSet = $model->fetchAll(
			$model->select()
				->where('status = ?',2)
				->where('travel_date < ?',SCRIPT_TIME)
				->where('parent_id <> ?',0)
				->limit(10,0)
		);
		if ($rowSet->count() > 0)
		{
			foreach ($rowSet as $r)
			{
				$r->status = 3;
				$r->save();
			}
		}
	}
	
	/**
	 *  下次时间
	 */
	public function nextTime()
	{
		return SCRIPT_TIME + 1;
	}
}

?>