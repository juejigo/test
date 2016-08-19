<?php

class Userapi_AccountController extends Core2_Controller_Action_Api    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['app_session'] = new Model_AppSession();
		$this->models['imei'] = new Model_Imei();
	}
	
	/**
	 *  注册
	 */
	public function registerAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['member'] = $this->models['member']->createRow(array(
			'role' => 'member',
			'account' => $this->input->account,
			'password' => $this->input->password,
		    'os' => $this->input->os,
		    'uid' => $this->input->uid,
			'status' => 1,
			'register_from' => 10
		));
		
		/* 推荐人 */
		if (!empty($this->input->referee)) 
		{
			$refereeId = $this->_db->select()
				->from(array('m' => 'member'),array('id'))
				->where('m.account = ?',$this->input->referee)
				->query()
				->fetchColumn();
			
			$this->rows['member']->referee_id = $refereeId;
		}
		
		$this->rows['member']->save();
		
		$sessionId = applogin($this->rows['member']->id,$this->input->uid,$this->input->os);
		$this->json['sessid'] = $sessionId;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  登录
	 */
	public function loginAction()
	{
		if (!form($this) || !$this->_authenticate()) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 登录 */
		$sessionId = applogin($this->vars['result_row_object']->id,$this->input->uid,$this->input->os);
		$this->json['sessid'] = $sessionId;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  找回密码
	 */
	public function findpasswordAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$memberId = $this->_db->select()
			->from(array('m' => 'member'),array('id'))
			->where('m.account = ?',$this->input->account)
			->query()
			->fetchColumn();
		
		$sessionId = applogin($memberId);
		$this->json['sessid'] = $sessionId;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  密码校对
	 */
	protected function _authenticate()
	{
		/* 初始化适配器 */
		$adapter = new Zend_Auth_Adapter_DbTable($this->_db,'member','account','password',"MD5(CONCAT(MD5(?),salt)) AND status = 1");
		$adapter->setIdentity($this->input->account);
		$adapter->setCredential($this->input->password);
		$result = $this->_auth->authenticate($adapter);
		
		/* 适配器检验 */
		if (!$result->isValid())
		{
			$this->error->add('account','用户名或密码错误');
			return false;
		}
		
		/* 检验成功 */
		$this->vars['result_row_object'] = $adapter->getResultRowObject();
		return true;
	}
}

?>