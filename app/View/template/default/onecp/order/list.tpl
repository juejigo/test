 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}ordercp/order/order.css" rel="stylesheet" type="text/css"/>
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">夺宝订单 <small>查看和管理夺宝订单</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/list">一元夺宝</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/order/list">夺宝订单</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th width="15%">订单号</th>
								<th width="10%">期数ID</th>
								<th width="25%">标题</th>
				                <th width="10%">联系人</th>
				                <th width="15%">联系号码</th>
				                <th width="10%">订单类型</th>
								<th width="15%">状态</th>
								<th >操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input type="text" class="form-control" name="id" value="{$params.id}"></td>
								<td><input type="text" class="form-control" name="phase_id" value="{$params.phase_id}"></td>
								<td><input type="text" class="form-control" name="subject" value="{$params.subject}"></td>
				                <td><input type="text" class="form-control" name="consignee" value="{$params.consignee}"></td>
				                <td><input type="text" class="form-control" name="mobile" value="{$params.mobile}"></td>
								<td>
									<select class="form-control" name="type">
					                    <option value="">全部</option>
					                    <option value="1" {if $params.type == 1}selected="selected"{/if}>充值</option>
					                    <option value="2" {if $params.type == 2}selected="selected"{/if}>购买</option>
									</select>
								</td>
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
											<th width="7%">期数ID</th>
											<th width="15%">标题</th>
											<th width="8%">会员</th>
											<th width="10%">下单时间</th>
											<th width="5%">支付金额</th>
					                        <th width="10%">联系人</th>
					                        <th width="5%">类型</th>
					                        <th width="5%">状态</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
										{foreach $orderList as $data}
										<tr data-id="{$data.id}" class="gradeX odd" role="row">
											<td>{$data.id}</td>
											<td>{$data.phase_id}</td>
											<td>{$data.subject}</td>
											<td>用户ID：{$data.buyer_id}</br>用户名：{$data.member_name}</td>
											<td>{date("Y-m-d H:i:s",$data.dateline)}</td>
											<td>￥{$data.amount}</td>
						                    <td class="userNote">
						                        <p>{$data.consignee}</p>
						                        <p>{$data.mobile}</p>
						                    </td>
						                    <td>
						                    	{if $data.type == 1}充值
						                    	{else if $data.type == 2}购买
						                    	{/if}
						                    </td>
						                    <td>
						                      {if $data.status == 0}
						                        <span class="label label-danger">待付款</span>
					                         {else if $data.status == 1}
					                         <span class="label label-warning">已付款</span>
					                         {else if $data.status == 2}
					                         <span class="label label-success">待确认</span>
					                         {else if $data.status == 3}
					                         <span class="label label-info">已完成</span>
					                         {else if $data.status == 13}
					                         <span class="label label-default">已退款</span>
					                         {else if $data.status == -1}
					                         <span class="label label-default">已关闭</span>
					                         {/if}
						                    </td>
											<td>
											{if $data.status == 2}
											<a href="javascript:;" class="right btn yellow btn-sm"><i class="fa fa-check"></i> 确认</a>
											{/if}
											<a href="/onecp/order/detail?id={$data.id}" class="btn green btn-sm"><i class="fa fa-search"></i> 查看</a>
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

{include file='public/admincp/footer.tpl'}
