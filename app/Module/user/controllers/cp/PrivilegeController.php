<?php
class Usercp_privilegeController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function  init()
	{
		parent::init();
		$this->models['privilege_user'] = new Model_PrivilegeUser();
		$this->models['privilege'] = new Model_Privilege();
	}
	
	/**
	 *  权限列表
	 */
	public function authlistAction()
	{
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
							'href' => '/admincp',
							'text' => '返回')
			));
		}
		$this->view->userId=$this->paramInput->id;

		/* 获取用户已有权限  */

		$userPrivilegeResult= $this->_db->select()
			->from(array('u' => 'privilege_user'))
			->where('member_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();

		if(!empty($userPrivilegeResult))
		{
			$userPri = array();
			foreach ($userPrivilegeResult as $v)
			{
				$userPri[]=$v['privilege_id'];
			}
			$this->view->userPri = $userPri;
		}

		/* 获取全部权限  */

		$dataMca=$this->_db->select()
			->from(array('p' => 'privilege'))
			->query()
			->fetchAll();

		$tree = new Core_Tree(0);
		$tree->setTree($dataMca,'id','parent_id','privilege_name');
		
		$this->view->authlist = $tree->toList();
	}
	
	/**
	 *  添加、删除权限
	 */
	public function ajaxAction()
	{
		if (!$this->_request->isXmlHttpRequest())
		{
			exit;
		}
	   	
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();

		/* 检验传值 */
	   	
		if (!ajax($this))
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
	   	
	   	switch ($op)
	   	{
	   		/* 添加权限 */
	   		case 'add':
	   			$this->rows['privilege_user'] = $this->models['privilege_user']->createRow(array(
		   			'member_id' => $this->input->userId,
		   			'privilege_id' => $this->input->authId,
		   		));
	   			$this->rows['privilege_user']->save();
	   			
	   			$json['errno'] = 0;
	   			$this->_helper->json($json);
	   			break;
	   		
	   		/* 删除权限 */
	   		case 'delete':
	   			$this->_db->delete('privilege_user',array(
					   			'member_id = ?' =>$this->input->userId,
					   			'privilege_id = ?'=>$this->input->authId
	   			));
	   			
	   			$json['errno'] = 0;
	   			$this->_helper->json($json);
	   			break;
	   		default:
	   			break;
	   	}
   }
}
?>