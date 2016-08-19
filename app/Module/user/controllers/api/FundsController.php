<?php

class Userapi_FundsController extends Core2_Controller_Action_Api    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  我的收益
	 */
	public function incomeAction()
	{
		/* 直接推荐人数 */
		/*$select = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('referee_id = ?',$this->_user->id);
		$this->json['referee_count'] = $select->query()->fetchColumn();*/

		/* 间接推荐人数 */
		/*$select = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('indirect_id = ?',$this->_user->id);
		$this->json['indirect_count'] = $select->query()->fetchColumn();*/

		$this->json['referee_count'] = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('referee_id = ?',$this->_user->id)
			->orWhere('indirect_id = ?',$this->_user->id)
			->query()
			->fetchColumn();
			

		//echo $select->__toString();exit;
		
		/* 会员分红 */
		$shareIncome = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('SUM(money)')))
			->where('f.type = ?',1)
			->where('f.member_id = ?',$this->_user->id)
			->where('f.status = ?',1)
			->query()
			->fetchColumn();
		$this->json['share_income'] = empty($shareIncome) ? 0 :$shareIncome;
		
		/* 会员奖励收益 */
		$rewardIncome = 0;
		$rewardIncome = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('SUM(money)')))
			->where('f.type = ?',2)
			->where('f.member_id = ?',$this->_user->id)
			->where('f.status = ?',1)
			->query()
			->fetchColumn();
		$this->json['reward_income'] = empty($rewardIncome) ? 0 : $rewardIncome;
		
		$this->json['total_income'] = $shareIncome + $rewardIncome;
		$this->json['total_income'] = number_format($this->json['total_income'],2,'.','');
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  我的代理
	 */
	public function agentincomeAction() 
	{
		$totalIncome = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('SUM(money)')))
			->where('f.type = ?',3)
			->where('f.member_id = ?',$this->_user->id)
			->where('f.status = ?',1)
			->query()
			->fetchColumn();
		$this->json['total_icome'] = empty($totalIncome) ? 0 : $totalIncome;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  推荐人名单
	 */
	public function refereeAction() 
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取推荐人名单 */
		
		$this->json['referee_list'] = array();
		
		$select = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('m.referee_id = ?',$this->_user->id);
		
		// 总数
		$count = $select->query()
			->fetchColumn();
		
		// 数据
		$results = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','m')
			->order('m.register_time DESC')
			->limitPage($this->input->page,$this->input->perpage)
			->query()
			->fetchAll();
		
		$refereeList = array();
		foreach ($results as $result) 
		{
			$referee = array();
			$referee['id'] = $result['id'];
			$referee['account'] = $result['account'];
			$referee['dateline'] = $result['register_time'];
			$refereeList[] = $referee;
		}
		$this->json['referee_list'] = $refereeList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  资金明细
	 */
	public function detailAction() 
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取资金明细 */
		
		$this->json['funds_list'] = array();
		
		$select = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('f.member_id = ?',$this->_user->id)
			->where('f.status = ?',1);
		
		if ($this->input->type !== '') 
		{
			$select->where('f.type IN (?)',$this->input->type);
		}
		else 
		{
			$select->where('f.type IN (?)',array(0,1,2,3,4));
		}
		
		if (!empty($this->input->from)) 
		{
			$select->where('f.dateline >= ?',strtotime($this->input->from));
		}
		
		if (!empty($this->input->to)) 
		{
			$select->where('f.dateline <= ?',strtotime($this->input->to));
		}
		
		// 总数
		$count = $select->query()
			->fetchColumn();
		
		// 数据
		$rs = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','f')
			->order('f.dateline DESC')
			->limitPage($this->input->page,$this->input->perpage)
			->query()
			->fetchAll();
		
		$fundsList = array();
		foreach ($rs as $r) 
		{
			$funds = array();
			$funds['type'] = $r['type'];
			$funds['money'] = $r['money'];
			$funds['desc'] = $r['desc'];
			$funds['dateline'] = $r['dateline'];
			$fundsList[] = $funds;
		}
		$this->json['funds_list'] = $fundsList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}

	/**
	 *  提现资金明细
	 */
	public function withdrawdetailAction() 
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取资金明细 */
		
		$this->json['funds_list'] = array();
		
		$select = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('f.member_id = ?',$this->_user->id);
		
		$select->where('f.type = ?',0);

		// 总数
		$count = $select->query()
			->fetchColumn();
		
		// 数据
		$rs = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','f')
			->order('f.dateline DESC')
			->limitPage($this->input->page,$this->input->perpage)
			->query()
			->fetchAll();
		//dump($this->input->perpage);exit;
		//echo $select->__toString();exit;
		$fundsList = array();
		foreach ($rs as $r) 
		{
			$funds = array();
			$funds['type'] = $r['type'];
			$funds['money'] = $r['money'];
			preg_match("/帐号\:(.*)?/is",$r['params'],$arr);
		    $name = $arr[1];
			//$account = hideAccount($name);
			$account = mb_substr($name,0,3,'utf8').'*****'.mb_substr($name,-3,10,'utf8');
			$funds['account'] = $account;
			$funds['status'] = $r['status'];
			$funds['auth'] = $r['auth'];
			$funds['dateline'] = $r['dateline'];
			$fundsList[] = $funds;
		}
		$this->json['funds_list'] = $fundsList;
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}

}

?>