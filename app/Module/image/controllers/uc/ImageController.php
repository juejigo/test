<?php

class Imageuc_ImageController extends Core2_Controller_Action_Uc 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['image'] = new Model_Image();
		$this->models['member_profile'] = new Model_MemberProfile();
	}
	
	/**
	 *  上传
	 */
	public function uploadAction()
	{
		/* 取消视图 */
		$this->_helper->viewRenderer->setNoRender();
		
		/* 初始化变量 */
		$this->_vars['json'] = array();
		$this->_vars['image'] = array();
		
		if (!form($this)) 
		{
			$this->_vars['json']['flag'] = 'error';
			$this->_vars['json']['msg'] = $this->error->firstMessage();
			$this->_helper->json($this->_vars['json']);
		}
		
		$image = new Core2_Image($this->input->from);
		if (!$this->_vars['image'] = $image->upload('image'))
		{
			$this->_vars['json']['flag'] = 'error';
			$this->_vars['json']['msg'] = '图片格式错误或图片过大';
			$this->_helper->json($this->_vars['json']);
		}
		
		$this->_vars['json']['flag'] = 'success';
		$this->_vars['json']['url'] = $this->_vars['image']['url'];
		$this->_helper->json($this->_vars['json']);
	}
}

?>