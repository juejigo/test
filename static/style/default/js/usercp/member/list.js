$(function()
{
	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    
	// 设置编号
	$(document).on('click','.code',function()
	{
		var memberId = $(this).closest('tr').attr('dataid');
		var code = $(this).attr('code');
		
		$('#code_form').find('input[name="code"]').val(code);
		$('#code_form').find('input[name="id"]').val(memberId);
	});
	$(document).on('click','#code_submit',function()
	{
		var form = $(this).closest('form');
		
		if (confirm('确定进行此操作？'))
		{
			$.post('/usercp/member/code',form.serialize(),function(json)
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
	
	// 设置银行卡
	$(document).on('click','.bankcard',function()
	{
		var memberId = $(this).closest('tr').attr('dataid');
		var bankcard = $(this).attr('bankcard');
		
		$('#bankcard_form').find('input[name="bankcard"]').val(bankcard);
		$('#bankcard_form').find('input[name="id"]').val(memberId);
	});
	$(document).on('click','#bankcard_submit',function()
	{
		var form = $(this).closest('form');
		
		if (confirm('确定进行此操作？'))
		{
			$.post('/usercp/member/bankcard',form.serialize(),function(json)
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
/**
 * 展开收起用户资料
 */
$(document).on("click",".look_tr",function(){
	var _this=$(this);
	var pId=_this.parents("tr").attr("dataid");
	if(_this.hasClass("op")){
		_this.removeClass("op");
		_this.html('<i class="fa fa-angle-double-down"></i> 展开')
	}else{
		_this.addClass("op");
		_this.html('<i class="fa fa-angle-double-up"></i> 收起')
	}
	$("#look"+pId).toggle();
});
/**
 * 更新微信资料
 * @param  {[type]} id 用户ID
 * @param  {[type]} e  dom
 * @return {[type]}    [description]
 */
function reloadWx(id,e){
	var name=$(e).parent().prev(),
			img=name.prev().find(".user_img");
	$.post("/usercp/member/ajax?op=update",{id:id},function(data){
		if(data.errno==0){
			name.html(data.name);
			img.attr("src",data.image);
		}else{
			alert(data.errmsg);
		}
	},'json')
}
