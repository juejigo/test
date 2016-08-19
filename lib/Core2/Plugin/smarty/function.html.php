<?php

/**
 *  输出自定义HTML
 * 
 *  @param array $params 参数
 *  @param smarty $template
 *  @return string
 */
function smarty_function_html($params,$template)
{
	return htmlspecialchars_decode(stripcslashes($params['content']));
}

?>