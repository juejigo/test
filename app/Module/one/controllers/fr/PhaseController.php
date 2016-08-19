<?php

class One_PhaseController extends Core2_Controller_Action_Fr
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();

		$this->models['one_phase'] = new Model_OnePhase();
		if(!$this->_auth->hasIdentity())
		{
			$session = new Zend_Session_Namespace('wx_redirect_url');
			$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$session->url = $redirectUrl;
			$this->_redirect('/wx/user/auth');
		}
	}

	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_forward('list');
	}

	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
	}
	
	/**
	 * 	详情
	 */
	public function detailAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 期数详情*/
		
		$phaseInfo = array();
		$phase = $this->_db->select()
			->from(array('p' => 'one_phase'))
			->joinLeft(array('i' => 'product_image'),'p.product_id = i.product_id','image as p_image')
			->where('p.id = ?',$this->paramInput->id)
			->order('i.order ASC')
			->query()
			->fetchAll();
		
		/* 图片*/
		
		if (!empty($phase))
		{
			$image[] = $phase[0]['image'];
			foreach ($phase as $r)
			{
				$image[] = $r['p_image'];
			}
			$phaseInfo = $phase[0];
		}
		
		//非进行中
		if ($phaseInfo['status'] != 1)
		{	
			//倒计时
			if ($phaseInfo['status'] == 2)
			{
				$phaseInfo['clock'] = $phaseInfo['lottery_time'] - time();
			}
			elseif ($phaseInfo['status'] == 3)
			{
				/* 中奖信息*/
				
				$prizeInfo = $this->_db->select()
					->from(array('p' => 'one_phase'),array('lucky_num','lottery_time'))
					->joinLeft(array('f' => 'member_profile'), 'p.winner_id = f.member_id',array('member_name','avatar'))
					->joinLeft(array('m' => 'member'),'f.member_id = m.id',array('register_ip'))
					->joinLeft(array('l' => 'one_lucky_num'),'p.lucky_num = l.lucky_num',array('order_id'))
					->where('p.id = ?',$this->paramInput->id)
					->where('l.phase_id = ?',$this->paramInput->id)
					->query()
					->fetch();
				
				/* 本期参与次数*/
				
				$prizeInfo['num'] = $this->_db->select()
					->from(array('o' => 'one_order'),array(new Zend_Db_Expr('SUM(num)')))
					->where('o.phase_id = ?',$this->paramInput->id)
					->where('o.buyer_id = ?',$this->_user->id)
					->where('o.type = ?',2)
					->where('o.status > ?',0)
					->query()
					->fetchColumn();
				
				$this->view->prizeInfo = $prizeInfo;
			}
			
			/* 最新一期*/
			
			$lastPhase = $this->_db->select()
				->from(array('p' => 'one_phase'),array('id','no'))
				->where('p.product_id = ?',$phaseInfo['product_id'])
				->where('p.status = ?',1)
				->order('start_time DESC')
				->query()
				->fetch();
			if (!empty($lastPhase))
			{
				$this->view->lastPhase = $lastPhase;
			}
		}		
		$this->view->phaseInfo = $phaseInfo;
		$this->view->image = $image;
	}
	
	/**
	 *  计算详情
	 */
	public function calculteAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 中奖信息*/
		
		$prizeInfo = $this->_db->select()
			->from(array('p' => 'one_phase'))
			->where('p.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		$this->view->prizeInfo = $prizeInfo;
		
		/* 50个订单信息*/
		
		$orderList = $this->_db->select()
			->from(array('o' => 'one_order'),'pay_time')
			->joinLeft(array('f' => 'member_profile'), 'o.buyer_id = f.member_id','member_name')
			->where('o.pay_time < ?',$prizeInfo['over_time'])
			->where('o.status > ?',0)
			->where('o.type = ?',2)
			->order('o.pay_time DESC')
			->limit(50)
			->query()
			->fetchAll();
		$this->view->orderList = $orderList;
	}
	
	/**
	 *  往期揭晓
	 */
	public function winrecordAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
	}
	
	/**
	 *  参与记录
	 */
	public function joinrecordAction()
	{
		/* 检验传值 */
	
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
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
			/* 期数列表*/
		
			case 'list':
		
				/* 分页*/
		
				$perpage = 10;
				$phaseSelect = $this->_db->select()
					->from(array('p' => 'one_phase'),array(new Zend_Db_Expr('count(*)')))
					->where('p.status = ?',1);
		
				/* 判断是否正确页码*/
		
				$phaseCount = $phaseSelect->query()->fetchColumn();
				if ($this->input->page>ceil(($phaseCount/$perpage)))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有更多';
					$this->_helper->json($json);
				}
		
				/* 列表*/
		
				$phaseList = array();
				$phaseListResult = $phaseSelect->reset(Zend_Db_Select::COLUMNS)
					->columns('*','p')
					->order('dateline ASC')
					->limitPage($this->input->page,$perpage)
					->query()
					->fetchAll();
				if (!empty($phaseListResult))
				{
					foreach ($phaseListResult as $key => $r)
					{
						$phaseList[$key]['id'] = $r['id'];
						$phaseList[$key]['image'] = thumbpath($r['image'],620);
						$phaseList[$key]['name'] = $r['product_name'];
						$phaseList[$key]['need'] = $r['need_num'];
						$phaseList[$key]['schedule'] = $r['now_num']/$r['need_num']*100;
						$phaseList[$key]['surplus'] = $r['need_num'] - $r['now_num'];
						$phaseList[$key]['purchase'] = $r['limit_num'];
					}
				}
				$json['product_list'] = $phaseList;
				$json['errno'] = 0;
				$this->_helper->json($json);
				break;
			
			/* 往期列表*/
				
			case 'winrecord':
				
				/* 分页*/
			
				$perpage = 10;
				$phaseSelect = $this->_db->select()
					->from(array('p' => 'one_phase'),array(new Zend_Db_Expr('count(*)')))
					->where('p.product_id = ?',$this->input->id)
					->where('p.status = ?',3);
			
				/* 判断是否正确页码*/
			
				$phaseCount = $phaseSelect->query()->fetchColumn();
				if ($this->input->page>ceil(($phaseCount/$perpage)))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有更多';
					$this->_helper->json($json);
				}
			
				/* 列表*/
			
				$phaseList = array();
				$phaseListResult = $phaseSelect->reset(Zend_Db_Select::COLUMNS)
					->columns(array('lucky_num','lottery_time','no','id','winner_id'),'p')
					->joinLeft(array('f' => 'member_profile'), 'p.winner_id = f.member_id',array('member_name','avatar'))
					->joinLeft(array('m' => 'member'),'f.member_id = m.id',array('register_ip'))
					->joinLeft(array('l' => 'one_lucky_num'),'p.lucky_num = l.lucky_num and p.id = l.phase_id',array('order_id'))
					->order('no DESC')
					->limitPage($this->input->page,$perpage)
					->query()
					->fetchAll();
				
				if (!empty($phaseListResult))
				{
					foreach ($phaseListResult as $key => $r)
					{
						/* 本期参与人数*/
						
						$r['num'] = $this->_db->select()
							->from(array('o' => 'one_order'),array(new Zend_Db_Expr('SUM(num)')))
							->where('o.phase_id = ?',$r['id'])
							->where('o.buyer_id = ?',$r['winner_id'])
							->where('o.type = ?',2)
							->where('o.status > ?',0)
							->query()
							->fetchColumn();
						
						$phaseList[$key]['id'] = $r['id'];
						$phaseList[$key]['no'] = $r['no'];
						$phaseList[$key]['time'] = date("Y-m-d H:i:s",$r['lottery_time']);
						$phaseList[$key]['lucky'] = $r['lucky_num'];
						$phaseList[$key]['name'] = $r['member_name'];
						$phaseList[$key]['img'] = $r['avatar'];
						$phaseList[$key]['ip'] = $r['register_ip'];
						$phaseList[$key]['num'] = $r['num'];
					}
				}
				$json['list'] = $phaseList;
				$json['errno'] = 0;
				$this->_helper->json($json);
				break;
		
			/* 参与记录*/
			
			case 'joinrecord':
				
				/* 分页*/
					
				$perpage = 10;
				$recordSelect = $this->_db->select()
					->from(array('o' => 'one_order'),array(new Zend_Db_Expr('count(*)')))
					->where('o.phase_id = ?',$this->input->id)
					->where('o.type = ?',2)
					->where('o.status > ?',0);
					
				/* 判断是否正确页码*/
					
				$recordCount = $recordSelect->query()->fetchColumn();
				if ($this->input->page>ceil(($recordCount/$perpage)))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有更多';
					$this->_helper->json($json);
				}
					
				/* 列表*/
					
				$recordList = array();
				$recordListResult = $recordSelect->reset(Zend_Db_Select::COLUMNS)
					->columns(array('num','pay_time'),'o')
					->joinLeft(array('f' => 'member_profile'), 'o.buyer_id = f.member_id',array('member_name','avatar'))
					->joinLeft(array('m' => 'member'),'f.member_id = m.id',array('register_ip'))
					->order('pay_time DESC')
					->limitPage($this->input->page,$perpage)
					->query()
					->fetchAll();
			
				if (!empty($recordListResult))
				{
					foreach ($recordListResult as $key => $r)
					{
						$recordList[$key]['time'] = date("Y-m-d H:i:s",$r['pay_time']);
						$recordList[$key]['name'] = $r['member_name'];
						$recordList[$key]['img'] = $r['avatar'];
						$recordList[$key]['ip'] = $r['register_ip'];
						$recordList[$key]['num'] = $r['num'];
					}
				}
				$json['list'] = $recordList;
				$json['errno'] = 0;
				$this->_helper->json($json);
				break;
				
			default:
				break;
		}
	}

}
?>