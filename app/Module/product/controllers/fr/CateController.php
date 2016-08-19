<?php

class Product_CateController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  分类列表
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回'),
			));
		}
		
		/* 获取列表 */
		$this->json['cate_list'] = array();
		
		$select = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.display = ?','1')
			->where('c.status = ?','1');
		
		if ($this->input->parent_id !== '') 
		{
			$select->where('c.parent_id = ?',$this->input->parent_id);
		}

		if ($this->input->area !== '') 
		{
			$select->where('c.area = ?',$this->input->area);
		}

		if ($this->input->level !== '') 
		{
			$select->where('c.level = ?',$this->input->level);
		}

		$results = $select->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $result) 
		{
			$cate = array();
			$cate['id'] = $result['id'];
			$cate['level'] = $result['level'];
			$cate['cate_name'] = $result['cate_name'];
			$cate['image'] = thumbpath($result['image'],220);
			$cateList[] = $cate;
		}
		$this->view->cateList = $cateList;
	}
	
	/**
	 *  推荐位
	 */
	public function positionAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取列表 */
		
		$this->json['position_list'] = array();
		
		$select = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.parent_id = ?',$this->input->cate_id)
			->where('c.display = ?','1')
			->where('c.status = ?','1');
		
		$positionList = array();
		$rs = $select->query()
			->fetchAll();
		foreach ($rs as $r) 
		{
			$position = array();
			$position['type'] = 'product_list';
			$position['title'] = $r['cate_name'];
			$position['image'] = thumbpath($r['image'],220);
			$position['cate_id'] = $r['id'];
			$positionList[] = $position;
		}
		$this->json['position_list'] = $positionList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  分类详情
	 */
	public function detailAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取分类 */
		$this->json['cate'] = array();
		
		$select = $this->_db->select()
			->from(array('c' => 'product_cate'));
		
		$result = $select->where('c.id = ?',$this->input->id)
			->query()
			->fetch();
		$cate = array();
		$cate['id'] = $result['id'];
		$cate['type_id'] = $result['type_id'];
		$cate['cate_name'] = $result['cate_name'];
		$this->json['cate'] = $cate;
		
		/* 获取类型 */
		$this->json['cate']['type'] = array();
		
		if (!empty($cate['type_id'])) 
		{
			$result = $this->_db->select()
				->from(array('m' => 'product_type'))
				->where('m.id = ?',$cate['type_id'])
				->query()
				->fetch();
			list($result['attrs'],$result['params']) = $this->models['product_type']->decode($result);
			$type = array();
			$type['type_name'] = $result['type_name'];
			$type['attrs'] = $result['attrs'];
			$type['params'] = $result['params'];
			$this->json['cate']['type'] = $type;
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  列表+推荐位
	 *  #2.0废弃
	 */
	public function listpositionAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 二级分类 */
		
		$cateList = array();
		$rs = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.parent_id = ?',$this->input->parent_id)
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		
		if (!empty($rs)) 
		{
			foreach ($rs as $r) 
			{
				$cate = array();
				$cate['id'] = $r['id'];
				$cate['cate_name'] = $r['cate_name'];
				$cate['image'] = thumbpath($r['image'],220);
				$cateList[] = $cate;
			}
		}
		$this->json['cate_list'] = $cateList;
		
		/* 推荐位 */
		
		$positionList = array();
		if (!empty($cateList)) 
		{
			$rs = $this->_db->select()
				->from(array('c' => 'product_cate'))
				->where('c.parent_id = ?',$cateList[0]['id'])
				->where('c.display = ?','1')
				->where('c.status = ?','1')
				->query()
				->fetchAll();
			
			if (!empty($rs)) 
			{
				foreach ($rs as $r) 
				{
					$position = array();
					$position['type'] = 'product_list';
					$position['title'] = $r['cate_name'];
					$position['image'] = thumbpath($r['image'],220);
					$position['cate_id'] = $r['id'];
					$positionList[] = $position;
				}
			}
		}
		$this->json['position_list'] = $positionList;
		
		/* 搜索 TOP10 */
		$top10 = array();
		$this->json['top10'] = $top10;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  推荐位
	 */
	public function listposition2Action()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 二级分类 */
		
		$cateList = array();
		$rs = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.parent_id = ?',$this->input->parent_id)
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		
		if (!empty($rs)) 
		{
			foreach ($rs as $r) 
			{
				$cate = array();
				$cate['id'] = $r['id'];
				$cate['cate_name'] = $r['cate_name'];
				$cate['image'] = thumbpath($r['image'],220);
				$cateList[] = $cate;
			}
		}
		$this->json['cate_list'] = $cateList;
		
		/* 推荐位 */
		
		$this->json['position_list'] = array();
		
		foreach ($cateList as $cate) 
		{
			$this->json['position_list'][$cate['id']]['title'] = $cate['cate_name'];
			$cacheId = "productapi_cate_listposition2_{$cate['id']}";
			$positionList = array();
			
			if ($this->_cache->test($cacheId)) 
			{
				$positionList = $this->_cache->load($cacheId);
			}
			else 
			{
				$rs = $this->_db->select()
					->from(array('d' => 'position_data'))
					->where('d.cate_id = ?',$cate['id'])
					->where('d.status = ?',1)
					->query()
					->fetchAll();
				$position = array();
				foreach ($rs as $r) 
				{
					$position = array();
					$position['type'] = $r['data_type'];
					$position['title'] = $r['title'];
					$position['image'] = $r['image'];
					$params = Zend_Serializer::unserialize($r['params']);
					$position = array_merge($position,$params);
					$positionList[] = $position;
				}
				$this->_cache->save($positionList,$cacheId);
			}
			$this->json['position_list'][$cate['id']]['position_list'] = $positionList;
		}
		
		/* 搜索 TOP10 */
		$top10 = array('高跟鞋','时尚鞋','真皮','豆豆鞋','商务休闲','韩版','布洛克','英伦风','乐福鞋','小码男鞋');
		$this->json['top10'] = $top10;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>