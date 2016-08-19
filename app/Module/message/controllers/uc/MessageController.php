<?php

class Message_MessageController extends Core2_Controller_Action_Uc   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['message'] = new Model_Message();
	}
	
	/**
	 *  列表
	 */
	public function indexAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('m' => 'message'),array(new Zend_Db_Expr('COUNT(*)')));
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,"/message/message/index");
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		$messageList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','m')
			->where('m.status = ?','1')
			->order('m.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		$this->view->messageList = $messageList;
	}
	
	/**
	 *  详情
	 */
	public function detailAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
		
		$this->rows['message'] = $this->models['message']->find($this->paramInput->id)->current();
		
		$this->data = $this->rows['message']->toArray();
	}
	
	/**
	 *  回复
	 */
	public function replyAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
		
		$this->rows['message'] = $this->models['message']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				$this->rows['message']->reply = $this->input->reply;
				$this->rows['message']->save();
				
				/* 提示 */
				$this->_helper->notice('编辑成功',$this->error->firstMessage(),'error',array(
					array(
						'href' => 'javascript:history.back();',
						'text' => '返回')
				));
			}
		}
		
		$this->data = $this->rows['message']->toArray();
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
				/* 提示 */
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => 'javascript:history.back();',
						'text' => '返回')
				));
			}
		}
		
		/* 删除消息 */
		$this->rows['message'] = $this->models['message']->find($this->paramInput->id)->current();
		$this->rows['message']->status = -1;
		$this->rows['message']->save();
		
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
}

?>