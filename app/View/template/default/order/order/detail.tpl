{include file='public/fr/headeruser.tpl'}
  <!--订单详情-->
  <div class="main">
    <!--面包屑-->
    <div class="page_bar">
      <ul class="page_breadcrumb">
				<li><a href="/index">网站首页</a><i class="page_right">></i></li>
				<li><a href="/order/order/list">我的订单</a><i class="page_right">></i></li>
				<li><a href="/order/order/detail?id={$order.id}">订单详情</a></li>
			</ul>
    </div>
    <!--面包屑 /-->
    <div class="order_details">
      <div class="order_line">
        <h2>当前订单状态：{$order.memo}</h2>
       
      </div>
      <div class="order_line">
        <h2>订单信息</h2>
        <div class="order_info">
          <p>
            <span>订单编号：{$order.id}</span>
            <span>下单时间：{date("Y-m-d",$order.dateline)}</span>
            <span>出发时间：{date("Y-m-d",$order.travel_date)}</span>
          </p>
        </div>
        <table class="order_info_table">
          <thead>
            <tr>
              <th width="60%">产品信息</th>
              <th width="20%">人数</th>
              <th width="20%">价格</th>
            </tr>
          </thead>
          <tbody>
          {foreach $order.items as $row}
            <tr>
              <td>
                <div class="order_name">
                  <img src="{$row.product_image}">
                  <div class="name"><a href="/product/product/detail?id={$row.product_id}">{$row.product_name}</a></div>
                </div>
              </td>
              <td class="tc">{$row.product_items}</td>
              <td class="tc">￥{$row.price}</td>
            </tr>
            {/foreach}
          </tbody>
        </table>
      </div>
      <div class="order_line">
        <h2>旅客信息</h2>
        <table class="order_info_table">
          <thead>
            <tr>
              <th width="15%">旅客姓名</th>
              <th width="20%">手机号码</th>
              <th width="10%">性别</th>
              <th width="15%">证件类型</th>
              <th width="20%">证件号码</th>
              <th width="20%">证件有效期</th>
            </tr>
          </thead>
          <tbody>
          {foreach $order.tourist as $row}
            <tr>
              <td class="tc">{$row.tourist_name}</td>
              <td class="tc">{$row.mobile}</td>
              <td class="tc">{if $row.sex == 1}男{else}女{/if}</td>
              <td class="tc">{if $row.cert_type == 1}身份证{else}护照{/if}</td>
              <td class="tc">{$row.cert_num}</td>
              <td class="tc">{date("Y-m-d",$row.cert_deadline)}</td>
            </tr>
            {/foreach}
          </tbody>
        </table>
      </div>
      {if $order.from != 1}
      {if $order.status == 0}
      <div class="order_pay_box">
        应付金额：<span class="ycolor">￥{$order.pay_amount}</span>
        <a href="/order/order/pay?id={$order.id}" class="btn btn_ycolor btn_raidus order_list_btn">立即付款</a>
      </div>
      {/if}
      {/if}
    </div>
  </div>
  <!--订单详情 /-->
   {include file='public/fr/footer.tpl'}
