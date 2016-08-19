<?php

class User_AccountController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['app_smscode'] = new Model_AppSmscode();
/* 		$this->models['envelope_schedule'] = new Model_EnvelopeSchedule();
		$this->models['envelope_from'] = new Model_EnvelopeFrom();
		$this->models['envelope_scheme'] = new Model_EnvelopeScheme(); */
		$this->models['member_profile'] =new Model_MemberProfile();
	}
	
	/**
	 * 注册
	 */
	public function registerAction()
	{
	    $this->view->headerTitle = "友趣游 - 注册";
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
	        case 'sendcode':
	           
	            $session = new Zend_Session_Namespace('code');
	            
	            if (isset($session->sended))
	            {
	                $this->json['errno'] = '1';
	                $this->json['errmsg'] = '短信已发送，请稍后再试';
	                $this->_helper->json($this->json);
	            }
	            
	            $sms = new Core_Sms();
	            $result = $sms->sendcode($this->input->account);
	            
	            /* 发送不成功 */
	            if ($result['errno'] == 1)
	            {
	                $this->json['errno'] = '1';
	                $this->json['errmsg'] = '短信发送失败，请稍后再试';
	                $this->_helper->json($this->json);
	            }
	            
	            $session->setExpirationSeconds(60);
	            $session->sended = true;

	            $this->json['errno'] = '0';
	            $this->json['errmsg'] = '短信发送成功';
	            $this->_helper->json($this->json);
	            break;
	            
            case 'forgetsendcode':
	            
                $session = new Zend_Session_Namespace('code');
                 
                if (isset($session->sended))
                {
                    $this->json['errno'] = '1';
                    $this->json['errmsg'] = '短信已发送，请稍后再试';
                    $this->_helper->json($this->json);
                }
                 
                $sms = new Core_Sms();
                $result = $sms->sendcode($this->input->account);
                
                /* 发送不成功 */
                if ($result['errno'] == 1)
                {
                    $this->json['errno'] = '1';
                    $this->json['errmsg'] = '短信发送失败，请稍后再试';
                    $this->_helper->json($this->json);
                }
                
                $session->setExpirationSeconds(60);
                $session->sended = true;

                $this->json['errno'] = '0';
                $this->json['errmsg'] = '短信发送成功';
                $this->_helper->json($this->json);
	                break;
            
	        case 'register':
	        	
	        	$referee = getReferee();
	        	
	        	$referee=!empty($referee) ? $referee['id'] : 0;
	        	
	            /* 插入数据库  */
	            $this->rows['member'] = $this->models['member']->createRow(array(
	                'account' => $this->input->account,
	                'password' => $this->input->password,
	                'referee_id' => $referee,
	                'role' => 'member',
	                'status' => 1
	            ));
	            $this->rows['member']->save();
	            
	            if($this->rows['member']->register_from ==23)
	            {
	                $url = "http://a.app.qq.com/o/simple.jsp?pkgname=com.zzb.zzbang";
	            }
	            else 
	            {
	                $url = "/index/index";
	            }
	            
	            $this->json['errno'] = '0';
	            $this->json['url'] = $url;
	            $this->_helper->json($this->json);
	            break;
            
	        case 'hasaccount':
	            $this->json['errno'] = '0';
	            $this->_helper->json($this->json);
	            break;
	            
	        case 'login':
	            
                    if($this->_authenticate())
                    {
                        $select = $this->_db->select()
                            ->from(array('m' => 'member'),array('id'))
                            ->where('m.account = ?',$this->input->account);
                        
                        $member = $select->query()->fetch();
                        login($member['id']);
                        /* 记住密码 */
                        if ($this->input->remember == 1)
                        {
                            setcookie('account',$this->input->account,SCRIPT_TIME + (60 * 60 * 24 * 7),'/',$this->_config->site->domain);
                            setcookie('password',$this->vars['result_row_object']->password,SCRIPT_TIME + (60 * 60 * 24 * 7),'/',$this->_config->site->domain);
                        }
                        else
                        {
                            setcookie('account','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
                            setcookie('password','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
                        }
                        
                        $this->json['errno'] = '0';
                        $this->_helper->json($this->json);
                    }
                    else 
                    {
                        $this->json['errno'] = '1';
                        $this->json['errmsg'] = '密码错误';
                        $this->_helper->json($this->json);
                    }
                
	            break;
	            
	        case 'forget':
	            /* 更新密码 */

	            $this->rows['member'] = $this->models['member']->fetchRow(
	                	
	                $this->models['member']->select()
	                	
	                ->where('account = ?', $this->input->account)
	            );
	            	
	            $this->rows['member']->password = $this->input->password;
	            	
	            $this->rows['member']->save();
	            	

	                $this->json['errno'] = 0;
	                
	                $this->json['errmsg'] = '密码修改成功';
	                
	                $this->_helper->json($this->json);
	                
	            break;
	    }
    }
    
    /**
     * 登录
     */
    public function loginAction()
    {
        $this->view->headerTitle = "友趣游 - 登录";
    }
    
    /**
     * 找回密码
     */
    public function forgetAction()
    {
        $this->view->headerTitle = "友趣游 - 找回密码";
    }
    
    /**
     *  密码校对
     */
    protected function _authenticate()
    {
    	/* 初始化适配器 */
    	$adapter = new Zend_Auth_Adapter_DbTable($this->_db,'member','account','password',"MD5(CONCAT(MD5(?),salt)) AND status = 1");
    	$adapter->setIdentity($this->input->account);
    	$adapter->setCredential($this->input->password);
    	$result = $this->_auth->authenticate($adapter);
    
    	/* 适配器检验 */
    	if (!$result->isValid())
    	{
    		$this->error->add('account','用户名或密码错误');
    		return false;
    	}
    
    	/* 检验成功 */
    	$this->vars['result_row_object'] = $adapter->getResultRowObject();
    	return true;
    }
    
    /**
     *  注销
     */
    public function logoutAction()
    {
    	/* 清除身份 */
    	Zend_Auth::getInstance()->clearIdentity();
    	
    	/* 清除 cookie */
    	setcookie('account','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
    	setcookie('password','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
    
    	$this->_redirect('/index');
    }
    
    /**
     * 下载页面
     */
    public function downloadAction()
    {
    	$this->_redirect('http://a.app.qq.com/o/simple.jsp?pkgname=com.zzb.zzbang');
    }
	
	/**
	 *  我的二维码
	 */
	public function qrcodeAction()
	{
		if (!params($this))
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array());
		}
		
		if (!empty($this->paramInput->account))
		{
			$r = base64_encode($this->paramInput->account);
			$url = DOMAIN . "user/account/register?r={$r}";
			$urlEncode = urlencode($url);
			$this->view->qrcodeUrl = DOMAIN . "utility/qrcode?content={$urlEncode}";
		}
	}

	/**
	 *  登录
	 */
	public function OLD_loginAction()
	{
		/* 转向 */
		$url = urlencode(DOMAIN . '/wx/user/auth');
		$this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_config->wx->appid}&redirect_uri={$url}&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect");
		//exit;
		
		if($this->_user->id>0){
			$this->_helper->notice('你已登录,请先退出','','error_1',array(
				array(
					'href' => '/user/account/logout',
					'text' => '退出帐号'),
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));
			exit;		
		}

		if ($this->_request->isPost()) 
		{
			if (!form($this) || !$this->_authenticate()) 
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			if ($this->_request->isPost()) 
			{
				if (form($this)) 
				{
					$select = $this->_db->select()
						->from(array('m' => 'member'),array('id'))
						->where('m.account = ?',$this->input->account);
					$member = $select->query()->fetch();
					login($member['id']);
					/* 记住密码 */
					if ($this->input->remember) 
					{
						setcookie('account',$this->data['account'],SCRIPT_TIME + (60 * 60 * 24 * 7),'/',$this->_config->site->domain);
						setcookie('password',$this->vars['result_row_object']->password,SCRIPT_TIME + (60 * 60 * 24 * 7),'/',$this->_config->site->domain);
					}
					else 
					{
						setcookie('account','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
						setcookie('password','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
					}
					$this->json['errno'] = '0';
					$this->json['notice'] = '登录成功';

					$form_url  = $_GET['form_url'] ? urldecode($_GET['form_url']) : DOMAIN.'download';
					$this->json['gourl'] = $form_url;
					$this->json['gourl'] = DOMAIN;
					$this->_helper->json($this->json);	
				}
			}else{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);		
		    }
			/* 登录 */
			$sessionId = applogin($this->vars['result_row_object']->id);
			$this->json['sessid'] = $sessionId;
			
			$this->json['errno'] = '0';
			$this->_helper->json($this->json);
		}
		$this->view->headerTitle = '登录_' . SITE_NAME;
	}
	
	/**
	 *  发送验证码
	 */
	public function sendcodeAction() 
	{
	 	if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$session = new Zend_Session_Namespace('code');
		
		if (isset($session->sended)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = '短信已发送，请稍后再试';
			$this->_helper->json($this->json);
		}
		
		$sms = new Core_Sms();
		$result = $sms->sendcode($this->input->mobile);
		/* 发送不成功 */
		if (!$result) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = '短信发送失败，请稍后再试';
			$this->_helper->json($this->json);
		}
		
		$session->setExpirationSeconds(60);
		$session->sended = true;
		
		$this->json['errno'] = '0';
		$this->json['errmsg'] = '短信发送成功';
		$this->_helper->json($this->json);

	}
	
	/**
	 *  修改密码
	 */
	public function passwordAction()
	{
		if ($this->_request->isPost()) 
		{
			if (!form($this)) 
			{
				$this->json['errno'] = 1;
				$this->json['errmsg'] = $this->error->firstMessage();
				$this->_helper->json($this->json);
			}
			
			$sms = $this->_db->select()
				->from(array('a' => 'app_smscode')) 
				->where('a.code = ?', $this->input->code)
				->where('a.mobile = ?', $this->input->account)
				->query()
				->fetch();  
			if(!$sms){
				$this->json['errno'] = '1';
				$this->json['errmsg'] = '验证码错误';
				$this->_helper->json($this->json); 
			}
					 
	  
			/* 更新密码 */ 
	 
			$this->rows['member'] = $this->models['member']->fetchRow(
			
			$this->models['member']->select()  
			
				 ->where('account = ?', $this->input->account)
			);			
			
			$this->rows['member']->password = $this->input->password; 
			
			$this->rows['member']->save(); 
			
			$this->json['errno'] = 0;
			
			$this->json['notice'] = '密码修改成功';
			
			$this->_helper->json($this->json);
		}
	}

	/**
	 *  找回密码
	 */
	public function findpasswordAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$memberId = $this->_db->select()
			->from(array('m' => 'member'),array('id'))
			->where('m.account = ?',$this->input->mobile)
			->query()
			->fetchColumn();
			
		$this->sendcodeAction(); 
		
		$this->json['sessid'] = $sessionId; 
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	
	//密码
	
	public function findpwdAction()
	{
		
 		if ($this->_request->isPost()) 
		{
			
			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				
				$this->json['errmsg'] = $this->error->firstMessage();
				
				$this->_helper->json($this->json);
			}  		
 
			$sms = $this->_db->select()
				->from(array('a' => 'app_smscode')) 
				->where('a.code = ?', $this->input->sms)
				->where('a.mobile = ?', $this->input->account)
				->query()
				->fetch(); 
  
 			if(!$sms){
 				$this->json['errno'] = '1';
				$this->json['errmsg'] = '验证码错误';
				$this->_helper->json($this->json); 
 			}
			 
		}	 
		
		$this->view->headerTitle = '找回密码_' . SITE_NAME;
	} 

	public function findpwdphAction()
	{ 
	   
		if ($this->_request->isPost()) 
		{		
			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				
				$this->json['errmsg'] = $this->error->firstMessage();
				
				$this->_helper->json($this->json);
			}   
	 
			$memberId = $this->_db->select()
			
				->from(array('m' => 'member'),array('id'))
				
				->where('m.account = ?',$this->input->account)
				
				->query()
				
				->fetch(); 
  
 
	 		$sms = new Core_Sms(); 
			
			$result = $sms->sendcode($this->input->account,true); 
							
			setcookie('mobile',$this->input->account,SCRIPT_TIME + (60 * 60 *10 ),'/',$this->_config->site->domain);  
			
			
			/* 发送不成功 */
			if (!$result) 
			{
				$this->json['errno'] = '1';
				$this->json['errmsg'] = '短信发送失败';
				$this->_helper->json($this->json);
			}
			
 
			$this->json['errno'] = '0';  
			
			$this->_helper->json($this->json);	 
			
			} 
		$this->view->mobile = $_COOKIE['mobile'];
		$this->view->headerTitle = '找回密码_' . SITE_NAME;
	} 


	/**
	 *  注销
	 */
	public function OLD_logoutAction()
	{
		/* 清除身份 */
		Zend_Auth::getInstance()->clearIdentity();
		
		/* 清除 cookie */
		setcookie('account','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
		setcookie('password','',SCRIPT_TIME - 1,'/',$this->_config->site->domain);
		
		$this->_redirect('/');
	}


	/**
	 *  绑定手机
	 */
	public function bindingmbAction()
	{

 		if ($this->_request->isPost()) 
		{
			
			if (!form($this)) 
			{
				$this->json['errno'] = '1';
				
				$this->json['errmsg'] = $this->error->firstMessage();
				
				$this->_helper->json($this->json);
			}  		
 
			$this->rows['member'] = $this->models['member']->find($this->_user->id)->current();
			$this->rows['member']->account = $this->input->account;
			$this->rows['member']->password = $this->input->password;
			$this->rows['member']->save();
			$this->json['errno'] = '0'; 
			
			$this->json['notice'] = '手机绑定成功';
			$this->json['gourl'] = DOMAIN.'user/member';
			
			$this->_helper->json($this->json);
			
		}
		if($this->_user->id<1){
			$this->_helper->notice($this->error->firstMessage(),'','error_1',array(
				array(
					'href' => '/product/product/list',
					'text' => '商品列表'),
				array(
					'href' => 'javascript:history.go(-1);',
					'text' => '返回上一面'),
			));	  
		}		
		$this->view->headerTitle = '绑定手机_' . SITE_NAME;
	}
}

?>