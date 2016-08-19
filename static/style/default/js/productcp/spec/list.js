$(function()
{
	// 增加规格
	$(document).on('click','#spec_submit',function()
	{
		var form = $(this).closest('form');
		
		if (confirm('确定进行此操作？'))
		{
			$.post('/productcp/spec/ajax?op=addspec',form.serialize(),function(json)
			{
				if (json.errno == 0)
				{
					window.location.reload();
				}
				else
				{
					showMessage(json.errmsg)
				}
			})
		}
	});
})