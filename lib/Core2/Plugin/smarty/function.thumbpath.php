<?php

/**
 *  获取图片缩略图地址
 * 
 *  @param array $params 参数
 *  @param smarty $template
 *  @return string
 */
function smarty_function_thumbpath($params,$template)
{
	$image = '';
	$ret = '';
	
	if (!empty($params['source']) && is_numeric($params['source'])) 
	{
		$db = Zend_Registry::get('db');
		$r = $db->select()
			->from(array('i' => 'image'))
			->where('i.id = ?',$params['source'])
			->query()
			->fetch();
		$image = $r['url'];
	}
	else 
	{
		$image = $params['source'];
	}
	
	if (empty($image)) 
	{
		$default = empty($params['default']) ? 'blank' : $params['default'];
		switch ($default)
		{
			case 'pic':
				$ret = URL_IMG . "public/default_pic.thumb.{$params['width']}.jpg";
				break;
			case 'blank':
				$ret = URL_IMG . 'public/blank.gif';
				break;
			default:
				break;
		}
	}
	else 
	{
		$pathinfo = pathinfo($image);
		$ret = ($params['width'] == 0) ? $image : "{$image}.thumb.{$params['width']}.{$pathinfo['extension']}";
	}
	
	return $ret;
}

?>