<?php
class Scrathuc_CardController extends Core2_Controller_Action_Uc
{
    public function init()
    {
        parent::init();

        /* 微信分享 */

        require_once "lib/api/wxwebpay/jssdk.php";
        $jssdk = new JSSDK($this->_config->wx->appid,$this->_config->wx->secret,'');
        $signPackage = $jssdk->GetSignPackage();
        $this->view->wxSign = $signPackage;
        
        $this->models['scrath_card'] = new Model_ScrathCard();
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
        $this->_forward('list');
    }
    
    /**
     * 刮刮卡列表
     */
    public function listAction()
    {
        if (!params($this))
        {
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
                array(
                    'href' => 'javascript:history.go(-1);',
                    'text' => '返回上一页'),
            ));
        }
        
        $this->json['card_list'] = array();
        $scrath = $this->_db->select()
            ->from(array('o' => 'scrath_prize'),array('id as prize_id','redeem_code','scrath_card_id','member_id','is_deliver'))
            ->joinLeft(array('c' => 'scrath_product'),'c.id = o.product_id',array('product_name','image'))
            ->joinLeft(array('b' => 'scrath_card'),'o.scrath_card_id = b.id')
            ->joinLeft(array('e' => 'scrath'), 'b.scrath_id = e.id')
            ->where('b.status = 0')
            ->where('o.member_id = ?',$this->_user->id)
            ->order('o.id desc')
            ->query()
            ->fetchAll();
       
        for ($i=0;$i<count($scrath);$i++)
        {
            $scrath[$i]['add_time'] = date("Y-m-d h:i:s",$scrath[$i]['add_time']);
        }
        
        $this->view->productList = $scrath;
        $this->view->scrath_id = $this->paramInput->id;
        
        $this->view->wxTitle = $scrath['scrath_name'];
        $this->view->wxDescription = $scrath['scrath_name'];
        $this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST']."/scrathuc/scrath/index?id=".$scrath['scrath_id'];
        $this->view->wxImage = $scrath['image'];
        
    }
    
    /**
     * 使用刮刮卡
     */
    public function useAction()
    {
        if (!params($this))
        {
            $this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
                array(
                    'href' => 'javascript:history.go(-1);',
                    'text' => '返回上一页'),
            ));
        }
        
        $scrath = $this->_db->select()
            ->from(array('n' => 'scrath_card'))
            ->joinLeft(array('e' => 'scrath'), 'n.scrath_id = e.id')
            ->where('n.id = ?',$this->paramInput->id)
            ->where('n.status = ?',1)
            ->query()
            ->fetch();
        
        $this->view->product = $scrath;
        
        $this->view->wxTitle = $scrath['scrath_name'];
        $this->view->wxDescription = $scrath['scrath_name'];
        $this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST']."/scrathuc/scrath/index?id=".$this->paramInput->id;
        $this->view->wxImage = $scrath['image'];
        
    }
    
    public function  ajaxAction()
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
            $json['errno'] = '0';
            $json['errmsg'] = $this->error->firstMessage();
            $this->_helper->json($json);
        }
         
        switch ($op)
        {
            case 'weixin':     
                if($this->input->id != "")
                {
                      $scrath = $this->_db->select()
                        ->from(array('a' => 'scrath_prize'))
                        ->joinLeft(array('b' => 'scrath_product'), 'b.id = a.product_id')
                        ->joinLeft(array('c' => 'scrath'), 'c.id = b.scrath_id',array('scrath_name'))
                        ->where('a.id = ?',$this->input->id)
                        ->query()
                        ->fetch();
                        $wx = new Core2_Wx();
                        $data = array(
                            'first' => array(
                                'value' => '恭喜您，中奖了。',
                                'color' => '#173177'
                            ),
                            'keyword1' => array(
                                'value' => $scrath['scrath_name'],
                                'color' => '#173177'
                            ),
                            'keyword2' => array(
                                'value' => $scrath['product_name'].',兑换码：'.$scrath['redeem_code'],
                                'color' => '#173177'
                            ),
                            'remark' => array(
                                'value' => '众游网络感谢您的参与',
                                'color' => '#173177'
                            ),
                        );
                        $url=DOMAIN ."scrathuc/card/prizeinfo?id=".$this->input->id;
                       $wx->sendMessageTpl($this->_user->openid, 'sv4lW99tHO_j3gT73NvVHBMNQ_ynLsVHfrGb1Mdsr0s', $url, $data);
                }   
             
                break;
                
            case 'verification':
                //根据兑换码查询
                $scrath_prize = $this->_db->select()
                ->from(array('o' => 'scrath_prize'))
                ->where('o.redeem_code = ?',$this->input->redeem_code)
                ->query()
                ->fetch();
                
                if($scrath_prize == "")
                {
                    $this->json['errno'] = '1';
                    $this->json['errmsg'] = '该兑换码无效';
                    $this->_helper->json($this->json);
                }
               else if($scrath_prize['is_deliver'] == '1')
                {
                    $this->json['errno'] = '1';
                    $this->json['errmsg'] = '该兑换码已使用';
                    $this->_helper->json($this->json);
                }
                else
                {
                    //使用兑换码
                    $this->rows['scrath_prize'] = $this->models['scrath_prize']->find($scrath_prize['id'])->current();
                    $this->rows['scrath_prize']->is_deliver = 1;
                    $this->rows['scrath_prize']->use_time = time();
                    $this->rows['scrath_prize']->save();
                    
                    $this->json['errno'] = '0';
                    $this->json['errmsg'] = '使用成功！';
                    $this->_helper->json($this->json);
                }
                
                break;
                
            case 'usecard':
                
                //用户刮刮卡信息
                $card = $this->_db->select()
                    ->from(array('n' => 'scrath_card'))
                    ->where('n.member_id = ?',$this->_user->id)
                    ->where('n.status = ?',1)
                    ->order('n.id desc')
                    ->query()
                    ->fetch();

                if(!$card)
                {
                    $this->json['errno'] = '0';
                    $this->json['errmsg'] = '参数错误';
                    $this->_helper->json($this->json);
                }
                
                if($card['is_prize']=="1")
                {
                    $productId =$this->_db->select()
                        ->from(array('n' => 'scrath_prize'))
                        ->where('n.scrath_card_id = ?',$card['id'])
                        ->query()
                        ->fetch();
                    
                    $productInfo=$this->_db->select()
                        ->from(array('n' => 'scrath_product'))
                        ->where('n.id = ?',$productId['product_id'])
                        ->query()
                        ->fetch();
                    $product['product_level']="恭喜您，获得了".$productInfo['product_name'];
                }
                else 
                {
                    $product['product_level']="很遗憾没有中奖！再接再厉！";
                }
                
                /* 刮刮卡表 */  
                
                $this->rows['scrath_card'] = $this->models['scrath_card']->find($card['id'])->current();
                $this->rows['scrath_card']->id = $card['id'];
                $this->rows['scrath_card']->status = 0;
                $this->rows['scrath_card']->use_time = time();
                $this->rows['scrath_card']->save();
                
                $prizecard = $this->_db->select()
                    ->from(array('a' => 'scrath_card'),array(new Zend_Db_Expr('COUNT(*)')))
                    ->where('a.member_id = ?',$this->_user->id)
                    ->where('a.is_prize = ?',1)
                    ->where('a.status = ?',0)
                    ->query()
                    ->fetchColumn();
                
                $usecard = $this->_db->select()
                    ->from(array('a' => 'scrath_card'),array(new Zend_Db_Expr('COUNT(*)')))
                    ->where('a.member_id = ?',$this->_user->id)
                    ->where('a.status = ?',1)
                    ->query()
                    ->fetchColumn();

                if($card['is_prize']==1)
                {
                    //生成兑换码
                    $id = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
                    
                    $redeem_code = $id;

                    //添加兑换码
                    $this->rows['scrath_prize'] = $this->models['scrath_prize']->find($productId['id'])->current();
                    $this->rows['scrath_prize']->redeem_code = $redeem_code;
                    $row=$this->rows['scrath_prize']->save();


                    $this->json['errno'] = '1';
                    $this->json['winstart']='1';
                    $this->json['prize']=$product['product_level'];
                    $this->json['usecard'] = $usecard;
                    $this->json['prizecard'] = $prizecard;
                    $this->json['scrath_card_id'] =$card['id'];
                    $this->json['scrath_prize_id'] = $productId['id'];
                    $this->_helper->json($this->json);
                }
                else
                {

                    $this->json['errno'] = '1';
                    $this->json['winstart']='0';
                    $this->json['usecard'] = $usecard;
                    $this->json['prize']=$product['product_level'];
                    $this->json['prizecard'] = $prizecard;
                    $this->_helper->json($this->json);
                }
                break;
                
            case 'address':
                
                if($this->input->scrath_prize_id!="")
                {
                    $this->rows['scrath_prize'] = $this->models['scrath_prize']->find($this->input->scrath_prize_id)->current();
                    $this->rows['scrath_prize']->phone =  $this->input->phone;
                    $this->rows['scrath_prize']->add_time = time();
                
                    $row=$this->rows['scrath_prize']->save();
                }
                else
                {
                    $prize =$this->_db->select()
                        ->from(array('n' => 'scrath_prize'))
                        ->where('n.scrath_card_id = ?',$this->input->scrath_card_id)
                        ->where('n.member_id = ?',$this->_user->id)
                        ->query()
                        ->fetch();
                    
                    $this->rows['scrath_prize'] = $this->models['scrath_prize']->find($prize['id'])->current();
                    $this->rows['scrath_prize']->phone =  $this->input->phone;
                    $this->rows['scrath_prize']->scrath_card_id = $this->input->scrath_card_id;
                    $this->rows['scrath_prize']->member_id = $this->input->member_id;
                    $this->rows['scrath_prize']->add_time = time();
                
                    $row=$this->rows['scrath_prize']->save();
                }
                
                
                if($row)
                {
                    $this->json['errno'] = '1';
                    $this->json['errmsg'] = '操作成功';
                    $this->json['scrath_prize_id'] = $row;
                    $this->_helper->json($this->json);
                }
                else
                {
                    $this->json['errno'] = '0';
                    $this->json['errmsg'] = '操作失败';
                    $this->_helper->json($this->json);
                }
                break;
        }
    }
    
    
    
    /**
     * 兑换
     */
    public function verificationAction()
    {
        //查询使用过的兑换码
        $select = $this->_db->select()
        ->from(array('o' => 'scrath_prize'))
         ->joinLeft(array('c' => 'member_profile'),'c.member_id = o.member_id',array('member_name','avatar'))
        ->where('o.is_deliver = 1')
        ->order('o.use_time desc')
        ->query()
        ->fetchAll();

        $this->view->select = $select;
    }
    
    /**
     * 通知详情
     */
    public function prizeinfoAction()
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
        
        //查询数据
        $scrath = $this->_db->select()
            ->from(array('o' => 'scrath_prize'),array('redeem_code','is_deliver','scrath_card_id'))
            ->joinLeft(array('c' => 'scrath_card'),'c.id = o.scrath_card_id',array('scrath_id'))
            ->joinLeft(array('b' => 'scrath'),'b.id = c.scrath_id',array('content','image','info','scrath_name'))
            ->joinLeft(array('d' => 'scrath_product'), 'o.product_id = d.id',array('product_name'))
            ->where('o.id = ?',$this->paramInput->id)
            ->query()
            ->fetch();
        
        $this->view->scrath = $scrath;
        
        $this->view->wxTitle = $scrath['scrath_name'];
        $this->view->wxDescription = $scrath['scrath_name'];
        $this->view->wxUrl = 'http://'.$_SERVER['HTTP_HOST']."/scrathuc/scrath/index?id=".$this->paramInput->id;
        $this->view->wxImage = $scrath['image'];
        
    }
    
}