<?php

class Core2_User
{
	/**
	 *  @var array
	 */
	protected $_fields = array();
	
	/**
	 *  构造函数
	 */
	public function __construct($id = 0)
	{
		if ($id > 0) 
		{
			$db = Zend_Registry::get('db');
			
			/* 会员信息 */
			
			$userInfo = $db->select()
				->from(array('m' => 'member'))
				->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('member_name','avatar'))
				->where('m.id = ?',$id)
				->query()
				->fetch();
				
			/* 群组信息 */
			
			$userInfo['group_name'] = getGroupName($userInfo['role'],$userInfo['group']);
			
			/* 所在门店信息 */
			
			$refereeId = 0;
			$refereeInfo = array();
			
			// cookie 优先
			if(Core_Cookie::exists('seller'))
			{
				$base64 = Core_Cookie::get('seller');
				$account = base64_decode($base64,true);
				
				// 销售员是否存在
				$seller = $db->select()
					->from(array('m' => 'member'),array('id'))
					->where("m.`account` = '{$account}' or m.`openid` = '{$account}'")
					->where('m.status = ?',1)
					->query()
					->fetch();
				
				if ($seller['role'] == 'seller') 
				{
					$refereeId = $seller['id'];
				}
				else 
				{
					$refereeId = $seller['owner_id'];
				}
			}
			else if ($userInfo['referee_id'] > 0)
			{
				$refereeId = $userInfo['referee_id'];
			}
			
			if ($refereeId > 0) 
			{
				$refereeInfo = $db->select()
					->from(array('m' => 'member'),array('referee_id' => 'id'))
					->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id',array('referee_name' => 'member_name','referee_avatar' => 'avatar','seller_name'))
					->where('m.id = ?',$refereeId)
					->query()
					->fetch();
			}
			
			$fields = array_merge($userInfo,$refereeInfo);
			
			$this->_fields = $fields;
		}
	}
	
	/**
	 *  是否登录
	 */
	public function isLogin()
	{
		return !empty($this->_fields['id']) ? true : false;
	}
	
	/**
	 *  刷新数据
	 */
	public function refresh()
	{
		if (!$this->isLogin()) 
		{
			return;
		}
		
		
		$db = Zend_Registry::get('db');
		$fields = $db->select()
			->from(array('m' => 'member'))
			->where('m.id = ?',$this->_fields['id'])
			->query()
			->fetch(Zend_Db::FETCH_ASSOC);
		$this->_fields = $fields;
	}
	
	/**
	 *  魔法方法
	 * 
	 *  @param string $key 消息键值
	 *  @return string
	 */
	public function __get($key)
	{
		if ($key == 'id') 
		{
			return $this->isLogin() ? $this->_fields[$key] : 0;
		}
		
		return $this->_fields[$key];
	}
}

?>