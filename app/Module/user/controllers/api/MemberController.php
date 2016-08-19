<?php

class Userapi_MemberController extends Core2_Controller_Action_Api    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['app_session'] = new Model_AppSession();
		$this->models['tourist'] = new Model_Tourist();
	}
	
	/**
	 *  用 sessionid 获取用户信息
	 */
	public function userinfoAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['app_session'] = $this->models['app_session']->find($this->input->session_id)->current();
		
		$userInfo = $this->_db->select()
			->from(array('m' => 'member'),array('id','role','group','account','email','deadline'))
			->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('avatar','member_name','alias','sex'))
			->where('m.id = ?',$this->rows['app_session']->member_id)
			->query()
			->fetch();
		
		$userInfo['group_name'] = getGroupName($userInfo['role'],$userInfo['group']);
		
		$this->json['userinfo'] = $userInfo;
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
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
		$r = base64_encode($this->_user->account);
		$url = DOMAIN . "user/account/register?r={$r}";
		$urlEncode = urlencode($url);
		$this->json['url'] = $url;
		$this->json['qrcode_url'] = DOMAIN . "utility/qrcode?content={$urlEncode}";
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	
	/**
	 * 用户旅客列表
	 */
	public function listtouristAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }

	    // 数据
	    $results = $this->_db->select()
    	    ->from(array('p' => 'tourist'))
    	    ->where('p.member_id = ?',$this->_user->id)
    	    ->where('p.status  = ?',1)
    	    ->query()
    	    ->fetchAll();
	    
	    $tourist_list = array();
	    
	    foreach ($results as $row)
	    {
	        $tourist['tourist_id'] = $row['id'];
	        $tourist['tourist_name'] = $row['tourist_name'];
	        $tourist['cert_type'] = $row['cert_type'];
	        $tourist['cert_num'] = $row['cert_num'];
	        $tourist_list[] = $tourist;
	    }

	    $this->json['tourist_list'] = $tourist_list;
	    
	    $this->json['errno'] = '0';
	    $this->_helper->json($this->json);
	}

	/**
	 * 添加旅客信息
	 */
	public function addtouristAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }

	    $this->rows['tourist'] = $this->models['tourist']->createRow(array(
	        'member_id' => $this->_user->id,
	        'tourist_name' => $this->input->tourist_name,
	        'cert_type' => $this->input->cert_type,
	        'cert_num' => $this->input->cert_num,
	        'mobile' => $this->input->mobile,
	        'birthday' => strtotime($this->input->birthday),
	        'sex' => $this->input->sex,
	        'cert_deadline' => strtotime($this->input->cert_deadline),
	        'dateline' => time(),
	        'status' => 1,
	    ));
	    $this->rows['tourist']->save();
	    
	    $this->json['errno'] = '0';
	    $this->json['tourist_id'] = $this->rows['tourist']->id;
	    $this->_helper->json($this->json);
	}
	
	/**
	 * 修改旅客信息
	 */
	public function edittouristAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    
	    $this->rows['tourist'] = $this->models['tourist']->find($this->input->id)->current();
	    $this->rows['tourist']->tourist_name = $this->input->tourist_name;
	    $this->rows['tourist']->cert_type = $this->input->cert_type;
	    $this->rows['tourist']->cert_num = $this->input->cert_num;
	    $this->rows['tourist']->mobile = $this->input->mobile;
	    $this->rows['tourist']->birthday = strtotime($this->input->birthday);
	    $this->rows['tourist']->sex = $this->input->sex;
	    $this->rows['tourist']->cert_deadline = strtotime($this->input->cert_deadline);
	    $this->rows['tourist']->save();
	     
	    $this->json['errno'] = '0';
	    $this->_helper->json($this->json);
	}
	
	/**
	 * 旅客详情
	 */
	public function detailtouristAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    
	    $tourist = $this->_db->select()
	       ->from(array('o' => 'tourist'),array('id as tourist_id','tourist_name','cert_type','cert_num','mobile','birthday','sex','cert_deadline'))
	       ->where('o.status = ?',1)
	       ->where('o.member_id = ?',$this->_user->id)
	       ->where('o.id = ?',$this->input->id)
	       ->query()
	       ->fetch();
	    
	    $tourist['birthday'] = date("Y-m-d",$tourist['birthday']);
	    $tourist['cert_deadline'] = date("Y-m-d",$tourist['cert_deadline']);
	    
	    $this->json['errno'] = '0';
	    $this->json['tourist'] = $tourist;
	    $this->_helper->json($this->json);    
	}
	
	/**
	 * 删除旅客
	 */
	public function deletetouristAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }

	    $this->rows['tourist'] = $this->models['tourist']->find($this->input->id)->current();
	    $this->rows['tourist']->status = -1;
	    $this->rows['tourist']->save();
	    
	    $this->json['errno'] = '0';
	    $this->_helper->json($this->json);
	    
	}
	
}

?>