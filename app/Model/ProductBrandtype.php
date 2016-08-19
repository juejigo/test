<?php

class Model_ProductBrandtype extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'product_brandtype';
	
	/**
	 *  @var array
	 */
	protected $_primary = array('brand_id','type_id');
}

?>