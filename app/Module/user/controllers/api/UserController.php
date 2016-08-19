<?php

class Userapi_UserController extends Core2_Controller_Action_Api    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['app_session'] = new Model_AppSession();
	}
	
	/**
	 *  用sessionid获取用户信息
	 */
	public function userinfoAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['app_session'] = $this->models['app_session']->find($this->input->session_id)->current();
		
		$userInfo = $this->_db->select()
			->from(array('m' => 'member'),array('id','role','group','account','email','deadline'))
			->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('avatar','member_name','alias','sex'))
			->where('m.id = ?',$this->rows['app_session']->member_id)
			->query()
			->fetch();
		
		$userInfo['group_name'] = getGroupName($userInfo['role'],$userInfo['group']);
		$userInfo['meiqia_id'] = md5($userInfo['id']."_".$userInfo['salt']);
		$this->json['userinfo'] = $userInfo;
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>