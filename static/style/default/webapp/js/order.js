$(function()
{
	$('.df').click(function()
	{
		var id = prompt('请输入代付人ID','');
		var orderId = $(this).attr('dataid');
		
		if (id != null && id != '')
		{
			$.post('/orderuc/advance/apply',{'payer_id':id,'order_id':orderId},function(json)
			{
				if (json.errno == 0)
				{
					showMessage('申请已提交');
				}
				else if (json.errno == 1)
				{
					showMessage(json.errmsg);
				}
			})
		}
	})
})

/* 确认订单页 */
//发票选择
function fp(){
  if($('#tax').attr("checked")=='checked'){
	$('#tax').attr("checked",false);
  }else{
	$('#tax').attr("checked",true);
  }
}
//红包选择
function hb(coupon_user_id,val){
	if(coupon_user_id>0){
		$('#coupon_user_id').attr('value',coupon_user_id);
		$('#coupon_user_id').text(val+'元红包');
	}else{
		$('#coupon_user_id').attr('value',0);
		$('#coupon_user_id').text('');		
	}
	$('#dd_hbbb').hide();
}
//提交信息
function sent(){
	var consignee_id = $('.s_name').attr('value');
	var tax = $("#tax:checked").val() ? 1 : 0;
	var tax_title = $('#tax_title').val();
	var tax_content = $('#tax_content').val();
	var memo = $('#memo').val();
	//alert(tax);
	if(!consignee_id){
		/*
		art.dialog({
			time: 1.5,
			content: '缺少收件人信息'
		});
		*/
		showMessage('缺少收件人信息',2000);
		return;
	}
	var coupon_user_id = $('#coupon_user_id').attr('value');
	//购物车支付
	if(type==1){
		sentdata = {
			consignee_id : consignee_id,
			tax : tax,
			tax_title : tax_title,
			tax_content : tax_content,
			memo : memo,
			coupon_user_id : coupon_user_id
		}
	}else{
	//直接下单
		sentdata = {
			item_id : item_id,
			num : item_num,
			consignee_id : consignee_id,
			tax : tax,
			tax_title : tax_title,
			tax_content : tax_content,
			memo : memo,
			coupon_user_id : coupon_user_id
		}	
	}
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'order/order/create',
		data:sentdata,
		async: false,
		error: function(request) {
			alert("系统繁忙");
		},
		success: function(data) {
			//alert(data);return;
			if(data.errno==0){
				/*
				art.dialog({
					time: 1,
					content: '提交成功'
				});
				*/
				//showMessage('提交成功',1000);
				window.location.href = URL+'order/order/beforpay?id='+data.order_id;
				return;
			}else{
				alert(data.errmsg);
			}
		}
	});
}

/* 列表页 */

//取消订单
function cancel(id){
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'order/order/cancle',
		data:"id="+id,
		async: false,
		error: function(request) {
			//alert("系统繁忙");
			showMessage('系统繁忙',1000);
		},
		success: function(data) {
			if(data.errno==0){
				/*
				art.dialog({
					time: 1,
					content: '取消成功'
				});
				*/
				showMessage('取消成功',1000);
				setTimeout(function(){
					location.reload();			
				},1500);
				return;
			}else{
				/*
				art.dialog({
					time: 1,
					content: data.errmsg
				});
				*/
				showMessage(data.errmsg,2000);
			}
		}
	});
}

//删除订单
function del(id){
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'order/order/delete',
		data:"id="+id,
		async: false,
		error: function(request) {
			//alert("系统繁忙");
			showMessage('系统繁忙',1000);
		},
		success: function(data) {
			if(data.errno==0){
				/*
				art.dialog({
					time: 1,
					content: '取消成功'
				});
				*/
				showMessage('删除成功',1000);
				setTimeout(function(){
					location.reload();			
				},1500);
				return;
			}else{
				/*
				art.dialog({
					time: 1,
					content: data.errmsg
				});
				*/
				showMessage(data.errmsg,2000);
			}
		}
	});
}

//付款
function payment(id){
	//window.location = URL+'order/order/pay?id='+id;
	window.location = URL+'order/order/beforpay?id='+id;
}

//评价
function feedback(id){
	window.location = URL+'order/order/feedback?id='+id;
}

//退款申请
function refund(id){
	window.location = URL+'order/order/refund?id='+id;
}

//查看物流
function shipping(id){
	window.location.href = URL+'order/order/shipping?id='+id;
}

//退货单填写
function return_product(id){
	window.location.href = URL+'order/order/return?id='+id;
}

//退货单填写
function finish(id){
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'order/order/finish',
		data:"id="+id,
		async: false,
		error: function(request) {
			showMessage('系统繁忙',1000);
		},
		success: function(data) {
			if(data.errno==0){
				showMessage('确认成功',1000);
				setTimeout(function(){
					location.reload();			
				},1500);
				return;
			}else{
				showMessage(data.errmsg,2000);
			}
		}
	});
}