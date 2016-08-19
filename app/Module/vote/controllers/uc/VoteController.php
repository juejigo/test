<?php

class Voteuc_VoteController extends Core2_Controller_Action_Uc  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['vote'] = new Model_Vote();
		$this->models['vote_player'] = new Model_VotePlayer();
		$this->models['vote_record'] = new Model_VoteRecord();
		$this->models['member_profile'] = new Model_MemberProfile();
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
		
		/* 参赛信息*/		
		
		$votePlayer = $this->_db->select()
			->from(array('p' => 'vote_player'),array('id','name','vote_num','player_num','image'))
			->where('p.member_id = ?',$this->_user->id)
			->where('p.vote_id = ?',$this->paramInput->vote_id)
			->where('p.status = ?',1)
			->query()
			->fetch();
		
		if (!empty($votePlayer))
		{
			//选手信息
			$votePlayer['image'] = thumbpath($votePlayer['image'],60,'avatar');
			$this->view->voteplayer = $votePlayer;
			
		}
		else
		{
			//会员信息
			$memberInfo = $this->_db->select()
				->from(array('m' => 'member_profile'),array('member_name','avatar'))
				->where('m.member_id = ?',$this->_user->id)
				->query()
				->fetch();
			
			$memberInfo['avatar'] = thumbpath($memberInfo['avatar'],60,'avatar');
			$this->view->memberinfo = $memberInfo;
		}
		
		/* 投票数*/
		$voteSum = $this->_db->select()
			->from(array('r' => 'vote_record'),array(new Zend_Db_Expr('count(*)')))
			->where('r.vote_id = ?',$this->paramInput->vote_id)
			->where('r.member_id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$voteInfo['id'] = $this->paramInput->vote_id;
		
		$this->view->votesum=$voteSum;
		$this->view->voteinfo =$voteInfo;
			   
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
	
		/* 检验传值 */
		
		if (!ajax($this))
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		$perpage = 5;
		
		switch ($op)
		{
			/* 投票给我*/
			
			case 'voteme' :
				
				/* 是否参赛*/
				
				$votePlayer = $this->_db->select()
					->from(array('p' => 'vote_player'),array('id'))
					->where('p.member_id = ?',$this->_user->id)
					->where('p.vote_id = ?',$this->input->vote_id)
					->query()
					->fetch();
				
				if (!empty($votePlayer))
				{
					/* 构造 SQL 选择器 */
					
					$memberSelect = $this->_db->select()
						->from(array('r' => 'vote_record'),array(new Zend_Db_Expr('count(distinct(member_id))')))
						->where('r.vote_id = ?',$this->input->vote_id)
						->where('r.voteplayer_id = ?',$votePlayer[id]);
					$memberCount = $memberSelect->query()->fetchColumn();
					
					/* 分页 */
					
					if ($this->input->page>ceil(($memberCount/$perpage)))
					{
						$json['errno'] = '1';
						$json['errmsg'] = '没有更多';
						$this->_helper->json($json);
					}
					
					$member = $memberSelect->reset(Zend_Db_Select::COLUMNS)
						->columns('distinct(member_id)','r')
						->order('dateline DESC')
						->limitPage($this->input->page,$perpage)
						->query()
						->fetchAll();
					$memberList = array();
					foreach ($member as $mem)
					{
						$memberList[] = $mem["member_id"];
					}
						
					/* 会员信息与记录*/
						
					$record = $this->_db->select()
						->from(array('r' => 'vote_record'),array('member_id','dateline'))
						->joinLeft(array('m' => 'member_profile'), 'r.member_id = m.member_id',array('member_name','member_id','avatar'))
						->where('r.vote_id = ?',$this->input->vote_id)
						->where('r.voteplayer_id = ?',$votePlayer['id'])
						->where('r.member_id in(?)',$memberList)
						->order('dateline DESC')
						->query()
						->fetchAll();
							
					$recordList = array();	
					foreach ($record as $key => $value)
					{
						$recordList[$value['member_id']]['img'] = thumbpath($value['avatar'],60,'avatar');
						$recordList[$value['member_id']]['name'] = $value['member_name'];
						$recordList[$value['member_id']]['num']++;
						$recordList[$value['member_id']]['dateline'][] = Date("Y-m-d H:i:s",$value['dateline']);
					}
					
					$this->view->vote_id =   $this->input->vote_id;
					$this->view->recordlist = $recordList;
					
					$json['html'] = $this->view->render('voteuc/vote/voteme.tpl');
					$json['PageIndex'] = $this->input->page +1;
					$json['errno'] = 0;
					$this->_helper->json($json);
					
				}
				else
				{
						$json['errno'] = '1';
						$json['errmsg'] = '没有报名';
						$this->_helper->json($json);
				}
				break ;
				
			/* 我投给谁*/
			
			case 'myvote' : 
				 
				/* 构造 SQL 选择器 */
				
				$myVoteSelect = $this->_db->select()
					->from(array('r' => 'vote_record'),array(new Zend_Db_Expr('count(distinct(voteplayer_id))')))
					->where('r.member_id = ?',$this->_user->id)
					->where('r.vote_id = ?',$this->input->vote_id);
				$myVoteCount = $myVoteSelect->query()->fetchColumn();
				
				/* 分页*/
				
				if ($this->input->page>ceil(($myVoteCount/$perpage)))
				{
					$json['errno'] = '1';
					$json['errmsg'] = '没有更多';
					$this->_helper->json($json);
				}
				
				$myVotePlayer = $myVoteSelect->reset(Zend_Db_Select::COLUMNS)
					->columns('distinct(voteplayer_id)','r')
					->order('dateline DESC')
					->limitPage($this->input->page,$perpage)
					->query()
					->fetchAll();
				 
				if (!empty($myVotePlayer))
				{
					$votePlayerList = array();
					foreach ($myVotePlayer as $value)
					{
						$votePlayerList[] = $value["voteplayer_id"];
					}
						
					/* 选手信息和记录*/
						
					$record = $this->_db->select()
						->from(array('r' => 'vote_record'),array('voteplayer_id','dateline'))
						->joinLeft(array('p' => 'vote_player'), 'p.id = r.voteplayer_id',array('id','name','image','vote_num','player_num'))
						->where('r.vote_id = ?',$this->input->vote_id)
						->where('r.voteplayer_id in (?)',$votePlayerList)
						->where('r.member_id = ?',$this->_user->id)
						->order('dateline DESC')
						->query()
						->fetchAll();
	
					$votedList = array(); 	
					foreach ($record as $key => $value)
					{
						$votedList[$value['voteplayer_id']]['img'] = thumbpath($value['image'],60,'avatar');
						$votedList[$value['voteplayer_id']]['name'] = $value['name'];
						$votedList[$value['voteplayer_id']]['vote_num'] = $value['vote_num'];
						$votedList[$value['voteplayer_id']]['player_num'] = $value['player_num'];
						$votedList[$value['voteplayer_id']]['num']++;
						$votedList[$value['voteplayer_id']]['dateline'][] = Date("Y-m-d H:i:s",$value['dateline']);
					}
						
					$this->view->vote_id =   $this->input->vote_id;
					$this->view->votedlist = $votedList;
					
					$json['html'] = $this->view->render('voteuc/vote/myvote.tpl');
					$json['PageIndex'] = $this->input->page +1;
					$json['errno'] = 0;
					$this->_helper->json($json);
					
				}   
				break;
	
			default :
				break ;
		}
		$this->_helper->json($json);
	}
	
}

?>