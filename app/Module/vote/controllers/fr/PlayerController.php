<?php

class Vote_PlayerController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['vote_player'] = new Model_VotePlayer();
		$this->models['vote'] = new Model_Vote();
		$this->models['member_profile'] = new Model_MemberProfile();
	}
		
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_forward('info');
		
	}
	
	/**
	 *  选手详细信息
	 */
	public function infoAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
		
		/* 选手详细信息*/
		
		$myInfo = $this->_db->select()
			->from(array('p' => 'vote_player'),array('id','name','image','declaration','join_time','vote_num','player_num'))
			->where('p.id = ?',$this->paramInput->id)
			->where('p.status = ?',1)
			->query()
			->fetch();
		$myInfo['image'] = thumbpath($myInfo['image'],620,'avatar');
		
		/* 排名*/
		
		$rank = $this->_db->select()
			->from(array('p' => 'vote_player'),array('vote_num'))
			->where('vote_num >= ?',$myInfo['vote_num'])
			->where('p.vote_id = ?',$this->paramInput->vote_id)
			->where('p.status = ?',1)
			->order('vote_num DESC')
			->query()
			->fetchAll();
		
		foreach ($rank as $key => $value)
		{
			if ($value['vote_num'] == $myInfo['vote_num'])
			{
				$myInfo['rank'] =$key+1;
				break;
			}
		}
	   
		/* 前一名差距*/
		
		if ($myInfo['rank'] == 1)
		{
			$myInfo['diff'] = 0;
		}
		else 
		{
			$myInfo['diff'] = $rank[$myInfo['rank']-2]['vote_num'] - $rank[$myInfo['rank']-1]['vote_num'];
		}
		
		/* 会员头像*/
		
		$memberAvatar = $this->_db->select()
			->from(array('m' => 'member_profile'),array('avatar'))
			->where('m.member_id = ?',$this->_user->id)
			->query()
			->fetchColumn();
			
		$memberAvatar = thumbpath($memberAvatar,60,'avatar');
		$this->view->memberAvatar = $memberAvatar;
		
		$voteInfo = $this->_db->select()
			->from(array('v' => 'vote'), array('id','view_count','image','allow_post','vote_name','intro','vote_btn'))
			->where('v.id = ?',$this->paramInput->vote_id)
			->query()
			->fetch();
		
		$this->view->myinfo = $myInfo;
		$this->view->voteinfo = $voteInfo;
		$this->view->wxTitle = '我是'.$myInfo['name'].',编号是['.$myInfo['player_num'].']，请为我见证！';
		$this->view->wxDescription = $voteInfo['intro'];
		$this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$this->view->wxImage = 'http://'.$_SERVER['HTTP_HOST'].thumbpath($myInfo['image'],60);
	}
	
	/**
	 *  选手状态
	 */
	public function statusAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
		
		/* 读取状态*/
		
		$status = $this->_db->select()
			->from(array('p' => 'vote_player'),array('status','id','name','declaration','nopass','image','phone'))
			->where('p.vote_id = ?',$this->paramInput->vote_id)
			->where('p.member_id = ?',$this->_user->id)
			->where('p.status in (?)',array(-1,0,1))
			->query()
			->fetch();
		
		if (empty($status))
		{
			$this->redirect('/vote/vote?vote_id='.$this->paramInput->vote_id);
		}
		$allowPost = $this->_db->select()
			->from(array('v' => 'vote'),array('allow_post'))
			->where('v.id = ?' , $this->paramInput->vote_id)
			->query()
			->fetchColumn();
		$status['thumbimage'] = thumbpath($status['image'],60);
		$voteInfo['id'] = $this->paramInput->vote_id;
		
		$this->view->allowpost = $allowPost;
		$this->view->status = $status;
		$this->view->voteinfo = $voteInfo;
	}
	
	/**
	 *  报名ajax
	 */
	public function addAction()
	{
		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
		
		if ($this->_request->isXmlHttpRequest())
		{
			$json = array();
			$this->_helper->viewRenderer->setNoRender();
			 
			/* 是否登录*/
			
			if ($this->_user->id == null)
			{
				$json['errno'] = 1;
				$json['errmsg'] = '请登录后报名';
				$this->_helper->json($json);
			}
			
			/* 检验传值 */
			
			if (!ajax($this))
			{
				$json['errno'] = 1;
				$json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			
			 /* 是否报过名*/
			
			$isAdd = $this->_db->select()
				->from(array('p' => 'vote_player'),array('id','status'))
				->where('p.vote_id = ?',$this->input->vote_id)
				->where('p.member_id = ?',$this->_user->id)
				->where('p.status in (?)',array(-1,0,1))
				->query()
				->fetch();
			
			/* 审核未通过重新报名*/
			
			if ($isAdd['status'] == -1)
			{
				$this->models['vote_player']->update(array(
					'status' => 0,
					'name' => $this->input->name,
					'phone' => $this->input->phone,
					'image' => $this->input->image,
					'declaration' => $this->input->declaration),
					array('vote_id = ?' => $this->input->vote_id,
					'member_id = ?' => $this->_user->id));				
				$json['errno'] = 0;
				$this->_helper->json($json);
			}
			
			if (!empty($isAdd))
			{
				$json['errno'] = 1;
				$json['errmsg'] = '不要重复报名';
				$this->_helper->json($json);
			}
			
			/* 报名时间以及是否允许报名验证*/
			
			$time = time();
			$voteTime = $this->_db->select()
				->from(array('v' => 'vote'),array('start_time','end_time','allow_post'))
				->where('v.id =?',$this->input->vote_id)
				->query()
				->fetch();
			
			if ($voteTime['start_time']<$time&&$voteTime['end_time']>$time&&$voteTime['allow_post']==1)
			{
				/* 报名，插入数据*/
				
				$this->rows['vote_player'] = $this->models['vote_player']->createRow(array(
					'vote_id' => $this->input->vote_id,
					'member_id' => $this->_user->id,
					'name' => $this->input->name,
					'phone' => $this->input->phone,
					'image' => $this->input->image,
					'declaration' => $this->input->declaration,
				));
				$this->rows['vote_player']->save();
				
				$json['errno'] = 0;
			}
			else 
			{
				$json['errno'] = 1;
				$json['errmsg'] = '不在报名时间内';
			}
			$this->_helper->json($json);
		}
		
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
					array(
							'href' => 'javascript:history.go(-1);',
							'text' => '返回')
			));
		}
		
		$voteInfo['id'] = $this->paramInput->vote_id;
		$this->view->voteinfo =$voteInfo;
		
		/* 是否报过名*/
		
		$isAdd = $this->_db->select()
			->from(array('p' => 'vote_player'),array('id'))
			->where('p.vote_id = ?',$voteInfo['id'])
			->where('p.member_id = ?',$this->_user->id)
			->where('p.status in (?)',array(-1,0,1))
			->query()
			->fetch();
		
		/* 是否跳转*/
		 
		if (!empty($isAdd))
		{
			$this->redirect('vote/player/status?vote_id='.$voteInfo['id']);
		}
	}
	
	/**
	 *  上传图片
	 */
	public function imageAction()
	{
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		$image = new Core2_Image('vote');
		
 		if (!$ret = $image->upload('upImg'))
 		{
 			$json['errno'] = 1;
			$json['errmsg'] = '图片格式错误或图片过大';
 			$this->_helper->json($json);
 		}

		$json['errno'] = 0;
		$json['img'] = $ret['url'];
		$this->_helper->json($json);
	}
	
}

?>