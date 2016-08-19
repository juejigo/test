<?php
 
class Usercp_AuthController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member_auth'] = new Model_MemberAuth();
		$this->models['bankcard'] = new Model_Bankcard();
        $this->models['member'] = new Model_Member();
 	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/usercp/auth/list');
	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{ 
 
		$this->models['member_auth'] = new Model_MemberAuth();
			/* 检验传值 */
	 
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/usercp/auth',
					'text' => '会员认证')
			));
		}
	  
		/* 构造 SQL 选择器 */
		$perpage = 20;
		
		$select = $this->_db->select()
		
			->from(array('m' => 'member_auth'),array(new Zend_Db_Expr('COUNT(*)')));
			
		$query = '/usercp/auth/list?page={page}';  
		/* 分页 */
		$count = $select->query()
		
			->fetchColumn();
			
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		
		$this->view->pagebar = $corepage->output();
	 
		/* 列表 */ 
		$memberList = $this->_db->select()
		
			->from(array('a' => 'member_auth'))
			
			->joinLeft(array('b' => 'bankcard'),'a.member_id = b.member_id',array('bank_type','card_no'))
			->limitPage($corepage->currPage(),$perpage)
			
			->order('a.dateline DESC')
			
			->query()
			
			->fetchAll();
		
 		$this->view->memberList = $memberList; 
	 
		 
	}
	
	 
	
	/**
	 *  编辑
	 */
	public function editAction()
	{
	 
 	    if (!params($this)) 
		{
			$this->_helper->notice('页面错误','','error',array(
					array(
						'href' => '/usercp/auth',
						'text' => '实名认证')
				));
		}

 		 $this->rows['member_auth'] = 
 		   	$this->models['member_auth']->fetchRow(
 		   	
			$this->models['member_auth']->select()
			
			->where('member_id = ?',$this->paramInput->member_id)
				
		 );	 
		 
		
		  
 		 $this->rows['bankcard'] = $this->models['bankcard']->fetchRow(
 		 
			$this->models['bankcard']->select()
			
				->where('member_id = ?',$this->paramInput->member_id)
				
		 );		
		 
	
	  
 		 $this->rows['member'] = $this->models['member']->fetchRow(
 		 
			$this->models['member']->select()
			
				->where('id = ?',$this->paramInput->member_id)
		 ); 
				 
	

		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
			  
 				$this->rows['member_auth']->status = $this->input->status; 
				$this->rows['member_auth']->memo = $this->input->memo; 
				$this->rows['member_auth']->save();	
				$this->rows['bankcard']->status = $this->input->status; 	 
				$this->rows['bankcard']->save();
				$this->rows['member']->real_auth =  $this->input->status;			 
				$this->rows['member']->save();				
	 			  
				$this->_helper->notice('操作成功','','success',array(
					array(
						'href' => '/usercp/auth/',
						'text' => '返回认证用户') 
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{ 
		   $this->data = array_merge($this->rows['member_auth'] 
		   		    	->toArray(),$this->rows['bankcard']->toArray());  
		} 
 
	}
	
	/**
	 *  编码
	 */
	public function codeAction()
	{
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 更新用户表 */
		
		$this->rows['member'] = $this->models['member']->find($this->input->id)->current();
		$this->rows['member']->code = $this->input->code;
		$this->rows['member']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  银行卡
	 */
	public function bankcardAction()
	{
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 更新用户表 */
		
		$this->rows['member'] = $this->models['member']->find($this->input->id)->current();
		$this->rows['member']->bank = $this->input->bank;
		$this->rows['member']->bankcard = $this->input->bankcard;
		$this->rows['member']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
}

?>