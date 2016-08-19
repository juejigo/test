var errorMsg=$(".error_msg");
$("#jpush").submit(function(e){
		e.preventDefault();
    $.post($(this).attr("action"),$(this).serialize(),function(data){
        if(data.flag=='error'){
          var str='<div class="alert alert-danger">';
    			str+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
    			str+='<strong>错误！</strong> 错误信息：'+data.msg;
    			str+='</div>';
        }else{
          var str='<div class="alert alert-success">';
    			str+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
    			str+='<strong>成功！</strong> '+data.msg;
    			str+='</div>';
        }
        errorMsg.html(str);
    },'json');
});
//选择目标人群
var userIdDom=$('<div class="form-group"></div>');
$("#target").on("change",function(){
  userIdDom.html('<label class="control-label col-md-2">用户ID </label><div class="col-md-6"><input type="text" class="form-control select2" name="user_id" placeholder="用户ID,输入完请敲回车"></div></div>');
  var pars=$(this).parents('.form-group');
  var val=$(this).val();
  if(val=='one'){
    pars.after(userIdDom);
    userIdDom.find(".select2").select2({
        tags: []
    });
  }else{
    userIdDom.remove();
  }
})
//选择类型
var typeIdDom=$('<div class="form-group"></div>');
$("#type").on("change",function(){
  var pars=$(this).parents('.form-group');
  var val=$(this).val();
  if(val=="product"){
	typeIdDom.html('<label class="control-label col-md-2">商品ID </label><div class="col-md-6"><input type="text" class="form-control" name="product_id" placeholder="商品ID"></div>');
    pars.after(typeIdDom);
  }else if(val=="order"){
	typeIdDom.html('<label class="control-label col-md-2">订单ID </label><div class="col-md-6"><input type="text" class="form-control" name="order_id" placeholder="订单ID"></div>');
    pars.after(typeIdDom);
	}else{
    typeIdDom.remove();
  }
})
//选择时间
var sendTimeDom=$('<div class="form-group"></div>');
sendTimeDom.append('<label class="control-label col-md-2">发送时间 </label><div class="col-md-6"><input type="text" class="form-control form_datetime" name="send_time" placeholder="发送时间"></div>');

$("#sendType").on("change",function(){
  var pars=$(this).parents('.form-group');
  var val=$(this).val();
  if(val=='timer'){
    pars.after(sendTimeDom);
    sendTimeDom.find(".form_datetime").datetimepicker({
        language: 'zh',
        weekStart: 1,
        showMeridian: 1,
        autoclose: true,
        isRTL: Metronic.isRTL(),
        format: "yyyy-mm-dd hh:ii",
        pickerPosition: (Metronic.isRTL() ? "top-left" : "top-right")
    });
  }else{
    sendTimeDom.remove();
  }
})
