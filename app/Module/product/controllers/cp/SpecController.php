<?php

class Productcp_SpecController extends Core2_Controller_Action_Cp 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product_spec'] = new Model_ProductSpec();
		$this->models['product_specval'] = new Model_ProductSpecval();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/productcp/spec/list');
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
			->from(array('s' => 'product_spec'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('s.status = ?',1);
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/productcp/spec/list?page={page}");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$specList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','s')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->specList = $specList;
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
					'href' => '/productcp/spec',
					'text' => '返回')
			));
		}
		
		$this->rows['product_spec'] = $this->models['product_spec']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新 */
			    
				$this->rows['product_spec']->spec_name = $this->input->spec_name;
				$this->rows['product_spec']->save();
				
				/* 提示 */
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => "/productcp/spec",
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['product_spec']->toArray();
		}
		
		$valueList = $this->_db->select()
			->from(array('v' => 'product_specval'))
			->where('v.spec_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->valueList = $valueList;
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
			$json['errno'] = 'error';
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		switch ($op)
		{
			case 'addspec' :
				$this->rows['product_spec'] = $this->models['product_spec']->createRow(array(
					'spec_name' => $this->input->spec_name
				));
				$this->rows['product_spec']->save();
				
				$json['errno'] = 0;
				$this->_helper->json($json);
				break ;
			
			case 'addvalue' :
				$this->rows['product_specval'] = $this->models['product_specval']->createRow(array(
					'spec_id' => $this->input->spec_id,
					'value' => $this->input->value,
					'letter' => $this->input->letter,
					'memo' => $this->input->memo,
					'image' => $this->input->image
				));
				$this->rows['product_specval']->save();
				
				$json['errno'] = 0;
				$this->_helper->json($json);
				break ;
				
			case 'editvalue' :
				$this->rows['product_specval'] = $this->models['product_specval']->find($this->input->id)->current();
				$this->rows['product_specval']->letter = $this->input->letter;
				$this->rows['product_specval']->memo = $this->input->memo;
				$this->rows['product_specval']->save();
				
				$json['errno'] = 0;
				$this->_helper->json($json);
				break ;
				
			default :
				break ;
		}
		$this->_helper->json($json);
	}
}

?>