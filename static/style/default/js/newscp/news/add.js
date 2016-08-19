$(function()
{
	// 上传图片
	$('input[name="image"]').click(function()
	{
		imageUploader.show('news',callback);
	})
	
	KindEditor.ready(function(K) {
	    window.editor = K.create('#content',{
	        //cssPath:'/public/plugin/editor/plugins/code/prettify.css',
	        uploadJson:'/imageuc/kindeditor/upload?',
	        extraFileUploadParams : {
	            cookie : $.cookie('session_id')
            },
	        resizeType :1,
	        allowPreviewEmoticons : true,
	        allowImageUpload : true,
	      });	
	});
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