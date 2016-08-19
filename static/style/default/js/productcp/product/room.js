"use srtict";
var trip=new Object(),
	addBox=$("#addRoomForm");
var prodId=$("#prodId").val(),		//商品ID
	tripBox=$("#tripBox");		//表格

$(function()
{
	// 上传图片
	$('input[name="image"]').click(function()
	{
		imageUploader.show('product',callback);
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

/*
增加行程
*/
trip.add=function(){
var addon_name=$("#addon_name").val(),		//序号
	image=$("#image").val(),		//标题
	area=$("#area").val(),		//摘要
	num=$("#num").val(),
	price=$("#price").val(),
	floor=$("#floor").val(),
	stock=$("#stock").val(),
	facilities=$("#facilities").val();		//具体行程
if(!addon_name){
	alert("请输入房间名");
	return false;
}
$.ajax({
	url:'ajax?op=addroom',
	type:'POST',
	dataType:'json',
	data:{
		product_id:prodId,
		image:image,
		addon_name:addon_name,
		price:price,
		num:num,
		area:area,
		floor:floor,
		stock:stock,
		facilities:facilities,
	}
}).done(function(data){
	if(data.errno == 0){
		addBox.modal("hide").on('hidden.bs.modal', function (e) {
			window.location.reload();
		})
	}else
	{	
		alert(data.msg);
	}
})
}
$(".del").click(function(){
	var _this=$(this).parents("tr"),
		id=_this.data("roomid");
	$.ajax({
		url:'ajax?op=deleteroom',
		type:'POST',
		dataType:'json',
		data:{
			id:id
		}
	}).done(function(data){
		if( data.errno == 0){
			_this.animate({opacity:0},800,function(){
				_this.remove();
			})
		}
	})
})