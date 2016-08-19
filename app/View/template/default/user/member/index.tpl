<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=1080, user-scalable=no"/>
    <title>五一享城_会员中心</title>
    <link rel="stylesheet" href="{$smarty.const.URL_MIX}zhanghj/css/base.css"/>
    <link rel="stylesheet" href="{$smarty.const.URL_MIX}zhanghj/css/VIPcenter.css"/>
</head>
<body>
<!--头部内容-->
<header class="vp_header">
    <img src="{if $user->avatar}{$user->avatar}{else}{$smarty.const.URL_IMG}public/avatar.thumb.60.png{/if}" alt=""/>
    <div class="vp_header_info">
        <h6>会员ID：<span>{$user->id}</span></h6>
        <h6>昵称：<span>{$user->member_name}</span></h6>
        <h6>身份：<span>{$user->group_name}</span></h6>
        <h6>所在门店：<span>{$user->seller_name}</span></h6>
        <!--<h6>大使：<span>否</span><a href="#">（点此链接成为大使）</a></h6>-->
    </div>
</header>
<!--/头部内容-->

<!--主要内容-->
<main class="vp_main">
    <ul>
    	{if $user->role == 'seller' || $user->role == 'partner'}
        <li>
            <a href="/user/member/qrcode">
                <i></i>
                <span>我的二维码</span>
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="/user/funds/income">
                <i></i>
                <span>我的业绩</span>
                <span>&gt;</span>
            </a>
        </li>
        <li class="sep">
            <a href="/user/wallet">
                <i></i>
                <span>我的钱包</span>
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="/orderuc/advance/list">
                <i></i>
                <span>我的代付</span>
                <span>&gt;</span>
            </a>
        </li>
        <li class="sep">
            <a href="/funds/funds/post">
                <i></i>
                <span>申请提现</span>
                 <span>&gt;</span>
            </a>
        </li>
        {/if}
        <li>
            <a href="/order/order">
                <i></i>
                <span>我的订单</span>
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="/favorite/favorite">
                <i></i>
                <span>我的收藏</span>
                <span>&gt;</span>
            </a>
        </li>
        <li class="sep">
            <a href="/consignee/consignee/list">
                <i></i>
                <span>我的地址</span>
                <span>&gt;</span>
            </a>
        </li>
        <li>
            <a href="tel:4008267122">
                <i></i>
                <span>联系客服</span>
                <span>&gt;</span>
            </a>
        </li>
    </ul>
</main>
<!--/主要内容-->

<!--底部导航-->
<footer class="vp_footer">
{if $user->role == 'seller'}
		<ul class="clearfix">
	        <li>
	            <a href="/">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav1.jpg" alt=""/>
	                首页
	            </a>
	        </li>
	        <li>
	            <a href="/user/member">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav2.jpg" alt=""/>
	                个人中心
	            </a>
	        </li>
	        <li>
	            <a href="/user/funds/income">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav3.jpg" alt=""/>
	                我的业绩
	            </a>
	        </li>
	        <li>
	            <a href="/user/member/qrcode">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav4.jpg" alt=""/>
	                我的二维码
	            </a>
	        </li>
	        <li>
	            <a href="http://mp.weixin.qq.com/s?__biz=MzA5Njc5MzQ1MA==&mid=401612402&idx=1&sn=9069bbb46df79fbc8ff516786fe4c249#rd">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav5.jpg" alt=""/>
	                客服
	            </a>
	        </li>
	    </ul>
{else}
        <ul class="clearfix">
	        <li>
	            <a href="/">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav1.jpg" alt=""/>
	                首页
	            </a>
	        </li>
	        <li>
	            <a href="/user/member">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav2.jpg" alt=""/>
	                个人中心
	            </a>
	        </li>
	        <li>
	            <a href="/cart/cart/list">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav6.jpg" alt=""/>
	                购物车
	            </a>
	        </li>
	        <li>
	            <a href="/order/order/list">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav7.jpg" alt=""/>
	                订单
	            </a>
	        </li>
	        <li>
	            <a href="http://mp.weixin.qq.com/s?__biz=MzA5Njc5MzQ1MA==&mid=401612402&idx=1&sn=9069bbb46df79fbc8ff516786fe4c249#rd">
	                <img src="{$smarty.const.URL_MIX}zhanghj/img/vp_nav5.jpg" alt=""/>
	                客服
	            </a>
	        </li>
	    </ul>
{/if}
</footer>
<!--/底部导航-->
</body>
</html>