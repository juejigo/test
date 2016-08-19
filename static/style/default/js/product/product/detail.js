$(function()
{
	$(document).on('click','#buy',function()
	{
		var id = $(this).attr('data_id');
		
		// 先清空购物车,再添加商品到购物车
		$.post('/cart/cart/deleteall',function(json)
		{
			if (json.errno == 0)
			{
				$.post('/cart/cart/add',{'item_id':id},function(json)
				{
					if (json.errno == 0)
					{
						window.location.href = '/cart/cart/list';
					}
				})
			}
		})
	})
})