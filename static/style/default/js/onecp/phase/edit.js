$(function() {
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	$(".date-picker").datepicker({
		rtl: Metronic.isRTL(),
        autoclose: true
    });
    $('input[name="image"]').click(function()
	{
		imageUploader.show('product',callback);
	})
});
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
$(".form_datetime").datetimepicker({
    language: 'zh',
    weekStart: 1,
    showMeridian: 1,
    autoclose: true,
    isRTL: Metronic.isRTL(),
    format: "yyyy-mm-dd hh:ii",
    pickerPosition: (Metronic.isRTL() ? "top-left" : "top-right")
});