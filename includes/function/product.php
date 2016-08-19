<?php

/**
 *  根据商品分类 ID 获取商品类型信息
 */
function getProductTypeInfoByCateId($cateId)
{
	/* 初始化 */
	
	$db = Zend_Registry::get('db');
	$productTypeModel = new Model_ProductType();
	$attrs = array();
	$params = array();
	$specs = array();
	
	/* 获取商品类型 */
	
	$typeId = $db->select()
		->from(array('c' => 'product_cate'),array('type_id'))
		->where('c.id = ?',$cateId)
		->query()
		->fetchColumn();
	$type = $db->select()
		->from(array('t' => 'product_type'))
		->where('t.id = ?',$typeId)
		->query()
		->fetch();
		
	if (!empty($type)) 
	{
		/* 获取属性、参数 */
	
		list($attrs,$params) = $productTypeModel->decode($type);
		
		/* 获取规格 */
		
		for ($i = 1;$i <= 3;$i ++)
		{
			$specId = $type["spec_{$i}"];
			if ($specId > 0) 
			{
				$tmp = array();
				$spec = $db->select()
					->from(array('s' => 'product_spec'))
					->where('s.id = ?',$specId)
					->query()
					->fetch();
				$specValue = $db->select()
					->from(array('v' => 'product_specval'))
					->where('v.spec_id = ?',$specId)
					->order('v.letter ASC')
					->query()
					->fetchAll();
					
				$tmp['spec_name'] = $spec['spec_name'];
				$tmp['values'] = array();
				foreach ($specValue as $value) 
				{
					$tmp['values'][] = $value['value'];
				}
				$specs[$i] = $tmp;
			}
		}
	}
	
	return array($attrs,$params,$specs);
}

/**
 *  商品编号中获取商家编码
 */
function getSupplierCode($sn)
{
	return $sn;
}

/**
 * 处理价格
 */
function intPrice($price)
{
    if(intval($price) == $price)
    {
        return intval($price);
    }
    else 
    {
        return $price;
    }
}



?>