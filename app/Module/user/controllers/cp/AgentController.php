<?php
 
class Usercp_AgentController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init(); 
	 	$this->models['region'] = new Model_Region();
        $this->models['member_agent'] = new Model_MemberAgent();       
  	}
  	
  	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/usercp/agent/list');
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
					'href' => '/admincp/index',
					'text' => '错误!')
			));
		}
	  
		/* 构造 SQL 选择器 */
		$perpage = 20;
		
		$select = $this->_db->select()		
				->from(array('u' => 'member_agent'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('status = ?',1);

		$query = '/usercp/agent/list?page={page}';  
		
		/* 分页 */
		
		$count = $select->query()
				->fetchColumn();

		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);

		$this->view->pagebar = $corepage->output();
		
		/* 列表 */  
			
		$agentList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('*'),'u')
			->joinLeft(array('r' => 'region'),'u.region_id = r.id',array('region_name'))
			->limitPage($corepage->currPage(),$perpage)
			->where('status = ?',1)
			->query()
			->fetchALL();
			
		if(!empty($agentList))
		{
 			$this->view->agentList = $agentList;
 		}
	 
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		$city = $this->_db->select()
		->from(array('r'=>'region'),array('id','region_name'))
		->where('level = ?','2')
		->query()
		->fetchAll();

		if(!empty($city))
		{
			$this->view->city=$city;
		}
 		if ($this->_request->isPost())
		{
			if (form($this))
			{
				/* 插入数据库  */
				
				$this->rows['member_agent'] = $this->models['member_agent']->createRow(array(
						'agent_name' => $this->input->agent_name,
						'region_id' => $this->input->agent_city_id,
						'company_name' => $this->input->company_name,
						'province_id' => $this->input->province_id,
						'city_id' => $this->input->city_id,
						'county_id' => $this->input->county_id,
						'address' => $this->input->address,
						'linkman' => $this->input->linkman,
						'linkman_mobile' => $this->input->linkman_mobile,
						'telephone' => $this->input->telephone,
						'memo' => $this->input->memo,
						'status' => 1,
				));
				$this->rows['member_agent']->save();
		
				
			 	$this->_helper->notice('添加成功','','success',array(
						array(
								'href' => '/usercp/agent/list',
								'text' => '返回'),
						array(
								'href' => '/usercp/agent/add',
								'text' => '继续添加')
				));
			}
		}
	}
	
	/**
	 *  编辑
	 */
	public function editAction()
	{		ERROR_REPORTING(E_ALL);
		if (!params($this))
		{
			/* 检验传值 */
				
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
							'href' => '/admincp',
							'text' => '返回')
			));
		}
		
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				$id = $this->paramInput->id;
				
				/* 插入数据库 */
				$this->rows['member_agent'] = $this->models['member_agent']->find($id)->current();
					
				$this->rows['member_agent']->agent_name = $this->input->agent_name;
				$this->rows['member_agent']->region_id = $this->input->agent_city_id;
				$this->rows['member_agent']->company_name = $this->input->company_name;
				$this->rows['member_agent']->province_id = $this->input->province_id;
				$this->rows['member_agent']->city_id = $this->input->city_id;
				$this->rows['member_agent']->county_id = $this->input->county_id;
				$this->rows['member_agent']->address = $this->input->address;
				$this->rows['member_agent']->linkman = $this->input->linkman;
				$this->rows['member_agent']->linkman_mobile = $this->input->linkman_mobile;
				$this->rows['member_agent']->telephone = $this->input->telephone;
				$this->rows['member_agent']->memo = $this->input->memo;

				$this->rows['member_agent']->save();

				$this->_helper->notice('修改成功','','success',array(
						array(
								'href' => '/usercp/agent/list',
								'text' => '返回'),
						array(
								'href' => "/usercp/agent/edit?id=$id",
								'text' => '继续修改'),
					
				));
			}
		}
		
		if ($this->_request->isGet())
		{
			$editListResult = $this->_db->select()
			->from(array('u'=>'member_agent'))
			->where('u.id = ?',$this->paramInput->id)
			->joinLeft(array('r' => 'region'),'u.region_id = r.id',array('region_name'))
			->query()
			->fetch();

			if(!empty($editListResult))
			{
				foreach($editListResult as $k => $r)
				{
					$editList[$k] = $r;
				}
				$this->view->editList = $editList;
			}
		}
		
		$city = $this->_db->select()
		->from(array('r'=>'region'),array('id','region_name'))
		->where('level = ?','2')
		->query()
		->fetchAll();
		
		if(!empty($city))
		{
			$this->view->city=$city;
		}
	}

	/**
	 *  删除
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
			/* 删除 */
				
			case 'delete':
	
				//批量
				if (is_array($this->input->id))
				{
					foreach ($this->input->id as $key => $value)
					{
						$idlist[$value] = -1;
					}
					$ids = implode(',', array_keys($idlist));
					$sql = "UPDATE member_agent SET status = CASE id ";
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
					$this->rows['member_agent'] = $this->models['member_agent']->find($this->input->id)->current();
					$this->rows['member_agent']->status = -1;
					$this->rows['member_agent']->save();
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