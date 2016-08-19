<?php

class Vote_VoteController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['member_profile'] = new Model_MemberProfile();
		$this->models['vote'] = new Model_Vote();
		$this->models['vote_player'] = new Model_VotePlayer();
		$this->models['vote_record'] = new Model_VoteRecord();
		$this->models['vote_comment'] = new Model_VoteComment();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('活动不存在',$this->error->firstMessage(),'error_1',array(
				array(
						'href' => 'javascript:history.go(-1);',
						'text' => '返回')		
			));
		}

		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()
			->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
	
		/* 活动相关信息*/
		
		$voteInfo = $this->_db->select()
			->from(array('v' => 'vote'), array('id','view_count','image','allow_post','vote_name','intro','vote_btn','status'))
			->where('v.id = ?',$this->paramInput->vote_id)
			->query()
			->fetch();
		if ($voteInfo['status'] == 2)
		{
			$nowVote = $this->_db->select()
				->from(array('v' => 'vote','id'))
				->where('status = ?',1)
				->order('start_time DESC')
				->query()
				->fetchColumn();
			$url = "/vote/vote/expire?expire_id={$this->paramInput->vote_id}";
			if (!empty($nowVote))
			{
				$url .=	 "&vote_id={$nowVote}";
			}
			$this->redirect($url);
		}
		$voteInfo['image'] = thumbpath($voteInfo['image'],700);
	
		$voteInfo['player_num'] = 0;
		$voteInfo['vote_num'] = 0;
		$result = $this->_db->select()
			->from(array('p' => 'vote_player'),array('count' => new Zend_Db_Expr('COUNT(*)'),'sum' => new Zend_Db_Expr('SUM(vote_num)')))
			->where('p.vote_id = ?',$this->paramInput->vote_id)
			->where('p.status = ?',1)
			->query()
			->fetch();
		if (!empty($result['sum'])) 
		{
			$voteInfo['vote_num'] = $result['sum'];
		}
	    $voteInfo['player_num'] = $result['count'];
	    
		/* 更新浏览次数*/
		
		$this->models['vote']->update(array('view_count' => new Zend_Db_Expr('view_count + 1')),array('id = ?' => $this->paramInput->vote_id));
		
		$this->view->allowpost = $voteInfo['allow_post'];
		$this->view->search = $this->paramInput->search;
		$this->view->voteinfo = $voteInfo;
	
		/* 判断是否是选手 */
		
		$isPlayer = $this->_db->select()
			->from(array('p' => 'vote_player'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('p.vote_id = ?',$this->paramInput->vote_id)
			->where('p.member_id = ?',$this->_user->id)
			->where('p.status = ?',1)
			->query()
			->fetchColumn();
		
		if ($isPlayer) 
		{
			$playerInfo = $this->_db->select()
				->from(array('p' => 'vote_player'),array('id','name','image','declaration','join_time','vote_num','player_num'))
				->where('p.vote_id = ?',$this->paramInput->vote_id)
				->where('p.member_id = ?',$this->_user->id)
				->where('p.status = ?',1)
				->query()
				->fetch();
			$this->view->wxTitle = '我是'.$playerInfo['name'].',编号是['.$playerInfo['player_num'].']，请为我见证！';
			$this->view->wxDescription = $voteInfo['intro'];
			$this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$this->view->wxImage = 'http://'.$_SERVER['HTTP_HOST'].thumbpath($playerInfo['image'],60);
		}
		else 
		{
			$this->view->wxTitle = $voteInfo['vote_name'];
			$this->view->wxDescription = $voteInfo['intro'];
			$this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$this->view->wxImage = 'http://'.$_SERVER['HTTP_HOST'].thumbpath($voteInfo['image'],220);
		}
	}
	
	
	/**
	 *  规则
	 */
	public function ruleAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => '/javascript:history.go(-1);',
					'text' => '返回')
			));
		}
	
		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
	
		/* 规则信息*/
		
		$voteRule = $this->_db->select()
			->from(array('v' => 'vote'),array('allow_post','rule'))
			->where('v.id = ?',$this->paramInput->vote_id)
			->query()
			->fetch();
		$voteInfo['id'] = $this->paramInput->vote_id;
		
		$this->view->allowpost = $voteRule['allow_post'];
		$this->view->voterule = $voteRule;
		$this->view->voteinfo = $voteInfo;
	}
	
	
	/**
	 *  排名
	 */
	public function rankAction()
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
		
		/* 活动排名人数*/
		
		$rankNum = $this->_db->select()
			->from(array('v' => 'vote'),array('allow_post','rank_num'))
			->where('v.id = ?' , $this->paramInput->vote_id)
			->query()
			->fetch();

		/* 读取排名*/
		$rankList = array();
		$voteRank = $this->_db->select()
			->from(array('p' => 'vote_player'),array('image','name','vote_num','id'))
			->where('p.vote_id = ?',$this->paramInput->vote_id)
			->where('p.status = ?',1)
			->order('p.vote_num DESC')
			->limit($rankNum['rank_num'])
			->query()
			->fetchAll();
		if (!empty($voteRank))
		{
			foreach ($voteRank as $key => $rank)
			{
				$rankList[$key]['name'] = $rank['name'];
				$rankList[$key]['vote_num'] = $rank['vote_num'];
				$rankList[$key]['id'] = $rank['id'];
				$rankList[$key]['image'] = thumbpath($rank['image'],60,'avatar');
			}
		}

		$voteInfo['id'] = $this->paramInput->vote_id;
		
		$this->view->voterank = $rankList;
		$this->view->allowpost = $rankNum['allow_post'];
		$this->view->voteinfo = $voteInfo;
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
			/* 投票*/
			
			case 'vote':
				
				/* 是否登录*/
				
				if ($this->_user->id == null)
				{
					$json['errno'] = 1;
					$json['errmsg'] = '请登录';
					$this->_helper->json($json);
				}
				
				/* 活动规则*/
				
				$voteInfo = $this->_db->select()
					->from(array('v' => 'vote'),array('day_auth','vstart_time','vend_time','limit_sumnum','limit_daynum','limit_dayonenum','subscribe'))
					->where('v.id = ?',$this->input->vote_id)
					->query()
					->fetch();
			
				/* 是否需要关注*/
				
				if (!$voteInfo['subscribe'] == 0)
				{
					$isSubscribe=$this->_db->select()->from(array('m' => 'member'),array('subscribe'))
						->where('m.id = ?',$this->_user->id)
						->query()
						->fetchColumn();
					if ($isSubscribe == 0)
					{
						$json['errno'] = 2;
						$json['errmsg'] = '请关注公众号';
						$this->_helper->json($json);
					}
				}
			
				/* 24小时内是否可以投票*/
				
				if (!$voteInfo['day_auth'] == 0)
				{
					
					$playTime = $this->_db->select()
						->from(array('p' => 'vote_player'),array('join_time'))
						->where('id = ?',$this->input->player_id)
						->query()
						->fetchColumn();
					$time = time();
					if ($time>($playTime+86400))
					{
						$json['errno'] = 1;
						$json['errmsg'] = '本活动超过24小时不能投票';
						$this->_helper->json($json);
					}
				}
				
				/* 是否正确时间*/
				
				$time = time();
				if (($time>$voteInfo['vend_time'])||($time<$voteInfo['vstart_time']))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '不在投票时间内';
					$this->_helper->json($json);
				}
			
				/* 检验投票总量*/
				
				if (!$voteInfo['limit_sumnum'] == 0)
				{
					$recordSum = $this->_db->select()
						->from(array('r' => 'vote_record'),array(new Zend_Db_Expr('COUNT(*)')))
						->where('r.vote_id = ?', $this->input->vote_id)
						->where('r.member_id = ?', $this->_user->id)
						->query()
						->fetchColumn();
			
			
					if ($recordSum>=$voteInfo['limit_sumnum'])
					{
						$json['errno'] = 1;
						$json['errmsg'] = '超出总投票数';
						$this->_helper->json($json);
					}
					if ($voteInfo['limit_sumnum'] - $recordSum -1 == 0)
					{
						$json['num'] = '你的票数已经用完';
					}
					else
					{
						$json['num'] = '你总共还能投<span class="red">'.($voteInfo['limit_sumnum'] - $recordSum -1).'</span>票';
					}
				}
			
				/* 检验当天投票*/
				
				if (!$voteInfo['limit_daynum'] == 0)
				{
					$date = getdate();
					$startTime = mktime(0,0,0,$date['mon'],$date['mday'],$date['year']);
					$endTime = mktime(0,0,0,$date['mon'],$date['mday'],$date['year']) + 86400;
				
					$recordDay = $this->_db->select()
						->from(array('r' => 'vote_record'),array(new Zend_Db_Expr('COUNT(*)')))
						->where('r.vote_id = ?', $this->input->vote_id)
						->where('r.member_id = ?', $this->_user->id)
						->where('r.dateline >= ?',$startTime)
						->where('r.dateline < ?',$endTime)
						->query()
						->fetchColumn();
				
					if ($recordDay>=$voteInfo['limit_daynum'])
					{
						$json['errno'] = 1;
						$json['errmsg'] = '超出今天投票数';
						$this->_helper->json($json);
					}
					if ($voteInfo['limit_daynum'] - $recordDay -1 == 0)
					{
						$json['num'] = '你今天的票数已经用完';
					}
					else
					{
						$json['num'] = '你今天还能投<span class="red">'.($voteInfo['limit_daynum'] - $recordDay-1).'</span>票';
					}
				}
			
				/* 检验当天一对一投票*/
				
				if (!$voteInfo['limit_dayonenum'] == 0)
				{
					$date = getdate();
					$startTime = mktime(0,0,0,$date['mon'],$date['mday'],$date['year']);
					$endTime = mktime(0,0,0,$date['mon'],$date['mday'],$date['year']) + 86400;
				 
					$recordDayOne = $this->_db->select()
						->from(array('r' => 'vote_record'),array(new Zend_Db_Expr('COUNT(*)')))
						->where('r.vote_id = ?', $this->input->vote_id)
						->where('r.member_id = ?', $this->_user->id)
						->where('r.voteplayer_id = ?',$this->input->player_id)
						->where('r.dateline >= ?',$startTime)
						->where('r.dateline < ?',$endTime)
						->query()
						->fetchColumn();
				 
					if ($recordDayOne>=$voteInfo['limit_dayonenum'])
					{
						$json['errno'] = 1;
						$json['errmsg'] = '今天对他投票数超出限制,请投其他选项';
						$this->_helper->json($json);
					}
				 
				}
				
				/* 投票*/
				
				$this->rows['vote_record'] = $this->models['vote_record']->createRow(array(
					'vote_id' => $this->input->vote_id,
					'member_id' => $this->_user->id,
					'voteplayer_id' => $this->input->player_id,
				));
				$this->rows['vote_record']->save();
			
				/* 更新票数*/
				
				$this->models['vote_player']->update(array('vote_num' => new Zend_Db_Expr('vote_num + 1')),array('id = ?' => $this->input->player_id));
				
				$json['errno'] = 0;
				$json['vote_num'] = $this->_db->select()
					->from(array('p' => 'vote_player'),array('vote_num'))
					->where('p.id = ?',$this->input->player_id)
					->query()
					->fetchColumn();
			
				/* 返回票数*/
				
				if ($json['num'] == null)
				{
					$json['num'] == '你可以随便投';
				}
				$this->_helper->json($json);
				break;
				
			/* 选手列表*/
				
			case 'list':
				
				/* 分页*/
				
				$perpage = 6;
				$playerSelect = $this->_db->select()
					->from(array('p' => 'vote_player'),array(new Zend_Db_Expr('count(*)')))
					->where('p.vote_id = ?',$this->input->vote_id)
					->where('p.status = ?',1);
				
				/* 搜索*/
				
				if (!empty($this->input->search))
				{
					/* 数字检索编号*/
						
					if (is_numeric($this->input->search))
					{
						$playerId = $this->_db->select()
							->from(array('p' => 'vote_player'),array('id'))
							->where('p.player_num = ?',$this->input->search)
							->where('p.vote_id = ?',$this->input->vote_id)
							->where('p.status = ?',1)
							->query()
							->fetchColumn();
				
						if (!empty($playerId))
						{
							$json['errno'] = 2;
							$json['url'] = '/vote/player/info?id='.$playerId.'&vote_id='.$this->input->vote_id;
							$this->_helper->json($json);
						}
							
					}
				
					$playerId = $this->_db->select()
						->from(array('p' => 'vote_player'),array('id','name'))
						->where('p.name like ?','%'.$this->input->search.'%')
						->where('p.vote_id = ?',$this->input->vote_id)
						->where('p.status = ?',1)
						->query()
						->fetchAll();
				
					/* 当姓名全等且只有一个时跳转*/
						
					if ((count($playerId) == 1) && ($playerId[0]['name'] === $this->input->search) )
					{
						$json['errno'] = 2;
						$json['url'] = '/vote/player/info?id='.$playerId[0]['id'].'&vote_id='.$this->input->vote_id;
						$this->_helper->json($json);
					}
					else
					{
						$playerSelect->where('p.name like ?','%'.$this->input->search.'%');
					}
						
				}
				
				/* 判断是否正确页码*/
				
				$playerCount = $playerSelect->query()->fetchColumn();
				if ($this->input->page>ceil(($playerCount/$perpage)))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有更多';
					$this->_helper->json($json);
				}
				
				/* 列表*/
				
				$playerList = array();
				$playerListResult = $playerSelect->reset(Zend_Db_Select::COLUMNS)
					->columns(array('name','image','vote_num','player_num','id','declaration'),'p')
					->order('order ASC')
					->limitPage($this->input->page,$perpage)
					->query()
					->fetchAll();
				if (!empty($playerListResult))
				{
					foreach ($playerListResult as $key => $r)
					{
						$playerList[$key]['name'] = $r['name'];
						$playerList[$key]['image'] = thumbpath($r['image'],620);
						$playerList[$key]['vote_num'] = $r['vote_num'];
						$playerList[$key]['player_num'] = $r['player_num'];
						$playerList[$key]['id'] = $r['id'];
						$playerList[$key]['declaration'] = $r['declaration'];
					}
				}
				$json['data'] = $playerList;
				$json['vote_id'] = $this->input->vote_id;
				$json['errno'] = 0;
				$json['PageIndex'] = $this->input->page+1;
				$this->_helper->json($json);
				break;
				
			/* 评论*/
				
			case 'comment':
				
				/* 是否登录*/
				
				if ($this->_user->id == null)
				{
					$json['errno'] = 1;
					$json['errmsg'] = '请登录';
					$this->_helper->json($json);
				}
				
				/* 是否需要关注*/
				
				if (!$voteInfo['subscribe'] == 0)
				{
					$isSubscribe=$this->_db->select()->from(array('m' => 'member'),array('subscribe'))
						->where('m.id = ?',$this->_user->id)
						->query()
						->fetchColumn();
					if ($isSubscribe == 0)
					{
						$json['errno'] = 2;
						$json['errmsg'] = '请关注公众号';
						$this->_helper->json($json);
					}
				}
				
				/* 评论时间限制*/
				
				$time = time() - 300;
				$isTureTime = $this->_db->select()
					->from(array('c' => 'vote_comment'))
					->where('dateline >= ?',$time)
					->where('member_id = ?',$this->_user->id)
					->limit(1)
					->query()
					->fetch();
				if (!empty($isTureTime))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '请不要频繁评论';
					$this->_helper->json($json);
				}	
				/* 评论*/
				
				$this->rows['vote_comment'] = $this->models['vote_comment']->createRow(array(
						'vote_id' => $this->input->vote_id,
						'member_id' => $this->_user->id,
						'voteplayer_id' => $this->input->player_id,
						'comment' => $this->input->comment,
				));
				$this->rows['vote_comment']->save();
				
				$json['errno'] = 0 ;
				$this->_helper->json($json);
				break;
			
			/* 评论列表*/	
				
			case 'commentlist':
				
				/* 分页*/
				
				$perpage = 6;
				$commentSelect = $this->_db->select()
					->from(array('c' => 'vote_comment'),array(new Zend_Db_Expr('count(*)')))
					->where('c.vote_id = ?',$this->input->vote_id)
					->where('c.voteplayer_id = ?',$this->input->player_id)
					->where('c.status = ?',1);
				
				/* 判断是否正确页码*/
				
				$commentCount = $commentSelect->query()->fetchColumn();
				if ($this->input->page>ceil(($commentCount/$perpage)))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有评论了';
					$this->_helper->json($json);
				}
				
				$json['sum'] = $commentCount;
				if ($commentCount-($this->input->page * $perpage) < 0)
				{
					$json['surplus'] = 0;
					$json['none'] = 1;
					$json['errmsg'] = '没有评论了';
				}
				else
				{
					$json['surplus'] = $commentCount-($this->input->page * $perpage);
				}
				/* 列表*/
				
				$commentList = array();
				$commentListResult = $commentSelect->reset(Zend_Db_Select::COLUMNS)
					->columns(array('comment','dateline'),'c')
					->joinLeft(array('m' => 'member_profile'), 'c.member_id = m.member_id',array('avatar','member_name'))
					->order('c.dateline DESC')
					->limitPage($this->input->page,$perpage)
					->query()
					->fetchAll();
				if (!empty($commentListResult))
				{
					foreach ($commentListResult as $key => $r)
					{
						$commentList[$key]['name'] = $r['member_name'];
						$commentList[$key]['avatar'] = thumbpath($r['avatar'],60,'avatar');
						$commentList[$key]['comment'] = $r['comment'];
						$commentList[$key]['dateline'] = $r['dateline'];
					}
				}
				$json['data'] = $commentList;
				$json['vote_id'] = $this->input->vote_id;
				$json['errno'] = 0;
				$json['PageIndex'] = $this->input->page+1;
				$this->_helper->json($json);
				break;
				
			default:
				break;
		}
	}
	
	/**
	 *  往期回顾列表
	 */
	public function expirelistAction()
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
		
		/* 是否允许报名*/
		
		$allowPost = $this->_db->select()
			->from(array('v' => 'vote'),array('allow_post'))
			->where('v.id = ?' , $this->paramInput->vote_id)
			->query()
			->fetchColumn();
		$this->view->allowpost = $allowPost;
		
		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
		
		/* 过期活动列表*/
		
		$time = time();
		$endVoteList = array();
		$endVoteListResult = $this->_db->select()
			->from(array('v' => 'vote'),array('id','vote_name','start_time','vend_time','image'))
			->where('v.vend_time< ?',$time)
			->where('v.status = ?',2)
			->query()
			->fetchAll();
		if (!empty($endVoteListResult))
		{
			foreach ($endVoteListResult as $key => $vote )
			{
				$endVoteList[$key]['id'] = $vote['id'];
				$endVoteList[$key]['vote_name'] = $vote['vote_name'];
				$endVoteList[$key]['start_time'] = Date("Y-m-d",$vote['start_time']);
				$endVoteList[$key]['vend_time'] = Date("Y-m-d",$vote['vend_time']);
				$endVoteList[$key]['image'] = thumbpath($vote['image'],700);
			}
		}
		$voteInfo['id'] = $this->paramInput->vote_id;
		
		$this->view->endVoteList = $endVoteList;
		$this->view->voteinfo = $voteInfo;
	}
	
	/**
	 *  过期活动详细
	 */
	public function expireAction()
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
		
		/* 是否允许报名*/
		
		$allowPost = $this->_db->select()
			->from(array('v' => 'vote'),array('allow_post'))
			->where('v.id = ?' , $this->paramInput->vote_id)
			->query()
			->fetchColumn();
		$this->view->allowpost = $allowPost;
		
		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
		
		/* 过期活动详细*/
		
		$expireVoteInfo = $this->_db->select()
			->from(array('v' => 'vote'),array('vote_name','image','view_count','rule','awards','start_time','vend_time'))
			->where('v.id = ?' ,$this->paramInput->expire_id)
			->where('v.status = ?',2)
			->query()
			->fetch();
		if (!empty($expireVoteInfo))
		{
			$expireVoteInfo['image'] = thumbpath($expireVoteInfo['image'],700);
			$expireVoteInfo['start_time'] = Date("Y-m-d",$expireVoteInfo['start_time']);
			$expireVoteInfo['vend_time'] = Date("Y-m-d",$expireVoteInfo['vend_time']);
			$expireVoteInfo['player_num'] = 0;
			$expireVoteInfo['vote_num'] = 0;
		}
		
		//过期活动人数票数
		$result = $this->_db->select()
			->from(array('p' => 'vote_player'),array('count' => new Zend_Db_Expr('COUNT(*)'),'sum' => new Zend_Db_Expr('SUM(vote_num)')))
			->where('p.vote_id = ?',$this->paramInput->expire_id)
			->where('p.status = ?',1)
			->query()
			->fetch();
		if (!empty($result['sum']))
		{
			$expireVoteInfo['vote_num'] = $result['sum'];
		}
		$expireVoteInfo['player_num'] = $result['count'];
		
		$voteInfo['id'] = $this->paramInput->vote_id;
		
		$this->view->expireVoteInfo = $expireVoteInfo;
		$this->view->voteinfo = $voteInfo;
	}
	
	/**
	 *  下期预告
	 */
	public function nextAction()
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
		
		/* 是否允许报名*/
		
		$allowPost = $this->_db->select()
			->from(array('v' => 'vote'),array('allow_post'))
			->where('v.id = ?' , $this->paramInput->vote_id)
			->query()
			->fetchColumn();
		$this->view->allowpost = $allowPost;
		
		/* 是否关注微信*/
		
		$isSubscribe = $this->_db->select()->from(array('m' => 'member'),array('subscribe'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->issub = $isSubscribe;
		
		/* 下次活动详细*/
		
		$time = time();
		$nextVoteInfo = $this->_db->select()
			->from(array('v' => 'vote'))
			->where('start_time >?',$time)
			->where('status = ? ',0)
			->order('start_time ASC')   
			->limit(1)
			->query()
			->fetch();
		if (!empty($nextVoteInfo))
		{
			$nextVoteInfo['image'] = thumbpath($nextVoteInfo['image'],700);
			$nextVoteInfo['start_time'] = Date("Y-m-d",$nextVoteInfo['start_time']);
			$nextVoteInfo['end_time'] = Date("Y-m-d",$nextVoteInfo['end_time']);
			$nextVoteInfo['vstart_time'] = Date("Y-m-d",$nextVoteInfo['vstart_time']);
			$nextVoteInfo['vend_time'] = Date("Y-m-d",$nextVoteInfo['vend_time']);
			
			$this->view->nextVoteInfo = $nextVoteInfo;
		}
		$voteInfo['id'] = $this->paramInput->vote_id;
		$this->view->voteinfo = $voteInfo;
	}
}

?>