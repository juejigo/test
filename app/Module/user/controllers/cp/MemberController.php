<?php

class Usercp_MemberController extends Core2_Controller_Action_Cp   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['member'] = new Model_Member();
		$this->models['member_profile'] = new Model_MemberProfile();
	}
	
	/**
	 *  首页
	 */
	public function indexAction() 
	{
		$this->_redirect('/usercp/member/list');
	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error',array(
				array(
					'href' => '/usercp/member',
					'text' => '会员管理')
			));
		}
		
		/* 构造 SQL 选择器 */
		$perpage = 20;
		$select = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')));
			
		$query = '/usercp/member/list?page={page}';
		
		if (!empty($this->paramInput->id)) 
		{
			$select->where('m.id = ?',$this->paramInput->id);
			$query .= "&id={$this->paramInput->id}";
		}
		
		if (!empty($this->paramInput->account)) 
		{
			$select->where('m.account = ?',$this->paramInput->account);
			$query .= "&account={$this->paramInput->account}";
		}
		
		if (!empty($this->paramInput->referee)) 
		{
			$refereeId = $this->_db->select()
				->from(array('m' => 'member'),array('id'))
				->where('m.account = ?',$this->paramInput->referee)
				->query()
				->fetchColumn();
				
			$select->where('m.referee_id = ?',$refereeId);
			$query .= "&referee={$this->paramInput->referee}";
		}
		
		if ($this->paramInput->role !== '') 
		{
			$select->where('m.role = ?',$this->paramInput->role);
			$query .= "&role={$this->paramInput->role}";
		}
		
		if ($this->paramInput->group !== '') 
		{
			$select->where('m.group = ?',$this->paramInput->group);
			$query .= "&group={$this->paramInput->group}";
		}
		
		if ($this->paramInput->imei !== '') 
		{
			if ($this->paramInput->imei == 0) 
			{
				$select->where('m.imei_id = ?',0);
			}
			else 
			{
				$select->where('m.imei_id > ?',0);
			}
			$query .= "&imei={$this->paramInput->imei}";
		}
		
		if ($this->paramInput->status !== '') 
		{
			$select->where('m.status = ?',$this->paramInput->status);
			$query .= "&status={$this->paramInput->status}";
		}
		
		/* 分页 */
		$count = $select->query()
			->fetchColumn();
		$corepage = new Core_Page($this->paramInput->page,$perpage,$count,$query);
		$this->view->pagebar = $corepage->output();
		$this->view->count = $count;
		
		/* 列表 */
		$memberList = $select->reset(Zend_Db_Select::COLUMNS)
			->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('member_name','alias','avatar'))
			->joinLeft(array('g' => 'member_group'), 'm.group = g.id','group_name')
			->columns('*','m')
			->order('m.register_time DESC')
			->limitPage($corepage->currPage(),$perpage)
			->query()
			->fetchAll();
		foreach ($memberList as $key => $member)
		{
			$r = createR($member['id']);
			$url = DOMAIN . "user/account/register?register_from=23&r={$r}";
			$urlEncode = urlencode($url);
			$memberList[$key]['url'] = DOMAIN . "utility/qrcode?content={$urlEncode}";
			$memberList[$key]['group_name'] = getGroupName($member['role'],$member['group']);
		}
		
		$this->view->memberList = $memberList;
	}
	
	/**
	 *  添加用户
	 */
	public function addAction()
	{
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新用户表 */
				
				$this->rows['member'] = $this->models['member']->createRow();
				$this->rows['member']->account = $this->input->account;
				if ($this->input->password != '') 
				{
					$this->rows['member']->password = $this->input->password;
				}
				$this->rows['member']->role = $this->input->role;
				$this->rows['member']->group = $this->input->group;
				$this->rows['member']->status = $this->input->status;
				$this->rows['member']->deadline = strtotime($this->input->deadline);
				$this->rows['member']->save();
				
				/* 附属表 */
				
				$this->rows['member_profile'] = $this->models['member_profile']->find($this->rows['member']->id)->current();
				$this->rows['member_profile']->member_name = $this->input->member_name;
				$this->rows['member_profile']->alias = $this->input->alias;
				$this->rows['member_profile']->sex = $this->input->sex;
				$this->rows['member_profile']->mobile = $this->input->mobile;
				$this->rows['member_profile']->telephone = $this->input->telephone;
				$this->rows['member_profile']->company = $this->input->company;
				$this->rows['member_profile']->province_id = $this->input->province_id;
				$this->rows['member_profile']->city_id = $this->input->city_id;
				$this->rows['member_profile']->county_id = $this->input->county_id;
				$this->rows['member_profile']->address = $this->input->address;
				$this->rows['member_profile']->memo = $this->input->memo;
				$this->rows['member_profile']->save();
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => '/usercp/member',
						'text' => '会员管理')
				));
			}
		}
	}
	
	/**
	 *  编辑用户
	 */
	public function editAction()
	{
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误','','error',array(
					array(
						'href' => '/usercp/member',
						'text' => '会员管理')
				));
		}
		
		$this->rows['member'] = $this->models['member']->find($this->paramInput->id)->current();
		$this->rows['member_profile'] = $this->models['member_profile']->find($this->paramInput->id)->current();
		
		if ($this->_request->isPost()) 
		{
			if (form($this)) 
			{
				/* 更新用户表 */
				
				$this->rows['member']->referee_id = $this->input->referee_id;
				$this->rows['member']->account = $this->input->account;
				if ($this->input->password != '') 
				{
					$this->rows['member']->password = $this->input->password;
				}
				$this->rows['member']->role = $this->input->role;
				$this->rows['member']->group = $this->input->group;
				$this->rows['member']->status = $this->input->status;
				$this->rows['member']->deadline = strtotime($this->input->deadline);
				$this->rows['member']->save();
				
				/* 附属表 */
				
				$this->rows['member_profile']->member_name = $this->input->member_name;
				$this->rows['member_profile']->alias = $this->input->alias;
				$this->rows['member_profile']->sex = $this->input->sex;
				$this->rows['member_profile']->mobile = $this->input->mobile;
				$this->rows['member_profile']->telephone = $this->input->telephone;
				$this->rows['member_profile']->company = $this->input->company;
				$this->rows['member_profile']->province_id = $this->input->province_id;
				$this->rows['member_profile']->city_id = $this->input->city_id;
				$this->rows['member_profile']->county_id = $this->input->county_id;
				$this->rows['member_profile']->address = $this->input->address;
				$this->rows['member_profile']->memo = $this->input->memo;
				$this->rows['member_profile']->save();
				
				$this->_helper->notice('编辑成功','','success',array(
					array(
						'href' => '/usercp/member',
						'text' => '会员管理')
				));
			}
		}
		
		if ($this->_request->isGet()) 
		{
			$this->data = array_merge($this->rows['member']->toArray(),$this->rows['member_profile']->toArray());
			$this->data['deadline'] = date('Y-m-d',$this->data['deadline']);
		}
	}
	
	/**
	 *  编码
	 */
	public function codeAction()
	{
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 更新用户表 */
		
		$this->rows['member'] = $this->models['member']->find($this->input->id)->current();
		$this->rows['member']->code = $this->input->code;
		$this->rows['member']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  银行卡
	 */
	public function bankcardAction()
	{
		if (!form($this)) 
		{
			$json['errno'] = 1;
			$json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
		
		/* 更新用户表 */
		
		$this->rows['member'] = $this->models['member']->find($this->input->id)->current();
		$this->rows['member']->bank = $this->input->bank;
		$this->rows['member']->bankcard = $this->input->bankcard;
		$this->rows['member']->save();
		
		$json['errno'] = 0;
		$this->_helper->json($json);
	}
	
	/**
	 *  ajax
	 */
	public function  ajaxAction()
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
			/* 更新用户信息*/
				
			case 'update':
				include "lib/api/wxwebpay/lib/WxPay.Api.php";
				include "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
				
				$client = new Zend_Http_Client(
					null,
					array(
						'adapter' => 'Zend_Http_Client_Adapter_Curl',
						'curloptions' => array(
							CURLOPT_SSL_VERIFYPEER => false,
							CURLOPT_SSL_VERIFYHOST => false))
				);
				
				$client->setUri('https://api.weixin.qq.com/cgi-bin/token');
				$client->setParameterGet(array(
					'appid' => $this->_config->wx->appid,
					'secret' => $this->_config->wx->secret,
					'grant_type' => 'client_credential'
				));
				$response = $client->request(Zend_Http_Client::GET);
				$result = Zend_Json::decode($response->getBody());
				
				if (empty($result['access_token']))
				{
					/* 提示 */
					$this->_helper->notice('信息获取失败',$this->error->firstMessage(),'error_1',array(
						array(
							'href' => 'javascript:wx.closeWindow();',
							'text' => '关闭')
					));
				}
				$openid = $this->_db->select()
					->from(array('m' => 'member'),array('openid','id'))
					->where('id = ?',$this->input->id)
					->query()
					->fetch();
				if (empty($openid['openid']))
				{
					$json['errno'] = 1;
					$json['errmsg'] = '没有微信帐号';
					$this->_helper->json($json);
				}
				$client = new Zend_Http_Client(
					null,
					array(
						'adapter' => 'Zend_Http_Client_Adapter_Curl',
						'curloptions' => array(
							CURLOPT_SSL_VERIFYPEER => false,
							CURLOPT_SSL_VERIFYHOST => false))
				);
				
				$client->setUri('https://api.weixin.qq.com/cgi-bin/user/info');
				$client->setParameterGet(array(
					'access_token' => $result['access_token'],
					'openid' => $openid['openid'],
					'lang' => 'zh_CN'
				));
				$response = $client->request(Zend_Http_Client::GET);
				$info = Zend_Json::decode($response->getBody());
				
				if (!empty($info['subscribe']))
				{
					$this->rows['member_profile'] = $this->models['member_profile']->find($this->input->id)->current();
					$this->rows['member_profile']->member_name = $info['nickname'];
					$this->rows['member_profile']->avatar = $info['headimgurl'];
					$this->rows['member_profile']->save();
					$json['errno'] = 0;
					$json['name'] = $info['nickname'];
					$json['image'] = $info['headimgurl'];
					$this->_helper->json($json);
				}
				$json['errno'] = 1;
				$json['errmsg'] = '已取消关注';
				$this->_helper->json($json);
				break;
	
			default:
				break;
		}
	}
}

?>