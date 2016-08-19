<?php

class Favorite_FavoriteController extends Core2_Controller_Action_Fr   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['favorite'] = new Model_Favorite();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_forward('list');
	}	
	
	
	/**
	 *  收藏列表
	 */
	public function listAction()
	{
	    if (!params($this))
	    {
	        $this->_helper->notice($this->error->firstMessage(),'','error_1',array(
	            array(
	                'href' => '/product/product/list',
	                'text' => '全部宝贝'),
	            array(
	                'href' => 'javascript:history.go(-1);',
	                'text' => '返回上一面'),
	        ));
	    }

		/* 获取收藏列表 */
		
	    $this->view->from = 2;
	    
		$select = $this->_db->select()
			->from(array('f' => 'favorite'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('f.member_id = ?',$this->_user->id);
		

		$select->where('f.type = ?',$this->paramInput->type);
		
		// 总数
		$count = $select->query()
			->fetchColumn();

		$page = $this->paramInput->page ? $this->paramInput->page : 1;
		$perpage = $this->paramInput->perpage ? $this->paramInput->perpage : 10;
		//总页数
		$pages = ceil($count/$perpage);
		if($page>$pages){
		  $page = $pages;
		}
		//上一页
		if($page>1){
		  $prev_page = $page-1;
		}else{
		  $prev_page = 1;
		}
		//下一页
		if($page>=$pages){
		  $next_page = $pages;
		}else{
		  $next_page = $page+1;
		}

		// 数据
		$rs = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','f')
			->order('f.dateline DESC')
			->limitPage($page,$perpage)
			->query()
			->fetchAll();

		$favoriteList = array();
		foreach ($rs as $r) 
		{
			$favorite = array();
			$favorite['favorite_id'] = $r['id'];
			$favorite['type'] = $r['type'];
			$favorite['dataid'] = $r['dataid'];
			$favorite['title'] = $r['title'];
			$favorite['image'] = $r['image'];
			$favorite['id'] = $r['id'];
			$params = Zend_Serializer::unserialize($r['params']);
			$favorite = array_merge($favorite,$params);
			
			//查询商品价格
			$product = $this->_db->select()
			     ->from(array('p' => 'product'))
			     ->where('p.id = ?',$favorite['dataid'])
			     ->query()
			     ->fetch();
			$favorite['cost_price'] = $product['cost_price'];
		
			
			$favoriteList[] = $favorite;
		}

		$this->view->count = $count; //总条数
		$this->view->pages = $pages; //总页数
		$this->view->page = $page;   //当前页数
		$this->view->prev_page = $prev_page;   //上一页
		$this->view->next_page = $next_page;   //下一页
 		$this->view->favorite_list = $favoriteList;
		$this->view->headerTitle = '我的收藏_' . SITE_NAME;
	}
	
	/**
	 *  添加收藏
	 */
	public function addAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取收藏内容 */
			$product = $this->_db->select()
				->from(array('p' => 'product'),array('product_name','image','price'))
				->where('p.id = ?',$this->input->dataid)
				->query()
				->fetch();
			$title = $product['product_name'];
			$image = $product['image'];
			$params = array('price' => $product['price']); 
		
		$this->rows['favorite'] = $this->models['favorite']->createRow(array(
			'member_id' => $this->_user->id,
			'type' => $this->input->type,
			'dataid' => $this->input->dataid,
			'title' => $title,
			'image' => $image,
			'params' => Zend_Serializer::serialize($params)
		));
		$id = $this->rows['favorite']->save();
		$this->json['id'] = $id;
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  取消收藏
	 */
	public function cancleAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		if (!empty($this->input->favorite_id)) 
		{
			$this->rows['favorite'] = $this->models['favorite']->find($this->input->favorite_id)->current();
		}
		else if (isset($this->input->dataid)) 
		{
			$this->rows['favorite'] = $this->models['favorite']->fetchRow(
				$this->models['favorite']->select()
					->where('member_id = ?',$this->_user->id)
					->where('dataid = ?',$this->input->dataid)
			);
			
		}
		$this->rows['favorite']->delete();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>