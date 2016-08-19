{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">期数详情 <small>一元夺宝期数详情</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/list">一元夺宝</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/list">期数列表</a><i class="fa fa-angle-right"></i></li>
          			<li><a href="/onecp/phase/detail?id={$params.id}">期数详情</a></i></li>
				</ul>
			</div>
      <div class="portlet">
  			<div class="portlet-title">
  				<div class="caption">
  					<i class="fa fa-edit"></i>期数详情
  				</div>
  				<div class="pull-right">
  					<a href="/onecp/phase/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
  				</div>
  			</div>
  		</div>
      <div class="row">
      <div class="col-md-12">
        <table id="user" class="table table-bordered table-striped">
          <tbody>
            <tr>
              <td style="width:15%">期数状态</td>
              <td style="width:85%">
	          {if $phase.status == 0}
	          <span class="label label-warning">待上架</span>
              {else if $phase.status == 1}
              <span class="label label-danger">进行中</span>
              {else if $phase.status == 2}
              <span class="label label-info">待揭晓</span>
              {else if $phase.status == 3}
              <span class="label label-success">已揭晓</span>
              {else if $phase.status == 4}
              <span class="label lable-default">已到期</span>
              {/if}
              </td>
            </tr>
            <tr>
              <td>期数ID</td>
              <td>{$phase.id}</td>
            </tr>
            <tr>
              <td>对应商品ID</td>
              <td>{$phase.product_id}</td>
            </tr>
            <tr>
              <td>期数</td>
              <td>{$phase.no}</td>
            </tr>
            <tr>
              <td>商品名称</td>
              <td>{$phase.product_name}</td>
            </tr>
            <tr>
              <td>商品图片</td>
              <td><img src="{$phase.image}" width="70" height="70"></td>
            </tr>
            <tr>
              <td>商品价格</td>
              <td>￥{$phase.product_price}</td>
            </tr>
            <tr>
              <td>夺宝单价</td>
              <td>￥{$phase.price}</td>
            </tr>
            <tr>
              <td>中奖用户ID</td>
              <td>{$phase.winner_id}</td>
            </tr>
            <tr>
              <td>总需人数</td>
              <td>{$phase.need_num}人</td>
            </tr>
            <tr>
              <td>现在人数</td>
              <td>{$phase.now_num}人</td>
            </tr>
            <tr>
              <td>限购次数</td>
              <td>{$phase.limit_num}次</td>
            </tr>
            <tr>
              <td>幸运号码</td>
              <td>{$phase.lucky_num}</td>
            </tr>
            <tr>
              <td>彩票号码</td>
              <td>{$phase.lottery_num}</td>
            </tr>
            <tr>
              <td>50个时间戳之和</td>
              <td>{$phase.salt}</td>
            </tr>
            <tr>
              <td>创建时间</td>
              <td>{date("Y-m-d H:i:s",$phase.dateline)}</td>
            </tr>
            <tr>
              <td>开始时间</td>
              <td>{if $phase.start_time == 0}自动开始{else}{date("Y-m-d H:i:s",$phase.start_time)}{/if}</td>
            </tr>
            <tr>
              <td>到期时间</td>
              <td>{if $phase.end_time == 0}无限期{else}{date("Y-m-d H:i:s",$phase.end_time)}{/if}</td>
            </tr>
            <tr>
              <td>满人时间</td>
              <td>{date("Y-m-d H:i:s",$phase.over_time)}</td>
            </tr>
            <tr>
              <td>揭晓时间</td>
              <td>{date("Y-m-d H:i:s",$phase.lottery_time)}</td>
            </tr>
          </tbody>
        </table>
      </div>
      </div>
		</div>
	</div>
	<!--content-wrapper /-->
</div>
<!-- container /-->
{include file='public/admincp/footer.tpl'}
</body>
<!-- END BODY -->
</html>
