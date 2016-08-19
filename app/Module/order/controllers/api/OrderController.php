<?php

class Orderapi_OrderController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
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
	 *  选择日期
	 */
	public function chooseAction()
	{
		if (!form($this))
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}

		/* 产品 传入一个父产品的id*/
		
		$items = $this->_db->select()
			->from(array('p' => 'product'),array('product_name','travel_date','price','stock'))
			->joinLeft(array('i' => 'product_item'), 'p.id = i.product_id',array('id','product_id','item_name','price as item_price','stock as item_stock'))
			->joinLeft(array('r' => 'region'), 'region_name')
			->where('p.parent_id = ?',$this->input->id)
			->where('i.status = ?',1)
			->query()
			->fetchAll();

		$products = array();
		
		if(!empty($items))
		{
			foreach ($items as $i)
			{
				if (array_key_exists($i['product_id'], $products))
				{
					$item = array();
					$item['id'] = $i['id'];
					$item['item_name'] = $i['item_name'];
					$item['price'] = $i['item_price'];
					$item['item_stock'] = $i['item_stock'];
					$products[$i['product_id']]['item'][] = $item;
				}
				else
				{
	 				$item = array();
					$item['id'] = $i['id'];
					$item['item_name'] = $i['item_name'];
					$item['price'] = $i['item_price'];
					$item['item_stock'] = $i['item_stock'];
					$products[$i['product_id']]['region_name'] = $i['region_name'];
					$products[$i['product_id']]['product_name'] = $i['product_name'];
					$products[$i['product_id']]['stock'] = $i['stock'];
					$products[$i['product_id']]['price'] = $i['price'];
					$products[$i['product_id']]['travel_date'] = $i['travel_date'];
					$products[$i['product_id']]['item'][] = $item;
				}
			}
		}	
		
		$this->json['products'] = $products;
		
		$this->json['errno'] = 0;
		$this->_helper->json($this->json);
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
    		        ->where('i.stock >= ?',$i['num'])
    		        ->where('i.status <> ?',-1)
    		        ->order('i.price asc')
    		        ->limit(1)
    		        ->query()
    		        ->fetch();

		        if($itemInfo == "")
		        {
		            $this->json['errno'] = '1';
		            $this->json['errmsg'] = '商品库存不足';
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
		
		
		//判断库存是否住够
		if($sum > $product['stock'])
		{
		    $this->json['errno'] = '1';
		    $this->json['errmsg'] = '商品库存不足';
		    $this->_helper->json($this->json);
		}
		
		/*查询机票信息*/

		$ticket = $this->_db->select()
    		->from(array('o' => 'product_ticket'),array('type','time','flight','berths','go_area','go_time','go_airport','return_area','return_time','return_airport','company'))
    		->joinLeft(array('p' => 'product'), 'o.product_id = p.parent_id',array('id as product_id'))
    		->where('o.product_id = ?',$item['id'])
    		->where('o.status = ?',1)
    		->order('o.type asc')
    		->query()
    		->fetchAll();

		for ($i=0;$i<count($ticket);$i++)
		{
		    $ticket[$i]['time'] = date("Y-m-d",$ticket[$i]['go_time']);
		    $ticket[$i]['go_time'] = date("H:i",$ticket[$i]['go_time']);
		    $ticket[$i]['return_time'] = date("H:i",$ticket[$i]['return_time']);
		    unset($ticket[$i]['product_id']);
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
		
	    $this->json['errno'] = '0';
	    $this->json['product_id'] = $product_id;
	    $this->json['travel_date'] = date("Y-m-d",$product['travel_date']);
	    $this->json['sn'] = $product['sn'];
	    $this->json['product_name'] = $product['product_name'];
	    $this->json['image'] = $product['image'];
	    $this->json['buyer'] = $renshu;
		$this->json['travel_restrictions'] = $travel_restrictions;
		$this->json['information'] =  $product['information'];
		$this->json['ticket'] = $ticket;
		$this->json['insurance'] = $addon;
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
    		        ->where('i.stock >= ?',$i['num'])
    		        ->where('i.status <> ?',-1)
    		        ->order('i.price asc')
    		        ->limit(1)
    		        ->query()
    		        ->fetch();
		        
		        if($itemInfo == "")
		        {
		            $this->json['errno'] = '1';
		            $this->json['errmsg'] = '商品库存不足';
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
		
		//判断库存是否住够
		if($sum > $product['stock'])
		{
		    $this->json['errno'] = '1';
		    $this->json['errmsg'] = '商品库存不足';
		    $this->_helper->json($this->json);
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

		// 记录订单所含产品并减少库存
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

			$this->rows['product_item'] = $this->models['product_item']->find($item['id'])->current();
			$this->rows['product_item']->stock -= $item['num'];
			$this->rows['product_item']->save();
			
			$this->rows['product'] = $this->models['product']->find($item['product_id'])->current();
			$this->rows['product']->stock -= $item['num'];
			$this->rows['product']->save();
		}
		
		$this->json['errno'] = '0';
		$this->json['order_id'] = $this->rows['order']->id;
		$this->json['pay_amount'] = $this->rows['order']->pay_amount;
		$this->json['status'] = $this->rows['order']->status;
		$this->json['product_name'] = $product['subject'];
		$this->_helper->json($this->json);
	}
	
	/**
	 * 获取商品签证信息
	 */
	public function visaAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }

        $visa_ids = array();
        $data = array();
	    //查询该商品的签证信息    
	    $visa_ids = $this->_db->select()
	       ->from(array('o' => 'product_visadata'),array('visa_id'))
	       ->where('o.status = ?',1)
	       ->where('o.product_id = ?',$this->input->id)
	       ->query()
	       ->fetchAll();
	    
	    if($visa_ids != "")
	    {
	        for ($i=0;$i<count($visa_ids);$i++)
	        {
	           //查询签证信息
	           $visa = $this->_db->select()   
	               ->from(array('q' => 'visa'),array('visa_name','content','id'))
	               ->where('q.id = ?',$visa_ids[$i]['visa_id'])
	               ->query()
	               ->fetch();

	           $data[$i]['visa_name'] = $visa['visa_name'];
	           $data[$i]['content'] = $visa['content'];
	           $data[$i]['children_visa'] = array();
	           //查询签证子类商品
	           $childer_visa = $this->_db->select()
    	           ->from(array('q' => 'visa'),array('visa_name','id','total'))
    	           ->where('q.parent_id = ?',$visa_ids[$i]['visa_id'])
    	           ->query()
    	           ->fetchAll();

	           //查询二级签证
	           for($j=0;$j<count($childer_visa);$j++)
	           {
	               $data[$i]['children_visa'][$j]['visa_name'] = $childer_visa[$j]['visa_name'];
	               $data[$i]['children_visa'][$j]['total'] = $childer_visa[$j]['total'];
 
	               $childer_childer_visa = $this->_db->select()
    	               ->from(array('q' => 'visa'),array('info_type','info_name','info_content','info_file','info_total'))
    	               ->where('q.parent_id = ?',$childer_visa[$j]['id'])
    	               ->query()
    	               ->fetchAll();
	               
	               $data[$i]['children_visa'][$j]['children_visa'] = $childer_childer_visa;
	           }
	        }
	    }
	
	    $this->json['errno'] = '0';
	    $this->json['visa'] = $data;
	    $this->_helper->json($this->json);
	}
	
	/**
	 *  获取支付宝支付参数
	 */
	public function goalipayAction() 
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		$order = $this->_db->select()
			->from(array('o' => 'order'),array('id','subject','body','pay_amount'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		$order['partner_id'] = $this->_config->pay->alipay->partnerId;
		$order['seller_id'] = $this->_config->pay->alipay->sellerId;
		$order['notify_url'] = DOMAIN . 'pay/aliapp/notify';
		$order['private_key'] = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKj4xvBW8D1JWNd3
dkFY7hMD5M9PPiNmMf4glojtg4Z0MzmhJyZh+qqGkwkgBKFIkt44GXp8rROuf9w8
vIHGp+Jrwo3GsM7V0TBsFd+y1IFJ5bQEITzh++kKPMD5Uoplwl4fDZ+LdMd5zMhn
EFQE0M0CQ7FbcCMAovu+nSWwgFFlAgMBAAECgYBraxQVcD9zxrAq8jAEZQOcpQoN
3MOU/3/inVb1n4DQtghmp5Na7EjNpZXN5EPtQFTV3nYcDyx8aZ+xJA92tRK+bqUt
xwUGRoJ8PMSsON0mkD4vhUaF3sXQKteYH1X48nE3d3sp3WmCBdPClIuqPtKbCBEP
Kk4iY652KFYxfTh6dQJBANoErPdm7tIvjAPOGc63a5aHVxRsY42CMi6bAhnMToJc
JRzJzywvkRQqL2yvilDBesYcB7ru6gg8JNipSNxIkasCQQDGaLGcvqsuHcHhkvt0
tGQ2kxQYnEHx8x5ViOptE1dxqbC3+TkmxnEZkr39qfVYVDPSTvrnm4kMDxvSo10y
u7kvAkEApJXxCdhQJKa2Wc0GmJLJitrjN0zYHuUSAzML8/PTtP/hGImVd/L04Dc0
lCU1Et/tUo+omktwrATTgCjUE1BJfwJAcEgPO4Urmo18iwuDAiiUDc5uMP880o+j
OwSgpdmsQsalG3gOv8LNfotsuYDVN4dvTDUmCKwcgpaKSEnrFCroQQJAM+s/fHgM
OSvnAu8Iqm7sASRFtYi7LCV4/CwPTuCOUF36OMLoYCnN1QAMaosP4hQDfEjiYFhA
f0hYiWfw7omLrA==';
		$order['autoclose'] = '';
		
		$this->json['errno'] = '0';
		$this->json['order'] = $order;
		$this->_helper->json($this->json);
	}
	
	/**
	 *  选择微信支付
	 */
	public function gowxpayAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		$order = $this->_db->select()
			->from(array('o' => 'order'),array('id','subject','body','pay_amount'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		$body = $order['id'];    // 微信SDK问题,中文会出现无法获取的问题
		$payAmount = $order['pay_amount']*100;
		$payAmount = (string)$payAmount;
		require_once "lib/api/wxpay/lib/WxPay.Api.php";
		$input = new WxPayUnifiedOrder();
		$input->SetBody($body);
		$input->SetOut_trade_no($this->input->id);
		$input->SetTotal_fee($payAmount);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetNotify_url(DOMAIN . 'pay/wx/notify');
		$input->SetTrade_type("APP");
		$order = WxPayApi::unifiedOrder($input);
		
		$this->json['appid'] = $order['appid'];
		$this->json['partnerid'] = $order['mch_id'];
		$this->json['prepayid'] = $order['prepay_id'];
		$this->json['package'] = 'Sign=WXPay';
		$this->json['noncestr'] = $order['nonce_str'];
		$this->json['timestamp'] = SCRIPT_TIME;
		//$this->json['sign'] = $order['sign'];
		//再次生成签名
		ksort($this->json);
		//$string = $aa->ToUrlParams($this->json);
		$buff = "";
		foreach ($this->json as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
		$buff = trim($buff, "&");
		$string = $buff;
		//签名步骤二：在string后加入KEY
		$string = $string . "&key=".WxPayConfig::KEY;
		//签名步骤三：MD5加密
		$string = md5($string);
		//签名步骤四：所有字符转为大写
		$result = strtoupper($string);
		$this->json['sign'] = $result;
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}

	/**
	 *  订单列表
	 */
	public function listAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}

		/* 获取订单列表 */
		
		$this->json['order_list'] = array();
		
		$select = $this->_db->select()
			->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('o.buyer_id = ?',$this->_user->id)
			->where('o.display = ?',1);

		if ($this->input->status != '') 
		{
			$select->where('o.status = ?',$this->input->status);
		}
		else 
		{
			$select->where('o.status > ?',-1);
		}
		
		// 总数
		$count = $select->query()
			->fetchColumn();
		
		// 数据
		if($this->input->perpage == "")
		{
		    $this->input->perpage = 4;
		}
		
		// 数据
		$rs = $select->reset(Zend_Db_Select::COLUMNS)
			->columns('*','o')
			->order('o.dateline DESC')
			->limitPage($this->input->page,$this->input->perpage)
			->query()
			->fetchAll();

		$orderList = array();
		foreach ($rs as $r) 
		{
			$order = array();
			
			$order['id'] = $r['id'];
			$order['amount'] = $r['amount'];
			$order['status'] = $r['status'];
			$order['from'] = $r['from'];
			/* 产品 */
			$items = array();
			$items = $this->_db->select()
				->from(array('i' => 'order_item'),array('product_id','item_id','num','item_name','image','price','spec_desc','is_adult'))
				->where('i.order_id = ?',$r['id'])
				->query()
				->fetchAll();
			
			$product_items ="";
            for($i=0;$i<count($items);$i++)
			{
    			if($items[$i]['is_adult'] == 0 )
    			{
    			    $product_items  .= $items[$i]['num']."儿童";
    			}
    			else if ($items[$i]['is_adult'] == 1)
    			{
    			    $product_items  .= $items[$i]['num']."成人";
    			}
    			 
    			if($i != (count($items)-1))
    			{
    			    $product_items  .= ",";
    			}
    		      
			}

			//查询商品名和时间
			$product_info = "";
			$product_info = $this->_db->select()
			     ->from(array('o' => 'product'))
			     ->where('o.id = ?',$items[0]['product_id'])
			     ->query()
			     ->fetch();		
			

			$order['travel_date'] = $product_info['travel_date'];
			
			$order['product_item'] = $product_items;
			$order['product_name'] = htmlDecodeCn($product_info['product_name']);
			$order['product_id'] = $product_info['parent_id'];
			unset($product_items);

			$orderList[] = $order;
		}

		$this->json['order_list'] = $orderList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  订单详情
	 */
	public function detailAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}

		/* 订单信息 */
		
		$this->json['order'] = array();
		$order = "";
		$order = $this->_db->select()
		      ->from(array('o' => 'order'),array('id','dateline','buyer_name','mobile','status','email','amount','pay_amount','type','from'))
		      ->where('o.id = ?',$this->input->id)
		      ->query()
		      ->fetch();

		/* 购买人 */
		
		$this->json['buyer'] = array();
		$this->json['buyer']['buyer_name'] = $order['buyer_name'];
		$this->json['buyer']['mobile'] = $order['mobile'];
		$this->json['buyer']['email'] = $order['email'];
		
		// 移除不必要的值
		unset($order['buyer_name']);
		unset($order['mobile']);
		unset($order['telephone']);
		unset($order['email']);
		//$order['clock'] = $order['clock'] - SCRIPT_TIME;
		$order['amount'] = intval($order['amount']);

	   /* 产品 */
		$items = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id','item_id','num','item_name','image','price','spec_desc','is_adult'))
			->where('i.order_id = ?',$this->input->id)
			->query()
			->fetchAll();
		
		if($items)
		{
		    $product_items ="";
		    for($i=0;$i<count($items);$i++)
		    {
		        if($items[$i]['is_adult'] == 0 )
		        {
		            $product_items  .= $items[$i]['num']."儿童";
		        }
		        else if ($items[$i]['is_adult'] == 1)
		        {
		            $product_items  .= $items[$i]['num']."成人";
		        }
		       
		        if($i != (count($items)-1))
		        {
		           $product_items  .= ",";
		        }
	        }
	        
	        //查询商品名和时间
	        $product_info ="";
	        $product_info = $this->_db->select()
                ->from(array('o' => 'product'))
                ->where('o.id = ?',$items[0]['product_id'])
                ->query()
                ->fetch();
	        $product_parent_info = "";
	        $product_parent_info = $this->_db->select()
                ->from(array('o' => 'product'))
                ->where('o.id = ?',$product_info['parent_id'])
                ->query()
                ->fetch();

	        //查询出发城市
	        $region = "";
	        $region = $this->_db->select()
    	        ->from(array('o' => 'region'))
    	        ->where('o.id = ?',$product_info['origin_id'])
    	        ->query()
    	        ->fetch();

	        $order['region_name'] = $region['region_name'];
	        $order['product_item'] = $product_items;
	        $order['travel_date'] = $product_info['travel_date'];
	        $order['product_name'] = htmlDecodeCn($product_parent_info['product_name']);
	        $order['product_id'] = $product_info['parent_id'];
		}
		else
		{
		    $order['travel_date'] = "";
		    $order['product_name'] = "";
		}

		/*旅客信息*/
		
		$order_tourist = $this->_db->select()
		  ->from(array('o' => 'order_tourist'),array('tourist_name','cert_num','cert_type'))
		  ->where('o.order_id = ?',$this->input->id)
		  ->query()
		  ->fetchAll();
		
		$order['contract'] = DOMAIN."orderuc/order/contract?id=".$this->input->id;
		
		$this->json['order'] = $order;
		$this->json['tourist'] = $order_tourist;
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  确认收货
	 */
	public function finishAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::TRADE_FINISHED;
		$this->rows['order']->save();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  关闭订单
	 */
	public function cancleAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->status = Model_Order::CANCLE;
		$this->rows['order']->save();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  删除订单
	 */
	public function deleteAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
		$this->rows['order']->display = 0;
		$this->rows['order']->save();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>