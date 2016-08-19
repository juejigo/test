<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getUserParams();
	
	if ($action == 'auth') 
	{
		/* 构造检验器 */
		$filters = array();
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'mail',
					'field' => 'id',
					'where' => array('type = ?' => 0,'status = ?' => 1)
				)),
				'breakChainOnFailure' => true
			),
			'hash' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'breakChainOnFailure' => true
			),
			'auth' => array(
				'fields' => array('id','hash'),
				new Auth()
			)
		);
		
		$controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
		
		/* 检验器检验 */
		if (!$controller->paramInput->isValid()) 
		{
			$controller->error->import($controller->paramInput->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		return true;
	}
	
	return false;
}

/**
 *  内部验证类
 */
class Auth extends Core_Validate_Abstract 
{
	const ERROR = 'error';
	const EXPIRE = 'expire';
	const REGISTED = 'registed';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::ERROR => '验证错误',
		self::EXPIRE => '验证已过期',
		self::REGISTED => '已注册'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $value
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['id']) && !empty($values['hash'])) 
		{
			$mail = $this->_db->select()
				->from(array('m' => 'mail'))
				->where('m.id = ?',$values['id'])
				->query()
				->fetch();
			
			/* hash 匹配 */
			
			if (md5($mail['dateline']) != $values['hash']) 
			{
				$this->_error(self::EXPIRE);
				return false;
			}
			
			/* 1小时内有效 */
			
			if (($mail['dateline'] < (SCRIPT_TIME - (60 * 60))) && $mail['status'] == 0) 
			{
				$this->_error(self::EXPIRE);
				return false;
			}
			
			/* 邮箱是否被绑定 */
			
			$count = $this->_db->select()
				->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('m.email = ?',$mail['email'])
				->query()
				->fetchColumn();
			if ($count > 0) 
			{
				$this->_error(self::REGISTED);
				return false;
			}
		}
		
		return true;
	}
}

?>