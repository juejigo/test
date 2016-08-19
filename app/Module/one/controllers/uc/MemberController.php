<?php

class Oneuc_MemberController extends Core2_Controller_Action_Uc
{
	/**
	 *  初始化
	 */
	public function init()
	{	
		parent::init();
		
		/* 微信分享 */

		require_once "lib/api/wxwebpay/jssdk.php";
		$jssdk = new JSSDK($this->_config->wx->appid,$this->_config->wx->secret,'');
		$signPackage = $jssdk->GetSignPackage();
		$this->view->wxSign = $signPackage;

		$this->models['member'] = new Model_Member();
		$this->models['one_order'] = new Model_OneOrder();
		$this->models['one_address'] = new Model_OneAddress();
		
		if(!$this->_auth->hasIdentity())
		{
			$session = new Zend_Session_Namespace('wx_redirect_url');
			$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$session->url = $redirectUrl;
			$this->_redirect('/wx/user/auth');
		}
	}

	/**
	 *  首页
	 */
	public function indexAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 用户信息*/
		
		$memberInfo = $this->_db->select()
			->from(array('m' => 'member'),array('coin'))
			->joinLeft(array('f' => 'member_profile'),'m.id = f.member_id',array('member_name','avatar'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetch();
		$this->view->memberInfo = $memberInfo;
	}
	
	/**
	 *  完善信息
	 */
	public function userinfoAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 收货地址信息*/
		
		$addressInfo = $this->_db->select()
			->from(array('a' => 'one_address'))
			->where('a.member_id = ?',$this->_user->id)
			->query()
			->fetch();
		$this->view->addressInfo = $addressInfo;
	}
	
	/**
	 *  ajax
	 */
	public function ajaxAction()
	{
		if (!$this->_request->isXmlHttpRequest())
		{
			exit;
		}
		
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		
		
		/* 检验传值 */
		
		if (!ajax($this))
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		switch ($op)
		{
			/* 保存信息*/
			
			case 'saveinfo':
				
				//是否已经存在
				$issave = $this->_db->select()
					->from(array('a' => 'one_address'),'id')
					->where('a.member_id = ?',$this->_user->id)
					->query()
					->fetchColumn();
				
				//更新
				if (!empty($issave))
				{	
					$this->rows['one_address'] = $this->models['one_address']->fetchRow(
						$this->models['one_address']->select()
							->where('member_id = ?',$this->_user->id)
					);
					$this->rows['one_address']->name = $this->input->name;
					$this->rows['one_address']->mobile = $this->input->mobile;
					$this->rows['one_address']->province_id = $this->input->province_id;
					$this->rows['one_address']->city_id = $this->input->city_id;
					$this->rows['one_address']->county_id = $this->input->county_id;
					$this->rows['one_address']->post_code = $this->input->post_code;
					$this->rows['one_address']->address = $this->input->address;
					$this->rows['one_address']->save();
				}
				//新增
				else
				{
					$this->rows['one_address'] = $this->models['one_address']->createRow(array(
						'member_id' => $this->_user->id,
						'name' => $this->input->name,
						'mobile' => $this->input->mobile,
						'province_id' => $this->input->province_id,
						'city_id' => $this->input->city_id,
						'county_id' => $this->input->county_id,
						'post_code' => $this->input->post_code,
						'address' => $this->input->address,
					));
					$this->rows['one_address']->save();
				}
				$json['errno'] = 0;
				$this->_helper->json($json);
				break;
		
			default:
				break;
		}
	}
}
?>