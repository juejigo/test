<?php
/**
 *  检验类
 */
class Id extends Core_Validate_Abstract
{
	const NOT_FOUND = 'notFound';

	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
			self::NOT_FOUND => '用户错误！'
	);

	/**
	 *  检验
	 *
	 *  @param array $values
	 *  @return boolean
	*/
	public function isValid($value)
	{
		$userId=explode(',',$value);
		if(count($userId)==1)
		{
			$count = $this->_db->select()
			->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('m.id in(?)',$userId)
			->where('m.status = ?',1)
			->query()
			->fetchColumn();
				
			if ($count != count($userId))
			{
				$this->_error(self::NOT_FOUND);
				return false;
			}
		}
		return true;
	}
}

/**
 *  检验 ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();

	if ($op == 'jpush')
	{
		/* 构造验证器 */

		$filters = array(
				
		);
		$validators = array(
				'title' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入标题',
				),
				'content' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入内容',
				),
				'target' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择目标人群',
				),
				'user_id'=> array(
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入用户ID',
						new Id(),
				),
				'type' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择类型',
				),
				'product_id' => array(
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入产品ID',
				),
				'order_id' => array(
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入订单ID',
						array('DbRowExists',array(
								'table' => `order`,
								'field' => 'id',
								'where' => array('status in(?)',array('20','0','1','2','3','13')),
						)),
						'messages' => array('错误订单！'),			
				),
				'send_type' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择发送时间',
				),
				'send_type' => array(
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择时间',
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

?>
