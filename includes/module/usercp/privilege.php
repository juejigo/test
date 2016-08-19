<?php
/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'authlist') 
	{
		/* 构造验证器 */
		
		$filters = array(

		);
		$validators = array(
				'id' => array(
						array('DbRowExists',array(
								'table' => 'member',
								'field' => 'id',
						)),
						'messages' => array('用户错误'),
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
 *  检验ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();
	
	if ($op == 'add') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
				'userId' => array(
						array('DbRowExists',array(
								'table' => 'member',
								'field' => 'id',
						)),
						'messages' => array('用户错误'),
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
		);
		$validators = array(
				'userId' => array(
						array('DbRowExists',array(
								'table' => 'privilege_user',
								'field' => 'member_id',
						)),
						'messages' => array('用户错误'),
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

/**
 * 返回一维数组带level
 * @param array $mca
 * @param int $start_id 根
 * @param string $id 主键
 * @param string $parent
 * @return array
 */
function treelist($mca,$start_id,$id = 'id',$parent,$level = 0)
{
	global $arr;
	$level ++;
	foreach ($mca as $key => $con)
	{
		if($start_id == $con[$parent])
		{
			$con['level'] = $level;
			$arr[] = $con;
			//递归
			treelist($mca,$con[$id],$id,$parent,$level);
		}
	}
	return $arr;
}


?>