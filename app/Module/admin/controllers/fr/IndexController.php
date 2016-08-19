<?php

class Admin_IndexController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  登录
	 */
	public function indexAction()
	{
		/* 清除登录 */
		/*if ($this->_request->isGet()) 
		{
			Zend_Auth::getInstance()->clearIdentity();
			setcookie('account','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
			setcookie('password','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
		}*/
		
		if ($this->_auth->hasIdentity()) 
		{
			$this->_redirect('/admincp');
		}
		
		/* 表单提交 */
		if (!$this->_auth->hasIdentity() && $this->_request->isPost()) 
		{
			/* 检验表单 */
			if (form($this) && $this->_authenticate()) 
			{
				/* 登录 */
				login($this->vars['result_row_object']->id);
				
				/* 记住密码 */
				if ($this->input->remember) 
				{
					setcookie('account',$this->input->account,SCRIPT_TIME + (60 * 60 * 24 * 7),'/',$this->_config->site->domain);
					setcookie('password',$this->vars['result_row_object']->password,SCRIPT_TIME + (60 * 60 * 24 * 7),'/',$this->_config->site->domain);
				}
				else 
				{
					setcookie('account','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
					setcookie('password','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
				}
				
				$this->_redirect('/admincp');
			}
		}
	}
	
	/**
	 *  密码校对
	 */
	protected function _authenticate()
	{
		/* 初始化适配器 */
		$adapter = new Zend_Auth_Adapter_DbTable($this->_db,'member','account','password',"MD5(CONCAT(MD5(?),salt)) AND role IN ('admin','sa') AND status = 1");
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
	
	/**
	 *  注销
	 */
	public function logoutAction()
	{
		/* 清除身份 */
		Zend_Auth::getInstance()->clearIdentity();
		
		/* 清除 cookie */
		setcookie('account','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
		setcookie('password','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
		
		$this->_redirect('/admin');
	}
}

?>