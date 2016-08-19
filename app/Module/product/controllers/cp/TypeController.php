<?php

class Productcp_TypeController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product_type'] = new Model_ProductType();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/productcp/type/list');
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
		
		/* 构造 SQL 选择器 */
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('t' => 'product_type'),array(new Zend_Db_Expr('COUNT(*)')));
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/type/list?page={page}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$typeList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','t')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->typeList = $typeList;
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
			    
				$this->rows['product_type'] = $this->models['product_type']->createRow(array(
					'type_name' => $this->input->type_name,
					'attrs' => Zend_Serializer::serialize($this->data['attrs']),
					'params' => Zend_Serializer::serialize(array()),
					'minfo' => Zend_Serializer::serialize(array()),
					'spec_1' => $this->input->spec_1,
					'spec_2' => $this->input->spec_2,
					'spec_3' => $this->input->spec_3,
				));
				$this->rows['product_type']->save();
				
				/* 提示 */
				
				$this->_helper->notice('添加成功','','success',array(
					array(
						'href' => '/productcp/type/list',
						'text' => '返回')
				));
			}
		}
		
		$this->view->specList = $this->_db->select()
			->from(array('s' => 'product_spec'))
			->where('s.status = ?',1)
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
					'href' => '/admincp',
					'text' => '返回')
			));
		}
		
		$this->rows['product_type'] = $this->models['product_type']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 插入数据库 */
			    
				$this->rows['product_type']->type_name = $this->input->type_name;
				$this->rows['product_type']->attrs = Zend_Serializer::serialize($this->data['attrs']);
				$this->rows['product_type']->params = Zend_Serializer::serialize(array());
				$this->rows['product_type']->minfo = Zend_Serializer::serialize(array());
				$this->rows['product_type']->spec_1 = $this->input->spec_1;
				$this->rows['product_type']->spec_2 = $this->input->spec_2;
				$this->rows['product_type']->spec_3 = $this->input->spec_3;
				$this->rows['product_type']->save();
				
				/* 提示 */
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => '/productcp/type/list',
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['product_type']->toArray();
			$this->data['attrs'] = Zend_Serializer::unserialize($this->rows['product_type']->attrs);
		}
		
		$this->view->specList = $this->_db->select()
			->from(array('s' => 'product_spec'))
			->where('s.status = ?',1)
			->query()
			->fetchAll();
	}
}

?>