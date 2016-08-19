<?php
/**
 * 推荐位encode
 * @param object $input
 * @param array $params
 * @return array $data 
 */
function encode($input,$params)
{
	$data = array();
	
	$data['data_type'] = $input->data_type;
	$data['title'] = empty($input->title) ? ' ' : $input->title;
	$data['image'] = empty($input->image) ? ' ' : $input->image;
	$data['params'] = array();
	
	if ($input->data_type == 'product')
	{
		$data['params']['product_id'] = empty($params['product_id']) ? 0 : (int)$params['product_id'];
	}
	else if ($input->data_type == 'product_list')
	{
		$data['params']['keyword'] = empty($params['keyword']) ? ' ' : $params['keyword'];
		$data['params']['area'] = empty($params['area']) ? 0 : (int)$params['area'];
		$data['params']['cate_id'] = empty($params['cate_id']) ? 0 : (int)$params['cate_id'];
		$data['params']['tourism_type'] = empty($params['tourism_type']) ? 0 :(int)$params['tourism_type'];
		$data['params']['tag_id'] = empty($params['tag_id']) ? 0 : (int)$params['tag_id'];
		$data['params']['brand_id'] = empty($params['brand_id']) ? 0 : (int)$params['brand_id'];
	}
	else if ($input->data_type == 'link')
	{
		$data['params']['url'] = empty($params['url']) ? ' ' : $params['url'];
	}
	
	return $data;
}
/**
 * 推荐位decode
 * @param array $position id+limit
 * @param string $type 类型
 * @return array $data
 */
function decode($position,$type)
{
	$db = Zend_Registry::get('db');
	$select = $db->select();
	$data = array();
	
	//推荐位数据
	$positionsData = $select
		->from(array('d' => 'position_data'))
		->where('d.position_id = ?',$position['id'])
		->where('d.status = ?',1)
		->order(array('order asc','dateline desc'))
		->limit($position['limit'])
		->query()
		->fetchAll();
	    
	/* app推荐*/
	
	if ($type == 'app')
	{
		foreach ($positionsData as $key=>$positionData)
		{
			$params = Zend_Serializer::unserialize($positionData['params']);
			
			$data[$key]['type'] = $positionData['data_type'];
			$data[$key]['properties']['title'] = empty($positionData['title']) ? ' ' : htmlDecodeCn($positionData['title']);
			$data[$key]['properties']['image'] = empty($positionData['image']) ? DOMAIN.'static/style/default/mix/company/images/moren.gif' : $positionData['image'];
			
			switch ($positionData['data_type'])
			{
				//商品
				case 'product' :
					if (!empty($params['product_id']))
					{
						$productInfo = $db->select()
							->from(array('p' => 'product'),array('brief','price','down_time','product_name','image'))
							->from(array('i' => 'product_item'),array('stock'))
							->where('p.id = ?',$params['product_id'])
							->query()
							->fetch();
					}
					
					if (!empty($productInfo))
					{
						$data[$key]['properties']['image'] = empty($positionData['image']) ? (empty($productInfo['image']) ? DOMAIN.'static/style/default/mix/company/images/moren.gif' : $productInfo['image']) : $positionData['image'];
						$data[$key]['properties']['title'] = empty($positionData['title']) ? htmlDecodeCn($productInfo['product_name']) : htmlDecodeCn($positionData['title']);
						$data[$key]['properties']['product_id'] = (int)$params['product_id'];
						$data[$key]['properties']['brief'] = $productInfo['brief'];
						$data[$key]['properties']['price'] = (string)$productInfo['price'];
						$data[$key]['properties']['clock'] = (int)($productInfo['down_time'] - time());
						$data[$key]['properties']['onsale'] = empty($productInfo['stock']) ? 0 : 1;
					}
					else
					{
						$data[$key]['properties']['product_id']	= 0;
						$data[$key]['properties']['brief'] = ' ';
						$data[$key]['properties']['price'] = ' ';
						$data[$key]['properties']['clock'] = 0;
						$data[$key]['properties']['onsale'] = 0;
					}
					break ;
				
				//商品列表
				case 'product_list' :
					$data[$key]['properties']['params'] = array();
					if (!empty($params))
					{
						$data[$key]['properties']['params']['keyword'] = empty($params['keyword']) ? ' ' : $params['keyword'];
						$data[$key]['properties']['params']['area'] = empty($params['area']) ? 0 : (int)$params['area'];
						$data[$key]['properties']['params']['cate_id'] = empty($params['cate_id']) ? 0 : (int)$params['cate_id'];
						$data[$key]['properties']['params']['tag_id'] = empty($params['tag_id']) ? 0 : (int)$params['tag_id'];
						$data[$key]['properties']['params']['brand_id'] = empty($params['brand_id']) ? 0 : (int)$params['brand_id'];
						$data[$key]['properties']['params']['tourism_type'] = empty($params['tourism_type']) ? 0 : (int)$params['tourism_type'];
					}
					break ;
				
				//链接
				case 'link' :
					$data[$key]['properties']['url'] = empty($params['url']) ? ' ' : $params['url'];
					break ;
				
				default:
					break;
			}
		}
	}
	
	/* web推荐*/
	
	elseif ($type == 'web')
	{
		foreach ($positionsData as $key=>$positionData)
		{
			$params = Zend_Serializer::unserialize($positionData['params']);
			
			$data[$key]['title'] = empty($positionData['title']) ? ' ' : htmlDecodeCn($positionData['title']);
			$data[$key]['image'] = empty($positionData['image']) ? DOMAIN.'static/style/default/mix/company/images/moren.gif' : $positionData['image'];
			
			switch ($positionData['data_type'])
			{
				//商品
				case 'product' :
					
					if (!empty($params['product_id']))
					{
						$productInfo = $db->select()
							->from(array('p' => 'product'))
							->where('p.id = ?',$params['product_id'])
							->query()
							->fetch();
					}
					if (!empty($productInfo))
					{
						unset($data[$key]['title']);
						unset($data[$key]['image']);
						
						$data[$key]['productInfo']['title'] = empty($positionData['title']) ? (empty($productInfo['title']) ? ' ' : $productInfo['title']) : $positionData['title'];
						$data[$key]['productInfo']['image'] = empty($positionData['image']) ? (empty($productInfo['image']) ? DOMAIN.'static/style/default/mix/company/images/moren.gif' : $productInfo['image']) : $positionData['image'];
						$data[$key]['url'] = DOMAIN.'product/product/detail/?id='.$params['product_id'];
						
						$data[$key]['productInfo'] = $productInfo;
					}
					break ;
						
				//商品列表
				case 'product_list' :
					$param_str = http_build_query($params);
					$data[$key]['url'] = DOMAIN.'product/product/list?'.$param_str;
					break ;
						
				//链接
				case 'link' :
					$data[$key]['url'] = $params['url'];
					break;
					
				default:
					break;
			}
		}
	}
	return $data;
}

?>