<?php
function params(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->params = $request->getQuery();
    if ($action == 'list')
    {

        /* 构造验证器 */

        $filters = array();
        $validators = array();
        $controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);

        /* 验证器检验 */

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
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'list') 
	{
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int',
			'perpage' => 'Int',
			'type' => 'Int'
		);
		$validators = array(
			'page' => array(
				array('GreaterThan','0'),
				'messages' => array('page必须大于0'),
				'default' => '1'
			),
			'perpage' => array(
				array('GreaterThan','0'),
				'messages' => array('perpage必须大于0'),
				'default' => 20
			),
			'type' => array(
				array('InArray',array(0)),
				'messages' => array('类型错误'),
				'default' => 0
			),
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 验证器检验 */
		if (!$controller->input->isValid()) 
		{
			$controller->error->import($controller->input->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		return true;
	}
	else if ($action == 'add') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'dataid' => 'Int',
		);
		$validators = array(
			'dataid' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'breakChainOnFailure' => true
			),
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 验证器检验 */
		
		if (!$controller->input->isValid()) 
		{
			$controller->error->import($controller->input->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		return true;
	}
	else if ($action == 'cancle') 
	{
		/* 构造验证器 */
		 
		$filters = array(
 			'id' => 'Int',
		);
		$validators = array(

			'id' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '您收藏的宝贝不存在！',
				array('DbRowExists',array(
					'table' => 'favorite',
					'field' => 'id',
					'where' => array('member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id)
				)),
			),
 
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 验证器检验 */
		
		if (!$controller->input->isValid()) 
		{
			$controller->error->import($controller->input->getMessages());
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
 *  检验类
 */
class DataType extends Core_Validate_Abstract 
{
	const NO_EXISTS = 'noExists';
	const EXISTS = 'exists';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NO_EXISTS => '无法收藏',
		self::EXISTS => '已收藏'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (isset($values['type']) && !empty($values['dataid'])) 
		{
			/* 是否已收藏 */
				
			$exists = $this->_db->select()
				->from(array('f' => 'favorite'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('f.member_id = ?',Zend_Auth::getInstance()->getIdentity()->id)
				->where('f.type = ?',$values['type'])
				->where('f.dataid = ?',$values['dataid'])
				->query()
				->fetchColumn();
			
			if ($exists > 0) 
			{
				$this->_error(self::EXISTS);
				return false;
			}
			
			if ($values['type'] == 0) 
			{
				/* 对象是否存在 */
			
				$count = $this->_db->select()
					->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
					->where('p.id = ?',$values['dataid'])
					->query()
					->fetchColumn();
			}
			
			if ($count == 0) 
			{
				$this->_error(self::NO_EXISTS);
				return false;
			}
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class Required extends Core_Validate_Abstract 
{
	const MISSING = 'missing';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::MISSING => '参数错误',
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!isset($values['favorite_id']) && !isset($values['type']) && !isset($values['dataid'])) 
		{
			$this->_error(self::MISSING);
			return false;
		}
		
		
		
		return true;
	}
}

/**
 *  检验类
 */
class DataId extends Core_Validate_Abstract 
{
	const NO_EXISTS = 'noExists';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NO_EXISTS => '收藏不存在',
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (isset($values['type']) && isset($values['dataid'])) 
		{
			$exists = $this->_db->select()
				->from(array('f' => 'favorite'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('f.member_id = ?',Zend_Auth::getInstance()->getIdentity()->id)
				->where('f.type = ?',$values['type'])
				->where('f.dataid = ?',$values['dataid'])
				->query()
				->fetchColumn();
			
			if ($exists == 0) 
			{
				$this->_error(self::NO_EXISTS);
				return false;
			}
		}
		
		return true;
	}
}

?>