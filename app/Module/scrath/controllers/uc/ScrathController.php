<?php 
class Scrathuc_ScrathController extends Core2_Controller_Action_Uc
{
    public function init()
    {
        parent::init();
        
        /* 微信分享 */

        require_once "lib/api/wxwebpay/jssdk.php";
        $jssdk = new JSSDK($this->_config->wx->appid,$this->_config->wx->secret,'');
        $signPackage = $jssdk->GetSignPackage();
        $this->view->wxSign = $signPackage;
        
        $this->models['scrath_order'] = new Model_ScrathOrder();
        $this->models['scrath_card'] = new Model_ScrathCard();
        $this->models['scrath_product'] = new Model_ScrathProduct();
        $this->models['scrath_prize'] = new Model_ScrathPrize();
        $this->models['envelope_schedule'] = new Model_EnvelopeSchedule();
        $this->models['envelope_scheme'] = new Model_EnvelopeScheme();
        $this->models['envelope_from'] = new Model_EnvelopeFrom();
        
         if(!$this->_auth->hasIdentity())
         {
         	$session = new Zend_Session_Namespace('wx_redirect_url');
			$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$session->url = $redirectUrl;
            $this->_redirect('/wx/user/auth');
         }
    }
    
    public function indexAction()
    {
        if(!params($this))
        {
            /* 提示 */
            
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => 'javascript:history.go(-1);',
                    'text' => '返回上一页'),
            ));
        }
        
        $scrath  = $this->_db->select()
            ->from(array('o' => 'scrath'))
            ->where('o.id = ?',$this->paramInput->id)
             ->where('o.status = ?',1)
             ->where('o.end_time >=?',time())
            ->query()
            ->fetch();

        $this->view->scrath = $scrath;
        
        $this->view->wxTitle = $scrath['scrath_name'];
        $this->view->wxDescription = $scrath['scrath_name'];
        $this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST']."/scrathuc/scrath/index?id=".$this->paramInput->id;
        $this->view->wxImage = $scrath['image'];
    }
    
    /**
     * 付款
     */
   public function payAction()
   {
       if (!params($this))
       {
           $this->_helper->notice($this->error->firstMessage(),'','error_1',array(
               array(
                   'href' => 'javascript:history.go(-1);',
                   'text' => '返回上一面'),
           ));
       }
       
       //判断用户是否关注
       $member = $this->_db->select()
           ->from(array('o' => 'member'))
           ->where('o.id = ?',$this->_user->id)
           ->query()
           ->fetch();
       
       //查询活动是否到期
       $scrath = array();
       $row =  $this->_db->select()
           ->from(array('a' => 'scrath'))
           ->where('a.id = ?',$this->paramInput->id)
           ->where('a.status = ?',1)
           ->where('a.end_time >=?',time())
           ->query()
           ->fetch();
       $scrath=$row;
       
       /*判断用户是否还有抽奖次数*/
       
      $this->view->scrath = $scrath;
      $card_sum= $this->_db->select()
			->from(array('t' => 'scrath_card'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('t.scrath_id = ?',$this->paramInput->id)
			->where('t.member_id = ?',$this->_user->id);	
	  $count = $card_sum->query()
			->fetchColumn();
	  
	  /*判断用户是否中奖两次*/
	  
	  $prize_num = $this->_db->select()
    	  ->from(array('t' => 'scrath_card'),array(new Zend_Db_Expr('COUNT(*)')))
    	  ->where('t.is_prize = ?',1)
    	  ->where('t.scrath_id = ?',$this->paramInput->id)
    	  ->where('t.member_id = ?',$this->_user->id);
	  $prize_count = $prize_num->query()
	        ->fetchColumn();
	  
	  if($_COOKIE[$this->_user->id] == 1)
	  {
	      $scrath['lottery_num'] += 1;
	  }

     if($count >= $scrath['lottery_num'])
	  {

	      if($_COOKIE[$this->_user->id] != 1 && $prize_count < $scrath['lottery_num'])
	      {
	          //分享家抽奖次数
	          $this->view->type = "4";
	      }
	      else 
	      {
	          //抽奖次数已用完
	          $this->view->type = "2";
	      }  

	  }
	  else  if($scrath == "")
       {
           //活动到期或不存在
           $this->view->type = "1";
       }
	  else if($prize_count >= $scrath['lottery_num'])
	  {
	      //抽奖次数已用完
	      $this->view->type = "3";
	  }
	  else
	  {
	      /*创建订单*/
	       
	      $orderId=$this->_createOrderAction($this->paramInput);
	      
	      if(!$orderId)
	      {
	          $this->_helper->notice($this->error->firstMessage(),'','error_1',array(
	              array(
	                  'href' => 'javascript:history.go(-1);',
	                  'text' => '金额小于抽奖金额'),
	          ));
	      }
	      
	      $thsi->view->order_id = $orderId;
	      
	      $isWeixin = isWeixin();
	      if($isWeixin)
	      {
	          
	          $openId = $this->_user->openid;
	          $scrathOrder = $this->_db->select()
    	          ->from(array('o' => 'scrath_order'),array('id','total_money'))
    	          ->where('o.id = ?',$orderId)
    	          ->query()
    	          ->fetch();
	      
	          $body = '刮刮卡';
	          $payAmount = $scrathOrder['total_money']*100;
	          $payAmount = (string)$payAmount;
	          require_once "lib/api/wxwebpay/lib/WxPay.Api.php";
	          require_once "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
	          	
	          $tools = new JsApiPay();
	          $input = new WxPayUnifiedOrder();
	          $input->SetBody($body);
	          $input->SetOut_trade_no($scrathOrder['id']);
	          $input->SetTotal_fee($payAmount);
	          $input->SetTime_start(date("YmdHis"));
	          $input->SetTime_expire(date("YmdHis", time() + 600));
	          $input->SetNotify_url(DOMAIN . 'pay/wxweb/notify');
	          $input->SetTrade_type("JSAPI");
	          $input->SetOpenid($openId);
	          	
	          $payOrder = WxPayApi::unifiedOrder($input);
	          $jsApiParameters = $tools->GetJsApiParameters($payOrder);

	          $this->view->jsApiParameters = $jsApiParameters;
	      }
	       
	      $this->view->isWeixin = $isWeixin;
	      $this->view->order_id=$orderId;
	      $this->view->pay=$orderId;
	      
	      
	      $this->view->wxTitle = $scrath['scrath_name'];
	      $this->view->wxDescription = $scrath['scrath_name'];
	      $this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST']."/scrathuc/scrath/index?id=".$this->paramInput->id;
	      $this->view->wxImage = $scrath['image'];
	  }

   }
   
   
   /**
    *创建订单
    */
   protected function _createOrderAction($input)
   {
       //判断金额是否大于抽奖金额
        $scrath = $select = $this->_db->select()
            ->from(array('a' => 'scrath'))
            ->where('a.id = ?',$input->id)
            ->query()
            ->fetch();
      
        $this->view->order_money = $scrath['draw_amount'];
       /* 生成订单 */
        
        $id = $this->models['scrath_order']->createId();
        $this->rows['scrath_order'] = $this->models['scrath_order']->createRow(array(
           'id' => $id,
           'add_time'  =>   time(),
           'member_id'  =>$this->_user->id,
           'scrath_id' =>  $input->id,
           'total_money'    =>  $scrath['draw_amount'],
           'subject' => SITE_NAME . "订单-{$id}",
           'body' =>  SITE_NAME . "订单-{$id}",
           'is_send' => '0'
        ));
        $this->rows['scrath_order']->save();
    
        return  $id;
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
           case 'shareweixin':
               setcookie($this->_user->id,'1',time()+(3600*24*30));
               break;
               
           case 'weixin':
               $order = $this->_db->select()
                   ->from(array('o' => 'scrath_order'))
                   ->where('o.id = ?',$_REQUEST['id'])
                   ->query()
                   ->fetch();
               $wx = new Core2_Wx();
               $data = array(
                   'name' => array(
                       'value' => '恭喜您，获得了一张刮刮卡',
                       'color' => '#173177'
                   ),
                   'remark' => array(
                       'value' => '众游网络感谢您的参与',
                       'color' => '#173177'
                   ),
               );
               $url=DOMAIN . "scrathuc/scrath/scrathcard?order_id=".$_REQUEST['id'];
               $wx->sendMessageTpl($this->_user->openid, 'OG2h3oQLgXq8EBYF8GWTzZpAOD3LDnsgCkgSnVITclY', $url, $data);
               break;
           
           case 'money' :
               //用户金额是否小于抽奖金额
               $scrath = $this->_db->select()
                   ->from(array('a' => 'scrath'))
                   ->where('a.id = ?',$_REQUEST['id'])
                   ->query()
                   ->fetch();
               
               if(!$scrath)
               {
                   $this->json['errno'] = '1';
                   $this->json['errmsg'] = '活动不存在！';
                   $this->_helper->json($this->json);
               }
               if($this->input->money<$scrath['draw_amount'])
               {
                   $this->json['errno'] = '1';
                   $this->json['errmsg'] = '金额小于抽奖金额';
                   $this->_helper->json($this->json);
               }
               else
               {
                   $this->json['errno'] = '0';
                   $this->json['errmsg'] = '';
                   $this->_helper->json($this->json);
               }
               break;
               
       }
       
       $this->_helper->json($json);
   }
   
   private  function _checkpayAction($order_id)
   {
       //查询订单
       $row = $select = $this->_db->select()
       ->from(array('a' => 'scrath_order'))
       ->where('a.id = ?',$order_id)
       ->where('a.member_id = ?',$this->_user->id)
       ->query()
       ->fetch();
       
       if($row['status'] == 1)
       {
           return true;
       }
       require_once "lib/api/wxwebpay/lib/WxPay.Api.php";
       require_once "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
       
       $tools = new JsApiPay();
        
       $input = new WxPayOrderQuery();
       $input->SetOut_trade_no($order_id);
       $code = WxPayApi::orderQuery($input);


       //查看返回值 如果成功修改状态
       if($code['trade_state'] == "SUCCESS" && $code['result_code'] == "SUCCESS")
       {
           $this->rows['scrath_order'] = $this->models['scrath_order']->find($order_id)->current();
           $this->rows['scrath_order']->pay_time = time();
           $this->rows['scrath_order']->status = 1;
           $this->rows['scrath_order']->save();
           return true;
       }
       else
       {
          return false;
       }
   }
   
   
   /**
    * 获取刮刮卡
    */
    public function scrathcardAction()
    {
        if (!params($this))
        {
            /* 提示 */
            
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
                array(
                    'href' => 'javascript:history.go(-1);',
                    'text' => '返回上一页'),
            ));
        }
        //判断订单是否支付
        if($this->paramInput->order_id)
        {

            if(!$this->_checkpayAction($this->paramInput->order_id))
            {
                $this->view->type = "0";
            }
            else 
            {
                $this->view->order_id = $this->paramInput->order_id;
                
                $this->_cardAction($this->paramInput->order_id);
                
                //获取刮刮卡信息
                $scrath_card = array();
                
                $prizecard = $this->_db->select()
                    ->from(array('a' => 'scrath_card'),array(new Zend_Db_Expr('COUNT(*)')))
                    ->where('a.member_id = ?',$this->_user->id)
                    ->where('a.is_prize = ?',1)
                    ->where('a.status = ?',0)
                    ->query()
                    ->fetchColumn();
                
                $scrath = $this->_db->select()
                    ->from(array('o' => 'scrath'))
                    ->joinLeft(array('c' => 'scrath_order'),'c.scrath_id = o.id')
                    ->where('c.id = ?',$this->paramInput->order_id)
                    ->query()
                    ->fetch();
                $this->view->scrath = $scrath; 
                
                $scrath_card['prizecard'] = $prizecard;
                
                //查看用户是否有刮刮卡中奖纪录
                $address = $this->_db->select()
                    ->from(array('a' => 'scrath_prize'))
                    ->where('a.member_id = ?',$this->_user->id)
                    ->where('a.phone != ?',"")
                    ->order('a.id desc')
                    ->limit(1,0)
                    ->query()
                    ->fetch();
                
                $this->view->address = $address;
                $this->view->scrathcard = $scrath_card;
                $this->view->user_id = $this->_user->id;
                
                $this->view->wxTitle = $scrath['scrath_name'];
                $this->view->wxDescription = $scrath['scrath_name'];
                $this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST']."/scrathuc/scrath/index?id=".$this->paramInput->id;
                $this->view->wxImage = $scrath['image'];
            }
        }
    }
    
    
    /**
     * 根据订单生成刮刮卡
     */
    private  function _cardAction($order_id)
    {
        //查询订单 生成几个刮刮卡
        $row = $select = $this->_db->select()
            ->from(array('a' => 'scrath_order'))
            ->joinLeft(array('c' => 'scrath'),'a.scrath_id = c.id',array('draw_amount'))
            ->where('a.id = ?',$order_id)
            ->where('a.is_card = ?',0)
            ->query()
            ->fetch();
        
        $this->view->total_money = $row['total_money'];

        if(empty($row))
        {
            return false;
        }

        $this->_prizeAction($row['scrath_id'],$row['member_id'],$row['total_money'],$order_id);

        //修改订单参数  以获取刮刮卡
        $this->rows['scrath_order'] = $this->models['scrath_order']->find($order_id)->current();
        $this->rows['scrath_order']->is_card = 1;
     //   $this->rows['scrath_order']->status = 1;
        $this->rows['scrath_order']->save();
    }
    
    
    /***
     * 中奖计算
     */
    private function  _prizeAction($scrath_id,$member_id,$total_money,$order_id)
    {
        //查询刮刮卡活动
        $scrath = $this->_db->select()
             ->from(array('a' => 'scrath'))
            ->where('a.id = ?',$scrath_id)
            ->query()
            ->fetch();
        
        //查询刮刮卡商品表
        $scrath_product = $this->_db->select()
            ->from(array('a' => 'scrath_product'))
            ->where('scrath_id = ?', $scrath_id)
            ->where('stock > ?','0' )
            ->where('status = ?',1)
            ->order('a.total_num asc')
            ->query()
            ->fetchAll();

        /*判断商品库存  获取区间*/

        $qujian=array();
        for ($i=0;$i<count($scrath_product);$i++)
        {
            if($i==0)
            {
                $qujian[$i]['total_num'] = $scrath_product[$i]['total_num'];
                $qujian[$i]['scrath_product_id']=$scrath_product[$i]['id'];
            }
            else 
            {
                $qujian[$i]['total_num'] = $scrath_product[$i]['total_num']+$qujian[$i-1]['total_num'];
                $qujian[$i]['scrath_product_id']=$scrath_product[$i]['id'];
            }
        }
     
        /*获取 刮刮卡和中奖 */
        
        $card=array();
        
        //几张刮刮卡
        $sum=intval($total_money/$scrath['draw_amount']);
        
        $card['member_id']=$member_id;
        $card['add_time']=time();
        $card['scrath_id']=$scrath_id;
        $card['scrath_order_id']=$order_id;
        
        //随机一个数
        $rand=rand(1, $scrath['total_num']);
        for ($j=0;$j<count($qujian);$j++)
        {                
            //判断是否中奖
            if(j==0)
            {
                if($rand>=1&&$rand<=$qujian[$j]['total_num'])
                {
                    $card['is_prize']=1;   
                    //添加中奖纪录
                    $prize['product_id']=$qujian[$j]['scrath_product_id'];
                }
                else 
                {
                    $card['is_prize']=0;
                }
            }
            else 
            {
                if($rand>=$qujian[$j-1]['total_num']&&$rand<=$qujian[$j]['total_num'])
                {
                    $card['is_prize']=1;
                  
                    //添加中奖纪录
                    $prize['product_id']=$qujian[$j]['scrath_product_id'];
                }
                else 
                {
                    $card['is_prize']=0;
                }
            }
        }
        
            //添加用户刮刮卡表
            $card['status'] = 1;
            $this->rows['scrath_card'] = $this->models['scrath_card']->createRow($card);
            $cardId=$this->rows['scrath_card']->save();    
            
            if($card['is_prize']==1)
            {
                //如果中奖 减去剩余数量
                $id = $this->_updateproductAction($prize['product_id']);
                if($id)
                {
                    $prize['member_id']=$member_id;
                    $prize['scrath_card_id']=$cardId;
                    $this->rows['scrath_prize'] = $this->models['scrath_prize']->createRow($prize);
                    $this->rows['scrath_prize']->save();
                }
            }
            else
            {
                //添加发红包记录
                $this->envelopAction($order_id);
            }
    }
    
    /**
     * 修改商品剩余数量
     */
    private function _updateproductAction($scrath_product_id)
    {
        //查询订单 生成几个刮刮卡
        $row = $select = $this->_db->select()
            ->from(array('a' => 'scrath_product'))
            ->where('a.id = ?',$scrath_product_id)
            ->query()
            ->fetch();
        
        $this->rows['scrath_product'] = $this->models['scrath_product']->find($scrath_product_id)->current();
        $this->rows['scrath_product']->stock = intval($row['stock']-1);
        $id=$this->rows['scrath_product']->save();
        return $id;
    }
    
    
    /**
     * 添加红包
     */
    public function envelopAction($order_id)
    {
        $order = $select = $this->_db->select()
        ->from(array('a' => 'scrath_order'))
        ->where('a.id = ?',$order_id)
        ->query()
        ->fetch();
    
        /*创建发送红包*/
    
        if($order['total_money']%200===0)
        {
            $send_num = intval($order['total_money']/200);
        }
        else
        {
            $send_num = intval($order['total_money']/200)+1;
        }
         
        //判断是否已经有该用户的
        $scheme = $select = $this->_db->select()
        ->from(array('a' => 'envelope_scheme'))
        ->where('a.member_id = ?',$order['member_id'])
        ->query()
        ->fetch();
    
        if(!$scheme)
        {
            $this->rows['envelope_scheme'] = $this->models['envelope_scheme']->createRow(array(
                'total_money' =>  $order['total_money'],
                'member_id'  =>  $order['member_id'],
            ));
            $this->rows['envelope_scheme']->save();
        }
        else
        {
            $this->rows['envelope_scheme'] = $this->models['envelope_scheme']->find($order['member_id'])->current();
            $this->rows['envelope_scheme']->total_money = $order['total_money']+$scheme['total_money'];
            $this->rows['envelope_scheme']->save();
            $schemeId=$scheme['id'];
        }
         
        //关联表
        $this->rows['envelope_from'] = $this->models['envelope_from']->createRow(array(
            'from' => '3',
            'from_id' =>$order_id,
            'member_id'   =>  $order['member_id'],
            'money' =>  $order['total_money'],
        ));
        $this->rows['envelope_from']->save();
    
        //添加计划表
        for ($i=1;$i<=$send_num;$i++)
        {
                $send_time  = (time()+180);
                if($i==$send_num)
                {
                $send_money = intval($order['total_money']-(200*($i-1)));
                $this->rows['envelope_schedule'] = $this->models['envelope_schedule']->createRow(array(
                    'send_money' =>  $send_money,
                    'member_id'  =>  $order['member_id'],
                    'send_time'  =>  $send_time,
                ));
                }
                else
                {
                        $this->rows['envelope_schedule'] = $this->models['envelope_schedule']->createRow(array(
                        'send_money' =>  '200',
                            'member_id'  =>  $order['member_id'],
                            'send_time'  =>   $send_time,
                    ));
                }
                        $this->rows['envelope_schedule']->save();
            }
    }
    
   
}
