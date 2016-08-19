<?php

class Funds_FundsController extends Core2_Controller_Action_Fr    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['funds'] = new Model_Funds();
	}
	
	/**
	 *  申请提现
	 */
	public function postAction()
	{
		/* 最近提现记录 */
		
		$lastPost = $this->_db->select()
			->from(array('f' => 'funds'))
			->where('f.member_id = ?',$this->_user->id)
			->where('f.type = ?',0)
			->order('dateline DESC')
			->query()
			->fetch();
		
		if ($this->_request->isGet()) 
		{
			/* 根据最近提现记录 自动填充银行卡信息 */
			
			if (!empty($lastPost)) 
			{
				$params = Zend_Serializer::unserialize($lastPost['params']);
			
				$this->data['bank_type'] = $params['type'];
				$this->data['bank_name'] = $params['bank_name'];
				$this->data['bod'] = empty($params['bod']) ? '' : $params['bod'];
				$this->data['card_no'] = $params['card_no'];
				$this->data['owner_name'] = $params['owner_name'];
			}
		}
		
		if ($this->_request->isPost()) 
		{
			$date = getdate();
			$timestamp = mktime(0,0,0,$date['mon'],1,$date['year']);    // 本月第一天时间戳
			
			if (!empty($lastPost) && $lastPost['dateline'] > $timestamp) 
			{
				$this->json['errno'] = '';
				$this->json['errmsg'] = '您本月已提现，请下个月再申请';
				$this->_helper->json($this->json);
			}
			
			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			
			/* 记录提现信息 */
			
			$bankName = '支付宝';
			$switcher = array(
				0 => '支付宝',
				1 => '工商银行',
				2 => '农业银行',
				3 => '中国银行',
				4 => '建设银行'
			);
			if (array_key_exists($this->input->bank_type,$switcher)) 
			{
				$bankName = $switcher[$this->input->bank_type];
			}
			$params = array(
				'type' => $this->input->bank_type,
				'bank_name' => $bankName,
				'bod' => $this->input->bod,
				'card_no' => $this->input->card_no,
				'owner_name' => $this->input->owner_name
			);
			$this->rows['funds'] = $this->models['funds']->createRow(array(
				'member_id' => $this->_user->id,
				'type' => 0,
				'desc' => '提现',
				'params' => Zend_Serializer::serialize($params),
				'money' => "-{$this->input->amount}",
				'status' => 0
			))->save();

			/* 扣除转出 */

			$this->_db->update('member',array('balance' => new Zend_Db_Expr("balance - {$this->input->amount}")),array('id = ?' => $this->_user->id));
			
			$this->json['errno'] = '0';
			$this->json['notice'] = '申请成功，请等待处理';
			$this->json['gourl'] = '/user/member';
			$this->_helper->json($this->json);
		}
	}
}

?>