<?php

class Member_GroupController extends Core2_Controller_Action_Uc   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member_group'] = new Model_MemberGroup();
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
					'href' => '/member/group',
					'text' => '会员分组')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('g' => 'member_group'),array(new Zend_Db_Expr('COUNT(*)')));
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/member/group/index");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		$groupList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','g')
			->where('g.status = ?','1')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->groupList = $groupList;
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				$this->rows['member_group'] = $this->models['member_group']->createRow(array(
					'group_name' => $this->input->group_name));
				$this->rows['member_group']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => '/member/group/add',
						'text' => '继续添加'),
					array(
						'href' => 'javascript:history.go(-2)',
						'text' => '返回')
				));
			}
		}
	}
	
	/**
	 *  编辑
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
		
		$this->rows['member_group'] = $this->models['member_group']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 昵称 */
				$this->rows['member_group']->group_name = $this->input->group_name;
				$this->rows['member_group']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => 'javascript:history.go(-2)',
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['member_group']->toArray();
		}
	}
}

?>