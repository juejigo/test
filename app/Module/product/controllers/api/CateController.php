<?php

class Productapi_CateController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product_type'] = new Model_ProductType();
	}
	
	/**
	 *  分类列表
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
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
		
		$results = $select->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $result) 
		{
			$cate = array();
			$cate['id'] = $result['id'];
			$cate['cate_name'] = $result['cate_name'];
			$cate['image'] = thumbpath($result['image'],220);
			$cateList[] = $cate;
		}
		$this->json['cate_list'] = $cateList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  分类树
	 */
	public function treeAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		if (is_array($this->input->area) || $this->input->area === '') 
		{
			$this->json['tree'] = array();
		
			$this->json['errno'] = '0';
			$this->_helper->json($this->json);
		}
		#delete 版本兼容
		else 
		{
			$this->input->area = 0;
		}
		
		/* 构造树装结构 */
		$list = $this->_db->select()
			->from(array('p' => 'product_cate'))
			->where('area = ?',$this->input->area)
			->where('display = ?',1)
			->where('p.status = ?',1)
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($list as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($list,'id','parent_id','cate_name');
		$this->json['tree'] = $tree->toList();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  推荐位
	 */
	public function positionAction()
	{
		/* 定义推荐位ID */
		
// 		$positionId1 = 73;
		
		/* 横幅 */
		require_once 'includes/function/position.php';
		$cacheId = 'productapi_cate_position_position1';
		if ($this->_cache->test($cacheId)) 
		{
			$position1 = $this->_cache->load($cacheId);
		}
		else 
		{
// 			$rs = $this->_db->select()
// 				->from(array('d' => 'position_data'))
// 				->where('d.position_id = ?',$positionId1)
// 				->where('d.status = ?',1)
// 				->order('d.dateline DESC')
// 				->query()
// 				->fetchAll();
			$rs = array(
				array('id' => 73),
			);
			$position1 = decode($rs,'app');
// 			foreach ($rs as $r) 
// 			{
// 				$position = array();
// 				$position['type'] = $r['data_type'];
// 				$position['title'] = $r['title'];
// 				$position['image'] = $r['image'];
// 				$params = Zend_Serializer::unserialize($r['params']);
// 				$position = array_merge($position,$params);
// 				$position1[] = $position;
// 			}
			$this->_cache->save($position1,$cacheId);
		}
		$this->json['position1'] = $position1;
		
		$this->json['errno'] = 0;
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
			
			/* 处理属性 */
			
			$attrs = array();
			if (!empty($result['attrs'])) 
			{
				foreach ($result['attrs'] as $i => $r) 
				{
					$attr = array();
					$attr['i'] = $i;
					$attr['name'] = $r['name'];
					$attr['options'] = array();
					foreach ($r['options'] as $j => $value) 
					{
						$option = array();
						$option['value'] = $j;
						$option['option'] = $value;
						$attr['options'][] = $option;
					}
					$attrs[] = $attr;
				}
			}
			
			/* 参数处理 */
			
			$params = $result['params'];
			
			$type['attrs'] = $attrs;
			$type['params'] = $params;
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
		$top10 = array('高跟鞋','时尚鞋','真皮','豆豆鞋','商务休闲','韩版','布洛克','英伦风','乐福鞋','小码男鞋');
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