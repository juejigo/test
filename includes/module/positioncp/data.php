<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'list') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'position_id' => 'Int',
			'cate_id' => 'Int',
			'page' => 'Int'
		);
		$validators = array(
			'required' => array(
				'fields' => array('position_id','cate_id'),
				new Required()
			),
			'position_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'position',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('参数错误'),
			),
			'cate_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('参数错误'),
			),
			'page' => array(
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			)
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
	else if ($action == 'add') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'position_id' => 'Int',
			'cate_id' => 'Int',
		);
		$validators = array(
			'required' => array(
				'fields' => array('position_id','cate_id'),
				new Required()
			),
			'position_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'position',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('参数错误'),
			),
			'cate_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('参数错误'),
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
	else if ($action == 'edit') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'position_data',
					'field' => 'id',
					'where' => array('status in (?)' => array(1,0))
				)),
				'messages' => array('推荐数据错误'),
			)
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
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'position_data',
					'field' => 'id',
					'where' => array('status in (?)' => array(1,0))
				)),
				'messages' => array('推荐数据错误'),
			)
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
	$controller->data = $request->getPost();
	
	if ($action == 'add') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'data_type' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择类型',
				array('InArray',array('product','product_list','news','link')),
				'messages' => array('类型错误'),
				'breakChainOnFailure' => true
			),
			'title' => array(
				'presence' => 'required',
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
		
		return extra($controller);
	}
	else if ($action == 'edit') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'data_type' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择类型',
				array('InArray',array('product','product_list','news','link')),
				'messages' => array('类型错误'),
				'breakChainOnFailure' => true
			),
			'title' => array(
				'presence' => 'required',
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
		
		return extra($controller);
	}
	else if ($action == 'order') 
	{
		
		print_r($controller->data);exit;
		
		/* 构造验证器 */
		$filters = array(
		 	'id' => 'int',
 
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

		return order($controller);
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
	
	if ($op == 'areachanged') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'area' => 'Int',
		);
		$validators = array(
			'area' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择区域',
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
	else if ($op == 'up')
	{
		/* 构造验证器 */
	
		$filters = array(
				'id' => 'Int',
		);
		$validators = array(
				'id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => 'id不允许为空',
						array('DbRowExists',array(
								'table' => 'position_data',
								'field' => 'id',
								'where' => array('status = ?' => 0),
						)),
						'messages' => array('id不存在'),
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
	elseif ($op == 'down')
	{
		/* 构造验证器 */
	
		$filters = array(
				'id' => 'Int',
		);
		$validators = array(
				'id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => 'id不允许为空',
						array('DbRowExists',array(
								'table' => 'position_data',
								'field' => 'id',
								'where' => array('status = ?' => 1),
						)),
						'messages' => array('id不存在'),
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
	elseif ($op == 'order')
	{
		/* 构造验证器 */
	
		$filters = array(
			'id' => 'Int',
			'order' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => 'id不允许为空',
				array('DbRowExists',array(
					'table' => 'position_data',
					'field' => 'id',
				)),
				'messages' => array('id不存在'),
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
	elseif ($op == 'orderlist')
	{
		/* 构造验证器 */
	
		$filters = array(
			'id' => 'Int',
			'order' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => 'id不允许为空',
				array('DbRowExists',array(
					'table' => 'position_data',
					'field' => 'id',
				)),
				'messages' => array('id不存在'),
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
	
	return false;
}

/**
 *  验证 params
 */
function extra(&$controller)
{
	$data = $controller->data;
	
	if (empty($data['data_type'])) 
	{
		return false;
	}
	
	$params = array();
	if ($data['data_type'] == 'product') 
	{
		// 构造验证器
			
		$filters = array(
			'product_id' => 'Int'
		);
		$validators = array(
			'product_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入商品ID',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
					'where' => array('status = ?' => 2)
				)),
			),
		);
		$input = new Core_Filter_Input($filters,$validators,$data['params']);
		
		// 验证器检验
		
		if (!$input->isValid()) 
		{
			$controller->error->import($input->getMessages());
			return false;
		}
		
		$params['product_id'] = $input->product_id;
		$params['clock'] = 0;
		$params['brief'] = '';
	}
	else if ($data['data_type'] == 'product_list') 
	{
		// 构造验证器
			
		$filters = array(
			'area' => 'Int',
			'cate_id' => 'Int',
			'tag_id' => 'Int',
		);
		$validators = array(
			'keyword' => array(
				'presence' => 'required',
			),
			'area' => array(
				'presence' => 'required',
				'default' => 0
			),
			'cate_id' => array(
				'presence' => 'required',
			),
			'tourism_type' => array(
				'presence' => 'required',
				'default' => 0,
			),
			'areacate' => array(
				new Areacate(),
			),
			'tag_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_tag',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
			),
			'brand_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_brand',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
			),
		);
		$input = new Core_Filter_Input($filters,$validators,$data['params']);
		
		// 验证器检验
		
		if (!$input->isValid()) 
		{
			$controller->error->import($input->getMessages());
			return false;
		}
		
		$params['keyword'] = $input->keyword;
		$params['area'] = $input->area;
		$params['cate_id'] = $input->cate_id;
		$params['tourism_type'] = $input->tourism_type;
		$params['tag_id'] = $input->tag_id;
		$params['brand_id'] = $input->brand_id;
		$params['tourism_type'] = $input->tourism_type;
	}
	else if ($data['data_type'] == 'news') 
	{
		// 构造验证器
			
		$filters = array(
			'news_id' => 'Int'
		);
		$validators = array(
			'news_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入新闻ID',
				array('DbRowExists',array(
					'table' => 'news',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
			),
		);
		$input = new Core_Filter_Input($filters,$validators,$data['params']);
		
		// 验证器检验
		
		if (!$input->isValid()) 
		{
			$controller->error->import($input->getMessages());
			return false;
		}
		
		$params['news_id'] = $input->news_id;
	}
	else if ($data['data_type'] == 'link') 
	{
		// 构造验证器
			
		$filters = array(
		);
		$validators = array(
			'url' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入链接',
				'Url'
			),
		);
		$input = new Core_Filter_Input($filters,$validators,$data['params']);
		
		// 验证器检验
		
		if (!$input->isValid()) 
		{
			$controller->error->import($input->getMessages());
			return false;
		}
		
		$params['url'] = $input->url;
	}
	$data['params'] = $params;
	
	$controller->data = $data;
	return true;
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
		self::MISSING => '缺少参数'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!isset($values['position_id']) && !isset($values['cate_id'])) 
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
class Areacate extends Core_Validate_Abstract 
{
	const NOT_MATCHED = 'notMatched';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_MATCHED => '区域和分类不匹配'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (isset($values['area']) && !empty($values['cate_id'])) 
		{
			/* 分类与区域匹配 */
			$count = $this->_db->select()
				->from(array('c' => 'product_cate'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('c.id = ?',$values['cate_id'])
				->where('c.area = ?',$values['area'])
				->query()
				->fetchColumn();
			
			if ($count == 0) 
			{
				$this->_error(self::NOT_MATCHED);
				return false;
			}
		}
		
		return true;
	}
}

?>