<?php

require_once('includes/function/order.php');

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'pay') 
	{
		/* 构造验证器 */
		$filters = array(
			'password' => 'Rsadecode'
		);
		$validators = array(
			'order_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_PAY)
				)),
				'messages' => array('订单状态错误'),
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				new Password()
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
 *  内部验证类
 */
class Password extends Core_Validate_Abstract  
{
	const INVALID = 'invalid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID => '密码错误'
	);
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		/* 获取原密码 */
		$user = Zend_Auth::getInstance()->getIdentity();
		$model = new Model_Member();
		$r = $this->_db->select()
			->from(array('m' => 'member'),array('password','salt'))
			->where('m.id = ?',$user->id)
			->query()
			->fetch();
		
		/* 加密验证 */
		if ($model->encodePassword($value,$r['salt']) != $r['password']) 
		{
			$this->_error(self::INVALID);
			return false;
		}
		
		return true;
	}
}

?>