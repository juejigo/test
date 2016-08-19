<?php

function afterPay($order) 
{
	$db = Zend_Registry::get('db');
	$fundsModel = new Model_Funds();
	
	/* 更新产品购买数量和权重 */
	
	$products = $db->select()
		->from(array('i' => 'order_item'),array('product_id','sum' => new Zend_Db_Expr('SUM(num)')))
		->where('i.order_id = ?',$order['id'])
		->group('i.product_id')
		->query()
		->fetchAll();
	foreach ($products as $product) 
	{
		$db->update('product',array('sells' => new Zend_Db_Expr("sells + {$product['sum']}")),array('id = ?' => $product['product_id']));
		//$db->update('product',array('weight' => new Zend_Db_Expr("weight + 10")),array('id = ?' => $product['product_id']));
	}
}

function afterRechange($order)
{
	//增加余额
	$db = Zend_Registry::get('db');
	$db->update('member',array('coin' => new Zend_Db_Expr("coin + {$order['amount']}")),array('id = ?' => $order['buyer_id']));
}

function afterOnePay($order)
{
	$db = Zend_Registry::get('db');
	$luckyModel = new Model_OneLuckyNum();
	$phaseModel = new Model_OnePhase();
	
	/* 增加期数现有人数*/
	
	$rows['one_phase'] = $phaseModel->find($order['phase_id'])->current();
	$rows['one_phase']->now_num += $order['num'];
	$rows['one_phase']->save();
	
	/* 分配幸运号码*/
	
	$luckyNum = $db->select()
		->from(array('p' => 'one_phase'),array('need_num','now_num'))
		->joinLeft(array('l' => 'one_lucky_num'),'p.id = l.phase_id','lucky_num')
		->where('p.id = ?',$order['phase_id'])
		->query()
		->fetchAll();
	
	//已存在号码
	$num = array();
	foreach ($luckyNum as $value)
	{
		$num[] = $value['lucky_num'];
	}
	
	//初始总号码
	$beginNum = 10000001;
	$endNum = $beginNum + $luckyNum[0]['need_num'] - 1;
	$all = range($beginNum, $endNum);
	
	//未分配号码
	$lucky = array_diff($all, $num);
	
	//随机排序
	shuffle($lucky);
	
	//分配号码
	$sql = 'INSERT INTO `one_lucky_num` (`order_id`,`lucky_num`,`phase_id`,`is_win`) VALUES';
	for ($i = 0;$i < $order['num'];$i++)
	{
		//拼接sql
		$sql .= "('".$order['id']."','".$lucky[$i]."','".$order['phase_id']."',0),";
	}
	$sql = rtrim($sql,',');
	$db->query($sql);
}

function afterFinish($order) 
{
	$db = Zend_Registry::get('db');
				
	if ($order['turnover'] > 0) 
	 {
	 	/* 累计个人消费额度和订单数 */
				
		$db->update('member',array('consumption' => new Zend_Db_Expr("consumption + {$order['turnover']}"),'order_count' => new Zend_Db_Expr("order_count + 1")),array('id = ?' => $order['buyer_id']));
	 }
}

function afterRefund($order)
{
	afterFinish($order);
}

?>