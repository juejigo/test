 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<link href="{$smarty.const.URL_WEB}css/admincp/index/style.css" rel="stylesheet"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">刮卡记录 <small>查看和管理刮卡记录</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/scrathcp/scrath/list">刮刮卡列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/scrathcp/card/list?scrath_id={$params.scrath_id}">刮卡记录</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="/scrathcp/card/list" id="search" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="180px">微信名</th>
								<th class="sorting_asc" width="180px">奖项标题</th>
								<th class="sorting_asc" width="180px">奖项等级</th>
								<th class="sorting_asc" width="180px">是否中奖</th>
								<th class="sorting_asc">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input type="search" class="form-control" placeholder="微信名" name="member_name" aria-controls="sample_1" value="{$params.member_name}">
								</td>
								
								<td>
									<input type="search" class="form-control" placeholder="奖项标题" name="product_name" aria-controls="sample_1" value="{$params.product_name}">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="奖项等级" name="product_level" aria-controls="sample_1" value="{$params.product_level}">
								</td>
								<td>
									<select class="form-control" name="is_prize">
										<option value="1" {if $params.is_prize == 1}selected="selected"{/if}>已中奖</option>
										<option value="0"{if $params.is_prize === '0'}selected="selected"{/if}>未中奖</option>
									</select>
								</td>
								<td>
									<div class="margin-bottom-5">
										<input type="hidden" name="scrath_id" value="{$params.scrath_id}" />
										<button class="btn btn-sm yellow filter-submit margin-bottom" type="submit"><i class="fa fa-search"></i> 搜索</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
			<!--搜索栏 /-->

			<!--表格列表-->
			<div class="row">
				<div class="col-md-12">
					<!--表格-->
					<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
								<thead>
									<tr role="row">
										<th width="50">ID</th>
										<th width="150">订单号</th>
										<th width="150">微信名</th>
										<th width="400">奖项</th>
										<th width="150">发卡时间</th>
										<th width="150">刮卡时间</th>
										<th width="200">中奖码</th>
										<th>刮卡记录</th>
									</tr>
								</thead>
								<tbody>
								{foreach $cardList as $card}
									<tr  data-id="{$card.id}" class="gradeX" role="row"> 
										<td>{$card.id}</td>
										<td>{$card.order_sn}</td>
										<td>{$card.member_name}</td>
										<td {if $card.is_deliver == 1}class="out"{/if}>{if $card.is_prize == 1}
											<img src="{$card.image}" width="60" height="60">
											{$card.product_level}：{$card.product_name}{else}谢谢惠顾{/if}
										</td>
										<td>{$card.add_time}</td>
										<td>{$card.use_time}</td>
										<td>{$card.redeem_code}</td>
										
										<td   data-config='{literal}{{/literal}"id":"{$card.z_id}","consignee":"{$card.consignee}","phone":{$card.phone},"address":"{$card.address}","invoice_number":"{$card.invoice_number}","note":"{$card.note}","prize_time":"{$card.prize_time}","express":"{$card.express}"{literal}}{/literal}'>
										{if $card.is_prize == 1}
										{if $card.is_deliver == 1}
											<a href="javascript:;" class="btn default details" >查看</a>
										{else}
											 <a href="javascript:;" class="btn green send_out">发放 <i class="icon-present"></i></a>  
										{/if}
										{/if}
											<a href="javascript:;" class="btn red delete">删除</a>
										</td>
									</tr>
								{/foreach}
									<tr>
										<td colspan="10">{$pagebar}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!--表格结束 /-->
		</div>
	</div>
	<!--content-wrapper /-->
</div>
<!-- container /-->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2014 &copy; Metronic by keenthemes.
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->



<!-- 查看信息 -->
<div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		    <h4 class="modal-title" id="myModalLabel">发货信息</h4>
		  </div>
		  <div class="modal-body send_img">
		  	
		  </div>
	</div>
</div>
<!-- 查看信息 /-->
{include file='public/admincp/footer.tpl'}
{literal}
<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	//添加日期选择功能 
    $(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true,
    });
});
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.post('/scrathcp/card/ajax?op=delete',{id:id},function(data){
			if (data.errno == 0){
				tr.remove();
				return;
			}else{
				alert(data.errmsg);
			}
		})
	}
});
//删除
$('.send_out').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确认兑换？')){
		$.post('/scrathcp/card/ajax?op=deliver',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
				return;
			}else{
				alert(data.errmsg);
			}
		})
	}
});

//查看
$(".details").click(function(){
	var data=$(this).parent().data('config'),str='';
	str+='<p>快递公司：'+data.express+'</p>';
  	str+='<p>快递单号：'+data.invoice_number+'</p>';
  	str+='<p>真实姓名：'+data.consignee+'</p>';
  	str+='<p>手机号码：'+data.phone+'</p>';
  	str+='<p>收货地址：'+data.address+'</p>';
  	str+='<p>备注内容：'+data.note+'</p>';
  	str+='<p>时间：'+data.prize_time+'</p>';
    $("#details .modal-body").html(str);
	$("#details").modal('show');
})
</script>
{/literal}
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
