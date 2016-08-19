<?php

class Orderapi_LinerorderController extends Core2_Controller_Action_Api  
{
    /**
     * 初始化
     */
    public function init()
    {	
        parent::init();
        $this->models['member'] = new Model_Member();
        $this->models['order'] = new Model_Order();
        $this->models['order_item'] = new Model_OrderItem();
        $this->models['product_item'] = new Model_ProductItem();
        $this->models['product'] = new Model_Product();
        $this->models['order_tourist'] = new Model_OrderTourist();
        $this->models['order_addon'] = new Model_OrderAddon();
        $this->models['order_contract'] = new Model_OrderContract();
        $this->models['contract'] = new Model_Contract();
        $this->models['tourist'] = new Model_Tourist();
        $this->models['product_visadata'] = new Model_ProductVisadata();
        $this->models['vids'] = new Model_Visa();
    }
    
	/**
	 *  确认订单
	 */
    public function confirmAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 传入一个数组 包含item id 跟 数量*/
		$sum = 0;
		foreach ($this->data['items'] as $i)
		{
		    if($i['num'] > 0)
		    {
		        $itemInfo = $this->_db->select()
    		        ->from(array('p' => 'product'),array('product_name','travel_date','contract_id','image','travel_restrictions','parent_id','information','sn'))
    		        ->joinLeft(array('i' => 'product_item'), 'i.product_id = p.id',array('id','item_name','price','product_id','spec_desc','child_price','stock'))
    		        ->where('p.id = ?',$i['id'])
    		        ->where('p.status = ?',2)
    		        ->where('i.status <> ?',-1)
    		        ->order('i.price asc')
    		        ->limit(1)
    		        ->query()
    		        ->fetch();

		        if($itemInfo == "")
		        {
		            $this->json['errno'] = '1';
		            $this->json['errmsg'] = '商品不存在';
		            $this->_helper->json($this->json);
		        }
		        $item = array();
		        $item['id'] = $i['id'];
		        $item['item_name'] = $itemInfo['item_name'];
		        
		        if($i['type'] == 0)
		        {
		            $item['price'] = $itemInfo['child_price'];
		        }
		        else if($i['type'] == 1)
		        {
		            $item['price'] = $itemInfo['price'];
		        }
		        
		        $item['num'] = $i['num'];
		        $item['product_id'] = $itemInfo['product_id'];
		        $item['image'] = $itemInfo['image'];
		        $item['spec_desc'] = $i['spec_desc'];
		        $item['product_name'] = $itemInfo['product_name'];
		        $product['items'][] = $item;
		        
		        //商品的图片
		        $productImage = $this->_db->select()
    		        ->from(array('q' => 'product'),array('image'))
    		        ->where('q.id = ?', $itemInfo['parent_id'])
    		        ->query()
    		        ->fetch();
		         
		        $product['image'] = $productImage['image'];
		        $product['subject'] = $itemInfo['product_name'];
		        $product['sn'] = $itemInfo['sn'];
		        $product['travel_date'] = $itemInfo['travel_date'];
		        //	$product['subject'] = $product['subject'].$itemInfo['item_name'].$i['num'];
		        if($i['is_adult'] == 0)
		        {
		            $product['item_amount'] += $i['num'] * $itemInfo['child_price'];
		        }
		        else if($i['is_adult'] == 1)
		        {
		            $product['item_amount'] += $i['num'] * $itemInfo['price'];
		        }
		        $product['product_name'] = $itemInfo['product_name'];
		        $product['num'] += $i['num'];
		        $product_id = $itemInfo['parent_id'];
		        $product['information'] = $itemInfo['information'];
		        $product['stock'] = $itemInfo['stock'];
		        $sum += $i['num'];
		        $product_items['is_adult'] = $i['is_adult'];
		        $product_items['num'] = $i['num'];
		        $renshu[]=$product_items;
		        
		        $travel_restrictions = $itemInfo['travel_restrictions'];
		    }
		}

		
		/*商品保险*/
		
		$addon = $this->_db->select()
	      ->from(array('o' => 'product_addondata'))
	      ->joinLeft(array('p' => 'product_addon'), 'p.id = o.addon_id',array('id as insurance_id','type','title','addon_name','price','info'))
	      ->joinLeft(array('i' => 'product'), 'o.product_id = i.parent_id',array('id as parent_product_id'))
	      ->where('i.id = ?',$item['product_id'])
	      ->where('p.addon_type = ?',0)
	      ->query()
	      ->fetchAll();
		
		for ($i=0;$i<count($addon);$i++)
		{
			unset($addon[$i]['addon_type']);
			unset($addon[$i]['image']);
			unset($addon[$i]['extra']);
			unset($addon[$i]['dateline']);
            unset($addon[$i]['id']);
            unset($addon[$i]['addon_id']);
            unset($addon[$i]['status']);
          //  $product_id = $addon[$i]['product_id'];
            unset($addon[$i]['parent_product_id']);
            unset($addon[$i]['product_id']);
		}
		
		/*房间数据*/
		$result = $this->_db->select()
		      ->from(array('p' => 'product_addon'))
		      ->joinLeft(array('o' => 'product_addondata'), 'p.id = o.addon_id')
		      ->where('p.addon_type  = ?',1)
		      ->where('o.product_id = ?',$product_id)
		      ->where('o.status <> ?',-1)
		      ->query()
		      ->fetchAll();
		
		$roomList = array();
		
		if (!empty($result))
		{
		    foreach ($result as $room)
		    {
		        $room['extra']= Zend_Serializer::unserialize($room['extra']);
		        unset($room['id']);
		        unset($room['type']);
		        unset($room['title']);
		        unset($room['info']);
		        $room['room_id'] = $room['addon_id'];
                $room['price'] = intPrice($room['price']);
		        unset($room['addon_id']);
		        $roomList[] = $room;
		    }
		}
		
	    $this->json['errno'] = '0';
	    $this->json['product_id'] = $product_id;
	    $this->json['travel_date'] = date("Y-m-d",$product['travel_date']);
	    $this->json['sn'] = $product['sn'];
	    $this->json['product_name'] = $product['product_name'];
	    $this->json['image'] = $product['image'];
	    $this->json['buyer'] = $renshu;
		$this->json['travel_restrictions'] = $travel_restrictions;
		$this->json['information'] =  $product['information'];
		$this->json['insurance'] = $addon;
		$this->json['room_list'] = $roomList;
		$this->json['pay_amount'] =  $product['item_amount'];
		$this->_helper->json($this->json);
	}
	
	/**
	 *  创建订单
	 */
	public function createAction()
	{
			if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}

		/* 商品  规格 */

		$product = array();
		
		/* 传入一个数组 包含item id 跟 数量*/
		$sum = 0;
		foreach ($this->data['items'] as $i)
		{
		    if($i['num'] > 0)
		    {
		        
		        $itemInfo = $this->_db->select()
    		        ->from(array('p' => 'product'),array('product_name','travel_date','contract_id','image','travel_restrictions','parent_id','information','sn','supplier_id'))
    		        ->joinLeft(array('i' => 'product_item'), 'i.product_id = p.id',array('id','item_name','price','product_id','spec_desc','child_price','stock'))
    		        ->where('p.id = ?',$i['id'])
    		        ->where('p.status = ?',2)
    		        ->where('i.status <> ?',-1)
    		        ->order('i.price asc')
    		        ->limit(1)
    		        ->query()
    		        ->fetch();

		        if($itemInfo == "")
		        {
		            $this->json['errno'] = '1';
		            $this->json['errmsg'] = '商品不存在';
		            $this->_helper->json($this->json);
		        }
		        
		        $item = array();
		        $item['id'] = $itemInfo['id'];
		        $item['item_name'] = $itemInfo['item_name'];
		        if($i['is_adult'] == 0)
		        {
		            $item['price'] = $itemInfo['child_price'];
		            $product['item_amount'] += $i['num'] * $itemInfo['child_price'];
		        }
		        else if($i['is_adult'] == 1)
		        {
		            $item['price'] = $itemInfo['price'];
		            $product['item_amount'] += $i['num'] * $itemInfo['price'];
		        }
		        
		        $item['is_adult'] = $i['is_adult'];
		        $item['num'] = $i['num'];
		        $item['product_id'] = $itemInfo['product_id'];
		        $item['image'] = $itemInfo['image'];
		        $item['spec_desc'] = $itemInfo['spec_desc'];
		        $item['product_name'] = $itemInfo['product_name'];
		        $product['items'][] = $item;
		        	
		        $product['subject'] = $itemInfo['product_name'];
		        $product['travel_date'] = $itemInfo['travel_date'];
		        //	$product['subject'] = $product['subject'].$itemInfo['item_name'].$i['num'];
		        $product['num'] += $i['num'];
		        $product['product_id'] = $itemInfo['parent_id'];
		        $product['supplier_id'] = $itemInfo['supplier_id'];
		        $travel_restrictions = $itemInfo['travel_restrictions'];
		        
		        $sum += $i['num'];
		       
		        $product['stock'] = $itemInfo['stock']; 
		        }
		        
		}
		
		/* 保险*/
		
		if (!empty($this->input->insurance_ids))
		{
		    $insurance = array();
		    $insurance = $this->_db->select()
		    ->from(array('a' => 'product_addon'))
		    ->where('a.id in (?)',$this->input->insurance_ids)
		    ->where('a.addon_type = ?',0)
		    ->query()
		    ->fetchAll();
		    foreach ($insurance as $in)
		    {
		        $product['insurance_amount'] += $in['price'] * $product['num'];
		        $product['insurance'][] = $in;
		    }
		}
		
		
		/* 统计价格*/
		
		$product['amount'] = $product['item_amount']+$product['insurance_amount'];
		if ($product['amount'] < 0)
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = '支付错误';
			$this->_helper->json($this->json);
		}

		/* 生成订单 保险 商品 合同*/
		
		if($this->input->email == "")
		{
		    $this->input->email = "";
		}

		$id = $this->models['order']->createId();

		$this->rows['order'] = $this->models['order']->createRow(array(
			'id' => $id,
		    'buyer_name' => $this->input->buyer_name,
		    'mobile' => $this->input->mobile,
		    'email' => $this->input->email,
			'subject' => SITE_NAME . $product['subject'],
			'body' =>  SITE_NAME . $product['subject'],
			'buyer_id' => $this->_user->id,
			'item_amount' => $product['item_amount'],
			'insurance_amount' => "",
			'amount' => $product['amount'],
		    'from' => 1,
		    //客服类型
		    'service_type' => $this->input->service_type,
			//现在没有折扣优惠
			'pay_amount' => $product['amount'],
		//	'memo' => $this->input->memo,
			'status' => $product['amount'] == 0 ? Model_Order::WAIT_SELLER_SEND_GOODS : Model_Order::WAIT_BUYER_PAY
		));
		$this->rows['order']->save();

		/* 生成保险*/
		
		if(!empty($product['insurance']))
		{
			foreach ($product['insurance'] as $ins)
			{
				$this->rows['order_addon'] = $this->models['order_addon']->createRow(array(
					'order_id' => $this->rows['order']->id,
					'addon_id' => $ins['id'],
					'price' => $ins['price'],
					'num' => $product['num'],
				    'dateline' => time(),
				    'status' => 1,
				));
				$this->rows['order_addon']->save();
			}
		}

		/* 生成房间*/
		
		if(!empty($this->data['room_ids']))
		{
		    foreach ($this->data['room_ids'] as $ins)
		    {
		        
		        $room = $this->_db->select()
		              ->from(array('p' => 'product_addon'),array('price'))
		              ->where('p.id = ?',$ins['id'])
		              ->query()
		              ->fetch();
		        
		        $this->rows['order_addon'] = $this->models['order_addon']->createRow(array(
		            'order_id' => $this->rows['order']->id,
		            'addon_id' => $ins['id'],
		         //   'num' => $ins['num'],
		            'price' => $room['price'],
		            'dateline' => time(),
		            'status' => 1,
		        ));
		        $this->rows['order_addon']->save();
		    }
		}
		
		/* 生成订单游客  生成合同*/
		
		if($this->input->tourist_ids != "")
		{
		    $tourist = array();
		    $tourist = $this->_db->select()
    		    ->from(array('a' => 'tourist'))
    		    ->where('a.id in (?)',$this->input->tourist_ids)
    		    ->where('a.member_id = ?',$this->_user->id)
    		    ->query()
    		    ->fetchAll();
		    
		    //添加旅客信息
		    foreach ($tourist as $row)
		    {
                $this->rows['order_tourist'] = $this->models['order_tourist']->createRow(array(
                    'order_id' => $this->rows['order']->id,
                    'tourist_name' => $row['tourist_name'],
                    'cert_type' => $row['cert_type'],
                    'cert_num' => $row['cert_num'],
                    'mobile' => $row['mobile'],
                    'birthday' => $row['birthday'],
                    'sex' => $row['sex'],
                    'cert_deadline' => $row['cert_deadline'],
                ));
                $touristId = $this->rows['order_tourist']->save();
                
                $tourist_name[] = $row['tourist_name'];
		    }
		    
		    //生成合同
		    $contract_id  = $this->_db->select()
		      ->from(array('o' => 'product'),array('contract_id','id'))
		      ->where('o.id =?',$product['product_id'])
		      ->query()
		      ->fetch();
		    
		    //查询供应商名称
		    $supplier= $this->_db->select()
		      ->from(array('a' => 'member_supplier'))
		      ->where('a.id = ?',$product['supplier_id'])
		      ->query()
		      ->fetch();
		    
		    $first_part = implode(",", $tourist_name);
		    
		    if(!$first_part)
		    {
		        $first_part = "";
		    }
		    $this->rows['order_contract'] = $this->models['order_contract']->createRow(array(
		        'order_id' => $this->rows['order']->id,
		        'contract_id' => $contract_id['contract_id'],
		        'no' => $this->rows['order']->id,
		        'first_part' =>$first_part,
		        'second_part' => $supplier['supplier_name'],
		        'dateline' => time(),
		        'status' => 1,
		    ));
		    $this->rows['order_contract']->save();
		}
		
		// 记录订单所含产品
		foreach ($product['items'] as $item)
		{
		    $this->models['order_item']->createRow(array(
		        'order_id' => $this->rows['order']->id,
		        'product_id' => $item['product_id'],
		        'item_id' => $item['id'],
		        'item_name' => $item['item_name'],
		        'image' => $item['image'],
		        'price' => $item['price'],
		        'num' => $item['num'],
		        'is_adult' => $item['is_adult'],
		        'spec_desc' => $item['spec_desc'],
		       ))->save();
		}

		$this->json['errno'] = '0';
		$this->json['order_id'] = $this->rows['order']->id;
		$this->json['pay_amount'] = $this->rows['order']->pay_amount;
		$this->json['status'] = $this->rows['order']->status;
		$this->json['product_name'] = $product['subject'];
		$this->_helper->json($this->json);
	}
    
}