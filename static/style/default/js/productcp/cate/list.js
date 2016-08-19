$(function()
{
	$('.delete').click(function()
	{
		var tr = $(this).closest('tr');
		var cid = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定删除？'))
		{
			$.get('/productcp/cate/delete?id=' + cid,function(json)
			{
				if (json.flag == 'success')
				{
					tr.remove();
					return;
				}
				
				if (json.flag == 'error')
				{
					showMessage(json.msg);
				}
			})
		}
	})
})