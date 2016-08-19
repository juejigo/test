{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}ordercp/order/order.css" rel="stylesheet" type="text/css" />
<!--content-wrapper-->
<div class="page-content-wrapper">
	<div class="page-content" style="min-height: 812px">
		<!--标题栏-->
		<h3 class="page-title">
			订单 <small>查看和管理订单</small>
		</h3>
		<!--路径导航-->
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i
					class="fa fa-angle-right"></i></li>
				<li><a href="/ordercp/order/list">订单</a><i class="fa fa-angle-right"></i></li>
				<li><a href="/ordercp/order/detail?id={$params.id}">订单详情</a></li>
			</ul>
		</div>
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-edit"></i>订单详情
				</div>
				<div class="pull-right">
					<a href="/ordercp/order/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
					<a class="remarks btn default" href="#remarks" data-toggle="modal"><i class="fa fa-pencil-square-o"></i> 备注</a>
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
			<input type="hidden" id="orderId" value="{$order.id}"
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
											<label class="control-label col-md-3">商品总价:</label>
											<div class="col-md-9">
												<p class="form-control-static">￥{$order.amount}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">优惠金额:</label>
											<div class="col-md-9">
												<p class="form-control-static">￥{$order.discount}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">支付金额:</label>
											<div class="col-md-9">
												<p class="form-control-static">￥{$order.pay_amount}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">订单状态:</label>
											<div class="col-md-9">
												<p class="form-control-static">

													{if $order.status == 1} 待确认 <a href="javascript:;"
														class="right btn yellow btn-sm"><i class="fa fa-check"></i>
														确认</a> <a href="javascript:;"
														class="refund btn red btn-sm"><i class="fa fa-money"></i>
														退款</a> {else if $order.status == 2}待出行{else if $order.status == -1} 订单已关闭 {else if
													$order.status == 3} 已完成 {else if $order.status == 13} 已退款
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
												<p class="form-control-static">{$order.buyer_name}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">联系手机:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.mobile}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">邮箱:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$order.email}</p>
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
												<p class="form-control-static">{$supplier.supplier_name}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">联系号码:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$supplier.telephone}</p>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-3">地址:</label>
											<div class="col-md-9">
												<p class="form-control-static">{$supplier.address}</p>
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
								<div class="caption">商品规格</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="5%">封面</th>
											<th width="55%">商品名称</th>
											<th width="10%">单价</th>
											<th width="10%">规格</th>
											<th width="10%">数量</th>
											<th width="10%">总价</th>
										</tr>
									</thead>
									<tbody>
										{foreach $items as $row}
										<tr role="row">
											<td><img src="{$row.product_image}" width="100%"></td>
											<td>{$row.product_name}</td>
											<td>{$row.price}</td>
											<td>{$row.spec_desc}</td>
											<td>{$row.num}</td>
											<td>{$row.num*$row.price}</td>
										</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--商品规格 /-->
					<!--房间信息-->
					<div class="col-md-12">
						<div class="portlet blue box">
							<div class="portlet-title">
								<div class="caption">房间信息</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="25%">房间名</th>
											<th width="20%">图片</th>
											<th width="25%">价格</th>
											<th width="8%">数量</th>
											<th width="22%">总价</th>
										</tr>
									</thead>
									<tbody>
									{foreach $addons as $addon}
										{if $addon.addon_type == 1}
										<tr role="row">
											<td>{$addon.addon_name}</td>
											<td><img src="{$addon.image}" width="100%"></td>
											<td>{$addon.o_price}</td>
											<td>{$addon.num}</td>
											<td>{$addon.num*$addon.o_price}</td>
										</tr>
										{/if}
									{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--房间信息 /-->
					<!--游客信息-->
					<div class="col-md-12">
						<div class="portlet green box">
							<div class="portlet-title">
								<div class="caption">游客信息</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="15%">游客姓名</th>
											<th width="10%">证件类型</th>
											<th width="25%">证件号</th>
											<th width="20%">联系方式</th>
											<th width="8%">性别</th>
											<th width="22%">有效日期</th>
										</tr>
									</thead>
									<tbody>
									{foreach $tourist as $tour}
										<tr role="row">
											<td>{$tour.tourist_name}</td>
											<td>
											{if $tour.cert_type == 1}
											身份证
											{elseif $tour.cert_type == 2}
											护照
											{/if}
											</td>
											<td>{$tour.cert_num}</td>
											<td>{$tour.mobile}</td>
											<td>
											{if $tour.sex == 1}
											男
											{elseif $tour.sex == 0}
											女
											{/if}
											</td>
											<td>{date("Y年-m月-d日",$tour.cert_deadline)}</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--游客信息 /-->
					<!--优惠信息-->
					<div class="col-md-12">
						<div class="portlet yellow box">
							<div class="portlet-title">
								<div class="caption">优惠详情</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<a class="btn yellow" href="#addDiscount" data-toggle="modal">新增
									<i class="fa fa-plus"></i>
								</a>
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="40%">优惠类型</th>
											<th width="20%">优惠金额</th>
											<th width="40%">操作人</th>
										</tr>
									</thead>
									<tbody>
										{foreach $discounts as $discount}
										<tr role="row">
											<td>{if $discount.type == 0}折扣{else if $discount.type ==
												1}优惠券{/if}</td>
											<td>{$discount.amount}</td>
											<td>{$discount.operator_id}</td>
										</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--优惠信息 /-->
					<!--增加优惠-->
					<div class="modal fade" id="addDiscount" tabindex="-1" role="basic"
						aria-hidden="true">
						<div class="modal-dialog">
							<form class="form-horizontal form-row-seperated">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"
											aria-hidden="true"></button>
										<h4 class="modal-title">设置优惠</h4>
									</div>
									<div class="modal-body">
										<div class="form-body">
											<div class="form-group">
												<label class="col-md-3 control-label">优惠金额：</label>
												<div class="col-md-8">
													<input type="text" class="form-control discount"
														name="discount" placeholder="如设置5元优惠，输入5即可.">
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn default" data-dismiss="modal">取消</button>
										<button type="button" class="btn yellow" id="addDis">保存</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!--增加优惠 /-->
					<!--合同-->
					<div class="col-md-12">
						<div class="portlet purple box">
							<div class="portlet-title">
								<div class="caption">合同</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="70%">合同名称</th>
											<th width="15%">甲方</th>
											<th width="15%">乙方</th>
										</tr>
									</thead>
									<tbody>
									{foreach $contract as $con}
										<tr role="row" >
											<td>{$con.contract_name}</td>
											<td>{$con.first_part}</td>
											<td>{$con.second_part}</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--合同 /-->
					<!--保险-->
					<div class="col-md-12">
						<div class="portlet grey-cascade box">
							<div class="portlet-title">
								<div class="caption">保险</div>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
								</div>
							</div>
							<div class="portlet-body">
								<table
									class="table table-striped table-bordered table-hover dataTable no-footer">
									<thead>
										<tr role="row">
											<th width="60%">保险名称</th>
											<th width="20%">保险类型</th>
											<th width="10%">数量</th>
											<th width="10%">价格</th>
										</tr>
									</thead>
									<tbody>
									{foreach $addons as $addon}
									{if $addon.addon_type == 0}
										<tr role="row">
											<td>{$addon.addon_name}</td>
											<td>{$addon.type}</td>
											<td>{$addon.num}</td>
											<td>{$addon.o_price}</td>
										</tr>
									{/if}
									{/foreach}
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--保险 /-->
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
