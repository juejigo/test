"use strict";
setTimeout(function(){
	getList();
},SETTIME)
var listLoading = false;    //是否可以加在标志
var listPage=1;    //列表页码
var ul=$("#orderList");		//列表
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
//点击条件改变列表
$(document).on("click",".type_btn",function(){
	var _this=$(this);
	if(!_this.hasClass("checked")){
		$(".type_btn").removeClass("checked");
		_this.addClass("checked");
		setGetList();
		return false;
	}
})
/**
 * 初始化，并加载列表
 */
function setGetList(){
	ul.html('');
	listPage=1;
	listLoading=true;
	$(".list_load").html('<span class="mui-spinner"></span>');
	setTimeout(function(){
		getList();
	},SETTIME)
}
/**
 * 查看夺宝号
 * @param  {[type]} '#showNumBox' [description]
 * @return {[type]}               [description]
 */
$(document).on('click','.see_num', function() {
  var _this=$(this);
  var id=_this.data("id"),
      arr=_this.data("arr"),
      acount=_this.data("acount");
  $("#modalId").html('第 '+id+' 期');
  $("#modalArr").html('<p class="am-text-xs">本期参与'+acount+'次，夺宝号码：</p><p>'+arr+'</p>');
  $('#numBox').modal({
   relatedTarget: this,
  });
});
/**
 * 获取列表
 * @return {[type]} [description]
 */
function getList() {
	var type=$("#typeNav").find(".checked").data("type");
	$.post("/oneuc/order/ajax?op=orderlist",{type:type,page:listPage},function(data){
		if(data.errno==0){
			var str='';
			var l=data.list;
			$.each(l,function(i,t){
				str+='<li class="flex-wrap">';
				str+='<a href="/one/phase/detail?id='+t.phase_id+'"><img src="'+t.img+'"></a>';
				str+='<div class="flex-con">';
				str+='<p class="title">'+t.title+'</p>';
				str+='<p class="am-text-xs">期数：'+t.no+'</p>';
				str+='<p class="am-text-xs">本期参与：<span class="zcolor">'+t.acount+'</span>人次</p>';
				if(t.status==1){
		      str+='<p class="am-text-xs">总需：'+t.need_num+'</p>';
		      str+='<div class="am-progress am-progress-striped am-progress-xs am-active"><div class="am-progress-bar am-progress-bar-danger" style="width: '+t.schedule+'%"></div></div>';

		      str+='</div>';
		      str+='<div class="btn">';
		      str+='<a class="am-btn am-btn-danger am-radius" href="/one/phase/detail?id='+t.phase_id+'">追加</a>';
				}else if(t.status==2){
					str+='<p class="am-text-xs">总需：'+t.need_num+'</p>';
					str+='<div class="am-progress am-progress-striped am-progress-xs am-active"><div class="am-progress-bar am-progress-bar-danger" style="width: '+t.schedule+'%"></div></div>';

					str+='</div>';
					str+='<div class="btn">';
				}else if(t.status==3){
					str+='<p class="am-text-xss">获奖者：<span class="zcolor">'+t.name+'</span>(本期参与：<span class="zcolor">'+t.num+'</span>人次)</p>';
					str+='<p class="am-text-xss">幸运号码：<span class="zcolor">'+t.lucky+'</span></p>';
					str+='<p class="am-text-xss">揭晓时间：'+t.time+'</p>';
					str+='</div>';
					str+='<div class="btn">';
				}
				str+='<a class="no_btn see_num" href="javascript:;" data-id="'+t.no+'" data-acount="'+t.acount+'" data-arr="'+t.arr_num+'">查看号码</a></div></li>';
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
