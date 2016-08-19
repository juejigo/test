"use strict";
$(function() {
  //商品详情绑定幻灯片
  $('.am-slider').flexslider({
    directionNav: false,
  });
  //商品列表加载列表
  if($("#indexList").length>0){
    setTimeout(function(){
  		getList();
  	},SETTIME)
  }
  if($(".countdown_box").length>0){
    countDown.init($(".countdown"),function(){
      location.reload();
    });
  }
});
/*************************************************
 * 商品列表方法
 *************************************************/
var listLoading = false;    //是否可以加在标志
var listPage=1;    //列表页码
var ul=$("#indexList");		//列表
//滚动触发
$(document).scroll(function() {
	if(!listLoading){
		scrollLoad();
	}
});
/**
 * 滚动加载
 * @return {[type]} [description]
 */
function scrollLoad(){
	if ($(document).height() - $(window).height() - $(window).scrollTop() <= 100) {
  		listLoading=true;
  		setTimeout(function(){
  			getList();
  		},SETTIME)
    }
}
/**
 * 获取列表
 * @return {[type]} [description]
 */
function getList() {
	$.post("/one/phase/ajax?op=list",{page:listPage},function(data){
		if(data.errno==0){
			var str='';
			var l=data.product_list;
			$.each(l,function(i,t){
				str+='<li>';
				str+='<a href="/one/phase/detail?id='+t.id+'" class="pic"><img class="lazy" src="'+t.image+'"><span class="xg">限购'+t.purchase+'次</span></a>';
				str+='<span class="title"><a href="/one/phase/detail?id='+t.id+'">'+t.name+'</a></span>';
				str+='<div class="info_box flex-wrap">';
				str+='<div class="info flex-con">';
				str+='<div class="am-text-xs am-link-muted">总需'+t.need+'人次</div>';
				str+='<div class="am-progress am-progress-striped am-progress-xs am-active">';
				str+='<div class="am-progress-bar am-progress-bar-danger"  style="width: '+t.schedule+'%"></div>';
				str+='</div>';
				str+='<div class="am-text-xs am-link-muted"><span class="am-fl">剩余<span class="am-text-danger">'+t.surplus+'</span></span></div>';
				str+='</div></div></li>';
			})
			ul.append(str);
			if(l.length<10){
				$(".list_load").html("已经到最底了");
				listLoading=true;
			}else{
				listPage++;
				listLoading=false;
			}
		}else{
      $(".list_load").html("已经到最底了");
			nAlert(data.errmsg)
		}
	},"json");
}
/**************************************************
 * 商品详情
 *************************************************/

$("#showHide").click(function(){
  $(".result_content").slideToggle();
})