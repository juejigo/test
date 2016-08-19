<?php

class Core2_Filter_AllowedTags implements Zend_Filter_Interface 
{
	/**
	 *  @var array
	 */
	protected $_options = array(
		'allowTags' => array(
			'img' => array('src','alt','width','height'),
			'b' => array(),
			'strong' => array(),
			'em' => array(),
			'i' => array(),
			'ul' => array(),
			'ol' => array(),
			'li' => array(),
			'p' => array(),
			'br' => array(),
			'span' => array('class'),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'h6' => array(),
			'sub' => array(),
			'sup' => array(),
			'table' => array('class','cellspacing','cellpadding'),
			'tbody' => array(),
			'tr' => array(),
			'td' => array('rowspan','colspan')),
		'allowAttribs' => array('style')
	);
	
	/**
	 *  过滤
	 * 
	 *  过滤javascript脚本,只留下被允许的标签
	 * 
	 *  @param string $value
	 *  @return string
	 */
	public function filter($value)
	{
		/*
		 *  过滤javascript脚本
		 */
		
		$tmp = '';
		while (1)
		{
			$value = preg_replace('/(<[^>]*)javascript:([^>]*>)/i','$1$2',$value);
			if ($value == $tmp)
			{
				break;
			}
			$tmp = $value;
		}
		
		/*
		 *  只留下被允许的标签
		 */
		
		$filter = new Zend_Filter();
		$filter->addFilter(new Zend_Filter_StripTags($this->_options))
			->addFilter(new Zend_Filter_HtmlEntities(array('encoding' => 'UTF-8')));;
		$value = $filter->filter($value);
		
		return $value;
	}
}

?>