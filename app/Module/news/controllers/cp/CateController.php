<?php

class Newscp_CateController extends Core2_Controller_Action_Cp  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['news_cate'] = new Model_NewsCate();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/newscp/cate/list');
	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 构造树装结构 */
		$list = $this->_db->select()
			->from(array('c' => 'news_cate'))
			->where('c.status = ?',1)
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($list as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($list,'id','parent_id','cate_name');
		$this->view->tree = $tree;
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/cate',
					'text' => '分类列表')
			));
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data['parent_id'] = $this->paramInput->parent_id;
		}
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				$this->rows['news_cate'] = $this->models['news_cate']->createRow(array(
					'parent_id' => $this->input->parent_id,
					'cate_name' => $this->input->cate_name,
					'image' => $this->input->image,
					'display' => $this->input->display
				));
				$this->rows['news_cate']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => '/newscp/cate/list',
						'text' => '返回')
				));
			}
		}
		
		/* 父分类树 */
		$list = $this->_db->select()
			->from(array('c' => 'news_cate'))
			->where('c.status = ?',1)
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($list as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($list,'id','parent_id','cate_name');
		$this->view->tree = $tree;
		$this->view->parentList = $tree->toArray();
		$this->view->cateList =$cateList;
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
					'href' => '/newscp/cate/list',
					'text' => '返回')
			));
		}
		
		$this->rows['news_cate'] = $this->models['news_cate']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				$this->rows['news_cate']->parent_id = $this->input->parent_id;
				$this->rows['news_cate']->cate_name = $this->input->cate_name;
				$this->rows['news_cate']->image = $this->input->image;
				$this->rows['news_cate']->display = $this->input->display;
				$this->rows['news_cate']->save();
				
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => '/newscp/cate/list',
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = $this->rows['news_cate']->toArray();
		}
		
		/* 父分类树 */
		$list = $this->_db->select()
			->from(array('c' => 'news_cate'))
			->where('c.status = ?',1)
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($list as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($list,'id','parent_id','cate_name');
		$this->view->tree = $tree;
		$this->view->parentList = $tree->toArray();
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
		
		/* 删除文章分类 */
		
		$this->rows['news_cate'] = $this->models['news_cate']->find($this->paramInput->id)->current();
		if ($this->rows['news_cate']->allow_delete != 1)
		{
			$json['errno'] = '1';
			$json['msg'] = '该分类不允许删除';
			$this->_helper->json($json);
		}
		$this->rows['news_cate']->status = -1;
		$this->rows['news_cate']->save();
		
		if ($this->_request->isXmlHttpRequest()) 
		{
			$json['errno'] = '0';
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