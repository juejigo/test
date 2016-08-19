{include file='public/header_admincp.tpl'}

<script type="text/javascript">
$(function()
{
	var windowHeight = $(window).height();
	var height = windowHeight - 88;
	$('#left-menu').height(height);
	$('#work-ground').height(height);
})
function seturl(url)
{
	$('#iframe').attr('src',url);
}
</script>

<div id="main-content" class="clearfix">
		<div id="main" class="f2"><div id="work-ground">
				<iframe id="iframe" src="/admincp/account" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="auto" allowtransparency="yes"></iframe>
		</div></div>
		
		<!-- 左侧 -->
		<div id="left-uc" class="f1">
				<div id="left-menu" class="clearfix">
						<dl>
								<dt><a class="selected dt" id="home" href="javascript:seturl('/admincp/account');">首页</a></dt>
								
								<dt class="shop"><a class="dt up" href="javascript:;">商品</a></dt>
								<dl class="shop"><a class="dl" href="javascript:seturl('/productcp/product');">商品管理</a></dl>
								<dt class="shop"><a class="dt" href="javascript:;">订单</a></dt>
								<dl class="shop"><a class="dl" href="javascript:javascript:;">订单管理</a></dl>
								<dt class="shop"><a class="dt up" id="service" href="javascript:;">会员</a></dt>
								<dl class="shop"><a class="dl" href="javascript:;">会员管理</a></dl>
								<dt class="site"><a class="dt up" id="service" href="javascript:;">站点设置</a></dt>
								<dl class="site"><a class="dl" href="javascript:;">基本设置</a></dl>
								<!--<dl class="site"><a class="dl" href="javascript:seturl('/admin/admin';">管理员管理</a></dl>-->
								
						</dl>
				</div>
		</div>
		<!-- 左侧 /-->
</div>

{include file='public/footer_admincp.tpl'}