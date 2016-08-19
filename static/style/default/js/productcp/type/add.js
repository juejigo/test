$(function()
{
	// 添加规格
	$(document).on('click','#add_attr',function()
	{
		var index = 0;
		
		$('.attr').each(function(i,e)
		{
			var i = parseInt($(e).attr('index'));
			if (index < i)
			{
				index = i;
			}
		})
		index += 1;
		
		var html = '<tr index="' + index + '" class="attr">';
		html += '<td><input type="text" class="form-control" name="attrs[' + index + '][name]" value=""></td>';
		html += '<td><input type="text" class="form-control" name="attrs[' + index + '][options]" value=""></td>';
		html += '</tr>';
		
		$('#attr_table tbody').append(html);
	})
})