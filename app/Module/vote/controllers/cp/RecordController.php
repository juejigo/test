<?php

class Votecp_RecordController extends Core2_Controller_Action_Cp 
{
	/**
	 *  初始化
	 */
	public function  init()
	{
		parent::init();
		
		$this->models['voterecord'] = new Model_VoteRecord();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/votecp/record/list');
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
		
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('r' => 'vote_record'),array(new Zend_Db_Expr('COUNT(*)')))
			->joinLeft(array('v' => 'vote'),'v.id = r.vote_id',array('*'))
			->joinLeft(array('p' => 'vote_player'),'p.id = r.voteplayer_id',array('*'))
			->joinLeft(array('m' => 'member_profile'), 'm.member_id = r.member_id');

		$query = '/votecp/record/list?page={page}';

		if (!empty($this->paramInput->vr_id) )
		{
			$vp_id = $this->paramInput->vr_id;
			$select->where('r.vote_id = ?',$this->paramInput->vr_id);
			$query .= "&vr_id = {$this->paramInput->vr_id}";
		}
   
		/* 分页 */

		$count = $select->query()->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;

		/* 列表 */
		$idList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('dateline'),'r')
			->columns(array('vote_name'),'v')
			->columns(array('name','phone'),'p')
			->columns(array('member_name'),'m')
			->order('r.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();

		$this->view->idList = $idList;
		$this->view->vr_id = $this->paramInput->vr_id;
	}
}

?>