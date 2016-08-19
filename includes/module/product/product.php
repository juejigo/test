<?php
require_once('includes/function/product.php');
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
		self::NOT_FOUND => '产品不存在'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$count = $this->_db->select()
			->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('p.id = ?',$value)
			->where('p.status = ?',2)
			->query()
			->fetchColumn();
			
		if ($count == 0) 
		{
			$this->_error(self::NOT_FOUND);
			return false;
		}
		return true;
	}
}

/**
 *  检验类
 */
class CateId extends Core_Validate_Abstract 
{
	const NOT_FOUND = 'notFound';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_FOUND => '分类不存在'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		// 允许未分类
		if ($value == 0) 
		{
			return true;
		}
		$value = explode(',',$value);
		$count = $this->_db->select()
			->from(array('c' => 'product_cate'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('c.id in (?)',$value)
			->where('c.status = ?',1)
			->query()
			->fetchColumn();
			
		if ($count == 0) 
		{
			$this->_error(self::NOT_FOUND);
			return false;
		}
		return true;
	}
}

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getQuery();
	if ($action == 'list') 
	{
		/* 构造验证器 */
		$filters = array(
			'num' => 'Int',
			'offset' => 'Int',
			//'cate_id' => 'Int'
		);
		$validators = array(
			'cate_id' => array(
				new CateId()),
			'orderby' => array(
				array('InArray',array('dateline')),
				'default' => ''),
			'page' => array(
				array('GreaterThan','0'),
				'messages' => array('page必须大于0'),
				'default' => '1'),
			'perpage' => array(
				array('GreaterThan','0'),
				'messages' => array('perpage必须大于0'),
				'default' => '30'),
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
	else if ($action == 'detail') 
	{

		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择商品',
				new Id())
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
	else if ($action == 'graphicdetails') 
	{

		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择商品',
				new Id())
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

function params(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->params = $request->getQuery();
    if ($action == 'travel')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
        );
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
    else if ($action == 'features')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
        );
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
   else if ($action == 'travelrestrictions')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
        );
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
   else if ($action == 'costneed')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
        );
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
    else if ($action == 'list')
    {
        /* 构造验证器 */
    
        $filters = array();
        $validators = array(

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
    else if ($action == 'detail')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
        );
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请选择商品',
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
    else if ($action == 'search')
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

//获取位置数据
function getPositionData($positions)
{
    $db = Zend_Registry::get('db');

    foreach($positions as $position)
    {
        $id = $position['id'];
        $select = $db->select();
        $positionsData = $select
        ->from(array('d' => 'position_data'))
        ->where('d.position_id = ?',$position['id'])
        ->where('d.status = ?',1)
        ->order(array('order asc','id asc'))
        ->limit($position['limit'])
        ->query()
        ->fetchAll();
        foreach ($positionsData as $key=>$positionData)
        {
            $params = Zend_Serializer::unserialize($positionData['params']);
            	
            if($positionData['data_type'] == 'product')
            {
                $positionsData[$key]['url'] = DOMAIN.'product/product/detail/?id='.$params['product_id'];
            }
            else if($positionData['data_type'] == 'product_list')
            {
                $param_str = http_build_query($params);
                $positionsData[$key]['url'] = DOMAIN.'product/product/list?'.$param_str;
            }
            else if($positionData['data_type'] == 'news')
            {
                $positionsData[$key]['url'] = DOMAIN.'news/news/detail/?id='.$params['news_id'];
            }
            else if ($positionData['data_type'] == 'link')
            {
                $positionsData[$key]['url'] = $params['url'];
            }
        }
        $data[$id] = $positionsData;
    }

    return $data;
}

/**
 *  检验 ajax
 */
function ajax(&$controller)
{
    $request = $controller->getRequest();
    $op = $request->getQuery('op','');
    $controller->data = $request->getPost();

    if ($op == 'productlist')
    {
        /* 构造验证器 */
    
        $filters = array();
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
   else  if ($op == 'product_items')
    {
        /* 构造验证器 */

        $filters = array(
            'id' => 'Int',
        );
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请选择商品',
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
   else if ($op == 'product_ticket')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
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
    else if ($op == 'product_list')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
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

?>