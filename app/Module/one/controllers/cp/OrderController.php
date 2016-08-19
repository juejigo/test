<?php

class Onecp_OrderController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['one_phase'] = new Model_OnePhase();
		$this->models['one_order'] = new Model_OneOrder();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/onecp/order/list');
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
					'href' => '/onecp/order/list',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('o' => 'one_order'),array(new Zend_Db_Expr('COUNT(*)')));
		
		$query = '/onecp/order/list?page={page}';
		
		if (!empty($this->paramInput->id))
		{
			$select->where('o.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		//期数ID
		if (!empty($this->paramInput->phase_id))
		{
			$select->where('o.phase_id = ?',$this->paramInput->phase_id);
			$query .= "&phase_id={$this->paramInput->phase_id}";
		}
		//标题
		if(!empty($this->paramInput->subject))
		{
		    $select->where("o.subject like '%{$this->paramInput->subject}%' ");
		    $query .= "&subject={$this->paramInput->subject}";
		}
		//联系人
		if(!empty($this->paramInput->consignee))
		{
		    $select->where("o.consignee like '%{$this->paramInput->consignee}%' ");
		    $query .= "&consignee={$this->paramInput->consignee}";
		}
		//联系方式
		if(!empty($this->paramInput->mobile))
		{
		    $select->where('o.mobile = ?',$this->paramInput->mobile);
		    $query .= "&mobile={$this->paramInput->mobile}";
		}
		//类型
		if (!empty($this->paramInput->type))
		{
			$select->where('o.type = ?',$this->paramInput->type);
			$query .= "type={$this->paramInput->type}";
		}
		
		if ($this->paramInput->status !== '') 
		{
			if($this->paramInput->status == '20')
			{
				$select->where('o.status > ?',0);
			}
			else
			{
				$select->where('o.status = ?',$this->paramInput->status);
			}
			$query .= "&status={$this->paramInput->status}";
		}
		
		/* 分页 */
		
		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$orderList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','o')
			->joinLeft(array('m' => 'member_profile'),'o.buyer_id = m.member_id','member_name')
			->order(array('dateline DESC'))
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		foreach ($orderList as $key => $order)
		{
			if (empty($orderList[$key]['consignee']))
			{			
				//是否填写了收货地址
				$address = $this->_db->select()
					->from(array('a' => 'one_address'))
					->where('a.member_id = ?',$order['buyer_id'])
					->query()
					->fetch();
				$orderList[$key]['consignee'] = $address['name'];
				$orderList[$key]['province_id'] = $address['province_id'];
				$orderList[$key]['city_id'] = $address['city_id'];
				$orderList[$key]['county_id'] = $address['county_id'];
				$orderList[$key]['address'] = $address['address'];
				$orderList[$key]['mobile'] = $address['mobile'];
				$orderList[$key]['zip'] = $address['post_code'];
			}
		}
		
		$this->view->orderList = $orderList;
		
		//状态
		$status_array = array(
			array('value' => '20','name' => '有效订单'),
			array('value' => '0','name' => '未付款'),
			array('value' => '1','name' => '已付款'),
			array('value' => '2','name' => '待确认'),
			array('value' => '3','name' => '已完成'),
			array('value' => '13','name' => '已退款')
		);
		$this->view->status_array = $status_array;
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
					'href' => '/onecp/order/list',
					'text' => '返回')
			));
		}
		
		/* 订单内容 */
		
		$order = $this->_db->select()
			->from(array('o' => 'one_order'))
			->where('o.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		
		//是否填写了收货地址

		if (empty($order['address']))
		{		
			$address = $this->_db->select()
				->from(array('a' => 'one_address'))
				->where('a.member_id = ?',$order['buyer_id'])
				->query()
				->fetch();
			$order['consignee'] = $address['name'];
			$order['province_id'] = $address['province_id'];
			$order['city_id'] = $address['city_id'];
			$order['county_id'] = $address['county_id'];
			$order['address'] = $address['address'];
			$order['mobile'] = $address['mobile'];
			$order['zip'] = $address['post_code'];
		}

		//省市区
		$region = $this->_db->select()
			->from(array('r' => 'region'),'region_name')
			->where('id in (?)',array("{$order['province_id']}","{$order['city_id']}","{$order['county_id']}"))
			->order('level ASC')
			->query()
			->fetchAll();
		$order['region'] = $region[0]['region_name']."-".$region[1]['region_name']."-".$region[2]['region_name'];
	
		$this->view->order = $order;
		
		/* 期数详情*/
		
		$phase = $this->_db->select()
			->from(array('o' => 'one_order'),array('phase_id'))
			->joinLeft(array('h' => 'one_phase'), 'o.phase_id = h.id')
			->joinLeft(array('p' => 'product'),'p.id = h.product_id',array('supplier_id'))
			->joinLeft(array('a' => 'member_supplier'), 'p.supplier_id = a.id',array('supplier_name','address','telephone'))
			->where('o.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		$this->view->phase = $phase;
		
		/* 幸运号码*/
		
		$luckyNum = $this->_db->select()
			->from(array('l' => 'one_lucky_num'))
			->where('l.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->luckyNum = $luckyNum;
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
			/* 确认*/
		
			case 'confirm':
		
				if (!empty($this->input->id))
				{
					$this->rows['one_order'] = $this->models['one_order']->find($this->input->id)->current();
					$this->rows['one_order']->status = 3;
					$this->rows['one_order']->save();
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
