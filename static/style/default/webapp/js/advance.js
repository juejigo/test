$(function()
{
	$('.df').click(function()
	{
		var id = $(this).attr('dataid');
		
		$.post('/orderuc/advance/pay',{'id':id},function(json)
		{
			if (json.errno == 0)
			{
				window.location.href = '/order/order/beforpay/?' + 'id=' + json.order_id;
				return;
			}
			else if (json.errno == 1)
			{
				showMessage(json.errmsg);
				return;
			}
		})
	})
})