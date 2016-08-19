<?php

class Fundscp_FundsController extends Core2_Controller_Action_Cp 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init(); 
		$this->models['order'] = new Model_Order();
		$this->models['member'] = new Model_Member();
		$this->models['funds'] = new Model_Funds();
		 
 
	}
	 
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/fundscp/funds/list');
	}
	
	
	
	/**
	 *  首页
	 */
	public function batchAction() 
	{  
	 
		 
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				if(empty($this->data)){
							/* 提示 */
							$this->_helper->notice('请选择!',$this->error->firstMessage(),'error',array(
								array(
									'href' => '/fundscp/funds',
									'text' => '返回')
							));							
									 
				} 
				 
			 	 foreach($this->data as $key=>$val){
			 	 	
			 	 	foreach($val as $k=>$v){
			 	 		
						$this->rows['funds'] = $this->models['funds']->find($v)->current();
						
						$row=$this->rows['funds']->toArray();
						 
						if($row['auth']!=1){
							/* 提示 */
							$this->_helper->notice($row['member_id'].'审核未通过!',$this->error->firstMessage(),'error',array(
								array(
									'href' => '/fundscp/funds',
									'text' => '返回')
							));							
										
							
						}
						
						$this->rows['funds']->status = 1;
						
						$this->rows['funds']->save();	 
						
							
						
			 	 	}
			 	 }
	 							/* 提示 */
							$this->_helper->notice('操作成功',$this->error->firstMessage(),'error',array(
								array(
									'href' => '/fundscp/funds',
									'text' => '返回')
							));	
			 
			}
		}
		 
			
		$this->_helper->viewRenderer->setNoRender();	
	 
 
	}
	 
 	/**
	 *  审核
	 * */
 	public function checkAction() 
	{
		 
	 	$this->_helper->viewRenderer->setNoRender();
		
	 	if (!form($this)) 
		{
				/* 提示 */
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => '/fundscp/funds',
						'text' => '返回')
				));
 
		}  
		 
	 	if ($this->_request->isPost()) 
		{  
		 
			$this->rows['funds'] = $this->models['funds']->find($this->input->id)->current();
			
			if($this->rows['funds']->auth == -1)
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => '/fundscp/funds',
						'text' => '申请您已经驳回,无需重复操作！')
				));				
				
			}
			if($this->input->auth == 1)
			{
				$this->rows['funds']->status = $this->input->status;
			}
			else if ($this->input->auth == -1)
			{
				$this->rows['funds']->status = -1;
			}
			$this->rows['funds']->auth = $this->input->auth; 	 
			$this->rows['funds']->memo = $this->input->memo;
			$this->rows['funds']->save();			
				 
	  		$this->_helper->notice('操作成功','','success',array(
				array(
					'href' => '/fundscp/funds',
					'text' => '返回')
			));
  	 	}	
 
	}
	/**
	 *  详情
	 */
	public function editAction() 
	{
		 
	 	if (!params($this)) 
			{
				/* 提示 */
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => '/fundscp/funds',
						'text' => '返回')
				));
			}
			  
		/* 提现 */ 			
		$this->rows['funds'] = $this->models['funds']->find($this->paramInput->id)->current(); 
		
		$member=$this->rows['funds']->toArray();   
	    /* 用户 */	
		$userList= $this->_db->select()
				->from(array('m' => 'member'),array('account','group','bank','bankcard','balance','consumption','id'))
				//->joinLeft(array('a' => 'member_auth'),'m.id = a.member_id',array('name','idcard_no','mobile','img_1','img_2','memo','dateline'))  
				->where('m.id = ?',$member['member_id'])
				->query()
				->fetch();	 
			
	    /* 明细 */
	   $results = $this->_db->select()
			->from(array('f' => 'funds'))
			->where('f.member_id = ?',$member['member_id'])
			->query()
			->fetchAll();
		$info = array();
		if (!empty($results)) 
		{
			foreach ($results as $r) 
			{
				if ($r['type'] == 0) 
				{
					$r['params'] = Zend_Serializer::unserialize($r['params']);
					$r['detail'] = "{$r['params']['bank_name']}，{$r['params']['card_no']},{$r['params']['owner_name']}";
				}
				else 
				{
					$r['detail'] = '';
				}
				$info[] = $r;
			}
		}
			
		$this->view->info = $info;
 		$this->view->fundslist =$member;
		$this->view->userList = $userList;
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
					'href' => '/fundscp/funds',
					'text' => '返回')
			));
		}
		
		
		
		
		/* 构造 SQL 选择器 */
		// 提现
 	
 		$query = '/fundscp/funds/list?page={page}';
		$perpage = 15;
		$select = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('m' => 'member'),'m.id = f.member_id',array('account'))
			->where('f.type = ?',0)	;		
 
			
		if (!empty($this->paramInput->auth)) 
		{
			$select->where('f.auth = ?',$this->paramInput->auth);
			$query .= "&auth={$this->paramInput->auth}";
		} 
		
			
		if (empty($this->paramInput->status)) 
		{
			$select->where('f.status != ?',1);	
		} 		
		
			
			
		if (!empty($this->paramInput->price_from)) 
		{
			$select->where('f.money < ?','-'.$this->paramInput->price_from);
			$query .= "&price_from={$this->paramInput->price_from}";
		}
		
		if (!empty($this->paramInput->price_to)) 
		{
			$select->where('f.money > ?','-'.$this->paramInput->price_to);
			$query .= "&price_to={$this->paramInput->price_to}";
		}	 		
		
			 

		if (!empty($this->paramInput->status)) 
		{
			$select->where('f.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
		}	




		if (!empty($this->paramInput->member_id)) 
		{
			$select->where('f.member_id = ?',$this->paramInput->member_id);
			$query .= "&member_id={$this->paramInput->member_id}";
		}		
 
 
 
		if (!empty($this->paramInput->dateline_from)) 
		{
			$select->where('f.dateline >= ?',strtotime($this->paramInput->dateline_from));
			$query .= "&dateline={$this->paramInput->dateline_from}";
		}		
 
		if (!empty($this->paramInput->dateline_to)) 
		{
			$select->where('f.dateline <= ?',strtotime($this->paramInput->dateline_to));
			$query .= "&dateline={$this->paramInput->dateline_to}";
		}		
 						
		/* 分页 */

		$count = $select->query()
			   ->fetchColumn();
 
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
	 
		$results = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','f') 
			->limitPage($corepage->currPage(),$perpage)
			->order('f.dateline DESC')		
 			->query()
			->fetchAll();
		$fundsList = array();
		foreach ($results as $r) 
		{
			$r['params'] = Zend_Serializer::unserialize($r['params']);
			$r['detail'] = "{$r['params']['bank_name']}（{$r['params']['bod']}），{$r['params']['card_no']},{$r['params']['owner_name']}";
			$fundsList[] = $r;
		} 
 
		$this->view->fundsList = $fundsList;
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
 
		$perpage = 15;
		$select = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('COUNT(*)')));
	 

			
		$query = '';
		
		if (!empty($this->paramInput->status)) 
		{
			$select->where('f.status = ?',$this->paramInput->status);
			$query .= "status={$this->paramInput->status}";
		}
		 
		
		if (!empty($this->paramInput->auth)) 
		{
			$select->where('f.auth = ?',$this->paramInput->auth);
			$query .= "auth={$this->paramInput->auth}";
		}
			  
	 
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count);
		
		/* 获取订单数据 */
		
		$data = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','f')
			->joinLeft(array('a' => 'member_auth'),'f.member_id = a.member_id',array('name','mobile')) 
			->order('f.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
			 
			
		/* 设置参数 */
		
		// 设置
		$resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		
		$resultPHPExcel->getActiveSheet()->setCellValue('A1','姓名');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1','电话');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1','银行');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1','帐号');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1','金额');
//		$resultPHPExcel->getActiveSheet()->setCellValue('F1','到达地');
//		$resultPHPExcel->getActiveSheet()->setCellValue('G1','收货人手机');
//		$resultPHPExcel->getActiveSheet()->setCellValue('H1','重要提示');
//		$resultPHPExcel->getActiveSheet()->setCellValue('I1','订单号'); 
		
		
		// 数据
		$i = 2;
		foreach($data as $d)
		{

	        $data = explode(';',$d['params']);  
			 			
		 	$yonghu=explode('：',$data['0']); 
		
			$chang=explode('：',$data['1']); 
			
			$jin=explode('：',$data['2']); 
		 
  
			$resultPHPExcel->getActiveSheet()->getStyle("B{$i}")->getAlignment()->setWrapText(true);
			$resultPHPExcel->getActiveSheet()->getStyle("D{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$resultPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$resultPHPExcel->getActiveSheet()->getStyle("I{$i}")->getNumberFormat()   
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			
			$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i,$d['name']);
			$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i,$d['mobile']);
			$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i,$chang[1]);
			$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i,$jin[1]);
			$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i,abs($d['money']));
			//$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i,'77');
			//$resultPHPExcel->getActiveSheet()->setCellValue('G' . $i,'88');
			//$resultPHPExcel->getActiveSheet()->setCellValue('H' . $i,'99');

			$i ++;
		}
		
		//设置导出文件名 
		$page = $corepage->currPage();
		$pageCount = $corepage->getPagecount();
		$outputFileName = ($page == $pageCount) ? "zhifubao".time().".xls" : "zhifubaodingdan".time().".xls";
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
 

		$this->view->downloadUrl = "/{$filename}";
	 
		$nextPage = $page + 1;
		
		$this->view->nextUrl = ($page == $pageCount) ? '' : "/fundscp/funds/exportorder?page={$nextPage}" . $query;
	$this->_helper->viewRenderer->setNoRender();	
}
		
 
}

?>