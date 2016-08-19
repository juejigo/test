$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	//添加日期选择功能 
    $(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true,
    });
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
//下架
$('.down').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定下架？')){
		$.post('/onecp/phase/ajax?op=down',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.errmsg);
			}
		})
	}
})
// 上架
$('.up').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定上架？')){
		$.post('/onecp/phase/ajax?op=up',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.errmsg);
			}
		})
	}
})
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.post('/onecp/phase/ajax?op=delete',{id:id},function(data){
			if (data.errno == 0){
				tr.remove();
				return;
			}else{
				alert(data.errmsg);
			}
		})
	}
})
//勾选删除
$(".checkDel").click(function(){
	var cBoxs=$("input.checkbox:checked");
	var arr=new Array();
	if(cBoxs.length>0){
		$.each(cBoxs,function(i,d){
			var id=$(d).closest('tr').data('id');
			arr[i]=id;
		})
		if (confirm('确定删除？')){
			$.post('/onecp/phase/ajax?op=delete',{id:arr},function(data){
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