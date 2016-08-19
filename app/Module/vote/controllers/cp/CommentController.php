<?php

class Votecp_CommentController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	
		$this->models['vote'] = new Model_Vote();
		$this->models['vote_player'] = new Model_VotePlayer();
		$this->models['vote_comment'] = new Model_VoteComment();
		$this->models['member_profile'] = new Model_MemberProfile();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/votecp/comment/list');
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
		
		$perpage = 10;
		$select = $this->_db->select()
			->from(array('c' => 'vote_comment'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('p' => 'vote_player'), 'c.voteplayer_id = p.id')
			->joinLeft(array('m' => 'member_profile'), 'c.member_id = m.member_id')
			->where('c.vote_id = ?',$this->paramInput->vote_id);
		
		$query = '/votecp/comment/list?page={page}&vote_id='.$this->paramInput->vote_id;
		
		if (!empty($this->paramInput->member_name))
		{
			$select->where('m.member_name like ?','%'.$this->paramInput->member_name.'%');
			$query .= "&member_name={$this->paramInput->member_name}";
		}
		
		if (!empty($this->paramInput->player_name))
		{
			$select->where('p.name like ?','%'.$this->paramInput->player_name.'%');
			$query .= "&player_name={$this->paramInput->player_name}";
		}
		
		if (!empty($this->paramInput->phone))
		{
			$select->where('p.phone = ?',$this->paramInput->phone);
			$query .= "&phone={$this->paramInput->phone}";
		}
		
		if (!empty($this->paramInput->dateline_from))
		{
			$select->where('c.dateline >= ?',strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}
		 
		if (!empty($this->paramInput->dateline_to))
		{
			$select->where('c.dateline <= ?',strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}
		
		if ($this->paramInput->status !== '')
		{    
			$select->where('c.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
			$this->view->status = $this->paramInput->status;
		}else
		{
			$select->where('c.status in (?)',array(0,1));
		}
		
		/* 分页 */

		$count = $select->query()->fetchColumn();   
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$commentList = array();
		$commentListResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','comment','dateline','status'),'c')
			->columns(array('member_name'),'m')
			->columns(array('name','phone'),'p')
			->order('c.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		if (!empty($commentListResult))
		{
			foreach ($commentListResult as $key => $value)
			{
				$commentList[$key]['id'] = $value['id'];
				$commentList[$key]['comment'] = $value['comment'];
				$commentList[$key]['status'] = $value['status'];
				$commentList[$key]['member_name'] = $value['member_name'];
				$commentList[$key]['name'] = $value['name'];
				$commentList[$key]['phone'] = $value['phone'];
				$commentList[$key]['dateline'] = Date("Y-m-d H:i:s",$value['dateline']);
			}
		}
		$this->view->commentList = $commentList;
		$this->view->voteid = $this->paramInput->vote_id;
	}
	
	
	/**
	 *  ajax
	 */
	public function  ajaxAction()
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
			/* 审核*/
			
			case 'audit':
				
				//批量
				if (is_array($this->input->id))
				{
					foreach ($this->input->id as $key => $value)
					{
						$idlist[$value] = 1;
					}
					$ids = implode(',', array_keys($idlist));
					$sql = "UPDATE vote_comment SET status = CASE id ";
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
					$this->rows['vote_comment'] = $this->models['vote_comment']->find($this->input->id)->current();
					$this->rows['vote_comment']->status = 1;
					$this->rows['vote_comment']->save();
					$json['errno'] = 0;
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
					$sql = "UPDATE vote_comment SET status = CASE id ";
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
					$this->rows['vote_comment'] = $this->models['vote_comment']->find($this->input->id)->current();
					$this->rows['vote_comment']->status = -1;
					$this->rows['vote_comment']->save();
					$json['errno'] = 0;
					$this->_helper->json($json);
				}
				break;
			default:
				break;
		}
	}
}

?>