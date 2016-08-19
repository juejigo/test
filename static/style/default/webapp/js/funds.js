/* 转账 */
//确认
function confirm(){
	confirm_pay();
}

//确认支付
function confirm_pay(){
	send('transfer','/funds/funds/post');
}