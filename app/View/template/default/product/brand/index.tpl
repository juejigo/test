{include file='public/fr/header.mobile.tpl'}
<link type="text/css" href="{$smarty.const.URL_WEB}webapp/css/imgbox.css" rel="stylesheet"/>
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/jquery.event.drag-1.5.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/jquery.touchSlider.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".main_visual").hover(function(){
	$("#btn_prev,#btn_next").fadeIn()
	},function(){
		$("#btn_prev,#btn_next").fadeOut()
	});
	
	$dragBln = false;
	
	$(".main_image").touchSlider({
		flexible : true,
		speed : 200,
		btn_prev : $("#btn_prev"),
		btn_next : $("#btn_next"),
		paging : $(".flicking_con a"),
		counter : function (e){
			$(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
		}
	});
	
	$(".main_image").bind("mousedown", function() {
		$dragBln = false;
	});
	
	$(".main_image").bind("dragstart", function() {
		$dragBln = true;
	});
	
	$(".main_image a").click(function(){
		if($dragBln) {
			return false;
		}
	});
	
	timer = setInterval(function(){
		$("#btn_next").click();
	}, 5000);
	
	$(".main_visual").hover(function(){
		clearInterval(timer);
	},function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		},5000);
	});
	
	$(".main_image").bind("touchstart",function(){
		clearInterval(timer);
	}).bind("touchend", function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		}, 5000);
	});
	var imgwidth = $(".main_visual").width();
	$(".main_image img").width(imgwidth);
    $(".main_image img").height(imgwidth/2.2);
	$(".main_visual").height(imgwidth/2.2);
	$(".main_image").height(imgwidth/2.2);
	$(".main_image li").height(imgwidth/2.2);
});
</script>

<div class="menu2">
   <a class="back2" href="javascript:history.go(-1);"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">品牌区</div>
</div>
<!--图片展示开始-->
<div class="ppq_show">
    <div class="main_visual">
        <div class="flicking_con">
			{foreach $positions[13] as $i => $position}
			<a href="#">{$i}</a>
			{/foreach}
        </div>
        <div class="main_image">
            <ul>
				{foreach $positions[13] as $i => $position}
				<li><a href="{$position.url}"><img src="{$position.image}"></a></li>
				{/foreach}
            </ul>
        </div>
    </div>
    <div class="clear"></div>
</div>
<!--图片展示开始结束-->
{foreach $positions[14] as $i => $position}
<div class="ad bbb {if $i==0}mgt15{/if}"><a href="{$position.url}"><img src="{$position.image}" width="100%"></a></div>
{/foreach}
{include file='public/fr/footer.mobile.tpl'}