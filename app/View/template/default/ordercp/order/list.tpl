 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}ordercp/order/order.css" rel="stylesheet" type="text/css"/>
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">订单 <small>查看和管理订单</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/ordercp/order/list">订单</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th width="20%">订单号</th>
								<th width="30%">标题</th>
				                <th width="15%">联系人</th>
				                <th width="15%">联系号码</th>
								<th width="15%">状态</th>
								<th >操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input type="text" class="form-control" name="id" value="{$params.id}"></td>
								<td><input type="text" class="form-control" name="subject" value="{$params.subject}"></td>
				                <td><input type="text" class="form-control" name="buyer_name" value="{$params.buyer_name}"></td>
				                <td><input type="text" class="form-control" name="mobile" value="{$params.mobile}"></td>
								<td>
									<select class="form-control" name="status">
					                    <option value="">全部</option>
					                    {foreach $status_array as $row}
					                    <option value="{$row.value}" {if $params.status == $row.value} selected="selected"  {/if}>{$row.name}</option>
					                    {/foreach}
									</select>
								</td>
								<td>
									<div class="margin-bottom-5">
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
					<div class="portlet-body">
						<!--表格-->
						<div class="dataTables_wrapper no-footer">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="10%">订单号</th>
											<th width="25%">标题</th>
											<th width="10%">下单时间</th>
											<th width="5%">支付金额</th>
                      <th width="10%">联系人</th>
                      <th width="10%">供应商</th>
                      <th width="5%">状态</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									
										{foreach $orderList as $data}
										<tr data-id="{$data.id}" class="gradeX odd" role="row">
											<td>{$data.id}</td>
											<td>{$data.subject}</td>
											<td>{date("Y-m-d H:i:s",$data.dateline)}</td>
											<td>￥{$data.pay_amount}</td>
						                      <td class="userNote">
						                        <p>{$data.buyer_name}</p>
						                        <p>{$data.mobile}</p>
						                        <a href="javascript:;" class="tooltips" data-container="body" data-original-title="用户备注" data-info="{$data.memo}"><i class="fa fa-file-text"></i></a>
						                      </td>
											<td>
											  <p>{$data.supplier_name}</p>
											  <p>{$data.supplier_telephone}</p>
											</td>
						                      <td>
						                      {if $data.status == 0}
						                        <span class="label label-danger">待付款</span>
					                         {else if $data.status == 1}
					                         <span class="label label-warning">待确认</span>
					                         {else if $data.status == 2}
					                         <span class="label label-success">待出行</span>
					                         {else if $data.status == 3}
					                         <span class="label label-info">已完成</span>
					                         {else if $data.status == 13}
					                         <span class="label label-default">已退款</span>
					                         {else if $data.status == -1}
					                         <span class="label label-default">已关闭</span>
					                         {/if}
						                      </td>
											<td>
											{if $data.status == 1}
											<a href="javascript:;" class="right btn yellow btn-sm"><i class="fa fa-check"></i> 确认</a>
                        					<a href="javascript:;" class="refund btn red btn-sm"><i class="fa fa-money"></i> 退款</a>
											{/if}
												<a href="/ordercp/order/detail?id={$data.id}" class="btn green btn-sm"><i class="fa fa-search"></i> 查看</a>
                       							 <a href="javascript:;" class="remarks btn default btn-sm"><i class="fa fa-pencil-square-o"></i> 备注
                       							 {if  $data.admin_memo != ""}
                       							 <i class="t"></i>
                       							 {/if}
                       							 </a>

											</td>
										</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
						</div>
						<!--分页-->
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap_full_number pull-right">
											{$pagebar}
								</div>
							</div>
						</div>
						<!--分页 /-->
					</div>
				</div>
			</div>
			<!--表格结束 /-->
		</div>
	</div>
	<!--content-wrapper /-->
	
	<!--用户备注-->
<div class="modal fade" id="userNote" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">用户备注</h4>
			</div>
			<div class="modal-body" style="min-height:200px;"></div>
		</div>
	</div>
</div>
<!--用户备注 /-->
<!--后台备注-->
<div class="modal fade" id="remarks" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
    <form>
  		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  				<h4 class="modal-title">后台备注</h4>
  			</div>
  			<div class="modal-body" style="min-height:200px;">
          <input type="hidden" name="id" value="">
  			  <textarea cols="30" rows="10" name="remarks" class="form-control"></textarea>
  			</div>
        <div class="modal-footer">
          <button type="button" class="btn default" data-dismiss="modal">取消</button>
  				<button type="button" class="btn green sub">保存</button>
  			</div>
  		</div>
    </form>
	</div>
</div>
<!--后台备注 /-->

{include file='public/admincp/footer.tpl'}
