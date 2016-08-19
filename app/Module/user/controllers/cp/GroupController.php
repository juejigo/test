<?php

class Usercp_GroupController extends Core2_Controller_Action_Cp   
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
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/usercp/group/list');
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
		->from(array('g' => 'member_group'),array(new Zend_Db_Expr('COUNT(*)')));
		
		/* 分页 */
		
		$count = $select->query()
						->fetchColumn();
		
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/usercp/group/list");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$roleResult = $this->_db->select()
		->from(array('r'=>'member_role'),array('role','role_name'))
		->query()
		->fetchAll();
		
		if(!empty($roleResult))
		{
			foreach($roleResult as $r)
			{
				$roleList[] = $r;
			}
		}
		
		$this->view->role = $roleList;
		
		$groupList = $select->reset(Zend_Db_Select::COLUMNS)
		->columns('*','g')
		->where('g.status = ?','1')
		->limitPage($corepage->currPage(),$perpage)
		->query()
		->fetchAll();

		for($i=0;$i<count($groupList);$i++)
		{
			$groupList[$i]['setting']=json_decode($groupList[$i]['setting']);
			$groupList[$i]['setting']=json_decode(json_encode($groupList[$i]['setting']),true);
		}
		
		for($i=0;$i<count($groupList);$i++)
		{
			foreach($groupList[$i]['setting'] as $t)
			{
				$turn[$i][]=$t['turnover'];
			}
			array_multisort($turn[$i],SORT_ASC,$groupList[$i]['setting']);
		}
		$this->view->groupList = $groupList;
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		$roleResult = $this->_db->select()
			->from(array('r'=>'member_role'),array('role','role_name'))
			->query()
			->fetchAll();
		
		if(!empty($roleResult))
		{
			foreach($roleResult as $r)
			{
				$roleList[] = $r;
			}
		}

		$this->view->role = $roleList;

		if ($this->_request->isPost())
		{
			if (form($this))
			{
				for($i=1;$i<count($this->input->turnover)+1;$i++)
				{
					$setting[$i]['turnover']=$this->input->turnover[$i];
					$setting[$i]['reward']=$this->input->reward[$i];
				}
				
				//序列化
				$setting = json_encode($setting);

				/* 插入数据库  */
				
				$this->rows['member_group'] = $this->models['member_group']->createRow(array(
						'group_name' => $this->input->name,
				 		'role' => $this->input->role,
						'condition_point' => $this->input->point,
						'condition_consumption' => $this->input->consumption,
						'ratio' => $this->input->ratio,
						'setting' => $setting,
						'status' => '1'
				));
				$this->rows['member_group']->save();

				$this->_helper->notice('添加成功','','success',array(
						array(
								'href' => '/usercp/group/list',
								'text' => '返回'),
						array(
								'href' => '/usercp/group/add',
								'text' => '继续添加')
				));
			}
		}
	}

	/**
	 *  编辑
	 */
	public function editAction()
	{
		$roleResult = $this->_db->select()
		->from(array('r'=>'member_role'),array('role','role_name'))
		->query()
		->fetchAll();
		
		if(!empty($roleResult))
		{
			foreach($roleResult as $r)
			{
				$roleList[] = $r;
			}
		}
		$this->view->role = $roleList;
		
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
				for($i=1;$i<count($this->input->turnover)+1;$i++)
				{
					if(!empty($this->input->turnover[$i]))
					{
						$setting[$i]['turnover']=$this->input->turnover[$i];
						$setting[$i]['reward']=$this->input->reward[$i];
					}
				}

				//获取数组最大下标
				$key = array_search(max($this->input->dbturnover),$this->input->dbturnover);

				for($j=0;$j<$key+1;$j++)
				{
					if(isset($this->input->dbturnover[$j]))
					{
						$dbsetting[$j]['turnover'] = $this->input->dbturnover[$j];
						$dbsetting[$j]['reward'] = $this->input->dbreward[$j];
					}
				}

				for($i=0;$i<$key+1;$i++)
				{
					if(isset($dbsetting[$i]))
					{
						$setting[]=$dbsetting[$i];
					}
				}

				$setting = json_encode($setting);

				/* 更新数据库  */
				
				$this->rows['member_group'] = $this->models['member_group']->find($this->paramInput->id)->current();
				
				$this->rows['member_group']->group_name = $this->input->name;
				$this->rows['member_group']->role = $this->input->role;
				$this->rows['member_group']->condition_point = $this->input->point;
				$this->rows['member_group']->condition_consumption = $this->input->consumption;
				$this->rows['member_group']->ratio = $this->input->ratio;
				$this->rows['member_group']->setting = $setting;
				
				$this->rows['member_group']->save();

				$this->_helper->notice('编辑成功','','success',array(
						array(
								'href' => '/usercp/group/list',
								'text' => '返回'),
						array(
								'href' => "/usercp/group/edit?id={$this->paramInput->id}",
								'text' => '继续编辑')
				));
			}
		}
		
		if ($this->_request->isGet())
		{
			$editListResult = $this->_db->select()
			->from(array('g'=>'member_group'))
			->where('g.id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
			
			$editListResult[0]['setting']=json_decode($editListResult[0]['setting']);
			$editListResult[0]['setting']=json_decode(json_encode($editListResult[0]['setting']),true);
			
			foreach($editListResult[0]['setting'] as $t)
			{
				$turn[]=$t['turnover'];
			}
			
			// 数组排序
			array_multisort($turn,SORT_ASC,$editListResult[0]['setting']);
 
			if(!empty($editListResult))
			{
				foreach($editListResult as $r)
				{
					$editList[] = $r;
				}
			}
			$this->view->editList = $editList;
		}
	}
	
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
					$sql = "UPDATE member_group SET status = CASE id ";
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
					$this->rows['member_group'] = $this->models['member_group']->find($this->input->id)->current();
					$this->rows['member_group']->status = -1;
					$this->rows['member_group']->save();
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
