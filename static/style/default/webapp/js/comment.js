"use strict";
$(function(){
	comList();
})
//发表评论
var comment=$("#comment");
$(".comment_post span").click(function(){
	if(!comment.val()){
		alert("请输入评论内容！");
		return false;
	}
	$.ajax({
		url:"/vote/vote/ajax?op=comment",
		data: {
			vote_id:VOTEID,
			player_id:playerId,
        	comment:comment.val()
        }
	}).done(function(data){
        if (data.errno == 0) {
        	alert("评论成功");
        	setTimeout(function(){
        		location.reload();
        	},1000);
        }else if(data.errno == 2){
        	follow();
        }else{
        	alert(data.errmsg);
        }
    });
})
//获取焦点
$(".comment_fixed").click(function(){
	comment.focus();
})

//加载列表
var btn=$(".ajax_btn");
btn.click(function(){
	comList();
})
var playerId=getQueryString("id"),		//获取选手编号
	page=$("#page"),
	comLi=$("#comLi"),
	surplus=$("#surplus"),
	sum=$("#sum");
function comList(){
	$.ajax({
		url:"/vote/vote/ajax?op=commentlist",
		data:{
			vote_id:VOTEID,
			player_id:playerId,
			page:page.val(),
		}
	}).done(function(data){
		if(data.errno == 0){
			var str="";
			$.each(data.data,function(i,d){
				str+='<li>';
				str+='<img src="'+d.avatar+'" width="100%" />';
				str+='<div class="info">';
				str+='<p class="name">'+d.name+'</p>';
				str+='<p class="time">'+conversionFun(d.dateline)+'</p>';
				str+='<p class="details">'+d.comment+'</p>';
				str+='</div>';
				str+='</li>';
			})
			surplus.text(data.surplus);
			sum.text(data.sum);
			comLi.append($(str).hide().fadeIn(600));
			page.val(data.PageIndex);
			if(data.none == 1)
			{
				btn.remove();
				comLi.after('<div class="s_loading">'+data.errmsg+'</div>');
			}
		}else{
			btn.remove();
			comLi.after('<div class="s_loading">'+data.errmsg+'</div>');
		}
	})
}
//转换时间戳
function conversionFun(time){
	var min = 60,		//1分钟
		hour = min * 60,		//1小时
		day = hour * 24,		//1天
		month = day * 30;		//1个月
	function conversion(time){
		var now = Math.round(new Date().getTime()/1000),		//当前时间
			dif=now-time,		//时间差
			monthD=dif/month,	
			weekD=dif/(7*day),
			dayD=dif/day,
			hourD=dif/hour,
			minD=dif/min;
		if(monthD>=1){
			return parseInt(monthD)+"个月前";
		}else if(weekD>=1){
			return parseInt(weekD)+"个星期前";
		}else if(dayD>=1){
			return parseInt(dayD)+"天前";
		}else if(hourD>=1){
			return parseInt(hourD)+"小时前";
		}else if(minD>=1){
			return parseInt(minD)+"分钟前";
		}else{
			return "刚刚";
		}
	}
	return conversion(time);
}
