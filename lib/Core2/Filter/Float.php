<?php

class Core2_Filter_Float implements Zend_Filter_Interface 
{
	/**
	 *  过滤
	 * 
	 *  格式化成浮点数形式
	 * 
	 *  @param string $value
	 *  @return float
	 */
	public function filter($value)
	{
		return (float) ((string) $value);
	}
}

?>