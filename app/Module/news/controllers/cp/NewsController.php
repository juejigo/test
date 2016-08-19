<?php

class Newscp_NewsController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['news'] = new Model_News();
		$this->models['news_data'] = new Model_NewsData();
		$this->models['position_data'] = new Model_PositionData();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/newscp/news/list');
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
					'href' => '/newscp/news/list',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('n' => 'news'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('n.status = ?',1);
		
		$query = '/newscp/news/list?page={page}';
		
		if (!empty($this->paramInput->id))
		{
			$select->where('n.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		
		if (!empty($this->paramInput->title))
		{
			$select->where('n.title like ?','%'.$this->paramInput->title.'%');
			$query .= "&title={$this->paramInput->title}";
		}
		
		if (!empty($this->paramInput->cate_id))
		{
			$select->where('n.cate_id = ?',$this->paramInput->cate_id);
			$query .= "&cate_id={$this->paramInput->cate_id}";
		}
		
		/* 分页 */
		
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$newsList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','n')
			->order('n.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->newsList = $newsList;
		
		/* 分类 */
		
		$results = $this->_db->select()
			->from(array('c' => 'news_cate'))
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($results,'id','parent_id','cate_name');
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 新闻主表 */
				
				$this->rows['news'] = $this->models['news']->createRow(array(
					'cate_id' => $this->input->cate_id,
					'title' => $this->input->title,
					'image' => $this->input->image,
				));
				$this->rows['news']->save();
				
				/* 新闻附属表 */
				
				$this->rows['news_data'] = $this->models['news_data']->find($this->rows['news']->id)->current();
				$this->rows['news_data']->content = $this->input->getUnescaped('content');
				$this->rows['news_data']->save();
				
				$this->_helper->notice('发布成功','','success',array(
					array(
						'href' => '/newscp/news/add',
						'text' => '继续添加'),
					array(
						'href' => '/newscp/news/list',
						'text' => '文章列表')
				));
			}
		}
		
		/* 分类列表 */
		
		$results = $this->_db->select()
			->from(array('c' => 'news_cate'))
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($results,'id','parent_id','cate_name');
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;
	}
	
	/**
	 *  编辑
	 */
	public function editAction()
	{
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/newscp/news/list',
					'text' => '文章列表')
			));
		}
		
		$this->rows['news'] = $this->models['news']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 新闻主表 */
				
				$this->rows['news']->cate_id = $this->input->cate_id;
				$this->rows['news']->title = $this->input->title;
				$this->rows['news']->image = $this->input->image;
				$this->rows['news']->save();
				
				/* 新闻附属表 */
				
				$this->rows['news_data'] = $this->models['news_data']->find($this->rows['news']->id)->current();
				$this->rows['news_data']->content = $this->input->getUnescaped('content');
				$this->rows['news_data']->save();
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => '/newscp/news/list',
						'text' => '文章列表')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$news = $this->_db->select()
				->from(array('n' => 'news'))
				->joinLeft(array('d' => 'news_data'),'d.news_id = n.id',array('content'))
				->where('n.id = ?',$this->paramInput->id)
				->query()
				->fetch();
			$this->data = $news;
		}
		
		/* 分类 */
		
		$results = $this->_db->select()
			->from(array('c' => 'news_cate'))
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($results,'id','parent_id','cate_name');
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;
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
				$json['msg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			else 
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => '/member/center',
						'text' => '会员中心')
				));
			}
		}
		
		/* 删除文章 */
		
		$this->rows['news'] = $this->models['news']->find($this->paramInput->id)->current();
		$this->rows['news']->status = -1;
		$this->rows['news']->save();
		
		if ($this->_request->isXmlHttpRequest()) 
		{
			$json['errno'] = 0;
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
}

?>