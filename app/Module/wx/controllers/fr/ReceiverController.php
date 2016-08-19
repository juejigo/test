<?php

class Wx_ReceiverController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  入口
	 */
	public function indexAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		/* 验证 */
		
		$this->params = array_merge($this->_request->getQuery(),$this->_request->getUserParams());
		if (!params($this)) 
		{
			echo $this->error->firstMessage();
			exit;
		}
		
		/* 接入认证 */
		
		$echoStr = $this->_request->getQuery('echostr');
		if (!empty($echoStr)) 
		{
			echo $echoStr;
        	exit;
		}
		
		/* 回复 */
		
		$this->vars['post_str'] = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (empty($this->vars['post_str'])) 
		{
			echo '';
    		exit;
		}
		
      	$this->vars['post_obj'] = simplexml_load_string($this->vars['post_str'],'SimpleXMLElement',LIBXML_NOCDATA);
        $msgType = $this->vars['post_obj']->MsgType;
        $fromUsername = $this->vars['post_obj']->FromUserName;
        $toUsername = $this->vars['post_obj']->ToUserName;
        
        /* 事件消息 */
        
        if ($msgType == 'event') 
        {
        	$event = $this->vars['post_obj']->Event;
        	
        	/* 关注 */
        	
        	if ($event == 'subscribe') 
        	{
				$refereeId = substr($this->vars['post_obj']->EventKey,8);
        		$qrscene = substr($this->vars['post_obj']->EventKey,0,7);
        		$ishas = $this->_db->select()
					->from(array('m' => 'member'))
					->where('m.openid = ?',$fromUsername)
					->query()
					->fetchColumn();

        		if($ishas != '' && $qrscene != 'qrscene')
        		{
        			$this->_db->update('member',array('subscribe' => 1),array('openid = ?' => $fromUsername));
        		}
        		else if($ishas != '' && $qrscene == 'qrscene')
        		{
        			$this->_db->update('member',array('subscribe' => 1,'referee_id' => $refereeId),array('openid = ?' => $fromUsername));        			 
        		}
        		else if($ishas == '' && $qrscene == 'qrscene')
        		{
        			$this->models['member'] = new Model_Member();
        			$this->rows['member'] = $this->models['member']->createRow(array(
        					'openid' => $fromUsername,
        					'referee_id' => $refereeId,
        					'status' => 1
        			));
        			$this->rows['member']->save();
        		}
        	}
        	else if ($event == 'unsubscribe') 
        	{
        		$this->_db->update('member',array('subscribe' => 0),array('openid = ?' => $fromUsername));
        	}
        	else if ($event == 'CLICK') 
	        {
	        	$key = $this->vars['post_obj']->EventKey;
	        	
	        	/* 关键字回复 */
        	
        		$this->_key($key);
	        }
        }
        
        /* 文本消息 */
        
        else if ($msgType == 'text')
        {
        	$key = trim($this->vars['post_obj']->Content);
        	
        	$this->_key($key);
        	
        	/* 转发到多客服 */
        	
        	$xml = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[transfer_customer_service]]></MsgType></xml>';
        	$resultStr = sprintf($xml,$fromUsername,$toUsername,SCRIPT_TIME);
    		echo $resultStr;
    		exit;
        }
        
        /* 地理位置消息 */
        
        else if ($msgType == 'location') 
        {
        	$this->_responseLocation();
        }
        
        $this->_defaultResponse();
	}
	
	protected function _defaultResponse()
	{
		$this->_responseText('恭喜宝宝加入温州第三期【王者争霸】之海天盛筵游艇趴，跟着疯狂的节拍动起来吧！');
	}
	
	protected function _responseText($text,$unsetaction = false)
	{
		if ($unsetaction) 
		{
			$this->_unsetaction();
		}
		
		$fromUsername = $this->vars['post_obj']->FromUserName;
        $toUsername = $this->vars['post_obj']->ToUserName;
        
        $xml = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>0</FuncFlag></xml>';
		$resultStr = sprintf($xml,$fromUsername,$toUsername,SCRIPT_TIME,$text);
    	echo $resultStr;
    	
        exit;
	}
	
	protected function _responseArticle($items,$unsetaction = false)
	{
		if ($unsetaction) 
		{
			$this->_unsetaction();
		}
		
		$fromUsername = $this->vars['post_obj']->FromUserName;
        $toUsername = $this->vars['post_obj']->ToUserName;
        $count = count($items);
        
        $xml = "<xml><ToUserName><![CDATA[{$fromUsername}]]></ToUserName><FromUserName><![CDATA[{$toUsername}]]></FromUserName><CreateTime>" . SCRIPT_TIME . "</CreateTime><MsgType><![CDATA[news]]></MsgType>";
		$xml .= "<ArticleCount>{$count}</ArticleCount>";
		$xml .= '<Articles>';
		foreach ($items as $item) 
		{
			$xml .= "<item><Title><![CDATA[{$item['title']}]]></Title> <Description><![CDATA[{$item['description']}]]></Description><PicUrl><![CDATA[{$item['picurl']}]]></PicUrl><Url><![CDATA[{$item['url']}]]></Url></item>";
		}
		$xml .= '</Articles></xml>';
    	echo $xml;
    	
        exit;
	}
	
	protected function _responseLocation()
	{
		$this->_responseText('');
	}
	
	protected function _key($key)
	{
		if ($key == '本期报名') 
		{
			$count = 1;
			$item = array(
				'title' => '温州第三期《王牌争霸》之海天盛筵游艇趴',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/1_3.png',
				'description' => '动起来吧，比基尼辣妹！',
				'url' => 'http://www.zhongyouwl.com/vote/vote?vote_id=24#rd'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '本期活动') 
		{
			$count = 1;
			$item = array(
				'title' => '温州第三期《王牌争霸》之海天盛筵游艇趴',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/1_5.jpg',
				'description' => '动起来吧，比基尼辣妹！',
				'url' => 'http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=2650124893&idx=1&sn=fe1ab68444272110278007a01b0bf766&scene=0#wechat_redirect'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '下期预告') 
		{
			$count = 1;
			$item = array(
				'title' => '【第三期预告】低调奢华的游艇party，不管帅哥美女撩的就是你！',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/1_3.png',
				'description' => '友谊的巨轮说不翻就不翻，come on baby，湿身就是为了诱惑你！',
				'url' => 'https://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=2650124843&idx=1&sn=7e507aed79d5e66081f57c69fe3b3fe7&scene=0&key=b28b03434249256bf0abecb41204bd3bd48e6a38eb2457c4218d414abc5089faf085d15ed1cd6da1146b03925e846205&ascene=7&uin=Nzk2NzQ4OTgx&devicetype=android-21&version=26030f35&nettype=WIFI&pass_ticket=v2hn7olZZvw%2FgFPf755%2BQ92NlhomdviQGzpBSsG1X9YFmu%2FM%2FIezXgSi8NZ%2BFU5%2B'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '往期回顾') 
		{
			$count = 1;
			$item = array(
				'title' => '看往期  温故而知新',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/5.jpg',
				'description' => '看看你都错过了什么！',
				'url' => 'http://mp.weixin.qq.com/mp/homepage?__biz=MzI0MTEyOTY5MQ==&hid=1&sn=2fd6b79d6526e70bf9b684bff9e73968#wechat_redirect'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '行业动态') 
		{
			$count = 1;
			$item = array(
				'title' => '【旅游风向标】聚焦两会旅游动态',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/hydt.png',
				'description' => '解读两会丨2016年两会上的旅游提案',
				'url' => 'http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=502641325&idx=1&sn=8ae43b523de8b1d9508e96aead75823f&scene=0&previewkey=KMuGVBG8InoEUahxdtKjO8wqSljwj2bfCUaCyDofEow%3D#wechat_redirect'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '政策法规') 
		{
			$count = 1;
			$item = array(
				'title' => '两会发出”旅游好声音“政策',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/zcfg.png',
				'description' => '全国政协委员、联想集团总裁兼CEO杨元庆提交了《关于进一步落实企事业单位员工带薪休假的提案》。',
				'url' => 'http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=502641327&idx=1&sn=a707b7e0bcc01b51cd684ec756dca220&scene=0&previewkey=KMuGVBG8InoEUahxdtKjO8wqSljwj2bfCUaCyDofEow%3D#wechat_redirect'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '旅游攻略') 
		{
			$count = 1;
			$item = array(
				'title' => '泰国已经成功“上位”，月薪3000即可拿下！',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/lygl.png',
				'description' => '据说来这里，价格便宜，距离适宜，手续方便，接着就可以享受完美的旅行啦！',
				'url' => 'http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=502641297&idx=1&sn=3d9aca75b5dc49503d428d98bec712ea&scene=0&previewkey=KMuGVBG8InoEUahxdtKjO8wqSljwj2bfCUaCyDofEow%3D#wechat_redirect'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == 'app下载') 
		{
			$count = 1;
			$item = array(
				'title' => '友趣游APP下载',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/3_1.png',
				'description' => '友趣游是一款以旅游尾单为切入点APP客户端平台。',
				'url' => 'http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&tempkey=eYyYfaaklcwor4d9iFASgxJjvNTN3k%2F%2FXtHzDrTi4DQqLpZ5wPzEers7YATtExwebkXGOLj0etj6sR3mZ9xcmSS%2FizC0HnGA7fZ8SJWk7q5LKGoFggOFF88YYWKCj3B%2Fh52OiCxcV%2BbXmqVkMVL4TQ%3D%3D&scene=1&srcid=0629b4D1xjuaMAhbBBEXIkWI#wechat_redirect'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '联系我们') 
		{
			$count = 1;
			$item = array(
				'title' => '联系我们',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/3_2.png',
				'description' => '客服热线：0577-88898776手机号： 13587630625（同微信）客服Q Q：22183057380',
				'url' => 'http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=402336101&idx=1&sn=ea940d6345f52225a6dc7dbd93f61f14&scene=18#rd'
			);
			$this->_responseArticle(array($item));
		}
		else if ($key == '企业文化') 
		{
			$count = 1;
			$item = array(
				'title' => '众游网络科技有限公司简介',
				'picurl' => 'http://www.zhongyouwl.com/static/style/default/image/wx/qywh.jpg',
				'description' => '一触即发，众游天下！',
				'url' => 'http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=502641353&idx=1&sn=381de770ee4cba39511ad82f7b05d81f&scene=0&previewkey=KMuGVBG8InoEUahxdtKjO8wqSljwj2bfCUaCyDofEow%3D#wechat_redirect'
			);
			$this->_responseArticle(array($item));
		}
	}
	
	
	/* =======  以下部分为保留功能  ======= */
	
	protected function _action($key)
	{
		$this->rows['wx_action'] = $this->models['wx_action']->fetchRow($this->models['wx_action']->select()
			->where('site_id = ?',$this->paramInput->siteid)
			->where('openid = ?',$this->vars['post_obj']->FromUserName));
		if (empty($this->rows['wx_action'])) 
		{
			return;
		}
		
		/* 快递查询 */
		
		if ($this->rows['wx_action']->action == 'express') 
		{
			if ($this->rows['wx_action']->step == '1') 
			{
				$orderList = $this->_db->select()
					->from(array('o' => 'wx_order'))
					->where('o.site_id = ?',$this->paramInput->siteid)
					->where('o.buyer_openid = ?',$this->vars['post_obj']->FromUserName)
					->order('o.dateline DESC')
					->query()
					->fetchAll();
				
				$i = intval($key) - 1;
				if (array_key_exists($i,$orderList)) 
				{
					$orderId = $orderList[$i]['order_id'];
					$moduleConfig = $this->_db->select()
						->from(array('c' => 'wx_config'))
						->where('c.site_id = ?',$this->paramInput->siteid)
						->query()
						->fetch();
					if (empty($moduleConfig)) 
					{
						$this->_responseText('微信功能未配置',true);
					}
					$this->vars['module_config'] = $moduleConfig;
					$wxShop = new Core2_Wx_Shop($moduleConfig['appid'],$moduleConfig['appsecret']);
					$result = $wxShop->getOrderById($orderId);
					
					if (!empty($result) && $result['errcode'] == '0') 
					{
						$order = $result['order'];
						if ($order['order_status'] != '3') 
						{
							$this->_responseText('订单已经完成未发货',true);
						}
						
						$switch = array(
							'Fsearch_code' => 'youzhengguonei',
							'002shentong' => 'shentong',
							'066zhongtong' => 'zhongtong',
							'056yuantong' => 'yuantong',
							'042tiantian' => 'tiantian',
							'003shunfeng' => 'shunfeng',
							'059Yunda' => 'yunda',
							'064zhaijisong' => 'zhaijisong',
							'020huitong' => 'huitongkuaidi');
						if (!array_key_exists($order['delivery_company'],$switch)) 
						{
							$this->_responseText('快递公司不详',true);
						}
						
						$time = SCRIPT_TIME;
			        	$title = "点这里查看物流信息";
			        	$description = "订单号：{$order['order_id']}";
				        $url = "http://m.kuaidi100.com/index_all.html?type={$switch[$order['delivery_company']]}&postid={$order['delivery_id']}&callbackurl=http://mp.weixin.qq.com/bizmall/mallshelf?id=&t=mall/list&biz=MzA5NTk2NjAxNA==&shelf_id=2&showwxpaytitle=1#wechat_redirect";
				        
						$str = "
						<xml>
						<ToUserName><![CDATA[{$this->vars['post_obj']->FromUserName}]]></ToUserName>
						<FromUserName><![CDATA[{$this->vars['post_obj']->ToUserName}]]></FromUserName>
						<CreateTime>{$time}</CreateTime>
						<MsgType><![CDATA[news]]></MsgType>
						<ArticleCount>1</ArticleCount>
						<Articles>
						<item><Title><![CDATA[{$title}]]></Title><Description><![CDATA[{$description}]]></Description><Url><![CDATA[{$url}]]></Url></item>
						</Articles>
						</xml>";
						
						$this->_unsetaction();
						echo $str;
			    		exit;
					}
				}
			}
		}
	}
	
	protected function _setaction($action,$step)
	{
		$this->rows['wx_action'] = $this->models['wx_action']->fetchRow($this->models['wx_action']->select()
			->where('site_id = ?',$this->paramInput->siteid)
			->where('openid = ?',$this->vars['post_obj']->FromUserName));
		if (empty($this->rows['wx_action'])) 
		{
			$this->rows['wx_action'] = $this->models['wx_action']->createRow(array(
				'site_id' => $this->paramInput->siteid,
				'openid' => $this->vars['post_obj']->FromUserName));
		}
		
		$this->rows['wx_action']->action = $action;
		$this->rows['wx_action']->step = $step;
		$this->rows['wx_action']->dateline = SCRIPT_TIME;
		$this->rows['wx_action']->save();
	}
	
	protected function _unsetaction()
	{
		$this->rows['wx_action'] = $this->models['wx_action']->fetchRow($this->models['wx_action']->select()
			->where('site_id = ?',$this->paramInput->siteid)
			->where('openid = ?',$this->vars['post_obj']->FromUserName));
		
		if (!empty($this->rows['wx_action'])) 
		{
			$this->rows['wx_action']->delete();
		}
	}
}

?>