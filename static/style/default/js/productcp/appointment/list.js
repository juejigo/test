$(function() {    
	//添加日期选择功能 
    $(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true,
    });
});
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').attr('dataid');
	if (confirm('确定删除？')){
		$.post('/productcp/appointment/ajax?op=delete',{ id:id},function(data){
			if (data.errno == 0){
				tr.remove();
				return;
			}else{
				alert(data.errmsg);
			}
		});
	}
});
//确认
$('.confirm').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').attr('dataid');
	if (confirm('确认预约？')){
		$.post('/productcp/appointment/ajax?op=confirm',{ id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.errmsg);
			}
		});
	}
});
//勾选
$("#allCheck").click(function(){
	var _this=$(this),
			cb=$(".checkbox");
	if(_this.attr("checked") == "checked"){
		cb.attr("checked","checked");
		cb.parent().addClass("checked");
		cb.parent().parent().addClass("checked");
	}else{
		cb.removeAttr("checked","checked");
		cb.parent().removeClass("checked");
		cb.parent().parent().removeClass("checked");
	}
})

//删除
$('#all_delete').click(function(){
	var cBoxs=$("input.checkbox:checked");
	var arr=new Array();
	if(cBoxs.length>0){
		$.each(cBoxs,function(i,d){
			var id=$(d).closest('tr').attr('dataid');
			arr[i]=id;
		})
		if (confirm('确定删除勾选项？')){
			$.post('/productcp/appointment/ajax?op=delete',{id:arr},function(data){
				if (data.errno == 0){
					window.location.reload();
				}else{
					alert(data.errmsg);
				}
			})
		}
	}else{
		alert("至少勾选一项");
	}
})