<?php

class Model_Row_Member extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 密码加密 */
		
		$this->_password();
		
		/* 注册IP */
		
		$this->__set('register_ip',ip());
		
		/* 时间戳 */
		
		$this->__set('register_time',SCRIPT_TIME);
		
		/* 客服ID*/
		
		$staffId = $this->_table->getAdapter()->select()
			->from(array('s' => 'service_staff'),'id')
			->where('s.status = ?',1)
			->order('RAND()')
			->limit(1)
			->query()
			->fetchColumn();
		if (empty($staffId))
		{
			$staffId = 0;
		}
		$this->__set('service_staff_id', $staffId);

		/* 注册来源*/
		
		if (empty($this->_data['register_from']))
		{
			$registerFrom = Core_Cookie::get('register_from');
			if($registerFrom == '')
			{
				if(isMobile())
				{
					$this->__set('register_from','20');
				}
				else
				{
					$this->__set('register_from','30');
				}
			}
			else
			{
				$this->__set('register_from',$registerFrom);
			}
		}
	}
	
	/**
	 *  数据插入后处理
	 * 
	 *  @return void
	 */
	protected function _postInsert()
	{
		/* 用户资料表 */
		
		$memberProfileModel = new Model_MemberProfile();
		$memberProfileModel->createRow(array(
			'member_id' => $this->_data['id'])
		)->save();
		
		/* 有推荐人 */
		
		if ($this->_data['referee_id'] > 0) 
		{
			// 增加推荐人直推人数
			$this->_table->update(array('referee_count' => new Zend_Db_Expr('referee_count + 1')),array('id = ?' => $this->_data['referee_id']));
		}
	}
	
	/**
	 *  数据更新前处理
	 * 
	 *  @return void
	 */
	protected function _update()
	{
		/* 密码加密 */
		
		if(array_key_exists('password',$this->_modifiedFields))
		{
			$this->_password();
		}
	}
	
	/**
	 *  密码加密
	 * 
	 *  @return void
	 */
	protected function _password()
	{
		/* 生成 salt */
		
		/*$chars = array('0','1','2','3','4','5','6','7','8','9','a','A','b','B','c','C','d','D','e','E','f','F','g','G','h','H','i','I','j','J','k','K','l','L','m','M','n','N','o','O','p','P','q','Q','r','R','s','S','t','T','u','U','v','V','w','W','x','X','y','Y','z','Z');
		shuffle($chars);
		$salt = substr(implode('',$chars),0,4);
		$this->__set('salt',$salt);*/
		
		/* 加密 */
		
		$password = $this->_data['password'];
		$passwordEncode = $this->_table->encodePassword($password,$this->_data['salt']);
		$this->__set('password',$passwordEncode);
	}
}

?>