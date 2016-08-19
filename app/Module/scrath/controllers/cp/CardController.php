<?php

class Scrathcp_CardController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['scrath_card'] = new Model_ScrathCard();
		$this->models['scrath_prize'] = new Model_ScrathPrize();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/scrathcp/card/list');
	}
	
	/**
	 *  列表
	 */
	public function listAction()
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
			->from(array('c' => 'scrath_card'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('o' => 'scrath_order'), 'c.scrath_order_id = o.id')
			->joinLeft(array('m' => 'member_profile'), 'c.member_id = m.member_id')
			->joinLeft(array('z' => 'scrath_prize'), 'c.id = z.scrath_card_id')
			->joinLeft(array('p' => 'scrath_product'), 'z.product_id = p.id')
			->where('c.scrath_id = ?',$this->paramInput->scrath_id)
			->where('c.status = ?',0);
	
		$query = '/scrathcp/card/list?page={page}&scrath_id='.$this->paramInput->scrath_id;
	
		if (!empty($this->paramInput->member_name))
		{
			$select->where('m.member_name like ?','%'.$this->paramInput->member_name.'%');
			$query .= "&member_name={$this->paramInput->member_name}";
		}
	
		if ($this->paramInput->is_prize !== '')
		{    
			$select->where('c.is_prize = ?',$this->paramInput->is_prize);
			$query .= "&is_prize={$this->paramInput->is_prize}";
			$this->view->is_prize = $this->paramInput->is_prize;
		}
		else
		{
			$select->where('c.is_prize in (?)',array(0,1));
		}
		
		if (!empty($this->paramInput->product_level))
		{
			$select->where('p.product_level like ?','%'.$this->paramInput->product_level.'%');
			$query .= "&product_level={$this->paramInput->product_level}";
		}
	
		if (!empty($this->paramInput->product_name))
		{
			$select->where('p.product_name like ?','%'.$this->paramInput->product_name.'%');
			$query .= "&product_name={$this->paramInput->product_name}";
		}

		/* 分页 */
	
		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
	
		/* 列表 */
	
		$cardList = array();
		$cardListResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','add_time','use_time','is_prize'),'c')
			->columns(array('id as o.id'),'o')
			->columns(array('m.member_name'),'m')
			->columns(array('product_name','product_level','image'),'p')
			->columns(array('id as z.id','add_time as z.add_time','phone','remarks','is_deliver','redeem_code','use_time as useruser_time'),'z')
			->order('c.use_time DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		if (!empty($cardListResult))
		{
			foreach ($cardListResult as $key => $value)
			{
				$cardList[$key]['id'] = $value['id'];
				$cardList[$key]['is_prize'] = $value['is_prize'];
				$cardList[$key]['order_sn'] = $value['o.id'];
				$cardList[$key]['member_name'] = $value['member_name'];
				$cardList[$key]['add_time'] = Date("Y-m-d H:i:s",$value['add_time']);
				$cardList[$key]['use_time'] = Date("Y-m-d H:i:s",$value['use_time']);
				$cardList[$key]['useruser_time'] = Date("Y-m-d H:i:s",$value['useruser_time']);
				
				$cardList[$key]['redeem_code'] = $value['redeem_code'];
				//获奖
				if (!empty($value['is_prize']))
				{
					$cardList[$key]['product_name'] = $value['product_name'];
					$cardList[$key]['product_level'] = $value['product_level'];
					$cardList[$key]['image'] = $value['image'];
					$cardList[$key]['z_id'] = $value['z.id'];
					$cardList[$key]['phone'] = $value['phone'];
// 					$cardList[$key]['consignee'] = $value['consignee'];
// 					$cardList[$key]['address'] = $value['address'];
//					$cardList[$key]['invoice_number'] = $value['invoice_number'];
					$cardList[$key]['is_deliver'] = $value['is_deliver'];
//					$cardList[$key]['express'] = $value['express'];
					$cardList[$key]['note'] = $value['remarks'];
					$cardList[$key]['prize_time'] = Date("Y-m-d H:i:s",$value['z.add_time']);
				}
			}
		}
		$this->view->cardList = $cardList;
	}
	
	/**
	 *  ajax
	 */
	public function  ajaxAction()
	{
		$op = $this->_request->getQuery('op','');
		$json = array();
	
		/* 检验传值 */
		
		if (!ajax($this))
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
	
		switch ($op)
		{	
			/* 发货*/
			
			case 'deliver':
				
				if (!empty($this->input->id))
				{
				    //查询
				    $prize = $this->_db->select()
				        ->from(array('o' => 'scrath_prize'),'id')
				        ->where('o.scrath_card_id = ?',$this->input->id)
				        ->query()
				        ->fetch();
				    
					$this->rows['scrath_prize'] = $this->models['scrath_prize']->find($prize['id'])->current();
					$this->rows['scrath_prize']->use_time = time();
					$this->rows['scrath_prize']->is_deliver = 1;
					$this->rows['scrath_prize']->save();
					
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
				
			/* 删除*/
				
			case 'delete':
	
				$this->_helper->viewRenderer->setNoRender();
				if (!empty($this->input->id))
				{
					$this->rows['scrath_card'] = $this->models['scrath_card']->find($this->input->id)->current();
					$this->rows['scrath_card']->status = -1;
					$this->rows['scrath_card']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
		}
	}
	
}

?>
