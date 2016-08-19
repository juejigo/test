<?php

class Productapi_FeedbackController extends Core2_Controller_Action_Api  
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
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取评论列表 */
		
		$this->json['feedback_list'] = array();
		
		$select = $this->_db->select()
			->from(array('f' => 'product_feedback'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('f.product_id = ?',$this->input->product_id)
			->where('f.status = ?',1);
		
		if ($this->input->grade !== '') 
		{
			switch ($this->input->grade)
			{
				case 0:
					$select->where('f.grade <= ?',1);
					break;
				case 1:
					$select->where('f.grade = ?',3);
					break;
				case 2:
					$select->where('f.grade > ?',3);
					$select->where('f.grade <= ?',5);
					break;
				default:
					break;
			}
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
			$feedback['grade'] = $r['grade'];
			$feedback['content'] = !empty($r['content']) ? $r['content'] : '默认好评';
			$feedback['dateline'] = $r['dateline'];
			$feedbackList[] = $feedback;
		}
		$this->json['feedback_list'] = $feedbackList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>