<?php

class Votecp_PlayerController extends Core2_Controller_Action_Cp 
{
	/**
	 *  初始化
	 */
	public function  init()
	{
		parent::init();

		$this->models['vote_player'] = new Model_VotePlayer();
		$this->models['image'] = new Model_Image();
	}

	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/votecp/player/list');
	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
						'href' => '/admincp',
						'text' => '返回')
			));
      	}
      	
      	/* 构造 SQL 选择器 */

		$perpage = 10;
		$select = $this->_db->select()->from(array('p' => 'vote_player'),array(new Zend_Db_Expr('COUNT(*)'))); 
		
		$query = '/votecp/player/list?page={page}';
		
		$vp_id = $this->paramInput->vp_id;    //选手所在的活动的ID
		$select->where('p.vote_id = ?',$this->paramInput->vp_id);
		$query .= "&vp_id={$this->paramInput->vp_id}";
                  
		if (!empty($this->paramInput->id) ) 
		{
			$select->where('p.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
       
		if (!empty($this->paramInput->dateform) )
		{
			$select->where('p.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
   
		if (!empty($this->paramInput->name) ) 
		{
			$select->where('p.name = ?',$this->paramInput->name);
			$query .= "&name={$this->paramInput->name}";
		}
		if(!is_null($this->paramInput->status))
		{ 
			if($this->paramInput->status=='all')
			{
				$select->where('p.status > ?',-2);
				$query .= "&status={$this->paramInput->status}";
			}
			else if(empty($this->paramInput->id)&&empty($this->paramInput->name))
			{
				$select->where('p.status = ?',$this->paramInput->status);
				$query .= "&status={$this->paramInput->status}";
			}
		}
  
		if (!empty($this->paramInput->dateline_from)) 
		{
			$select->where("p.join_time >= ?",strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}
   
		if (!empty($this->paramInput->dateline_to)) 
		{
			$select->where("p.join_time <= ?",strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}

		if (!empty($this->paramInput->order))
		{
			$query.="&order={$this->paramInput->order}";
		}
		/* 分页 */

		$count = $select->query()->fetchColumn();    
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;

		/* 列表 */
		
		$idList = array();
		$idListResult = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','name','phone','image','join_time','status','vote_num','nopass'),'p')
			->where('p.vote_id =?',$vp_id)
			->where('status >?',-2);

		if (!empty($this->paramInput->order))
		{	
			$idListResult->order('p.vote_num DESC');
		}
		else
		{
			$idListResult->order('p.join_time DESC');
		} 
		$idListResult = $idListResult
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		
		if(!empty($idListResult))
		{
			foreach($idListResult as $key => $r)
			{
				$idList[$key]=$r;
				if (!empty($this->paramInput->order))
				{
					$rank = $key+($this->paramInput->page-1)*$perpage+1;
					$idList[$key]['rank'] = "　排名：{$rank}";
				}
				
			}
		}
		$this->view->idList = $idList;
		$this->view->vp_id = $vp_id;
	}

	/**
	 *  添加
	 */
	public function addAction()
	{
		if (!params($this))
		{ 
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/admincp',
					'text' => '返回')
			));
		}
		$vote_id = $this->paramInput->vp_id;
     	//$vote_id = $this->_request->getQuery('vp_id','');
     	$vp_id = $vote_id;
     	$this->view->vp_id = $vp_id;

     	if ($this->_request->isPost())
		{
			if (form($this))
			{
				$this->rows['vote_player'] = $this->models['vote_player']->createRow(array(
					'vote_id' => $this->paramInput->vp_id,
					'name' => $this->input->name,
					'image'=> $this->input->image,
					'declaration' => $this->input->declaration,
					'introduction' => $this->input->getUnescaped(introduction),
					'vote_num' => $this->input->vote_num,
					'phone' => $this->input->phone,
					'status' => $this->input->audit,
					'nopass' => $this->input->nopass,			
				));

				$this->rows['vote_player']->save();

				$this->_helper->notice('添加成功','','success',array(
							array(
     								'href' => "/votecp/player/add?vp_id={$vp_id}",
     								'text' => '继续添加'), 
							array(
     								'href' => "/votecp/player/list?vp_id={$vp_id}",
     								'text' => '返回')
				));
     		}    		
		}
	}
	
	/**
	 *  添加
	 */
	public function editAction()
	{
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
						array(
								'href' => '/admincp',
								'text' => '返回')
			));
		}

		$vp_id = $this->_request->getQuery('vp_id','');
        $this->view->vp_id=$vp_id;
		$id=$this->paramInput->id;
		
		if ($this->_request->isPost())
		{
			if (form($this))
			{		
				$this->rows['vote_player'] = $this->models['vote_player']->find($this->paramInput->id)->current();     				

				$this->rows['vote_player']->name = $this->input->name;
     			$this->rows['vote_player']->image = $this->input->image;
     			$this->rows['vote_player']->declaration = $this->input->declaration;
     			$this->rows['vote_player']->introduction = $this->input->getUnescaped('introduction');
     			$this->rows['vote_player']->vote_num = $this->input->vote_num;
     			$this->rows['vote_player']->phone = $this->input->phone;
     			$this->rows['vote_player']->status = $this->input->audit;
     			$this->rows['vote_player']->nopass = $this->input->nopass;
     			$this->rows['vote_player']->save();

				$this->_helper->notice('添加成功','','success',array(
							array(
									'href' => "/votecp/player/edit?id={$id}&vp_id={$vp_id}",
									'text' => '继续编辑'), 
							array(
     							'href' => "/votecp/player/list?vp_id={$vp_id}",
     							'text' => '返回')
     			));
     		}
     	}
     	
     	/* 编辑列表 */
     	
     	$editList = array();
 		$editListResult = $this->_db->select()
     		->from(array('p'=>'vote_player'))
     		->where('p.id = ?',$this->paramInput->id)
     		->query()
     		->fetchAll();

 		if(!empty($editListResult))
 		{
 			foreach($editListResult as $r)
 			{
 				$editList[] = $r;
 			}
		}

		$this->view->editList = $editList;
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
     
		$this->rows['vote_player'] = $this->models['vote_player']->find($this->paramInput->id)->current();
		$this->rows['vote_player']->status = -2;
		$this->rows['vote_player']->save();
     
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
	 *  上传头像
	 */
	public function avauploadAction()
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
	     
		/* 初始化变量 */
		
		$this->_vars['json'] = array();
		$this->_vars['image'] = array();
     
     	$image = new Core2_Image('vote');
     	
		if (!$this->_vars['image'] = $image->upload('image'))
		{
			$this->_vars['json']['flag'] = 'error';
			$this->_vars['json']['errmsg'] = '图片格式错误或图片过大';
			$this->_helper->json($this->_vars['json']);
     	}

		$this->_vars['json']['flag'] = 'success';
		$this->_vars['json']['url'] = $this->_vars['image']['url'];
		$this->_helper->json($this->_vars['json']);
	}
     
}


?>