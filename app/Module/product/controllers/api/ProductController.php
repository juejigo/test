<?php

class Productapi_ProductController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product'] = new Model_Product();
		$this->models['product_cate'] = new Model_ProductCate();
		$this->models['product_type'] = new Model_ProductType();
	}
	
	/**
	 *  产品列表
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		    /* 获取商品列表 */
		    
		    $this->json['product_list'] = array();
		    
		    if (!empty($this->input->tag_id))
		    {
		        $select = $this->_db->select()
		        ->from(array('d' => 'product_tagdata'))
		        ->join(array('p' => 'product'),'p.id = d.product_id')
		        ->where('d.tag_id = ?',$this->input->tag_id);
		    }
		    else
		    {
		        $select = $this->_db->select()
		        ->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
		        ->joinLeft(array('c' => 'product_catedata'),'c.product_id = p.id')
		        ->where('p.parent_id = ?',0)
		        ->where('p.down_time  >= ?',time());
		    }
		    
		    $select->where('p.status = ?',2);
		    
		    /* 分类 */
		    
		    if (!empty($this->input->cate_id))
		    {
		        $cateIds = array($this->input->cate_id);
		        $this->rows['product_cate'] = $this->models['product_cate']->find($this->input->cate_id)->current();
		        $children = $this->rows['product_cate']->getAllChildren();
		        if (!empty($children))
		        {
		            foreach ($children as $child)
		            {
		                $cateIds[] = $child['id'];
		            }
		        }
		        	
		        $select->where('c.cate_id IN (?)',$cateIds);
		    }
		    
		    if (!empty($this->input->price_from))
		    {
		        $select->where("p.price >= ?",$this->input->price_from);
		    }
		    
		    if (!empty($this->input->price_to))
		    {
		        $select->where("p.price <= ?",$this->input->price_to);
		    }
		    
		    if($this->input->keyword != "")
		    {
		        $select->where("p.product_name like '%{$this->input->keyword}%'");
		    }
		    
		    if($this->input->tourism_type != '0' && !empty($this->input->tourism_type))
		    {
		        $select->where("p.tourism_type = ?",$this->input->tourism_type);
		    }
		    // 属性
		    $this->_attr($select);
		    
		    /* 排序 */
		    
		    switch ($this->input->sort)
		    {
		        case 'priced':
		            $select->order('p.price DESC');
		            break;
		        case 'pricea':
		            $select->order('p.price ASC');
		            break;
		    }
		    
		    $select->group('p.id');
		    
		    // 总数
		    $count = $select->query()
		    ->fetchColumn();
		    
		    // 数据
		    $results = $select->reset(Zend_Db_Select::COLUMNS)
		    ->columns('*','p')
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
		        $product['price'] = intPrice($result['price']);
		        $product['mktprice'] = intPrice($result['cost_price']);
		        $product['travel_date'] = strtotime($result['travel_date']);
		        $product['discount'] =intPrice($result['cost_price'] - $result['price']);
		        $product['clock'] =$result['down_time']-time();
		        $product['recommended'] = 0;    // 推荐
		        $product['brief'] = $result['brief'];
		        $product['tourism_type'] = $result['tourism_type'];
		        
		        //查询时候有房间
		        $addon = $this->_db->select()
    		        ->from(array('p' => 'product_addondata'))
    		        ->joinLeft(array('o' => 'product_addon'), 'o.id = p.addon_id')
    		        ->where('o.addon_type = ?',1)
    		        ->where('p.product_id = ?',$product['product_id'])
    		        ->where('p.status = ?',1)
    		        ->query()
    		        ->fetchAll();
		        $product['is_cruise'] = count($addon)>0 ? 1 : 0;
		        
		        //查询出发城市
		        $city = $this->_db->select()
		        ->from(array('o' => 'region'))
		        ->where('o.id = ?',$result['origin_id'])
		        ->query()
		        ->fetch();
		        $product['origin'] = $city['region_name'];  //出发城市
		        $productList[] = $product;
		    }
		    $this->json['product_list'] = $productList;
		    
		    $this->json['errno'] = '0';
		    $this->_helper->json($this->json);
	}
	
	protected function _attr(&$select)
	{
		if ($this->input->a_0 !== '') 
		{
			$select->where('p.a_0 = ?',$this->input->a_0);
		}
		if ($this->input->a_1 !== '') 
		{
			$select->where('p.a_1 = ?',$this->input->a_1);
		}
		if ($this->input->a_2 !== '') 
		{
			$select->where('p.a_2 = ?',$this->input->a_2);
		}
		if ($this->input->a_3 !== '') 
		{
			$select->where('p.a_3 = ?',$this->input->a_3);
		}
		if ($this->input->a_4 !== '') 
		{
			$select->where('p.a_4 = ?',$this->input->a_4);
		}
		if ($this->input->a_5 !== '') 
		{
			$select->where('p.a_5 = ?',$this->input->a_5);
		}
		if ($this->input->a_6 !== '') 
		{
			$select->where('p.a_6 = ?',$this->input->a_6);
		}
		if ($this->input->a_7 !== '') 
		{
			$select->where('p.a_7 = ?',$this->input->a_7);
		}
		if ($this->input->a_8 !== '') 
		{
			$select->where('p.a_8 = ?',$this->input->a_8);
		}
		if ($this->input->a_9 !== '') 
		{
			$select->where('p.a_9 = ?',$this->input->a_9);
		}
		if ($this->input->a_10 !== '') 
		{
			$select->where('p.a_10 = ?',$this->input->a_10);
		}
	}
	
	/**
	 *  产品详情
	 */
	public function detailAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}

		/* 获取商品信息 */
		
		$this->json['product'] = array();
		
		$select = $this->_db->select()
			->from(array('p' => 'product'));
		
		$p = $select->where('p.id = ?',$this->input->id)
			->query()
			->fetch();
		
	   //查询出发城市
		$city = $this->_db->select()
    		->from(array('o' => 'region'))
    		->where('o.id = ?',$p['origin_id'])
    		->query()
    		->fetch();

		//查询时候有房间
		$addon = $this->_db->select()
		      ->from(array('p' => 'product_addondata'))
		      ->joinLeft(array('o' => 'product_addon'), 'o.id = p.addon_id')
		      ->where('o.addon_type = ?',1)
		      ->where('p.product_id = ?',$this->input->id)
		      ->where('p.status = ?',1)
		      ->query()
		      ->fetchAll();

        $is_cruise = count($addon)>0 ? 1 :0;

		$product = array();
		$product['product_id'] = $p['id'];
		$product['area'] = $p['area'];
		$product['is_cruise'] = $is_cruise;
		$product['product_name'] = htmlDecodeCn($p['product_name']);
		$product['price'] = intPrice($p['price']);
		$product['child_price'] = intPrice($p['child_price']);
		$product['mktprice'] = intPrice($p['cost_price']);
		$product['features']['features_info'] = $p['features_info'];    // 产品特色
		$product['features']['features_content'] = DOMAIN . "product/product/features?id=".$p['id'];    // 产品特色
		$product['cost_need'] = DOMAIN . "product/product/costneed?id=".$p['id'];   //费用需知
		$product['origin'] = $city['region_name'];  //出发城市
		$product['clock'] =$p['down_time']-time();
		$product['sn'] = $p['sn'];
		$product['information'] = $p['information'];
		
		$tourism_type = array(
		    array('id' => 1,'tourism_type' => '跟团游'),
		    array('id' => 2,'tourism_type' => '自助游'),
		    array('id' => 3,'tourism_type' => '自由行'),
		    array('id' => 4,'tourism_type' => '自驾游'),
		    array('id' => 5,'tourism_type' => '目的地服务'),
		);
		
		foreach ($tourism_type as $row)
		{
		    if($row['id'] == $p['tourism_type'])
		    {
		        $product['tourism_type'] = $row['tourism_type'];
		    }
		}

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
    		->where('o.product_id = ?',$this->input->id)
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
            $trip[$i]['content'] = htmlDecodeCn($trip[$i]['content']);
            
            $trip[$i]['images'] = $images;
        }
		
		$product['trip'] = $trip;
		
		//查询该商品的子产品
// 		$childer_product = $this->_db->select()
//             ->from(array('o' => 'product'),array('travel_date','id as product_id'))
//             ->where('o.parent_id = ?',$this->input->id)
//             ->order('o.travel_date asc')
//             ->query()
//             ->fetchAll();
		
// 		for ($i=0;$i<count($childer_product);$i++)
// 		{
// 		    $product_item = $this->_db->select()
// 		          ->from(array('o' => 'product_item'),array('id as item_id','item_name','price','stock','spec_desc'))
// 		          ->where('o.product_id = ?',$childer_product[$i]['product_id'])
// 		          ->query()
// 		          ->fetchAll();
		    
// 		    for ($j=0;$j<count($product_item);$j++)
// 		    {
// 		        $product_item[$j]['item_name'] = $product_item[$j]['spec_desc'];
// 		        $product_item[$j]['stock'] = intval($product_item[$j]['stock']);
// 		        unset($product_item[$j]['spec_desc']);
// 		    }
		    
// 		    $childer_product[$i]['product_item']  = $product_item;
// 		}
		
// 		$product['children'] = $childer_product;
		

		// 解决图文详情图片宽度自适应屏幕
// 		$product['detail'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
// 		<html xmlns="http://www.w3.org/1999/xhtml"><head><meta content="width=device-width,height=device-height,initial-scale=1,user-scalable=0" name="viewport">
// 		<script src="' . DOMAIN . 'static/style/default/js/lib/jquery-1.9.1.min.js"></script>
// 		<script>$(function()
// 		{
// 			var as = setInterval(\'autosize()\',1);
// 		})
// 		function autosize()
// 		{
// 			$(\'img\').each(function (i,e) 
// 			{
// 				var iwidth = $(e).width();
// 				var windowWidth = $(window).width();
// 				var twidth = windowWidth - 40;
				
// 				if (iwidth > twidth)
// 				{
// 					$(e).css(\'width\',twidth + \'px\');
// 				}
// 			});
// 		}</script></head><body>' . htmlspecialchars_decode(stripcslashes($p['detail'])) .'</body></html>';
		
		$product['seo_title'] = $p['seo_title'];
		$product['seo_keywords'] = $p['seo_keywords'];
		$product['seo_description'] = $p['seo_description'];
		/*商家数组*/
		$product['supplier'] = array(
				"service_mobile" => "13587630625",
		);
		if(in_array(date("w"),array(1,2,3,4,5)) && date("Hi") > 0830 && date("Hi") < 1700)
		{
			$product['supplier'] = array(
			    "service_mobile" => "4006-117-121",
			);
		}
		// 库存
		$product['stock'] = $p['stock'];
		
		$this->json['product'] = $product;
		
		/* 获取商品图片 */
		
		$this->json['product']['image'] = array();
		
		$imageResults = $this->_db->select()
			->from(array('i' => 'product_image'))
			->where('i.product_id = ?',$this->input->id)
			->where('i.status = ?',1)
			->order('i.main DESC')
			->order('i.order ASC')
			->query()
			->fetchAll();
		$list = array();
		foreach ($imageResults as $result) 
		{
			$list[] = $result['image'];
		}
		$this->json['product']['image'] = $list;
		
		// 提示标签
		$this->json['tag'] = array_filter(explode(' ',$p['tag']));
		
		/* 生成规格产品对照数组 */
		
		$items = array();
		
		// 获取产品
		$results = $this->_db->select()
			->from(array('i' => 'product_item'))
			->where('i.product_id = ?',$this->input->id)
			->where('i.stock > ?',0)
			->where('i.status = ?',1)
			->query()
			->fetchAll();
		
		/* 是否收藏 */
		
		$isFavorite = 0;
		if ($this->_user->isLogin()) 
		{
			$count = $this->_db->select()
				->from(array('f' => 'favorite'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('f.member_id = ?',$this->_user->id)
				->where('f.type = ?',0)
				->where('f.dataid = ?',$this->input->id)
				->query()
				->fetchColumn();
			if ($count > 0) 
			{
				$isFavorite = 1;
			}
		}
		$this->json['is_favorite'] = $isFavorite;
		
		/* 评论 */
		
		$rs = $this->_db->select()
			->from(array('f' => 'product_feedback'),array('member_id','product_id','member_name' => 'account','avatar','grade','content','dateline'))
			->where('f.product_id = ?',$this->input->id)
			->limit(5,0)
			->query()
			->fetchAll();
		$this->json['feedbacks'] = !empty($rs) ? $rs : array();
		
		/* 活动 */
		
		$activities = array();
		$this->json['activities'] = $activities;
		
		/* 分享参数 */
		
		$share = array();
		$r = $this->_user->isLogin() ? base64_encode($this->_user->openid) : '';
		$share['title'] = $product['product_name'];
		$share['content'] = $product['product_name'];
		$share['image'] = thumbpath($imageResults[0]['image'],220);
		$share['url'] = DOMAIN ."product/product/detail?id={$product['product_id']}&r={$r}";
		$this->json['share'] = $share;
		
		$this->json['errno'] = 0;
		$this->_helper->json($this->json);
	}
	
	/**
	 * 商品item
	 */
	public function detailitemsAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }

	    $product = array();
	     
	    //查询该商品的子产品
	    $childer_product = $this->_db->select()
    	    ->from(array('o' => 'product'),array('travel_date','id as product_id','price','child_price'))
    	    ->where('o.parent_id = ?',$this->input->id)
    	    ->where('o.status = ?',2)
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
	            ->joinLeft(array('i' => 'product'), 'o.product_id = i.id',array('discount_information'))
	            ->where('o.product_id = ?',$childer_product[$i]['product_id'])
	            ->order('o.price asc')
	            ->query()
	            ->fetchAll();

	        $type = 0;
	        if(count($product_item) > 1)
	        {
	           //是否有多规格
	            $type = 1;
	        }
	        
	        //预定需知
	        $reserveDescription = "预订须知：
1、由于尾单产品的特殊性和时效性，尾单一旦交易成功，就不再支持退、改、换等业务，给您带来不便敬请谅解。
2、由于旅游尾单的特殊性，价格可能还会随着时间的临近而降低。当您购买到到心仪产品后，请冷静面对之后可能出现的降价问题。
3、出行前3-5个工作日您将会收到《出团通知书》或领队的电话号码，如未收到，请及时与客服取得联系，联系电话：4006117121。
4、请在导游约定的时间内到约定的地点集合，切勿迟到，以免耽误行程。若因迟到耽误行程，后果自负，敬请谅解。
5、倘若旅行社或旅游者提出退改申请，造成的一切经济损失需按旅游合同进行赔偿。
	        ";
	        
	        //儿童说明
	        $childrenDescription = "儿童价格标准：身高0.8~1.2米（含），不占床，只包含座位费，其余产生费用自理";
	        
            if($product[$k-1]['year_month'] == $year_month && $i > 0)
            {
	            $product[$k-1]['year_month_days'][$m+1]['year_month_day'] = $date;
	            $product[$k-1]['year_month_days'][$m+1]['price'] = "¥".intval($product_item[0]['price']);
	            $product[$k-1]['year_month_days'][$m+1]['child_price'] = "¥".intval($product_item[0]['child_price']);
	            $product[$k-1]['year_month_days'][$m+1]['id'] =  $product_item[0]['product_id'];
	            $product[$k-1]['year_month_days'][$m+1]['stock'] =  intval($product_item[0]['stock']);
	            $product[$k-1]['year_month_days'][$m+1]['reserve_description'] =  $reserveDescription;
	            
	            if($product_item[0]['discount_information'] != "")
	            {
	                $product[$k-1]['year_month_days'][$m+1]['has_discount'] = 1;
	                $product[$k-1]['year_month_days'][$m+1]['discount_information'] =  $product_item[0]['discount_information'];
	            }
	            else 
	            {
	                $product[$k-1]['year_month_days'][$m+1]['has_discount'] = 0;
	                $product[$k-1]['year_month_days'][$m+1]['discount_information'] = "";
	            }
	            
	            $product[$k-1]['year_month_days'][$m+1]['children_description'] =  $childrenDescription;
	            $product[$k-1]['year_month_days'][$m+1]['is_multi'] =  $type;
	            $product[$k-1]['year_month_days'][$m+1]['cate'] =  0;  //0 要和人数一直 1多余无所谓
	            
	            $m++;
    	    }
    	    else
    	    {
        	    $product[$k]['year_month'] = $year_month;
        	    $product[$k]['year_month_days'][$m]['year_month_day'] = $date;
        	    $product[$k]['year_month_days'][$m]['price'] = "¥".intval($product_item[0]['price']);
        	    $product[$k]['year_month_days'][$m]['child_price'] =  "¥".intval($product_item[0]['child_price']);
        	    $product[$k]['year_month_days'][$m]['id'] = $product_item[0]['product_id'];
        	    $product[$k]['year_month_days'][$m]['stock'] =  intval($product_item[0]['stock']);
        	    $product[$k]['year_month_days'][$m]['reserve_description'] =  $reserveDescription;
        	    
        	    if($product_item[0]['discount_information'] != "")
        	    {
        	        $product[$k]['year_month_days'][$m]['has_discount'] = 1;
        	        $product[$k]['year_month_days'][$m]['discount_information'] =  $product_item[0]['discount_information'];
        	    }
        	    else
        	    {
        	        $product[$k]['year_month_days'][$m]['has_discount'] = 0;
        	        $product[$k]['year_month_days'][$m]['discount_information'] = "";
        	    }
        	    
        	    $product[$k]['year_month_days'][$m]['children_description'] =  $childrenDescription;
        	    $product[$k]['year_month_days'][$m]['is_multi'] = $type;
        	    $product[$k]['year_month_days'][$m]['cate'] =  0;  //0 要和人数一致 1多余无所谓

        	    $k++;
        	    $m++;
    	    }

	    }
	
	    $data = array();
	    for ($j=0;$j<count($product);$j++)
	    {
    	    $data[$j]['year_month'] = $product[$j]['year_month'];
    	    $row = array_values($product[$j]['year_month_days']);
    	    $data[$j]['year_month_days'] = $row;
		}
	
        $this->json['items']=array_values($data);
        $this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  推荐商品
	 */
	public function recommendAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 获取商品列表 */
		
		$this->json['product_list'] = array();
		
		$select = $this->_db->select()
			->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
			//->where('p.area = ?',1)
			->where('p.parent_id = ?',0)
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
			$product['product_id'] = $result['id'];
			$product['product_name'] = htmlDecodeCn($result['product_name']);
			$product['image'] = thumbpath($result['image'],220);
			$product['price'] = intval($result['price']);
			$product['brief'] = $result['brief'];
		//	$product['mktprice'] = $result['mktprice'];
		//	$product['sells'] = $result['sells'];
			$product['clock'] = $result['down_time'] - SCRIPT_TIME;
			
			$discount =  round(($result['price']/$result['mktprice']),2);
			if($discount == 0)
			{
			    $discount = "";
			}
			else
			{
			    $discount =($discount*10)."折";
			}
			$product['discount'] = $discount;
			
			$productList[] = $product;
		}
		$this->json['product_list'] = $productList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	public function ticketAction()
	{
        if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    
	    //查询行程
	    $ticket = $this->_db->select()
    	    ->from(array('o' => 'product_ticket'))
    	    ->where('o.product_id = ?',$this->input->id)
    	    ->where('o.status = ?',1)
    	    ->query()
    	    ->fetchAll();
	    
	    $this->json['errno'] = '0';
	    $this->json['ticket_list'] = $ticket;
	    $this->_helper->json($this->json);
	}
	
	
	/**
	 * 预定需知
	 */
	public function reserveAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    
	    $reserve = array();
	    
	    $reserve = array(
	        array('title' => '出游人数限制','url' =>  DOMAIN . "product/product/travel?id=".$this->input->id),   
	        array('title' => '友趣游服务使用协议', 'url' => DOMAIN . "product/product/agreement"),
	    );
	    
	    $this->json['errno'] = '0';
        $this->json['reserve'] = $reserve;
	    $this->_helper->json($this->json);
	}
}

?>