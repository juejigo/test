$(function()
{
	$('.delete').click(function()
	{
		var tr = $(this).closest('tr');
		var cid = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定删除？'))
		{
			$.get('/newscp/news/delete?id=' + cid,function(json)
			{
				if (json.errno == 0)
				{
					tr.remove();
				}
				else
				{
					showMessage(json.msg);
				}
			})
		}
	})
})