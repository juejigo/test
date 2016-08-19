<?php

class User_MemberController extends Core2_Controller_Action_Fr    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member_profile']  = new Model_MemberProfile();
		
	}

	/**
	 *  用户中心首页
	 */
	public function indexAction()
	{
		
	}
	
	/**
	 *  用户信息
	 */
	public function userinfoAction()
	{
	    $user = $this->_db->select()   
	       ->from(array('m' => 'member_profile'))
	      // ->joinLeft(array('e' => 'member_profile'), 'm.id = e.member_id')
	       ->where('m.member_id = ?',$this->_user->id)
	       ->query()
	       ->fetch();
	    $this->view->county_id = $user['county_id'];
	    $this->view->birthday = $user['birthday'];    
	    $this->view->userinfo = $user;
	    $this->view->from = 3;
	}
	
	
	public function profileAction()
	{
	    $user = $this->_db->select()
	    ->from(array('m' => 'member_profile'))
	    // ->joinLeft(array('e' => 'member_profile'), 'm.id = e.member_id')
	    ->where('m.member_id = ?',$this->_user->id)
	    ->query()
	    ->fetch();
	    $this->view->county_id = $user['county_id'];
	    $this->view->birthday = $user['birthday'];
	    $this->view->userinfo = $user;
	    $this->view->from = 3;
	}
	/**
	 *  冒泡信息
	 */
	public function bubbleAction()
	{
		/* 冒泡 */
		
		$this->json['order'] = array();
		
		$rs = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.buyer_id = ?',$this->_user->id)
			->where('o.status IN (?)',array(Model_Order::WAIT_BUYER_PAY,Model_Order::WAIT_SELLER_SEND_GOODS,Model_Order::WAIT_BUYER_CONFIRM_GOODS,Model_Order::TRADE_FINISHED))
			->where('o.feedback = ?',0)
			->where('o.display = ?',1)
			->query()
			->fetchAll();
		
		$waitBuyerPay = 0;
		$waitSellerSendGoods = 0;
		$waitBuyerConfirmGoods = 0;
		$waitFeedback = 0;
		if (!empty($rs)) 
		{
			foreach ($rs as $r) 
			{
				if ($r['status'] == Model_Order::WAIT_BUYER_PAY) 
				{
					$waitBuyerPay += 1;
				}
				else if ($r['status'] == Model_Order::WAIT_SELLER_SEND_GOODS) 
				{
					$waitSellerSendGoods += 1;
				}
				else if ($r['status'] == Model_Order::WAIT_BUYER_CONFIRM_GOODS) 
				{
					$waitBuyerConfirmGoods += 1;
				}
				else if ($r['status'] == Model_Order::TRADE_FINISHED) 
				{
					$waitFeedback += 1;
				}
			}
		}
		$this->json['order']['wait_buyer_pay'] = $waitBuyerPay;
		$this->json['order']['wait_seller_send_goods'] = $waitSellerSendGoods;
		$this->json['order']['wait_buyer_confirm_goods'] = $waitBuyerConfirmGoods;
		$this->json['order']['wait_feedback'] = $waitFeedback;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  我的二维码
	 */
	public function qrcodeAction() 
	{
		$r = base64_encode($this->_user->account ? $this->_user->account : $this->_user->openid);
		$url = DOMAIN . "?r={$r}";
		$urlEncode = urlencode($url);
		$this->json['url'] = $url;
		$this->json['qrcode_url'] = DOMAIN . "utility/qrcode?content={$urlEncode}";
		$this->view->qrcode_url = $this->json['qrcode_url'];
	}


	public function safetyAction()
	{
	}
	
	/**
	 * 异步
	 */
	public function ajaxAction()
	{
	    
	    if (!$this->_request->isXmlHttpRequest())
	    {
	        exit ;
	    }
	     
	    $op = $this->_request->getQuery('op','');
	    $json = array();
	    $this->_helper->viewRenderer->setNoRender();
	     
	    if (!ajax($this))
	    {
	        $json['errno'] = '1';
	        $json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($json);
	    }
	    
	    switch ($op)
	    {
	        case 'edit_userinfo':
	            
	            $this->rows['member_profile'] = $this->models['member_profile']->find($this->_user->id)->current();
	          
	            $this->rows['member_profile']->member_name = $this->input->member_name;
	            $this->rows['member_profile']->birthday = strtotime($this->input->birthday);
	            $this->rows['member_profile']->sex = $this->input->sex;
	            $this->rows['member_profile']->province_id = $this->input->province_id;
	            $this->rows['member_profile']->city_id = $this->input->city_id;
	            $this->rows['member_profile']->county_id = $this->input->county_id;
	            $this->rows['member_profile']->address = $this->input->address;
	            $this->rows['member_profile']->save();
	            
	            $this->json['errno'] = '0';
	            $this->_helper->json($this->json);
	            
	            break;
	    }
	}
	


}

?>