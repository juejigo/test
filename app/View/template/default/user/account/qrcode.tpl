{include file='public/fr/header.tpl'}
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<div class="nav"><a href="http://weixin.cwise.cn/index.php?g=Wap&m=Index&a=index&token=lavasz1427965819"></a>我的二维码</div>

{if !empty($params.account)}
<div class="content" style="text-align:center;margin-top:20px;margin-bottom:20px;">
    <img src="{$qrcodeUrl}" />
    <div class="r_prompt">
    <p><span>点击长按二维码</span>，在弹出的对话框中点<span>识别图中二维码</span>并注册。</p>
    </div> 
    <div class="r_note">
    <strong>{$smarty.const.SITE_NAME}：</strong>
    <p>1.注册会员，<span>送鞋一双</span>，款式自选，快递自付25元，<span>领取25元红包</span>一个。</p>
    <p>2.分享传播可以享受传播会员消费<span>4%+4%的直接和间接收益</span>，市级代理拥有这个城市<span>所有会员消费的收益</span>。</p>
    <p>3.不用装修、租金、人工、铺货、库存，{$smarty.const.SITE_NAME}专业、系统的做好了，你<span>只要一部智能手机</span>，只要捕捉住商机，机会就是你的。</p><br /><br /></div>
    <div class="r_prompt">
    <p>我们的宗旨：让每一个人都很舒服</p>
    <p>我们的目标：鞋管家 真朋友 美生活</p>
    <p>我们的价值观：创新、分享、服务、共赢</p>
    <p><span><strong>温州美尚美鞋网络有限公司  服务热线：4009267858</strong></span></p>
    </div> 
</div>
{else}
<div class="content" style="margin-top:20px;margin-bottom:20px;">
<form action="/user/account/qrcode" method="GET">
    <div class="r_tab">
       <ul>
          <li><span>手机号</span><input name="account" placeholder="请输入手机号" type="text" value="" class="r_txt" /></li>
       </ul>
    </div>
    <div class="r_button"><input type="submit" class="r_b" value="获得二维码" /></div>
</form>
</div>
{/if}

{include file='public/fr/footer.tpl'}