<?php

class User_WalletController extends Core2_Controller_Action_Fr    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['member_auth'] = new Model_MemberAuth();
		$this->models['bankcard'] = new Model_Bankcard();
		$this->models['funds'] = new Model_Funds();
	}
	
	/**
	 *  我的钱包
	 */
	public function indexAction() 
	{
		/* 余额 */
		
		$balance = $this->_db->select()
			->from(array('m' => 'member'),array('balance'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->balance = $balance;
	}

	/**
	 *  我的红包
	 */
	public function mycouponAction()
	{

		$select = $this->_db->select();
		$coupons = $select
			->from(array('u' => 'coupon_user'),array('deadline','status'))
			->joinLeft(array('c' => 'coupon'),'c.id = u.coupon_id',array('coupon_name','memo','condition','value'))
			->where('u.member_id = ?',$this->_user->id)
			->query()
			->fetchAll();

		foreach($coupons as $key=>$val){
			$coupons[$key]['end_time'] = date('Y-m-d H:i:s',$val['deadline']);
			$coupons[$key]['value'] = ceil($val['value']);
		}

		$this->view->coupons = $coupons;
	}	

	/**
	 *  转帐
	 */
	public function transferAction() 
	{

		if ($this->_request->isGet()) 
		{
			$profile = $this->_db->select()
				->from(array('p' => 'member_profile'),array('avatar','member_name','alias','sex'))
				->where('p.member_id = ?',$this->_user->id)
				->query()
				->fetch();

			switch ($this->_user->group) {
			case 0:
				$lv_name = '美义士';
				break;
			case 1:
				$lv_name = '美香主';
				break;
			case 2:
				$lv_name = '美堂主';
				break;
			case 3:
				$lv_name = '美舵主';
				break;
			case 4:
				$lv_name = '美盟主';
				break;
			}
			$profile['lv_name'] = $lv_name;

			$this->view->ages = $ages;
			$this->view->profile = $profile;
		}
		if ($this->_request->isPost()) 
		{

			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			
			/* 扣除转出 */
			
			$this->_db->update('member',array('balance' => new Zend_Db_Expr("balance - {$this->input->amount}")),array('id = ?' => $this->_user->id));
			$this->models['funds']->createRow(array(
				'member_id' => $this->_user->id,
				'related_account' => $this->input->account,
				'type' => 4,
				'desc' => '转出',
				'money' => "-{$this->input->amount}",
				'status' => 1
			))->save();
			
			/* 增加转入 */
			
			$id = $this->_db->select()
				->from(array('m' => 'member'))
				->where('m.account = ?',$this->input->account)
				->query()
				->fetchColumn();
			$this->_db->update('member',array('balance' => new Zend_Db_Expr("balance + {$this->input->amount}")),array('id = ?' => $id));
			$this->models['funds']->createRow(array(
				'member_id' => $id,
				'related_account' => $this->_user->account,
				'type' => 4,
				'desc' => '转入',
				'money' => $this->input->amount,
				'status' => 1
			))->save();
			
		  $this->json['errno'] = '0';
		  $this->json['notice'] = '转账成功';
		  $this->json['gourl'] = DOMAIN.'user/funds/detail';
		  $this->_helper->json($this->json);
		}
	}

	/**
	 *  提现信息
	 */
	public function withdrawAction()
	{

	    if($this->_user->real_auth==0){
		    $this->json['errno'] = '1';
		    $this->json['errmsg'] = '请先补充资料';
		    $this->_helper->json($this->json);	    
	    }
		if ($this->_request->isPost()) 
		{

			  /* 银行列表 */

			  $select = $this->_db->select();
			  $banklist = $select
					->from(array('b' => 'bankcard'),array('id','name','card_no','status','bank_type'))
					->where('b.member_id = ?',$this->_user->id)
					->where('b.status = ?',2)
					->order(array('default desc','id desc'))
					->query()
					->fetchAll();
			  if(!$banklist){
				  $this->json['errno'] = '1';
				  $this->json['errmsg'] = '无帐号信息';
				  $this->_helper->json($this->json);	    
			  }
			  $this->json['errno'] = '0';
			  $this->json['balance'] =  $this->_user->balance;
			  $this->json['banklist'] =  $banklist;
			  $this->_helper->json($this->json);
		}else{
		  $this->json['errno'] = '1';
		  $this->json['errmsg'] = '出错';
		  $this->_helper->json($this->json);		
		}

	}

	/**
	 *  提现信息添加
	 */
	public function withdrawinsertAction()
	{
		$result = 0;
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 银行信息 */
				$select = $this->_db->select();
				$bank = $select
					->from(array('b' => 'bankcard'),array('id','card_no','status','bank_type'))
					->joinLeft(array('a' => 'member_auth'),'a.member_id = b.member_id',array('a.name','a.status'))
					->where('b.member_id = ?',$this->_user->id)
					->where('b.id = ?',$this->input->bank_id)
					->query()
					->fetch();
				switch ($bank['bank_type']) {
					case 0:
						$bankName = '支付宝';
						break;
					case 1:
						$bankName = '微信';
						break;
					case 2:
						$bankName = '银行';
						break;
				}
				$desc = '提现';
				$params = "用户：".$bank['name'].";银行：$bankName;帐号：".$bank['card_no'];

				/* 扣除转出 */

				$result = $this->_db->update('member',array('balance' => new Zend_Db_Expr("balance - {$this->input->amount}")),array('id = ?' => $this->_user->id));
				$id = $this->models['funds']->createRow(array(
					'member_id' => $this->_user->id,
					'related_account' => $this->_user->account,
					'type' => 0,
					'desc' => $desc,
					'params' => $params,
					'money' => "-{$this->input->amount}",
					'status' => 0
				))->save();
			}else{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);			
			}
		}
		if($result){
		  $this->json['errno'] = '0';
		  $this->json['errmsg'] = '添加成功，等待审核';		
		}else{
		  $this->json['errno'] = '1';
		  $this->json['errmsg'] = '添加失败';			
		}
	    $this->_helper->json($this->json);
	}

	/**
	 *  提现认证
	 */
	public function memberauthAction()
	{

		if ($this->_request->isPost() && form($this)) 
		{

			/* 判断是否认证 */

			$select = $this->_db->select()
				->from(array('a' => 'member_auth'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('a.member_id = ?',$this->_user->id);
			$count = $select->query()->fetchColumn();
			if($count>0){
			  /* 修改认证信息 */

			    $this->rows['member_auth'] = $this->models['member_auth']->fetchRow(
					$this->models['member_auth']->select()
						->where('member_id = ?',$this->_user->id)
				);
				$this->rows['member_auth']->name = $this->input->name;
				$this->rows['member_auth']->idcard_no = $this->input->idcard_no;
				$this->rows['member_auth']->mobile = $this->input->mobile;
				$this->rows['member_auth']->img_1 = $this->input->img_1;
				$this->rows['member_auth']->img_2 = $this->input->img_2;
				$this->rows['member_auth']->status = 1;
			    $result = $this->rows['member_auth']->save();

	
			  /* 修改银行卡信息 */
				$this->rows['bankcard'] = $this->models['bankcard']->fetchRow(
					$this->models['bankcard']->select()
						->where('member_id = ?',$this->_user->id)
						->where('`default` = ?',1)
				);
				$this->rows['bankcard']->name = $this->input->name;
				$this->rows['bankcard']->card_no = $this->input->card_no;
				$this->rows['bankcard']->status = 1;
				$result2 = $this->rows['bankcard']->save();
				$this->json['errno'] = '0';
				$this->_helper->json($this->json);		  
			}else{

				/* 添加认证信息 */
				$this->rows['member_auth'] = $this->models['member_auth']->createRow(array(
					'member_id' => $this->_user->id,
					'name' => $this->input->name,
					'idcard_no' => $this->input->idcard_no,
					'mobile' => $this->input->mobile,
					'img_1' => $this->input->img_1,
					'img_2' => $this->input->img_2,
					'dateline' => SCRIPT_TIME,
					'status' => 1
				));
				$id = $this->rows['member_auth']->save();
				if($id){
					/* 添加银行卡信息 */
					$this->rows['bankcard'] = $this->models['bankcard']->createRow(array(
						'member_id' => $this->_user->id,
						'name' => $this->input->name,
						'bank_type' => 0,
						'card_no' => $this->input->card_no,
						'default' => 1,
						'dateline' => SCRIPT_TIME,
						'status' => 1
					));
					$id2 = $this->rows['bankcard']->save();
				}
				if($id && $id2){
				  $this->json['errno'] = '0';
				  $this->_helper->json($this->json);	
				}else{
				  $this->json['errno'] = '1';
				  $this->json['errmsg'] = '添加失败';
				  $this->_helper->json($this->json);			
				}			
			
			}

		}else{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
	    $this->json['errno'] = '1';
	    $this->json['errmsg'] = '参数错误';
	    $this->_helper->json($this->json);
	}

	/**
	 *  认证查询
	 */
	public function memberauthsearchAction()
	{

		$select = $this->_db->select()
			->from(array('a' => 'member_auth'))
			->joinLeft(array('b' => 'bankcard'),'b.member_id = a.member_id',array('card_no'))
			->where('a.member_id = ?',$this->_user->id);
		$memberAuth = $select->query()->fetch();
		if($memberAuth)
		{
		  $this->json['errno'] = '0';
		  $this->json['member_auth'] = $memberAuth;
		}else{
	      $this->json['errno'] = '2';
		  $this->json['member_auth'] = '';
	      $this->json['errmsg'] = '未提交认证';		
		}

		$this->_helper->json($this->json);
	}

	/**
	 *  认证检查
	 */
	public function checkauthAction()
	{
		$select = $this->_db->select()
			->from(array('a' => 'member_auth'))
			->where('a.member_id = ?',$this->_user->id);
		$auth = $select->query()->fetch();
		if($auth){
		  $this->json['errno'] = '0';
		  $this->json['status'] = $auth['status'];
		  $this->json['errmsg'] = '你已提交过资料';			  
		}else{
		  $this->json['errno'] = '1';
		  $this->json['errmsg'] = '还未认证';			
		}
		$this->_helper->json($this->json);
	}

	/**
	 *  添加支付宝帐号
	 */
	public function accountinsertAction()
	{
		if ($this->_request->isPost() && form($this))
		{

			if($this->input->card_no!=$this->input->cfm_card_no)
			{
			  $this->json['errno'] = '1';
			  $this->json['errmsg'] = '2次输入帐号不同';
			  $this->_helper->json($this->json);		
			}

			/* 判断是否已提交过 */

			$select = $this->_db->select()
				->from(array('b' => 'bankcard'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('b.member_id = ?',$this->_user->id)
				->where('b.card_no = ?',$this->input->card_no);
			$count = $select->query()->fetchColumn();

			if($count>0){
			  $this->json['errno'] = '1';
			  $this->json['errmsg'] = '此帐号您已提交过';
			  $this->_helper->json($this->json);			  
			}

			if($count>2){
			  $this->json['errno'] = '1';
			  $this->json['errmsg'] = '帐号不能超过3个';
			  $this->_helper->json($this->json);			  
			}

			/* 添加银行卡信息 */

			$this->rows['bankcard'] = $this->models['bankcard']->createRow(array(
				'member_id' => $this->_user->id,
				'name' => $this->input->name,
				'bank_type' => 0,
				'card_no' => $this->input->card_no,
				'default' => 0,
				'status' => 0
			));
			$id = $this->rows['bankcard']->save();
			if($id){
			  $this->json['errno'] = '0';
			  $this->json['errmsg'] = 'OK';
			  $this->_helper->json($this->json);
			}else{
			  $this->json['errno'] = '1';
			  $this->json['errmsg'] = '出错';
			  $this->_helper->json($this->json);
			}		

		}
		$this->json['errno'] = '1';
		$this->json['errmsg'] = '出错';
		$this->_helper->json($this->json);
	}

	/**
	 *  身份证上传
	 */
	public function imgupAction()
	{
		$image = new Core2_Image('idcard');
		if (!$ret = $image->upload('image'))
		{
			$this->json['errno'] = 1;
			$this->json['errmsg'] = '图片格式错误或图片过大';
			$this->_helper->json($this->json);
		}
		$this->json['errno'] = 0;
		$this->json['url'] = $ret['url'];
		$this->_helper->json($this->json);
	}

}

?>