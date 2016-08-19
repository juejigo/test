<?php

abstract class Core2_Controller_Action_Abstract extends Zend_Controller_Action
{
	/**
	 *  @var Zend_Auth
	 */
	protected $_auth = null;
	
	/**
	 *  @var Zend_Db_Adapter_Pdo_Abstract
	 */
	protected $_db = null;
	
	/**
	 *  @var Zend_Config_Ini
	 */
	protected $_config = null;
	
	/**
	 *  @var stdClass
	 */
	public $user = null;
	
	/**
	 *  @var array
	 */
	public $models = array();
	
	/**
	 *  @var array
	 */
	public $rows = array();
	
	/**
	 *  @var array
	 */
	public $vars = array();
	
	/*
	 *  @var array
	 */
	public $params = array();
	
	/**
	 *  @var array
	 */
	public $data = array();
	
	/**
	 *  @var Zend_Filter_Input
	 */
	public $input = null;
	
	/*
	 *  @var Zend_Filter_Input
	 */
	public $paramInput = null;
	
	/**
	 *  @var Core_Error
	 */
	public $error = array();
	
	/**
	 *  预处理
	 */
	public function init()
	{
		/* 配置文件 */
		$this->_config = Zend_Registry::get('config');
		
		/* 数据库 */
		$this->_db = Zend_Registry::get('db');
		
		/* 身份证 */
		$this->_auth = Zend_Auth::getInstance();
		if ($this->_auth->hasIdentity())
		{
			$this->_user = new Core2_User($this->_auth->getIdentity()->id);
		}
		else 
		{
			$this->_user = new Core2_User();
		}
		
		/* 城市信息 */
		/*$session = new Zend_Session_Namespace('site');
		$this->_site = new stdClass();
		$this->_site->id = $session->siteId;
		$this->_site->name = $session->siteName;
		$this->_site->url = $session->siteUrl;*/
		
		/* 缓存 */
		$this->_cache = Zend_Registry::get('cache');
		
		/* 错误类 */
		$this->error = new Core_Error();
		
		/* module 文件 */
		$module = strtolower($this->_request->getModuleName());
		$controller = strtolower($this->_request->getControllerName());
		$file = "includes/module/{$module}/{$controller}.php";
		if (file_exists($file)) 
		{
			require_once($file);
		}
		
		/* 计划任务 */
		$cron = new Core_Cron();
		$cron->run();
	}
	
	/**
	 *  派发后
	 */
	public function postDispatch()
	{
		parent::postDispatch();
		
		/* 常用变量 */
		
		$this->view->action = strtolower($this->_request->getActionName());
		$this->view->controller = strtolower($this->_request->getControllerName());
		$this->view->module = strtolower($this->_request->getModuleName());
		$this->view->user = $this->_user;
		//$this->view->site = $this->_site;
		
		$this->view->error = $this->error;
		$this->view->data = $this->data;
		$this->view->params = $this->params;
		
		/* 微信分享url */
		
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if ($this->_auth->hasIdentity()) 
		{
			$r = base64_encode($this->_user->openid);
			$pos = strstr($url,'?') === false ? '?' : '&';
			$url = preg_replace(array('/(&|\?)r=[^&]+/','/(&|\?)from=[^&]+/','/(&|\?)isappinstalled=[^&]+/'),'',$url);
			$fxUrl = "{$url}{$pos}r={$r}";
		}
		else 
		{
			$fxUrl = $url;
		}
		
		$this->view->fxUrl = $fxUrl;
		$this->view->pageUrl = $url;
	}
}

?>