<?php

class Onecp_PhaseController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['one_phase'] = new Model_OnePhase();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/onecp/phase/list');
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
					'href' => '/onecp/phase/list',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('p' => 'one_phase'),array(new Zend_Db_Expr('COUNT(*)')));
		
		$query = '/onecp/phase/list?page={page}';
		
		if (!empty($this->paramInput->id))
		{
			$select->where('p.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		
		if (!empty($this->paramInput->product_name))
		{
			$select->where('p.product_name like ?','%'.$this->paramInput->product_name.'%');
			$query .= "&product_name={$this->paramInput->product_name}";
		}
		
		if (!empty($this->paramInput->no))
		{
			$select->where('p.no = ?',$this->paramInput->no);
			$query .= "&no={$this->paramInput->no}";
		}
		
		if ($this->paramInput->status !== '')
		{
			$select->where('p.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
		}
		else
		{
			$select->where('p.status in (?)',array(0,1,2,3,4));
		}
		
		/* 分页 */
		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		$phaseList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->order(array('dateline DESC','no DESC'))
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		$this->view->phaseList = $phaseList;
	}
	
	/**
	 *  增加
	 */
	public function addAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/onecp/phase/list',
					'text' => '返回')
			));
		}
		
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				//已存在的该商品对应期数
				$no = $this->_db->select()
					->from(array('p' => 'one_phase'),array(new Zend_Db_Expr('MAX(no)')))
					->where('p.product_id = ?',$this->input->product_id)
					->query()
					->fetchColumn();
				
				//该期数不存在
				if (empty($no))
				{
					$no = 0;
				}
				
				$startTime = 0;
				$nowPhase = $this->_db->select()
					->from(array('p' => 'one_phase'),'id')
					->where('p.product_id = ?',$this->input->product_id)
					->where('p.status in (?)',array(0,1))
					->query()
					->fetchColumn();

				//循环插入
				for ($i = $no+1;$i<=($no+$this->input->num);$i++)
				{
					//开始时间
					
					if ($i == $no+1 && empty($nowPhase))
					{
						$startTime = strtotime($this->input->start_time);
					}
					else
					{
						$startTime = 0;
					}

					/* 插入一期*/
					
					$this->rows['one_phase'] = $this->models['one_phase']->createRow(array(
						'product_id' => $this->input->product_id,
						'no' => $i,
						'product_name' => $this->input->product_name,
						'image' => $this->input->image,
						'product_price' => $this->input->product_price,
						'price' => $this->input->price,
						'need_num' => ceil($this->input->product_price/$this->input->price),
						'limit_num' => $this->input->limit_num,
						'start_time' => $startTime,
						'end_time' => strtotime($this->input->end_time),
					));
					$this->rows['one_phase']->save();
				}
		
				$this->_helper->notice('添加成功','','success',array(
					array(
						'href' => '/onecp/phase/list',
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
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/onecp/phase/list',
					'text' => '返回')
			));
		}
		
		if ($this->_request->isPost())
		{
			if (form($this))
			{		
				/* 修改*/
				
				$this->rows['one_phase'] = $this->models['one_phase']->find($this->paramInput->id)->current();
				$this->rows['one_phase']->product_name = $this->input->product_name;
				$this->rows['one_phase']->image = $this->input->image;
				$this->rows['one_phase']->product_price = $this->input->product_price;
				$this->rows['one_phase']->price = $this->input->price;
				$this->rows['one_phase']->need_num = ceil($this->input->product_price/$this->input->price);
				$this->rows['one_phase']->limit_num = $this->input->limit_num;
				$this->rows['one_phase']->start_time = strtotime($this->input->start_time);
				$this->rows['one_phase']->end_time = strtotime($this->input->end_time);
				$this->rows['one_phase']->save();
		
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => '/onecp/phase/list',
						'text' => '返回')
				));
			}
		}
		//期数详情
		$phase = $this->_db->select()
			->from(array('p' => 'one_phase'))
			->where('p.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		$this->view->phase = $phase;
	}
	
	/**
	 *  详情
	 */
	public function detailAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/onecp/phase/list',
					'text' => '返回')
			));
		}
		
		//期数详情
		$phase = $this->_db->select()
			->from(array('p' => 'one_phase'))
			->where('p.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		$this->view->phase = $phase;
	}
	
	/**
	 *  ajax
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
					$sql = "UPDATE one_phase SET status = CASE id ";
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
					$this->rows['one_phase'] = $this->models['one_phase']->find($this->input->id)->current();
					$this->rows['one_phase']->status = -1;
					$this->rows['one_phase']->save();
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