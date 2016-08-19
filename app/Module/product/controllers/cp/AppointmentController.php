<?php

class Productcp_AppointmentController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();	
		
		$this->models['product_appointment'] = new Model_ProductAppointment();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/productcp/appointment/list');
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
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('a' => 'product_appointment'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('m' => 'member_profile'),'a.member_id = m.member_id');
		
		$query = '/productcp/appointment/list?page={page}';
		$query .= "&area={$this->paramInput->area}";
		
		if (!empty($this->paramInput->member_name))
		{
			$select->where('m.member_name like ?','%'.$this->paramInput->member_name.'%');
			$query .= "&member_name={$this->paramInput->member_name}";
			
		}
		if (!empty($this->paramInput->member_id))
		{
			$select->where('a.member_id = ?',$this->paramInput->member_id);
			$query .= "&member_id={$this->paramInput->member_id}";
		}
		
		if (!empty($this->paramInput->tourism_type))
		{
			$select->where('a.tourism_type = ?',$this->paramInput->tourism_type);
			$query .= "&tourism_type={$this->paramInput->tourism_type}";
		}
		
		if (!empty($this->paramInput->destination))
		{
			$select->where('a.destination = ?',$this->paramInput->destination);
			$query .= "&destination={$this->paramInput->destination}";
		}
		
		if (!empty($this->paramInput->phone))
		{
			$select->where('a.phone = ?',$this->paramInput->phone);
			$query .= "&phone={$this->paramInput->phone}";
		}
		
		if (!empty($this->paramInput->dateline_from))
		{
			$select->where("a.start_time >= ?",strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}
		
		if (!empty($this->paramInput->dateline_to))
		{
			$select->where("a.end_time <= ?",strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}
		
		if ($this->paramInput->status !== '')
		{
			$select->where('a.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
		}
		else
		{
			$select->where('a.status in (?)',array(0,1));
		}
		
		$this->view->query = $query;
		/* 分页 */
		
		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$appointmentList = array();
		$appointmentListResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','member_id','status','tourism_type','start_time','end_time','destination','phone'),'a')
			->columns(array('member_name'),'m')
			->order('a.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		if(!empty($appointmentListResult))
		{
			foreach($appointmentListResult as $result)
			{
				$result['start_time'] = date("Y-m-d",$result['start_time']);
				$result['end_time'] = date("Y-m-d",$result['end_time']);
				$appointmentList[] = $result;
			}
		}
		$this->view->appointmentList = $appointmentList;
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
			/* 确认预约*/
			
			case 'confirm':
				
				if(!empty($this->input->id))
				{
					$this->rows['product_appointment'] = $this->models['product_appointment']->find($this->input->id)->current();
					$this->rows['product_appointment']->status = 1;
					$this->rows['product_appointment']->save();
					
					$sms = new Core_Sms();
					$content = "【友趣游】尊敬的客户您好！后台已收到您的预约信息，我们将会在第一时间为您推送最新的旅游尾单信息！如有疑问请拨打客服热线：0577-88898776。客服工作时间为：9:00-22:30，祝您生活愉快！";
					$sms->send($this->rows['product_appointment']->phone,$content);
					$json['errno'] = '0';
					$this->_helper->json($json);
				}
				break;
				
			/* 删除*/
				
			case 'delete':
			
				//批量
				if (is_array($this->input->id))
				{
					foreach ($this->input->id as $key => $value)
					{
						$idlist[$value] = -1;
					}
					$ids = implode(',', array_keys($idlist));
					$sql = "UPDATE product_appointment SET status = CASE id ";
					foreach ($idlist as $id => $status)
					{
						$sql .= sprintf("WHEN %d THEN %d ", $id, $status);
					}
					$sql .= "END WHERE id IN ($ids)";
					$db = Zend_Registry::get('db');
					$db->query($sql);
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				//单条
				elseif (!empty($this->input->id))
				{
					$this->rows['product_appointment'] = $this->models['product_appointment']->find($this->input->id)->current();
					$this->rows['product_appointment']->status = -1;
					$this->rows['product_appointment']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
			
			default:
				break;
		}	
	}
	
	/**
	 *  导出商品EXCEL
	 */
	public function  exportAction()
	{
		/* 检验传值 */
		 
		if (!params($this))
		{
			/* 提示 */
			
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/product/list',
					'text' => '返回')
			));
		}
	
		/* 引入PHPExcel相关文件 */
	
		require_once 'lib/api/phpexcel/PHPExcel.php';
		require_once 'lib/api/phpexcel/PHPExcel/IOFactory.php';
		require_once 'lib/api/phpexcel/PHPExcel/Writer/Excel5.php';
		$resultPHPExcel = new PHPExcel();
	
		/* 构造 SQL 选择器 */
	
		$perpage = 50;
		$select = $this->_db->select()
			->from(array('a' => 'product_appointment'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('m' => 'member_profile'),'a.member_id = m.member_id');
		
		$query = '';
		
		if (!empty($this->paramInput->member_name))
		{
			$select->where('m.member_name like ?','%'.$this->paramInput->member_name.'%');
			$query .= "&member_name={$this->paramInput->member_name}";
				
		}
		if (!empty($this->paramInput->member_id))
		{
			$select->where('a.member_id = ?',$this->paramInput->member_id);
			$query .= "&member_id={$this->paramInput->member_id}";
		}
		
		if (!empty($this->paramInput->tourism_type))
		{
			$select->where('a.tourism_type = ?',$this->paramInput->tourism_type);
			$query .= "&tourism_type={$this->paramInput->tourism_type}";
		}
		
		if (!empty($this->paramInput->destination))
		{
			$select->where('a.destination = ?',$this->paramInput->destination);
			$query .= "&destination={$this->paramInput->destination}";
		}
		
		if (!empty($this->paramInput->phone))
		{
			$select->where('a.phone = ?',$this->paramInput->phone);
			$query .= "&phone={$this->paramInput->phone}";
		}
		
		if (!empty($this->paramInput->dateline_from))
		{
			$select->where("a.start_time >= ?",strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}
		
		if (!empty($this->paramInput->dateline_to))
		{
			$select->where("a.end_time <= ?",strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}
	
		if ($this->paramInput->status !== '')
		{
			$select->where('a.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
		}
		else
		{
			$select->where('a.status in (?)',array(0,1));
		}
		/* 分页 */
	
		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
	
		/* 列表 */
	
		$appointmentList = array();
		$appointmentListResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','member_id','tourism_type','start_time','end_time','destination','dateline','phone','status'),'a')
			->columns(array('member_name'),'m')
			->order('a.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$tourist_type = array(
				1 => '跟团游',
				2 => '自助游',
				3 => '自由行',
				4 => '自驾游',
				5 => '目的地服务',
		);
		$status = array(
				0 => '待确认',
				1 => '已确认',
		);
		
		if(!empty($appointmentListResult))
		{
			foreach($appointmentListResult as $result)
			{
				$result['start_time'] = date("Y-m-d",$result['start_time']);
				$result['end_time'] = date("Y-m-d",$result['end_time']);
				$result['dateline'] = date("Y-m-d",$result['dateline']);
				$result['tourism_type'] = $tourist_type[$result['tourism_type']];
				$result['status'] = $status[$result['status']];
				$appointmentList[] = $result;
			}
		}
			
		/* 设置参数 */
	
		$resultPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->setCellValue('A1','ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1','用户ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1','用户名');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1','预约类型');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1','目的地');
		$resultPHPExcel->getActiveSheet()->setCellValue('F1','手机');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1','开始时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1','结束时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1','预约时间');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1','状态');
		//加粗居中
		$resultPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray(
			array(
				'font' => array (
					'bold' => true
				),
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
				)
			)
		);
		
		// 数据
		$i = 2;
	
		foreach($appointmentList as $app)
		{
			$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i,$app['id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i,$app['member_id']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i,$app['member_name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i,$app['tourism_type']);
			$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i,$app['destination']);
			$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i,$app['phone']);
			$resultPHPExcel->getActiveSheet()->setCellValue('G' . $i,$app['start_time']);
			$resultPHPExcel->getActiveSheet()->setCellValue('H' . $i,$app['end_time']);
			$resultPHPExcel->getActiveSheet()->setCellValue('I' . $i,$app['dateline']);
			$resultPHPExcel->getActiveSheet()->setCellValue('J' . $i,$app['status']);
	
			$i ++;
		}
	
		//设置导出文件名
		$page = $corepage->currPage();
		$pageCount = $corepage->getPagecount();
	
		$date=date("Y_m_d H_i_s");
		$outputFileName = ($page == $pageCount) ? "product_appointment_{$date}__{$page}_last.xls" : "product_appointment_{$date}_{$page}.xls";
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
		$filename = "runtime/{$outputFileName}";
		if (is_file($filename)) {
			@unlink($filename);
		}
		$xlsWriter->save($filename);
	
		$this->view->downloadUrl = "/{$filename}";
	
		$nextPage = $page + 1;
		$this->view->nextUrl = ($page == $pageCount) ? '' : "/productcp/appointment/export?page={$nextPage}" . $query;
			
	}
}
?>