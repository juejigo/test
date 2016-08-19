<?php

class Positioncp_DataController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['position'] = new Model_Position();
		$this->models['position_data'] = new Model_PositionData();
	}
	
	/**
	 *  转向
	 */
	public function indexAction() 
	{
		$this->_redirect('/positioncp/data/list');
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
			->from(array('d' => 'position_data'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('d.status in (?)',array(1,0));
		
		// 推荐推荐位
		if (!empty($this->paramInput->position_id)) 
		{
			$select->where('d.position_id = ?',$this->paramInput->position_id);
		}
		
		// 分类推荐位
		if (!empty($this->paramInput->cate_id)) 
		{
			$select->where('d.cate_id = ?',$this->paramInput->cate_id);
		}
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/positioncp/data/list?position_id={$this->paramInput->position_id}&cate_id={$this->paramInput->cate_id}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		$dataList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','d')
			->where('d.status in (?)',array(1,0))
			->order(array('d.order ASC','d.dateline DESC'))
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->dataList = $dataList;
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => "/positioncp/position",
					'text' => '返回')
			));
		}
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{

				/* 插入数据库 */
				require_once 'includes/function/position.php';
				$data = encode($this->input,$this->data['params']);

				$this->rows['position_data'] = $this->models['position_data']->createRow(array(
					'position_id' => $this->paramInput->position_id,
					'cate_id' => $this->paramInput->cate_id,
					'data_type' => $data['data_type'],
					'title' => $data['title'],
					'image' => $data['image'],
					'up_time' => strtotime($this->input->up_time),
					'down_time' => strtotime($this->input->down_time),
					'params' => Zend_Serializer::serialize($data['params'])
				));
				$this->rows['position_data']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => "/positioncp/data/list?position_id={$this->paramInput->position_id}&cate_id={$this->paramInput->cate_id}",
						'text' => '返回')
				));
			}
		}
		
		/*旅游类型*/
		$tourismType = array(
		    array('id' => 1,'tourism_type' => '跟团游'),
		    array('id' => 2,'tourism_type' => '自助游'),
		    array('id' => 3,'tourism_type' => '自由行'),
		    array('id' => 4,'tourism_type' => '自驾游'),
		    array('id' => 5,'tourism_type' => '目的地服务'),
		);
		$this->view->tourism_type = $tourismType;
		
		/* 商品标签 */
		
		$this->view->tags = $this->_db->select()
			->from(array('t' => 'product_tag'))
			->where('t.status = ?',1)
			->query()
			->fetchAll();
		
		/* 商品分类 */
		
		$area = 0;
		if ($this->_request->isPost() && in_array($this->data['params']['area'],array(0,1))) 
		{
			$area = $this->data['params']['area'];
		}
		
		$results = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.area = ?',$area)
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($results,'id','parent_id','cate_name');
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;
		
		/* 品牌 */
		
		$this->view->brands = $this->_db->select()
			->from(array('b' => 'product_brand'))
			->where('b.status = ?',1)
			->query()
			->fetchAll();
	}
	
	
	/**
	 *  排序
	 */
	public function orderAction()
	{
		
		 
		$this->_helper->viewRenderer->setNoRender();		 
	 
		if ($this->_request->isPost()){
			
			  $position_id=$this->_request->get('position_id'); 
		
	 	      $arr=$this->_request->getPost('order'); 
			  
			  
			  
			  foreach( $arr as $k=>$v){
			   
			
			    $this->rows['position_data']= $this->models['position_data']
			    
			  					 	   ->fetchRow(
			  					 	   
			    $this->models['position_data']->select()
				
									   ->where('position_id = ?',$position_id) 
									   
									   ->where('id = ?',$k) 
				 );
	 
	 
 
	 				$this->rows['position_data']->order = $v;
	 				$this->rows['position_data']->save();
			  }
			  	 
				
			  		$this->_helper->notice('操作成功','','success',array(
						array(
							'href' => "/positioncp/data/list?position_id={$position_id}",
							'text' => '返回')
					)); 			 
			 
			 
	 
 
			
			
		} 
			
			
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
		
		$this->rows['position_data'] = $this->models['position_data']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新 */
				require_once 'includes/function/position.php';
				$data = encode($this->input,$this->data['params']);
				
				$this->rows['position_data']->data_type = $data['data_type'];
				$this->rows['position_data']->title = $data['title'];
				$this->rows['position_data']->image = $data['image'];
				$this->rows['position_data']->up_time = strtotime($this->input->up_time);
				$this->rows['position_data']->down_time = strtotime($this->input->down_time);
				$this->rows['position_data']->params = Zend_Serializer::serialize($data['params']);
				$this->rows['position_data']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => "/positioncp/data/list?position_id={$this->rows['position_data']->position_id}&cate_id={$this->rows['position_data']->cate_id}",
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['position_data']->toArray();
			$this->data['params'] = Zend_Serializer::unserialize($this->rows['position_data']->params);
		}
		
		
		/*旅游类型*/
		$tourismType = array(
		    array('id' => 1,'tourism_type' => '跟团游'),
		    array('id' => 2,'tourism_type' => '自助游'),
		    array('id' => 3,'tourism_type' => '自由行'),
		    array('id' => 4,'tourism_type' => '自驾游'),
		    array('id' => 5,'tourism_type' => '目的地服务'),
		);
		$this->view->tourism_type = $tourismType;
		
		/* 商品标签 */
		
		$this->view->tags = $this->_db->select()
			->from(array('t' => 'product_tag'))
			->where('t.status = ?',1)
			->query()
			->fetchAll();
		
		/* 商品分类 */
		
		$area = 0;
		if ($this->data['data_type'] == 'product_list' && in_array($this->data['params']['area'],array(0,1))) 
		{
			$area = $this->data['params']['area'];
		}
		
		$results = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.area = ?',$area)
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($results,'id','parent_id','cate_name');
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;
		
		/* 品牌 */
		
		$this->view->brands = $this->_db->select()
			->from(array('b' => 'product_brand'))
			->where('b.status = ?',1)
			->query()
			->fetchAll();
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
				$json['errno'] = 1;
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
		
		$this->rows['position_data'] = $this->models['position_data']->find($this->paramInput->id)->current();
		$this->rows['position_data']->status = -1;
		$this->rows['position_data']->save();
		
		if ($this->_request->isXmlHttpRequest()) 
		{
			$json['errno'] = 0;
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
	
	/**
	 *  ajax
	 */
	public function ajaxAction()
	{
		if (!$this->_request->isXmlHttpRequest()) 
		{
			exit ;
		}
		
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		
		if (!ajax($this)) 
		{
			$json['flag'] = 'error';
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		switch ($op)
		{
			case 'areachanged' :
				$results = $this->_db->select()
					->from(array('c' => 'product_cate'))
					->where('c.area = ?',$this->input->area)
					->where('c.status = ?','1')
					->query()
					->fetchAll();
				$cateList = array();
				foreach ($results as $cate) 
				{
					$cateList[$cate['id']] = $cate;
				}
				$tree = new Core_Tree(0);
				$tree->setTree($results,'id','parent_id','cate_name');
				$this->view->list = $tree->toArray();
				$this->view->cateList = $cateList;
				
				$json['errno'] = '0';
				$json['html'] = $this->view->render('positioncp/data/ajax_areachanged.tpl');
				break ;
				
			/* 上架*/
			
			case 'up':
				
				if (!empty($this->input->id))
				{
					$this->rows['position_data'] = $this->models['position_data']->find($this->input->id)->current();
					$this->rows['position_data']->status = 1;
					$this->rows['position_data']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
				
			/* 下架*/
			
			case 'down':
				
				if (!empty($this->input->id))
				{
					$this->rows['position_data'] = $this->models['position_data']->find($this->input->id)->current();
					$this->rows['position_data']->status = 0;
					$this->rows['position_data']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
			
			/* 排序*/
			
			case 'order' :
				$this->rows['position_data'] = $this->models['position_data']->find($this->input->id)->current();
				$this->rows['position_data']->order = $this->input->order;
				$this->rows['position_data']->save();
			
				$json['errno'] = '0';
				$this->_helper->json($json);
				break;
			
			/* 批量排序*/
				
			case 'orderlist':
				if (is_array($this->input->id))
				{
					$ids = implode(',', array_values($this->input->id));
					$sql = "UPDATE `position_data` SET `order` = CASE `id` ";
					foreach ($this->input->id as $key => $id)
					{
						$sql .= sprintf("WHEN %d THEN '%d' ", $id, $this->input->order[$key]);
					}
					$sql .= "END WHERE `id` IN ($ids)";
					$db = Zend_Registry::get('db');
					$db->query($sql);
						
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
				
			default :
				break ;
		}
		$this->_helper->json($json);
	}
}

?>