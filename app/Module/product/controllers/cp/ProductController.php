<?php

class Productcp_ProductController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['product'] = new Model_Product();
		$this->models['product_item'] = new Model_ProductItem();
		$this->models['product_type'] = new Model_ProductType();
		$this->models['product_tagdata'] = new Model_ProductTagdata();
		$this->models['product_catedata'] = new Model_ProductCatedata();
		$this->models['product_cate'] = new Model_ProductCate();
		$this->models['product_addon'] = new Model_ProductAddon();
		$this->models['product_addondata'] = new Model_ProductAddondata();
		$this->models['product_trip'] = new Model_ProductTrip();
		$this->models['product_ticket'] = new Model_ProductTicket();
		$this->models['product_visadata'] = new Model_ProductVisadata();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/productcp/product/list');
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
			->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('p.parent_id =?',0)
			->where('p.status !=  ?',-1)
  			->where('p.area = ?',$this->paramInput->area);
		
		$query = '/productcp/product/list?page={page}';
		$query .= "&area={$this->paramInput->area}";
		
		if (!empty($this->paramInput->status))
		{
		    if($this->paramInput->status != 0 && $this->paramInput->status != "")
		    {
		          $this->view->status = $this->paramInput->status;
		        
		        $select->where('p.status = ?',$this->paramInput->status);
		        $query .= "&status={$this->paramInput->status}";
		    }
		}
		
		if (!empty($this->paramInput->id)) 
		{
			$select->where('p.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		
		if (!empty($this->paramInput->sn))
		{
		    $select->where('p.sn = ?',$this->paramInput->sn);
		    $query .= "&sn={$this->paramInput->sn}";
		}
		
		if (!empty($this->paramInput->product_name)) 
		{
			$select->where("p.product_name like '%{$this->paramInput->product_name}%'");
			$query .= "&product_name={$this->paramInput->product_name}";
		}
		
		if (!empty($this->paramInput->up_time)) 
		{
		    $up_time = strtotime($this->paramInput->up_time);
			$select->where("p.travel_date >= ?",$up_time);
			$query .= "&up_time >={$up_time}";
		}
		
		if (!empty($this->paramInput->down_time))
		{
		    $down_time = strtotime($this->paramInput->down_time);
		    $select->where("p.travel_date <= ?",$down_time);
		    $query .= "&down_time<={$down_time}";
		}
	
		if (!empty($this->paramInput->city_id)) 
		{
			$select->where("p.origin_id = ?", $this->paramInput->city_id);
			$query .= "&origin_id={$this->paramInput->city_id}";
		}
		
		/* 分页 */
  
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		
		$productList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','p')
			->order(array('p.order ASC','p.travel_date DESC'))
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();

		for($i=0;$i<count($productList);$i++)
		{
    		$city = $this->_db->select()
                ->from(array('o' => 'region'))
                ->where('o.id = ?',$productList[$i]['origin_id'])
                ->query()
                ->fetch();
		
	        $productList[$i]['origin_id'] = $city['region_name'];
	        
	        $time = $productList[$i]['up_time'] - SCRIPT_TIME;
	        if ($time > 0 && $time <= 86400)
	        {
	        	
	        	$productList[$i]['travel_date'] = date("Y-m-d",$productList[$i]['travel_date']);
	        	if ($productList[$i]['status'] != 2)
	        	{
	        		$time = gmstrftime("%H 时 %M 分 %S 秒 ", $time);
	        		$productList[$i]['travel_date'] = $productList[$i]['travel_date'].'<p style="color:red">离商品上架只有'."$time".'</p>';
	        	}
	        }
	        else 
	        {
	        	$productList[$i]['travel_date'] = date("Y-m-d",$productList[$i]['travel_date']);
	        }
	        
		}
		
		$this->view->productList = $productList;
		
		/* 分类 */
		
		$results = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.area = ?',$this->paramInput->area)
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($results,'id','parent_id','cate_name');
		$this->view->list = $tree->toArray();

		$this->view->cateList = $cateList;
		
		$this->params = $this->paramInput->getEscaped();
		$this->view->query = $query;
	}
	
	/**
	 *  添加
	 */
	public function addAction()
	{
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/admincp',
					'text' => '返回')
			));
		}
		
		if ($this->_request->isPost()) 
		{
		    
			if (form($this)) 
			{
			    
			    $items = $this->data['item_s'];
			    	      

			    
				/* 库存 */
				
				$stock = $this->_stock($items);
				
				/* 供货商 */
				
				$supplierId = $this->input->supplier_id;
				
				/* 插入数据库 */

				$this->rows['product'] = $this->models['product']->createRow(array(
				    'area' => $this->input->area,
				    'origin_id' => $this->input->city_id,
				    'contract_id' =>$this->input->contract_id,
				    'supplier_id' => $supplierId,
				    'image' => "",
				    'product_name' => $this->input->product_name,
				    'sn' => $this->input->sn,
				    'mktprice' => $this->input->mktprice,
				    'price' => $this->input->price,
 				    'type_id' => $this->input->type_id,
				    'cost_price' => $this->input->cost_price,
				    'stock' => $stock,
				    'seo_title' => $this->input->seo_title,
				    'seo_keywords' => $this->input->seo_keywords,
				    'seo_description' =>$this->input->getUnescaped('seo_description'),
				    'features_content' =>$this->input->getUnescaped('features_content'),
				    'features_info' =>$this->input->getUnescaped('features_info'),
				    'cost_need' => $this->input->getUnescaped('cost_need'),
				    'information' => $this->input->information,
				    'up_time' => strtotime($this->input->up_time),
				    'down_time' => strtotime($this->input->down_time),
					'notify_day' => $this->input->notify_day,
				    'add_time' => time(),
				    'brief' => $this->input->getUnescaped('brief'),
				    'travel_date' => strtotime($this->input->travel_date),
				    'tourism_type' =>$this->input->tourism_type,    
				    'travel_restrictions' => $this->input->travel_restrictions,
				));
				$product_id = $this->rows['product']->save();
				
				/*添加分类表*/
				
				if(!empty($this->input->cate_id))
				{
				    $cate_id = explode(",",$this->input->cate_id);

				    for($i=0;$i<count($cate_id);$i++)
				    {
				        $this->rows['product_catedata'] = $this->models['product_catedata']->createRow(array(
				            'cate_id' => $cate_id[$i],
				            'product_id' => $product_id,
				        ));
				        $this->rows['product_catedata']->save();
				    }
				}
				
				/* 更新属性 */
				
				if (!empty($this->data['attrs'])) 
				{
					$this->rows['product']->setFromArray($this->data['attrs'])->save();
				}
				
				/* 规格 */
				if(!empty($items))
				{
				    foreach ($items as $item)
				    {
				        $this->rows['product'] = $this->models['product']->createRow(array(
				            'area' => $this->input->area,
				            'origin_id' => $this->input->city_id,
				            'contract_id' =>$this->input->contract_id,
				            'supplier_id' => $supplierId,
				            'image' => "",
				            'product_name' => $this->input->product_name,
				            'sn' => $this->input->sn,
				            'mktprice' => $this->input->mktprice,
				            'price' => $this->input->price,
				            'cost_price' => $this->input->cost_price,
				            'stock' => $stock,
				            'detail' => $this->input->getUnescaped('detail'),
				            'seo_title' => $this->input->seo_title,
				            'seo_keywords' => $this->input->seo_keywords,
				            'seo_description' =>$this->input->getUnescaped('seo_description'),
        				    'features_content' =>$this->input->getUnescaped('features_content'),
        				    'features_info' =>$this->input->features_info,
				            'cost_need' => $this->input->getUnescaped('cost_need'),
				            'information' => $this->input->information,
				            'parent_id' =>$product_id,
				            'travel_date' => strtotime($item['time']),
				            'add_time' => time(),
				            'brief' => $this->input->getUnescaped('brief'),
				            'tourism_type' => strtotime($this->input->tourism_type),
				            'travel_restrictions' => $this->input->travel_restrictions,
				        ));
				        $product_id_new = $this->rows['product']->save();
				        
				        foreach ($item['row'] as $data)
				        {
				            if($data['price']=="")
				            {
				                $data['price'] = $this->input->price;
				            }
				            if($data['mktprice']=="")
				            {
				                $data['mktprice'] = $this->input->mktprice;
				            }
				            if($data['cost_price']=="")
				            {
				                $data['cost_price'] = $this->input->cost_price;
				            }
				            
				            $specval_1 = "";
				            $specval_2 = "";
				            $specval_3 = "";
				            $specsval = "";

                                if($data['spec_1'] != "" )
                                {
                                    $specval_1 = $data['spec_1']; 
                                    //查询value
                                    $spec_desc = $this->_db->select()
                                        ->from(array('o' => 'product_specval'))
                                        ->where('o.id = ?',$specval_1)
                                        ->query()
                                        ->fetch();
                                    
                                    $specsval .=  $spec_desc['value'];
                                }
                               if($data['spec_2'] != "" )
                                {
                                    $specval_2 = $data['spec_2'];
                                    //查询value
                                    $spec_desc = $this->_db->select()
                                        ->from(array('o' => 'product_specval'))
                                        ->where('o.id = ?',$specval_2)
                                        ->query()
                                        ->fetch();
                                    $specsval .= ",".$spec_desc['value'];
                                }
                                if($data['spec_3'] != "" )
                                {
                                    $specval_3 = $data['spec_3'];
                                    //查询value
                                    $spec_desc = $this->_db->select()
                                        ->from(array('o' => 'product_specval'))
                                        ->where('o.id = ?',$specval_3)
                                        ->query()
                                        ->fetch();
                                    $specsval .= ",".$spec_desc['value'];
                                }
                              
				            $this->rows['product_item'] = $this->models['product_item']->createRow(array(
				                'area' =>$this->rows['product']->area,
				                'item_name' => $data['item_name'],
				                'specval_1' => $specval_1,
				                'specval_2' => $specval_2,
				                'specval_3' => $specval_3,
				                'image' =>empty($item['image']) ? $this->rows['product']->image : $data['image'],
				                'fn' =>$data['fn'],
				                'stock' =>$data['stock'],
				                'spec_desc' =>$specsval,
				                'status' =>1,
				                'price' => $data['price'],
				                'mktprice' => $data['mktprice'],
				                'product_id' => $product_id_new,
				                'cost_price' => $data['cost_price'],
				            ));
				            $this->rows['product_item']->save();
				        }
				    }
				}

				$this->_helper->notice('发布成功','','success',array(
					array(
						'href' => "/productcp/product/add?area={$this->paramInput->area}",
						'text' => '继续添加'),
					array(
						'href' => "/productcp/product/list?area={$this->paramInput->area}",
						'text' => '返回')
				));
			}
			
			/* 商品类型信息 */
			
			list($attrs,$params,$specs) = getProductTypeInfoByCateId($this->data['cate_id']);
			$this->view->attrs = $attrs;
			$this->view->params = $params;
			$this->view->specs = $specs;
		}
		
		/* 分类 */
		
		$results = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.area = ?',$this->paramInput->area)
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$tree = new Core_Tree(0);
		$tree->setTree($results,'id','parent_id','cate_name');
		$this->view->list = $tree->toArray();
		$this->view->cateList = $cateList;

		$tourism_type = array(
		    array('id' => 1,'tourism_type' => '跟团游'),
		    array('id' => 2,'tourism_type' => '自助游'),
		    array('id' => 3,'tourism_type' => '自由行'),
		    array('id' => 4,'tourism_type' => '自驾游'),
		    array('id' => 5,'tourism_type' => '目的地服务'),
		);
		$this->view->tourism_type = $tourism_type;
		
		/*类型*/
		$type = $this->_db->select()
		  ->from(array('o'=>'product_type'))
		  ->query()
		  ->fetchAll();
		
		$this->view->type = $type;
		
		/* 标签 */
		
		$tags = $select = $this->_db->select()
			->from(array('t' => 'product_tag'))
			->where('t.status = ?','1')
			->query()
			->fetchAll();
		$this->view->tags = $tags;
		
		/*供应商*/
		
		$supplier = $this->_db->select()
		->from(array('o' => 'member_supplier'),array('id','supplier_name'))
		->where('o.status = ?', 1)
		->query()
		->fetchAll();
		
		$this->view->supplier  = $supplier;
	}
	
	/**
	 *  编辑
	 */
	public function editAction()
	{
		if (!params($this)) 
		{
			/* 提示 */
		    
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}

		$this->rows['product'] = $this->models['product']->find($this->paramInput->id)->current();
		
		$this->view->product_id = $this->paramInput->id;
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 图片 */

				if (empty($this->rows['product']->image)) 
				{
					$defaultImage = $this->_db->select()
						->from(array('i' => 'product_image'),array('image'))
						->where('i.product_id = ?',$this->paramInput->id)
						->query()
						->fetchColumn();
					if (empty($defaultImage)) 
					{
						$defaultImage = '';
					}
					$this->rows['product']->image = $defaultImage;
				}
				
			    $items = $this->data['item_s'];
		
				/* 库存 */
				
				$stock = $this->_stock($items);
			
				/* 供货商 */
				
				$supplierId = $this->input->supplier_id;

				/* 更新主表 */	
				$this->rows['product']->origin_id = $this->input->city_id;
				$this->rows['product']->stock = $stock;
				//$this->rows['product']->contract_id = $this->input->contract_id;
				$this->rows['product']->supplier_id = $supplierId;
				$this->rows['product']->product_name = $this->input->product_name;
				$this->rows['product']->sn = $this->input->sn;
				$this->rows['product']->price = $this->input->price;
				$this->rows['product']->cost_price = $this->input->cost_price;
				$this->rows['product']->mktprice = $this->input->mktprice;
				$this->rows['product']->seo_title = $this->input->seo_title;
				$this->rows['product']->seo_keywords = $this->input->seo_keywords;
				$this->rows['product']->seo_description = $this->input->getUnescaped('seo_description');
				$this->rows['product']->features_content = $this->input->getUnescaped('features_content');
				$this->rows['product']->features_info = $this->input->getUnescaped('features_info');
				$this->rows['product']->cost_need = $this->input->getUnescaped('cost_need');
				$this->rows['product']->information =  $this->input->getUnescaped('information');
				$this->rows['product']->up_time =  strtotime($this->input->up_time);
				$this->rows['product']->down_time =  strtotime($this->input->down_time);
				$this->rows['product']->notify_day = $_REQUEST['notify_day'];
				$this->rows['product']->type_id = $this->input->type;
				$this->rows['product']->brief = $this->input->getUnescaped('brief');
				$this->rows['product']->travel_date = strtotime($this->input->travel_date);
				$this->rows['product']->tourism_type = $this->input->tourism_type;
				$this->rows['product']->travel_restrictions = $this->input->travel_restrictions;
				$this->rows['product']->save();
				
				/*更新分类关系表*/
				
				if(!empty($this->input->cate_id))
				{
				    $cate_id = explode(",", $this->input->cate_id); 
				    
				    //删除所有关联信息
				    $this->models['product_catedata']->delete(array('product_id = ?' => $this->paramInput->id));
				    
				    for ($i=0;$i<count($cate_id);$i++)
				    {
				        $this->rows['product_catedata'] = $this->models['product_catedata']->createRow(array(
				            'cate_id' => $cate_id[$i],
				            'product_id' => $this->paramInput->id,
				        ));
				        $this->rows['product_catedata']->save();
				    }
				}
				
				/* 规格 */
				if(!empty($items))
				{
				    $product_ids = $this->_db->select()
                        ->from(array('i' => 'product'),array('id'))
                        ->where('i.parent_id = ?',$this->paramInput->id)
                        ->where('i.status <> ?',-1)
                        ->query()
			            ->fetchAll();

				    //删除item表
				    foreach ($product_ids as $row)
				    {
				        //删除原先的商品
				        $this->models['product']->delete(array('id = ?' => $row['id']));
				        $this->models['product_item']->delete(array('product_id = ?' =>$row['id']));
				    }
				    
				    foreach ($items as $item)
				    {
				        $this->rows['product'] = $this->models['product']->createRow(array(
				            'area' => $this->input->area,
				            'origin_id' => $this->input->city_id,
				            'contract_id' =>$this->input->contract_id,
				            'supplier_id' => $supplierId,
				            'image' => "",
				            'product_name' => $this->input->product_name,
				            'sn' => $this->input->sn,
				            'price' => $this->input->price,
				            'cost_price' => $this->input->cost_price,
				            'mktprice' =>$this->input->mktprice,
				            'stock' => $stock,
				            'detail' => $this->input->getUnescaped('detail'),
				            'seo_title' => $this->input->seo_title,
				            'seo_keywords' => $this->input->seo_keywords,
				            'seo_description' =>$this->input->getUnescaped('seo_description'),
				            'features_info' =>$this->input->getUnescaped('features_info'),
				            'features_content' =>$this->input->getUnescaped('features_content'),
				            'cost_need' => $this->input->getUnescaped('cost_need'),
				            'information' => $this->input->information,
				            'parent_id' =>$this->paramInput->id,
				            'travel_date' => strtotime($item['time']),
				            'type_id' => $this->input->type,
				            'add_time' => time(),
				            'travel_restrictions' => $this->input->travel_restrictions,
				        ));
				        $product_id_new = $this->rows['product']->save();
				        
				        foreach ($item['row'] as $data)
				        {
				            if($data['price']=="")
				            {
				                $data['price'] = $this->input->price;
				            }
				            if($data['mktprice']=="")
				            {
				                $data['mktprice'] = $this->input->mktprice;
				            }
				            if($data['cost_price']=="")
				            {
				                $data['cost_price'] = $this->input->cost_price;
				            }
				            
				            $specval_1 = "";
				            $specval_2 = "";
				            $specval_3 = "";
				            $specsval = "";

                                if($data['spec_1'] != "" )
                                {
                                    $specval_1 = $data['spec_1']; 
                                    //查询value
                                    $specDesc = $this->_db->select()
                                        ->from(array('o' => 'product_specval'))
                                        ->where('o.id = ?',$specval_1)
                                        ->query()
                                        ->fetch();
                                    
                                    $specsval .=  $specDesc['value'];
                                }
                               if($data['spec_2'] != "" )
                                {
                                    $specval_2 = $data['spec_2'];
                                    //查询value
                                    $specDesc = $this->_db->select()
                                        ->from(array('o' => 'product_specval'))
                                        ->where('o.id = ?',$specval_2)
                                        ->query()
                                        ->fetch();
                                    $specsval .= ",".$specDesc['value'];
                                }
                                if($data['spec_3'] != "" )
                                {
                                    $specval_3 = $data['spec_3'];
                                    //查询value
                                    $specDesc = $this->_db->select()
                                        ->from(array('o' => 'product_specval'))
                                        ->where('o.id = ?',$specval_3)
                                        ->query()
                                        ->fetch();
                                    $specsval .= ",".$specDesc['value'];
                                }
				            $this->rows['product_item'] = $this->models['product_item']->createRow(array(
				                'area' =>$this->rows['product']->area,
				                'item_name' => $data['item_name'],
	                            'specval_1' => $specval_1,
				                'specval_2' => $specval_2,
				                'specval_3' => $specval_3,
				                'image' =>empty($item['image']) ? $this->rows['product']->image : $data['image'],
				                'fn' =>$data['fn'],
				                'stock' =>$data['stock'],
				                'spec_desc' =>$specsval,
				                'status' =>1,
				                'price' => $data['price'],
				                'mktprice' => $data['mktprice'],
				                'cost_price' => $data['cost_price'],
				                'product_id' => $product_id_new,
				            ));
				            $this->rows['product_item']->save();
				        }
				    }
				}
				
				/* 提示 */
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => "/productcp/product/list?area={$this->rows['product']->area}",
						'text' => '返回')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			/* 产品 */
			
			$product =$this->_db->select()
			     ->from(array('o' => 'product'))
			     ->where('o.parent_id = ?',$this->paramInput->id)
			     ->where('o.status <> ?',-1)
			     ->query()
			     ->fetchAll();
			
			$items = $this->_db->select()
    			->from(array('i' => 'product_item'))
    			->where('product_id = ?',$this->paramInput->id)
    			->where('status = ?',1)
    			->order('specval_1')
    			->query()
    			->fetchAll();
			
			/* 将第一个产品参数并入商品参数 */

			$this->data =$this->_db->select()
			     ->from(array('o' => 'product'))
			     ->where('o.id = ?',$this->paramInput->id)
			     ->query()
			     ->fetch();

			$this->data['up_time'] = date("Y-m-d H:i:s",$this->data['up_time']);
			$this->data['down_time'] = date("Y-m-d H:i:s",$this->data['down_time']);
			for ($i=0;$i<count($product);$i++)
			{
			    $data[$i]['time'] = date("Y-m-d",$product[$i]['travel_date']);
			    $data[$i]['product_id'] = $product[$i]['id'];
		    	$item = $this->_db->select()
        			->from(array('i' => 'product_item'))
        			->where('i.product_id = ?',$product[$i]['id'])
        			->where('i.status = ?',1)
        			->query()
        			->fetchAll();

                for ($j=0;$j<count($item);$j++)
                {
                    if($item[$j]['specval_1'] != "" )
                    {
                        $data[$i]['data_mc']  = 1;
                        $specval_1 = $item[$j]['specval_1'];
                        //查询value
                        $spec_desc = $this->_db->select()
                            ->from(array('o' => 'product_specval'))
                            ->joinLeft(array('e' => 'product_spec'), 'o.spec_id = e.id')
                            ->where('o.id = ?',$specval_1)
                            ->query()
                            ->fetch();
                        $item[$j]['specval_1_val'] = $spec_desc['value'];
                        $item[$j]['spec_name_1'] = $spec_desc['spec_name'];
                    }
                    if($item[$j]['specval_2'] != "" )
                    {
                        $data[$i]['data_mc']  = 2;
                        $specval_2 = $item[$j]['specval_2'];
                        //查询value
                        $spec_desc = $this->_db->select()
                            ->from(array('o' => 'product_specval'))
                            ->joinLeft(array('e' => 'product_spec'), 'o.spec_id = e.id')
                            ->where('o.id = ?',$specval_2)
                            ->query()
                            ->fetch();
                        $item[$j]['specval_2_val'] = $spec_desc['value'];
                        $item[$j]['spec_name_2'] = $spec_desc['spec_name'];
                    }
                    if($item[$j]['specval_3'] != "" )
                    {
                        $data[$i]['data_mc']  = 3;
                        $specval_3= $item[$j]['specval_3'];
                        //查询value
                        $spec_desc = $this->_db->select()
                            ->from(array('o' => 'product_specval'))
                            ->joinLeft(array('e' => 'product_spec'), 'o.spec_id = e.id')
                            ->where('o.id = ?',$specval_3)
                            ->query()
                            ->fetch();
                        $item[$j]['specval_3_val'] = $spec_desc['value'];
                        $item[$j]['spec_name_3'] = $spec_desc['spec_name'];
                    }
                }
		    		    	
		    	$data[$i]['row'] = $item;
			}
			
			$this->data['item_s'] = $data;
		}

		
		/*类型*/
		$type = $this->_db->select()
		->from(array('o'=>'product_type'))
		->query()
		->fetchAll();
		
		$this->view->type = $type;
		
		$tourismType = array(
		    array('id' => 1,'tourism_type' => '跟团游'),
		    array('id' => 2,'tourism_type' => '自助游'),
		    array('id' => 3,'tourism_type' => '自由行'),
		    array('id' => 4,'tourism_type' => '自驾游'),
		    array('id' => 5,'tourism_type' => '目的地服务'),
		);
		$this->view->tourism_type = $tourismType;
		
		/*地址*/
		
		$cityId =  $this->_db->select()
    		->from(array('o' => 'region'))
    		->where('id = ?',$this->rows['product']->origin_id)
    		->query()
    		->fetch();
			
		$provinceId = $this->_db->select()
    		->from(array('o' => 'region'))
    		->where('id = ?',$cityId['parent_id'])
    		->query()
    		->fetch();
		
		$region = array();
		$region['city_id'] = $cityId['id'];
		$region['province_id'] = $provinceId['id'];
			
		$this->view->region = $region;

		/* 图片 */
		
		$imageList = $this->_db->select()
			->from(array('i' => 'product_image'))
			->where('i.product_id = ?',$this->paramInput->id)
			->where('i.status = ?','1')
			->order('i.main DESC')
			->order('i.order ASC')
			->query()
			->fetchAll();
		$this->view->imageList = $imageList;
		
		/* 分类 */
		
		$results = $this->_db->select()
			->from(array('c' => 'product_cate'))
			->where('c.area = ?',$this->paramInput->area)
			->where('c.status = ?','1')
			->query()
			->fetchAll();
		$cateList = array();
		foreach ($results as $cate) 
		{
			$cateList[$cate['id']] = $cate;
		}
		$this->view->cateList = $cateList;
		
		/* 标签 */
		
		$tags = $select = $this->_db->select()
			->from(array('t' => 'product_tag'))
			->where('t.status = ?','1')
			->query()
			->fetchAll();
		$this->view->tags = $tags;
		
		/* 标签 */
		
		$tagIds = array();
		$results = $this->_db->select()
			->from(array('d' => 'product_tagdata'),array('tag_id'))
			->where('d.product_id = ?',$this->paramInput->id)
			->query()
			->fetchAll();
		foreach ($results as $result)
		{
			$tagIds[] = $result['tag_id'];
		}
		
		$this->view->tagIds = $tagIds;
		
		/*供应商*/
		
		$supplier = $this->_db->select()
    		->from(array('o' => 'member_supplier'),array('id','supplier_name'))
    		->where('o.status = ?', 1)
    		->query()
    		->fetchAll();
		
		$this->view->supplier  = $supplier;
	}
	
	/**
	 *  价格
	 */
	protected function _price() 
	{
		/* 统一规格 */
		
		if (empty($this->input->items)) 
		{
			return $this->input->price;
		}
		
		/* 多种规格 */
		
		$min = 0;
		$prices = array();
		foreach ($this->data['items'] as $item) 
		{
			$prices[] = $item['price'];
		}
		
		$min = min($prices);
		return $min;
	}
	
	/**
	 *  最高市场价
	 */
	protected function _mktprice() 
	{
		/* 统一规格 */
		
		if (empty($this->input->items)) 
		{
			return $this->input->mktprice;
		}
		
		/* 多种规格 */
		
		$mktprices = array();
		foreach ($this->data['items'] as $item) 
		{
			$mktprices[] = $item['mktprice'];
		}
		
		return max($mktprices);
	}
	
	/**
	 *  库存
	 */
	protected function _stock($items) 
	{
		/* 统一规格 */
		
		if (empty($items)) 
		{
			return $this->input->stock;
		}
		
		/* 多种规格 */
		
		$stock = 0;
		foreach ($items as $item) 
		{
		    foreach ($item['row'] as $data)
		    {
			     $stock += $data['stock'];
		    }
		}
		return $stock;
	}
	
	/**
	 *  供货商编码
	 */
	protected function _supplierId() 
	{
		$code = getSupplierCode($this->input->sn);
		
		$supplierId = $this->_db->select()
			->from(array('m' => 'member'),array('id'))
			->where('m.role = ?','supplier')
			->where('m.code = ?',$code)
			->query()
			->fetchColumn();
		return empty($supplierId) ? 0 : $supplierId;
	}
	
	
	/**
	 *  审核
	 */
	public function auditAction() 
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();
		
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 审核通过 */
		
		$this->rows['product'] = $this->models['product']->find($this->input->id)->current();
		$this->rows['product']->status = 1;
		$this->rows['product']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  下架
	 */
	public function downAction() 
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();

		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 下架*/
		
		if (is_array($this->input->id))
		{
			foreach ($this->input->id as $id)
			{
				$this->rows['product'] = $this->models['product']->find($id)->current();
				$this->rows['product']->status = 3;
				$this->rows['product']->save();
			}
		}
		else
		{
			$this->rows['product'] = $this->models['product']->find($this->input->id)->current();
			$this->rows['product']->status = 3;
			$this->rows['product']->save();
		}
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  上架
	 */
	public function upAction() 
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();
		
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 上架*/
		
		if (is_array($this->input->id))
		{
			foreach ($this->input->id as $id)
			{
				$this->rows['product'] = $this->models['product']->find($id)->current();
				$this->rows['product']->status = 2;
				$this->rows['product']->save();
			}
		}
		else
		{		
			$this->rows['product'] = $this->models['product']->find($this->input->id)->current();
			$this->rows['product']->status = 2;
			$this->rows['product']->save();
		}
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  删除
	 */
	public function deleteAction()
	{
		/* 取消视图 */
		
		$this->_helper->viewRenderer->setNoRender();
		
		$json = array();
		
		if (!params($this)) 
		{
			if ($this->_request->isXmlHttpRequest()) 
			{
				$json['flag'] = 'error';
				$json['msg'] = $this->error->firstMessage();
				$this->_helper->json($json);
			}
			else 
			{
				$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
					array(
						'href' => 'javascript:history.back();',
						'text' => '返回')
				));
			}
		}
		
		/* 删除商品 */
		
		$this->rows['product'] = $this->models['product']->find($this->paramInput->id)->current();
		$this->rows['product']->status = -1;
		$this->rows['product']->save();
		
		if ($this->_request->isXmlHttpRequest()) 
		{
			$json['flag'] = 'success';
			$this->_helper->json($json);
		}
		else 
		{
			$this->_helper->notice('删除成功','','success',array(
				array(
					'href' => 'javascript:history.back();',
					'text' => '返回')
			));
		}
	}
	
	/**
	 *  ajax
	 */
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
			$json['flag'] = 'error';
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}

		switch ($op)
		{
		    case 'product_dates';
    		    //根据商品的id查询出行期日
    		    $dates = $this->_db->select()
    		    ->from(array('o' => 'product'),array('travel_date'))
    		    ->where('o.parent_id = ?',$this->input->id)
    		    ->query()
    		    ->fetchAll();
    		     
    		    $this->json['dates']=$dates;
    		    $this->json['errno'] = '0';
    		    $this->_helper->json($this->json);
		    break;
		    case 'add_product':
		        
		        $items = $this->data['item_s'];
		        
		        /*判断时间上架是否有子产品*/
		        
		        if(!empty($items))
		        {
		            $childerProduct = 0;
		            foreach ($items as $item)
		            {
		                if(strtotime($item['time']) >=  strtotime($this->input->up_time))
		                {
		                    $childerProduct++;
		                }
		            }
		             
		            if($childerProduct == 0)
		            {
		                $json['flag'] = 'error';
		                $json['msg'] = "商品上架后没有子产品";
		                $this->_helper->json($json);
		            }
		        }
		        
		        if(strtotime($this->input->up_time) >= strtotime($this->input->down_time))
		        {
		            $json['flag'] = 'error';
		            $json['msg'] = "下架时间要大于上架时间";
		            $this->_helper->json($json);
		        }
		        
		        /* 库存 */
		        
		        $stock = $this->_stock($items);
		        
		        /* 供货商 */
		        
		        $supplierId = $this->input->supplier_id;
		        
		        /* 插入数据库 */
		        
		        $this->rows['product'] = $this->models['product']->createRow(array(
		            'area' => $this->input->area,
		            'origin_id' => $this->input->city_id,
		           // 'contract_id' =>$this->input->contract_id,
		            'supplier_id' => $supplierId,
		            'image' => "",
		            'product_name' => $this->input->getUnescaped('product_name'),
		            'sn' => $this->input->sn,
		            'mktprice' => $this->input->mktprice,
		            'price' => $this->input->price,
		            'type_id' => $this->input->type_id,
		            'cost_price' => $this->input->cost_price,
		            'child_price' => $this->input->child_price,
		            'stock' => $stock,
		            'seo_title' => $this->input->seo_title,
		            'seo_keywords' => $this->input->seo_keywords,
		            'seo_description' =>$this->input->getUnescaped('seo_description'),
		            'features_content' =>$this->input->getUnescaped('features_content'),
		            'features_info' =>$this->input->getUnescaped('features_info'),
		            'cost_need' => $this->input->getUnescaped('cost_need'),
		            'information' => $this->input->information,
		            'up_time' => strtotime($this->input->up_time),
		            'down_time' => strtotime($this->input->down_time),
		            'add_time' => time(),
		            'brief' => $this->input->getUnescaped('brief'),
		            'travel_date' => strtotime($this->input->travel_date),
		            'tourism_type' =>$this->input->tourism_type,
		            'travel_restrictions' => $this->input->travel_restrictions,
		            'discount_information' => $this->input->discount_information,
		        ));
		        $productId = $this->rows['product']->save();
		        
		        /*添加分类表*/
		        
		        if(!empty($this->input->cate_id))
		        {
		            $cateId = explode(",",$this->input->cate_id);
		        
		            for($i=0;$i<count($cateId);$i++)
		            {
    		            $this->rows['product_catedata'] = $this->models['product_catedata']->createRow(array(
    		                'cate_id' => $cateId[$i],
    		                'product_id' => $productId,
    		            ));
    		                $this->rows['product_catedata']->save();
		            }
	            }
		        
				/* 更新属性 */
		        
				if (!empty($this->data['attrs']))
				{
			     	$this->rows['product']->setFromArray($this->data['attrs'])->save();
				}
		        
				/* 标签*/
				
				if (!empty($this->input->tags) && is_array($this->input->tags))
				{
					foreach ($this->input->tags as $tag)
					{
						$this->models['product_tagdata']->createRow(array(
								'product_id' => $productId,
								'tag_id' => $tag))->save();
					}
				}
				
        		    /* 规格 */
        	    if(!empty($items))
        		{
        			foreach ($items as $item)
        			{
                			$this->rows['product'] = $this->models['product']->createRow(array(
                			    'area' => $this->input->area,
                			    'origin_id' => $this->input->city_id,
                			    'contract_id' =>$this->input->contract_id,
                			    'supplier_id' => $supplierId,
                			    'image' => "",
                			    'product_name' => $this->input->getUnescaped('product_name'),
                			    'sn' => $this->input->sn,
                			    'mktprice' => $this->input->mktprice,
                			    'price' => $this->input->price,
                			    'cost_price' => $this->input->cost_price,
                			    'child_price' => $this->input->child_price,
                			    'stock' => $stock,
                			    'detail' => $this->input->getUnescaped('detail'),
                			    'seo_title' => $this->input->seo_title,
                			    'seo_keywords' => $this->input->seo_keywords,
                			    'seo_description' =>$this->input->getUnescaped('seo_description'),
                		        'features_content' =>$this->input->getUnescaped('features_content'),
                			    'features_info' =>$this->input->features_info,
                			    'cost_need' => $this->input->getUnescaped('cost_need'),
                		        'information' => $this->input->information,
                		        'parent_id' =>$productId,
                		        'travel_date' => strtotime($item['time']),
                	            'add_time' => time(),
                	            'brief' => $this->input->getUnescaped('brief'),
                	            'tourism_type' => strtotime($this->input->tourism_type),
                                'travel_restrictions' => $this->input->travel_restrictions,
                			    'discount_information' => $this->input->discount_information,
        			            ));
        			        $productIdNew = $this->rows['product']->save();
        
                    foreach ($item['row'] as $data)
                    {
            	        if($data['price']=="")
            		    {
            	           $data['price'] = $this->input->price;
            			}
            			if($data['mktprice']=="")
            			{
            			     $data['mktprice'] = $this->input->mktprice;
            			}
            			if($data['cost_price']=="")
            		    {
                            $data['cost_price'] = $this->input->cost_price;
            		    }
            		    if($data['child_price']=="")
            		    {
            		        $data['cost_price'] = $this->input->child_price;
            		    }
        			    $specval_1 = "";
        			    $specval_2 = "";
        			    $specval_3 = "";
        		        $specsval = "";
        
        	            if($data['spec_1'] != "" )
        	            {
        		            $specval_1 = $data['spec_1'];
        		            //查询value
        	                $specDesc = $this->_db->select()
        		                ->from(array('o' => 'product_specval'))
        		                ->where('o.id = ?',$specval_1)
        		                ->query()
        		                ->fetch();
        
                			$specsval .=  $specDesc['value'];
            			}
        			    if($data['spec_2'] != "" )
        			    {
        			        $specval_2 = $data['spec_2'];
        			        //查询value
        			        $specDesc = $this->_db->select()
        			        ->from(array('o' => 'product_specval'))
        			        ->where('o.id = ?',$specval_2)
        			        ->query()
        			            ->fetch();
        			            $specsval .= ",".$specDesc['value'];
        			    }
        			    if($data['spec_3'] != "" )
        			    {
            			    $specval_3 = $data['spec_3'];
            			    //查询value
            			    $specDesc = $this->_db->select()
                    			     ->from(array('o' => 'product_specval'))
                    			    ->where('o.id = ?',$specval_3)
                    			    ->query()
                    			    ->fetch();
            			    $specsval .= ",".$specDesc['value'];
        			     }
                    
                    		$this->rows['product_item'] = $this->models['product_item']->createRow(array(
                    		'area' =>$this->rows['product']->area,
                    		'item_name' => $data['item_name'],
                    		'specval_1' => $specval_1,
                    		'specval_2' => $specval_2,
                    		'specval_3' => $specval_3,
                    	    'image' =>empty($item['image']) ? $this->rows['product']->image : $data['image'],
                    	    'fn' =>$data['fn'],
                    	    'stock' =>$data['stock'],
                    	    'spec_desc' =>$specsval,
                    	    'status' =>1,
                    	    'price' => $data['price'],
                            'mktprice' => $data['mktprice'],
                            'product_id' => $productIdNew,
                            'cost_price' => $data['cost_price'],
                    		'child_price' => $data['child_price'],
        			         ));
        			        $this->rows['product_item']->save();
        		        }
        	        }
                }
                $json['flag'] = 'success';
                $json['msg'] = "保存成功！";
                $this->_helper->json($json);
		        break;

		    case 'edit_product':
		        
		        $this->rows['product'] = $this->models['product']->find($this->input->product_id)->current();
		        
		        /* 图片 */

		        if (empty($this->rows['product']->image))
		        {
		            $defaultImage = $this->_db->select()
    		            ->from(array('i' => 'product_image'),array('image'))
    		            ->where('i.product_id = ?',$this->input->product_id)
    		            ->query()
    		            ->fetchColumn();
		            if (empty($defaultImage))
		            {
		                $defaultImage = '';
		            }
		            $this->rows['product']->image = $defaultImage;
		        }

		        $items = $this->data['item_s'];
		        
		        if(!empty($items))
		        {
		            $childerProduct = 0;
		            foreach ($items as $item)
		            {
		                if(strtotime($item['time']) >=  strtotime($this->input->up_time))
		                {
		                    $childerProduct++;
		                }
		            }
		             
		            if($childerProduct == 0)
		            {
		                $json['flag'] = 'error';
		                $json['msg'] = "商品上架后没有子产品";
		                $this->_helper->json($json);
		            }
		        }
		        
		        if(strtotime($this->input->up_time) >= strtotime($this->input->down_time))
		        {
		            $json['flag'] = 'error';
		            $json['msg'] = "下架时间要大于上架时间";
		            $this->_helper->json($json);
		        }
		        /* 库存 */
		        
		        $stock = $this->_stock($items);
		        	
		        /* 供货商 */
		        
		        $supplierId = $this->input->supplier_id;

		        /* 更新主表 */

		        $this->rows['product']->origin_id = $this->input->city_id;
		        $this->rows['product']->stock = $stock;
		      //  $this->rows['product']->contract_id = $this->input->contract_id;
		        $this->rows['product']->supplier_id = $supplierId;
		        $this->rows['product']->product_name = $this->input->getUnescaped('product_name');
		        $this->rows['product']->sn = $this->input->sn;
		        $this->rows['product']->price = $this->input->price;
		        $this->rows['product']->cost_price = $this->input->cost_price;
		        $this->rows['product']->mktprice = $this->input->mktprice;
		        $this->rows['product']->child_price = $this->input->child_price;
		        $this->rows['product']->seo_title = $this->input->seo_title;
		        $this->rows['product']->seo_keywords = $this->input->seo_keywords;
		        $this->rows['product']->seo_description = $this->input->getUnescaped('seo_description');
		        $this->rows['product']->features_content = $this->input->getUnescaped('features_content');
		        $this->rows['product']->features_info = $this->input->getUnescaped('features_info');
		        $this->rows['product']->cost_need = $this->input->getUnescaped('cost_need');
		        $this->rows['product']->information =  $this->input->getUnescaped('information');
		        $this->rows['product']->up_time =  strtotime($this->input->up_time);
		        $this->rows['product']->down_time =  strtotime($this->input->down_time);
		        $this->rows['product']->type_id = $this->input->type;
		        $this->rows['product']->brief = $this->input->getUnescaped('brief');
		        $this->rows['product']->travel_date = strtotime($this->input->travel_date);
		        $this->rows['product']->tourism_type = $this->input->tourism_type;
		        $this->rows['product']->travel_restrictions = $this->input->travel_restrictions;
		        $this->rows['product']->discount_information = $this->input->discount_information;
		        $this->rows['product']->save();
		        
		        /*更新分类关系表*/
		        
		        if(!empty($this->input->cate_id))
		        {
		            $cate_id = explode(",", $this->input->cate_id);
		        
		            //删除所有关联信息
		            $this->models['product_catedata']->delete(array('product_id = ?' => $this->input->product_id));
		        
		            for ($i=0;$i<count($cate_id);$i++)
		            {
        	            $this->rows['product_catedata'] = $this->models['product_catedata']->createRow(array(
        	                'cate_id' => $cate_id[$i],
        	                'product_id' => $this->input->product_id,
        	            ));
        	                $this->rows['product_catedata']->save();
		            }
	            }

	            /* 标签 */
	            
	            $where = array('product_id = ?' => $this->input->product_id);
	            $this->_db->delete('product_tagdata',$where);
	            if (!empty($this->input->tags) && is_array($this->input->tags))
	            {
	            	foreach ($this->input->tags as $tag)
	            	{
	            		$this->models['product_tagdata']->createRow(array(
	            				'product_id' => $this->input->product_id,
	            				'tag_id' => $tag))->save();
	            	}
	            }
	            
				/* 规格 */
	            if(!empty($items))
	            {
    	            $productIds = $this->_db->select()
        	            ->from(array('i' => 'product'),array('id'))
        	            ->where('i.parent_id = ?',$this->input->product_id)
    	                ->query()
    	                ->fetchAll();
		        
	                //删除item表
	                foreach ($productIds as $row)
	                {
	                    //删除原先的商品
	                    $this->_db->update('product',array('status' => -1),array('id = ?' => $row['id']));   
	                    
	                    //查询item的id
	                    $itemids = $this->_db->select()
	                       ->from(array('o'=>'product_item'))
	                       ->where('o.product_id = ?',$row['id'])
	                       ->where('o.status <> ?',-1)
	                       ->query()
	                       ->fetchAll();
	                    
	                    foreach ($itemids as $data)
	                    {
	                        $this->_db->update('product_item',array('status' => -1),array('id = ?' => $data['id']));
	                    }
	                }           
	                
	                $y = 1;
	                $q=1;
	                $sql = 'INSERT INTO `product` (`id`,`area`,`origin_id`,`contract_id`,`supplier_id`,`image`,`product_name`,`sn`,`price`,`cost_price`,`mktprice`,`child_price`,`stock`,`seo_title`,`seo_keywords`,`seo_description`,`features_info`,`features_content`,`cost_need`,`information`,`parent_id`,`travel_date`,`type_id`,`add_time`,`travel_restrictions`,`discount_information`,`status`) VALUES';
	                $sqlitem = 'INSERT INTO `product_item` (`id`,`item_name`,`specval_1`,`specval_2`,`specval_3`,`image`,`fn`,`stock`,`spec_desc`,`status`,`price`,`mktprice`,`cost_price`,`child_price`,`product_id`) VALUES';
	                
	                $productId = "";
	                foreach ($items as $item)
	                {
	                    if($item['product_id'] != "" && $item['product_id'])
	                    {
	                        //修改商品子类
	                        $sql .= "('".$item['product_id']."','".$this->input->area."',
	                            '".$this->input->city_id."','".$this->input->contract_id."','".$supplierId."','',
	                                '".$this->input->getUnescaped('product_name')."','".$this->input->sn."','".$this->input->price."',
	                                    '".$this->input->cost_price."','".$this->input->mktprice."','".$this->input->child_price."',
	                                        '".$stock."','".$this->input->seo_title."',
	                                            '".$this->input->seo_keywords."','".$this->input->getUnescaped('seo_description')."','".$this->input->getUnescaped('features_info')."',
	                                                '".$this->input->getUnescaped('features_content')."','".$this->input->getUnescaped('cost_need')."',
	                                                     '".$this->input->information."','".$this->input->product_id."',
	                                                          '".strtotime($item['time'])."','".$this->input->type."',                   
	                                               '". time()."','".$this->input->travel_restrictions."','".$this->input->discount_information."','0'),";
	                        
	                        $productId = $item['product_id'];
	                    }
	                   else 
	                   {
	                       //修改商品子类
	                       //查询最后一条的id
	                       $product_id = $this->_db->select()
	                           ->from(array('p' => 'product'),array('id'))
	                           ->order('p.id desc')
	                           ->query()
	                           ->fetch();
	                       
	                       $productId = $product_id['id']+$q;
	                       
	                       $sql .= "('".$productId."','".$this->input->area."',
	                         '".$this->input->city_id."','".$this->input->contract_id."','".$supplierId."','',
	                                '".$this->input->product_name."','".$this->input->sn."','".$this->input->price."',
	                                    '".$this->input->cost_price."','".$this->input->mktprice."','".$this->input->child_price."',
	                                        '".$stock."','".$this->input->seo_title."',
	                                            '".$this->input->seo_keywords."','".$this->input->getUnescaped('seo_description')."','".$this->input->getUnescaped('features_info')."',
	                                                '".$this->input->getUnescaped('features_content')."','".$this->input->getUnescaped('cost_need')."',
	                                                     '".$this->input->information."','".$this->input->product_id."',
	                                                          '".strtotime($item['time'])."','".$this->input->type."',         
	                                               '". time()."','".$this->input->travel_restrictions."','".$this->input->discount_information."','0'),";
	                       
	                                           $q++;
	                   }
	                   

	                   
	                   foreach ($item['row'] as $data)
	                   {
	                       if($data['price']=="")
	                       {
	                           $data['price'] = $this->input->price;
	                       }
	                       if($data['mktprice']=="")
	                       {
	                           $data['mktprice'] = $this->input->mktprice;
	                       }
	                       if($data['cost_price']=="")
	                       {
	                           $data['cost_price'] = $this->input->cost_price;
	                       }
	                       if($data['child_price']=="")
	                       {
	                           $data['child_price'] = $this->input->child_price;
	                       }
	                   
	                       $specval_1 = "";
	                       $specval_2 = "";
	                       $specval_3 = "";
	                       $specsval = "";
	                   
	                       if($data['spec_1'] != "" )
	                       {
	                           $specval_1 = $data['spec_1'];
	                           //查询value
	                           $spec_desc = $this->_db->select()
    	                           ->from(array('o' => 'product_specval'))
    	                           ->where('o.id = ?',$specval_1)
    	                           ->query()
    	                           ->fetch();
	                   
	                           $specsval .=  $spec_desc['value'];
	                       }
	                       if($data['spec_2'] != "" )
	                       {
	                           $specval_2 = $data['spec_2'];
	                           //查询value
	                           $spec_desc = $this->_db->select()
	                           ->from(array('o' => 'product_specval'))
	                           ->where('o.id = ?',$specval_2)
	                           ->query()
	                           ->fetch();
	                           $specsval .= ",".$spec_desc['value'];
	                       }
	                       if($data['spec_3'] != "" )
	                       {
	                           $specval_3 = $data['spec_3'];
	                           //查询value
	                           $spec_desc = $this->_db->select()
	                           ->from(array('o' => 'product_specval'))
	                           ->where('o.id = ?',$specval_3)
	                           ->query()
	                           ->fetch();
	                           $specsval .= ",".$spec_desc['value'];
	                       }
	                        
	                        if($data['item_id'] != "" && $data['item_id'])
	                        {
	                            //修改商品item
	                            
	                            $image = empty($item['image']) ? $this->rows['product']->image : $data['image'];
	                           
	                            $sqlitem .="('".$data['item_id']."','".$data['item_name']."','".$specval_1."','".$specval_2."','".$specval_3."','$image',
	                                '".$data['fn']."','".$data['stock']."','".$specsval."',
	                                    '1','".$data['price']."','".$data['mktprice']."',
	                                        '".$data['cost_price']."','".$data['child_price']."',               
	                                        '".$productId."'),";

	                        }
	                        else 
	                        {
	                            //修改商品item

	                            
	                            //查询最后一条的id
	                            $productItemId = $this->_db->select()
    	                            ->from(array('p' => 'product_item'),array('id'))
    	                            ->order('p.id desc')
    	                            ->query()
    	                            ->fetch();

	                            $image = empty($item['image']) ? $this->rows['product']->image : $data['image'];
	                             
	                            $itemId = $productItemId['id']+$y;
	                            $sqlitem .="('".$itemId."','".$data['item_name']."','".$specval_1."','".$specval_2."','".$specval_3."','$image',
	                            '".$data['fn']."','".$data['stock']."','".$specsval."',
	                                    '1','".$data['price']."','".$data['mktprice']."',
	                                        '".$data['cost_price']."','".$data['child_price']."',               
	                                        '".$productId."'),";
	                            
	                            $y++;

	                        }
	                   }
	                   
	                }
	                   $sql = rtrim($sql,',');
	                   $sql .= 'ON DUPLICATE KEY UPDATE  area = VALUES(area),origin_id = VALUES(origin_id),contract_id = VALUES(contract_id),supplier_id = VALUES(supplier_id)
	                       ,image = VALUES(image),product_name = VALUES(product_name),sn = VALUES(sn),price = VALUES(price),cost_price = VALUES(cost_price),mktprice = VALUES(mktprice)
	                       ,child_price = VALUES(child_price),stock = VALUES(stock),seo_title = VALUES(seo_title),seo_keywords = VALUES(seo_keywords)
	                       ,seo_description = VALUES(seo_description),features_info = VALUES(features_info)
	                       ,features_content = VALUES(features_content),cost_need = VALUES(cost_need),information = VALUES(information),parent_id = VALUES(parent_id)
	                       ,travel_date = VALUES(travel_date),type_id = VALUES(type_id)
	                       ,add_time = VALUES(add_time),travel_restrictions = VALUES(travel_restrictions),discount_information = VALUES(discount_information),status = VALUES(status)';
	 
	                   $sqlitem = rtrim($sqlitem,',');
	                   $sqlitem .= 'ON DUPLICATE KEY UPDATE item_name = VALUES(item_name),specval_1 = VALUES(specval_1),specval_2 = VALUES(specval_2)
	                       ,specval_3 = VALUES(specval_3),image = VALUES(image),fn = VALUES(fn),stock = VALUES(stock),spec_desc = VALUES(spec_desc),status = VALUES(status)
	                       ,price = VALUES(price),mktprice = VALUES(mktprice),cost_price = VALUES(cost_price)
	                       ,child_price = VALUES(child_price),product_id = VALUES(product_id)';

                        $db =Zend_Registry::get('db');
                        $db->query($sql);
                        $db->query($sqlitem);
	                
		            }
		            $json['flag'] = 'success';
		            $json['msg'] = "修改成功！";
		            $this->_helper->json($json);
		        break;
		    
		    case 'authsn':
		        
		        $select = $this->_db->select()
    		        ->from(array('o' => 'product'))
    		        ->where('o.sn = ?',$this->input->sn);
		        
		        if($this->input->id != "")
		        {
		            $select->where('o.id = ?',$this->input->product_id);
		            $sn = $select->query()
		            ->fetch();
		            
		            if($sn == "")
		            {
    		            $sn = $select->query()
    		                ->fetch();
    		            
    		            if($sn == "")
    		            {
    		                $json = "true";
    		            }
    		            else
    		            {
    		                $json = false;
    		            }
		            }
		            else
		            {
		                $json = "true";
		            }
		        }
		        else 
		        {
		            $sn = $select->query()
		                ->fetch();
		            
		            if($sn == "")
		            {
		                $json = "true";
		            }
		            else
		            {
		                $json = false;
		            }
		        }
	
		        $this->_helper->json($json);
		    
		        break;
		    
		    case 'addvisa':
		        if($this->input->pordId !="")
		        {
		            $ids = explode(",", $this->input->visaIds);
		            
		            //删除原先的数据
		            $this->models['product_visadata']->delete(array('product_id = ?' => $this->input->pordId));
		            foreach ($ids as $row)
		            {
		                $this->rows['product_visadata'] = $this->models['product_visadata']->createRow(array(
		                    'visa_id' => $row,
		                    'product_id' => $this->input->pordId,
		                    'status' => 1,
		                ));
		                $this->rows['product_visadata']->save();
		            }
		         
		        }
		        //查询数据
		        $productVisa = $this->_db->select()
    		        ->from(array('o' => 'product_visadata'))
    		        ->joinLeft(array('c' => 'visa'),'c.id = o.visa_id',array('c.id as visa_id','visa_name'))
    		        ->where('o.product_id = ?',$this->input->pordId)
    		        ->where('o.status = ?',1)
    		        ->query()
    		        ->fetchAll();
		        
		        $json['errno'] = '0';
		        $json['visa'] = $productVisa;
	            $this->_helper->json($json);
		    
	            break;
	            
        case 'deletevisa': 
            if($this->input->pordId !="")
            {
                $this->rows['product_visadata'] = $this->models['product_visadata']->find($this->input->visaId)->current();
                $this->rows['product_visadata']->status = -1;
                $this->rows['product_visadata']->save();
            }
            $json['errno'] = '0';
            $this->_helper->json($json);
        
            break;
		    
		    case 'catelist':
		        
		        $catelist = $this->_db->select()
    		        ->from(array('o' => 'product_cate'),array('id','parent_id','cate_name as text'))
    		        ->where('o.status = ?','1')
    		        ->query()
    		        ->fetchAll();
		        
		        if($this->input->product_id != "")
		        {
		            //查询商品关联的cate_id
		            $productCates = $this->_db->select() 
		                  ->from(array('o' => 'product_catedata'))
		                  ->where('o.product_id = ?',$this->input->product_id)
		                  ->query()
		                  ->fetchAll();
		            
		            for ($i=0;$i<count($productCates);$i++)
		            {
		                for ($j=0;$j<count($catelist);$j++)
		                {
		                    if($productCates[$i]['cate_id'] == $catelist[$j]['id'])
		                    {
		                        $catelist[$j]['state'] = array('selected'=>true);
		                    }
		                }
		            }
		        }
		        
		        $data = $this->_gentree($catelist);
		        
		        $json['errno'] = '0';
		        $this->_helper->json($data);
		      
		        break;
		        
		        case 'addonadd':
		            if($this->input->insIds != "" && $this->input->pordId)
		            {

		                $ids = explode(",", $this->input->insIds);
		                
		                foreach ($ids as $row)
		                {
		                    //查询
		                    $addon = $this->_db->select()
		                         ->from(array('o' => 'product_addondata'))
		                         ->where('o.addon_id = ?',$row)
		                         ->where('o.product_id = ?',$this->input->pordId)
		                         ->where('o.status = ?',1)
		                         ->query()
		                         ->fetch();
		                    
		                    if($addon['id'] == "")
		                    {
		                        $this->rows['product_addondata'] = $this->models['product_addondata']->createRow(array(
		                            'addon_id' => $row,
		                            'product_id' => $this->input->pordId,
		                            'status' => 1,
		                        ));
		                        $this->rows['product_addondata']->save();
		                    }

		                }  
		            }
	
		            //查询数据
		            $addondata = $this->_db->select()
    		        	->from(array('o' => 'product_addondata'))
    		            ->joinLeft(array('c' => 'product_addon'),'c.id = o.addon_id')
    		            ->where('o.product_id = ?',$this->input->pordId)
    		            ->where('c.addon_type = ?',0)
    		            ->query()
    		            ->fetchAll();
		            
                    $json['errno'] = '0';
                    $json['ins'] = $addondata;
                    $this->_helper->json($json);
        
                break;
                
                case 'deleteaddon':
                    if($this->input->insId != "" && $this->input->pordId)
                    {
                        //删除原先的商品
                        $this->models['product_addondata']->delete(array('product_id = ?' => $this->input->pordId,'addon_id = ?' => $this->input->insId));
                    }
                
                    $json['errno'] = '0';
                    $json['id'] = $this->input->insId;
                    $this->_helper->json($json);
                
                    break;
                    
              case 'addcontract':
                    if($this->input->conIds != "" && $this->input->contract_id!="")
                    {
                        $this->rows['product'] = $this->models['product']->find($this->input->contract_id)->current();
                        $this->rows['product']->contract_id = $this->input->conIds;
                        $this->rows['product']->save();
                    }
                    
                    //查询关联的合同
                    $contract = array();
                    $contract = $this->_db->select()
                        ->from(array('i' => 'product'))
                        ->joinLeft(array('c' => 'contract'),'c.id = i.contract_id',array('c.id as con_id','contract_name','content'))
                        ->where('i.id = ?',$this->input->contract_id)
                        ->query()
                        ->fetch();
 
                    $json['errno'] = '0';
                    $json['con'] = $contract;
                    $this->_helper->json($json);
                
                    break;
                    
                case 'deletecontract':
                    if($this->input->conId != "" && $this->input->pordId!="")
                    {
                        $this->rows['product'] = $this->models['product']->find($this->input->pordId)->current();
                        $this->rows['product']->contract_id = 0;
                        $this->rows['product']->save();
                    }
                 
                    $json['errno'] = '0';
                    $this->_helper->json($json);
                
                    break;
		    
                case 'addtrip':
                    
                    if($this->input->porduct_id != "" && $this->input->sort!="")
                    {
                        $this->rows['product_trip'] = $this->models['product_trip']->createRow();
                        
                        /* 添加 */

                        $images = implode(",", $this->input->imgup);
                        if($this->input->imgup == "")
                        {
                            $images = "";
                        }
                        
                        $this->rows['product_trip']->title = $this->input->title;
                        $this->rows['product_trip']->content = $this->input->content;
                        $this->rows['product_trip']->info = $this->input->info;
                        $this->rows['product_trip']->sort = $this->input->sort;
                        $this->rows['product_trip']->status = 1;
                        $this->rows['product_trip']->remarks = "";
                        $this->rows['product_trip']->images = $images;
                        $this->rows['product_trip']->product_id = $this->input->porduct_id;
                        $trip = $this->rows['product_trip']->save();
                    }
                     
                    $json['errno'] = '0';
                    $this->_helper->json($json);
                
                    break;

                case 'deletetrip':
                    if($this->input->id != "")
                    {
                            $this->rows['product_trip'] = $this->models['product_trip']->find($this->input->id)->current();
                            $this->rows['product_trip']->status = -1;
                            $this->rows['product_trip']->save();
                    }
                     
                    $json['errno'] = '0';
                    $this->_helper->json($json);
                
                    break;
                    
                case 'addticket':
                    if($this->input->product_id !="")
                    {
                        $this->rows['product_ticket'] = $this->models['product_ticket']->createRow();
                        
                        /* 添加 */
                        
                        $this->rows['product_ticket']->type = $this->input->type;
                        $this->rows['product_ticket']->time = strtotime($this->input->go_time);
                        $this->rows['product_ticket']->flight = $this->input->flight;
                        $this->rows['product_ticket']->berths = $this->input->berths;
                        $this->rows['product_ticket']->go_area = $this->input->go_area;
                        $this->rows['product_ticket']->go_time = strtotime($this->input->go_time);
                        $this->rows['product_ticket']->go_airport = $this->input->go_airport;
                        $this->rows['product_ticket']->return_area = $this->input->return_area;
                        $this->rows['product_ticket']->return_time = strtotime($this->input->return_time);
                        $this->rows['product_ticket']->return_airport = $this->input->return_airport;
                        $this->rows['product_ticket']->price = $this->input->price;
                        $this->rows['product_ticket']->company = $this->input->company;
                        $this->rows['product_ticket']->product_id = $this->input->product_id;
                        $this->rows['product_ticket']->save();
                     }
                 
                    $json['errno'] = '0';
                    $this->_helper->json($json);
                    
                    break;
                    
                case 'editticket':
                    if($this->input->id !="")
                    {
                        $ticket = $this->_db->select()
                            ->from(array('o' => 'product_ticket'))
                            ->where('o.id = ?',$this->input->id)
                            ->query()
                            ->fetch();

                        $this->view->ticket = $ticket;
                    }

                    //查询商品出行日期
                    $travelDate = $this->_db->select()
                        ->from(array('o' => 'product'))
                        ->where('o.parent_id = ?',$this->input->prod_id)
                        ->where('o.status <> ?',-1)
                        ->query()
                        ->fetchAll();
                     
                    $this->view->travel_date = $travelDate;
                    
                    $json['errno'] = '0';
                    $json['html'] = $this->view->render('productcp/product/editticket.tpl');
                    $this->_helper->json($json);
                    
                    break;
                case 'deitvalticket':
                    if($this->input->id !="")
                    {
                        $this->rows['product_ticket'] = $this->models['product_ticket']->find($this->input->id)->current();
                        $this->rows['product_ticket']->type = $this->input->type;
                        $this->rows['product_ticket']->time = strtotime($this->input->go_time);
                        $this->rows['product_ticket']->flight = $this->input->flight;
                        $this->rows['product_ticket']->berths = $this->input->berths;
                        $this->rows['product_ticket']->go_area = $this->input->go_area;
                        $this->rows['product_ticket']->go_time = strtotime($this->input->go_time);
                        $this->rows['product_ticket']->go_airport = $this->input->go_airport;
                        $this->rows['product_ticket']->return_area = $this->input->return_area;
                        $this->rows['product_ticket']->return_time = strtotime($this->input->return_time);
                        $this->rows['product_ticket']->return_airport = $this->input->return_airport;
                        $this->rows['product_ticket']->price = $this->input->price;
                        $this->rows['product_ticket']->company = $this->input->company;
                        $this->rows['product_ticket']->product_id = $this->input->product_id;
                        $ticket = $this->rows['product_ticket']->save();
                    }
                  
                    if($ticket)
                    {
                        $json['errno'] = '0';
                        $this->_helper->json($json);
                    }
                    else 
                    {
                        $json['errno'] = '1';
                        $this->_helper->json($json);
                    }
                    break;
                    
                case 'deleteticket':
                
                    if($this->input->id !="")
                    {
                        $this->rows['product_ticket'] = $this->models['product_ticket']->find($this->input->id)->current();
                        $this->rows['product_ticket']->status = -1;
                        $this->rows['product_ticket']->save();
                    }
                    
                    $json['errno'] = '0';
                    $this->_helper->json($json);
                    break;
                    
    			case 'catechanged' :
    			    
    				/* 商品类型信息 */
    			    
    				list($attrs,$params,$specs) = getProductTypeInfoByCateId($this->input->cate_id);
    				$this->view->attrs = $attrs;
    				$this->view->params = $params;
    				$this->view->specs = $specs;
    				
    				$json['errno'] = '0';
    				$json['attrs'] = $this->view->render('productcp/product/attrs.tpl');
    				$json['params'] = $this->view->render('productcp/product/params.tpl');
    				$json['specs'] = $this->view->render('productcp/product/specs.tpl');
    				break ;
				
//     			case 'addspec' :
    			    
//     				/* 商品类型信息 */
//                     $specval = $this->_db->select()
//                         ->from(array('o' => 'product_specval'))
//                         ->query()
//                         ->fetchAll();
                        
//                     $this->view->i = $this->input->i;
//                     $this->view->time = $this->input->time;
//                     $this->view->specs = $specval;    
//     				$json['errno'] = '0';
//     				$json['html'] = $this->view->render('productcp/product/addspec.tpl');
//     				break ;

    				
    				/* 排序*/
    				
			case 'order' :
			    $this->rows['product'] = $this->models['product']->find($this->input->id)->current();
			    $this->rows['product']->order = $this->input->order;
			    $this->rows['product']->save();
			
			    $json['errno'] = '0';
			    $this->_helper->json($json);
			    break;
			    	
			    /* 批量排序*/
				
			case 'orderlist':
			    if (is_array($this->input->id))
			    {
			        $ids = implode(',', array_values($this->input->id));
			        $sql = "UPDATE `product` SET `order` = CASE `id` ";
			        foreach ($this->input->id as $key => $id)
			        {
			            $sql .= sprintf("WHEN %d THEN '%d' ", $id, $this->input->order[$key]);
			        }
			        $sql .= "END WHERE `id` IN ($ids)";
			        $db = Zend_Registry::get('db');
			        $db->query($sql);
			        	
			        $json['errno'] = 0;
			        $this->_helper->json($json);
			    }
			    break;
    				    
			case 'addspecval' :
			    if($this->input->type != "")
			    {
			        /* 商品类型信息 */
			        $type = $this->_db->select()
    			        ->from(array('o' => 'product_type'),array('spec_1','spec_2','spec_3'))
    			        ->where('o.id = ?',$this->input->type)
    			        ->query()
    			        ->fetch();
			        
			        $spec_array = array($type['spec_1'],$type['spec_2'],$type['spec_3']);
			        //查询规格
			        for($i=0;$i<count($spec_array);$i++)
			        {
			            if($spec_array[$i] != 0)
			            {
			                $spec_val = $this->_db->select()
    			                ->from(array('i' => 'product_specval'),array('value','id','spec_id'))
    			                ->where('i.spec_id = ?',$spec_array[$i])
    			                ->query()
    			                ->fetchAll();

			                $spec = $this->_db->select()
    			                ->from(array('i' => 'product_spec'),array('spec_name','id'))
    			                ->where('i.id = ?',$spec_val[0]['spec_id'])
    			                ->query()
    			                ->fetch();
			                
			                    $option[$i]['name'] = $spec['spec_name'];
			                    $option[$i]['value'] =$spec_val;
			            }
			        }
			       
			    }
			    $json['errno'] = '0';
			    $json['option'] = $option;
			    $this->_helper->json($json);
			    break ;
				
			case 'addmultispec' :
			    
				/* 商品类型信息 */
			    
				list($attrs,$params,$specs) = getProductTypeInfoByCateId($this->input->cate_id);
				$this->view->specs = $specs;
				$this->view->from = $this->input->from;
				$this->view->to = $this->input->to;
				$this->view->art = $this->input->art;
				$this->view->product_name = $this->input->product_name;
				$this->view->price = $this->input->price;
				$this->view->cost_price = $this->input->cost_price;
				$this->view->mktprice = $this->input->mktprice;
				$this->view->stock = $this->input->stock;
				
				$json['errno'] = '0';
				$json['html'] = $this->view->render('productcp/product/addmultispec.tpl');
				break ;
				
			case 'weight' :
				$this->rows['product'] = $this->models['product']->find($this->input->id)->current();
				$this->rows['product']->weight = $this->input->weight;
				$this->rows['product']->save();
				
				$json['errno'] = '0';
				$this->_helper->json($json);
				break;
				
			case 'sells' :
				$this->rows['product'] = $this->models['product']->find($this->input->id)->current();
				$this->rows['product']->sells = $this->input->sells;
				$this->rows['product']->save();
				
				$json['errno'] = '0';
				$this->_helper->json($json);
				break;
				
			//增加房间
			case 'addroom' :
				$this->rows['product_addon'] = $this->models['product_addon']->createRow();
				$this->rows['product_addon']->addon_name = $this->input->addon_name;
				$this->rows['product_addon']->image = $this->input->image;
				$this->rows['product_addon']->addon_type = 1;
				$this->rows['product_addon']->price = $this->input->price;
				$extra['num'] = (int)$this->input->num;
				$extra['area'] = empty($this->input->area) ? ' ' :$this->input->area;
				$extra['floor'] = empty($this->input->floor) ? ' ' :$this->input->floor;
				$extra['stock'] = (int)$this->input->stock;
				$extra['facilities'] = empty($this->input->facilities) ? ' ' :$this->input->facilities;
				$this->rows['product_addon']->extra = Zend_Serializer::serialize($extra);
				$addonId = $this->rows['product_addon']->save();
				
				$this->rows['product_addondata'] = $this->models['product_addondata']->createRow();
				$this->rows['product_addondata']->product_id = $this->input->product_id;
				$this->rows['product_addondata']->addon_id = $addonId;
				$this->rows['product_addondata']->status = 1;
				$this->rows['product_addondata']->save();
				
				$json['errno'] = '0';
				$this->_helper->json($json);
				break;
				
			case 'deleteroom':
				$this->rows['product_addon'] = $this->models['product_addon']->find($this->input->id)->current();
				$this->rows['product_addon']->status = -1;
				$this->rows['product_addon']->save();
				
				$this->rows['product_addondata'] = $this->models['product_addondata']->fetchRow("addon_id = {$this->input->id}");
				$this->rows['product_addondata']->status = -1;
				$this->rows['product_addondata']->save();
				
				$json['errno'] = '0';
				$this->_helper->json($json);
				break;
				
			default :
				break ;
		}
		$this->_helper->json($json);
	}
	
	 
	
	/**
	 *  导出商品EXCEL
	 */
	public function  exportitemAction()
	{
		/* 检验传值 */
	    
		if (!params($this)) 
		{
			/* 提示 */
		    
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/productcp/product',
					'text' => '返回')
			));
		}
		
		/* 引入PHPExcel相关文件 */
		
		require_once 'lib/api/phpexcel/PHPExcel.php';
		require_once 'lib/api/phpexcel/PHPExcel/IOFactory.php';
		require_once 'lib/api/phpexcel/PHPExcel/Writer/Excel5.php';		
		$resultPHPExcel = new PHPExcel();		
		
		/* 构造 SQL 选择器 */
		
		$perpage = 50;
		$select = $this->_db->select()
			->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)'))) 
			->where('p.area = ?',$this->paramInput->area);
		$query = ''; 
		
		$query .= "&area={$this->paramInput->area}";
		
		$select->where("p.status = ?",$this->paramInput->status);
		$query .= "&status={$this->paramInput->status}";
			
		if (!empty($this->paramInput->id)) 
		{
			$select->where('p.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		
		if (!empty($this->paramInput->product_name)) 
		{
			$select->where("p.product_name like '%{$this->paramInput->product_name}%'");
			$query .= "&product_name={$this->paramInput->product_name}";
		}
		
		if (!empty($this->paramInput->cate_id)) 
		{
			$select->where("p.cate_id = ?",$this->paramInput->cate_id);
			$query .= "&cate_id={$this->paramInput->cate_id}";
		}
		
		if (!empty($this->paramInput->price_from)) 
		{
			$select->where("p.price >= ?",$this->paramInput->price_from);
			$query .= "&price_from={$this->paramInput->price_from}";
		}
		
		if (!empty($this->paramInput->price_to)) 
		{
			$select->where("p.price <= ?",$this->paramInput->price_to);
			$query .= "&price_to={$this->paramInput->price_to}";
		}
		
		if (!empty($this->paramInput->sn)) 
		{
			$select->where("p.sn like '%{$this->paramInput->sn}%'");
			$query .= "&sn={$this->paramInput->sn}";
		}
		
		if (!empty($this->paramInput->art)) 
		{
			$select->where('p.art = ?',$this->paramInput->art);
			$query .= "&art={$this->paramInput->art}";
		}
		
		if (!empty($this->paramInput->dateline_from)) 
		{
			$select->where("p.dateline >= ?",strtotime($this->paramInput->dateline_from));
			$query .= "&dateline_from={$this->paramInput->dateline_from}";
		}
		
		if (!empty($this->paramInput->dateline_to)) 
		{
			$select->where("p.dateline <= ?",strtotime($this->paramInput->dateline_to));
			$query .= "&dateline_to={$this->paramInput->dateline_to}";
		}
		
		/* 分页 */
  
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		
		/* 列表 */
		
		$productList = $select->reset(Zend_Db_Select::COLUMNS)
			->columns(array('id','area','price','sells'),'p')			
			->order('p.dateline DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		 
		/* 设置参数 */
		
		$resultPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$resultPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
 		$resultPHPExcel->getActiveSheet()->setCellValue('A1','商品ID');
		$resultPHPExcel->getActiveSheet()->setCellValue('B1','编码');
		$resultPHPExcel->getActiveSheet()->setCellValue('C1','货号');
		$resultPHPExcel->getActiveSheet()->setCellValue('D1','消费区');
		$resultPHPExcel->getActiveSheet()->setCellValue('E1','商品名'); 
 		$resultPHPExcel->getActiveSheet()->setCellValue('F1','颜色');
		$resultPHPExcel->getActiveSheet()->setCellValue('G1','尺码');
		$resultPHPExcel->getActiveSheet()->setCellValue('H1','价格');
		$resultPHPExcel->getActiveSheet()->setCellValue('I1','已售出');
		$resultPHPExcel->getActiveSheet()->setCellValue('J1','库存');	
 		//加粗居中
		$resultPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray(		 
		            array(		 
		                'font' => array (		 
		                    'bold' => true		 
		                ),
		                		 
		                'alignment' => array(		 
		                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER		 
		                )		 
		            )		 
		        );

		foreach($productList as $k=>$v)
		{
			 $arr[]=$v['id']; 
		} 
	
		$results = $this->_db->select()
			->from(array('i' => 'product_item'),array('product_id','item_name','fn','art','stock','specval_1','specval_2'))
			->where('i.product_id IN (?)',$arr)
			->where('i.status = ?',1)
			->query()
			->fetchAll();
 
		 foreach($results as $key=>$val)
		 {
 		 	foreach($productList as $k=>$v)
		 	{
 				if($val['product_id'] == $v['id'])
				{
 					$results[$key]['area']  = $v['area'];
 					$results[$key]['price'] = $v['price'];
 					$results[$key]['sells'] = $v['sells'];
				} 
		 	} 
 		 }
		 // 数据
		$i = 2;
 
 		foreach($results as $value){
				$title='';			
	 			$resultPHPExcel->getActiveSheet()->getStyle("B{$i}")->getAlignment()->setWrapText(true);
				$resultPHPExcel->getActiveSheet()->getStyle("D{$i}")->getNumberFormat()   
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$resultPHPExcel->getActiveSheet()->getStyle("G{$i}")->getNumberFormat()   
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$resultPHPExcel->getActiveSheet()->getStyle("I{$i}")->getNumberFormat()   
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); 
				
				$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i,$value['product_id']);
				$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i,$value['fn']);
				$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i,$value['art']);
				$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i,$title);
				$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i,$value['item_name']);				
				$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i,$value['specval_1']);
				$resultPHPExcel->getActiveSheet()->setCellValue('G' . $i,$value['specval_2']);
				$resultPHPExcel->getActiveSheet()->setCellValue('H' . $i,$value['price']);
				$resultPHPExcel->getActiveSheet()->setCellValue('I' . $i,$value['sells']);
				$resultPHPExcel->getActiveSheet()->setCellValue('J' . $i,$value['stock']);  
				
				$i ++;				 
 		}
  
		//设置导出文件名 
		$page = $corepage->currPage();
		$pageCount = $corepage->getPagecount();

		$date=date("Y_m_d H_i_s");
		$outputFileName = ($page == $pageCount) ? "product_{$date}__{$page}_last.xls" : "product_{$date}_{$page}.xls"; 
		$xlsWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
		$filename = "runtime/{$outputFileName}";
		if (is_file($filename)) {
			@unlink($filename);
		}
		$xlsWriter->save($filename);
		
		//ob_start();ob_flush();
//		header("Content-Type: application/force-download");
//		header("Content-Type: application/octet-stream");
//		header("Content-Type: application/download");
//		header('Content-Disposition:inline;filename="'.$outputFileName.'"');
//		header("Content-Transfer-Encoding: binary");
//		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//		header("Pragma: no-cache");
		
		//$xlsWriter->save( "php://output" );
		
//		$finalFileName = 'runtime/' . SCRIPT_TIME . '.xls';
//		$xlsWriter->save($finalFileName);
//	 
		$this->view->downloadUrl = "/{$filename}";
		//$this->view->downloadUrl = "/{$filename}"; 
	 	//$this->view->downloadUrl = "/{$filename}";
	 
		$nextPage = $page + 1;
		$this->view->nextUrl = ($page == $pageCount) ? '' : "/productcp/product/exportitem?page={$nextPage}" . $query;	
		 
	} 

	//库存同步
	public function invsynAction(){
		if ($this->_request->isPost()) 
		{
			$adapter = new Zend_File_Transfer();
			$adapter->addValidator('Extension',true,'xlsx,xls');
			
			if ($adapter->isValid('file')) 
			{
				$ext = strtolower(strrchr($_FILES['file']['name'],'.'));
				$destination = 'static/data/excel/' . date('Y/m/d/',time()) . date('His',time()) . mt_rand(1000,100000) . "{$ext}";
				$dirs = dirname($destination);
			    if (!is_dir($dirs))
			    {
			    	mkdir($dirs,0777,true);
			    }
				$adapter->addFilter('Rename',$destination,'file');
				$adapter->receive('file');
				Core_Cookie::set('lastImportTime',SCRIPT_TIME);
				$file_path = $destination;
				require_once 'lib/api/phpexcel/PHPExcel.php';
				require_once 'lib/api/phpexcel/PHPExcel/IOFactory.php';
				require_once 'lib/api/phpexcel/PHPExcel/Reader/Excel5.php';
				$PHPReader = new PHPExcel_Reader_Excel2007();
				if(!$PHPReader->canRead($file_path)){
					$PHPReader = new PHPExcel_Reader_Excel5();
					if(!$PHPReader->canRead($file_path)){
						$this->error('Excel文件处理错误!');
					}
				}
				$PHPExcel = $PHPReader->load($file_path);
				$currentSheet = $PHPExcel->getSheet(0);
				$data = $currentSheet->ToArray();
				echo '<strong>缺省产品</strong><br>';
				for($i=4;$i<count($data);$i++){
					if($data[$i][0]){
						$fn = rtrim($data[$i][0]);//货号
						$product_name = rtrim($data[$i][1]);//商品名称
						preg_match('/\w+/i',$fn,$fn_ar);
						$fn = $fn_ar[0];
					}
					$stock = trim($data[$i][6]);//库存
					$specval_1 = trim($data[$i][4]);//颜色
					$specval_2 = trim($data[$i][5]);//尺码
					if($stock=='' || $specval_1=='' || $specval_2=='' || $fn==''){
					  continue;
					}
					/*
					$where = array();
					$where[] =  $this->_db->quoteInto('fn = ?', $fn);
					$where[] =  $this->_db->quoteInto('specval_1 = ?', $specval_1);
					$where[] =  $this->_db->quoteInto('specval_2 = ?', $specval_2);
					$set['stock'] = $stock;
					$rows_affected = $this->models['product_item']->update($set, $where);
					*/
					$select = $this->_db->select()
						->from(array('p' => 'product_item'),array('id','item_name','fn','specval_1','specval_2'))
						->where('p.fn = ? ',$fn)
						->where('p.specval_1 = ? ',$specval_1)
						->where('p.specval_2 = ? ',$specval_2);
					$vo = $select->query()->fetch();
					//修改库存
					if($vo){
						$this->rows['product_item'] = $this->models['product_item']->find($vo['id'])->current();
						$this->rows['product_item']->stock = $stock;
						$num = $this->rows['product_item']->save();
					}else{
					  echo '编码: '.$fn.' 产品:'.$product_name.' 规格: '.$specval_1.' '.$specval_2.'<br>';
					  continue;
					}
				}
				echo '导入完成<a href="/productcp/product/invsyn">返回</a>';
				exit;
			}
		}
		$lastImportTime = Core_Cookie::get('lastImportTime');
		echo '上次导入时间 :'.date('Y-m-d H:i:s',$lastImportTime);
	}
	
	
	private  function _gentree($items,$id='id',$pid='parent_id',$son = 'children')
	{

	    $tree = array(); //格式化的树
	    $tmpMap = array();  //临时扁平数据
	
	    foreach ($items as $item)
	    {
	        $tmpMap[$item[$id]] = $item;
	    }

	    foreach ($items as $item)
	    {
	        if (isset($tmpMap[$item[$pid]]))
	        {
	            $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
	        } else
	        {
	            $tree[] =&$tmpMap[$item[$id]];
	        }
	    }
	    return $tree;
	}
	
	
	/**
	 * 保险
	 */
	public function addonAction()
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
	    
	    $this->view->product_id = $this->paramInput->id;
	    
	    //查询商品名
	    $productName = $this->_db->select()
    	    ->from(array('o' => 'product'),array('product_name'))
    	    ->where('o.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();
	     
	    $this->view->product_name = $productName['product_name'];
	    
	    
	    //查询保险
	    $addon = array();
	    $addon = $this->_db->select()  
	       ->from(array('o' => 'product_addon'))
	       ->where('o.status = ?',1)
	       ->where('o.addon_type = ?',0)
	       ->query()
	       ->fetchAll();
	    
	    $this->view->addon = $addon;
	    
	    //查询关联的保险
	    $pruductaddon = array();
	    $pruductaddon = $this->_db->select()
	       ->from(array('i' => 'product_addondata'))
	       ->joinLeft(array('c' => 'product_addon'),'c.id = i.addon_id')
	       ->where('i.product_id = ?',$this->paramInput->id)
	       ->where('c.addon_type = ?',0)
	       ->where('i.status = ?',1)
	       ->query()
	       ->fetchAll();
	    
	    foreach ($pruductaddon as $data)
	    {
	        $addonids[] = $data[id];
	    }
	    
        $this->view->addonids = implode(",", $addonids);
	    $this->view->pruductaddon = $pruductaddon;
	}
	
	/**
	 * 合同
	 */
	public function contractAction()
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
	     
	    $this->view->product_id = $this->paramInput->id;
	    
	    $product = $this->_db->select()
	       ->from(array('i' => 'product'))
	       ->where('i.id = ?',$this->paramInput->id)
	       ->query()
	       ->fetch();
	    $this->view->product = $product;
	    
	    //查询关联的合同
	    $contract = array();
	    $contract = $this->_db->select()
    	    ->from(array('i' => 'product'))
    	    ->joinLeft(array('c' => 'contract'),'c.id = i.contract_id',array('c.id as con_id','contract_name','content'))
    	    ->where('i.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();
	    
	    $this->view->contract = $contract;

	    //合同
	    $contracts = $this->_db->select()
    	    ->from(array('i' => 'contract'))
    	    ->where('i.status = ?',1)
    	    ->query()
    	    ->fetchAll();
	    $this->view->contracts = $contracts;

	}
	
	/**
	 * 行程
	 */
	public function tripAction()
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
	    
	    $this->view->product_id = $this->paramInput->id;
	    
	    //查询行程
	    $trip = $this->_db->select()
	       ->from(array('o' => 'product_trip'))
	       ->where('o.product_id = ?',$this->paramInput->id)
	       ->where('o.status = ?',1)
	       ->order('sort asc')
	       ->query()
	       ->fetchAll();
	    
	    //查询商品名
	    $product_name = $this->_db->select()
    	    ->from(array('o' => 'product'),array('product_name'))
    	    ->where('o.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();
	    
	    $this->view->product_name = $product_name['product_name'];
	    
	    $this->view->trip = $trip;

	}
	
	/**
	 * 机票信息
	 */
	public function ticketAction()
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
	     
	    $this->view->product_id = $this->paramInput->id;
	     
	    //查询商品子类
	    $childerProduct = $this->_db->select()
    	    ->from(array('p' => 'product'),array('id'))
    	    ->where('p.parent_id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetchAll();
	    
	    //查询机票信息
	    $goticket = $this->_db->select()
	       ->from(array('o' => 'product_ticket'))
	       ->joinLeft(array('p' => 'product'), 'o.product_id = p.id',array('travel_date'))
	       ->where('o.product_id in(?)',$childerProduct)
	       ->where('o.status = ?',1)
	       ->where('o.type = ?',0)
	       ->query()
	       ->fetchAll();
	    
	    $this->view->goticket = $goticket;

	    $returnticket = $this->_db->select()
            ->from(array('o' => 'product_ticket'))
            ->joinLeft(array('p' => 'product'), 'o.product_id = p.id',array('travel_date'))
             ->where('o.product_id in(?)',$childerProduct)
            ->where('o.status = ?',1)
            ->where('o.type = ?',1)
            ->query()
            ->fetchAll();
	    
	    $this->view->returnticket = $returnticket;
	    
	    //查询商品名
	    $product_name = $this->_db->select()
    	    ->from(array('o' => 'product'),array('product_name'))
    	    ->where('o.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();
	    
	    
	    //查询商品出行日期
	    $travelDate = $this->_db->select()
    	    ->from(array('o' => 'product'))
    	    ->where('o.parent_id = ?',$this->paramInput->id)
    	    ->where('o.status <> ?',-1)
    	    ->query()
    	    ->fetchAll();
	    
	    $this->view->travel_date = $travelDate;
	     
	    $this->view->product_name = $product_name['product_name'];
	}
	
	/**
	 * 修改行程
	 */
	public function edittripAction()
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
	    
	    $this->view->trip_id = $this->paramInput->id;
	    
        $trip = $this->_db->select()
            ->from(array('o' => 'product_trip'))
            ->where('o.id = ?',$this->paramInput->id)
            ->query()
            ->fetch();

        $this->view->product_id = $trip['product_id'];
        
        $tripimgs = explode(",", $trip['images']);

        $this->view->tripimgs = $tripimgs;

        $this->view->trip = $trip;
        
    	if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
			    $images = implode(",", $this->input->imgup);

			    $this->rows['product_trip'] = $this->models['product_trip']->find($this->input->trip_id)->current();
			    $this->rows['product_trip']->title = $this->input->title;
			    $this->rows['product_trip']->content = $this->input->content;
			    $this->rows['product_trip']->remarks = $this->input->remarks;
			    $this->rows['product_trip']->info = $this->input->info;
			    $this->rows['product_trip']->sort = $this->input->sort;
			    $this->rows['product_trip']->remarks = $this->input->remarks;
			    $this->rows['product_trip']->images = $images;
			    $this->rows['product_trip']->save();

			    /* 提示 */
			    
			    $this->_helper->notice('编辑成功','','success',array(
			        array(
			            'href' => "/productcp/product/edittrip?id=".$this->input->trip_id,
			            'text' => '返回')
			    ));
			}
		}
	}
	
	/**
	 * 签证
	 */
	public function visaAction()
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
	    
	    $this->view->product_id = $this->paramInput->id;
	    
	    //查询商品名
	    $product_name = $this->_db->select()
    	    ->from(array('o' => 'product'),array('product_name'))
    	    ->where('o.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();
	     
	    $this->view->product_name = $product_name['product_name'];
	    
        $product_visa = $this->_db->select()
           ->from(array('o' => 'product_visadata'))
           ->joinLeft(array('c' => 'visa'),'c.id = o.visa_id',array('c.id as visa_id','visa_name'))
           ->where('o.product_id = ?',$this->paramInput->id)
           ->where('o.status = ?',1)
           ->query()
           ->fetchAll();
        
        foreach ($product_visa as $row)
        {
            $visas[] =$row['visa_id']; 
        }
        $this->view->visas = implode(',', $visas);
        
	    $this->view->product_visa = $product_visa;
	    
	    //所有签证
	    $visaList = $this->_db->select()
	       ->from(array('o' => 'visa'))
	       ->where('o.parent_id = ?',0)
	       ->where('o.status = ?',1)
	       ->query()
	       ->fetchAll();
	    
	    $this->view->visaList = $visaList;
	}
	
	/**
	 *  房间
	 */
	public function roomAction()
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
		
		//商品名
	    $product_name = $this->_db->select()
    	    ->from(array('o' => 'product'),array('product_name'))
    	    ->where('o.id = ?',$this->paramInput->id)
    	    ->query()
    	    ->fetch();
	     
	    $this->view->product_name = $product_name['product_name'];
	    
		//房间列表
		$roomList = array();
		$result = $this->_db->select()
			->from(array('d' =>'product_addondata'))
			->joinLeft(array('a' => 'product_addon'), 'd.addon_id = a.id')
			->where('d.product_id = ?',$this->paramInput->id)
			->where('d.status = ?',1)
			->where('a.addon_type = ?',1)
			->query()
			->fetchAll();
		
		if (!empty($result))
		{
			foreach ($result as $room)
			{
				$room['extra']= Zend_Serializer::unserialize($room['extra']);
				$roomList[] = $room;
			}
		}
		
		$this->view->roomList = $roomList;
	}
	
	/**
	 *  修改房间
	 */
	public function editroomAction()
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
		
		//房间信息
		$room = $this->_db->select()
			->from(array('a' => 'product_addon'))
			->where('a.id = ?',$this->paramInput->id)
			->where('a.addon_type = ?',1)
			->query()
			->fetch();
		$room['extra'] = Zend_Serializer::unserialize($room['extra']);
		
		$this->view->room = $room;
		
		//修改
		if ($this->_request->isPost())
		{
			
			if (form($this))
			{	
				$this->rows['product_addon'] = $this->models['product_addon']->find($this->input->id)->current();
				$this->rows['product_addon']->addon_name = $this->input->addon_name;
				$this->rows['product_addon']->image = $this->input->image;
				$this->rows['product_addon']->price = $this->input->price;
				$extra['num'] = (int)$this->input->num;
				$extra['area'] = empty($this->input->area) ? ' ' :$this->input->area;
				$extra['floor'] = empty($this->input->floor) ? ' ' :$this->input->floor;
				$extra['stock'] = (int)$this->input->stock;
				$extra['facilities'] = empty($this->input->facilities) ? ' ' :$this->input->facilities;
				$this->rows['product_addon']->extra = Zend_Serializer::serialize($extra);
				$this->rows['product_addon']->save();
		
				/* 提示 */
				 
				$productId = $this->_db->select()
					->from(array('d' => 'product_addondata'),'product_id')
					->where('d.addon_id = ?',$this->input->id)
					->query()
					->fetchColumn();
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => "/productcp/product/room?id=".$productId,
						'text' => '返回')
				));
			}
		}
	}
}

?>