<?php

/**
 *  性别
 * 
 *  @param array $params 参数
 *  @param smarty $template
 *  @return string
 */
function smarty_function_fieldvalue($params,$template)
{
	if (empty($params['field']) || !isset($params['value'])) 
	{
		return false;
	}
	
	$ret = '未设置';
	switch ($params['field'])
	{
		case 'memberStatus':
			switch ($params['value'])
			{
				case '-1':
					$ret = '无效';
					break;
				case '0':
					$ret = '过期';
					break;
				case '1':
					$ret = '有效';
					break;
				default:
					break;
			}
			break;
			
		case 'memberRole':
			switch ($params['value'])
			{
				case 'member':
					$ret = '会员';
					break;
				case 'admin':
					$ret = '管理员';
					break;
				default:
					break;
			}
			break;
		
		case 'orderStatus':
			switch ($params['value'])
			{
				case '-1':
					$ret = '已关闭';
					break;
				case '0':
					$ret = '未支付';
					break;
				case '1':
					$ret = '待发货';
					break;
				case '2':
					$ret = '待确认收货';
					break;
				case '3':
					$ret = '已完成';
					break;
				case '10':
					$ret = '申请退款';
					break;
				case '11':
					$ret = '待退货';
					break;
				case '12':
					$ret = '待确认退货';
					break;
				case '13':
					$ret = '已退款';
					break;
				case '14':
					$ret = '拒绝退货';
					break;
				default:
					break;
			}
			break;
		
		case 'orderPayment':
			switch ($params['value'])
			{
				case 'aliapp':
					$ret = '支付宝移动端';
					break;
				case 'wxapp':
					$ret = '微信移动端';
					break;
				case 'wxweb':
					$ret = '微信网页端';
					break;
				case 'balance':
					$ret = '余额';
					break;
				default:
					break;
			}
			break;
			
		case 'orderDiscountType':
			switch ($params['value'])
			{
				case '0':
					$ret = '积分';
					break;
				case '1':
					$ret = '红包';
					break;
				case '2':
					$ret = '折扣';
					break;
				default:
					break;
			}
			break;
		
		default:
			break;
	}
	
	return $ret;
}

?>