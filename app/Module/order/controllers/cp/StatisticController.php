<?php

class Ordercp_OrderController extends Core2_Controller_Action_Uc  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['order'] = new Model_Order();
		$this->models['order_shipping'] = new Model_OrderShipping();
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
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')));
		
		$query = '/ordercp/order/list?page={page}';
		
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
			$select->where('o.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
		}
		else 
		{
			$select->where('o.status >= ?',0);
			$query .= "&status={$this->paramInput->status}";
		}
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$orderList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','o')
			->joinLeft(array('m' => 'member'),'m.id = o.buyer_id',array('account'))
			->order('o.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->orderList = $orderList;
		
		/* 生成导出 excel 条件 */
		
		$excelQuery = str_replace('/ordercp/order/list?page={page}&','/ordercp/order/exportexcel?',$query);
		$this->view->excelQuery = $excelQuery;
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
		$this->view->order = $order;
			
		/* 产品 */
		
		$items = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
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
		$this->view->order = $order;
			
		/* 产品 */
		
		$items = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
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
	 *  批量导出EXCEL
	 */
	public function  exportexcelAction()
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
			$select->where('o.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
		}
		else 
		{
			$select->where('o.status >= ?',0);
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
		$resultPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1','工作单号');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1','品名');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1','寄件人');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1','寄件人手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1','收货人');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1','到达地');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1','收货人手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1','重要提示');
		
		// 数据
		$i = 2;
		foreach($data as $d)
		{
			// 品名
			$itemName = '';
			$items = $this->_db->select()
				->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
				->joinLeft(array('i2' => 'product_item'),'i2.id = i.item_id',array('art'))
				->joinLeft(array('p' => 'product'),'i2.product_id = p.id',array('sn'))
				->where('i.order_id = ?',$d['id'])
				->query()
				->fetchAll();
			$this->view->items = $items;
			foreach ($items as $item) 
			{
				# 供货商编号方法需做成统一方法
				$sn = $item['sn'];
				$code = $sn{2}.$sn{3}.$sn{4}.$sn{5};
				$itemName .= "{$code}-{$item['art']} {$item['spec_desc']}\n";
			}
			
			// 地址
			$address = getRegionPath($d['city_id'],$d['county_id']) . $d['address'];
			
			$resultPHPExcel->getActiveSheet()->getStyle("A{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$resultPHPExcel->getActiveSheet()->getStyle("B{$i}")->getAlignment()->setWrapText(true);
			$resultPHPExcel->getActiveSheet()->getStyle("D{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$resultPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			
			$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i,$d['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i,$itemName);
			$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i,SITE_NAME);
			$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i,'');
			$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i,$d['consignee']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i,$address);
			$resultPHPExcel->getActiveSheet()->setCellValue('G' . $i,$d['mobile']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H' . $i,$d['memo']);
			$i ++;
		}
		
		//设置导出文件名 
		$page = $corepage->currPage();
		$pageCount = $corepage->getPagecount();
		$outputFileName = ($page == $pageCount) ? "{$this->paramInput->dateline_from}-{$this->paramInput->dateline_to}_{$page}_last.xls" : "{$this->paramInput->dateline_from}-{$this->paramInput->dateline_to}_{$page}.xls";
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
		$filename = "runtime/{$outputFileName}";
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

		$this->view->downloadUrl = "/{$filename}";
		$nextPage = $page + 1;
		$this->view->nextUrl = ($page == $pageCount) ? '' : "/ordercp/order/exportexcel?page={$nextPage}" . $query;
	}
	
	/**
	 *  发货
	 */
	public function  sendAction()
	{
		if (!form($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::WAIT_BUYER_CONFIRM_GOODS;
		$this->rows['order']->save();
		
		$companies = array(
			'shunfeng' => '顺丰',
			'yuantong' => '圆通',
			'shentong' => '申通',
		);
		
		$this->rows['order_shipping'] = $this->models['order_shipping']->createRow(array(
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
	 *  同意退货
	 */
	public function  agreeAction()
	{
		if (!form($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
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
		if (!form($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::REFUND_SUCCESS;
		$this->rows['order']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  拒绝退货
	 */
	public function  refuseAction()
	{
		if (!form($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
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
		if (!form($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/ordercp/order',
					'text' => '返回')
			));
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::CANCLE;
		$this->rows['order']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
}

?>