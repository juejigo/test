$(function()
{
	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true,
    });
    
	$('.delete').click(function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定删除？'))
		{
			$.get('/productcp/tag/delete?id=' + id,function(json)
			{
				if (json.errno == '0')
				{
					tr.remove();
					return;
				}
				
				if (json.errno == '1')
				{
					showMessage(json.errmsg);
				}
			})
		}
	})
})