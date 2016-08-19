<?php

/**
 *  格式化价格
 * 
 *  @param array $params 参数
 *  @param smarty $template
 *  @return string
 */
function smarty_function_price($params,$template)
{
	return "<span class=\"price-tag\">¥</span> <span class=\"price-value\">{$params['value']}</span>";
	
	/*$price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/','\1\2\3', number_format($params['value'],2,'.',''));
    if (substr($price, -1) == '.')
    {
        $price = substr($price,0,-1);
    }
	return $price;*/
}

?>