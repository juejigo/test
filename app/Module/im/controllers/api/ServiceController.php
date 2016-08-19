<?php

class Imapi_ServiceController extends Core2_Controller_Action_Api  
{
	private $_serverAPI = null;
	
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		require_once("lib/api/rongyun/ServerAPI.php");
		$this->_serverAPI = new ServerAPI($this->_config->rongyun->appKey,$this->_config->rongyun->appSecret);
	}
	
	/**
	 *  客服首页
	 */
	public function indexAction()
	{
		/* 客服热线 */
		
		$this->json['telephone'] = '13587630625';
		
		if(in_array(date("w"),array(1,2,3,4,5)) && date("Hi") > 0830 && date("Hi") < 1700)
		{
			$this->json['telephone'] = '4006117121';
		}
		
		/* 企业QQ */
		
		$this->json['qq'] = '2183057380';
		
		/* 热门问题 */

		$result = array();
		$questions = $this->_db->select()
			->from(array('c' => 'news_cate'),'id')
			->joinLeft(array('n' => 'news'), 'c.id = n.cate_id','title')
			->joinLeft(array('d' => 'news_data'), 'n.id = d.news_id','content')
			->where('cate_name like ?','%常见问题%')
			->where('n.status = ?',1)
			->query()
			->fetchAll();
		if (!empty($questions))
		{
			foreach ($questions as $question)
			{
				$question['content'] = strip_tags($question['content']);
				$question['content'] = str_replace(array("\r","&nbsp"), "", $question['content']);
				unset($question['id']);
				$result[] = $question;
			}
		}
		$this->json['questions'] = $result;
		
		$this->json['errno'] = 0;
		$this->_helper->json($this->json);
	}
	
	/**
	 *  获得客服token
	 */
	public function gettokenAction()
	{
		$userId = $this->_user->isLogin() ? $this->_user->id : radomUserId();
		$userName = $this->_user->isLogin() ? radomUserName() : radomUserName();
		$portraitUri = $this->_user->isLogin() ? empty($this->_user->avatar) ? DOMAIN . 'static/style/default/image/avatar.thumb.60.png' :  $this->_user->avatar : DOMAIN . 'static/style/default/image/avatar.thumb.60.png';
		
		$result = $this->_serverAPI->getToken($userId,$userName,$portraitUri);
		$json = Zend_Json::decode($result);
		
		if (!empty($json) && $json['code'] == 200) 
		{
			$this->json['errno'] = '0';
			$this->json['token'] = $json['token'];
		}
		else 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = empty($json['errorMessage']) ? '获取token失败' : $json['errorMessage'];
		}
		$this->_helper->json($this->json);
	}
}

?>