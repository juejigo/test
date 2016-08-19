<?php

class Productcp_CateController extends Core2_Controller_Action_Cp  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product_cate'] = new Model_ProductCate();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/productcp/cate/list');
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
					'href' => '/admincp',
					'text' => '返回')
			));
		}
		
		/* 构造树装结构 */
		
		$list = $this->_db->select()
			->from(array('p' => 'product_cate'))
			->where('area = ?',$this->paramInput->area)
			->where('p.status = ?',1)
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($list as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($list,'id','parent_id','cate_name');
		$this->view->tree = $tree;
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		if (!params($this)) 
		{
			/* 提示 */
		    
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/cate',
					'text' => '分类列表')
			));
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data['parent_id'] = $this->paramInput->parent_id;
		}
		
		if (!empty($this->paramInput->parent_id)) 
		{
			$this->paramInput->area = $this->_db->select()
				->from(array('c' => 'product_cate'),array('area'))
				->where('c.id = ?',$this->paramInput->parent_id)
				->query()
				->fetchColumn();
		}
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 插入数据库 */
			    
				$this->rows['product_cate'] = $this->models['product_cate']->createRow(array(
					'area' => $this->paramInput->area,
					'parent_id' => $this->input->parent_id,
					'type_id' => $this->input->type_id,
					'cate_name' => $this->input->cate_name,
					'image' => $this->input->image,
					'display' => $this->input->display));
				$this->rows['product_cate']->save();
				
				/* 提示 */
				
				$this->_helper->notice('添加成功','','success',array(
					array(
						'href' => "/productcp/cate?area={$this->paramInput->area}",
						'text' => '分类列表')
				));
			}
		}
		
		/* 父分类树 */
		
		$list = $this->_db->select()
			->from(array('c' => 'product_cate'))
			//->where('area = ?',$this->paramInput->area)
			->where('c.status = ?',1)
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($list as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($list,'id','parent_id','cate_name');
		$this->view->tree = $tree;
		$this->view->parentList = $tree->toArray();
		$this->view->cateList = $cateList;
		
		/* 类型 */
		
		$this->view->types = $this->_db->select()
			->from(array('t' => 'product_type'))
			->query()
			->fetchAll();
	}
	
	/**
	 *  编辑
	 */
	public function editAction()
	{
		if (!params($this)) 
		{
			/* 提示 */
		    
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/cate',
					'text' => '分类列表')
			));
		}
		
		$this->rows['product_cate'] = $this->models['product_cate']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新 */
			    
				$this->rows['product_cate']->parent_id = $this->input->parent_id;
				$this->rows['product_cate']->type_id = $this->input->type_id;
				$this->rows['product_cate']->cate_name = $this->input->cate_name;
				$this->rows['product_cate']->image = $this->input->image;
				$this->rows['product_cate']->display = $this->input->display;
				$this->rows['product_cate']->save();
				
				/* 提示 */
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => "/productcp/cate?area={$this->rows['product_cate']->area}",
						'text' => '分类列表')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['product_cate']->toArray();
		}
		
		/* 父分类树 */
		
		$list = $this->_db->select()
			->from(array('c' => 'product_cate'))
			//->where('area = ?',$this->rows['product_cate']->area)
			->where('c.status = ?',1)
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($list as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($list,'id','parent_id','cate_name');
		$this->view->tree = $tree;
		$this->view->parentList = $tree->toArray();
		
		/* 类型 */
		
		$this->view->types = $this->_db->select()
			->from(array('t' => 'product_type'))
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
				$json['flag'] = 'error';
				$json['msg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			else 
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => '/member/center',
						'text' => '会员中心')
				));
			}
		}
		
		/* 删除文章 */
		
		$this->rows['product_cate'] = $this->models['product_cate']->find($this->paramInput->id)->current();
		if ($this->rows['product_cate']->allow_delete != 1)
		{
			$json['flag'] = 'error';
			$json['msg'] = '该分类不允许删除';
			$this->_helper->json($json);
		}
		$this->rows['product_cate']->status = -1;
		$this->rows['product_cate']->save();
		
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
	
	/**
	 *  推荐位列表
	 */
	public function positionlistAction()
	{
		/* 检验传值 */
	    
		if (!params($this)) 
		{
			/* 提示 */
		    
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/admincp',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('d' => 'position_data'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('p.cate_id = ?',$this->paramInput->cate_id)
			->where('p.status = ?',1);
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/cate/positionlist?page={page}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$positionList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->order('p.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->positionList = $positionList;
	}
}

?>