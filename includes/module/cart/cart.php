<?php

/**
 *  表单 检验
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
		);
		$validators = array(
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
			'item_id' => 'Int'
		);
		$validators = array(
			'item_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择产品',
				new ItemId(),
				'breakChainOnFailure' => true
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择产品数量',
				array('GreaterThan','0'),
				'default' => 1,
				'breakChainOnFailure' => true
			),
			'stock' => array(
				'fields' => array('item_id','num'),
				new Stock()
			)
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
	else if ($action == 'select') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择购物车',
				array('DbRowExists',array(
					'table' => 'cart',
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
	else if ($action == 'num') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
			'num' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择购物车',
				array('DbRowExists',array(
					'table' => 'cart',
					'field' => 'id',
					'where' => array('member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id)
				)),
				'breakChainOnFailure' => true
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择数量',
				array('GreaterThan','0'),
				'breakChainOnFailure' => true
			),
			'stock' => array(
				'fields' => array('id','num'),
				new CartStock()
			)
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
	else if ($action == 'delete') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择购物车',
				array('DbRowExists',array(
					'table' => 'cart',
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
class ItemId extends Core_Validate_Abstract 
{
	const NOT_FOUND = 'notFound';
	const LOW_STOCK = 'lowStock';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_FOUND => '商品不存在或已下架',
		self::LOW_STOCK => '库存不足'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$item = $this->_db->select()
			->from(array('i' => 'product_item'))
			->joinLeft(array('p' => 'product'),'p.id = i.product_id',array())
			->where('i.id = ?',$value)
			->where('p.status = ?',2)
			->where('i.status = ?',1)
			->query()
			->fetch();
			
		if (empty($item)) 
		{
			$this->_error(self::NOT_FOUND);
			return false;
		}
		
		if ($item['stock'] <= 0) 
		{
			$this->_error(self::LOW_STOCK);
			return false;
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class Stock extends Core_Validate_Abstract 
{
	const LOW_STOCK = 'lowStock';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::LOW_STOCK => '库存不足'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['item_id']) && !empty($values['num'])) 
		{
			$item = $this->_db->select()
				->from(array('i' => 'product_item'))
				->where('i.id = ?',$values['item_id'])
				->query()
				->fetch();
			
			if ($item['stock'] < $values['num']) 
			{
				$this->_error(self::LOW_STOCK);
				return false;
			}
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class CartStock extends Core_Validate_Abstract 
{
	const LOW_STOCK = 'lowStock';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::LOW_STOCK => '库存不足'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['id']) && !empty($values['num'])) 
		{
			$item = $this->_db->select()
				->from(array('c' => 'cart'))
				->joinLeft(array('i' => 'product_item'),'i.id = c.item_id',array('stock'))
				->where('c.id = ?',$values['id'])
				->query()
				->fetch();

			if ($item['stock'] < $values['num']) 
			{
				$this->_error(self::LOW_STOCK);
				return false;
			}
		}
		
		return true;
	}
}

?>