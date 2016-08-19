<?php

class User_FundsController extends Core2_Controller_Action_Fr    
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
		/* 推荐人数 */
		$refereeCount = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('referee_id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->refereeCount = empty($refereeCount) ? 0 :$refereeCount;
		
		/* 提成 */
		$shareIncome = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('SUM(money)')))
			->where('f.type = ?',1)
			->where('f.member_id = ?',$this->_user->id)
			->where('f.status = ?',1)
			->query()
			->fetchColumn();
		$this->view->shareIncome = empty($shareIncome) ? 0 : number_format($shareIncome,2,'.','');
		
		/* 未确认 */
		$unconfirmIncome = 0;
		$unconfirmIncome = $this->_db->select()
			->from(array('f' => 'funds'),array(new Zend_Db_Expr('SUM(money)')))
			->where('f.type IN (?)',array(1,2))
			->where('f.member_id = ?',$this->_user->id)
			->where('f.status = ?',0)
			->query()
			->fetchColumn();
		$this->view->unconfirmIncome = empty($unconfirmIncome) ? 0 : number_format($unconfirmIncome,2,'.','');
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
			->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('avatar','member_name','alias','sex'))
			->where('m.referee_id = ?',$this->_user->id)
			->orWhere('m.indirect_id = ?',$this->_user->id);
		
		// 总数
		$count = $select->query()
			->fetchColumn();
		
		// 数据
		$results = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','m')
			->columns('member_name','p')
			->order('m.register_time DESC')
			->limitPage($this->input->page,$this->input->perpage)
			->query()
			->fetchAll();

		$refereeList = array();
		foreach ($results as $result) 
		{
			$referee = array();
			$referee['id'] = $result['id'];
			$referee['account'] = $result['account'] ? $result['account'] : $result['member_name'];
			$referee['referee_count'] = $result['referee_count'];
			$referee['dateline'] = date('Y-m-d H:i:s',$result['register_time']);
			$refereeList[] = $referee;
		}

		$this->view->refereeList = $refereeList;
		$this->view->count = $count;
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
			->where('f.member_id = ?',$this->_user->id);
		
		if ($this->input->type !== '') 
		{
			$select->where('f.type IN (?)',$this->input->type);
		}
		else 
		{
			$select->where('f.type IN (?)',array(0,1,2,3,4));
		}
		
		if ($this->input->status !== '') 
		{
			$select->where('f.status = ?',$this->input->status);
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
		$this->view->fundsList = $fundsList;
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

		$fundsList = array();
		foreach ($rs as $r) 
		{
			$funds = array();
			$funds['type'] = $r['type'];
			$funds['money'] = $r['money'];
			preg_match("/帐号\:(.*)?/is",$r['params'],$arr);
		    $name = $arr[1];
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