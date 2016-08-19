<?php

class Payapi_BalanceController extends Core2_Controller_Action_Api   
{
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
		
		$this->_helper->viewRenderer->setNoRender();
	}
	
	/**
	 *  余额支付
	 */
	public function payAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->order_id)->current();
		
		/* 余额是否足够 */
		
		$balance = $this->_db->select()
			->from(array('m' => 'member'),array('balance'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		if ($balance < $this->rows['order']->pay_amount) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = '余额不足';
			$this->_helper->json($this->json);
		}
		
		/* 更新订单 */
		
		$this->rows['order']->payment = 'balance';
		$this->rows['order']->pay_time = SCRIPT_TIME;
		$this->rows['order']->status = 1;
		$this->rows['order']->save();
		
		/* 扣除余额 */
		
		$this->rows['member'] = $this->models['member']->find($this->_user->id)->current();
		$this->rows['member']->balance -= $this->rows['order']->pay_amount;
		$this->rows['member']->save();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>