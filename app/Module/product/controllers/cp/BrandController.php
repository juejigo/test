<?php

class Productcp_BrandController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product_brand'] = new Model_ProductBrand();
		$this->models['product_brandtype'] = new Model_ProductBrandtype();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/productcp/brand/list');
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
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('b' => 'product_brand'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('b.status = ?',1);
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/brand/list?page={page}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		$brandList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','b')
			->order('b.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->brandList = $brandList;
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
				
				$this->rows['product_brand'] = $this->models['product_brand']->createRow(array(
					'brand_name' => $this->input->brand_name,
					'image' => $this->input->image,
				));
				$this->rows['product_brand']->save();
				
				/* 类型关联 */
				
				$this->_type();
				
				/* 提示 */
				
				$this->_helper->notice('添加成功','','success',array(
					array(
						'href' => '/productcp/brand/add',
						'text' => '继续添加'),
					array(
						'href' => '/productcp/brand/list',
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
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/brand/list',
					'text' => '返回')
			));
		}
		
		$this->rows['product_brand'] = $this->models['product_brand']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新主表 */
				
				$this->rows['product_brand']->brand_name = $this->input->brand_name;
				$this->rows['product_brand']->image = $this->input->image;
				$this->rows['product_brand']->save();
				
				/* 规格 */
				
				$this->_type();
				
				/* 提示 */
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => '/productcp/brand/list',
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$rs = $this->_db->select()
				->from(array('t' => 'product_brandtype'),array('type_id'))
				->where('t.brand_id = ?',$this->rows['product_brand']->id)
				->query()
				->fetchAll();
			$this->data['types'] = array();
			foreach ($rs as $r) 
			{
				$this->data['types'][] = $r['type_id'];
			}
			$this->data = array_merge($this->data,$this->rows['product_brand']->toArray());
		}
	}
	
	/**
	 *  类型关联
	 */
	protected function _type()
	{
		$types = array();
		if (!empty($this->data['types'])) 
		{
			foreach ($this->data['types'] as $id) 
			{
				$types[] = $id;
				
				$this->rows['product_brandtype'] = $this->models['product_brandtype']->find(array($this->rows['product_brand']->id,$id))->current();
				
				if (empty($this->rows['product_brandtype'])) 
				{
					$this->models['product_brandtype']->createRow(array(
						'brand_id' => $this->rows['product_brand']->id,
						'type_id' => $id
					))->save();
				}
			}
		}
		
		// 删除无关联
		if (!empty($types)) 
		{
			$this->models['product_brandtype']->delete(array('brand_id = ?' => $this->rows['product_brand']->id,'type_id NOT IN (?)' => $types));
		}
		else 
		{
			$this->models['product_brandtype']->delete(array('brand_id = ?' => $this->rows['product_brand']->id));
		}
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
				$json['errno'] = '1';
				$json['msg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			else 
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => 'javascript:history.back();',
						'text' => '返回')
				));
			}
		}
		
		/* 删除商品 */
		
		$this->rows['product_brand'] = $this->models['product_brand']->find($this->paramInput->id)->current();
		$this->rows['product_brand']->status = -1;
		$this->rows['product_brand']->save();
		
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
}

?>