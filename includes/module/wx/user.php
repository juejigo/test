<?php

/**
 *  参数检验
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'baseinfo') 
	{
		/* 构造验证器 */
		$filters = array(
		);
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
	else if ($action == 'info') 
	{
		/* 构造验证器 */
		$filters = array(
		);
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
	
	return false;
}

 /*
  *  获取城市id
  * */	
	
 
function regionInfo($province,$city)
{
	$db = Zend_Registry::get('db');
	
	$province = $db->select()
		->from(array('r' => 'region'),array('id','parent_id','region_name'))
		->where("r.region_name like '%{$province}%'")
		->where('r.level = ?',1)
		->query()
		->fetch();
	$city = $db->select()
		->from(array('r' => 'region'),array('id','parent_id','region_name'))
		->where("r.region_name like '%{$city}%'")
		->where('r.level = ?',2)
		->query()
		->fetch();
	
	$arr['province_id'] = $province['id'];
	$arr['city_id'] = $city['id'];
	
	return $arr;
}

?>