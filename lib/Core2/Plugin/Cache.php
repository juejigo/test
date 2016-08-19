<?php

class Core2_Plugin_Cache extends Zend_Controller_Plugin_Abstract 
{
	/**
	 *  @var Zend_Auth
	 */
	protected $_cache = null;
	
	/**
	 *  预处理
	 * 
	 *  @return void
	 */
	public function __construct()
	{
		$this->_cache = Zend_Registry::get('cache');
	}
	
	/**
	 *  派发前
	 * 
	 *  @param Zend_Controller_Request_Abstract $request
	 *  @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
	}
}

?>