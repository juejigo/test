<?php

class Votecp_VoteController extends Core2_Controller_Action_Cp
{
	/**
	 *  初始化
	 */
	public function  init()
	{
		parent::init();

		$this->models['vote'] = new Model_Vote();
		$this->models['image'] = new Model_Image();
	}

	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/votecp/vote/list');
	}

	/**
	 *  初始化
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

		$perpage = 20;
		$select = $this->_db->select()->from(array('v' => 'vote'),array(new Zend_Db_Expr('COUNT(*)')));

		$query = '/votecp/vote/list?page={page}';

		if (!empty($this->paramInput->voteId) )
		{
			$select->where('v.id = ?',$this->paramInput->voteId);
			$query .= "&voteId={$this->paramInput->voteId}";
		}

		if (!empty($this->paramInput->votename) )
		{
			$select->where('v.vote_name = ?',$this->paramInput->votename);
			$query .= "&votename={$this->paramInput->votename}";
		}

		if (!empty($this->paramInput->dateline_from))
		{
			$select->where("v.start_time >= ?",strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}

		if (!empty($this->paramInput->dateline_to))
		{
			$select->where("v.end_time <= ?",strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}

		if (!is_null($this->paramInput->status) )
		{
			if($this->paramInput->status=='all')
			{
				$select->where('v.status > ?',-1);
				$query .= "&status={$this->paramInput->status}";
			}
			else
			{
				$select->where('v.status = ?',$this->paramInput->status);
				$query .= "&status={$this->paramInput->status}";
			}
		}

		/* 分页 */

		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
 
		/* 列表 */

		$idList = array();
		$idListResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','image','vote_name','start_time','end_time','vend_time','vstart_time','status'),'v')
			->where('status > ?',-1)
			->order('v.id ASC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();

		/*期间状态判断*/

		$time = time();
		for($i=0;$i<count($idListResult);$i++)
		{
			//报名期
			if($time>$idListResult[$i]['start_time'] && $time<$idListResult[$i]['end_time'])
			{
				$idListResult[$i]['t_status']=1;
			}
			//投票期
			if($time>$idListResult[$i]['vstart_time'] && $time<$idListResult[$i]['vend_time'])
			{
				$idListResult[$i]['t_status']=2;
			}
			//过期
			if($time>$idListResult[$i]['vend_time'])
			{
				$idListResult[$i]['t_status']=3;
			}
		}

		if(!empty($idListResult))
		{
			foreach($idListResult as $r)
			{
				$idList[] = $r;
			}
		}
		
		$this->view->idList = $idList;
		
	}
		
	/**
	 *  添加
	 */    
	public function addAction()
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

		if ($this->_request->isPost())
		{
			if (form($this))
			{
				/* 插入数据库 */
				$this->rows['vote'] = $this->models['vote']->createRow(array(
					'vote_name' => $this->input->votename,
					'image' => $this->input->image,
					'rule' => $this->input->getUnescaped('detail'),
					'vote_btn' => $this->input->vote_btn,
					'start_time' => strtotime($this->input->signup_begin_time),
					'end_time' => strtotime($this->input->signup_end_time),
					'vstart_time' => strtotime($this->input->vote_begin_time),
					'vend_time' => strtotime($this->input->vote_end_time),
					'limit_sumnum' => $this->input->all_vote,
					'limit_daynum' => $this->input->day_vote,
					'limit_dayonenum' => $this->input->one_to_vote,
					'rank_num' => $this->input->rank_num,
					'subscribe' => $this->input->subscribe,
					'allow_post' => $this->input->allow_post,
					'player_auth' => $this->input->player_auth,
					'comment_auth' => $this->input->comment_auth,
					'day_auth' => $this->input->day_auth,
					'intro' => $this->input->intro,
					'awards' => $this->input->getUnescaped('awards'),
					'status'=> '0'
				));

				$this->rows['vote']->save();
				$this->_helper->notice('添加成功','','success',array(
					array(
						'href' => '/votecp/vote/list',
						'text' => '返回')
				));
			}
		}
	}
	
	/**
	 *  编辑
	 */
	public function editAction()
	{	
		if (!params($this))
		{
			/* 检验传值 */
			
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/admincp',
					'text' => '返回')
			));
		}
		
		if ($this->_request->isPost())
		{
			if (form($this))
			{
				/* 插入数据库 */
				$this->rows['vote'] = $this->models['vote']->find($this->paramInput->id)->current();
			
     		 	$this->rows['vote']->vote_name = $this->input->votename;
     			$this->rows['vote']->image = $this->input->image;
     			$this->rows['vote']->rule = $this->input->getUnescaped('detail');
     			$this->rows['vote']->vote_btn = $this->input->vote_btn;
     			$this->rows['vote']->start_time = strtotime($this->input->signup_begin_time);
     			$this->rows['vote']->end_time = strtotime($this->input->signup_end_time);
     			$this->rows['vote']->vstart_time = strtotime($this->input->vote_begin_time);
     			$this->rows['vote']->vend_time = strtotime($this->input->vote_end_time);
     			$this->rows['vote']->limit_sumnum = $this->input->all_vote;
     			$this->rows['vote']->limit_daynum = $this->input->day_vote;
     			$this->rows['vote']->limit_dayonenum = $this->input->one_to_vote;
     			$this->rows['vote']->rank_num = $this->input->rank_num;
     			$this->rows['vote']->subscribe = $this->input->subscribe;
     			$this->rows['vote']->allow_post = $this->input->allow_post;
     			$this->rows['vote']->intro = $this->input->intro;
     			$this->rows['vote']->awards = $this->input->getUnescaped('awards');
     			$this->rows['vote']->player_auth = $this->input->player_auth;
     			$this->rows['vote']->comment_auth = $this->input->comment_auth;
     			$this->rows['vote']->day_auth = $this->input->day_auth;
     			$this->rows['vote']->save(); 
     			
     			$this->_helper->notice('修改成功','','success',array(
     					array(
     							'href' => '/votecp/vote/list',
     							'text' => '返回')
     			));
     		}
		}

		if ($this->_request->isGet())
		{ 
			$editListResult = $this->_db->select()
				->from(array('v'=>'vote'))
				->where('v.id = ?',$this->paramInput->id)
				->query()
				->fetchAll();
			
			/*时间格式转换*/
			
			$editListResult[0]['start_time']=date('Y-m-d',$editListResult[0]['start_time']);
			$editListResult[0]['end_time']=date('Y-m-d',$editListResult[0]['end_time']);
			$editListResult[0]['vstart_time']=date('Y-m-d',$editListResult[0]['vstart_time']);
			$editListResult[0]['vend_time']=date('Y-m-d',$editListResult[0]['vend_time']);

			if(!empty($editListResult))
			{
				foreach($editListResult as $r)
				{
					$editList[] = $r;
				}
			}
			$this->view->editList = $editList;     	 
		}     	
	}
	
	/**
	 *  上架
	 */
	public function upAction()
	{
		/* 取消视图 */

		$this->_helper->viewRenderer->setNoRender();
		$json = array();
     	
		if (!form($this))
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}

		/* 上架 */  
		
		$this->rows['vote'] = $this->models['vote']->find($this->input->id)->current();
		$this->rows['vote']->status = 1;
		$this->rows['vote']->save();

		$json['errno'] = 0;
		$this->_helper->json($json);
 	
	}

	/**
	 *  下架
	 */    
	public function downAction()
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();
		
		if (!form($this))
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
     
		/* 下架 */

		$this->rows['vote'] = $this->models['vote']->find($this->input->id)->current();
		$this->rows['vote']->status = 0;
		$this->rows['vote']->save();

		$json['errno'] = 0;
		$this->_helper->json($json);
	}

	/**
	 *  删除
	 */
	public function deleteAction()
	{
		/* 取消视图 */

		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();

		if (!params($this)) 
		{
			if ($this->_request->isXmlHttpRequest()) 
			{
				$json['flag'] = 'error';
				$json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			else 
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => 'javascript:history.back();',
						'text' => '返回')
				));
			}
		}
		
		/* 删除商品 */

		$this->rows['vote'] = $this->models['vote']->find($this->paramInput->id)->current();
		$this->rows['vote']->status = -1;
		$this->rows['vote']->save();
		
		if ($this->_request->isXmlHttpRequest()) 
		{
			$json['flag'] = 'success';
			$this->_helper->json($json);
		}
		else 
		{
			$this->_helper->notice('删除成功','','success',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
	}
	
	/**
	 *  横幅上传
	 */
	public function banneruploadAction()
	{
		/* 取消视图 */
		$this->_helper->viewRenderer->setNoRender();

		/* 初始化变量 */
		$this->_vars['json'] = array();
		$this->_vars['image'] = array();

		$image = new Core2_Image('banner');
		if (!$this->_vars['image'] = $image->upload('image'))
		{
			$this->_vars['json']['flag'] = 'error';
			$this->_vars['json']['msg'] = '图片格式错误或图片过大';
			$this->_helper->json($this->_vars['json']);
		}
     
		$this->_vars['json']['flag'] = 'success';
		$this->_vars['json']['url'] = $this->_vars['image']['url'];
		$this->_helper->json($this->_vars['json']);
	}
}




?>