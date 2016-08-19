"use srtict";
$(function(){
	//如果insIds有值，就进行自动勾选
	if(conIds.val()){
		var conId=conIds.val().split(",");
		conId.forEach(function(v,i){
			$('input[value='+v+']').iCheck('check'); 
		})
	}
})
var pordId=$("#prodId").val(),
	conIds=$("#conIds"),
	conModal=$("#conModal"),		//合同选择弹框
	conBox=$("#conBox");		//已经保存的合同
var conArr= new Array();		//保存已经勾选的数组
/*
	勾选icheck添加到conIds
*/
$('.icheck').on('ifChecked', function(){
  var v=$(this).val();
  conIds.val(v);
});

var con=new Object();
/*
	添加已选择的合同
*/
con.add=function(){
	if(!conIds.val()){
		alert("请勾选需要的合同");
		return false;
	}
	$.ajax({
		url:"ajax?op=addcontract",
		type:"post",
		dataType:"json",
		data:{
			contract_id:pordId,
			conIds:conIds.val()
		}
	}).done(function(data){
		if(data.errno == 0){
			conModal.modal("hide").on('hidden.bs.modal', function (e) {
				var str='';
				
					str+='<div class="col-sm-12"><div class="alert alert-success">';
					str+='<span>'+data.con.contract_name+'</span>';
					str+='<a href="javascript:;" style="margin-top:-5px" class="pull-right btn btn-sm red" onclick="con.del(this,'+data.con.id+')"><i class="fa fa-times"></i> 删除</a>';
					str+='</div></div>';
				
				conBox.html($(str).hide().fadeIn(600));
			})
		}
	})
}
/*
	删除合同
*/
con.del=function(e,id){
	$.ajax({
		url:"ajax?op=deletecontract",
		type:"post",
		dataType:"json",
		data:{
			pordId:pordId,
			conId:id
		}
	}).done(function(data){
		if(data.errno == 0){
			conArr.remove(id);
  			conIds.val(conArr);
  			$('input[value='+id+']').iCheck('uncheck'); 
			var dome=$(e).parent().parent();
			dome.animate({opacity:0},500,function(){
				dome.remove();
			});
		}
	})
}