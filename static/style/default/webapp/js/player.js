"use strict";
$(function(){
	if(playerList.length>0){
		ajaxList(1);
		$(document).on('scroll', function(){
			if (!listLoading){
				loadMore();
			}
			$(this).scrollTop()>0?goTop.fadeIn(500):goTop.fadeOut(300);
		})
	}
})
//返回顶部
var goTop=$('#goTop');
goTop.click(function(){
	$('html,body').animate({scrollTop:'0px'},200);
})
//选手列表
var pList=$("div.dis_n"),
	ptype=$(".player_type").find("span");
ptype.each(function(i,k){
	$(k).click(function(){
		var type=$(this).data("type");
		if(!$(this).hasClass("check")){
			ptype.removeClass("check");
			$(this).addClass("check");
		}
		pList.hide();
		$('div.'+type+'').show();
	})
})

var votesTo=function(playerId){
	var num=$('.num_'+playerId+'');
	$.ajax({
		url:"/vote/vote/ajax?op=vote",
		data: {
			vote_id:VOTEID,
        	player_id:playerId
        	
        }
	}).done(function(data){
        if (data.errno == 0) {
        	successTip("votes_to","投票成功",data.num,1500,function(){
        		num.text(data.vote_num);
        	});
        }else if(data.errno == 2)
        {
        	follow();
        }
        else {
        	alert(data.errmsg);
        }
    });
}
var listLoading = false;
var playerList=$('.player_list'),
	playerListUlA=$('.player_list .t ul').eq(0),
	playerListUlB=$('.player_list .t ul').eq(1),
	playerListUl=$('.player_list .o ul'),
	page=$("#page");

function loadMore(){
	var	bheight=$("body").height(),
		bsTop=$("body").scrollTop();
	//console.log(wHeight,body.scrollTop(),body.height());
	if ( WHEIGHT + bsTop >= bheight - 60 ) { 
		listLoading = true;
		playerList.append('<div class="s_loading">加载中...</div>');
		ajaxList();
	} 
}

//关注遮罩
 function follow(){
 	var str='<div class="no_follow_b"><div class="box"><div class="title">关注我们</div><div class="msg">您需要关注我们，才能进行投票操作！</div><div class="btn"><a style="display:block;" href="http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=402572104&idx=1&sn=b67092e6b467ecac57d44281945ffea0&scene=0&previewkey=sBQYSRJtvpGj9W74NWZio8wqSljwj2bfCUaCyDofEow%3D#wechat_redirect">关注</a></div></div></div>';
 	$("body").append(str);
 	setTimeout(function(){
         $(".no_follow_b").animate({opacity : 0},500,function(){
             $(".no_follow_b").remove();
         })
     },1000)
 }