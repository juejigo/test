<?php
 
class Usercp_SupplierController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init(); 
	 	$this->models['region'] = new Model_Region();
        $this->models['member_supplier'] = new Model_MemberSupplier();       
  	}

  	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/usercp/supplier/list');
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
				->from(array('u' => 'member_supplier'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('status = ?',1);
					
		$query = '/usercp/supplier/list?page={page}';  
		
		/* 分页 */
		
		$count = $select->query()
				->fetchColumn();

		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */

		$supplierList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('*'),'u')
			->limitPage($corepage->currPage(),$perpage)
			->where('status = ?',1)
			->query()
			->fetchALL();

		if(!empty($supplierList))
		{
			foreach($supplierList as $k => $v)
			{
				$pcc = $this->_db->select()
					->from(array('r'=>'region'),array('region_name'))
					->where('r.id in (?)',array($v['province_id'],$v['city_id'],$v['county_id']))
					->query()
					->fetchALL();

				$supplierList[$k]['pcc'] = $pcc[0]['region_name'];
				$supplierList[$k]['pcc'] .= $pcc[1]['region_name'];
				$supplierList[$k]['pcc'] .= $pcc[2]['region_name'];
			}
	 		$this->view->supplierList = $supplierList;
 		}
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
				/* 插入数据库  */
				
				$this->rows['member_supplier'] = $this->models['member_supplier']->createRow(array(
						'supplier_name' => $this->input->supplier_name,
						'province_id' => $this->input->province_id,
						'city_id' => $this->input->city_id,
						'county_id' => $this->input->county_id,
						'address' => $this->input->address,
						'telephone' => $this->input->telephone,
						'intro' => $this->input->getUnescaped('intro'),
						'memo' => $this->input->memo,
						'status' => 1,
				));
				$this->rows['member_supplier']->save();
				
			 	$this->_helper->notice('添加成功','','success',array(
						array(
								'href' => '/usercp/supplier/list',
								'text' => '返回'),
						array(
								'href' => '/usercp/supplier/add',
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
				$this->rows['member_supplier'] = $this->models['member_supplier']->find($id)->current();
					
				$this->rows['member_supplier']->supplier_name = $this->input->supplier_name;
				$this->rows['member_supplier']->province_id = $this->input->province_id;
				$this->rows['member_supplier']->city_id = $this->input->city_id;
				$this->rows['member_supplier']->county_id = $this->input->county_id;
				$this->rows['member_supplier']->address = $this->input->address;
				$this->rows['member_supplier']->telephone = $this->input->telephone;
				$this->rows['member_supplier']->intro = $this->input->getUnescaped('intro');
				$this->rows['member_supplier']->memo = $this->input->memo;

				$this->rows['member_supplier']->save();

				$this->_helper->notice('修改成功','','success',array(
						array(
								'href' => '/usercp/supplier/list',
								'text' => '返回'),
						array(
								'href' => "/usercp/supplier/edit?id=$id",
								'text' => '继续修改'),
					
				));
			}
		}
		
		if ($this->_request->isGet())
		{
			$editListResult = $this->_db->select()
			->from(array('u'=>'member_supplier'))
			->where('u.id = ?',$this->paramInput->id)
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
					$sql = "UPDATE member_supplier SET status = CASE id ";
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
					$this->rows['member_supplier'] = $this->models['member_supplier']->find($this->input->id)->current();
					$this->rows['member_supplier']->status = -1;
					$this->rows['member_supplier']->save();
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