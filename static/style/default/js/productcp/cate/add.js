$(function()
{
	// 上传图片
	$('input[name="image"]').click(function()
	{
		imageUploader.show('cate',callback);
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