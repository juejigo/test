"use strict";
$(".reload").click(function(){
  window.location.reload();
})
/**
 * 填写数量
 */
var limit=parseInt($("#limit").val()),
    surplus=parseInt($("#surplus").val()),
    price=parseInt($("#price").val()),
    prodId=getUrl("id"),
    numDom = $("#num"),
    min = $("#min"), //减少
    add = $("#add"); //增加
/**
 * 减少
 * @param  {[type]} function( [description]
 * @return {[type]}           [description]
 */
min.click(function(){
    var num=$("#num").val();
    if(num==1){
    	nAlert('数量必须大于1');
    	return false;
    }
    num--;
    numDom.val(num);
    changePrice()
});
/**
 * 增加
 * @param  {[type]} function( [description]
 * @return {[type]}           [description]
 */
add.click(function(){
    var num=$("#num").val();
    if(num>=limit || num>=surplus){
      nAlert('不能大于限购数或剩余数');
      return false;
    }
    num++;
    numDom.val(num);
    changePrice()
});
/**
 * 通过input更改数量
 * @param  {[type]} function( [description]
 * @return {[type]}           [description]
 */
numDom.change(function(){
	var num=$("#num").val();
	if(num<=0 || num>limit || num>surplus){
		nAlert("请输入正确的数量");
		numDom.val(1);
		return false;
	}
	changePrice()
})
function changePrice(){
  var s=parseInt(numDom.val());
  $(".total").html('总计：<span class="zcolor">'+price*s+'</span> 夺宝币')
}
function pay(e){
  var btn=$(e);
  btn.attr("disabled","disabled").html("正在支付...")
  $.post('/oneuc/order/ajax?op=pay',{
      id:prodId,
      num:numDom.val()
  },function(data){
    if(data.errno==0){
      //nAlert("支付成功",function(){
        btn.removeAttr("disabled","disabled").html("确认支付");
        window.location.href="/oneuc/order/paysuccess?id="+data.id;
      //})
    }else if(data.errno==2){//余额不足提示充值
      btn.removeAttr("disabled","disabled").html("确认支付");
      nAlert("余额不足，请先充值",function(){
    	window.location.href="/oneuc/order/recharge";
      });
    }else{
      btn.removeAttr("disabled","disabled").html("确认支付");
      nAlert(data.errmsg);
    }
  },'json');
}
