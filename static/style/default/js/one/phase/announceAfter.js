"use strict";
$(function() {
   setTimeout(function(){
 		getList();
 	},SETTIME)
});
var listPage=1;    //往期揭晓页码
var listLoading = false;    //是否可以加在标志
var ul=$("#announceList");		//列表
var prodId=getUrl("id");    //商品ID
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
	$.post("/one/phase/ajax?op=winrecord",{id:prodId,page:listPage},function(data){
		if(data.errno==0){
			var str='';
			var l=data.list;
			$.each(l,function(i,t){
        str+='<li class="bd_b bd_t">';
        str+='<div class="title aui-list-view-cell bd_b"><a href="/one/phase/detail?id='+t.id+'" class="aui-arrow-right">第'+t.no+'期 揭晓时间：'+t.time+'</a></div>';
        str+='<div class="info flex-wrap">';
        str+='<img src="'+t.img+'">';
        str+='<div class="flex-con">';
        str+='<p class="am-text-sm">获奖者：'+t.name+'</p>';
        str+='<p>ip地址：'+t.ip+'</p>';
        str+='<p>幸运号码：<span class="zcolor">'+t.lucky+'</span></p>';
        str+='<p>本期参与：<span class="zcolor">'+t.num+'</span>人次</p>';
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
