<?php

class Imageuc_IndexController extends Core2_Controller_Action_Uc 
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
	 *  图片上传页面
	 */
	public function indexAction()
	{
	}
}

?>