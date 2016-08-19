<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();

	if ($action == 'shippingtpl')
	{
		/* 构造验证器 */

		$filters = array(
				'province_id' => 'Int',
				'fee' => 'Float',
		);
		$validators = array(
	            'province_id' => array(
		                'presence' => 'required',
		                'allowEmpty' => false,
		                'notEmptyMessage' => '省份不允许为空',
		                array('DbRowExists',array(
		                    'table' => 'region',
		                    'field' => 'id',
		                    'where' => array('level = ?' => 1),
		                )),
		                'messages' => array('省份不存在'),
	            ),
				'fee' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入运费',
						array('GreaterThan','0'),
						'messages' => array('请输入运费'),
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
	elseif ($action == 'config')
	{
		/* 构造验证器 */

		$filters = array(
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
	return false;
}

/**
 * 返回树状数组
 * @param array $config
 * @param int $start_id 根
 * @param string $id 主键
 * @param string $parent
 * @return array
 */
function tree($config,$start_id,$id = 'id',$parent,$name = 'field',$value = 'value')
{
	$arr = array();
	foreach ($config as $con)
	{
		if($start_id == $con[$parent])
		{
			if($con[$value] == '')
			{
				$arr[$con[$name]] = array();
				//递归
				$arr[$con[$name]] = $this->tree($config,$con[$id],$id,$name,$value,$parent);
			}
			else
			{
				$arr[$con[$name]] = $con[$value];
			}
		}
	}
	return $arr;
}

/**
 * 返回一维数组带level
 * @param array $config
 * @param int $start_id 根
 * @param string $id 主键
 * @param string $parent
 * @return array
 */
function treelist($config,$start_id,$id = 'id',$parent,$level = 0)
{
	
	global $arr;
	$level ++;
	foreach ($config as $key => $con)
	{
		if($start_id == $con[$parent])
		{
			$con['level'] = $level;
			$arr[] = $con;
			//递归
			treelist($config,$con[$id],$id,$parent,$level);
		}
	}
	return $arr;
}
?>