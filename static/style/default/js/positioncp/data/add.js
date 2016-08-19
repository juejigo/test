$(function()
{
	// 时间
	$('.form_datetime').datetimepicker({
	    language: 'zh',
	    weekStart: 1,
	    showMeridian: 1,
	    autoclose: true,
	    isRTL: Metronic.isRTL(),
	    format: "yyyy-mm-dd hh:ii",
	    pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
	});
	
	// 上传图片
	$('input[name="image"]').click(function()
	{
		imageUploader.show('position',callback);
	})
	
	// 上架时间
	$('#upcheck').click(function()
	{
		var _this=$(this);
		if(_this.attr("checked") == "checked"){
			$('input[name="up_time"]').attr("readonly",false);
		}else{		
			$('input[name="up_time"]').attr("readonly","readonly");
			$('input[name="up_time"]').val(0);
		}
	})
	
	// 下架时间
	$('#downcheck').click(function()
	{
		var _this=$(this);
		if(_this.attr("checked") == "checked"){
			$('input[name="down_time"]').attr("readonly",false);
		}else{		
			$('input[name="down_time"]').attr("readonly","readonly");
			$('input[name="down_time"]').val(0);
		}
	})
	
	// 选择关联
	$(document).on('change','select[name="data_type"]',function()
	{
		var type = $(this).val();
		
		$('.params').hide();
		$('.params.' + type).show();
	})
	
	// 选择商品区域
	$(document).on('change','select[name="params[area]"]',function()
	{
		var area = $(this).val();
		
		$.post('/positioncp/data/ajax?op=areachanged',{'area':area},function(json)
		{
			if (json.errno == 0)
			{
				$('#cate_selector').html(json.html);
			}
		})
	})
})

function callback(responseObject)
{
	json = $.parseJSON(responseObject.response);
	
	if (json.flag == 'error')
	{
		showMessage(json.msg);
	}
	else if (json.flag == 'success')
	{
		$('input[name="image"]').val(json.url);
	}
}