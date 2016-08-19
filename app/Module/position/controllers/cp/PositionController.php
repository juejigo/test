<?php

class Positioncp_PositionController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['position'] = new Model_Position();
		$this->models['position_group'] = new Model_PositionGroup();
	}
	
	public function indexAction() 
	{
		$this->_redirect('/positioncp/position/group');
	}
	 /**
	 *  分组
	 */
	public function groupAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/positioncp/position/list',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('p' => 'position_group'),array(new Zend_Db_Expr('COUNT(*)')));
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/positioncp/position/list");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		$groupList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->where('p.status = ?','1')
			->order('p.id DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		 
		$this->view->groupList = $groupList;		
  
	}
	
		
	/**
	 *  添加
	 */
	public function groupaddAction()
	{
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 插入数据库 */
				
				$this->rows['position_group'] = $this->models['position_group']->createRow(array(
					'group_name' => $this->input->position_name,
 
				));
				$this->rows['position_group']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => "/positioncp/position/group",
						'text' => '返回')
				));
			}
		}
	}
	
	
	/**
	 *  编辑
	 */
	public function groupeditAction()
	{
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => "/positioncp/position",
					'text' => '返回')
			));
		}
	 
		
		$this->rows['position_group'] = $this->models['position_group']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 插入数据库 */
				
				$this->rows['position_group']->group_name = $this->input->group_name;
 				$this->rows['position_group']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => "/positioncp/position",
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['position_group']->toArray();
		}
	}
	
	
	/**
	 *  列表
	 */
	public function listAction()
	{
 
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/positioncp/position/list',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('p' => 'position'),array(new Zend_Db_Expr('COUNT(*)')))
  			->where('p.group_id = ?',$this->paramInput->group_id);
			
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/positioncp/position/list");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		$positionList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->where('p.status = ?','1')
			->order('p.id DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->positionList = $positionList;
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
				/* 插入数据库 */
				
				$this->rows['position'] = $this->models['position']->createRow(array(
					'position_name' => $this->input->position_name,
					'group_id' => $this->input->group_id,
					'memo' => $this->input->memo,
				));
				$this->rows['position']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => "/positioncp/position/list?group_id=".$this->input->group_id,
						'text' => '返回')
				));
			}
		}else{
			
		$grouplist = $this->_db->select()
				->from(array('g' => 'position_group'),array('id','group_name'))  
				->order('g.id  DESC')
				->where('g.status = ?',1) 
				->query() 
				->fetchAll();	 
		}
		 
		$this->view->grouplist = $grouplist;
	}
	
	/**
	 *  编辑
	 */
	public function editAction()
	{
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => "/positioncp/position",
					'text' => '返回')
			));
		}
		
		$this->rows['position'] = $this->models['position']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 插入数据库 */
				
				$this->rows['position']->position_name = $this->input->position_name;
				$this->rows['position']->memo = $this->input->memo;
				$this->rows['position']->group_id = $this->input->group_id;
				$this->rows['position']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => "/positioncp/position/list?group_id=".$this->input->group_id,
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			
			$grouplist = $this->_db->select()
					->from(array('g' => 'position_group'),array('id','group_name')) 
					->where('g.status = ?',1) 
					->order('g.id  DESC')
					->query() 
					->fetchAll();	 			
			
			$this->data = $this->rows['position']->toArray();
		}
		$this->view->grouplist = $grouplist;
	}
	
	/**
	 *  删除
	 */
	public function deleteAction()
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();
		
		if (!params($this)) 
		{
			if ($this->_request->isXmlHttpRequest()) 
			{
				$json['flag'] = 'error';
				$json['msg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			else 
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => '/admincp',
						'text' => '返回')
				));
			}
		}
		
		/* 删除 */
		
		$this->rows['position'] = $this->models['position']->find($this->paramInput->id)->current();
		$this->rows['position']->status = -1;
		$this->rows['position']->save();
		
		if ($this->_request->isXmlHttpRequest()) 
		{
			$json['flag'] = 'success';
			$this->_helper->json($json);
		}
		else 
		{
			$this->_helper->notice('删除成功','','success',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
	}
}

?>