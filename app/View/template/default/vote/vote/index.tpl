{include file="public/vote/header.tpl"}
<input type="hidden" id="search" value="{$search}"/>
<body class="pb_80">
<!--投票banner图-->
<section class="banner">
	<div id="slider" class="swipe" style="visibility:visible;">  
	    <div class="swipe-wrap">  
	        <figure><div><a href=""><img src="{$voteinfo.image}" width="100%"/></a></div></figure>
	    </div>  
	</div>
<!-- 	<script src="{$smarty.const.URL_WEB}webapp/js/vender/swipe.js"></script> -->
<!--	<script>
	var slider =new Swipe(document.getElementById('slider'), {
	    auto: 3000,  
	    continuous: true
	});
	</script>-->
	<form action="/vote/vote" method="get">
		<div class="search_bg">
			<div class="search">
			    <input type="hidden" name='vote_id' value="{$voteinfo.id}"/>
				<input type="text" name="search" placeholder="请输入选手名称或者编号进行搜索" value="" />
				<input type="submit" value=""/>
			</div>
		</div>
	</form>	
</section>

<!--投票banner图 /-->	
<!--数据量-->
<section class="data_show">
	<ul>
		<li>{$voteinfo.player_num}<p>参与选手</p></li>
		<li>{$voteinfo.vote_num}<p>累计投票</p></li>
		<li>{$voteinfo.view_count}<p>访问量</p></li>
	</ul>
</section>
<!--数据量 /-->
<!--改变列表形式-->
<section class="player_type">
	<span class="o check" data-type="o"></span>
	<span class="t" data-type="t"></span>
</section>
<!--改变列表形式 /-->
<!--列表-->
<section class="player_list o">
	<div class="palyerbg"></div>
	<input type="hidden" id="page" value="1"/>
	<div class="t dis_n">
		<ul></ul>
		<ul></ul>
	</div>
	<div class="o dis_n" style="display: block;">
		<ul></ul>
	</div>
</section>
<i class="go_top" id="goTop"></i>
<!--列表 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/player.js"></script>
{literal}
<script>
 function ajaxList(pageReload){
	 var pageReload=pageReload?1:page.val();
 	$.post('/vote/vote/ajax?op=list',{search:$("#search").val(),vote_id:VOTEID,page:pageReload}).done(function(data){
		if(data.errno == 0){
			var strO="",j=1;
			$.each(data.data,function(i,d){
				var str='';
				str+='<li>';
				str+='<a href="/vote/player/info?id='+d.id+'&vote_id='+data.vote_id+'"><div class="img"><i>'+d.player_num+'号</i><img src="'+d.image+'" width="100%" /></div></a>';
				str+='<div class="info">';
				str+='<span class="name">'+d.name+'</span>';
				str+='<span class="votes"><b class="num_'+d.id+'">'+d.vote_num+'</b>票</span>';
				str+='<div class="manifesto"><span>参赛宣言</span>'+d.declaration+'</div>';
				str+='<div class="vote_btn_box"><span class="vote_btn" onclick="votesTo('+d.id+')">{/literal}{$voteinfo.vote_btn}{literal}</span></div>';
				str+='</div>';
				str+='</li>';
				if(j%2 ==0){
					playerListUlB.append(str);
				}else{
					playerListUlA.append(str);
				}
				strO+=str;
				j++;

			})
			playerListUl.append(strO);
			page.val(data.PageIndex);
			playerList.find(".s_loading").remove();
			listLoading = false;
		}else if(data.errno == 2)
		{
			window.location=data.url
		}
		else{
			playerList.find(".s_loading").remove();
			playerList.append('<div class="s_loading">'+data.errmsg+'</div>');
		}
	});
 }
</script>
{/literal}