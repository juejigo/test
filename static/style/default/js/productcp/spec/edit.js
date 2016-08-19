$(function()
{
	// 添加规格
	$(document).on('click','#value_submit',function()
	{
		var form = $(this).closest('form');
		
		if (confirm('确定进行此操作？'))
		{
			$.post('/productcp/spec/ajax?op=addvalue',form.serialize(),function(json)
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
	
	// 编辑规格
	$(document).on('click','.specval',function()
	{
		var id = $(this).closest('tr').attr('dataid');
		
		$('#edit_form').find('input[name="id"]').val(id);
	})
	$(document).on('click','#edit_submit',function()
	{
		var form = $(this).closest('form');
		
		if (confirm('确定进行此操作？'))
		{
			$.post('/productcp/spec/ajax?op=editvalue',form.serialize(),function(json)
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