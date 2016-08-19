{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}ordercp/order/order.css" rel="stylesheet" type="text/css" />
<!--content-wrapper-->
<div class="page-content-wrapper">
	<div class="page-content" style="min-height: 812px">
		<!--标题栏-->
		<h3 class="page-title">
			订单 <small>查看订单详情</small>
		</h3>
		<!--路径导航-->
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
				<li><a href="/onecp/phase/list">一元夺宝</a><i class="fa fa-angle-right"></i></li>
				<li><a href="/onecp/order/list">夺宝订单</a><i class="fa fa-angle-right"></i></li>
				<li><a href="/onecp/order/detail?id={$params.id}">订单详情</a></li>
			</ul>
		</div>
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>订单详情
				</div>
				<div class="pull-right">
					<a href="/one/order/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
				</div>
			</div>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#details"
				aria-controls="details" role="tab" data-toggle="tab">订单信息</a></li>
			<li role="presentation"><a href="#express" aria-controls="express"
				role="tab" data-toggle="tab">快递记录</a></li>
			<li role="presentation"><a href="#operate" aria-controls="operate"
				role="tab" data-toggle="tab">操作记录</a></li>
		</ul>
		<div class="tab-content">
			<input type="hidden" id="orderId" value="{$params.id}"
				placeholder="订单ID">
			<!--订单信息-->
			<div role="tabpanel" class="tab-pane active" id="details">
				<div class="row">
					<!--基本信息-->
					<div class="col-md-6">
						<div class="portlet blue box">
							<div class="portlet-title">
								<div class="caption">基本信息</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<div class="form-horizontal">
									<div class="form-body">
										<div class="form-group">
											<label class="control-label col-md-3">订单编号:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.id}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">订单标题:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.subject}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">下单时间:</label>
											<div class="col-md-9">
												<p class="form-control-static">{date("Y-m-d
													H:i:s",$order.dateline)}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">支付方式:</label>
											<div class="col-md-9">
												<p class="form-control-static">{fieldvalue
													field='orderPayment' value=$order.payment}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">会员ID:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.buyer_id}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">支付账号:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.out_account}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">支付流水号:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.out_id}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">支付金额:</label>
											<div class="col-md-9">
												<p class="form-control-static">￥{$order.amount}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">购买人次:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.num}人次</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">订单类型:</label>
											<div class="col-md-9">
												<p class="form-control-static">{if $order.type == 1}充值{else if $order.type == 2}购买{/if}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">订单状态:</label>
											<div class="col-md-9">
												<p class="form-control-static">
													{if $order.status == 1}已付款
													{else if $order.status == 2}待确认 <a href="javascript:;" class="right btn yellow btn-sm"><i class="fa fa-check"></i>确认</a>
													{else if $order.status == -1}订单已关闭 
													{else if $order.status == 3}已完成 
													{else if $order.status == 13}已退款
													{/if}
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--基本信息 /-->
					<div class="col-md-6">
						<!--联系人信息-->
						<div class="portlet green box">
							<div class="portlet-title">
								<div class="caption">联系人信息</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<div class="form-horizontal">
									<div class="form-body">
										<div class="form-group">
											<label class="control-label col-md-3">联系人:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.consignee}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">联系手机:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.mobile}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">电话:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.telephone}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">省市区:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.region}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">地址:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.address}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">邮编:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.zip}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--联系人信息 /-->
						<!--供货商信息-->
						<div class="portlet green box">
							<div class="portlet-title">
								<div class="caption">供货商信息</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<div class="form-horizontal">
									<div class="form-body">
										<div class="form-group">
											<label class="control-label col-md-3">供货商:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$phase.supplier_name}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">联系号码:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$phase.telephone}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">地址:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$phase.address}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--供货商信息 /-->
					</div>
				</div>
				<div class="row">
					<!--商品规格-->
					<div class="col-md-12">
						<div class="portlet red box">
							<div class="portlet-title">
								<div class="caption">期数信息</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="3%">ID</th>
											<th width="7%">封面</th>
											<th width="50%">商品名称</th>
											<th width="10%">商品价格</th>
											<th width="10%">单次价格</th>
											<th width="10%">购买人次</th>
											<th width="10%">总价</th>
										</tr>
									</thead>
									<tbody>
										<tr role="row">
											<td>{$phase.id}</td>
											<td><img src="{$phase.image}" width="100%"></td>
											<td><a href="/onecp/phase/detail?id={$phase.id}">{$phase.product_name}</a></td>
											<td>￥{$phase.product_price}</td>
											<td>￥{$phase.price}</td>
											<td>{$order.num}人次</td>
											<td>￥{$order.num*$phase.price}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--商品规格 /-->
					<!--幸运号码-->
					<div class="col-md-12">
						<div class="portlet blue box">
							<div class="portlet-title">
								<div class="caption">幸运号码</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="25%">期数ID</th>
											<th width="25%">幸运号码</th>
											<th width="25%">是否中奖</th>
										</tr>
									</thead>
									<tbody>
									{foreach $luckyNum as $num}
										<tr role="row">
											<td>{$num.phase_id}</td>
											<td>{$num.lucky_num}</td>
											<td>{if $num.is_win == 1}中奖{else}没中{/if}</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--幸运号码 /-->
				</div>
			</div>
			<!--订单信息 /-->
			<!--快递记录-->
			<div role="tabpanel" class="tab-pane" id="express">
				<div class="portlet green box">
					<div class="portlet-title">
						<div class="caption">快递记录</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<a class="btn green" href="#addExpress" data-toggle="modal">新增
										<i class="fa fa-plus"></i>
									</a>
								</div>
							</div>
						</div>
						<table
							class="table table-striped table-bordered table-hover dataTable no-footer">
							<thead>
								<tr role="row">
									<th width="25%">发货日期</th>
									<th width="25%">快递公司</th>
									<th width="25%">快递单号</th>
									<th width="25%">物流信息</th>
								</tr>
							</thead>
							<tbody>
								{foreach $shipping as $row }
								<tr role="row" data-id="{$row.id}">
									<td>{date("Y-m-d H:i:s",$row.dateline)}</td>
									<td>{$row.shipping_company}</td>
									<td>{$row.shipping_no}</td>
									<td><a href="javascript:;"
										class="btn green btn-sm checkExpress"><i class="fa fa-search"></i>
											查看</a></td>
								</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
					<!--增加快递-->
					<div class="modal fade" id="addExpress" tabindex="-1" role="basic"
						aria-hidden="true">
						<div class="modal-dialog">
							<form class="form-horizontal form-row-seperated">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"
											aria-hidden="true"></button>
										<h4 class="modal-title">新增快递信息</h4>
									</div>
									<div class="modal-body">
										<div class="form-body">
											<div class="form-group">
												<label class="col-md-3 control-label">快递公司：</label>
												<div class="col-md-8">
													<select class="form-control company" name="company">
														<option value="">请选择</option> {foreach $companies as $key
														=> $value}
														<option value="{$key}">{$value}</option> {/foreach}
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">快递单号：</label>
											<div class="col-md-8">
												<input type="text" class="form-control num" name="num"
													placeholder="快递单号">
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn default" data-dismiss="modal">取消</button>
										<button type="button" class="btn green" id="expressSub">保存</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!--增加快递 /-->
					<!--查看快递信息-->
					<div class="modal fade" id="checkExpress" tabindex="-1"
						role="basic" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"
										aria-hidden="true"></button>
									<h4 class="modal-title">查看快递信息</h4>
								</div>
								<div class="modal-body"
									style="max-height: 300px; min-height: 300px; overflow-y: scroll;">

								</div>
							</div>
						</div>
					</div>
					<!--查看快递信息 /-->
				</div>
			</div>
			<!--快递记录 /-->
			<!--操作记录-->
			<div role="tabpanel" class="tab-pane" id="operate">
				<div class="portlet green box">
					<div class="portlet-title">
						<div class="caption">操作记录</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body">
						<table
							class="table table-striped table-bordered table-hover dataTable no-footer">
							<thead>
								<tr role="row">
									<th width="25%">时间</th>
									<th width="75%">操作记录</th>
								</tr>
							</thead>
							<tbody>
								{foreach $logs as $row}
								<tr role="row">
									<td>{date("Y-m-d H:i:s",$row.dateline)}</td>
									<td>{$row.desc}</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!--操作记录 /-->
		</div>
	</div>
</div>
<!--后台备注-->
<div class="modal fade" id="remarks" tabindex="-1" role="basic"
	aria-hidden="true">
	<div class="modal-dialog">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true"></button>
					<h4 class="modal-title">后台备注</h4>
				</div>
				<div class="modal-body" style="min-height: 200px;">
					<textarea cols="30" rows="10" name="remarks" class="form-control">{$order.admin_memo}</textarea>
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
<!--查看合同-->
<div class="modal bs-modal-lg fade" id="con" tabindex="-1" role="basic"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<form>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true"></button>
					<h4 class="modal-title">合同</h4>
				</div>
				<div class="modal-body"
					style="min-height: 200px; max-height: 600px; overflow-y: scroll;">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn default" data-dismiss="modal">关闭</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!--查看合同 /-->
<!--content-wrapper /-->

{include file='public/admincp/footer.tpl'}
<script type="text/javascript">var pagevar = { 'order_id' : {$params.id}, }</script>
