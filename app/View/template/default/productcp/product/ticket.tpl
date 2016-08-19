{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">机票信息 <small>商品名：{$product_name}</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#">商品</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/ticket?id={$product_id}">机票信息</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form class="form-horizontal form-row-seperated form">
						<input type="hidden" name="prodId" id="prodId" value="{$product_id}" placeholder="商品ID">
                    	<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/productcp/product/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
								</div>
							</div>
						</div>
                        <div class="tab-content">
							<!--工具-->
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<button type="button" class="btn green" data-toggle="modal" href="#addFly">新增 <i class="fa fa-plus"></i></button>
									</div>
								</div>
							</div>
							<!--去程航班-->
							<div class="portlet box yellow-crusta">
								<div class="portlet-title">
									<div class="caption">去程航班</div>
									<div class="tools"><a href="javascript:;" class="collapse" data-original-title="收缩" title=""></a></div>
								</div>
								<div class="portlet-body">
									<!--表格-->
									<div class="dataTables_wrapper no-footer">
										<div class="table-scrollable" style="overflow-x:inherit;overflow-y:inherit;">
											<table class="table table-bordered table-hover" id="flyGo">
												<thead>
													<tr role="row">
														<th width="200">航空公司</th>
														<th width="150">航班</th>
														<th width="400">时间</th>
														<th width="150">舱位</th>
														<th width="150">价格</th>
														<th width="200">产品日期</th>
														<th>操作</th>
													</tr>
												</thead>
												<tbody>
												{foreach $goticket as $i=>$row}
													<tr data-airfareid="{$row.id}" role="row"> 
														<td>{$row.company}</td>
														<td>{$row.flight}</td>
														<td>
															<div class="fly_td">
																<div class="fly_td_zl">
																	<p>{$row.go_area}</p>
																	<p>{date("Y-m-d H:i",$row.go_time)}</p>
																	<p>{$row.go_airport}</p>
																</div>
																<div class="fly_td_zl">
																	<span class="fl"></span>
																</div>
																<div class="fly_td_zl">
																	<p>{$row.return_area}</p>
																	<p>{date("Y-m-d H:i",$row.return_time)}</p>
																	<p>{$row.return_airport}</p>
																</div>
															</div>

														</td>
														<td>{$row.berths}</td>
														<td>￥{$row.price}</td>
														<td>{date("Y-m-d",$row.travel_date)}</td>
														<td>
															<a href="javascript:;" class="btn green btn-sm edit">编辑 <i class="glyphicon glyphicon-edit"></i></a>
															<a href="javascript:;" class="btn default btn-sm del">删除 <i class="glyphicon glyphicon-remove"></i></a>	
														</td>
													</tr>
													{/foreach}
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>	
							<!--去程航班 /-->
							<!--返程航班-->
							<div class="portlet box grey-cascade">
								<div class="portlet-title">
									<div class="caption">返程航班</div>
									<div class="tools"><a href="javascript:;" class="collapse" data-original-title="收缩" title=""></a></div>
								</div>
								<div class="portlet-body">
									<!--表格-->
									<div class="dataTables_wrapper no-footer">
										<div class="table-scrollable" style="overflow-x:inherit;overflow-y:inherit;">
											<table class="table table-bordered table-hover" id="flyBack">
												<thead>
													<tr role="row">
														<th width="200">航空公司</th>
														<th width="150">航班</th>
														<th width="400">时间</th>
														<th width="150">舱位</th>
														<th width="150">价格</th>
														<th width="200">产品日期</th>
														<th>操作</th>
													</tr>
												</thead>
												<tbody>
													{foreach $returnticket as $i=>$row}
													<tr data-airfareid="{$row.id}" role="row"> 
														<td>{$row.company}</td>
														<td>{$row.flight}</td>
														<td>
															<div class="fly_td">
																<div class="fly_td_zl">
																	<p>{$row.go_area}</p>
																	<p>{date("Y-m-d H:i",$row.go_time)}</p>
																	<p>{$row.go_airport}</p>
																</div>
																<div class="fly_td_zl">
																	<span class="fl"></span>
																</div>
																<div class="fly_td_zl">
																	<p>{$row.return_area}</p>
																	<p>{date("Y-m-d H:i",$row.return_time)}</p>
																	<p>{$row.return_airport}</p>
																</div>
															</div>

														</td>
														<td>{$row.berths}</td>
														<td>￥{$row.price}</td>
														<td>{date("Y-m-d",$row.travel_date)}</td>
														<td>
															<a href="javascript:;" class="btn green btn-sm edit">编辑 <i class="glyphicon glyphicon-edit"></i></a>
															<a href="javascript:;" class="btn default btn-sm del">删除 <i class="glyphicon glyphicon-remove"></i></a>	
														</td>
													</tr>
													{/foreach}
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>	
							<!--返程航班 /-->
							<!--增加航班form框-->
							<input type="hidden" class="form-control seat" id="prodId" value="{$product_id}">
							<div class="modal fade bs-modal-lg" id="addFly" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">行程内容设置</h4>
										</div>
										<div class="modal-body">
											<div class="form-body">
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">往返选择：</label>
			                                        <div class="col-md-8">
			                                            <select class="form-control type">
															<option value="">请选择</option>
															<option value="0">去程</option>
															<option value="1">返程</option>
														</select>
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
													<label class="col-md-2 control-label">产品日期：</label>
													<div class="col-md-8">
														<select class="form-control prod_time">
															<option value="">请选择</option>
															{foreach $travel_date as $row}
															<option value="{$row.id}">{date("Y-m-d",$row.travel_date)}</option>
															{/foreach}

														</select>
														<span class="help-block text-primary">选择产品规格对应日期，不能重复选择。</span>
													</div>
												</div>
		                                    
												<div class="form-group">
													<label class="col-md-2 control-label">时间：</label>
													<div class="col-md-8">
														<input type="text" class="form-control reservationtime" placeholder="选择时间">
														<input type="hidden" class="start_time">
														<input type="hidden" class="end_time">														
													</div>
												</div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">出发地：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control input-inline go_address" placeholder="格式：省份-城市">
			                                            <input type="text" class="form-control input-inline go_airport" placeholder="机场名">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">目的地：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control input-inline back_address" placeholder="格式：国家-城市">
			                                            <input type="text" class="form-control input-inline back_airport" placeholder="机场名">
			                                        </div>
			                                    </div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">航空公司：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control company" placeholder="航空公司">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">航班：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control flight" placeholder="航班">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">舱位：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control seat" placeholder="舱位">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">价格：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control price" placeholder="价格">
			                                        </div>
			                                    </div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn green" onclick="airfare.add()">确定</button>
											<button type="button" class="btn" data-dismiss="modal">关闭</button>
										</div>
									</div>
								</div>
							</div>
							<!--增加航班form框 /-->
							<!--编辑航班form框-->
							<div class="modal fade bs-modal-lg" id="editFly" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">行程内容设置</h4>
										</div>
										<div class="modal-body">
											<!-- <div class="form-body">
												<input type="hidden" name="flyId" id="flyId" value="1">
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">往返选择：</label>
			                                        <div class="col-md-8">
			                                            <select class="form-control type">
															<option value="">请选择</option>
															<option value="0">去程</option>
															<option value="1">返程</option>
														</select>
			                                        </div>
			                                    </div>
												<div class="form-group">
													<label class="col-md-2 control-label">时间：</label>
													<div class="col-md-8">
														<input type="text" class="form-control reservationtime" placeholder="选择时间">
														<input type="hidden" class="start_time">
														<input type="hidden" class="end_time">														
													</div>
												</div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">出发地：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control input-inline go_address" placeholder="格式：省份-城市">
			                                            <input type="text" class="form-control input-inline go_airport" placeholder="机场名">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">目的地：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control input-inline back_address" placeholder="格式：国家-城市">
			                                            <input type="text" class="form-control input-inline back_airport" placeholder="机场名">
			                                        </div>
			                                    </div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">航空公司：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control company" placeholder="航空公司">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">航班：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control flight" placeholder="航班">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">舱位：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control seat" placeholder="舱位">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">价格：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control price" placeholder="价格">
			                                        </div>
			                                    </div>
											</div> -->
										</div>
										<div class="modal-footer">
											<button type="button" class="btn green" onclick="airfare.edit()">确定</button>
											<button type="button" class="btn" data-dismiss="modal">关闭</button>
										</div>
									</div>
								</div>
							</div>
							<!--编辑航班form框 /-->
                        </div>
                    </form>
				</div>
			</div>
			<!--表单 /-->
		</div>
	</div>
	<!--content-wrapper /-->
	{include file='public/admincp/footer.tpl'}



<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/plupload/js/plupload.full.min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>

<script src="{$smarty.const.URL_MIX}metronic3.6/global/scripts/metronic.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_JS}productcp/product/productticket.js"></script>