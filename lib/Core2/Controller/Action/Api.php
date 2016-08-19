<?php

class Core2_Controller_Action_Api extends Core2_Controller_Action_Abstract 
{
	/**
	 *  @var array
	 */
	public $json = array();
	
	/**
	 *  预处理
	 */
	public function init()
	{
		parent::init();
		
		$this->_helper->viewRenderer->setNoRender();
	}
}

?>