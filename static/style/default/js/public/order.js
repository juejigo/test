'use strict';
/**
 *
 */
$("#travel,#read").click(function(){
  $(this).toggleClass("checked");
})
$(".th").hover(
  function () {
    $(".tip").show();
  },
  function () {
    $(".tip").hide();
  }
)
if($("#right").length>0){	var rightDom=$("#right");
	var rightDomTop = rightDom.offset().top;
	$(window).scroll(function() {
	  var sTop = $(document).scrollTop();
	  if (sTop >= rightDomTop) {
	      rightDom.addClass("tag_fixed");
	  } else {
	      rightDom.removeClass("tag_fixed");
	  }
	})
}
var travelTj=$("#travel"),    //出行限制条件勾选框
    read=$("#read");
var realname=$("#realname"),
    phone=$("#phone");
var totalPeople=$("#totalPeople").val(),    //总人数
    travelPeople=$("#travelPeople");
$("#orderFill").submit(function(e){
  var _this=$(this);
  var action=_this.attr("action");
  e.preventDefault();
  //出行人条件限制
  if(!travelTj.hasClass("checked")){
    $('html,body').animate({
        scrollTop: travelTj.offset().top - 190
    }, 500);
    layer.msg("请先确认旅客出行条件");
    return false;
  }
  //联系人信息
  if(!realname.val() || !isPhone(phone.val())){
    $('html,body').animate({
        scrollTop: realname.offset().top - 75
    }, 500);
    layer.msg("请填写正确的联系人信息");
    return false;
  }
  //出行人
  var travelPeopleNum=travelPeople.find("input[type='hidden']").length;
  if(travelPeopleNum!=totalPeople){
    $('html,body').animate({
        scrollTop: travelPeople.offset().top - 20
    }, 500);
    layer.msg('请勾选'+totalPeople+'位出行人');
    return false;
  }
  //接受条款
  if(!read.hasClass("checked")){
    $('html,body').animate({
        scrollTop: read.offset().top - 30
    }, 500);
    layer.msg("请确认已经阅读预订须知、合同、保险等条款");
    return false;
  }
  $(".place_order").attr("disabled","disabled");
  $.post(action,_this.serialize(),function(data){
    if(data.errno==0){
      window.location.href=data.url;
    }else{
      layer.msg(data.errmsg)
      $(".place_order").removeAttr("disabled","disabled");
    }
  },'json');
});
/**
 * 勾选出行人并向服务器发起请求
 */
var personTable=$(".order_person_table ul");
$(document).on('click','.order_person_list li',function(){
    var _this=$(this);
    var id=_this.data("id");
    if(_this.hasClass('checked')){
      $('#person'+id+'').remove();
      _this.removeClass('checked');
    }else{
      addPerson(id,function(data){
        var str = '<li id="person'+data.tourist_id+'">';
        str += '<span class="fl">'+data.tourist_name+'</span>';
        str += '<span class="fl">'+data.cert_type+'</span>';
        str += '<span class="fl">'+data.cert_num+'</span>';
        str += '<span class="fl">'+data.mobile+'</span>';
        str += '<input type="hidden" name="tourist_ids[]" value="'+data.tourist_id+'">';
        str += '</li>';
        personTable.append(str);
        _this.addClass('checked');
      });
    }
})
/**
 * 增加出行人页面
 * @param {[type]} id 出行人ID
 */
function addPerson(id,callback){
  $.post('/order/order/ajax?op=tourist',{id:id},function(data){
    if(data.errno==0){
        callback(data.tourist);
    }else{
        layer.msg('勾选失败');
    }
  },'json')

}
/**
 * 打开增加出行人填写框
 */
var mask=$('.mask');
var popup=$('.popup');
$(document).on("click",".add_tourist",function(){
  mask.css('z-index', '10001');
  mask.animate({
      'opacity': '0.5'
  }, 'fast');
  popup.css('z-index', '10002');
  popup.animate({
      'opacity': '1',
      'margin-top': '-250px'
  }, 'slow');
})

$(".popup_close").click(function(){
    popup.animate({
        'z-index':'-2',
        'margin-top':'-220px',
        opacity:'0'
    });
    mask.animate({
        'z-index':'-2',
        opacity:'0'
    });
})
/**
 * 增加出行人
 */
$("#addTourist").submit(function(e){
  e.preventDefault();
  var _this=$(this);
  var btn=_this.find("button[type='submit']");
  var resetBtn=_this.find("button[type='reset']");
  var url=_this.attr("action");
  var tourist_name=$("#tourist_name");
  if(!tourist_name.val()){
      tourist_name.next().html("请输入出行人姓名");
      return false;
  }
  tourist_name.next().html("");
  var mobile=$("#mobile");
  if(!isPhone(mobile.val())){
      mobile.next().html("请输入正确的手机号码");
      return false;
  }
  mobile.next().html("");
  var cert_type=$("#cert_type");
  var cert_num=$("#cert_num");
  if(cert_type.val()==1){
    if(!isCardNo(cert_num.val())){
        cert_num.next().html("请正确的身份证件号");
        return;
    }
  }else{
    if(!cert_num.val()){
        cert_num.next().html("请输入证件号");
        return;
    }
  }
  cert_num.next().html("");
  var cert_deadline=$("#cert_deadline");
  if(!cert_deadline.val()){
      cert_deadline.next().html("请输入有效期");
      return false;
  }
  cert_deadline.next().html("");
  btn.attr("disabled", "disabled").html("正在提交...");
  $.post(url,_this.serialize(),function(data){
    if(data.errno==0){
      popup.animate({
          'z-index':'-2',
          'margin-top':'-220px',
          opacity:'0'
      });
      mask.animate({
          'z-index':'-2',
          opacity:'0'
      });
      $(".order_person_list ul").append('<li data-id="'+data.tourist_id+'"><i class="check"></i>'+tourist_name.val()+'</li>')
      layer.msg('出行人添加成功');
      btn.html("确认添加").removeAttr("disabled");
      resetBtn.click();
    }else{
      _this.find("p.error[for='all']").html(data.errmsg);
      btn.html("确认添加").removeAttr("disabled");
    }
  },'json');
})
/**
 * 验证手机号码
 * @param  {[type]}  num 手机号码
 */
function isPhone(num){
    var mobile = /^1[0-9]{10}$/;
    return (mobile.exec(num)) ? true : false;
}
/**
 * 验证身份证
 * @param  {[type]}  card 卡号
 */
function isCardNo(card){
   var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
   return (reg.exec(card)) ? true : false;
}
/**
 * 展开收起
 */
$(".operate a").on("click",function(){
  var _this=$(this);
  var parent=_this.parents(".order_insurance_table");
  if(parent.hasClass("up")){
    parent.removeClass("up");
    parent.find('.row:not(:first-child)').addClass("none");
    _this.html("展开");
    parent.find(".detail").hide();
  }else{
    parent.addClass("up");
    parent.find('.row:not(:first-child)').removeClass("none");
    _this.html("收起");
  }
})
/**
 * 勾选保险
 */
var insList=$("#insuranceList");
var insBox=$("#insurance");
var totalAmount=$("#totalAmount").val()
insList.on("click",".check",function(){
  var par=$(this).parents("li");
  if(par.hasClass('checked')){
    par.removeClass('checked');
  }else{
    par.addClass('checked');
  }
  //将勾选保险添加到右侧信息栏
  addIns();
})
function addIns(){
  var checkedIns=insList.find(".checked");
  var zj=parseInt(totalAmount);
  if(checkedIns.length<=0){
    insBox.html("");
    $(".order_right_bottom .amount").html('￥'+zj);
    $(".order_amount span").html('￥'+zj);
  }else{
    var str="<dt>保险费用</dt><dd>";
    checkedIns.each(function(i,t){
        var data=$(t).data();
        str+='<p>'+data.title+'</p>';
        str+='<span class="number">'+totalPeople+'</span>人 x <span class="price">￥'+data.money+'</span><span class="amount">￥'+data.money*totalPeople+'</span>';
        str+='<input type="hidden" name="insurance_ids[]" value="'+data.id+'">';
        zj+=data.money*totalPeople;
    })
    str+="<dd>";
    $(".order_right_bottom .amount").html('￥'+zj);
    $(".order_amount span").html('￥'+zj);
    insBox.html(str);
  }
}

//展开保险详情
$(".order_insurance_table li").on("click",".name",function(){
  var details=$(this).parents("li").next();
  details.slideToggle("fast");
})

/**
 * 绑定时间
 */
if($("#addTourist").length>0){
  $("#cert_deadline").datetimepicker({
      language: 'zh',
      autoclose: true,
      minView: "month",
      format: "yyyy-mm-dd",
      pickerPosition: "top-right"
  })
}
/**
 * 选择支付方式
 */
$('.pay_type_box .pay_type').click(function () {
  var _this=$(this),
      val=_this.data("type");
  if(_this.hasClass("checked")){
    return false;
  }
  _this.addClass('checked').siblings().removeClass('checked');
  $("#payType").val(val);
  if($("#payType").val()){
    $(".pay_btn").removeAttr("disabled");
  }
});
