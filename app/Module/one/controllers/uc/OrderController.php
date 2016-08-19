<?php

class Oneuc_OrderController extends Core2_Controller_Action_Uc
{
	/**
	 *  初始化
	 */
	public function init()
	{	
		parent::init();
		
		/* 微信分享 */
		
		require_once "lib/api/wxwebpay/jssdk.php";
		$jssdk = new JSSDK($this->_config->wx->appid,$this->_config->wx->secret,'');
		$signPackage = $jssdk->GetSignPackage();
		$this->view->wxSign = $signPackage;
		
		$this->models['member'] = new Model_Member();
		$this->models['one_order'] = new Model_OneOrder();
		$this->models['one_phase'] = new Model_OnePhase();
		
		if(!$this->_auth->hasIdentity())
		{
			$session = new Zend_Session_Namespace('wx_redirect_url');
			$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$session->url = $redirectUrl;
			$this->_redirect('/wx/user/auth');
		}
	}
	
	/**
	 *  中奖列表
	 */
	public function winlistAction()
	{
		/* 检验传值 */
	
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
	}
	
	/**
	 *  夺宝记录
	 */
	public function orderlistAction()
	{
		/* 检验传值 */
	
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
	}
	
	/**
	 *  充值记录
	 */
	public function rechargelistAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 充值列表*/
		
		$rechargeList = $this->_db->select()
			->from(array('o' => 'one_order'))
			->where('o.type = ?',1)
			->where('o.buyer_id = ?',$this->_user->id)
			->order('o.dateline DESC')
			->query()
			->fetchAll();
		$this->view->rechargeList = $rechargeList;
	}
	
	/**
	 *  微信支付
	 */
	public function payAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		/* 期数信息*/
		
		$phaseInfo = $this->_db->select()
			->from(array('p' => 'one_phase'))
			->where('p.id = ?',$this->paramInput->id)
			->query()
			->fetch();
		$this->view->phaseInfo = $phaseInfo;
		
		//账户夺宝币
		$balance = $this->_db->select()
			->from(array('m' => 'member'),'coin')
			->where('m.id = ?',$this->_user->id)
			->query()
			->fetchColumn();
		$this->view->balance = $balance;
	}
	
	/**
	 *  支付成功
	 */
	public function paysuccessAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
	}
	
	/**
	 *  充值
	 */
	public function rechargeAction()
	{
		/* 检验传值 */
		
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
	}
	
	/**
	 *  微信支付
	 */
	public function wxpayAction()
	{
		/* 检验传值 */
	
		if (!params($this))
		{
			$this->_helper->notice($this->error->firstMessage(),$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回')
			));
		}
		
		if(isWeixin())
		{
			$openId = $this->_user->openid;
			$oneOrder = $this->_db->select()
				->from(array('o' => 'one_order'),array('id','amount'))
				->where('o.id = ?',$this->paramInput->id)
				->query()
				->fetch();
			$body = '夺宝币';
			$payAmount = $oneOrder['amount']*100;
			$payAmount = (string)$payAmount;
			require_once "lib/api/wxwebpay/lib/WxPay.Api.php";
			require_once "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
		
			$tools = new JsApiPay();
			$input = new WxPayUnifiedOrder();
			$input->SetBody($body);
			$input->SetOut_trade_no($oneOrder['id']);
			$input->SetTotal_fee($payAmount);
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetNotify_url(DOMAIN . 'pay/wxweb/onenotify');
			$input->SetTrade_type("JSAPI");
			$input->SetOpenid($openId);
			$payOrder = WxPayApi::unifiedOrder($input);
			$jsApiParameters = $tools->GetJsApiParameters($payOrder);
		
			$this->view->jsApiParameters = $jsApiParameters;
		}
	}
	
	/**
	 *  ajax
	 */
	public function ajaxAction()
	{

		if (!$this->_request->isXmlHttpRequest())
		{
			exit;
		}
		
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		
		
		/* 检验传值 */
		
		if (!ajax($this))
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		switch ($op)
		{
			/* 中奖列表*/
		
			case 'winlist':
		
				/* 分页*/
		
				$perpage = 10;
				$phaseSelect = $this->_db->select()
					->from(array('o' => 'one_order'),array(new Zend_Db_Expr('count(*)')))
					->joinLeft(array('l' => 'one_lucky_num'),'o.id = l.order_id')
					->where('o.buyer_id = ?',$this->_user->id)
					->where('o.status > ?',0)
					->where('o.type = ?',2)
					->where('l.is_win = ?',1);
				
				/* 判断是否正确页码*/
		
				$phaseCount = $phaseSelect->query()->fetchColumn();
				if ($this->input->page>ceil(($phaseCount/$perpage)))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有更多';
					$this->_helper->json($json);
				}
		
				/* 列表*/
		
				$phaseList = array();
				$phaseListResult = $phaseSelect->reset(Zend_Db_Select::COLUMNS)
					->columns(array('phase_id','id'),'o')
					->columns(array('lucky_num'),'l')
					->joinLeft(array('p' => 'one_phase'), 'o.phase_id = p.id',array('image','product_name','lottery_time','no'))
					->order('p.lottery_time DESC')
					->limitPage($this->input->page,$perpage)
					->query()
					->fetchAll();

				if (!empty($phaseListResult))
				{
					foreach ($phaseListResult as $key => $r)
					{
						/* 幸运号码*/
						
						$luckyNum = $this->_db->select()
							->from(array('o' => 'one_order'),'id')
							->joinLeft(array('l' => 'one_lucky_num'), 'o.id = l.order_id',array('lucky_num'))
							->where('o.phase_id = ?',$r['phase_id'])
							->where('o.buyer_id = ?',$this->_user->id)
							->where('o.status > ?',0)
							->where('o.type = ?',2)
							->query()
							->fetchAll();
						$phaseList[$key]['arr_num'] = '';
						foreach ($luckyNum as $lucky)
						{
							$phaseList[$key]['arr_num'] .= "　".$lucky['lucky_num'];
						}
						
						$phaseList[$key]['acount'] = count($luckyNum);
						$phaseList[$key]['id'] = $r['id'];
						$phaseList[$key]['img'] = thumbpath($r['image'],620);
						$phaseList[$key]['title'] = $r['product_name'];
						$phaseList[$key]['phase_id'] = $r['phase_id'];
						$phaseList[$key]['no'] = $r['no'];
						$phaseList[$key]['lucky'] = $r['lucky_num'];
						$phaseList[$key]['time'] = date("Y-m-d H:i:s",$r['lottery_time']);
					}
				}
				$json['list'] = $phaseList;
				$json['errno'] = 0;
				$this->_helper->json($json);
				break;
			
			/* 订单列表*/
			
			case 'orderlist':
				
				/* 分页*/
				
				$perpage = 10;
				$phaseSelect = $this->_db->select()
					->from(array('o' => 'one_order'),array(new Zend_Db_Expr('count(distinct(phase_id))')))
					->joinLeft(array('p' => 'one_phase'), 'o.phase_id = p.id',array('image','product_name','lottery_time','no','need_num','now_num','lucky_num','status','winner_id'))
					->where('o.buyer_id = ?',$this->_user->id)
					->where('o.type = ?',2)
					->where('o.status > ?',0);
				
				if ($this->input->type == 'all')
				{
					$phaseSelect->where('p.status > ?',0);
				}
				else
				{
					$phaseSelect->where('p.status = ?',$this->input->type);
				}
				
				/* 判断是否正确页码*/
				
				$phaseCount = $phaseSelect->query()->fetchColumn();
				if ($this->input->page>ceil(($phaseCount/$perpage)))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有更多';
					$this->_helper->json($json);
				}
				
				/* 列表*/
				
				$phaseList = array();
				$phaseListResult = $phaseSelect->reset(Zend_Db_Select::COLUMNS)
					->columns(array('phase_id','id'),'o')					
					->columns(array('image','product_name','lottery_time','no','need_num','now_num','lucky_num','status','winner_id'),'p')
					->joinLeft(array('f' => 'member_profile'),'p.winner_id = f.member_id',array('member_name','member_id'))
					->group('o.phase_id')
					->order('o.pay_time DESC')
					->limitPage($this->input->page,$perpage)	
					->query()
					->fetchAll();
				
				if (!empty($phaseListResult))
				{
					foreach ($phaseListResult as $key => $r)
					{
						/* 幸运号码*/
						
						$luckyNum = $this->_db->select()
							->from(array('o' => 'one_order'),'id')
							->joinLeft(array('l' => 'one_lucky_num'), 'o.id = l.order_id',array('lucky_num'))
							->where('o.phase_id = ?',$r['phase_id'])
							->where('o.buyer_id = ?',$this->_user->id)
							->where('o.status > ?',0)
							->where('o.type = ?',2)
							->query()
							->fetchAll();
						$phaseList[$key]['arr_num'] = '';
						foreach ($luckyNum as $lucky)
						{
							$phaseList[$key]['arr_num'] .= "　".$lucky['lucky_num'];
						}
						
						$phaseList[$key]['status'] = $r['status'];
						if ($r['status'] == 1)
						{
							$phaseList[$key]['need_num'] = $r['need_num'];
							$phaseList[$key]['schedule'] = $r['now_num']/$r['need_num']*100;
						}
						else if ($r['status'] == 2)
						{
							$phaseList[$key]['need_num'] = $r['need_num'];
							$phaseList[$key]['schedule'] = $r['now_num']/$r['need_num']*100;
						}
						else if ($r['status'] == 3)
						{
							//本期参与人次
							$num = $this->_db->select()
								->from(array('o' => 'one_order'),array(new Zend_Db_Expr('SUM(num)')))
								->where('o.phase_id = ?',$r['phase_id'])
								->where('o.buyer_id = ?',$r['winner_id'])
								->where('o.status > ?',0)
								->where('o.type = ?',2)
								->query()
								->fetchColumn();
							$phaseList[$key]['name'] = $r['member_name'];
							$phaseList[$key]['num'] = $num;
							$phaseList[$key]['lucky'] = $r['lucky_num'];
							$phaseList[$key]['time'] = date("Y-m-d H:i:s",$r['lottery_time']);
						}
						$phaseList[$key]['acount'] = count($luckyNum);
						$phaseList[$key]['id'] = $r['id'];
						$phaseList[$key]['img'] = thumbpath($r['image'],620);
						$phaseList[$key]['title'] = $r['product_name'];
						$phaseList[$key]['phase_id'] = $r['phase_id'];
						$phaseList[$key]['no'] = $r['no'];
					}
				}
				$json['list'] = $phaseList;
				$json['errno'] = 0;
				$this->_helper->json($json);
				break;
				
			/* 充值*/
				
			case 'wxpay':
				
				/* 生成订单 */
				
				$address = $this->_db->select()
					->from(array('a' => 'one_address'))
					->where('member_id = ?',$this->_user->id)
					->query()
					->fetch();
					
				$id = $this->models['one_order']->createId();	
				$row = array(
					'id' => $id,
					'buyer_id' => $this->_user->id,
					'phase_id' => 0,
					'subject' => '夺宝币充值',
					'body' => '夺宝币充值'.$id,
					'num' => 0,
					'amount' => $this->input->money,
					'type' => 1,
				);
				//是否填写收货地址
				if (!empty($address['name']))
				{
					$row['consignee'] = $address['name'];
					$row['province_id'] =  $address['province_id'];
					$row['city_id'] = $address['city_id'];
					$row['county_id'] = $address['county_id'];
					$row['address'] = $address['address'];
					$row['zip'] = $address['post_code'];
					$row['mobile'] = $address['mobile'];
				}
				
				$this->rows['one_order'] = $this->models['one_order']->createRow($row);
				$this->rows['one_order']->save();
				$json['errno'] = 0;
				$json['id'] = $id;
				$this->_helper->json($json);
				break;
			
			/* 支付*/
				
			case 'pay':
				
				/* 期数信息*/
				
				$phase = $this->_db->select()
					->from(array('p' => 'one_phase'))
					->where('p.id = ?',$this->input->id)
					->query()
					->fetch();
				
				//会员信息
				$memberInfo = $this->_db->select()
					->from(array('m' => 'member'),'coin')
					->joinLeft(array('a' => 'one_address'), 'm.id =a.member_id')
					->where('m.id = ?',$this->_user->id)
					->query()
					->fetch();
				
				//是否有钱
				if ($memberInfo['coin'] < ($this->input->num*$phase['price']))
				{
					$json['errno'] = 2;
					$json['errmsg'] = '余额不足';
					$this->_helper->json($json);
				}
					
				//是否设置限购
				if ($phase['limit_num'] != 0)
				{	
					//已购买次数
					$num = $this->_db->select()
						->from(array('o' => 'one_order'),array(new Zend_Db_Expr('SUM(num)')))
						->where('o.phase_id = ?',$this->input->id)
						->where('o.buyer_id = ?',$this->_user->id)
						->where('o.status > ?',0)
						->where('o.type = ?',2)
						->query()
						->fetchColumn();
					
					if (($num + $this->input->num) > $phase['limit_num'])
					{
						$json['errno'] = 1;
						$json['errmsg'] = 'Sorry,baby,您的购买已超过限购次数';
						$this->_helper->json($json);
					}
				}
				
				//是否超过总次数
				if ($this->input->num > ($phase['need_num'] - $phase['now_num']))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '不能下单超过剩余人次，下次要快点下单哦';
					$this->_helper->json($json);
				}
				
				//扣除余额
				$this->rows['member'] = $this->models['member']->find($this->_user->id)->current();
				$this->rows['member']->coin -= $this->input->num*$phase['price'];
				$this->rows['member']->save();
				
				/* 生成订单 */
				
				$id = $this->models['one_order']->createId();				
				$row = array(
					'id' => $id,
					'buyer_id' => $this->_user->id,
					'phase_id' => $this->input->id,
					'subject' => $phase['product_name'],
					'body' => $phase['product_name'],
					'num'  => $this->input->num,
					'amount'  => $this->input->num*$phase['price'],
					'pay_time' => time(),
					'payment' => 'coin',
 					'type' => 2,
					'status' => 1,
				);
				
				//是否填写了收货地址
				if (!empty($memberInfo['name']))
				{
					$row['consignee'] = $memberInfo['name'];
					$row['province_id'] =  $memberInfo['province_id'];
					$row['city_id'] = $memberInfo['city_id'];
					$row['county_id'] = $memberInfo['county_id'];
					$row['address'] = $memberInfo['address'];
					$row['zip'] = $memberInfo['post_code'];
					$row['mobile'] = $memberInfo['mobile'];
				}
				
				$this->rows['one_order'] = $this->models['one_order']->createRow($row);
				$this->rows['one_order']->save();
				
				require_once 'includes/function/order.php';
				afterOnePay($this->rows['one_order']->toArray());
				
				$json['errno'] = 0;
				$json['id'] = $id;
				$this->_helper->json($json);
				break;
				
			default:
				break;
		}
	}
}
?>