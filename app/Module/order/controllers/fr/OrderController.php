<?php

class Order_OrderController extends Core2_Controller_Action_Fr  
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
		$this->models['order_discount'] = new Model_OrderDiscount();
		$this->models['order_shipping'] = new Model_OrderShipping();
		$this->models['product_feedback'] = new Model_ProductFeedback();
		$this->models['funds'] = new Model_Funds();
		$this->models['coupon_user'] = new Model_CouponUser();
		$this->models['tourist'] = new Model_Tourist();
		$this->models['order_tourist'] = new Model_OrderTourist();
		$this->models['order_contract'] = new Model_OrderContract();
		$this->models['product'] = new Model_Product();
		$this->models['order_addon'] = new Model_OrderAddon();
		$this->models['contract'] = new Model_Contract();
	}

	public function indexAction()
	{
		$this->_forward('list');
	}
	
	/**
	 *  确认订单
	 */
	public function confirmAction() 
	{
		if (!params($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
			    array(
			        'href' => '/product/product/list',
			        'text' => $this->error->firstMessage()),
			));
		}

		/* 传入一个数组 包含item id 跟 数量*/
		$sum = 0;
		foreach ($this->params['items'] as $i)
		{
		    if($i['num'] > 0)
		    {
		        $itemInfo = $this->_db->select()
    		        ->from(array('p' => 'product'),array('product_name','travel_date','contract_id','image','travel_restrictions','parent_id','information','sn','cost_need','origin_id'))
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
		          	$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
    				array(
    					'href' => '/product/product/list',
    					'text' => '商品发生错误！'),
    			         ));
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
		         
		        //查询出发城市
		        $city = $this->_db->select()
    		        ->from(array('o' => 'region'))
    		        ->where('o.id = ?',$itemInfo['origin_id'])
    		        ->query()
    		        ->fetch();

		        $product['region_name'] = $city['region_name'];
		        $product['image'] = $productImage['image'];
		        $product['subject'] = $itemInfo['product_name'];
		        $product['sn'] = $itemInfo['sn'];
		        $product['travel_date'] = $itemInfo['travel_date'];
		        //	$product['subject'] = $product['subject'].$itemInfo['item_name'].$i['num'];
		        if($i['is_adult'] == 0)
		        {
		            $product['item_amount'] += $i['num'] * $itemInfo['child_price'];
		            $product['child_num'] = "儿童x".$i['num'];
		            $product['child_price'] = intval($itemInfo['child_price']);
		            $product['child_total'] = $i['num'] * $itemInfo['child_price'];
		        }
		        else if($i['is_adult'] == 1)
		        {
		            $product['item_amount'] += $i['num'] * $itemInfo['price'];
		            $product['adult_num'] = "成人x".$i['num'];
		            $product['adult_price'] = intval($itemInfo['price']);
		            $product['adult_total'] = $i['num'] * $itemInfo['price'];
		        }
		        $product['product_name'] = $itemInfo['product_name'];
		        $product['num'] += $i['num'];
		        $product_id = $itemInfo['parent_id'];
		        $product['information'] = $itemInfo['information'];
		        $product['stock'] = $itemInfo['stock'];
		        
		        $product['cost_need'] = $itemInfo['cost_need'];
		        $order=array("\r\n","\n");
		        $replace='<br/>';
		        $product['travel_restrictions']=str_replace($order,$replace,$itemInfo['travel_restrictions']);   // 产品特色
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
		
		$this->view->addon = $addon;
		
		$this->view->product = $product;

		$this->view->pay_amount =  $product['item_amount'];
		//用户出行人信息
		$touristList = $this->_db->select()
		  ->from(array('t' => 'tourist'))
		  ->where('t.member_id = ?',$this->_user->id)
		  ->where('t.status = ?',1)
		  ->query()
		  ->fetchAll();
		$this->view->tourist_list = $touristList;
		
	}

	/**
	 *  收银台
	 */
	public function payAction()
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
		
        	$order = array();
        		$result = $this->_db->select()
        			->from(array('o' => 'order'))
        			->where('o.id = ?',$this->paramInput->id)
        			->query()
        			->fetch();
        		if (!empty($result)) 
        		{
        			$result['clock'] = $result['clock'] - SCRIPT_TIME;
        			$order = $result;
        		}
		

		$addon = $this->_db->select()
    		->from(array('o' => 'order_addon'))
    		->joinLeft(array('p' => 'product_addon'), 'p.id = o.addon_id',array('title'))
    		->where('o.order_id = ?',$this->paramInput->id)
    		->query()
    		->fetchAll();

		$order['addon'] =$addon;
		/* 产品 */
		$items = array();
		$items = $this->_db->select()
    		->from(array('i' => 'order_item'),array('product_id','item_id','num','item_name','image','price','spec_desc','is_adult'))
    		->where('i.order_id = ?',$this->paramInput->id)
    		->query()
    		->fetchAll();
			
		for($i=0;$i<count($items);$i++)
		{
			if($items[$i]['is_adult'] == 0 )
			{
			    $product_items  = $items[$i]['num']."儿童";
			}
			else if ($items[$i]['is_adult'] == 1)
			{
			    $product_items  = $items[$i]['num']."成人";
			}
			 

			$items[$i]['product_items'] = $product_items;
			
			//查询商品名和时间
			$product_info = "";
			$product_info = $this->_db->select()
    			->from(array('o' => 'product'))
    			->where('o.id = ?',$items[$i]['product_id'])
    			->query()
    			->fetch();
			$order['travel_date'] = $product_info['travel_date'];
			$items[$i]['product_name'] = htmlDecodeCn($product_info['product_name']);
			$items[$i]['product_id'] = $product_info['parent_id'];
			
			//查询数据
			$product_image = $this->_db->select()
    			->from(array('o' => 'product'))
    			->where('o.id = ?',$items[$i]['product_id'])
    			->query()
    			->fetch();
			$items[$i]['product_image'] = $product_image['image'];
		}
		
		$order['items'] = $items;

		/* 旅客信息 */
		
		$orderTourist = $this->_db->select()
    		  ->from(array('o' => 'order_tourist'),array('tourist_name','cert_num','cert_type','mobile','cert_deadline'))
    		  ->where('o.order_id = ?',$this->paramInput->id)
    		  ->query()
    		  ->fetchAll();

		$order['tourist'] = $orderTourist;
		
		if($order['from'] == 1 && $order['status'] == 0)
		{
		    $order['memo'] = "邮轮订单后台确认中。。。";
		}
		else if($order['from'] == 0 && $order['status'] == 0)
		{
		    $order['memo'] = "等待付款";
		}
		else if($order['from'] == 0 && $order['status'] == 1)
		{
		    $order['memo'] = "订单待确认";
		}
		else if($order['from'] == 0 && $order['status'] == 2)
		{
		    $order['memo'] = "待出行";
		}
		else if($order['from'] == 0 && $order['status'] == 3)
		{
		    $order['memo'] = "已完成";
		}
		else if($order['from'] == 0 && $order['status'] == 13)
		{
		    $order['memo'] = "已退款";
		}
		
		$this->view->order = $order;
		
		
	}

	/**
	 *  支付宝支付
	 */
	public function goalipayAction() 
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
	/* 订单信息 */
		
		$order = "";
		$order = $this->_db->select()
    		->from(array('o' => 'order'),array('id','dateline','buyer_name','mobile','status','email','amount','pay_amount','type','subject','body'))
    		->where('o.id = ?',$this->paramInput->id)
    		->query()
    		->fetch();
		
    		//支付宝
    		include "lib/api/aliwappay/lib/alipay_submit.class.php";
    		//支付信息
    		$config = Zend_Registry::get('config');
    		$alipay_config['partner'] = $config->pay->alipay->partnerId;
    		$alipay_config['seller_id']	= $config->pay->alipay->partnerId;
    		$alipay_config['sign_type'] = strtoupper('RSA');
    		$alipay_config['input_charset'] = strtolower('utf-8');
    		$alipay_config['private_key'] = 'MIICXAIBAAKBgQCo+MbwVvA9SVjXd3ZBWO4TA+TPTz4jZjH+IJaI7YOGdDM5oScm
YfqqhpMJIAShSJLeOBl6fK0Trn/cPLyBxqfia8KNxrDO1dEwbBXfstSBSeW0BCE8
4fvpCjzA+VKKZcJeHw2fi3THeczIZxBUBNDNAkOxW3AjAKL7vp0lsIBRZQIDAQAB
AoGAa2sUFXA/c8awKvIwBGUDnKUKDdzDlP9/4p1W9Z+A0LYIZqeTWuxIzaWVzeRD
7UBU1d52HA8sfGmfsSQPdrUSvm6lLccFBkaCfDzErDjdJpA+L4VGhd7F0CrXmB9V
+PJxN3d7Kd1pggXTwpSLqj7SmwgRDypOImOudihWMX04enUCQQDaBKz3Zu7SL4wD
zhnOt2uWh1cUbGONgjIumwIZzE6CXCUcyc8sL5EUKi9sr4pQwXrGHAe67uoIPCTY
qUjcSJGrAkEAxmixnL6rLh3B4ZL7dLRkNpMUGJxB8fMeVYjqbRNXcamwt/k5JsZx
GZK9/an1WFQz0k7655uJDA8b0qNdMru5LwJBAKSV8QnYUCSmtlnNBpiSyYra4zdM
2B7lEgMzC/Pz07T/4RiJlXfy9OA3NJQlNRLf7VKPqJpLcKwE04Ao1BNQSX8CQHBI
DzuFK5qNfIsLgwIolA3ObjD/PNKPozsEoKXZrELGpRt4Dr/CzX6LbLmA1TeHb0w1
JgisHIKWikhJ6xQq6EECQDPrP3x4DDkr5wLvCKpu7AEkRbWIuywlePwsD07gjlBd
+jjC6GApzdUADGqLD+IUA3xI4mBYQH9IWIln8O6Ji6w=';
    		$alipay_config['alipay_public_key'] = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRA
FljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQE
B/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5Ksi
NG9zpgmLCUYuLkxpLQIDAQAB';
    		$alipay_config['transport'] = 'http';
    		$alipay_config['service'] = "create_direct_pay_by_user";
    		$seller_email = $config->pay->alipay->sellerId;
    		$alipaySubmit = new AlipaySubmit($alipay_config);
    		$payment_type = "1";
    		$exter_invoke_ip = $_SERVER['REMOTE_ADDR'];
    		$out_trade_no = $order['id'];
    		$subject = $order['body'];
    		$total_fee = $order['pay_amount'];
    	//	$total_fee = 0.01;
    		$parameter = array(
    				"service" => "create_direct_pay_by_user",
    				"partner" => trim($alipay_config['partner']),
    				"seller_id" => trim($alipay_config['seller_id']),
    				"sign_type" => 'RSA',
    				"payment_type"	=> $payment_type,
    				"out_trade_no"	=> $out_trade_no,
    				"subject"	=> $subject,
    				"total_fee"	=> $total_fee,
    				"notify_url"	=> DOMAIN.'pay/aliapp/notify2',
    				"return_url"	=> DOMAIN.'/index/index',
    				"body"	=> $subject,
    				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
    		);

    		$html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
    		echo $html_text;exit;
	}

	/**
	 *  微信支付页面一
	 */
	public function beforpayAction()
	{
		if(!isWeixin()){
			header("Location: ".DOMAIN."order/order/pay?id=".$_GET['id']);
			exit;
		}
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		$order = $this->_db->select()
			->from(array('o' => 'order'),array('id','subject','body','pay_amount'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();

		if(!$order){
			$this->_helper->notice('订单不存在','','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		include "lib/api/wxwebpay/lib/WxPay.Api.php";
		include "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
		//获取用户openid
		$tools = new JsApiPay();
		$openId = $tools->GetOpenid();
		Core_Cookie::set('wx_real_openid',authcode($openId,'ENCODE'),60*60*3);
		$this->view->id = $this->input->id;
	}
	
	/**
	 *  选择微信支付
	 */
	/*public function gowxpayAction()
	{
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		$openId = Core_Cookie::get('wx_real_openid');
		$openId = authcode($openId);
		$order = $this->_db->select()
			->from(array('o' => 'order'),array('id','subject','body','pay_amount'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		$body = $order['body'];
		$payAmount = $order['pay_amount']*100;
		$payAmount = (string)$payAmount;
		require_once "lib/api/wxwebpay/lib/WxPay.Api.php";	
		require_once "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
		$tools = new JsApiPay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody($body);
		$input->SetOut_trade_no($this->input->id);
		$input->SetTotal_fee($payAmount);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetNotify_url(DOMAIN . 'pay/wxweb/notify');
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$payOrder = WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($payOrder);
		
		$this->view->jsApiParameters = $jsApiParameters;
		$this->view->order_id = $this->input->id;
		$this->view->order = $order;
	}*/

	/**
	 *  支付完成页面
	 */	
	public function paycompletAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		/* 订单信息 */
		
		$order = array();
		$result = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		if (!empty($result)) 
		{
			$order = $result;
		}
		$this->view->order = $order;
		
		/* 产品 */
		
		$items = array();
		$results = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
			->where('i.order_id = ?',$this->input->id)
			->query()
			->fetchAll();
		if (!empty($results)) 
		{
			foreach ($results as $r) 
			{
				$items[] = $r;
			}
		}
		$this->view->items = $items;
	 }

	/**
	 *  订单列表
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
		$this->view->from = 1;
		$query .= "&from=1";
		//未付款
		$payments = $this->_db->select()
    		->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')))
    		->where('o.buyer_id = ?',$this->_user->id)
    		->where('o.status = ?',0)
    		->where('o.display = ?',1)
    		->query()
    		->fetchColumn();	
		$this->view->payments = $payments;

		//待确认
		$onfirm =  $this->_db->select()
    		->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')))
    		->where('o.buyer_id = ?',$this->_user->id)
    		->where('o.display = ?',1)
    		->where('o.status = ?',1)
    		->query()
    		->fetchColumn();
		$this->view->onfirm = $onfirm;
		
		/* 获取订单列表 */
		
		$select = $this->_db->select()
			->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('o.buyer_id = ?',$this->_user->id)
			->where('o.display = ?',1);
		
		if ($this->paramInput->status != "" ) 
		{
		    if($this->paramInput->status == -1)
		    {
		        $select->where('o.status <> ?',-1);
		    }
		    else
		    {
		        $select->where('o.status = ?',$this->paramInput->status);
		    }

			$this->view->status = $this->paramInput->status;
			$query .= "&status={$this->paramInput->status}";
		}
		else
		{
		    $this->view->status = -1;
		}
		
		$select->where('o.status <> ?',-1);

		
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
			->columns('*','o')
			->order('o.dateline DESC')
			->limitPage($page,$perpage)
			->query()
			->fetchAll();

		$orderList = array();
		
		foreach ($rs as $r) 
		{
			$order = array();
			$order['id'] = $r['id'];
			$order['amount'] = $r['amount'];
			$order['status'] = $r['status'];
			$order['dateline'] = $r['dateline'];
			$order['pay_amount'] = $r['pay_amount'];
			$order['from'] = $r['from'];
			/* 产品 */
			$items = array();
			$items = $this->_db->select()
				->from(array('i' => 'order_item'),array('product_id','item_id','num','item_name','image','price','spec_desc','is_adult'))
				->where('i.order_id = ?',$r['id'])
				->query()
				->fetchAll();
			
            for($i=0;$i<count($items);$i++)
			{
    			if($items[$i]['is_adult'] == 0 )
    			{
    			    $product_chil = "儿童*".$items[$i]['num'];
    			}
    			else if ($items[$i]['is_adult'] == 1)
    			{
    			    $product_yon = "成人*".$items[$i]['num'];
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
			$order['product_chil'] = $product_chil;
			$order['product_yon'] = $product_yon;
			$order['product_name'] = htmlDecodeCn($product_info['product_name']);
			$order['product_id'] = $product_info['parent_id'];
			
			//查询数据
			$product_image = $this->_db->select()
			     ->from(array('o' => 'product'))
			     ->where('o.id = ?',$order['product_id'])
			     ->query()
			     ->fetch();		
			$order['product_image'] = $product_image['image'];
			
			switch ($product_image['tourism_type'])
			{
			    case '1':
			        $order['tourism_type'] = "跟团游";
			        break;
		        case '2':
		            $order['tourism_type'] = "自助游";
		            break;
                case '3':
                    $order['tourism_type'] = "自由游";
                    break;
                case '4':
                    $order['tourism_type'] = "自驾游";
                    break;
                case '5':
                    $order['tourism_type'] = "目的地服务";
                    break;
			}

			unset($product_items);

			$orderList[] = $order;
			
		}

		$this->view->count = $count; //总条数
		$this->view->pages = $pages; //总页数
		$this->view->page = $page;   //当前页数
		$this->view->prev_page = $prev_page;   //上一页
		$this->view->next_page = $next_page;   //下一页
		$this->view->query = $query;
		$this->view->orderList = $orderList;
	}
	
	/**
	 *  订单详情
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
		
		$this->view->from = 1;
		/* 订单信息 */
		
		$order = array();
		$result = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		if (!empty($result)) 
		{
			$result['clock'] = $result['clock'] - SCRIPT_TIME;
			$order = $result;
		}
		
		/* 产品 */
		$items = array();
		$items = $this->_db->select()
    		->from(array('i' => 'order_item'),array('product_id','item_id','num','item_name','image','price','spec_desc','is_adult'))
    		->where('i.order_id = ?',$this->paramInput->id)
    		->query()
    		->fetchAll();
			
		for($i=0;$i<count($items);$i++)
		{
			if($items[$i]['is_adult'] == 0 )
			{
			    $product_items  = $items[$i]['num']."儿童";
			}
			else if ($items[$i]['is_adult'] == 1)
			{
			    $product_items  = $items[$i]['num']."成人";
			}
			 

			$items[$i]['product_items'] = $product_items;
			
			//查询商品名和时间
			$product_info = "";
			$product_info = $this->_db->select()
    			->from(array('o' => 'product'))
    			->where('o.id = ?',$items[$i]['product_id'])
    			->query()
    			->fetch();
			$order['travel_date'] = $product_info['travel_date'];
			$items[$i]['product_name'] = htmlDecodeCn($product_info['product_name']);
			$items[$i]['product_id'] = $product_info['parent_id'];
			
			//查询数据
			$product_image = $this->_db->select()
    			->from(array('o' => 'product'))
    			->where('o.id = ?',$items[$i]['product_id'])
    			->query()
    			->fetch();

			$items[$i]['product_image'] = $product_image['image'];
			 
			switch ($product_image['tourism_type'])
			{
			    case '1':
			        $items[$i]['tourism_type'] = "跟团游";
			        break;
			    case '2':
			        $items[$i]['tourism_type'] = "自助游";
			        break;
			    case '3':
			        $items[$i]['tourism_type'] = "自由游";
			        break;
			    case '4':
			        $items[$i]['tourism_type'] = "自驾游";
			        break;
			    case '5':
			        $items[$i]['tourism_type'] = "目的地服务";
			        break;
			}
		}
		
		$order['items'] = $items;

		/* 旅客信息 */
		
		$orderTourist = $this->_db->select()
    		  ->from(array('o' => 'order_tourist'),array('tourist_name','cert_num','cert_type','mobile','cert_deadline','sex'))
    		  ->where('o.order_id = ?',$this->paramInput->id)
    		  ->query()
    		  ->fetchAll();

		$order['tourist'] = $orderTourist;
		
		if($order['from'] == 1 && $order['status'] == 0)
		{
		    $order['memo'] = "邮轮订单后台确认中。。。";
		}
		else if($order['from'] == 0 && $order['status'] == 0)
		{
		    $order['memo'] = "等待付款";
		}
		else if($order['from'] == 0 && $order['status'] == 1)
		{
		    $order['memo'] = "订单待确认";
		}
		else if($order['from'] == 0 && $order['status'] == 2)
		{
		    $order['memo'] = "待出行";
		}
		else if($order['from'] == 0 && $order['status'] == 3)
		{
		    $order['memo'] = "已完成";
		}
		else if($order['from'] == 0 && $order['status'] == 13)
		{
		    $order['memo'] = "已退款";
		}
		
		$this->view->order = $order;

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
		
		/* 分红提成确认 */
		
		$funds = $this->_db->select()
			->from(array('f' => 'funds'))
			->where('f.order_id = ?',$this->rows['order']->id)
			->where('f.type IN (?)',array(1,2,3))
			->where('f.status = ?',0)
			->query()
			->fetchAll();
		if (!empty($funds)) 
		{
			foreach ($funds as $fund) 
			{
				$this->_db->update('funds',array('status' => 1),array('id = ?' => $fund['id']));
				$this->_db->update('member',array('balance' => new Zend_Db_Expr("balance + {$fund['money']}")),array('id = ?' => $fund['member_id']));
			}
		}
		
		/* 累计个人消费额度 */
		$this->_db->update('member',array('consumption' => new Zend_Db_Expr("consumption + {$this->rows['order']->pay_amount}")),array('id = ?' => $this->_user->id));
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  查看物流
	 */
	public function shippingAction()
	{
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		
		/* 获取订单最近的发货单 */
		
		$orderShipping = $this->_db->select()
			->from(array('s' => 'order_shipping'))
			->where('s.order_id = ?',$this->input->id)
			->where('s.type = ?',0)
			->order('dateline DESC')
			->query()
			->fetch();
		
		/* 订单信息 */

		$rs = $this->_db->select()
				->from(array('i' => 'order_item'),array('item_id','item_name','image','price','num'))
				->where('i.order_id = ?',$this->input->id)
				->query()
				->fetchAll();
		$items = array();
		foreach ($rs as $r) 
		{
			$r['image'] = thumbpath($r['image'],220);
			$items[] = $r;
		}
		$this->json['items'] = $items;	
		
		/* 物流信息 */
		
		
		// 调用物流接口
		$kuaidi = new Core_Kuaidi();
		
		$data = $kuaidi->getData($orderShipping['shipping_no'],$orderShipping['company_no']);
		
		$this->json['shipping'] = array();
		$this->json['shipping']['company'] = $orderShipping['shipping_company'];
		$this->json['shipping']['shipping_no'] = $orderShipping['shipping_no'];
		$this->json['shipping']['data'] = $data;

		$this->view->shipping = $this->json['shipping'];
	}
	

	/**
	 *  评价
	 */
	public function feedbackAction()
	{
		if ($this->_request->isGet()) 
		{
			if (!params($this)) 
			{
				$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
					array(
						'href' => '/product/product/list',
						'text' => '商品列表'),
					array(
						'href' => 'javascript:history.go(-1);',
						'text' => '返回上一面'),
				));
			}
			$r = $this->_db->select()
				->from(array('oi' => 'order_item'))
				->joinLeft(array('o' => 'order'),'o.id = oi.order_id',array('buyer_id'))
				->joinLeft(array('f' => 'product_feedback'),'f.product_id = oi.product_id and f.member_id=o.buyer_id',array('account'))
				->where('o.id = ? ',$this->params['id'])
				->where('o.status = ? ',3)
				->query()
				->fetchAll();
			$this->view->items = $r;
			$this->view->orderId = $this->params['id'];
		}

		if ($this->_request->isPost()) 
		{
			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			
			/* 获取用户信息 */
			
			$member = $this->_db->select()
				->from(array('m' => 'member'))
				->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('avatar'))
				->where('m.id = ?',$this->_user->id)
				->query()
				->fetch();
		
			foreach ($this->data['feedbacks'] as $f) 
			{
				$this->models['product_feedback']->createRow(array(
					'member_id' => $this->_user->id,
					'account' => $member['account'],
					'avatar' => $member['avatar'],
					'product_id' => $f['product_id'],
					'grade' => $f['grade'],
					'content' => $f['content']
				))->save();
			}
			
			$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
			$this->rows['order']->feedback = 1;
			$this->rows['order']->save();
			
			$this->json['errno'] = '0';
			$this->json['notice'] = '评价成功';
			$this->json['gourl'] = DOMAIN.'order/order/list';
			$this->_helper->json($this->json);
		}
	}
	
	/**
	 *  申请退货
	 */
	public function refundAction()
	{
		if ($this->_request->isGet()) 
		{
			if (!params($this)) 
			{
				$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
					array(
						'href' => '/product/product/list',
						'text' => '商品列表'),
					array(
						'href' => 'javascript:history.go(-1);',
						'text' => '返回上一面'),
				));
			}
			/* 订单信息 */		

			$order = $this->_db->select()
				->from(array('o' => 'order'),array('id','pay_amount'))
				->where('o.id = ?',$this->params['id'])
				->query()
				->fetch();

			$this->view->order = $order;
		}
		
		if ($this->_request->isPost()) 
		{
			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			
			$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
			$this->rows['order']->refund_reason = $this->input->refund_reason;
			$this->rows['order']->status = Model_Order::WAIT_SELLER_AGREE;
			$this->rows['order']->save();
			
			$this->json['errno'] = '0';
			$this->json['notice'] = '申请成功，等待审核';
			$this->json['gourl'] = DOMAIN.'order/order/list';
			$this->_helper->json($this->json);
		}
	}

	/**
	 *  填写退货单
	 */
	
	public function returnproductAction()
	{
		if (!form($this)) 
		{
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		
		/* 订单信息 */		
		
		$order = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		
		/* 产品 */
		
		$order['items'] = $this->_db->select()
			->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
			->where('i.order_id = ?',$this->input->id)
			->query()
			->fetchAll();
		$order['dateline'] = date('Y.m.d H:i:s',$order['dateline']);
		$this->view->order = $order;
	}
	
	/**
	 *  填写退货单
	 */
	public function returnAction()
	{
		if ($this->_request->isGet()) 
		{
			if (!params($this)) 
			{
				$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
					array(
						'href' => '/product/product/list',
						'text' => '商品列表'),
					array(
						'href' => 'javascript:history.go(-1);',
						'text' => '返回上一面'),
				));
			}
			$order = $this->_db->select()
				->from(array('o' => 'order'))
				->where('o.id = ?',$this->params['id'])
				->query()
				->fetch();
			
			/* 产品 */
			
			$order['items'] = $this->_db->select()
				->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
				->where('i.order_id = ?',$this->params['id'])
				->query()
				->fetchAll();
			$order['dateline'] = date('Y.m.d H:i:s',$order['dateline']);
			$this->view->order = $order;
		}
		
		if ($this->_request->isPost()) 
		{
			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			
			/* 退货单 */
			
			$this->rows['order_shipping'] = $this->models['order_shipping']->createRow(array(
				'order_id' => $this->input->id,
				'type' => 1,
				'shipping_company' => $this->input->shipping_company,
				'shipping_no' => $this->input->shipping_no,
				'memo' => $this->input->memo
			));
			$this->rows['order_shipping']->save();
			
			/* 改变订单状态 */
			
			$this->rows['order'] = $this->models['order']->find($this->input->id)->current();
			$this->rows['order']->status = Model_Order::WAIT_SELLER_CONFIRM_GOODS;
			$this->rows['order']->save();
			
			$this->json['errno'] = '0';
			$this->json['notice'] = '提交成功';
			$this->json['gourl'] = DOMAIN.'order/order/list';
			$this->_helper->json($this->json);
		}
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
	
	/**
	 * 异步
	 */
	public function ajaxAction()
	{

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
	        case 'tourist':
	            
	            //查询出行人  
	            $tourist = $this->_db->select()
	            ->from(array('t' => 'tourist'),array('id as tourist_id','tourist_name','cert_type','cert_num','mobile'))
	            ->where('t.member_id = ?',$this->_user->id)
	            ->where('t.id = ?',$this->input->id)
	            ->where('t.status = ?',1)
	            ->query()
	            ->fetch();

	            switch ($tourist['cert_type'])
	            {
	                case '1':
	                    $tourist['cert_type'] = "身份证";
	                    break;
                    case '2':
                        $tourist['cert_type'] = "护照";
                        break;
	            }
	            
	            $this->json['errno'] = '0';
	            $this->json['tourist'] = $tourist;
	            $this->_helper->json($this->json);
	            break;
	            
	        case 'addtourist':

	            $this->rows['tourist'] = $this->models['tourist']->createRow(array(
	                'member_id' => $this->_user->id,
	                'tourist_name' => $this->input->tourist_name,
	                'cert_type' => $this->input->cert_type,
	                'cert_num' => $this->input->cert_num,
	                'mobile' => $this->input->mobile,
	                'birthday' => strtotime($this->input->birthday),
	                'sex' => $this->input->sex,
	                'cert_deadline' => strtotime($this->input->cert_deadline),
	                'dateline' => time(),
	                'status' => 1,
	            ));
	            $this->rows['tourist']->save();
	             
	            $this->json['errno'] = '0';
	            $this->json['tourist_id'] = $this->rows['tourist']->id;
	            $this->_helper->json($this->json);
	            break;
	            
	        case 'create':
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
	                'memo' => $this->input->remarks,
	                //客服类型
	                'service_type' => $this->input->service_type,
	                //现在没有折扣优惠
	                'pay_amount' => 0.01,
	               // 'pay_amount' => $product['amount'],
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
	            $this->json['url'] = "/order/order/pay?id=".$this->rows['order']->id;
	            $this->_helper->json($this->json);
	            break;
	    }
	}
	
	
}

?>