<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>填写订单信息</title>
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/style.css">
</head>
<body>
	<!--header-->
	<header>
		<span class="go_left"></span>
	    <div class="title">填写订单信息</div>
	</header >
	<div class="top"></div>
	<!--header /-->
	<!--订单信息-->
	<form method="post" name="orderInfo">
	{foreach $cartList.products as $i => $cart}
		<input type="hidden" id="prodId" value="{$cart.item_id}">
		<section class="bd_t bd_b">
			<div class="padz10">
				{$cart.item_name}
				<span class="fr" id="price" rel="{$cart.price}">{$cart.price}元</span>
			</div>
			<div class="padz10 bd_t pos_re">
				数量
				<div class="num_view">
					<a href="javascript:;" class="min" id="min">-</a>
					<input type="text" name="num" id="num" value="{$cart.num}">
					<a href="javascript:;" class="add" id="add">+</a>					
				</div>
			</div>
			<div class="padz10 bd_t">
				小计
				<span class="fr"><span id="subtotals">0</span>元</span>
			</div>
		</section>
	{/foreach}
		<div class="blank"></div>
		<!--<section class="bd_t bd_b">
			<div class="padz10 pos_re i_go">
				<a href="#" class="dis">
					优惠券
					<div class="fr gray1 mr15">使用优惠券</div>
				</a>
			</div>
			<div class="padz10 bd_t">
				总价（共1张）
				<div class="fr price"><span id="totalPrice">0</span>元</div>
			</div>
		</section>-->
		<div class="blank"></div>
		<section class="form_view">
			<!--<div class="input_box">
				<label>真实姓名</label>
				<div class="padl100">
		            <input type="text" id="realName" name="realName" placeholder="请输入真实姓名" class="width100">
		        </div>
			</div>
			<div class="input_box">
				<label>身份证号</label>
				<div class="padl100">
		            <input type="number" id="idCard" name="idCard" placeholder="请输入身份证号" class="width100">
		        </div>
			</div>-->
			<div class="input_box">
				<label>手机号码</label>
				<div class="padl100">
		            <input type="number" id="phone" name="mobile" placeholder="请输入手机号码" class="width100">
		        </div>
			</div>
		</section>
		<div class="input_btn">
	        <button type="button" onclick="order.creat();">提交订单</button>
	    </div>
	</form>
	<!--订单信息/-->
	<div class="end"></div>
</body>
</html>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery.alerts.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/cj.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/order.js"></script>