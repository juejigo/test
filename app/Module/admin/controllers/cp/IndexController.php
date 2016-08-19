<?php

class Admincp_IndexController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		echo '1';
		phpinfo();
		die();
	}
	
	/**
	 *  用户后台首页
	 */
	public function indexAction()
	{

		/* 当天营业额 */
		$date = getdate();
		$startTime = mktime(0,0,0,$date['mon'],$date['mday'],$date['year']);
		$endTime = mktime(0,0,0,$date['mon'],$date['mday'],$date['year']) + 86400;
		$beginMonthUnix=mktime(0,0,0,date('m'),1,date('Y'));
		$endMonthUnix=mktime(23,59,59,date('m'),date('t'),date('Y'));

		$cacheId = "admincp_index_summeryToday";

		if ($this->_cache->test($cacheId))
		{
			$summeryToday = $this->_cache->load($cacheId);
		}
		else
		{
			$summeryToday = $this->_db->select()
				->from(array('o' => 'order'),array('SUM(pay_amount)'))
				->where('o.dateline >= ?',$startTime)
				->where('o.dateline < ?',$endTime)
				->where('o.status > ?',0)
				->where('o.status < ?',10)
				->query()
				->fetchColumn();
			
			$this->_cache->save($summeryToday,$cacheId);
		}
		$this->view->summeryToday = $summeryToday;
		
		/* 本月销售额 */
		
		$cacheId = "admincp_index_summeryMonth";
		if ($this->_cache->test($cacheId))
		{
			$summeryMonth = $this->_cache->load($cacheId);
		}
		else
		{
			$summeryMonth = $this->_db->select()
			->from(array('o' => 'order'),array('SUM(pay_amount)'))
			->where('o.dateline >= ?',$beginMonthUnix)
			->where('o.dateline < ?',$endMonthUnix)
			->where('o.status > ?',0)
			->where('o.status < ?',10)
			->query()
			->fetchColumn();
			
			$this->_cache->save($summeryMonth,$cacheId);
		}
		$this->view->summeryMonth = $summeryMonth;
		
		/* 总交易额 */
		
		$cacheId = "admincp_index_summery";
		if ($this->_cache->test($cacheId))
		{
			$summery = $this->_cache->load($cacheId);
		}
		else
		{
			$summery = $this->_db->select()
			->from(array('o' => 'order'),array('SUM(pay_amount)'))
			->where('o.status > ?',0)
			->where('o.status < ?',10)
			->query()
			->fetchColumn();
			
			$this->_cache->save($summery,$cacheId);
		}
		$this->view->summery = $summery;

		/* 当日订单数 */
		
		$cacheId = "admincp_index_orderCountToday";
		if ($this->_cache->test($cacheId))
		{
			$orderCountToday = $this->_cache->load($cacheId);
		}
		else
		{
			$orderCountToday = $this->_db->select()
				->from(array('o' => 'order'),array('COUNT(*)'))
				->where('o.dateline >= ?',$startTime)
				->where('o.dateline < ?',$endTime)
				->where('o.status > ?',0)
				->where('o.status < ?',10)
				->query()
				->fetchColumn();
			$this->_cache->save($orderCountToday,$cacheId);
		}
		$this->view->orderCountToday = $orderCountToday;
		
		/* 本月订单数 */
		
		$cacheId = "admincp_index_orderCountMonth";
		if ($this->_cache->test($cacheId))
		{
			$orderCountMonth = $this->_cache->load($cacheId);
		}
		else
		{
			$orderCountMonth = $this->_db->select()
			->from(array('o' => 'order'),array('COUNT(*)'))
			->where('o.dateline >= ?',$beginMonthUnix)
			->where('o.dateline <= ?',$endMonthUnix)
			->where('o.status > ?',0)
			->where('o.status < ?',10)
			->query()
			->fetchColumn();
			$this->_cache->save($orderCountMonth,$cacheId);
		}
		$this->view->orderCountMonth = $orderCountMonth;
		
		/* 总订单数 */
		
		$cacheId = "admincp_index_orderCountAll";
		if ($this->_cache->test($cacheId))
		{
			$orderCountAll = $this->_cache->load($cacheId);
		}
		else
		{
			$orderCountAll = $this->_db->select()
			->from(array('o' => 'order'),array('COUNT(*)'))
			->where('o.status > ?',0)
			->where('o.status < ?',10)
			->query()
			->fetchColumn();
			$this->_cache->save($orderCountAll,$cacheId);
		}
		$this->view->orderCountAll = $orderCountAll;
		
		/* 本日注册会员数 */
		
		$cacheId = "admincp_index_memberCountToday";
		if ($this->_cache->test($cacheId))
		{
			$memberCountToday = $this->_cache->load($cacheId);
		}
		else
		{
			$memberCountToday = $this->_db->select()
			->from(array('m' => 'member'),array('COUNT(*)'))
			->where('m.register_time >= ?',$startTime)
			->where('m.register_time < ?',$endTime)
			->where('m.status = ?',1)
			->query()
			->fetchColumn();
			$this->_cache->save($memberCountToday,$cacheId);
		}
		$this->view->memberCountToday = $memberCountToday;
		
		/* 本月注册会员数 */
		
		$cacheId = "admincp_index_memberCountMonth";
		if ($this->_cache->test($cacheId))
		{
			$memberCountMonth = $this->_cache->load($cacheId);
		}
		else
		{
			$memberCountMonth = $this->_db->select()
			->from(array('m' => 'member'),array('COUNT(*)'))
			->where('m.register_time >= ?',$beginMonthUnix)
			->where('m.register_time < ?',$endMonthUnix)
			->where('m.status = ?',1)
			->query()
			->fetchColumn();
			$this->_cache->save($memberCountMonth,$cacheId);
		}
		$this->view->memberCountMonth = $memberCountMonth;
		
		/* 总注册会员数 */
		
		$cacheId = "admincp_index_memberCountAll";
		if ($this->_cache->test($cacheId))
		{
			$memberCountAll = $this->_cache->load($cacheId);
		}
		else
		{
			$memberCountAll = $this->_db->select()
			->from(array('m' => 'member'),array('COUNT(*)'))
			->where('m.status = ?',1)
			->query()
			->fetchColumn();
			$this->_cache->save($memberCountAll,$cacheId);
		}
		$this->view->memberCountAll = $memberCountAll;
		
		/* 概述  产品TOP10 */
		
		$cacheId = "admincp_index_productTop";
		if ($this->_cache->test($cacheId))
		{
			$productTop = $this->_cache->load($cacheId);
		}
		else
		{
			$productTop =$this->_db->select()
			->from(array('p' => 'product'),array('id','product_name','price','sells'))
			->where('p.parent_id = ?',0)
			->where('p.status = ?',2)
			->order('p.up_time DESC')
			->limit(10)
			->query()
			->fetchAll();
			$this->_cache->save($productTop,$cacheId);
		}
		$this->view->productTop = $productTop;
		
		/* 概述  用户TOP10 */
		
		$cacheId = "admincp_index_memberTop";
		if ($this->_cache->test($cacheId))
		{
			$memberTop = $this->_cache->load($cacheId);
		}
		else
		{
			$memberTop =$this->_db->select()
			->from(array('m' => 'member'),array('id','account','register_time'))
			->where('m.status = ?',1)
			->order('m.register_time DESC')
			->limit(10)
			->query()
			->fetchAll();
	
			foreach($memberTop as $k => $v)
			{
				$memberTop[$k]['register_time'] = date("Y-m-d H:i:s",$v['register_time']);
			}
			$this->_cache->save($memberTop,$cacheId);
		}
		$this->view->memberTop = $memberTop;
		
		/* 概述  待确认订单TOP10 */
		
		$cacheId = "admincp_index_waitConfirmOrderTop";
		if ($this->_cache->test($cacheId))
		{
			$waitConfirmOrderTop = $this->_cache->load($cacheId);
		}
		else
		{
			$waitConfirmOrderTop =$this->_db->select()
			->from(array('o' => 'order'),array('id','buyer_name','dateline','pay_amount','status'))
			->where('o.status = ?',1)
			->order('o.dateline DESC')
			->limit(10)
			->query()
			->fetchAll();
			
			foreach($waitConfirmOrderTop as $k => $v)
			{
				$waitConfirmOrderTop[$k]['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
			}
			$this->_cache->save($waitConfirmOrderTop,$cacheId);
		}
		$this->view->waitConfirmOrderTop = $waitConfirmOrderTop;
		
		/* 概述  待退货订单TOP10 */
		
		$cacheId = "admincp_index_waitReturnProductOrderTop";
		if ($this->_cache->test($cacheId))
		{
			$waitReturnProductOrderTop = $this->_cache->load($cacheId);
		}
		else
		{
			$waitReturnProductOrderTop =$this->_db->select()
			->from(array('o' => 'order'),array('id','buyer_name','dateline','pay_amount','status'))
			->where('o.status = ?',11)
			->order('o.dateline DESC')
			->limit(10)
			->query()
			->fetchAll();
			
			foreach($waitReturnProductOrderTop as $k => $v)
			{
				$waitReturnProductOrderTop[$k]['dateline'] = date("Y-m-d H:i:s",$v['dateline']);
			}
			$this->_cache->save($waitReturnProductOrderTop,$cacheId);
		}
		$this->view->waitReturnProductOrderTop = $waitReturnProductOrderTop;
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

		switch ($op) {
			case 'order':
				
				$date = $this->input->data;
				
				if($date == 'yesterday')
				{
					$dateline = date("Y-m-d",strtotime("-1 day"));
					$cacheName = strtotime("$dateline");
					$cacheId = "admincp_index_chartOrder_{$cacheName}";
					if ($this->_cache->test($cacheId))
					{
						$result = $this->_cache->load($cacheId);
					}
					else
					{
						$orderCount = $this->_db->select()
						->from(array('o' => 'order'),array('dateline'))
						->where('o.dateline >= ?',strtotime("$dateline 00:00:00"))
						->where('o.dateline <= ?',strtotime("$dateline 23:59:59"))
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchAll();
						
						foreach ($orderCount as $v)
						{
							$timeKey = date('H',$v['dateline']);
							$list[$timeKey][]=$v;
						}
						foreach ($list as $k => $v)
						{
							$list[$k] = count($v) ;
						}
						ksort($list);
	
						$i=0;
						$j=0;
						foreach($list as $k=>$v)
						{
							$c[$i][$j]="$k 点";
							$c[$i][$j+1]=$v;
						
							$i++;
						}
		
						$err=0;
						if($c =='')
						{
							$err = 1;
						}
						$result = array(
								'datas' => array(
										array('name'=>"订单量",'data' => $c		
										)
								),
								'errno' => $err
						);
						$this->_cache->save($result,$cacheId);
					}
					
					/* 昨日交易总额  */
					$cacheId = "admincp_index_summeryYesterday";
					
					if ($this->_cache->test($cacheId))
					{
						$summeryYesterday = $this->_cache->load($cacheId);
						$result['summeryDay'] = $summeryYesterday;
					}
					else
					{
						$summeryYesterday = $this->_db->select()
						->from(array('o' => 'order'),array('SUM(pay_amount)'))
						->where('o.dateline >= ?',strtotime("$dateline 00:00:00"))
						->where('o.dateline <= ?',strtotime("$dateline 23:59:59"))
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchColumn();
						
						$result['summeryDay'] = $summeryYesterday;
						$this->_cache->save($summeryYesterday,$cacheId);
					}
					
					/* 昨日订单数  */
					$cacheId = "admincp_index_orderCountYesterday";
						
					if ($this->_cache->test($cacheId))
					{
						$orderCountYesterday = $this->_cache->load($cacheId);
						$result['orderCount'] = $orderCountYesterday;
					}
					else
					{
						$orderCountYesterday = $this->_db->select()
						->from(array('o' => 'order'),array('COUNT(*)'))
						->where('o.dateline >= ?',strtotime("$dateline 00:00:00"))
						->where('o.dateline <= ?',strtotime("$dateline 23:59:59"))
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchColumn();
					
						$result['orderCount'] = $orderCountYesterday;
						$this->_cache->save($orderCountYesterday,$cacheId);
					}
			
				}
				
				if($date == 'today')
				{
					$dateline = date('Y-m-d');
					$cacheName = strtotime("$dateline");
					$cacheId = "admincp_index_chartOrder_{$cacheName}";
					if ($this->_cache->test($cacheId))
					{
						$result = $this->_cache->load($cacheId);
					}
					else
					{
						$orderCount = $this->_db->select()
						->from(array('o' => 'order'),array('dateline'))
						->where('o.dateline >= ?',strtotime("$dateline 00:00:00"))
						->where('o.dateline <= ?',strtotime("$dateline 23:59:59"))
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchAll();
							
						foreach ($orderCount as $v)
						{
							$timeKey = date('H',$v['dateline']);
							$list[$timeKey][]=$v;
						}
						foreach ($list as $k => $v)
						{
							$list[$k] = count($v) ;
						}
						ksort($list);
					
						$i=0;
						$j=0;
						foreach($list as $k=>$v)
						{
							$c[$i][$j]="$k 点";
							$c[$i][$j+1]=$v;
								
							$i++;
						}
					
						$err=0;
						if($c =='')
						{
							$err = 1;
						}
							
						$result = array(
								'datas' => array(
										array('name'=>"订单量",'data' => $c
										)
								),
								'errno' => $err
						);
					}
					$this->_cache->save($result,$cacheId);
					
					/* 今日交易总额  */
					$cacheId = "admincp_index_summeryToday";
					
					if ($this->_cache->test($cacheId))
					{
						$summeryToday = $this->_cache->load($cacheId);
						$result['summeryDay'] = $summeryToday;
					}
					else
					{
						$summeryToday = $this->_db->select()
						->from(array('o' => 'order'),array('SUM(pay_amount)'))
						->where('o.dateline >= ?',strtotime("$dateline 00:00:00"))
						->where('o.dateline <= ?',strtotime("$dateline 23:59:59"))
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchColumn();
						
						$result['summeryDay'] = $summeryToday;
						$this->_cache->save($summeryToday,$cacheId);
					}
					
					/* 今日订单数  */
					$cacheId = "admincp_index_orderCountToday";
						
					if ($this->_cache->test($cacheId))
					{
						$orderCountToday = $this->_cache->load($cacheId);
						$result['orderCount'] = $orderCountToday;
					}
					else
					{
						$orderCountToday = $this->_db->select()
						->from(array('o' => 'order'),array('COUNT(*)'))
						->where('o.dateline >= ?',strtotime("$dateline 00:00:00"))
						->where('o.dateline <= ?',strtotime("$dateline 23:59:59"))
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchColumn();
					
						$result['orderCount'] = $orderCountToday;
						$this->_cache->save($orderCountToday,$cacheId);
					}	
				}
				
				if($date == 'month')
				{
					$beginThismonthUnix=mktime(0,0,0,date('m'),1,date('Y'));
					$endThismonthUnix=mktime(23,59,59,date('m'),date('t'),date('Y'));
					
					$cacheId = "admincp_index_chartOrderMonth_{$beginThismonthUnix}";
					if ($this->_cache->test($cacheId))
					{
						$result = $this->_cache->load($cacheId);
					}
					else
					{
						$orderCount = $this->_db->select()
						->from(array('o' => 'order'),array('dateline'))
						->where('o.dateline >= ?',$beginThismonthUnix)
						->where('o.dateline <= ?',$endThismonthUnix)
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchAll();
					
						foreach ($orderCount as $v)
						{
							$timeKey = date('d',$v['dateline']);
							$list[$timeKey][]=$v;
						}
						foreach ($list as $k => $v)
						{
							$list[$k] = count($v) ;
						}
						ksort($list);
					
						$i=0;
						$j=0;
						foreach($list as $k=>$v)
						{
							$c[$i][$j]="$k 号";
							$c[$i][$j+1]=$v;
								
							$i++;
						}
					
						$err=0;
						if($c =='')
						{
							$err = 1;
						}
					
						$result = array(
								'datas' => array(
										array('name'=>"订单量",'data' => $c
										)
								),
								'errno' => $err
						);
					}
					$this->_cache->save($result,$cacheId);
					
					/* 月交易总额  */
					$cacheId = "admincp_index_summeryMonth";
						
					if ($this->_cache->test($cacheId))
					{
						$summeryMonth = $this->_cache->load($cacheId);
						$result['summeryMonth'] = $summeryMonth;
					}
					else
					{
						$summeryMonth = $this->_db->select()
						->from(array('o' => 'order'),array('SUM(pay_amount)'))
						->where('o.dateline >= ?',$beginThismonthUnix)
						->where('o.dateline <= ?',$endThismonthUnix)
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchColumn();
					
						$result['summeryMonth'] = $summeryMonth;
						$this->_cache->save($summeryMonth,$cacheId);
					}
						
					/* 月订单数  */
					$cacheId = "admincp_index_orderCountMonth";
					
					if ($this->_cache->test($cacheId))
					{
						$orderCountMonth = $this->_cache->load($cacheId);
						$result['orderCountMonth'] = $orderCountMonth;
					}
					else
					{
						$orderCountMonth = $this->_db->select()
						->from(array('o' => 'order'),array('COUNT(*)'))
						->where('o.dateline >= ?',$beginThismonthUnix)
						->where('o.dateline <= ?',$endThismonthUnix)
						->where('o.status > ?',0)
						->where('o.status < ?',10)
						->query()
						->fetchColumn();
							
						$result['orderCountMonth'] = $orderCountMonth;
						$this->_cache->save($orderCountMonth,$cacheId);
					}
				}
				$this->_helper->json($result);
				
				break;
				
			case 'member':
			
				$date = $this->input->data;
			
				if($date == 'yesterday')
				{
					$dateline = date("Y-m-d",strtotime("-1 day"));
					$cacheName = strtotime("$dateline");
					$cacheId = "admincp_index_chartMember_{$cacheName}";
					if ($this->_cache->test($cacheId))
					{
						$result = $this->_cache->load($cacheId);
					}
					else
					{
						$memberCount = $this->_db->select()
						->from(array('m' => 'member'),array('register_time'))
						->where('m.register_time >= ?',strtotime("$dateline 00:00:00"))
						->where('m.register_time <= ?',strtotime("$dateline 23:59:59"))
						->where('m.status = ?',1)
						->query()
						->fetchAll();
							
						foreach ($memberCount as $v)
						{
							$timeKey = date('H',$v['register_time']);
							$list[$timeKey][]=$v;
						}
						foreach ($list as $k => $v)
						{
							$list[$k] = count($v) ;
						}
						ksort($list);
				
						$i=0;
						$j=0;
						foreach($list as $k=>$v)
						{
							$c[$i][$j]="$k 点";
							$c[$i][$j+1]=$v;
								
							$i++;
						}
				
						$err=0;
						if($c =='')
						{
							$err = 1;
						}
							
						$result = array(
								'datas' => array(
										array('name'=>"每小时注册量",'data' => $c
										)
								),
								'errno' => $err
						);
					}
					$this->_cache->save($result,$cacheId);

					/* 昨日注册会员数 */
					
					$cacheId = "admincp_index_memberCountYesterDay";
					if ($this->_cache->test($cacheId))
					{
						$memberCountYesterDay = $this->_cache->load($cacheId);
						$result['memberCount'] = $memberCountYesterDay;
					}
					else
					{
						$memberCountYesterDay = $this->_db->select()
						->from(array('m' => 'member'),array('COUNT(*)'))
						->where('m.register_time >= ?',strtotime("$dateline 00:00:00"))
						->where('m.register_time <= ?',strtotime("$dateline 23:59:59"))
						->where('m.status = ?',1)
						->query()
						->fetchColumn();
						
						$result['memberCount'] = $memberCountYesterDay;
						$this->_cache->save($memberCountYesterDay,$cacheId);
					}
				}
			
				if($date == 'today')
				{
					$dateline = date('Y-m-d');
					
					$cacheName = strtotime("$dateline");
					$cacheId = "admincp_index_chartMember_{$cacheName}";
					if ($this->_cache->test($cacheId))
					{
						$result = $this->_cache->load($cacheId);
					}
					else
					{
						$memberCount = $this->_db->select()
						->from(array('m' => 'member'),array('register_time'))
						->where('m.register_time >= ?',strtotime("$dateline 00:00:00"))
						->where('m.register_time <= ?',strtotime("$dateline 23:59:59"))
						->where('m.status = ?',1)
						->query()
						->fetchAll();
							
						foreach ($memberCount as $v)
						{
							$timeKey = date('H',$v['register_time']);
							$list[$timeKey][]=$v;
						}
						foreach ($list as $k => $v)
						{
							$list[$k] = count($v) ;
						}
						ksort($list);
				
						$i=0;
						$j=0;
						foreach($list as $k=>$v)
						{
							$c[$i][$j]="$k 点";
							$c[$i][$j+1]=$v;
								
							$i++;
						}
				
						$err=0;
						if($c =='')
						{
							$err = 1;
						}
				
						$result = array(
								'datas' => array(
										array('name'=>"每小时注册量",'data' => $c
										)
								),
								'errno' => $err
						);
					}
					$this->_cache->save($result,$cacheId);
					
					/* 今日注册会员数 */
						
					$cacheId = "admincp_index_memberCountToday";
					if ($this->_cache->test($cacheId))
					{
						$memberCountToday = $this->_cache->load($cacheId);
						$result['memberCount'] = $memberCountToday;
					}
					else
					{
						$memberCountToday = $this->_db->select()
						->from(array('m' => 'member'),array('COUNT(*)'))
						->where('m.register_time >= ?',strtotime("$dateline 00:00:00"))
						->where('m.register_time <= ?',strtotime("$dateline 23:59:59"))
						->where('m.status = ?',1)
						->query()
						->fetchColumn();
						
						$result['memberCount'] = $memberCountToday;
						$this->_cache->save($memberCountToday,$cacheId);
					}
					
				}

				if($date == 'month')
				{
					$beginThismonthUnix=mktime(0,0,0,date('m'),1,date('Y'));
					$endThismonthUnix=mktime(23,59,59,date('m'),date('t'),date('Y'));

					$cacheId = "admincp_index_chartMemberMonth_{$beginThismonthUnix}";
					if ($this->_cache->test($cacheId))
					{
						$result = $this->_cache->load($cacheId);
					}
					else
					{
						$registerCount = $this->_db->select()
						->from(array('m' => 'member'),array('register_time'))
						->where('m.register_time >= ?',$beginThismonthUnix)
						->where('m.register_time <= ?',$endThismonthUnix)
						->where('m.status = ?',1)
						->query()
						->fetchAll();
						
						foreach ($registerCount as $v)
						{
							$timeKey = date('d',$v['register_time']);
							$list[$timeKey][]=$v;
						}
						foreach ($list as $k => $v)
						{
							$list[$k] = count($v) ;
						}
						ksort($list);
				
						$i=0;
						$j=0;
						foreach($list as $k=>$v)
						{
							$c[$i][$j]="$k 号";
							$c[$i][$j+1]=$v;
								
							$i++;
						}
				
						$err=0;
						if($c =='')
						{
							$err = 1;
						}
				
						$result = array(
								'datas' => array(
										array('name'=>"每天注册量",'data' => $c
										)
								),
								'errno' => $err
						);
					}
					$this->_cache->save($result,$cacheId);
					
					/* 本月注册会员数 */
					
					$cacheId = "admincp_index_memberCountMonth";
					if ($this->_cache->test($cacheId))
					{
						$memberCountMonth = $this->_cache->load($cacheId);
						$result['memberCountMonth'] = $memberCountMonth;
					}
					else
					{
						$memberCountMonth = $this->_db->select()
						->from(array('m' => 'member'),array('COUNT(*)'))
						->where('m.register_time >= ?',$beginThismonthUnix)
						->where('m.register_time <= ?',$endThismonthUnix)
						->where('m.status = ?',1)
						->query()
						->fetchColumn();
						
						$result['memberCountMonth'] = $memberCountMonth;
						$this->_cache->save($memberCountMonth,$cacheId);
					}			
				}
				$this->_helper->json($result);		
				break;

				case 'menu':
				//$cacheId = "Cp_Menu_allMenu";
				/* if ($this->_cache->test($cacheId))
				{
					$menu1 = $this->_cache->load($cacheId);
				}
				else
				{ */
					require_once 'includes/adminMenu.php';
	
					foreach($adminMenu as $k => $v)
					{
						foreach($v as $k1 => $v1)
						{
							$icon=$v1['icon'];
							unset($v1['icon']);
							foreach($v1 as $k2 => $v2)
							{
								$menu1[$k]['nav'][$k1]['url']='/'.$v2['module'].'/'.$v2['controller'].'/'.'index';
								$menu1[$k]['nav'][$k1]['name']=$k1;
								$menu1[$k]['nav'][$k1]['icon']=$icon;
								break;
							}
						}
					}
					
					foreach($menu1 as $k => $v)
					{
						$menu1[$k]['title']=$k;
						$menu1[$k]['nav']=array_values($menu1[$k]['nav']);	
					}
					$menu1 = array_values($menu1);
					/* $this->_cache->save($menu1,$cacheId);
				} */
				$this->_helper->json($menu1);
				break;	
			default:
				break;
		}
		$this->_helper->json($json);
	}
	/**
	 *  帐号概况
	 */
	public function overviewAction()
	{
	}

		
}

?>