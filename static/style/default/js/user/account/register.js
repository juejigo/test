$(function()
{
	var height = $(window).height();
	$('.content').css('min-height',height);
	
	// 发送验证码
	$(document).on('click','#sendcode',function()
	{
		var mobile = $('input[name="account"]').val();
		
		$.post('/user/account/sendcode',{'mobile' : mobile},function(json)
		{
			if (json.errno == 0)
			{
				$('#step1').hide();
				$('#step2').show();
				$('#m').html(mobile);
			}
			else
			{
				alert(json.errmsg);
			}
		})
	})
	
	// 提交注册
	$(document).on('click','#submit',function()
	{
		
	})
})