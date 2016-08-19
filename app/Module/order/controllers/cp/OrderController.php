<?php

class Ordercp_OrderController extends Core2_Controller_Action_Cp 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();

		$this->models['order'] = new Model_Order();
		$this->models['member'] = new Model_Member();
		$this->models['order_shipping'] = new Model_OrderShipping();
		$this->models['order_discount'] = new Model_OrderDiscount();
		$this->models['order_log'] = new Model_OrderLog();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{	    
		$this->_redirect('/ordercp/order/list');
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
		
		$perpage = 50;
		$select = $this->_db->select()
			->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('m' => 'member'),'m.id = o.buyer_id',array());
		
		$query = '/ordercp/order/list?page={page}';

		//订单号
		if (!empty($this->paramInput->id)) 
		{
			$select->where('o.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		//标题
		if(!empty($this->paramInput->subject))
		{
		    $select->where("o.subject  like '%{$this->paramInput->subject}%' ");
		    $query .= "&subject={$this->paramInput->subject}";
		}
		//联系人
		if(!empty($this->paramInput->buyer_name))
		{
		    $select->where("o.buyer_name  like '%{$this->paramInput->buyer_name}%' ");
		    $query .= "&buyer_name={$this->paramInput->buyer_name}";
		}
		//联系方式
		if(!empty($this->input->mobile))
		{
		    $select->where('o.mobile = ?',$this->paramInput->mobile);
		    $query .= "&mobile={$this->paramInput->mobile}";
		}
		
		if (!empty($this->paramInput->consignee)) 
		{
			$select->where('o.consignee = ?',$this->paramInput->consignee);
			$query .= "&consignee={$this->paramInput->consignee}";
		}
		
		if ($this->paramInput->from !== '') 
		{
			$select->where('o.from = ?',$this->paramInput->from);
			$query .= "&from={$this->paramInput->from}";
		}
		
		if (!empty($this->paramInput->price_from)) 
		{
			$select->where("o.price >= ?",$this->paramInput->price_from);
			$query .= "&price_from={$this->paramInput->price_from}";
		}
		
		if (!empty($this->paramInput->price_to)) 
		{
			$select->where("o.price <= ?",$this->paramInput->price_to);
			$query .= "&price_to={$this->paramInput->price_to}";
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
		//echo $query;exit;
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$orderList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','o')
			->columns(array('account'),'m')
			->order('o.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
	    $this->view->province_id = $this->paramInput->province_id;
		$this->view->city_id = $this->paramInput->city_id;
		$this->view->county_id = $this->paramInput->county_id;

		for($i=0; $i<count($orderList);$i++)
		{
		    $supplier = $this->_db->select()
		      ->from(array('a' => 'order_item'))
		      ->joinLeft(array('b' => 'product'), 'a.product_id = b.id',array('supplier_id'))
		      ->joinLeft(array('c' => 'member_supplier'), 'c.id = b.supplier_id',array('telephone as supplier_telephone','supplier_name'))
		      ->where('a.order_id = ?',$orderList[$i]['id'])
		      ->query()
		      ->fetch();
		    
		    $orderList[$i]['supplier_telephone'] = $supplier['supplier_telephone'];
		    $orderList[$i]['supplier_name'] = $supplier['supplier_name'];
		}
		
		$this->view->orderList = $orderList;
		/*  搜索条件 */
		//dump($query);
		//$query = str_replace('/ordercp/order/list?page={page}','1',$query);
		//echo $query;exit;
		$this->view->query = $query;
		
		$status_array = array(
			array('value' => '20','name' => '有效订单'),
		    array('value' => '0','name' => '未付款'),
		    array('value' => '1','name' => '待确认'),
		    array('value' => '2','name' => '待出行'),
		    array('value' => '3','name' => '已完成'),
		    array('value' => '13','name' => '已退款')
		);
		
		$this->view->status_array = $status_array;
		
	}
	
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
	        case 'remarks':
	            
	            if($this->input->id != "")
	            {
                    $order = $this->_db->select()
                        ->from(array('a' => 'order'),array('admin_memo','id'))
                        ->where('a.id = ?',$this->input->id)
                        ->query()
                        ->fetch();
                    
                    $json['errno'] = '0';
                    $json['id'] = $order['id'];
                    $json['admin_memo'] = $order['admin_memo'];
                    $this->_helper->json($json);
	            }
	            
	            break;
	            
	        case 'editremarks':
	            
	            if($this->input->id != "")
	            {
	                $this->rows['order'] = $this->models['order']->find($this->input->id)->current();
	                $this->rows['order']->admin_memo = $this->input->remarks;
	                $this->rows['order']->save();
	                
	                $json['errno'] = '0';
	                $this->_helper->json($json);
	            }  
	            
	            break;
	            
	        case 'confirm_order':
	            if($this->input->id != "")
	            {
	                $this->rows['order'] = $this->models['order']->find($this->input->id)->current();
	                $this->rows['order']->status = 2;
	                $this->rows['order']->save();
	                 
	                $json['errno'] = '0';
	                $this->_helper->json($json);
	            }  
	            break;
            
	        case 'refund':
	            
	            if($this->input->id != "")
	            {
	            	//退款
	            	$order = $this->_db->select()
	            		->from(array('o' => 'order'),array('id','pay_amount','payment','out_id'))
						->where('o.id = ?',$this->input->id)
						->query()
						->fetch();
	            	if ($order['payment'] == 'wxapp')
	            	{
		            	$payAmount = $order['pay_amount']*100;
		            	$payAmount = (string)$payAmount;
		            	require_once "lib/api/wxpay/lib/WxPay.Api.php";
		            	$input = new WxPayRefund();
		            	$input->SetTransaction_id($order['out_id']);
		            	$input->SetTotal_fee($payAmount);
		            	$input->SetRefund_fee($payAmount);
		            	$input->SetOut_refund_no($this->input->id);
		            	$input->SetOp_user_id(WxPayConfig::MCHID);
		            	$refund = WxPayApi::refund($input);
		            	//查询退款结果
		            	$query = new WxPayRefundQuery();
		            	$query->SetTransaction_id($order['out_id']);
		            	$result = WxPayApi::refundQuery($query);
		            	
		            	if ($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS')
		            	{
		            		//改变状态
			                $this->rows['order'] = $this->models['order']->find($this->input->id)->current();
			                $this->rows['order']->status = Model_Order::REFUND_SUCCESS;
			                $this->rows['order']->save();
			            
			                $json['errno'] = '0';
			                $json['errmsg'] = $result['return_msg'];
			                $this->_helper->json($json);
		            	}
		            		            		
		            	$json['errno'] = '1';
		            	$json['errmsg'] = '退款失败，错误码:'.$result['err_code'].'错误原因'.$result['err_code_des'].$result['refund_status_0'];
		           		$this->_helper->json($json);
	            	}
	            	elseif ($order['payment'] == 'aliapp')
	            	{
	            		$json['errno'] = '2';
	            		$json['url'] = '/ordercp/order/alipayrefund?order_id='.$order['out_id'].'&pay_amount='.$order['pay_amount'];
	            		$this->_helper->json($json);
	            	}
	            }
	            
	        break;
        
        case 'shipping':
            
            if($this->input->id != "")
            {
                $companies = array(
                    'shunfeng' => '顺丰',
                    'yuantong' => '圆通',
                    'zhongtong' => '中通',
                    'shentong' => '申通',
                    'huitong' => '汇通',
                    'yunda' => '韵达',
                    'tiantian' => '天天',
                    'ems' => 'EMS',
                    'quanfeng' => '全峰',
                    'kuaijie' => '快捷',
                    'suer' => '速尔',
                    'yousu' => '优速',
                    'zjs' => '宅急送',
                );
                
                $this->rows['order_shipping'] = $this->models['order_shipping']->createRow(array(
                   // 'member_id' => $this->rows['order']->buyer_id,
                    'member_id' => 0,
                    'order_id' => $this->input->id,
                    'type' => 0,
                    'shipping_company' => $companies["{$this->input->company_no}"],
                    'company_no' => $this->input->company_no,
                    'shipping_no' => $this->input->shipping_no,
                   // 'memo' => $this->input->memo,
                ));
                $this->rows['order_shipping']->save();
            }
            $json['errno'] = '0';
            $this->_helper->json($json);
            
            break;
	        
	    }
	}
	
	/**
	 *  详情
	 */
	public function detailAction()
	{
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
		}
		
		/* 订单内容 */
		
		$order = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		if($order['city_id'] !=""  && $order['county_id']  !="" && $order['address']  !="" )
		{
		    $order['address'] = getRegionPath($order['city_id'],$order['county_id']) . $order['address'];
		}else
		{
		    $order['address'] ="";
		}
		
		$this->view->order = $order;
		
		/*供货商*/
		$supplier = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id'))
			->joinLeft(array('p' => 'product'),'p.id = i.product_id',array('supplier_id'))
			->joinLeft(array('a' => 'member_supplier'), 'p.supplier_id = a.id')
			->where('i.order_id = ?',$this->paramInput->id)
			->query()
			->fetch();

		$this->view->supplier = $supplier;
		
		
		/* 产品 */
		
		$items = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
			->joinLeft(array('p' => 'product'),'p.id = i.product_id',array('sn','product_name','parent_id'))
			->joinLeft(array('i2' => 'product_item'),'i2.id = i.item_id',array('spec_desc'))
			->where('i.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		
		for($i=0 ;$i<count($items);$i++)
		{
		    //查询商品图片
		    $product = $this->_db->select()
		      ->from(array('p' => 'product'),array('image'))
		      ->where('p.id = ?',$items[$i]['parent_id'])
		      ->query()
		      ->fetch();
		    
		    $items[$i]['product_image'] = $product['image'];
		}
		
		$this->view->items = $items;
		/* 发货单 */
		
		$bills = $this->_db->select()
			->from(array('s' => 'order_shipping'))
			->where('s.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->bills = $bills;
		
		/* 历史记录 */
		
		$logs = $this->_db->select()
			->from(array('l' => 'order_log'))
			->where('l.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->logs = $logs;

		
		/* 优惠详情 */
		
		$discounts = $this->_db->select()
			->from(array('d' => 'order_discount'))
			->where('d.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->discounts = $discounts;
		
		/* 快递*/
		
		$shipping = $this->_db->select()
		      ->from(array('o' => 'order_shipping'))
		      ->where('o.order_id = ?',$this->paramInput->id)
		      ->query()
		      ->fetchAll();
		
		$this->view->shipping = $shipping;
		
		/* 游客*/
		
		$tourist = $this->_db->select()
			->from(array('t' => 'order_tourist'))
			->where('t.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		
		$this->view->tourist = $tourist;
		
		/* 合同*/
		
		$contract = $this->_db->select()
			->from(array('o' => 'order_contract'))
			->joinLeft(array('c' => 'contract'), 'o.contract_id = c.id')
			->where('order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		
		$this->view->contract = $contract;
		
		/* 附件*/
		
		$addons = $this->_db->select()
			->from(array('a' => 'order_addon'),array('price as o_price','num'))
			->joinLeft(array('p' => 'product_addon'), 'a.addon_id = p.id')
			->where('a.order_id = ?',$this->paramInput->id)
			->where('a.status = ?',1)
			->query()
			->fetchAll();
		
		$this->view->addons = $addons;
		
		/*快递公司 */
		$companies = array(
		    'shunfeng' => '顺丰',
		    'yuantong' => '圆通',
		    'zhongtong' => '中通',
		    'shentong' => '申通',
		    'huitong' => '汇通',
		    'yunda' => '韵达',
		    'tiantian' => '天天',
		    'ems' => 'EMS',
		    'quanfeng' => '全峰',
		    'kuaijie' => '快捷',
		    'suer' => '速尔',
		    'yousu' => '优速',
		    'zjs' => '宅急送',
		);
		$this->view->companies = $companies;
	}
	
	/**
	 *  打印订单
	 */
	public function  printAction()
	{
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
		}
		
		/* 订单内容 */
		
		$order = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		$order['address'] = getRegionPath($order['city_id'],$order['county_id']) . $order['address'];
		$this->view->order = $order;
		
		/* 产品 */
		
		$items = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
			->joinLeft(array('p' => 'product'),'p.id = i.product_id',array('sn'))
			->joinLeft(array('i2' => 'product_item'),'i2.id = i.item_id',array('art'))
			->where('i.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->items = $items;
		
		/* 发货单 */
		
		$bills = $this->_db->select()
			->from(array('s' => 'order_shipping'))
			->where('s.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->bills = $bills;
		
		/* 历史记录 */
		
		$logs = $this->_db->select()
			->from(array('l' => 'order_log'))
			->where('l.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->logs = $logs;
		
		/* 优惠详情 */
		
		$discounts = $this->_db->select()
			->from(array('d' => 'order_discount'))
			->where('d.order_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		$this->view->discounts = $discounts;
	}
	
	/**
	 *  导出订单EXCEL
	 */
	public function  exportorderAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
		}
		
		/* 引入PHPExcel相关文件 */
		
		require_once 'lib/api/phpexcel/PHPExcel.php';
		require_once 'lib/api/phpexcel/PHPExcel/IOFactory.php';
		require_once 'lib/api/phpexcel/PHPExcel/Writer/Excel5.php';
		
		$resultPHPExcel = new PHPExcel();
		
		$perpage = 500;
		$select = $this->_db->select()
			->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')));
			
		$query = '';
		
		if (!empty($this->paramInput->province_id)) 
		{
			$select->where('o.province_id = ?',$this->paramInput->province_id);
			$query .= "&province_id={$this->paramInput->province_id}"; 
			
		}
				
		if (!empty($this->paramInput->city_id) ) 
		{
			$select->where('o.city_id = ?',$this->paramInput->city_id);
			$query .= "&city_id={$this->paramInput->city_id}";
	
		}		
		
		if (!empty($this->paramInput->county_id)) 
		{
			$select->where('o.county_id = ?',$this->paramInput->county_id);
			$query .= "&county_id={$this->paramInput->county_id}";
	
		}			
				
		
		
		
		
		if (!empty($this->paramInput->id)) 
		{
			$select->where('o.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		
		if ($this->paramInput->from !== '') 
		{
			$select->where('o.from = ?',$this->paramInput->from);
			$query .= "&from={$this->paramInput->from}";
		}
		
		if (!empty($this->paramInput->price_from)) 
		{
			$select->where("o.price >= ?",$this->paramInput->price_from);
			$query .= "&price_from={$this->paramInput->price_from}";
		}
		
		if (!empty($this->paramInput->price_to)) 
		{
			$select->where("o.price <= ?",$this->paramInput->price_to);
			$query .= "&price_to={$this->paramInput->price_to}";
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
		
		if ($this->paramInput->status !== '') 
		{
			if ($this->paramInput->status == 20) 
			{
				$select->where('o.status NOT IN (?)',array(Model_Order::CANCLE,Model_Order::WAIT_BUYER_PAY));
			}
			else 
			{
				$select->where('o.status = ?',$this->paramInput->status);
			}
			$query .= "&status={$this->paramInput->status}";
		}
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count);
		
		/* 获取订单数据 */
		
		$data = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','o')
			->order('o.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		/* 设置参数 */
		
		// 设置
		$resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1','工作单号');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1','品名');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1','寄件人');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1','寄件人手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1','收货人');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1','到达地');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1','收货人手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1','重要提示');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1','订单号');
		
		// 数据
		$i = 2;
		foreach($data as $d)
		{
			// 品名
			$itemName = '';
			$items = $this->_db->select()
				->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
				->joinLeft(array('i2' => 'product_item'),'i2.id = i.item_id',array('area','art'))
				->joinLeft(array('p' => 'product'),'i2.product_id = p.id',array('sn'))
				->where('i.order_id = ?',$d['id'])
				->query()
				->fetchAll();
			$this->view->items = $items;
			foreach ($items as $item) 
			{
				$area = '';
				$code = getSupplierCode($item['sn']);
				$itemName .= "{$code}-{$item['art']} {$item['spec_desc']}{$area}\n";
			}
			
			// 地址
			$address = getRegionPath($d['city_id'],$d['county_id']) . $d['address'];
			
			$resultPHPExcel->getActiveSheet()->getStyle("B{$i}")->getAlignment()->setWrapText(true);
			$resultPHPExcel->getActiveSheet()->getStyle("D{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$resultPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$resultPHPExcel->getActiveSheet()->getStyle("I{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			
			$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i,'');
			$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i,$itemName);
			$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i,SITE_NAME);
			$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i,'4009267858');
			$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i,$d['consignee']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i,$address);
			$resultPHPExcel->getActiveSheet()->setCellValue('G' . $i,$d['mobile']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H' . $i,$d['memo']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I' . $i,$d['id']);
			$i ++;
		}
		
		//设置导出文件名 
		$page = $corepage->currPage();
		$pageCount = $corepage->getPagecount();
		$outputFileName = ($page == $pageCount) ? "o_{$this->paramInput->dateline_from}_{$this->paramInput->dateline_to}_{$page}_last.xls" : "o_{$this->paramInput->dateline_from}_{$this->paramInput->dateline_to}_{$page}.xls";
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
		$filename = "runtime/{$outputFileName}";
		if (is_file($filename)) {
			@unlink($filename);
		}
		$xlsWriter->save($filename);
		
		//ob_start();ob_flush();
//		header("Content-Type: application/force-download");
//		header("Content-Type: application/octet-stream");
//		header("Content-Type: application/download");
//		header('Content-Disposition:inline;filename="'.$outputFileName.'"');
//		header("Content-Transfer-Encoding: binary");
//		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//		header("Pragma: no-cache");
		
		//$xlsWriter->save( "php://output" );
		
//		$finalFileName = 'runtime/' . SCRIPT_TIME . '.xls';
//		$xlsWriter->save($finalFileName);
//		echo file_get_contents($finalFileName);

		$this->view->downloadUrl = DOMAIN . $filename;
		$nextPage = $page + 1;
		$this->view->nextUrl = ($page == $pageCount) ? '' : "/ordercp/order/exportorder?page={$nextPage}" . $query;
	}
	
	/**
	 *  导出商品EXCEL
	 */
	public function  exportitemAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
		}
		
		/* 引入PHPExcel相关文件 */
		
		require_once 'lib/api/phpexcel/PHPExcel.php';
		require_once 'lib/api/phpexcel/PHPExcel/IOFactory.php';
		require_once 'lib/api/phpexcel/PHPExcel/Writer/Excel5.php';
		
		$resultPHPExcel = new PHPExcel();
		
		$perpage = 500;
		$select = $this->_db->select()
			->from(array('i' => 'order_item'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('o' => 'order'),'o.id = i.order_id');
		
		$query = '';
		
			if (!empty($this->paramInput->province_id)) 
		{
			$select->where('o.province_id = ?',$this->paramInput->province_id);
			$query .= "&province_id={$this->paramInput->province_id}"; 
			
		}
				
		if (!empty($this->paramInput->city_id) ) 
		{
			$select->where('o.city_id = ?',$this->paramInput->city_id);
			$query .= "&city_id={$this->paramInput->city_id}";
	
		}		
		
		if (!empty($this->paramInput->county_id)) 
		{
			$select->where('o.county_id = ?',$this->paramInput->county_id);
			$query .= "&county_id={$this->paramInput->county_id}";
	
		}		 
		
		
		if (!empty($this->paramInput->id)) 
		{
			$select->where('o.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		
		if ($this->paramInput->from !== '') 
		{
			$select->where('o.from = ?',$this->paramInput->from);
			$query .= "&from={$this->paramInput->from}";
		}
		
		if (!empty($this->paramInput->price_from)) 
		{
			$select->where("o.price >= ?",$this->paramInput->price_from);
			$query .= "&price_from={$this->paramInput->price_from}";
		}
		
		if (!empty($this->paramInput->price_to)) 
		{
			$select->where("o.price <= ?",$this->paramInput->price_to);
			$query .= "&price_to={$this->paramInput->price_to}";
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
		
		if ($this->paramInput->status !== '') 
		{
			if ($this->paramInput->status == 20) 
			{
				$select->where('o.status NOT IN (?)',array(Model_Order::CANCLE,Model_Order::WAIT_BUYER_PAY));
			}
			else 
			{
				$select->where('o.status = ?',$this->paramInput->status);
			}
			$query .= "&status={$this->paramInput->status}";
		}
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count);
		
		/* 获取订单数据 */
		
		$rs = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','i')
			->joinLeft(array('i2' => 'product_item'),'i2.id = i.item_id',array('art'))
			->joinLeft(array('p' => 'product'),'p.id = i.product_id',array('sn'))
			->order('o.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		//dump($rs);exit;
		/* 设置参数 */
		
		// 设置
		$resultPHPExcel->getActiveSheet()->setCellValue('A1','产品名称');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1','商家编号');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1','货号');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1','规格');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1','数量');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1','订单号');
		
		$data = array();
		foreach ($rs as $r) 
		{
			$item = array();
			$item['order_id'] = $r['order_id'];
			$item['item_name'] = $r['item_name'];
			$item['code'] = getSupplierCode($r['sn']);
			$item['art'] = $r['art'];
			$item['cost_price'] = $r['cost_price'];
			$item['spec_desc'] = $r['spec_desc'];
			$item['num'] = $r['num'];
			$data[] = $item;
		}
		
		// 数据
		$i = 2;
		foreach($data as $d)
		{
			$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i,$d['item_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i,$d['code']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i,$d['art']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i,$d['spec_desc']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i,$d['num']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i,$d['order_id'],PHPExcel_Cell_DataType::TYPE_STRING);
			$i ++;
		}
		
		//设置导出文件名 
		$page = $corepage->currPage();
		$pageCount = $corepage->getPagecount();
		$outputFileName = ($page == $pageCount) ? "i_{$this->paramInput->dateline_from}_{$this->paramInput->dateline_to}_{$page}_last.xls" : "i_{$this->paramInput->dateline_from}_{$this->paramInput->dateline_to}_{$page}.xls";
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
		$filename = "runtime/{$outputFileName}";
		if (is_file($filename)) {
			@unlink($filename);
		}
		$xlsWriter->save($filename);
		
		//ob_start();ob_flush();
//		header("Content-Type: application/force-download");
//		header("Content-Type: application/octet-stream");
//		header("Content-Type: application/download");
//		header('Content-Disposition:inline;filename="'.$outputFileName.'"');
//		header("Content-Transfer-Encoding: binary");
//		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//		header("Pragma: no-cache");
		
		//$xlsWriter->save( "php://output" );
		
//		$finalFileName = 'runtime/' . SCRIPT_TIME . '.xls';
//		$xlsWriter->save($finalFileName);
//		echo file_get_contents($finalFileName);

		$this->view->downloadUrl = DOMAIN . $filename;
		$nextPage = $page + 1;
		$this->view->nextUrl = ($page == $pageCount) ? '' : "/ordercp/order/exportitem?page={$nextPage}" . $query;
	}
	
	/**
	 *  取消退货
	 */
	public function canclerefundAction()
	{
		$json = array();
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::WAIT_BUYER_CONFIRM_GOODS;
		$this->rows['order']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  发货(增加退货单)
	 */
	public function  sendAction()
	{
		$json = array();
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		
		if ($this->rows['order']->status == Model_Order::WAIT_SELLER_SEND_GOODS) 
		{
			$this->rows['order']->status = Model_Order::WAIT_BUYER_CONFIRM_GOODS;
			$this->rows['order']->save();
		}
		
		$companies = array(
			'shunfeng' => '顺丰',
			'yuantong' => '圆通',
			'zhongtong' => '中通',
			'shentong' => '申通',
			'huitong' => '汇通',
			'yunda' => '韵达',
			'tiantian' => '天天',
			'ems' => 'EMS',
			'quanfeng' => '全峰',
			'kuaijie' => '快捷',
			'suer' => '速尔',
			'yousu' => '优速',
			'zjs' => '宅急送',
		);
		
		$this->rows['order_shipping'] = $this->models['order_shipping']->createRow(array(
		    'member_id' => "124",
			'order_id' => $this->input->id,
			'type' => 0,
			'shipping_company' => $companies["{$this->input->company_no}"],
			'company_no' => $this->input->company_no,
			'shipping_no' => $this->input->shipping_no,
			'memo' => $this->input->memo,
		));
		$this->rows['order_shipping']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  支付宝退款
	 */
	public function alipayrefundAction()
	{
		if (!params($this))
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
							'href' => '/ordercp/order',
							'text' => '返回')
			));
		}
		
		require_once("lib/api/aliapp/alipay.config.php");
		require_once("lib/api/aliapp/lib/alipay_submit.class.php");
		 
		 
		//批次号，必填，格式：当天日期[8位]+序列号[3至24位]，如：201603081000001
		$batch_no = date('Ymdhis',time());
		
		//退款笔数，必填，参数detail_data的值中，“#”字符出现的数量加1，最大支持1000笔（即“#”字符出现的数量999个）
		$batch_num = 1;
		
		//退款详细数据，必填，格式（支付宝交易号^退款金额^备注），多笔请用#隔开
		$detail_data = $this->paramInput->order_id.'^'.$this->paramInput->pay_amount.'^正常退款';
		 
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => 'refund_fastpay_by_platform_pwd',
				"partner" => trim($alipay_config['partner']),
				"notify_url"	=> DOMAIN . 'pay/aliapp/refund',
				"seller_user_id"	=> trim($alipay_config['partner']),
				"refund_date"	=> trim(date("Y-m-d H:i:s",time())),
				"batch_no"	=> $batch_no,
				"batch_num"	=> $batch_num,
				"detail_data"	=> $detail_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		//建立请求

		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		
		$this->view->html = $html_text;
	}
	/**
	 *  同意退货
	 */
	public function  agreeAction()
	{
		$json = array();
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::WAIT_BUYER_RETURN_GOODS;
		$this->rows['order']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  退货成功
	 */
	public function  refundAction()
	{
		$json = array();
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::REFUND_SUCCESS;
		$this->rows['order']->save();
		
		/* 如果是余额支付 直接退款 */
		
		if ($this->rows['order']->payment == 'balance') 
		{
			$this->rows['member'] = $this->models['member']->find($this->rows['order']->buyer_id)->current();
			$this->rows['member']->balance += $this->rows['order']->pay_amount;
			$this->rows['member']->save();
		}
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  拒绝退货
	 */
	public function  refuseAction()
	{
		$json = array();
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->refuse_reason = $this->input->refuse_reason;
		$this->rows['order']->status = Model_Order::SELLER_REFUSE_BUYER;
		$this->rows['order']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  关闭订单
	 */
	public function  cancleAction()
	{
		$json = array();
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::CANCLE;
		$this->rows['order']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  设置优惠
	 */
	public function discountAction() 
	{
		$json = array();
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 优惠记录 */
		
		$this->rows['order_discount'] = $this->models['order_discount']->createRow();
		$this->rows['order_discount']->order_id = $this->input->id;
		$this->rows['order_discount']->operator_id = $this->_user->id;
		$this->rows['order_discount']->type = 0;
		$this->rows['order_discount']->amount = $this->input->amount;
		$this->rows['order_discount']->status = 1;
		$this->rows['order_discount']->save();
		
		/* 减少订单价格 */
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->discount += $this->input->amount;
		$this->rows['order']->pay_amount -= $this->input->amount;
		$this->rows['order']->save();
		
		/* 订单记录 */
		
		$this->models['order_log']->createRow(array(
			'order_id' => $this->input->id,
			'operator_id' => $this->_user->id,
			'desc' => "设置优惠：{$this->input->amount}",
		))->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  查看物流
	 */
	public function shippingAction()
	{
		if (!params($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取订单最近的发货单 */
		
		$orderShipping = $this->_db->select()
			->from(array('s' => 'order_shipping'))
			->where('s.id = ?',$this->paramInput->shipping_id)
			->where('s.type = ?',0)
			->order('dateline DESC')
			->query()
			->fetch();
		
		var_dump($orderShipping);
		die();
		/* 物流信息 */
		
		// 调用物流接口
		$kuaidi = new Core_Kuaidi();
		$data = $kuaidi->getData($orderShipping['shipping_no'],$orderShipping['company_no']);
		
		var_dump($data);
		die();
		exit;
	}
	
	/**
	 *  导入发货单
	 */
	public function importsendAction() 
	{
		if ($this->_request->isPost()) 
		{
			$adapter = new Zend_File_Transfer();
			$adapter->addValidator('Extension',true,'xlsx,xls');
			
			if ($adapter->isValid('file')) 
			{
				$ext = strtolower(strrchr($_FILES['file']['name'],'.'));
				$destination = 'static/data/excel/' . date('Y/m/d/',time()) . date('His',time()) . mt_rand(1000,100000) . "{$ext}";
				$dirs = dirname($destination);
			    if (!is_dir($dirs))
			    {
			    	mkdir($dirs,0777,true);
			    }
				
				$adapter->addFilter('Rename',$destination,'file');
				$adapter->receive('file');
				
				require_once 'lib/api/phpexcel/PHPExcel.php';
			    require_once 'lib/api/phpexcel/PHPExcel/IOFactory.php';
			    require_once 'lib/api/phpexcel/PHPExcel/Reader/Excel5.php';
			    
			    $objReader = PHPExcel_IOFactory::createReader('Excel5');
			    $objReader->setReadDataOnly(true);
			    $objPHPExcel = $objReader->load($destination);
			    $objWorksheet = $objPHPExcel->getActiveSheet();
			    $highestRow = $objWorksheet->getHighestRow();
			    $highestColumn = $objWorksheet->getHighestColumn();
			    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			    
			    $data = array();
			    for ($row = 1;$row <= $highestRow;$row++)
			    { 
			    	 for ($col = 0;$col < $highestColumnIndex;$col++)
			    	 {
			    	 	$data[$row][] = (string) $objWorksheet->getCellByColumnAndRow($col,$row)->getValue();
			    	 }
				}
				
				#分步导入
				$total = count($row);
			
				foreach ($data as $d) 
				{
					$this->rows['order'] = $this->models['order']->find($d[8])->current();
					
					if (!empty($this->rows['order'])) 
					{
						/* 如果是未发货状态则改成已发货状态 */
						if ($this->rows['order']->status == Model_Order::WAIT_SELLER_SEND_GOODS) 
						{
							$this->rows['order']->status = Model_Order::WAIT_BUYER_CONFIRM_GOODS;
							$this->rows['order']->save();
						}
						
						/* 是否重复导入 */
						$count = $this->_db->select()
							->from(array('s' => 'order_shipping'),array(new Zend_Db_Expr('COUNT(*)')))
							->where('s.order_id = ?',$d[8])
							->where('s.company_no = ?','zhaijisong')
							->where('s.shipping_no = ?',$d[0])
							->query()
							->fetchColumn();
						
						if ($count == 0) 
						{
							$this->rows['order_shipping'] = $this->models['order_shipping']->createRow(array(
								'order_id' => $d[8],
								'type' => 0,
								'shipping_company' => '宅急送',
								'company_no' => 'zhaijisong',
								'shipping_no' => $d[0],
							));
							$this->rows['order_shipping']->save();
						}
					}
				}
				
				echo '导入成功';
				exit;
			}
		}
	}

}

?>