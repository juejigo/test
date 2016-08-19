<?php

class Product_FeedbackController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  评论列表
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		
		/* 获取评论列表 */
		
		$this->json['feedback_list'] = array();
		
		$select = $this->_db->select()
			->from(array('f' => 'product_feedback'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('f.product_id = ?',$this->input->product_id)
			->where('f.status = ?',1);
		$this->view->grade = 0;
		if ($this->input->grade !== '') 
		{
			switch ($this->input->grade)
			{
				case 1:
					$select->where('f.grade <= ?',1);
					break;
				case 2:
					$select->where('f.grade = ?',3);
					break;
				case 3:
					$select->where('f.grade > ?',3);
					$select->where('f.grade <= ?',5);
					break;
				default:
					break;
			}
			$this->view->grade = $this->input->grade;
		}
		
		// 总数
		$count = $select->query()
			->fetchColumn();
		
		// 数据
		$rs = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','f')
			->order('f.dateline DESC')
			->limitPage($this->input->page,$this->input->perpage)
			->query()
			->fetchAll();
		
		$feedbackList = array();
		foreach ($rs as $r) 
		{
			$feedback = array();
			$feedback['member_name'] = hideMobile($r['account']);
			$feedback['avatar'] = $r['avatar'];
			$feedback['grade'] = floor($r['grade']);
			$feedback['content'] = !empty($r['content']) ? $r['content'] : '默认好评';
			$feedback['dateline'] = date('Y.m.d',$r['dateline']);
			$feedbackList[] = $feedback;
		}
		if($this->input->ajax){
			$this->json['errno'] = '0';
			$this->json['feedback_list'] = $feedbackList;
			$this->_helper->json($this->json);
		}
		$this->view->product_id = $this->input->product_id;
		$this->view->feedbackList = $feedbackList;
	}
}

?>