<?php
/**
 *  检验参数
 */
function params(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->params = $request->getQuery();

    if ($action == 'add')
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
 *  表单检验
 */
function form(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->data = $request->getPost();
    if ($action == 'imgupload')
    {
        /* 构造验证器 */
        $filters = array(
        );
        $validators = array();
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
 *  检验 ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();
	
	if ($op == 'insert') 
	{
		/* 构造验证器 */
		$filters = array(
			'product_id' => 'Int',
		);
		$validators = array(
			'product_id' => array(
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('请选择商品'),
				'default' => '0',
			),
			'image' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
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
	else if ($op == 'setdefault') 
	{
		/* 构造验证器 */
		$filters = array(
			'product_id' => 'Int',
		);
		$validators = array(
			'product_id' => array(
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('请选择商品'),
				'default' => '0',
			),
			'image_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'product_image',
					'field' => 'id',
					'where' => array('status = ?' => 1),
				))
			),
			'setdefault' => array(
				'fields' => array('product_id','image_id'),
				new Setdefault(),
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
	else if ($op == 'setorder') 
	{
		/* 构造验证器 */
		$filters = array(
			'image_id' => 'Int',
			'order' => 'Int'
		);
		$validators = array(
			'image_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'product_image',
					'field' => 'id',
					'where' => array('status = ?' => 1),
				))
			),
			'order' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
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
	else if ($op == 'delete') 
	{
		/* 构造验证器 */
		$filters = array(
			'image_id' => 'Int',
		);
		$validators = array(
			'image_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'product_image',
					'field' => 'id',
					'where' => array('status = ?' => 1),
				))
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
class Setdefault extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_VALID => '默认图片不合法'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['product_id']) && !empty($values['image_id'])) 
		{
			$count = $this->_db->select()
				->from(array('i' => 'product_image'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('i.id = ?',$values['image_id'])
				->where('i.product_id = ?',$values['product_id'])
				->query()
				->fetchColumn();
			
			if ($count == 0) 
			{
				$this->_error(self::NOT_VALID);
				return false;
			}
		}
		
		return true;
	}
}

?>