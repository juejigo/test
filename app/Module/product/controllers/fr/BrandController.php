<?php

class Product_BrandController extends Core2_Controller_Action_Fr  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  列表
	 */
	public function listAction()
	{
		/* 获取列表 */
		$this->json['brand_list'] = array();
		
		$select = $this->_db->select()
			->from(array('b' => 'product_brand'))
			->where('b.status = ?','1');

		$results = $select->query()
			->fetchAll();
		$brandList = array();
		foreach ($results as $result) 
		{
			$brand = array();
			$brand['id'] = $result['id'];
			$brand['brand_name'] = $result['brand_name'];
			$brand['image'] = thumbpath($result['image'],220);
			$brandList[] = $brand;
		}
		$this->view->brandList = $brandList;
	}
}

?>