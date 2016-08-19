<?php

class Indexapi_IndexController extends Core2_Controller_Action_Api 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		if($this->input->v == 2)
		{
		    //分类
		    require_once 'includes/function/position.php';
		    
		    //首页五个固定推荐分类
		    $cacheId = 'indexapi_index_index_position1v2';
		    if ($this->_cache->test($cacheId))
		    {
		    	$position1 = $this->_cache->load($cacheId);
		    }
		    else
		    {
		    	$positionId1 = array('id' => '72','limit' => '5');
		    	$position1 = decode($positionId1,'app');
		    	$this->_cache->save($position1,$cacheId);
		    }
		    $this->json['position1'] = $position1;
		    
		    //首页推荐列表
		    $cacheId = 'indexapi_index_index_position2v2';
		    if ($this->_cache->test($cacheId))
		    {
		    	$position2 = $this->_cache->load($cacheId);
		    }
		    else
		    {
		    	$positionId2 = array('id' => '73');
		    	$position2 = decode($positionId2,'app');
		    	$this->_cache->save($position2,$cacheId);
		    }
		    $this->json['position2'] = $position2;
		    
		    //商品列表
		    $cacheId = 'indexapi_index_index_productv2'.$this->input->page;
		    if ($this->_cache->test($cacheId))
		    {
		    	$productList = $this->_cache->load($cacheId);
		    }
		    else
		    {
		    	//商品列表
			    $tagId = 42;
			    $select = $this->_db->select()
				    ->from(array('t'=>'product_tagdata'),array(new Zend_Db_Expr('COUNT(*)')))
				    ->joinLeft(array('p' => 'product'), 't.product_id = p.id')
				    ->where('t.tag_id = ?',$tagId)
				    ->order('t.order asc');
			    
	            // 总数
	            $count = $select->query()
	                ->fetchColumn();
	
	            // 数据
	            $results = $select->reset(Zend_Db_Select::COLUMNS)
	                ->columns('*','p')
	                ->where('p.status = ?',2)
	                ->limitPage($this->input->page,$this->input->perpage)
	                ->query()
	                ->fetchAll();
	            
	            $productList = array();
	            
	            foreach ($results as $result)
	            {
	                $product = array();
	                $product['product_id'] = $result['id'];
	                $product['product_name'] = htmlDecodeCn($result['product_name']);
	                $product['image'] = $result['image'];
	                $product['large_image'] = $result['image'];
	                $product['price'] = intval($result['price']);
	                $product['mktprice'] = intval($result['cost_price']);
	                $product['travel_date'] = intval($result['travel_date']);
	                $product['discount']	= intval($result['cost_price'] - $result['price']);
	                $product['clock'] =$result['down_time']-time();
	                $product['tourism_type'] = $result['tourism_type'];
	                
	                //查询出发城市
	                $city = $this->_db->select()
	                    ->from(array('o' => 'region'))
	                    ->where('o.id = ?',$result['origin_id'])
	                    ->query()
	                    ->fetch();
	                $product['origin'] = $city['region_name'];  //出发城市
	                
	                $productList[] = $product;
	            }
	            $this->_cache->save($productList,$cacheId);
		    }
            $this->json['product_list'] = $productList;
            
            $this->json['errno'] = 0;
            $this->_helper->json($this->json);
            
		}
		else 
		{
		    //分类
		    $positionId1 = 66;
		    $cacheId = 'indexapi_index_index_position1';
		    
		    $rs = $this->_db->select()
    		    ->from(array('d' => 'position_data'))
    		    ->where('d.position_id = ?',$positionId1)
    		    ->where('d.status = ?',1)
    		    ->limit(5,0)
    		    ->order('d.dateline DESC')
    		    ->query()
    		    ->fetchAll();
		    $position1 = array();
		    
		    foreach ($rs as $r)
		    {
		        $position = array();
		        $position['type'] = $r['data_type'];
		        $position['title'] = $r['title'];
		        $position['image'] = $r['image'];
		        $params = Zend_Serializer::unserialize($r['params']);
		        $position = array_merge($position,$params);
		        $position1[] = $position;
		    }
		    $this->_cache->save($position1,$cacheId);
		    
		    $this->json['position1'] = $position1;
		    
		    
		    /* 首页 */
		    
		    $select = $this->_db->select()
		    ->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
		    //  ->joinLeft(array('c' => 'product_catedata'),'c.product_id = p.id')
		    ->where('p.parent_id = ?',0)
		    ->where('p.status = ?',2)
		    ->group('p.id')
		    ->order('p.up_time DESC');
		    	
		    // 			if (!empty($this->input->cate_id))
		        // 			{
		        // 			    $cateIds = array($this->input->cate_id);
		        // 			    $this->rows['product_cate'] = $this->models['product_cate']->find($this->input->cate_id)->current();
		        // 			    $children = $this->rows['product_cate']->getAllChildren();
		        // 			    if (!empty($children))
		            // 			    {
		            // 			        foreach ($children as $child)
		                // 			        {
		                // 			            $cateIds[] = $child['id'];
		                // 			        }
		            // 			    }
		    
		    // 			    $select->where('c.cate_id IN (?)',$cateIds);
		    // 			}
		    	
		    // 总数
		    $count = $select->query()
		    ->fetchColumn();
		    
		    // 数据
		    if($this->input->perpage == "")
		    {
		        $this->input->perpage = 6;
		    }
		    $results = $select->reset(Zend_Db_Select::COLUMNS)
		    ->columns('*','p')
		    ->limitPage($this->input->page,$this->input->perpage)
		    ->query()
		    ->fetchAll();
		    
		    $position1 = array();
		    	
		    foreach ($results as $r)
		    {
		        $position = array();
		        $position['type'] = 'product';
		        $position['title'] = htmlDecodeCn($r['product_name']);
		    
		        $r['image'] =thumbpath($r['image']);
		        $position['image'] = $r['image'];
		        $position['clock'] = $r['down_time']-time();
		        $position['product_id'] = $r['id'];
		        $position['brief'] = $r['brief'];
		        $position1[] = $position;
		    }
		    
		    $this->json['position2'] = $position1;
		    
		    $this->json['errno'] = 0;
		    $this->_helper->json($this->json);
		}
	
	}
	
}
?>