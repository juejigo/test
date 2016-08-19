<?php

class Favoriteapi_FavoriteController extends Core2_Controller_Action_Api   
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
	 *  收藏列表
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取收藏列表 */
		
		$this->json['favorite_list'] = array();
		
		$select = $this->_db->select()
			->from(array('f' => 'favorite'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('f.member_id = ?',$this->_user->id);
		
		$select->where('f.type = ?',$this->input->type);
		
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
		
		$favoriteList = array();
		foreach ($rs as $r) 
		{
			$favorite = array();
			$favorite['favorite_id'] = $r['id'];
			$favorite['type'] = $r['type'];
			$favorite['dataid'] = $r['dataid'];
			$favorite['title'] = $r['title'];
			$favorite['image'] = $r['image'];
			$params = Zend_Serializer::unserialize($r['params']);
			$favorite = array_merge($favorite,$params);
			$favoriteList[] = $favorite;
		}
		$this->json['favorite_list'] = $favoriteList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
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
		
		// 商品
		if ($this->input->type == 0) 
		{
			$product = $this->_db->select()
				->from(array('p' => 'product'),array('product_name','image','price'))
				->where('p.id = ?',$this->input->dataid)
				->query()
				->fetch();
			$title = $product['product_name'];
			$image = $product['image'];
			$params = array('price' => $product['price']); 
		}
		
		$this->rows['favorite'] = $this->models['favorite']->createRow(array(
			'member_id' => $this->_user->id,
			'type' => $this->input->type,
			'dataid' => $this->input->dataid,
			'title' => $title,
			'image' => $image,
			'params' => Zend_Serializer::serialize($params)
		));
		$this->rows['favorite']->save();
		
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
		else if (isset($this->input->type) && isset($this->input->dataid)) 
		{
			$this->rows['favorite'] = $this->models['favorite']->fetchRow(
				$this->models['favorite']->select()
					->where('type = ?',$this->input->type)
					->where('dataid = ?',$this->input->dataid)
			);
			
		}
		$this->rows['favorite']->delete();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>