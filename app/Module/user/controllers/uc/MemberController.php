<?php

class Member_MemberController extends Core2_Controller_Action_Uc   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['member_profile'] = new Model_MemberProfile();
	}
	
	/**
	 *  列表
	 */
	public function indexAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/member/member',
					'text' => '会员管理')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')));
		
		if ($this->paramInput->account !== '') 
		{
			$select->where('m.account = ?',$this->paramInput->account);
		}
		
		if ($this->paramInput->status !== '') 
		{
			$select->where('m.status = ?',$this->paramInput->status);
		}
		
		if ($this->paramInput->groupid !== '') 
		{
			$select->where('m.group_id = ?',$this->paramInput->groupid);
		}
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/member/member/index?account={$this->paramInput->account}&status={$this->paramInput->status}&groupid={$this->paramInput->groupid}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		$memberList = $select->reset(Zend_Db_Select::COLUMNS)
			->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('city' => 'f1','company','job'))
			->joinLeft(array('g' => 'member_group'),'g.id = m.group_id',array('group_name'))
			->columns('*','m')
			->order('m.register_time DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->memberList = $memberList;
		
		/* 分组 */
		$results = $this->_db->select()
			->from(array('g' => 'member_group'))
			->where('g.status = ?','1')
			->query()
			->fetchAll();
		$groupList = array();
		foreach ($results as $result) 
		{
			$groupList[$result['id']] = $result;
		}
		$this->view->groupList = $groupList;
	}
	
	/**
	 *  编辑用户
	 */
	public function editAction()
	{
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误','','error',array(
					array(
						'href' => '/member/member',
						'text' => '会员管理')
				));
		}
		
		$this->rows['member'] = $this->models['member']->find($this->paramInput->id)->current();
		$this->rows['member_profile'] = $this->models['member_profile']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新用户表 */
				$this->rows['member']->account = $this->input->account;
				if ($this->input->password != '') 
				{
					$this->rows['member']->password = $this->input->password;
				}
				$this->rows['member']->group_id = $this->input->group_id;
				$this->rows['member']->expiry = strtotime($this->input->expiry);
				$this->rows['member']->status = $this->input->status;
				$this->rows['member']->save();
				
				/* 昵称 */
				$this->rows['member_profile']->real_name = $this->input->real_name;
				$this->rows['member_profile']->save();
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => '/member/member',
						'text' => '会员管理')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = array_merge($this->rows['member']->toArray(),$this->rows['member_profile']->toArray());
			$this->data['expiry'] = date('Y-m-d',$this->rows['member']->expiry);
		}
		
		/* 会员分组 */
		$this->view->groupList = $this->_db->select()
			->from(array('g' => 'member_group'))
			->where('g.status = ?','1')
			->query()
			->fetchAll();
	}
	
	/**
	 *  异步修改
	 */
	public function ajaxeditAction()
	{
		if (!$this->_request->isXmlHttpRequest() || !$this->_request->isPost()) 
		{
			exit;
		}
		
		$this->_helper->viewRenderer->setNoRender();
		$json = array();
		
		if (!params($this)) 
		{
			$json['flag'] = 'error';
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$this->rows['member'] = $this->models['member']->find($this->paramInput->id)->current();
		
		if (!form($this)) 
		{
			$json['flag'] = 'error';
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		if (isset($this->input->group_id)) 
		{
			$this->rows['member']->group_id = $this->input->group_id;
		}
		else if (isset($this->input->status)) 
		{
			$this->rows['member']->status = $this->input->status;
		}
		$this->rows['member']->save();
		
		$json['flag'] = 'success';
		$this->_helper->json($json);
	}
}

?>