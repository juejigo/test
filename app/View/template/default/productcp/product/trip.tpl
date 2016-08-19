{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>

<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">商品行程 <small>商品名：{$product_name}</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#">商品</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/trip?id={$product_id}">行程</a></li>
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
							<div class="portlet-body">
								<div class="table-toolbar">
									<div class="row">
										<div class="col-md-6">
											<button type="button" class="btn green" data-toggle="modal" href="#addTripForm">新增 <i class="fa fa-plus"></i></button>
										</div>
									</div>
								</div>
								<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
									<div class="table-scrollable">
										<table class="table table-striped table-bordered table-hover dataTable no-footer" role="grid" aria-describedby="sample_1_info">
											<thead>
												<tr role="row">
													<th width="80">天数</th>
													<th width="200">标题</th>
													<th width="400">摘要</th>
													<th>操作</th>
												</tr>
											</thead>
											<tbody id="tripBox">
											{foreach $trip as $i=>$row}
												<tr data-tripid="{$row.id}"> 
													<td>第{$row.sort}天</td>
													<td>{$row.title}</td>
													<td>{$row.info}</td>
													<td>
														<a href="/productcp/product/edittrip?id={$row.id}" class="btn green btn-sm ">编辑 <i class="glyphicon glyphicon-edit"></i></a>
														<a href="javascript:;" class="btn default btn-sm del">删除 <i class="glyphicon glyphicon-remove"></i></a>	
													</td>
												</tr>
											{/foreach}
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!--增加行程form框-->
							<div class="modal fade bs-modal-lg" id="addTripForm" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">行程内容设置</h4>
										</div>
										<div class="modal-body">
											<div class="form-body">
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">序号：</label>
			                                        <div class="col-md-3">
			                                        	<div class="input-group">
			                                        		<span class="input-group-addon">第</span>
			                                        		<input type="text" class="form-control" id="num" name="num">
			                                        		<span class="input-group-addon">天</span>
			                                        	</div>
			                                            
			                                        </div>
			                                    </div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">标题：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="name" name="name" placeholder="行程标题">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">摘要：</label>
			                                        <div class="col-md-8">
			                                            <textarea class="form-control" id="explain" name="explain" placeholder="摘要" rows="10"></textarea>
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">详细行程：</label>
			                                        <div class="col-md-8">
			                                            <textarea class="form-control ke" id="detailed" name="detailed" rows="10"></textarea>
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label"></label>
			                                        <div class="col-md-8">
			                                        	<div id="tripPickfiles" class="trip_up">&nbsp;</div>
			                                            <div class="form-control-static trip_img_box">
			                                            </div>
			                                        </div>
			                                    </div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn green" onclick="trip.add()">确定</button>
											<button type="button" class="btn" data-dismiss="modal">关闭</button>
										</div>
									</div>
								</div>
							</div>
							<!--增加行程form框 /-->

                        </div>
                    </form>
				</div>
			</div>
			<!--表单 /-->
		</div>
	</div>
	<!--content-wrapper /-->
	{include file='public/admincp/footer.tpl'}

<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script src="{$smarty.const.URL_JS}productcp/product/producttrip.js"></script>