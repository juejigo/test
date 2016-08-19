<?php

class Orderuc_OrderController extends Core2_Controller_Action_Uc
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
		$this->models['order_discount'] = new Model_OrderDiscount();
		$this->models['order_shipping'] = new Model_OrderShipping();
		$this->models['product_item'] = new Model_ProductItem();
		$this->models['product_feedback'] = new Model_ProductFeedback();
		$this->models['funds'] = new Model_Funds();
		$this->models['coupon_user'] = new Model_CouponUser();
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
		
		/* 获取购物车提交的产品 */
		
		$cartList = $this->_cart();
		if(!$cartList['products']){
			$this->_helper->notice('购物车不存在','','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
		}
		$products = array();
		foreach ($cartList['products'] as $r)
		{
			unset($r['product_id']);
			unset($r['cost_price']);
			$products[] = $r;
		}
		$cartList['products'] = $products;
		$this->json['cart_list'] = $cartList;
		
		/* 默认收货人 */
		$consignee = array();
		$select = $this->_db->select();


		$select = $select
			->from(array('c' => 'consignee'))
			->where('c.member_id = ?',$this->_user->id)
			->where('c.status = ?',1);
		if($this->input->consignee_id){
			$select = $select->where('c.id = ?',$this->input->consignee_id);
		}else{
			$select = $select->where('c.default = ?',1);
		}
		$result = $select->query()->fetch();

		if (!empty($result)) 
		{
			$consignee['id'] = $result['id'];
			$consignee['name'] = $result['consignee'];
			$consignee['address'] = getRegionPath($result['city_id'],$result['county_id']) . $result['address'];
			$consignee['mobile'] = $result['mobile'];
		}

		$this->json['consignee'] = $consignee;
		
		/* 红包 */

		$select = $this->_db->select();
		$select = $select
			->from(array('u' => 'coupon_user'),array('coupon_user_id' => 'id','deadline','get_time'))
			->joinLeft(array('c' => 'coupon'),'c.id = u.coupon_id',array('coupon_name','value','memo'))
			->where('u.member_id = ?',$this->_user->id)
			->where('u.status = ?',1)
			->where('deadline > ?',time());
		$rs = $select->query()->fetchALL();

		if($rs){
			foreach($rs as $key=>$val){
				$rs[$key]['deadline'] = date('Y-m-d',$val['deadline']);
			}
		}
		$this->json['coupon'] = !empty($rs) ? $rs : array();

		
		/* 积分 */
		
		$point = $this->_db->select()
			->from(array('m' => 'member'),array('point'))
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->json['point'] = !empty($point) ? $point : 0;
		
		/* 是否允许索要发票 */
		
		$this->json['allow_tax'] = 1;
		
		$this->json['errno'] = '0';
		$selfUrl = $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?");
		$selfUrl = substr(DOMAIN,0,-1).resetUrl($selfUrl,'consignee_id');
		$this->view->selfUrl = $selfUrl;
		$this->view->confirmData = $this->json;
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
		
		/* 获取购物车提交的产品 */
		
		$cartList = $this->_cart();
		
		/* 获取收货人信息 */
		
		/*$consignee = $this->_db->select()
			->from(array('c' => 'consignee'))
			->where('c.id = ?',$this->input->consignee_id)
			->query()
			->fetch();*/
		
		/* 计算运费 */
		
		$shipping = 0;
		
		/* 计算优惠 */
		
		$discount = 0;
		
		// 积分
		$discount += $this->input->point_pay;
		
		// 优惠券
		if (!empty($this->input->coupon_user_id)) 
		{
			$coupon = $this->_db->select()
				->from(array('u' => 'coupon_user'),array())
				->joinLeft(array('c' => 'coupon'),'c.id = u.coupon_id',array('condition','value'))
				->where('u.id = ?',$this->input->coupon_user_id)
				->query()
				->fetch();
			
			// 使用条件
			$condition = Zend_Serializer::unserialize($coupon['condition']);
			if (!empty($condition['min_amount'])) 
			{
				if ($cartList['amount'] < $condition['min_amount']) 
				{
					$this->json['errno'] = '1';
					$this->json['errmsg'] = '订单金额未达到红包使用条件';
					$this->_helper->json($this->json);
				}
			}
			
			$discount += $coupon['value'];
		}
		
		// 优惠活动
		
		/* 实际支付 */
		
		$payAmount = $cartList['amount'] + $shipping - $discount;
		
		if ($payAmount < 0) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = '余额或红包使用错误';
			$this->_helper->json($this->json);
		}
		
		/* 生成订单 */
	
		$id = $this->models['order']->createId();
		$this->rows['order'] = $this->models['order']->createRow(array(
			'id' => $id,
			'from' => $cartList['from'],
			'subject' => SITE_NAME . "订单-{$id}",
			'body' =>  SITE_NAME . "订单-{$id}",
			'buyer_id' => $this->_user->id,
			'item_amount' => $cartList['amount'],
			'shipping' => $shipping,
			'discount' => $discount,
			//'pay_amount' => $payAmount,
			'pay_amount' => 0.01,
			/*'consignee' =>$consignee['consignee'],
			'province_id' => $consignee['province_id'],
			'city_id' => $consignee['city_id'],
			'county_id' => $consignee['county_id'],
			'address' => $consignee['address'],
			'zip' => $consignee['zip'],
			'telephone' => $consignee['telephone'],*/
			'mobile' => $this->input->mobile,
			'memo' => $this->input->memo,
			'status' => $payAmount == 0 ? Model_Order::WAIT_SELLER_SEND_GOODS : Model_Order::WAIT_BUYER_PAY
		));
		$this->rows['order']->save();
		
		// 记录订单所含产品并减少库存
		foreach ($cartList['products'] as $p)
		{
			$this->models['order_item']->createRow(array(
				'order_id' => $this->rows['order']->id,
				'product_id' => $p['product_id'],
				'supplier_id' => $p['supplier_id'],
				'item_id' => $p['item_id'],
				'item_name' => $p['item_name'],
				'image' => $p['image'],
				'price' => $p['price'],
				'cost_price' => $p['cost_price'],
				'num' => $p['num'],
				'spec_desc' => $p['spec_desc']
			))->save();
			
			$this->rows['product_item'] = $this->models['product_item']->find($p['item_id'])->current();
			$this->rows['product_item']->stock -= $p['num'];
			$this->rows['product_item']->save();
		}
		
		/* 处理优惠 */
		
		// 积分
		if (!empty($this->input->point_pay)) 
		{
			$this->_db->update('member',array('point' => new Zend_Db_Expr("point - {$this->input->point_pay}")),array('id = ?' => $this->_user->id));
			$this->rows['order_discount'] = $this->models['order_discount']->createRow(array(
				'order_id' => $this->rows['order']->id,
				'type' => 0,
				'amount' => $this->input->point_pay
			));
			$this->rows['order_discount']->save();
		}
		
		// 优惠券
		if (!empty($this->input->coupon_user_id)) 
		{
			$value = $this->_db->select()
				->from(array('u' => 'coupon_user'),array())
				->joinLeft(array('c' => 'coupon'),'c.id = u.coupon_id',array('value'))
				->where('u.id = ?',$this->input->coupon_user_id)
				->query()
				->fetchColumn();
			$this->_db->update('coupon_user',array('status' => 0),array('id = ?' => $this->input->coupon_user_id));
			$this->rows['order_discount'] = $this->models['order_discount']->createRow(array(
				'order_id' => $this->rows['order']->id,
				'type' => 1,
				'coupon_user_id' => $this->input->coupon_user_id,
				'amount' => $value
			));
			$this->rows['order_discount']->save();
		}
		
		/* 清除购物车 */
		
		if (!empty($this->input->carts)) 
		{
			//$this->input->carts = base64_decode($this->input->carts);
			$ids = explode(',',$this->input->carts);
			$this->_db->delete(array('c' => 'cart'),array('member_id = ?' => $this->_user->id,'id IN (?)' => $ids));
		}
		
		$this->json['errno'] = '0';
		$this->json['order_id'] = $this->rows['order']->id;
		$this->json['status'] = $this->rows['order']->status;
		$this->_helper->json($this->json);
	}

	/**
	 *  收银台
	 */
	public function payAction()
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
		
		$this->json['order'] = array();
		
		$order = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		$isWeixin = isWeixin();
		if($isWeixin){
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
			//dump($jsApiParameters);exit;
			$this->view->jsApiParameters = $jsApiParameters;
		}
		$this->view->isWeixin = $isWeixin;
		$this->view->order = $order;
	}

	/**
	 *  支付宝支付
	 */
	public function gopayAction() 
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
		$order = $this->_db->select()
			->from(array('o' => 'order'),array('id','subject','body','pay_amount'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		//支付宝
		include "lib/api/aliwappay/lib/alipay_submit.class.php";
		//支付信息
		$config = Zend_Registry::get('config');
		$alipay_config['partner'] = $config->pay->alipay->partnerId;
		$alipay_config['key']= $config->pay->alipay->key;
		$alipay_config['seller_id']	= $config->pay->alipay->sellerId;
		$alipay_config['sign_type'] = strtoupper('RSA');
		$alipay_config['input_charset'] = strtolower('utf-8');
		$alipay_config['cacert'] = 'lib/api/aliwappay/cacert.pem';
		$alipay_config['private_key_path'] = 'lib/api/aliwappay/key/rsa_private_key.pem';
		$alipay_config['ali_public_key_path'] = 'lib/api/aliwappay/key/alipay_public_key.pem';
		$alipay_config['transport'] = 'http';
		$seller_email = $config->pay->alipay->sellerId;
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$payment_type = "1";
		$exter_invoke_ip = $_SERVER['REMOTE_ADDR'];
		$out_trade_no = $order['id'];
		$subject = $order['body'];
		$total_fee = $order['pay_amount'];
		$parameter = array(
				"service" => "alipay.wap.create.direct.pay.by.user",
				"partner" => trim($alipay_config['partner']),
				"seller_id" => trim($alipay_config['seller_id']),
				"sign_type" => 'RSA',
				"payment_type"	=> $payment_type,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"total_fee"	=> $total_fee,
				"notify_url"	=> DOMAIN.'pay/aliapp/notify2',
				"return_url"	=> DOMAIN.'order/order/list',
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
		
		/* 获取订单列表 */
		
		$this->json['order_list'] = array();
		
		$select = $this->_db->select()
			->from(array('o' => 'order'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('o.buyer_id = ?',$this->_user->id)
			->where('o.display = ?',1);
		$this->view->status = $this->input->status;
		if ($this->input->status !== '') 
		{
			$this->input->status = explode(',',$this->input->status);
			$select->where('o.status IN (?)',$this->input->status);
			
			if ($this->input->status == Model_Order::TRADE_FINISHED) 
			{
				$select->where('o.feedback = ?',0);
			}
		}
		else 
		{
			$select->where('o.status > ?',-1);
		}
		
		// 总数
		$count = $select->query()
			->fetchColumn();
		
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
			$order['pay_amount'] = $r['pay_amount'];
			$order['feedback'] = $r['feedback'];
			$order['status'] = $r['status'];
			if($order['status']==0){
				$order['status_name'] = '待付款';
			}else if($order['status']==1){
				$order['status_name'] = '待发货';
			}else if($order['status']==2){
				$order['status_name'] = '待确认收货';
			}else if($order['status']==3){
				$order['status_name'] = '已完成';
				if($r['feedback']==1){
					$order['status_name'] = '已评价';
				}
			}else if($order['status']==10){
				$order['status_name'] = '申请退货';
			}else if($order['status']==11){
				$order['status_name'] = '待退货';
			}else if($order['status']==12){
				$order['status_name'] = '待确认退货';
			}else if($order['status']==13){
				$order['status_name'] = '已退货';
			}else if($order['status']==14){
				$order['status_name'] = '拒绝退货';
			}
			// 订单产品
			$order['items'] = $this->_db->select()
				->from(array('i' => 'order_item'),array('product_id','item_name','image','price','num','spec_desc'))
				->where('i.order_id = ?',$r['id'])
				->query()
				->fetchAll();
			$order['item_count'] = count($order['items']);
			$orderList[] = $order;
		}

		$this->view->orderList = $orderList;
	}
	
	/**
	 *  订单详情
	 */
	public function detailAction()
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
		
		$order = array();
		$result = $this->_db->select()
			->from(array('o' => 'order'))
			->where('o.id = ?',$this->input->id)
			->query()
			->fetch();
		if (!empty($result)) 
		{
			$result['clock'] = $result['clock'] - SCRIPT_TIME;
			$order = $result;
		}
		$this->view->order = $order;
		
		/* 收货人 */
		
		$consignee = array();
		if (!empty($order)) 
		{
			$consignee['consignee'] = $order['consignee'];
			$consignee['province_id'] = $order['province_id'];
			$consignee['city_id'] = $order['city_id'];
			$consignee['county_id'] = $order['county_id'];
			$consignee['address'] = $order['address'];
			$consignee['zip'] = $order['zip'];
			$consignee['mobile'] = $order['mobile'];
			$consignee['telephone'] = $order['telephone'];
		}
		$this->view->consignee = $consignee;
		
		/* 产品 */
		
		$items = array();
		$results = $this->_db->select()
			->from(array('i' => 'order_item'),array('id','sn','product_id','item_name','image','price','num','spec_desc'))
			->where('i.order_id = ?',$this->input->id)
			->query()
			->fetchAll();
		if (!empty($results)) 
		{
			foreach ($results as $r) 
			{
				$url = DOMAIN . "orderuc/sn/verify?id={$r['id']}";
				$urlEncode = urlencode($url);
				$r['qrcode'] = DOMAIN . "utility/qrcode?content={$urlEncode}";;
				$items[] = $r;
			}
		}
		$this->view->items = $items;
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
	 *  获取产品信息
	 */
	protected function _cart()
	{
		$cartList = array();
		
		/* 获取购物车选中的产品 */
		
		$carts = array();
		$from = 0;
		if (!empty($this->input->item_id)) 
		{
			$item = $this->_db->select()
				->from(array('i' => 'product_item'),array('id','product_id','item_name','image','price','cost_price','spec_desc'))
				->joinLeft(array('p' => 'product'),'p.id = i.product_id',array('area','supplier_id'))
				->where('i.id = ?',$this->input->item_id)
				->query()
				->fetch();
			$item['item_id'] = $item['id'];
			unset($item['id']);
			$item['num'] = $this->input->num;
			$carts[] = $item;
			$from = ($item['area'] == 0) ? 0 : 1;
			$this->view->item_id = $this->input->item_id;
			$this->view->item_num = $this->input->num;
		}
		else 
		{
			$carts = $this->_db->select()
				->from(array('c' => 'cart'),array('id','item_id','num'))
				->joinLeft(array('i' => 'product_item'),'i.id = c.item_id',array('product_id','area','item_name','image','price','cost_price','spec_desc','stock'))
				->joinLeft(array('p' => 'product'),'p.id = i.product_id',array('area','supplier_id'))
				->where('c.member_id = ?',$this->_user->id)
				->where('c.selected = ?',1)
				->query()
				->fetchAll();
			foreach($carts as $key=>$v)
			{
				$id = $v['id'];
				$carts[$key]['item_id'] = $v['item_id'];
				$carts[$key]['num'] = $v['stock'] > $v['num'] ? $v['num'] : $v['stock'];
			}

			$from = 1;
		}
		
		/* 统计价格 */
		
		$cartList = array();
		foreach ($carts as $cart)
		{
			$cart['image'] = thumbpath($cart['image'],220);
			$cart['subtotal'] = $cart['price'] * $cart['num'];
			$cartList['amount'] = empty($cartList['amount']) ? $cart['subtotal'] : $cartList['amount'] + $cart['subtotal'];
			$cartList['products'][] = $cart;
		}

		$cartList['from'] = $from;
		
		return $cartList;
	}
}

?>