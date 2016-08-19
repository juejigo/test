<?php

class Financecp_DetailController extends Core2_Controller_Action_Cp
{
   public function init()
	{
		parent::init();

		$this->models['order'] = new Model_Order();
		$this->models['order_item'] = new Model_OrderItem();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/financecp/detail/flow');
	}
	
	/**
	 *  财务流水
	 */
	public function flowAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/admincp',
					'text' => '返回')
			));
		}

        /* 构造 SQL 选择器 */

		$perpage = 10;
		$select = $this->_db->select()
		    ->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')))
		    ->joinLeft(array('o_i' => 'order_item'),'o_i.order_id = o.id',array('*')); 

		$query = '/financecp/detail/flow?page={page}';

        if (!empty($this->paramInput->out_id) ) 
		{
			$select->where('o.out_id = ?',$this->paramInput->out_id);
			$query .= "&out_id={$this->paramInput->out_id}";
		}

		if (!empty($this->paramInput->item_name) ) 
		{
			$select->where('o_i.item_name = ?',$this->paramInput->item_name);
			$query .= "&item_name={$this->paramInput->item_name}";
		}

		if (!empty($this->paramInput->pay_amount) ) 
		{
			$select->where('o.pay_amount = ?',$this->paramInput->pay_amount);
			$query .= "&pay_amount={$this->paramInput->pay_amount}";
		}

		if (!empty($this->paramInput->dateline_from)) 
		{
			$select->where("o.dateline >= ?",strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}

		if (!empty($this->paramInput->dateline_to)) 
		{
			$select->where("o.dateline <= ?",strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}
        
		/* 分页 */

		$count = $select->query()
			   ->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		 
		/* 列表 */
		
		$outidList = $select->reset(Zend_Db_Select::COLUMNS)
		    ->columns(array('out_id','dateline','pay_amount','status'),'o')
		    ->columns(array('item_name'),'o_i')
		    ->order('o.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->outidList=$outidList;
	}
} 
