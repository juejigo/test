<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'post') 
	{
		/* 构造验证器 */
		$filters = array(
			'amount' => 'Float',
		);
		$validators = array(
			'bank_type' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择银行',
				array('InArray',array(1,2,3,4))
			),
			'bod' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入开户行',
			),
			'card_no' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入卡号',
			),
			'owner_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入银行卡所有人',
			),
			'amount' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入提现金额',
				new Amount(),
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
class Amount extends Core_Validate_Abstract 
{
	const NOT_ENOUGH = 'notEnough';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_ENOUGH => '余额不足',
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$balance = $this->_db->select()
			->from(array('m' => 'member'),array('balance'))
			->where('m.id = ?',Zend_Auth::getInstance()->getIdentity()->id)
			->query()
			->fetchColumn();
		if ($balance < $value) 
		{
			$this->_error(self::NOT_ENOUGH);
			return false;
		}
		
		return true;
	}
}

?>