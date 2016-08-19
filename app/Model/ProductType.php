<?php

class Model_ProductType extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_type';
	
	/**
	 *  格式化存数据库
	 */
	public function encode($data)
	{
		$attrs = array();
		if (!empty($data['attrs'])) 
		{
			foreach ($data['attrs'] as $attr)
			{
				if (empty($attr['name']) || !in_array($attr['type'],array('select','input'))) 
				{
					continue ;
				}
				if ($attr['type'] == 'select' && !empty($attr['options'])) 
				{
					$attr['options'] = explode(',',str_replace('，',',',$attr['options']));
				}
				else 
				{
					$attr['type'] = 'input';
					$attr['options'] = '';
				}
				$attrs[] = $attr;
			}
		}
		
		$params = array();
		if (!empty($data['params']['group'])) 
		{
			foreach ($data['params']['group'] as $i => $groupName) 
			{
				if (empty($data['params']['name'][$i])) 
				{
					continue ;
				}
				$names = array();
				foreach ($data['params']['name'][$i] as $name) 
				{
					if (empty($name)) 
					{
						continue ;
					}
					$names[] = $name;
				}
				if (empty($names))
				{
					continue ;
				}
				$params[$groupName] = $names;
			}
		}
		
		$attrs = serialize($attrs);
		$params = serialize($params);
		
		return array($attrs,$params);
	}
	
	/**
	 *  格式化输出模板
	 */
	public function decode($data)
	{
		$data['attrs'] = unserialize($data['attrs']);
		$data['params'] = unserialize($data['params']);
		
		$attrs = array();
		if (!empty($data['attrs']))
		{
			foreach ($data['attrs'] as $i => $attr)
			{
				/*if ($attr['type'] == 'select')
				{
					$attr['options'] = join(',',$attr['options']);
				}*/
				$attrs[$i] = $attr;
			}
		}
		
		$params = array();
		if (!empty($data['params'])) 
		{
			$params = array('group' => array(),'name' => array());
			$i = 1;
			foreach ($data['params'] as $groupName => $names)
			{
				$params[$i] = $groupName;
				foreach ($names as $name)
				{
					$params[$i][] = $name;
				}
				$i += 1;
			}
		}
		
		return array($attrs,$params);
	}
}

?>