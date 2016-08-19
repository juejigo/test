"use strict";
$(function(){
	//订单列表根据type值，改变状态
	var type=getQueryString("type")?getQueryString("type"):1;	//获取地址栏type参数
	var lis=$(".order_type").find("li");
	$.each(lis,function(i,d){
		var li=$(d);
		if(li.attr("rel")==type){
			li.addClass("check");	
		}
	})
	subtotals.text(price);
	totalPrice.text(price);
})
var z= /^[0-9]*[1-9][0-9]*$/;   //正整数
var p= /^1\d{10}$/;				//手机
var s= /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;	//身份证
//根据数量计算小计总价
var numDom=$("#num"),
	min=$("#min"),					//减少
	add=$("#add"),					//增加
	price=$("#price").attr("rel"),	//单品价格
	subtotals=$("#subtotals"),		//小计
	totalPrice=$("#totalPrice");	//总计
//减少
min.click(function(){
	var num=$("#num").val();
	var prodId=$("#prodId").val();
	if(num==1){
		alert('数量必须大于1');
		return false;
	}
	num--;
	// 考虑到ajax延迟，先改变价格
	compute(num);
	$.post('/cart/cart/num',{'num':num},function(json)
	{
		if (json.errno != 0)
		{
			num++;
			// 提交错误，恢复价格
			compute(num);
		}
	})
});
//增加
add.click(function(){
	var num=$("#num").val();
	num++;
	// 考虑到ajax延迟，先改变价格
	compute(num);
	$.post('/cart/cart/num',{'num':num},function(json)
	{
		if (json.errno != 0)
		{
			num--;
			// 提交错误，恢复价格
			compute(num);
		}
	})
});
//通过input更改数量
numDom.change(function(){
	var num=$("#num").val();
	if(!z.test(num)){
		alert("数量必须大于1的整数");
		numDom.val(1);
		return false;
	}
	compute(num);
	$.post('/cart/cart/num',{'num':num},function(json)
	{
		if (json.errno != 0)
		{
			num--;
			// 提交错误，初始为1
			compute(1);
		}
	})
})
function compute(num){
	numDom.val(num);
	subtotals.text(num*price);
	totalPrice.text(num*price);
}

//订单相关
var order={
	//提交订单
	creat:function(){
		var prodId=$("#prodId").val(),
			num=$("#num").val(),
			realName=$("#realName").val(),
			idCard=$("#idCard").val(),
			phone=$("#phone").val(),
			btn=$("button");
		if (!p.test(phone)) {
	        alert("请填写正确手机");
	        return false;
	    }
	    btn.attr("disabled","disabled");
	    $.ajax({
	        url: "/order/order/create",
	        data: {
	        	'item_id':prodId,
	        	'num':num,
	            'mobile': phone
	        }
	    }).done(function(data){
            if (data.errno == 0) {
                window.location.href = '/order/order/beforpay?id='+data.order_id;
            } else {
            	btn.removeAttr("disabled","disabled");
                alert(data.errmsg);
            }
        }).fail(function(){ 
        	alert("网络错误"); 
        	btn.removeAttr("disabled","disabled");
        });
	},
	//支付订单
	pay:function(){
		var payType=$("input[name=payType]:checked").val();
		if(!payType){
			alert("请选择支付方式");
			return false;
		}
		switch(payType){
			case 'wx':
			//微信支付相关
			callpay();
			break;
			case 'bank':
			//银行卡支付相关
			
			break;
			case 'zfb':
			//支付宝支付相关
			
			break;
		}
	},
	//申请退款
	refund:function(orderId){
		jConfirm("您确定退款吗？","",function (r){
	        if (r) {
	        	$.ajax({
			        url: "../testApi/success.php",
			        data: {orderId: orderId},
			    }).done(function(data){
			        if (data.Success) {
			            jAlert(data.Msg,"",function(){
			            	location.reload();
			            });
			        } else {
			            alert(data.Msg);
			        }
			    }).fail(function(){ alert("网络错误"); });
	        }
	    });
	},
	//退款中
	refunding:function(){
		jAlert("退款进行中，请等带商家处理。");
	}
}
