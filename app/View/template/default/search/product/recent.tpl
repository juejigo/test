{include file='public/fr/header.mobile.tpl'}
<link rel="stylesheet" rev="stylesheet" href="{$smarty.const.URL_WEB}webapp/css/imgbox.css">

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
	
});
</script>
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/search.js"></script>
<div class="search">
   <div class="sea_txt"><input type="text" value="请输入搜索词" class="ss_txt"  name="kw" id="kw" onclick="$(this).val('');"></div>
   <div class="sea_btn"><input type="button" value="搜索" class="s_btn" onclick="search();"></div>
</div>
<div class="sea_show">
    <div class="sea_tt">
       <ul>
         <li><a href="/search/product/top10">热门搜索</a></li>
         <li class="cur"><a href="/search/product/recent">最近搜索</a></li>
       </ul>
    </div>
    <div class="clear"></div>
    <div class="main_visual">
        <div class="flicking_con">
			<!--
            <a href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
			-->
        </div>
        <div class="main_image">
            <ul>
                <li>
				  {foreach $search_wds as $i => $top}
				  <span><a href="/product/product/list?kw={$top}">{$top}</a></span>
				  {/foreach}
                </li>
            </ul>
        </div>
      </div>
</div>
{include file='public/fr/footer.mobile.tpl'}