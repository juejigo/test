<?php

class Model_Row_Order extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 订单号 */
		
		if (empty($this->_data['id'])) 
		{
			$this->__set('id',$this->_table->createId());
		}
		
		/* 显示 */
		
//		$this->__set('display',1);
		
		/* 未付款 设置60分钟自动关闭 */
		
		$this->__set('clock',SCRIPT_TIME + (60*60));
		
		/* 订单总金额 */
		
	//	$this->__set('amount',$this->_data['item_amount'] + $this->_data['shipping']);
		
		/* 时间戳 */
		
		$this->__set('dateline',SCRIPT_TIME);
		
		/* 显示*/
		
		$this->__set('display', 1);
	}
	
	/**
	 *  更新前
	 */
	protected function _update() 
	{
		// 待付款
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::WAIT_BUYER_PAY ) 
		{
			// 设置60分钟过期
			$this->__set('clock',SCRIPT_TIME + (5*60));
		}
		// 已付款
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::WAIT_SELLER_SEND_GOODS) 
		{
			// 支付时间
			$this->__set('pay_time',SCRIPT_TIME);
		}
		// 发货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::WAIT_BUYER_CONFIRM_GOODS) 
		{
			// 设置14天自动收货
			$this->__set('clock',SCRIPT_TIME + (60*60*24*14));
		}
		// 确认收货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::TRADE_FINISHED) 
		{
			// 记录订单结束时间
			$this->__set('finish_time',SCRIPT_TIME);
			
			// 营业额
		//	$this->__set('turnover',$this->_data['pay_amount']);
		}
		// 同意退款需要退货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::WAIT_BUYER_RETURN_GOODS) 
		{
			// 设置3天时间发货
			$this->__set('clock',SCRIPT_TIME + (60*60*24*3));
		}
		// 退货成功
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::REFUND_SUCCESS) 
		{
			// 记录订单结束时间
			$this->__set('finish_time',SCRIPT_TIME);
			
			// 营业额
		//	$turnover = $this->_data['pay_amount'] - $this->_data['refund_amount'];
		//	$this->__set('turnover',$turnover);
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate() 
	{
		/* 订单状态 */
		
		// 关闭
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::CANCLE && $this->_data['from'] == 0) 
		{
			// 优惠回滚
			$discounts = $this->_table->getAdapter()->select()
				->from(array('d' => 'order_discount'))
				->where('d.order_id = ?',$this->_data['id'])
				->where('d.status = ?',1)
				->query()
				->fetchAll();
			foreach ($discounts as $d) 
			{
				if ($d['type'] == 0) 
				{
					$this->_table->getAdapter()->update('member',array('point' => new Zend_Db_Expr("point + {$d['amount']}")),array('id = ?' => $this->_data['buyer_id']));
				}
				else if ($d['type'] == 1) 
				{
					$this->_table->getAdapter()->update('coupon_user',array('status' => 1),array('id = ?' => $d['coupon_user_id'],'deadline > ?' => SCRIPT_TIME));
				}
			}
			$this->_table->getAdapter()->update('order_discount',array('status' => 0),array('order_id = ?' => $this->_data['id']));
			
			// 库存回滚
			$items = $this->_table->getAdapter()->select()
				->from(array('i' => 'order_item'))
				->where('i.order_id = ?',$this->_data['id'])
				->query()
				->fetchAll();
			foreach ($items as $item) 
			{
				$this->_table->getAdapter()->update('product_item',array('stock' => new Zend_Db_Expr("stock + {$item['num']}")),array('id = ?' => $item['item_id']));
			}
		}
		// 支付完成
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::WAIT_SELLER_SEND_GOODS) 
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => 0,
				'desc' => '订单支付成功',
			))->save();
			
			require_once('includes/function/order.php');
			afterPay($this->_data);
			
			$sms = new Core_Sms();
			$content = sprintf('【友趣游】%s，您好！后台已收到您的订单：%s，我们将在工作时间1小时内处理并确认。确认后您将会收到订单确认信息，请注意查收！客服工作时间为：09:00-22:30',$this->_data['buyer_name'],$this->_data['id']);
			$result = $sms->send($this->_data['mobile'],$content);
		}
		// 发货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::WAIT_BUYER_CONFIRM_GOODS) 
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'desc' => '已发货',
			))->save();
			
			$sms = new Core_Sms();
			$content = sprintf('【友趣游】%s，您好！您的订单：%s已确认！请您准备好旅途中所需要的身份信息及资料，我们的客服人员会及时与您取得联系，预祝您旅途愉快！ ',$this->_data['buyer_name'],$this->_data['id']);
			$result = $sms->send($this->_data['mobile'],$content);
		}
		// 确认收货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::TRADE_FINISHED)
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => Zend_Auth::getInstance()->hasIdentity() ? Zend_Auth::getInstance()->getIdentity()->id : 0,    // 自动确认收货
				'desc' => '确认收货',
			))->save();
			
			require_once('includes/function/order.php');
			afterFinish($this->_data);
		}
		// 申请退货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::WAIT_SELLER_AGREE)
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'desc' => '申请退货',
			))->save();
		}
		// 同意退货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] == Model_Order::WAIT_BUYER_RETURN_GOODS)
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'desc' => '同意退货',
			))->save();
		}
		// 买家发货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] == Model_Order::WAIT_SELLER_CONFIRM_GOODS)
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'desc' => '买家已发货',
			))->save();
		}
		// 退款成功
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::REFUND_SUCCESS)
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'desc' => '退货成功',
			))->save();
			
			require_once('includes/function/order.php');
			afterRefund($this->_data);
			
			$sms = new Core_Sms();
			$content = sprintf('【友趣游】%s，您好！很遗憾的通知您，您的订单：%s因已售罄未被确认，退款将原路返回。更多旅游尾单，请持续关注友趣游，祝您生活愉快！',$this->_data['buyer_name'],$this->_data['id']);
			$result = $sms->send($this->_data['mobile'],$content);
		}
		// 拒绝退货
		else if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_Order::SELLER_REFUSE_BUYER)
		{
			// 订单记录
			$model = new Model_OrderLog();
			$model->createRow(array(
				'order_id' => $this->_data['id'],
				'operator_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'desc' => '拒绝退货',
			))->save();
			
			require_once('includes/function/order.php');
			afterFinish($this->_data);
		}
	}
}

?>