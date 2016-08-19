<?php

class Product_ProductController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();

		$this->models['product'] = new Model_Product();
		$this->models['product_cate'] = new Model_ProductCate();
		$this->models['product_image'] = new Model_ProductImage();
		$this->models['product_item'] = new Model_ProductItem();
		$this->models['product_ticket'] = new Model_ProductTicket();
		$this->models['product_trip'] = new Model_ProductTrip();
		$this->models['region'] = new Model_Region();
	}
	

	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_forward('list');
	}
	
	/**
	 * 查询商品
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
    	
    	/* 标签推荐位*/
    	
    	$tagId = 41;
    	$positionDatas = $this->_db->select()
	    	->from(array('t'=>'product_tagdata'))
	    	->joinLeft(array('p' => 'product'), 't.product_id = p.id')
	    	->where('t.tag_id = ?',$tagId)
	    	->order('t.order asc')
	    	->limit(5)
	    	->query()
	    	->fetchAll();
    	
    	$positions = array();    	
    	if (!empty($positionDatas))
    	{
	    	foreach ($positionDatas as $key => $positionData)
	    	{
	    		$positionData['title'] = empty($positionData['title']) ? ' ' : $positionData['title'];
	    		$positionData['image'] = empty($positionData['image']) ? DOMAIN.'static/style/default/mix/company/images/moren.gif' : $positionData['image'];
	    		$newjxuanProduct[$key]['productInfo'] = $positionData;
	    		$newjxuanProduct[$key]['url'] = DOMAIN.'product/product/detail/?id='.$positionData['id'];
	    	}
    		$positions = $newjxuanProduct;
    	}
    	
    	for($i=0;$i<count($positions);$i++)
    	{
    		//查询出发地
    		$cityId =  $this->_db->select()
    		->from(array('o' => 'region'))
    		->where('id = ?',$positions[$i]['productInfo']['origin_id'])
    		->query()
    		->fetch();
    	
    		$positions[$i]['productInfo']['region_name'] = $cityId['region_name'];
    	
    		// 查询团期
    		$productItem = $this->_db->select()
        		->from(array('o' => 'product'),array('travel_date'))
        		->where('o.parent_id = ?',$positions[$i]['productInfo']['id'])
        		->where('o.status <> ?',3)
        		->where('o.status <> ?',-1)
        		->query()
        		->fetchAll();
    	
    		$tuanqi = "";
    		foreach ($productItem as $j=>$row)
    		{
    			$tuanqi .= date("Y-m-d",$row['travel_date']);
    			if($j != count($productItem)-1)
    			{
    				$tuanqi .= ",";
    			}
    		}
    	
    		$positions[$i]['productInfo']['travel_date'] = $tuanqi;
    	}
    	
    	$this->view->jxuan_product = $positions;

        /* 构造 SQL 选择器 */
         
        $perpage = 20;
        $select = $this->_db->select()
	        ->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
	        ->where('p.parent_id =?',0)
	        ->where('p.status =  ?',2);

            if($this->paramInput->cate_id != 0 && $this->paramInput->cate_id != "")
            {
                $this->view->cate_id = $this->paramInput->cate_id;
                $query .= "&cate_id={$this->paramInput->cate_id}";
                $cateIds  = $this->_db->select()
                    ->from(array('o' => 'product_cate'),array('id'))
                    ->where('o.parent_id = ?',$this->paramInput->cate_id)
                    ->where('o.status = ?',1)
                    ->query()
                    ->fetchAll();
                
                 
                if(count($cateIds)<=0)
                {
                    //查询子类cate_id
                    $cateIds  = $this->_db->select()
                        ->from(array('o' => 'product_cate'),array('id'))
                        ->where('o.id = ?',$this->paramInput->cate_id)
                        ->where('o.status = ?',1)
                        ->query()
                        ->fetchAll();
                }
                
                $cate = array();
                foreach ($cateIds as $ids)
                {
                    $cate[] = $ids['id'];
                }
                 
                if(count($cate) > 0)
                {
                    //查询和cate_id关联的商品
                    $productIds = array();
                    $productIds = $this->_db->select()
                    ->from(array('o' => 'product_catedata'),array('product_id','id'))
                    ->where('o.cate_id in (?)',$cate)
                    ->query()
                    ->fetchAll();
                     
                    if($productIds)
                    {
                        $product = array();
                        foreach ($productIds as $ids)
                        {
                            $product[] = $ids['product_id'];
                        }
                         
                        if($product)
                        {
                            $product = array_unique($product);
                            $select->where('p.id in (?)',$product);
                        }
                        else
                        {
                            $select->where('p.id in (?)',"");
                        }
                    }
                    else
                    {
                        $select->where('p.id in (?)',"");
                    }
                }
                else 
                {
                    $select->where('p.id in (?)',"");
                }
          
            }
            
            if($this->paramInput->zonghe != "" )
            {
                $zonghe = 0;
            }
            $this->view->zonghe = $zonghe;
            //去哪儿
            if($this->paramInput->keyWord != "")
            {
                $this->view->keyWord = $this->paramInput->keyWord;
                $select->where("p.product_name like '%{$this->paramInput->keyWord}%' ");
                $query .= "&keyWord={$this->paramInput->keyWord}";
                
            }
            
            //销量
            if($this->paramInput->sort != "")
            {
                $this->view->sort = $this->paramInput->sort;
                $select->order('p.stock desc');
                $this->view->price = "";
                $query .= "&sort={$this->paramInput->sort}";
            }
            
            
            //价格排序
            $this->view->price_view = 0;
            if($this->paramInput->price != "")
            {
                if($this->paramInput->price == 1)
                {
                    $this->view->price = $this->paramInput->price;
                    $this->view->price_view = 0;
                     
                    $select->order('p.price desc');
                    
                    $query .= "&price={$this->paramInput->price}";
                }
                else if($this->paramInput->price == 0)
                {
                    $this->view->price = $this->paramInput->price;
                    $this->view->price_view = 1;
                    $select->order('p.price asc');
                    $query .= "&price={$this->paramInput->price}";
                 
                }
            }
             
            //价格区间
            if($this->paramInput->start_price != "" && $this->paramInput->start_price != 0)
            {
                $this->view->start_price = $this->paramInput->start_price;

                $select->where('p.price >= ?',$this->paramInput->start_price);
                $query .= "&start_price={$this->paramInput->start_price}"; 
            }
             
            //价格区间
            if($this->paramInput->end_price != "" && $this->paramInput->end_price != 0)
            {
                $this->view->end_price = $this->paramInput->end_price;
                 
                $select->where('p.price <= ?',$this->paramInput->end_price);
                $query .= "&end_price={$this->paramInput->end_price}";
            }
            
            //出游时间
            if($this->paramInput->start_time != "" && $this->paramInput->start_time != 0)
            {
                $this->view->start_time = $this->paramInput->start_time;
            
                $select->where('p.travel_date >= ?',strtotime($this->paramInput->start_time));
                $query .= "&start_time={$this->paramInput->start_time}";
            }
             
            //出游时间
            if($this->paramInput->end_time != "" && $this->paramInput->end_time != 0)
            {
                $this->view->end_time = $this->paramInput->end_time;
                 
                $select->where('p.travel_date <= ?',strtotime($this->paramInput->end_time));
                $query .= "&end_time={$this->paramInput->end_time}";
            }
        
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
        
        /* 列表 */
		$results = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->limitPage($page,$perpage)
			->query()
			->fetchAll();

		$productList = array();
		foreach ($results as $result)
		{
		    $product = array();
		    $product['id'] = $result['id'];
		    $product['product_name'] = $result['product_name'];
		    $product['image'] = $result['image'];
		    $product['price'] = $result['price'];
		    $product['cost_price'] = $result['cost_price'];
		    $product['sells'] = $result['sells'];
		    $product['travel_date'] = $result['travel_date'];
		    $product['down_time'] = $result['down_time'];
		    $productList[] = $product;
		}
        
		/* 组装URL */
		$url = $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");
		//echo $url;exit;
		$this->view->self_url = $url;
		$this->view->product_list = $productList;
        $this->view->count = $count; //总条数
        $this->view->pages = $pages; //总页数
        $this->view->page = $page;   //当前页数
        $this->view->prev_page = $prev_page;   //上一页
        $this->view->next_page = $next_page;   //下一页
        $this->view->query = $query;
        $this->view->queryq= $queryq;

    }

	//搜索条件
	protected function _search($kw){
		$search_wds = Core_Cookie::get('search_wds');
		if($search_wds){
			$wds_array = explode(',',$search_wds);
			if(array_search($kw,$wds_array)===false){
				//开头插入搜索关键字
				if(count($wds_array)>26){
				  //最后一个出栈
				  array_pop($wds_array);
				}
				array_unshift($wds_array,$kw);
			}else{
				//重新排序
				foreach($wds_array as $key=>$keyword){
					if($keyword==$kw){
						unset($wds_array[$key]);
					}
				}
				array_unshift($wds_array,$kw);
			}
			$kw_str = implode(',',$wds_array);
			Core_Cookie::set('search_wds',$kw_str);
		}else{
			$kw_str = $kw;
			Core_Cookie::set('search_wds',$kw_str);
		}

		/* 记录搜索关键字 */

		$this->models['search_count'] = new Model_SearchCount();

		$count = $this->_db->select()
			->from(array('c' => 'search_count'))
			->where('c.words = ?',$kw)
			->query()
			->fetch();
		if (!empty($count)) 
		{
			$this->models['search_count']->update(
				array(
					'count' => new Zend_Db_Expr('count + 1'),
					'last_user' => $this->_auth->hasIdentity() ? $this->_user->id : 0),
				array('id = ?' => $count['id'])
			);
		}
		else 
		{
			$this->_rows['search_count'] = $this->models['search_count']->createRow(array(
				'search_item' => 1,
				'words' => $kw,
				'count' => 1,
				'last_user' => $this->_auth->hasIdentity() ? $this->_user->id : 0)
			)->save();
		}
	}
	
	protected function _attr(&$select)
	{
		if ($this->input->a_0 != 0) 
		{
			$select->where('p.a_0 = ?',$this->input->a_0);
		}
		if ($this->input->a_0 != 0) 
		{
			$select->where('p.a_1 = ?',$this->input->a_1);
		}
		if ($this->input->a_0 != 0) 
		{
			$select->where('p.a_2 = ?',$this->input->a_2);
		}
		if ($this->input->a_0 != 0) 
		{
			$select->where('p.a_3 = ?',$this->input->a_3);
		}
		if ($this->input->a_4 != 0) 
		{
			$select->where('p.a_4 = ?',$this->input->a_4);
		}
		if ($this->input->a_5 != 0) 
		{
			$select->where('p.a_5 = ?',$this->input->a_5);
		}
		if ($this->input->a_6 != 0) 
		{
			$select->where('p.a_6 = ?',$this->input->a_6);
		}
		if ($this->input->a_7 != 0) 
		{
			$select->where('p.a_7 = ?',$this->input->a_7);
		}
		if ($this->input->a_8 != 0) 
		{
			$select->where('p.a_8 = ?',$this->input->a_8);
		}
		if ($this->input->a_9 != 0) 
		{
			$select->where('p.a_9 = ?',$this->input->a_9);
		}
		if ($this->input->a_10 != 0) 
		{
			$select->where('p.a_10 = ?',$this->input->a_10);
		}
	}
	
	/**
	 *  产品详情
	 */
	public function detailAction()
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
		
	   $this->view->product_id = $this->paramInput->id;
		
		$select = $this->_db->select()
		  ->from(array('p' => 'product'));
		
		$p = $select->where('p.id = ?',$this->paramInput->id)
    		->query()
    		->fetch();

		if(isMobile())
		{
		    //推荐shangp
		    $select = $this->_db->select()
    		    ->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
    		    ->where('p.parent_id = ?',0)
    		    ->where('p.status = ?',2);
		    
		    // 总数
		    $count = $select->query()
		    ->fetchColumn();
		    	
		    // 随机偏移
		    $offset = mt_rand(0,4);
		    
		    // 数据
		    $results = $select->reset(Zend_Db_Select::COLUMNS)
    		    ->columns('*','p')
    		    ->limit(4,$offset)
    		    ->query()
    		    ->fetchAll();
		    
		    $productList = array();
		    foreach ($results as $result)
		    {
		        $product = array();
		        $product['product_id'] = $result['id'];
		        $product['product_name'] = htmlDecodeCn($result['product_name']);
		        $product['image'] = thumbpath($result['image'],200);
		        $product['price'] = intval($result['price']);
		        $product['brief'] = $result['brief'];
		        $product['clock'] = $result['down_time'] - SCRIPT_TIME;

		        $product['discount'] = $discount;
		        	
		        $productList[] = $product;
		    }
		    $this->view->products = $productList;
		    
		}
		
        //查询商品分类名
        $productCatedata  = $this->_db->select()
            ->from(array('p' => 'product_catedata'))
            ->where('p.product_id = ?',$this->paramInput->id)
            ->query()
            ->fetch();
        
      $cate =   $this->_getcateidbycatenameAction($productCatedata['cate_id']);
        
        $this->view->cate_name = $cate['cate_name'];
        $this->view->cate_id = $cate['cate_id'];

		//查询出发城市
		$city = $this->_db->select()
    		->from(array('o' => 'region'))
    		->where('o.id = ?',$p['origin_id'])
    		->query()
    		->fetch();
		
		$product = array();
		$product['product_id'] = $p['id'];
		$product['area'] = $p['area'];
		$product['product_name'] = htmlDecodeCn($p['product_name']);
		$product['brief'] = $p['brief'];
		$product['price'] = intval($p['price']);
		$product['cost_price'] = intval($p['cost_price']);
		$order=array("\r\n","\n");
		$replace='<br/>';
		$product['features_info']=str_replace($order,$replace,$p['features_info']);   // 产品特色
		$product['features_content'] = $p['features_content']; // 产品特色
		$product['cost_need'] = $p['cost_need'];  //费用需知
		$product['origin'] = $city['region_name'];  //出发城市
		$product['clock'] =$p['down_time']-time();
		$product['sn'] = $p['sn'];
		$product['information'] = $p['information'];
		$product['down_time'] = $p['down_time'];

		$discount =  round(($p['price']/$p['cost_price']),2);
		

		if($discount == 0)
		{
		    $discount = "";
		}
		else if($discount > 1)
		{
		    $discount ="10折";
		}
		else
		{
		    $discount =($discount*10)."折";
		}
		
		$product['discount'] = $discount;   //打折
		
		//行程
		$trip = $this->_db->select()
    		->from(array('o' => 'product_trip'),array('sort','title','info','content','images'))
    		->where('o.product_id = ?',$this->paramInput->id)
    		->where('o.status = ?',1)
    		->order('o.sort asc')
    		->query()
    		->fetchAll();
		
		for($i=0;$i<count($trip);$i++)
		{
    		if($trip[$i]['images'] != "")
    		{
    		    $images = explode(',', $trip[$i]['images']);
    		
                for ($j=0;$j<count($images);$j++)
                {
                   $images[$j] = $images[$j];
                }
    		}
    		else
    		{
    		  $images = array();
    		}
    		
    		$trip[$i]['title'] = htmlDecodeCn($trip[$i]['title']);
    		$trip[$i]['info'] = htmlDecodeCn($trip[$i]['info']);
    		$order=array("\r\n","\n");
    		$replace='<br/>';
    		$trip[$i]['content']=str_replace($order,$replace,$trip[$i]['content']);
    		$trip[$i]['images'] = $images;
		}
		
	    $product['trip'] = $trip;

	    $product['seo_title'] = $p['seo_title'];
	    $product['seo_keywords'] = $p['seo_keywords'];
	    $product['seo_description'] = $p['seo_description'];

	    //查询时候有房间
	    $addon = $this->_db->select()
    	    ->from(array('p' => 'product_addondata'))
    	    ->joinLeft(array('o' => 'product_addon'), 'o.id = p.addon_id')
    	    ->where('o.addon_type = ?',1)
    	    ->where('p.product_id = ?',$this->paramInput->id)
    	    ->where('p.status = ?',1)
    	    ->query()
    	    ->fetchAll();

	    $product['addon'] = array();
	    if(count($addon)>0)
	    {
	        foreach ($addon as $room)
	        {
	            $room['extra']= Zend_Serializer::unserialize($room['extra']);
	            unset($room['id']);
	            unset($room['type']);
	            unset($room['title']);
	            unset($room['info']);
	            $room['room_id'] = $room['addon_id'];
	            unset($room['addon_id']);
	            $roomList[] = $room;
	        }
	        $product['addon'] = $roomList;
	    }
	    else 
	    {
	        $product['addon'] = "";
	    }

	    
	    // 库存
	    $product['stock'] = $p['stock'];
	    
	    $this->view->product = $product;
	    
	    /* 获取商品图片 */
	    
	    $imageResults = $this->_db->select()
    	    ->from(array('i' => 'product_image'))
    	    ->where('i.product_id = ?',$this->paramInput->id)
    	    ->where('i.status = ?',1)
    	    ->order('i.main DESC')
    	    ->order('i.order ASC')
    	    ->query()
    	    ->fetchAll();

	    $list = array();
	    foreach ($imageResults as $result)
	    {
	        $list[]['image'] = $result['image'];
	    }
	    $this->view->product_image = $list;
	    
	    // 提示标签
	    $this->view->tag = array_filter(explode(' ',$p['tag']));
	    
	    /* 生成规格产品对照数组 */
	    
	    $items = array();
	    
	    // 获取产品
	    $childerProduct = $this->_db->select()
	       ->from(array('o' => 'product'))
	       ->where('o.parent_id = ?',$this->paramInput->id)
	       ->where('o.status <> ?',-1)
	       ->where('o.status <> ?',3)
	       ->order('o.travel_date asc')
	       ->query()
	       ->fetch();

	    if(!$childerProduct['id'])
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
	    
	    $this->view->childer_product = $childerProduct;
	    
	    $results = $this->_db->select()
    	    ->from(array('i' => 'product_item'))
    	    ->where('i.product_id = ?',$childerProduct['id'])
    	    ->where('i.stock > ?',0)
    	    ->where('i.status = ?',1)
    	    ->query()
    	    ->fetch();
	    
        $this->view->results = $results;
         
	    /*机票票信息*/

	    //查询飞机票
	    $ticket = $this->_db->select()
    	    ->from(array('o' => 'product_ticket'))
    	    ->joinLeft(array('p' => 'product'), 'p.id = o.product_id',array('travel_date'))
    	    ->where('o.product_id = ?',$results['product_id'])
    	    ->where('o.status = ?',1)
    	    ->where('o.berths <> ?',"")
    	    ->query()
    	    ->fetchAll();
	    
        for($i=0;$i<count($ticket);$i++)
        {
            $ticket[$i]['spend_time'] = $this->time2string($ticket[$i]['return_time']-$ticket[$i]['go_time']);
        }
	    
	    $this->view->ticket_list = $ticket;
	    
	    /* 分享参数 */
	    $this->view->wxTitle = $product['product_name'];
	    $this->view->wxDescription =  $product['brief'];
	    $this->view->wxImage = thumbpath($p['image'],220);
	    
	    /*是否收藏过*/
	    $favorite = $this->_db->select()
	       ->from(array('p' => 'favorite'))
	       ->where('p.dataid = ?',$this->paramInput->id)
	       ->where('p.member_id = ?',$this->_user->id)
	       ->query()
	       ->fetch();
	    
	    if($favorite)
	    {
	        $is_favorite = 1;
	    }
	    else {
	        $is_favorite = 0;
	    }
	    
	    $this->view->is_favorite = $is_favorite;
	    
	    /*seo*/
	    $this->view->headerTitle = "友趣游 - ".$p['seo_title'];
	    $this->view->headerKeywords = $p['seo_keywords']." 友趣游";
	    $this->view->headerDescription =  $p['seo_description'];
	}
	
	
	/**
	 * 根据cate_id 查询父节点id
	 */
	protected  function _getcateidbycatenameAction($cate_id)
	{
	    $cate = $this->_db->select()
    	    ->from(array('p' => 'product_cate'))
    	    ->where('p.id =?',$cate_id)
    	    ->query()
    	    ->fetch();

	    if($cate['parent_id'] == "0")
	    {
	        $data  = array();
	        $data['cate_name'] = $cate['cate_name'];
	        $data['cate_id'] = $cate['id'];
	        return $data;
	    }
	    else 
	    {
	        $catec = $this->_db->select()
    	        ->from(array('p' => 'product_cate'))
    	        ->where('p.id =?',$cate['parent_id'])
    	        ->query()
    	        ->fetch();
	        
	        $data  = array();
	        $data['cate_name'] = $catec['cate_name'];
	        $data['cate_id'] = $catec['id'];
	        return $data;
	    }

	} 

	
	public function ajaxAction()
	{
	    
	    if (!$this->_request->isXmlHttpRequest())
	    {
	        exit ;
	    }
	    
	    $op = $this->_request->getQuery('op','');
	    $json = array();
	    $this->_helper->viewRenderer->setNoRender();
	    
	    if (!ajax($this))
	    {
	        $json['errno'] = '1';
	        $json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($json);
	    }
	    switch ($op)
	    {
	        
	        case 'productlist':
	            
	            $perpage = 20;
	            $select = $this->_db->select()
	            ->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
	            ->where('p.parent_id =?',0)
	            ->where('p.status =  ?',2);
	             
	            if($this->input->tourism_type != 0 && $this->input->tourism_type != "")
	            {
	                  $select->where('p.tourism_type  = ?',$this->input->tourism_type);
	            }
	            //去哪儿
	            if($this->input->keyWord != "")
	            {
	                $this->view->keyWord = $this->input->keyWord;
	                $select->where("p.product_name like '%{$this->input->keyWord}%' ");
	                $query .= "&keyWord={$this->input->keyWord}";
	            }
	             
	            //销量
	            if($this->input->classType != "")
	            {
	                switch ($this->input->calssType)
	                {
	                    case 'recommend':
	                        $select->order('p.order desc');
	                        break;
                        case 'sort':
	                            $select->order('p.stock desc');
	                            break;
	                }
	                
	                if($this->input->classType == 'price')
	                {
	                    if($this->input->classId == 0)
	                    {
	                        $select->order('p.price asc');
	                    }
	                    else if($this->input->classId == 1)
	                    {
	                        $select->order('p.price desc');
	                    }
	                }

	            }
	         
	            // 总数
	            $count = $select->query()
	            ->fetchColumn();
	             
	            $page = $this->input->page ? $this->input->page : 1;
	            $perpage = $this->input->perpage ? $this->input->perpage : 10;
	            //总页数
	            $pages = ceil($count/$perpage);

	            /* 列表 */
	            $results = $select->reset(Zend_Db_Select::COLUMNS)
    	            ->columns('*','p')
    	            ->limitPage($page,$perpage)
    	            ->query()
    	            ->fetchAll();
	             
	            $productList = array();
	            foreach ($results as $result)
	            {
	                $product = array();
	                $product['id'] = $result['id'];
	                $product['product_name'] = $result['product_name'];
	                $product['image'] = $result['image'];
	                $product['price'] = intval($result['price']);
	                $product['mktprice'] = intval($result['cost_price']);
	                $product['sells'] = $result['sells'];
	                $product['travel_date'] = $result['travel_date'];
	                $product['down_time'] = $result['down_time'];
	                $product['clock'] =$result['down_time']-time();
	                $discount =  round(($result['price']/$result['cost_price']),2);
	                if($discount == 0)
	                {
	                    $discount = "";
	                }
	                else if($discount > 1)
	                {
	                    $discount ="10";
	                }
	                else
	                {
	                    $discount =($discount*10);
	                }
	                $product['discount'] = $discount;
	            
	                $productList[] = $product;
	            }

        	    $this->json['errno'] = '0';
        	    $this->json['page'] = intval($page);
        	    $this->json['product_list'] = $productList;
        	    $this->_helper->json($this->json);

	            break;
	        
	        case 'product_items':
	            
	            $product = array();
	            
	            //查询该商品的子产品
	            $childer_product = $this->_db->select()
    	            ->from(array('o' => 'product'),array('travel_date','id as product_id','price','child_price'))
    	            ->where('o.parent_id = ?',$this->input->id)
        	       ->where('o.status <> ?',-1)
	               ->where('o.status <> ?',3)
    	            ->order('o.travel_date asc')
    	            ->query()
    	            ->fetchAll();
	            
	            $m = 0;
	            $k = 0;
	            for ($i=0;$i<count($childer_product);$i++)
	            {
	               $date = date("Y-m-d",$childer_product[$i]['travel_date']);
	                if($i == 0)
    	            {
    	               $year_month=date("Y-m",$childer_product[$i]['travel_date']);
    	            }
    	            else
    	            {
                        $year_month=date("Y-m",$childer_product[$i-1]['travel_date']);
                        $year_month2=date("Y-m",$childer_product[$i]['travel_date']);
                        if(strtotime($year_month) != strtotime($year_month2))
                        {
                            $year_month = $year_month2;
                        }
	               }
	            
    	            $product_item = $this->_db->select()
    	               ->from(array('o' => 'product_item'),array('price','child_price','product_id','stock'))
                        ->where('o.product_id = ?',$childer_product[$i]['product_id'])
                        ->where('o.status = ?',1)
                        ->order('o.price asc')
                        ->query()
                        ->fetchAll();
            
                    $type = 0;
        	        if(count($product_item) > 1)
        	        {
                        //是否有多规格
                        $type = 1;
                    }
                    if($product[$k-1]['year_month'] == $year_month && $i > 0)
                    {
                        $product[$k-1]['year_month_days'][$m+1]['year_month_day'] = $date;
                        $product[$k-1]['year_month_days'][$m+1]['price'] = intval($product_item[0]['price']);
                        $product[$k-1]['year_month_days'][$m+1]['child_price'] = intval($product_item[0]['child_price']);
                        $product[$k-1]['year_month_days'][$m+1]['id'] =  $product_item[0]['product_id'];
                        $product[$k-1]['year_month_days'][$m+1]['stock'] =  intval($product_item[0]['stock']);
                        $product[$k-1]['year_month_days'][$m+1]['is_multi'] =  $type;
                        $product[$k-1]['year_month_days'][$m+1]['cate'] =  0;  //0 要和人数一直 1多余无所谓
                        $m++;
                    }
                    else
                    {
                        $product[$k]['year_month'] = $year_month;
                        $product[$k]['year_month_days'][$m]['year_month_day'] = $date;
                        $product[$k]['year_month_days'][$m]['price'] = intval($product_item[0]['price']);
                        $product[$k]['year_month_days'][$m]['child_price'] =  intval($product_item[0]['child_price']);
                        $product[$k]['year_month_days'][$m]['id'] = $product_item[0]['product_id'];
                        $product[$k]['year_month_days'][$m]['stock'] =  intval($product_item[0]['stock']);
                        $product[$k]['year_month_days'][$m]['is_multi'] = $type;
                        $product[$k]['year_month_days'][$m]['cate'] =  0;  //0 要和人数一致 1多余无所谓
                        $k++;
                        $m++;
                    }
                }
    	            
                for ($j=0;$j<count($product);$j++)
                {
                    $data[$j]['year_month'] = $product[$j]['year_month'];
                    $row = array_values($product[$j]['year_month_days']);
                    $data[$j]['year_month_days'] = $row;
                }

                
                $this->json['items']=array_values($data);
                $this->json['errno'] = '0';
                $this->_helper->json($this->json);
    	            break;
    	            
	            case 'product_ticket':
	                
	                //查询行程
	                $ticket = $this->_db->select()
    	                ->from(array('o' => 'product_ticket'))
    	                ->joinLeft(array('p' => 'product'), 'p.id = o.product_id',array('travel_date'))
    	                ->where('o.product_id = ?',$this->input->id)
    	                ->where('o.status = ?',1)
    	                ->query()
    	                ->fetchAll();
	                
	                //处理数据
	                for($i=0;$i<count($ticket);$i++)
	                {
	                    $ticket[$i]['spend_time'] = $this->time2string($ticket[$i]['return_time']-$ticket[$i]['go_time']);
	                    $ticket[$i]['time'] = date("Y-m-d",$ticket[$i]['time']);
	                    $ticket[$i]['go_time'] = date("H:i",$ticket[$i]['go_time']);
	                    $ticket[$i]['return_time'] = date("H:i",$ticket[$i]['return_time']);
	                }
	                
	                $this->json['errno'] = '0';
	                $this->json['ticket'] = $ticket;
	                $this->_helper->json($this->json);
	                
	                break;
	                
	            case 'product_list':

            	    $perpage = 20;
            	    $select = $this->_db->select()
            	    ->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
            	    ->where('p.parent_id =?',0)
            	    ->where('p.status =  ?',2);
            	     
            	    if($this->paramInput->cate_id != 0 && $this->paramInput->cate_id != "")
            	    {
            	        $this->view->cate_id = $this->paramInput->cate_id;
            	        $query .= "&cate_id={$this->paramInput->cate_id}";
            	        $cateIds  = $this->_db->select()
            	        ->from(array('o' => 'product_cate'),array('id'))
            	        ->where('o.parent_id = ?',$this->paramInput->cate_id)
            	        ->where('o.status = ?',1)
            	        ->query()
            	        ->fetchAll();
            	         
            	    
            	        if(count($cateIds)<=0)
            	        {
            	            //查询子类cate_id
            	            $cateIds  = $this->_db->select()
            	            ->from(array('o' => 'product_cate'),array('id'))
            	            ->where('o.id = ?',$this->paramInput->cate_id)
            	            ->where('o.status = ?',1)
            	            ->query()
            	            ->fetchAll();
            	        }
            	         
            	        $cate = array();
            	        foreach ($cateIds as $ids)
            	        {
            	            $cate[] = $ids['id'];
            	        }
            	    
            	        //查询和cate_id关联的商品
            	        $productIds = array();
            	        $productIds = $this->_db->select()
            	        ->from(array('o' => 'product_catedata'),array('product_id','id'))
            	        ->where('o.cate_id in (?)',$cate)
            	        ->query()
            	        ->fetchAll();
            	    
            	        if($productIds)
            	        {
            	            $product = array();
            	            foreach ($productIds as $ids)
            	            {
            	                $product[] = $ids['product_id'];
            	            }
            	    
            	            if($product)
            	            {
            	                $product = array_unique($product);
            	                $select->where('p.id in (?)',$product);
            	            }
            	            else
            	            {
            	                $select->where('p.id in (?)',"");
            	            }
            	        }
            	        else
            	        {
            	            $select->where('p.id in (?)',"");
            	        }
            	         
            	         
            	    }
            	     
            	    if($this->paramInput->zonghe != "" )
            	    {
            	        $zonghe = 0;
            	    }
            	    $this->view->zonghe = $zonghe;
            	    //去哪儿
            	    if($this->paramInput->keyWord != "")
            	    {
            	        $this->view->keyWord = $this->paramInput->keyWord;
            	        $select->where("p.product_name like '%{$this->paramInput->keyWord}%' ");
            	        $query .= "&keyWord={$this->paramInput->keyWord}";
            	         
            	    }
            	     
            	    //销量
            	    if($this->paramInput->sort != "")
            	    {
            	        $this->view->sort = $this->paramInput->sort;
            	        $select->order('p.stock desc');
            	        $this->view->price = "";
            	        $query .= "&sort={$this->paramInput->sort}";
            	    }
            	     
            	     
            	    //价格排序
            	    $this->view->price_view = 0;
            	    if($this->paramInput->price != "")
            	    {
            	        if($this->paramInput->price == 1)
            	        {
            	            $this->view->price = $this->paramInput->price;
            	            $this->view->price_view = 0;
            	    
            	            $select->order('p.price desc');
            	             
            	            $query .= "&price={$this->paramInput->price}";
            	        }
            	        else if($this->paramInput->price == 0)
            	        {
            	            $this->view->price = $this->paramInput->price;
            	            $this->view->price_view = 1;
            	            $select->order('p.price asc');
            	            $query .= "&price={$this->paramInput->price}";
            	    
            	        }
            	    }
            	    
            	    //价格区间
            	    if($this->paramInput->start_price != "" && $this->paramInput->start_price != 0)
            	    {
            	        $this->view->start_price = $this->paramInput->start_price;
            	         
            	        $select->where('p.price >= ?',$this->paramInput->start_price);
            	        $query .= "&start_price={$this->paramInput->start_price}";
            	    }
            	    
            	    //价格区间
            	    if($this->paramInput->end_price != "" && $this->paramInput->end_price != 0)
            	    {
            	        $this->view->end_price = $this->paramInput->end_price;
            	    
            	        $select->where('p.price <= ?',$this->paramInput->end_price);
            	        $query .= "&end_price={$this->paramInput->end_price}";
            	    }
            	     
            	    //出游时间
            	    if($this->paramInput->start_time != "" && $this->paramInput->start_time != 0)
            	    {
            	        $this->view->start_time = $this->paramInput->start_time;
            	         
            	        $select->where('p.travel_date >= ?',strtotime($this->paramInput->start_time));
            	        $query .= "&start_time={$this->paramInput->start_time}";
            	    }
            	    
            	    //出游时间
            	    if($this->paramInput->end_time != "" && $this->paramInput->end_time != 0)
            	    {
            	        $this->view->end_time = $this->paramInput->end_time;
            	    
            	        $select->where('p.travel_date <= ?',strtotime($this->paramInput->end_time));
            	        $query .= "&end_time={$this->paramInput->end_time}";
            	    }
            	     
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
            	     
            	    /* 列表 */
            	    $results = $select->reset(Zend_Db_Select::COLUMNS)
            	    ->columns('*','p')
            	    ->limitPage($page,$perpage)
            	    ->query()
            	    ->fetchAll();
            	     
            	    $productList = array();
            	    foreach ($results as $result)
            	    {
            	        $product = array();
            	        $product['id'] = $result['id'];
            	        $product['product_name'] = $result['product_name'];
            	        $product['image'] = thumbpath($result['image'],200);
            	        $product['price'] = intval($result['price']);
            	        $product['cost_price'] = intval($result['cost_price']);
            	        $product['sells'] = $result['sells'];
            	        $product['travel_date'] = $result['travel_date'];
            	        $product['down_time'] = $result['down_time'];
            	        $product['clock'] =$result['down_time']-time();
            	        $discount =  round(($result['price']/$result['cost_price']),2);
            	        if($discount == 0)
            	        {
            	            $discount = "";
            	        }
            	        else if($discount > 1)
            	        {
            	            $discount ="10";
            	        }
            	        else
            	        {
            	            $discount =($discount*10);
            	        }
            	        $product['discount'] = $discount;
            	        
            	        $productList[] = $product;
            	    }
            	    $callback = "youquyou";
            	    
            	    $data['errno'] = 0;
            	    $data['page'] = $next_page;
            	    $data['product_list'] = $productList;
            // 	    $this->json['errno'] = '0';
            // 	    $this->json['page'] = $next_page;
            // 	    $this->json['product_list'] = $productList;
            // 	    $this->_helper->json($this->json);
            
            	    $json = json_encode($data);
            	    echo  $callback."($json)";
            	    die();
                
	                break;
	       }
	       

	}
	
	function time2string($second){
	    $day = floor($second/(3600*24));
	    $second = $second%(3600*24);//除去整天之后剩余的时间
	    $hour = floor($second/3600);
	    $second = $second%3600;//除去整小时之后剩余的时间
	    $minute = floor($second/60);
	    $second = $second%60;//除去整分钟之后剩余的时间
	    //返回字符串
	    return $day.'天'.$hour.'小时'.$minute;
	}

	/**
	 *  商品图文详情
	 */
	public function graphicdetailsAction()
	{
		if (!form($this)) 
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
		/* 获取商品信息 */
		
		$this->json['product'] = array();
		
		$select = $this->_db->select()
			->from(array('p' => 'product'));
		
		$result = $select->where('p.id = ?',$this->input->id)
			->query()
			->fetch();
		$r = $this->models['product']->createRow($result)->toArray();
		$this->view->product = $r;
	}
	
	/**
	 *  推荐商品
	 */
	public function recommendAction()
	{
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/',
					'text' => '返回首页'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		
		/* 获取商品列表 */
		
		$this->json['product_list'] = array();
		
		$select = $this->_db->select()
			->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('p.area = ?',1)
			->where('p.status = ?',2);
		
		// 总数
		$count = $select->query()
			->fetchColumn();
			
		// 随机偏移
		$offset = mt_rand(0,$count-$this->input->num);
		
		// 数据
		$results = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->limit($this->input->num,$offset)
			->query()
			->fetchAll();
		
		$productList = array();
		foreach ($results as $result) 
		{
			$product = array();
			$product['id'] = $result['id'];
			$product['product_name'] = $result['product_name'];
			$product['image'] = thumbpath($result['image'],220);
			$product['price'] = "¥{$result['price_range']}";
			$product['mktprice'] = $result['mktprice'];
			$product['sells'] = $result['sells'];
			$product['clock'] = $result['down_time'] - SCRIPT_TIME;
			$productList[] = $product;
		}
		$this->json['product_list'] = $productList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 * 产品特色详情
	 */
	public function  featuresAction()
	{
	    if (!params($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    $travel = $this->_db->select()
	    ->from(array('o' => 'product'),array('features_content'))
	    ->where('o.id = ?',$this->paramInput->id)
	    ->query()
	    ->fetch();
	
	    $this->view->features = $travel['features_content'];
	}
	
	public function travelAction()
	{
	    if (!params($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    $travel = $this->_db->select()
    	    ->from(array('o' => 'product'),array('travel_restrictions'))
    	    ->where('o.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();

	    $this->view->travel = $travel['travel_restrictions'];
	}
	/**
	 * 购买需知
	 */
	public function costneedAction()
	{
	    if (!params($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    
	    $costneed = $this->_db->select()
    	    ->from(array('o' => 'product'),array('cost_need'))
    	    ->where('o.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();
	    
	    $this->view->costneed = $costneed['cost_need'];
	}
	
	/**
	 * 协议
	 */
	public function agreementAction()
	{
	    
	}

	/***
	 * 商品搜索
	 */
	public function searchAction()
	{
	}
}

?>