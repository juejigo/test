<?php 
/**
 * 标签decode
 * @param array $position id+limit
 * @param string $type 类型
 * @return array $data
 */
function tagdecode($tag,$type)
{
	$db = Zend_Registry::get('db');
	$select = $db->select();
	$data = array();

	/* app推荐*/

	if ($type == 'app')
	{
		
	}

	/* web推荐*/

	elseif ($type == 'web')
	{
		$tagDatas = $select
		->from(array('t'=>'product_tagdata'))
		->joinLeft(array('p' => 'product'), 't.product_id = p.id',array('product_name','price','image as product_image'))
		->where('t.tag_id = ?',$tag['id'])
		->order('t.order asc')
		->limit($tag['limit'])
		->query()
		->fetchAll();

		$newjxuanProduct = array();
	
		if (!empty($tagDatas))
		{
			foreach ($tagDatas as $key => $tagData)
			{
				$tagData['product_name'] = (empty($tagData['title']) ? ' ' : empty($tagData['title'])) ? $tagData['product_name'] : $tagData['title'];
				//$tagData['image'] = (empty($tagData['image']) ? empty($tagData['product_image']) : $tagData['image']) ? DOMAIN.'static/style/default/mix/company/images/moren.gif' : $tagData['product_image'] ;
				
			 	if($tagData['image'] == '' && $tagData['product_image'] =='')
				{
					$tagData['image'] = DOMAIN.'static/style/default/mix/company/images/moren.gif';
				}
				else 
				{
					$tagData['image'] = empty($tagData['image']) ? $tagData['product_image'] : $tagData['image'];
				}
				
				$newjxuanProduct[$key]['productInfo'] = $tagData;
				$newjxuanProduct[$key]['url'] = DOMAIN.'product/product/detail/?id='.$tagData['product_id'];
			}
		}
	}
	return $newjxuanProduct;
}
?>