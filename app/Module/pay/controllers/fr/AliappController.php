<?php

class Pay_AliappController extends Core2_Controller_Action_Fr  
{
	/**
	 *  @var AlipayNotify
	 */
	protected $_alipayNotify = null;
	
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['order'] = new Model_Order();
		$this->models['product'] = new Model_Product();
		$this->models['member'] = new Model_Member();
		$this->models['funds'] = new Model_Funds();
		$this->models['order_log'] = new Model_OrderLog();
		
		$this->_helper->viewRenderer->setNoRender();
	}
	
	/**
	 *  异步通知页面
	 */
	public function notifyAction()
	{
		require_once("lib/api/aliapp/alipay.config.php");
		require_once("lib/api/aliapp/lib/alipay_notify.class.php");
		
		$this->_alipayNotify = new AlipayNotify($alipay_config);
		
		if (!$this->_alipayNotify->verifyNotify()) 
		{
			echo 'fail';
			exit ;
		}
		
		$orderId = $this->_request->getPost('out_trade_no');
		$tradeNo = $this->_request->getPost('trade_no');
		$outAccount = $this->_request->getPost('buyer_email');
		$amount = $this->_request->getPost('total_fee');
		
		$this->rows['order'] = $this->models['order']->find($orderId)->current();
		if (empty($this->rows['order'])) 
		{
			echo 'fail';
			exit ;
		}
		
		$tradestatus = $this->_request->getPost('trade_status');
		if (!empty($tradestatus)) 
		{
			switch ($tradestatus)
			{
				case 'WAIT_BUYER_PAY':
					if (($this->rows['order']->status == Model_Order::CANCLE || $this->rows['order']->status == Model_Order::WAIT_BUYER_PAY)) 
					{
						/* 更新订单 */
						
						$this->rows['order']->out_id = $tradeNo;
						$this->rows['order']->payment = 'aliapp';
						$this->rows['order']->out_account = $outAccount;
						$this->rows['order']->save();
						
						/* 日志 */
						
						$this->models['order_log']->createRow(array(
							'order_id' => $this->rows['order']->id,
							'operator_id' => 0,
							'desc' => '支付宝订单创建',
						))->save();
					}
					break;
				
				case 'TRADE_SUCCESS' :
					if (($this->rows['order']->status == Model_Order::CANCLE || $this->rows['order']->status == Model_Order::WAIT_BUYER_PAY) && $this->rows['order']->pay_amount == $amount) 
					{
						
						/* 更新订单 */
						
						$this->rows['order']->out_id = $tradeNo;
						$this->rows['order']->payment = 'aliapp';
						$this->rows['order']->out_account = $outAccount;
						$this->rows['order']->status = 1;
						$this->rows['order']->save();
						
						/* 业务处理 */
						
						afterPay($this->rows['order']->toArray());
					}
					echo 'success';
					break ;
					
				default :
					break ;
			}
		}
		
		$refundstatus = $this->_request->getPost('refund_status');
		if (!empty($refundstatus)) 
		{
			switch ($refundstatus) 
			{
				case 'WAIT_SELLER_AGREE' :
					
					echo 'success';
					break ;
					
				case 'SELLER_REFUSE_BUYER' :
					
					echo 'success';
					break ;
				
				case 'WAIT_BUYER_RETURN_GOODS' :
					
					echo 'success';
					break ;
					
				case 'WAIT_SELLER_CONFIRM_GOODS' :
					
					echo 'success';
					break ;
					
				case 'REFUND_SUCCESS' :
					
					echo 'success';
					break ;
			
				default :
					break ;
			}
		}
	}
	/**
	 *  异步通知页面
	 */
	public function refundAction()
	{
		require_once("lib/api/aliapp/alipay.config.php");
		require_once("lib/api/aliapp/lib/alipay_notify.class.php");
	
		$this->_alipayNotify = new AlipayNotify($alipay_config);
	
		if (!$this->_alipayNotify->verifyNotify())
		{
			echo 'fail';
			exit ;
		}
		$result_details = $this->_request->getPost('result_details');
		//2010031906272929^80^SUCCESS$jax_chuanhang@alipay.com^2088101003147483^0.01^SUCCESS
		$result = explode('$', $result_details);
		$status = explode('^', $result[0]);
		$outId = $status['0'];
		$orderId = $this->_db->select()
			->from(array('o' => 'order'),'id')
			->where('o.out_id = ?',$outId)
			->query()
			->fetchColumn();
		$this->rows['order'] = $this->models['order']->find($orderId)->current();
		if (empty($this->rows['order']))
		{
			echo 'fail';
			exit ;
		}
		
		if (!empty($status[2]))
		{
			switch ($status[2])
			{
				case 'SUCCESS':
					if (($this->rows['order']->status == Model_Order::WAIT_SELLER_SEND_GOODS))
					{
						/* 更新订单 */
	
						$this->rows['order']->status = Model_Order::REFUND_SUCCESS;
						$this->rows['order']->save();
	
						/* 日志 */
	
						$this->models['order_log']->createRow(array(
								'order_id' => $this->rows['order']->id,
								'operator_id' => 0,
								'desc' => '支付宝退款成功',
						))->save();
					}
					break;
						
				default :
					break ;
			}
		}
	}
	/**
	 *  异步通知页面
	 */
	public function notify2Action()
	{
	    require_once("lib/api/aliwappay/alipay.config.php");

 		require_once("lib/api/aliwappay/lib/alipay_notify.class.php");

		$this->_alipayNotify = new AlipayNotify($alipay_config);

		if (!$this->_alipayNotify->verifyNotify()) 
		{
			echo 'fail';
			exit ;
		}
		
		$orderId = $this->_request->getPost('out_trade_no');
		$tradeNo = $this->_request->getPost('trade_no');
		$outAccount = $this->_request->getPost('buyer_email');
		$amount = $this->_request->getPost('total_fee');
		
		

		
		$this->rows['order'] = $this->models['order']->find($orderId)->current();
		if (empty($this->rows['order'])) 
		{
			echo 'fail';
			exit ;
		}
		
		$tradestatus = $this->_request->getPost('trade_status');
		if (!empty($tradestatus)) 
		{
			switch ($tradestatus)
			{
				case 'WAIT_BUYER_PAY':
					if (($this->rows['order']->status == Model_Order::CANCLE || $this->rows['order']->status == Model_Order::WAIT_BUYER_PAY)) 
					{
						/* 更新订单 */
						
						$this->rows['order']->out_id = $tradeNo;
						$this->rows['order']->payment = 'aliapp';
						$this->rows['order']->out_account = $outAccount;
						$this->rows['order']->save();
						
						/* 日志 */
						
						$this->models['order_log']->createRow(array(
							'order_id' => $this->rows['order']->id,
							'operator_id' => 0,
							'desc' => '支付宝订单创建',
						))->save();
					}
					break;
				
				case 'TRADE_SUCCESS' :
					if (($this->rows['order']->status == Model_Order::CANCLE || $this->rows['order']->status == Model_Order::WAIT_BUYER_PAY) && $this->rows['order']->pay_amount == $amount) 
					{

						/* 更新订单 */
						
						$this->rows['order']->out_id = $tradeNo;
						$this->rows['order']->payment = 'aliweppay';
						$this->rows['order']->out_account = $outAccount;
						$this->rows['order']->status = 1;
						$this->rows['order']->save();
						
						/* 业务处理 */
						
						afterPay($this->rows['order']->toArray());
					}
					echo 'success';
					break ;
					
				default :
					break ;
			}
		}
		
		$refundstatus = $this->_request->getPost('refund_status');
		if (!empty($refundstatus)) 
		{
			switch ($refundstatus) 
			{
				case 'WAIT_SELLER_AGREE' :
					
					echo 'success';
					break ;
					
				case 'SELLER_REFUSE_BUYER' :
					
					echo 'success';
					break ;
				
				case 'WAIT_BUYER_RETURN_GOODS' :
					
					echo 'success';
					break ;
					
				case 'WAIT_SELLER_CONFIRM_GOODS' :
					
					echo 'success';
					break ;
					
				case 'REFUND_SUCCESS' :
					
					echo 'success';
					break ;
			
				default :
					break ;
			}
		}
	}

	/**
	 *  异步通知页面
	 */
	public function notify3Action()
	{
		require_once("lib/api/aliwebpay/alipay.config.php");
		require_once("lib/api/aliwebpay/lib/alipay_notify.class.php");
		file_put_contents('1.txt',serialize($_POST));
		$this->_alipayNotify = new AlipayNotify($alipay_config);
		
		if (!$this->_alipayNotify->verifyNotify()) 
		{
			echo 'fail';
			exit ;
		}
		
		$orderId = $this->_request->getPost('out_trade_no');
		$tradeNo = $this->_request->getPost('trade_no');
		$outAccount = $this->_request->getPost('buyer_email');
		$amount = $this->_request->getPost('total_fee');
		
		$this->rows['order'] = $this->models['order']->find($orderId)->current();
		if (empty($this->rows['order'])) 
		{
			echo 'fail';
			exit ;
		}
		
		$tradestatus = $this->_request->getPost('trade_status');
		if (!empty($tradestatus)) 
		{
			switch ($tradestatus)
			{
				case 'WAIT_BUYER_PAY':
					if (($this->rows['order']->status == Model_Order::CANCLE || $this->rows['order']->status == Model_Order::WAIT_BUYER_PAY)) 
					{
						/* 更新订单 */
						
						$this->rows['order']->out_id = $tradeNo;
						$this->rows['order']->payment = 'aliapp';
						$this->rows['order']->out_account = $outAccount;
						$this->rows['order']->save();
						
						/* 日志 */
						
						$this->models['order_log']->createRow(array(
							'order_id' => $this->rows['order']->id,
							'operator_id' => 0,
							'desc' => '支付宝订单创建',
						))->save();
					}
					break;
				
				case 'TRADE_SUCCESS' :
					if (($this->rows['order']->status == Model_Order::CANCLE || $this->rows['order']->status == Model_Order::WAIT_BUYER_PAY) && $this->rows['order']->pay_amount == $amount) 
					{
						
						/* 更新订单 */
						
						$this->rows['order']->out_id = $tradeNo;
						$this->rows['order']->payment = 'aliapp';
						$this->rows['order']->out_account = $outAccount;
						$this->rows['order']->status = 1;
						$this->rows['order']->save();
						
						/* 业务处理 */
						
						afterPay($this->rows['order']->toArray());
					}
					echo 'success';
					break ;
					
				default :
					break ;
			}
		}
		
		$refundstatus = $this->_request->getPost('refund_status');
		if (!empty($refundstatus)) 
		{
			switch ($refundstatus) 
			{
				case 'WAIT_SELLER_AGREE' :
					
					echo 'success';
					break ;
					
				case 'SELLER_REFUSE_BUYER' :
					
					echo 'success';
					break ;
				
				case 'WAIT_BUYER_RETURN_GOODS' :
					
					echo 'success';
					break ;
					
				case 'WAIT_SELLER_CONFIRM_GOODS' :
					
					echo 'success';
					break ;
					
				case 'REFUND_SUCCESS' :
					
					echo 'success';
					break ;
			
				default :
					break ;
			}
		}
	}

}


?>