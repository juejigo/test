<?php

class Core_Plugin_Auth extends Zend_Controller_Plugin_Abstract 
{
	/**
	 *  @var Zend_Auth
	 */
	protected $_auth = null;
	
	/**
	 *  预处理
	 * 
	 *  @return void
	 */
	public function __construct()
	{
		$this->_auth = Zend_Auth::getInstance();
	}
	
	/**
	 *  派发前
	 * 
	 *  设置session身份机制
	 * 
	 *  @param Zend_Controller_Request_Abstract $request
	 *  @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$this->_auth->setStorage(new Zend_Auth_Storage_Session('identity'));
	}
}

?>