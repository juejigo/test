<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'verify') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'sn' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入消费码',
				array('DbRowExists',array(
					'table' => 'order_item',
					'field' => 'sn',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('消费码错误'),
			),
		);
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
	
	if ($action == 'verify') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'sn' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入消费码',
				array('DbRowExists',array(
					'table' => 'order_item',
					'field' => 'sn',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('消费码错误'),
			),
		)
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
	else if ($action == 'use') 
	{
		/* 构造验证器 */
		$filters = array(
			'num' => 'Int'
		);
		$validators = array(
			'sn' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入消费码',
				array('DbRowExists',array(
					'table' => 'order_item',
					'field' => 'sn',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('消费码错误'),
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入消费数量',
				array('GreaterThan','0'),
				'messages' => array('消费数量错误'),
				'default' => '1'
			),
			'available' => array(
				'fields' => array('sn','num'),
				new Available()
			)
		)
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
class Available extends Core_Validate_Abstract 
{
	const NOT_AVAILABLE = 'notAvailable';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_AVAILABLE => '消费数额'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['sn']) && !empty($values['num'])) 
		{
			$item = $this->_db->select()
				->from(array('i' => 'order_item'))
				->where('i.sn = ?',$values['sn'])
				->query()
				->fetch();
			
			if ($item['available_num'] < $values['num']) 
			{
				$this->_error(self::NOT_AVAILABLE);
				return false;
			}
		}
		
		return true;
	}
}

?>