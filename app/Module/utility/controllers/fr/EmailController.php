<?php

class Utility_EmailController extends Core2_Controller_Action_Fr
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['member_profile'] = new Model_MemberProfile();
		$this->models['mail'] = new Model_Mail();
	}
	
	/**
	 *  验证
	 */
	public function authAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			$this->_helper->notice('出错啦','参数错误','error_1',array());
		}
		
		/* 更新邮件表 */
		$this->rows['mail'] = $this->models['mail']->find($this->paramInput->id)->current();
		$this->rows['mail']->status = 0;
		$this->rows['mail']->save();
		
		$this->rows['member'] = $this->models['member']->find($this->rows['mail']->member_id)->current();
		$this->rows['member']->email = $this->rows['mail']->email;
		$this->rows['member']->save();
		
		$this->_helper->notice('验证成功','','success',array());
	}
}

?>