$(document).ready(function()
{
	// 勾选或取消
	$('.owe').click(function()
	{
	 if($(this).hasClass('owe_on'))
	 {
		$(this).removeClass('owe_on').addClass('owe_no');
		var cartId = $(this).attr('cart_id');
		
		$.post('/cart/cart/select',{'id':cartId},function(json)
		{
			if (json.errno != 0)
			{
				$(this).removeClass('owe_no').addClass('owe_on');
			}
		})
	 }
	 else
	 {
		$(this).removeClass('owe_no').addClass('owe_on');
		var cartId = $(this).attr('cart_id');
		
		$.post('/cart/cart/select',{'id':cartId},function(json)
		{
			if (json.errno != 0)
			{
				$(this).removeClass('owe_on').addClass('owe_no');
			}
		})
	 }
	 
	 //价格信息更新
	 amount_update();
	});
	
	// 提交
	$(document).on('click','.gjiesuan',function()
	{
		sent();
	});
	
	// 全选
	$('#check_all').click(function(){
	  var self = $(this);
	  var className = self.attr('class');
	  
	  if(className=='c_all')
	  {
		self.attr('class','c_no');
		$('.owe').attr('class','owe owe_no');
	  }
	  else
	  {
		self.attr('class','c_all');
		$('.owe').attr('class','owe owe_on');
	  }
	});
});

/* 数量加减
 * var type 1减 2加
 * var id 产品id
*/
function update_num(type,id){
  if(type==1)
  {
	 if(Number($('#shu'+id).val())>1)$('#shu'+id).val(Number($('#shu'+id).val())-1);
  }
  else
  {
	 if(Number($('#shu'+id).val())<$('#shu'+id).attr('stock')){
		 $('#shu'+id).val( Number($('#shu'+id).val())+1);
	 }else{
		showMessage('超过库存数量',2000);
	 }
  }
    var sentdata = {
		id : id,
		num : $('#shu'+id).val()
	}
	//更新购物车数量
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'cart/cart/num',
		data:sentdata,
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {

		}
	});  
  //价格信息更新
  amount_update();
}

//价格更新
function amount_update(){
  var num = 0;
  var amount = 0;
  $('.car_box').each(function(){
	var obj = $(this).children(":first");
	var className = obj.attr('class');
	var item_id = obj.attr('item_id');
	var cart_id = obj.attr('cart_id');
	var item_price = obj.attr('item_price');
	var item_num = $('#shu'+cart_id).val();
	//alert(Number(item_price));return;
	//被选中
	if(className=='owe_on'){
	  num += 1;
	  //alert(Number(item_id));
	  amount += Number(item_price)*Number(item_num);
	}
  });
  var text = '<em>全选</em> 合计： '+amount+' 元<input type="button" value="去结算（'+num+'）" class="gjiesuan" >';
}

//提交信息
function sent()
{
	window.location.href = '/order/order/confirm';
}

//删除购物车
function item_del(){
  $('.car_box').each(function(){
	var cart = $(this);
	var obj = $(this).children(":first");
	var className = obj.attr('class');
	var cart_id = obj.attr('cart_id');
	//被选中
	if(className=='owe_on'){
		$.ajax({
			cache: true,
			dataType:'json',
			type: "POST",
			url:URL+'cart/cart/delete',
			data:'id='+cart_id,
			async: false,
			error: function(request) {
				alert("Connection error");
			},
			success: function(data) {
				//alert(data);
				if(data.errno==0){
					/*
					art.dialog({
						time: 1.5,
						content: '删除购物车成功'
					});
					*/
					showMessage('删除购物车成功',2000);
					$(cart).remove();
					amount_update();
					return;
				}else{
					alert(data.errmsg);
				}
			}
		});	
	}
  });

}