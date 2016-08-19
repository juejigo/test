<?php

class Core2_Controller_Action_Helper_Notice extends Zend_Controller_Action_Helper_Abstract 
{
	/**
	 *  显示
	 * 
	 *  @param string $text 提示语
	 *  @param string $redirect 转向地址
	 *  @param string $icon 提示类型
	 *  @return void
	 */
	public function display($title,$text,$icon = 'success',$buttons)
	{
		$request = $this->_actionController->getRequest();
		$this->_actionController->view->title = $title;
		$this->_actionController->view->text = $text;
		$this->_actionController->view->icon = $icon;
		$this->_actionController->view->buttons = $buttons;
		$html = $this->_actionController->view->render("public/notice/{$icon}.tpl");
		echo $html;
		exit;
	}
	
	/**
	 *  alias display()
	 * 
	 *  @param string $text 提示语
	 *  @param string $redirect 转向地址
	 *  @param string $icon 提示类型
	 *  @return void
	 */
	public function direct($title,$text,$icon = 'success',$buttons = array())
	{
		$this->display($title,$text,$icon,$buttons);
	}
}

?>