<?php

class Servicecp_StaffController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['service_staff'] = new Model_ServiceStaff();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/servicecp/staff/list');
	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/servicecp/staff/list',
					'text' => '返回')
			));
		}
		/* 构造 SQL 选择器 */
		
		$perpage = 10;
		$select = $this->_db->select()
			->from(array('s' => 'service_staff'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('s.status = ?',1);
		$query = '/servicecp/staff/list?page={page}';
		
		/* 分页 */
		
		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$staffList = array();
		$staffResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','s')
			->order('id ASC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		if (!empty($staffResult))
		{
			foreach ($staffResult as $staff)
			{
				$staffList[] = $staff;
			}
		}
		$this->view->staffList = $staffList;
	}
	
	/**
	 *  新增
	 */
	public function addAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/servicecp/staff/list',
					'text' => '返回')
			));
		}
		
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				/* 新增 */
				$this->rows['service_staff'] = $this->models['service_staff']->createRow(array(
					'staff_name' => $this->input->staff_name,
					'avatar' => $this->input->image,
					'wx' => $this->input->wx,
					'phone' => $this->input->phone,
					'introduce' => $this->input->introduce,
					//row里设置
					'status'=> '1'
				));
				$this->rows['service_staff']->save();
				$this->_helper->notice('添加成功','','success',array(
					array(
						'href' => '/servicecp/staff/list',
						'text' => '返回')
				));
			}
		}
	}
	
	/**
	 *  修改
	 */
	public function editAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/servicecp/staff/list',
					'text' => '返回')
			));
		}
		
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				/* 修改 */
				$this->rows['service_staff'] = $this->models['service_staff']->find($this->paramInput->id)->current();
				
				$this->rows['service_staff']->staff_name = $this->input->staff_name;
				$this->rows['service_staff']->avatar = $this->input->image;
				$this->rows['service_staff']->wx = $this->input->wx;
 				$this->rows['service_staff']->phone = $this->input->phone;
 				$this->rows['service_staff']->introduce = $this->input->introduce;
				$this->rows['service_staff']->save();
				$this->_helper->notice('修改成功','','success',array(
					array(
						'href' => '/servicecp/staff/list',
						'text' => '返回')
				));
			}

		}
		
		//客服信息
		$staff = array();
		$staffInfo = $this->_db->select()
			->from(array('s' => 'service_staff'))
			->where('s.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		if (!empty($staffInfo))
		{
			$staff = $staffInfo;
		}
		$this->view->staff = $staff;
	}
	
	/**
	 *  ajax
	 */
	public function  ajaxAction()
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
			/* 删除*/
			
			case 'delete':	
				
				//批量
				if (is_array($this->input->id))
				{
					foreach ($this->input->id as $key => $value)
					{
						$idlist[$value] = -1;
					}
					$ids = implode(',', array_keys($idlist));
					$sql = "UPDATE service_staff SET status = CASE id ";
					foreach ($idlist as $id => $status)
					{
						$sql .= sprintf("WHEN %d THEN %d ", $id, $status);
					}
					$sql .= "END WHERE id IN ($ids)";
					$db = Zend_Registry::get('db');
					$db->query($sql);
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				//单条
				elseif (!empty($this->input->id))
				{
					$this->rows['service_staff'] = $this->models['service_staff']->find($this->input->id)->current();
					$this->rows['service_staff']->status = -1;
					$this->rows['service_staff']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}				
				break;
				
			default:
				break;
		}
	}
}

?>