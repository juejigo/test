<?php

class Orderuc_SnController extends Core2_Controller_Action_Uc 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['order_item'] = new Model_OrderItem();
	}
	
	/**
	 *  检验
	 */
	public function verifyAction()
	{
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				$json = array();
				$json['errno'] == 0;
				$this->_helper->json($json);
			}
			
			$json = array();
			$json['errno'] == 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
	}
	
	/**
	 *  使用
	 */
	public function useAction()
	{
		if ($this->_request->isGet() && !params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/orderuc/sn/verify',
					'text' => '返回')
			));
		}
		
		$success = 0;    // success为1时,显示消费成功页面
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				$this->rows['order_sn'] = $this->models['order_item']->fetchRow(array('sn = ?' => $this->input->sn));
				$this->rows['order_sn']->available_num -= $this->input->num;
				$this->rows['order_sn']->user_time = SCRIPT_TIME;
				$this->rows['order_sn']->save();
				
				$success = 1;
				$this->view->orderSn = $this->rows['order_sn']->toArray();
				$this->view->num = $this->input->num;
			}
		}
		
		$this->view->sucess = $success;
	}
}