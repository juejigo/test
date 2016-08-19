{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">商品房间 <small>商品名：{$product_name}</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/room?id={$params.id}">房间</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form class="form-horizontal form-row-seperated form">
						<input type="hidden" name="prodId" id="prodId" value="{$params.id}" placeholder="商品ID">
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
											<button type="button" class="btn green" data-toggle="modal" href="#addRoomForm">新增 <i class="fa fa-plus"></i></button>
										</div>
									</div>
								</div>
								<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
									<div class="table-scrollable">
										<table class="table table-striped table-bordered table-hover dataTable no-footer" role="grid" aria-describedby="sample_1_info">
											<thead>
												<tr role="row">
													<th width="80">ID</th>
													<th width="200">房间名</th>
													<th width="100">图片</th>
													<th width="150">价格</th>
													<th width="100">可住人数</th>
													<th width="300">设备</th>
													<th>操作</th>
												</tr>
											</thead>
											<tbody id="tripBox">
											{foreach $roomList as $i=>$room}
												<tr data-roomid="{$room.id}"> 
													<td>{$room.id}</td>
													<td>{$room.addon_name}</td>
													<td><img width="50" height="50" src="{$room.image}" /></td>
													<td>{$room.price}</td>
													<td>{$room.extra.num}</td>
													<td>{$room.extra.facilities}</td>
													<td>
														<a href="/productcp/product/editroom?id={$room.id}" class="btn green btn-sm ">编辑 <i class="glyphicon glyphicon-edit"></i></a>
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
							<div class="modal fade bs-modal-lg" id="addRoomForm" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<form action="" class="form-horizontal form-row-seperated" method="post">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">房间内容设置</h4>
										</div>
										<div class="modal-body">
											<div class="form-body">
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">房间名：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="addon_name" name="addon_name">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
													<label class="col-md-2 control-label">图片：</label>
													<div class="col-md-8">
															<input data-toggle="modal" data-target="#image_uploader" type="text" class="form-control" name="image" id = "image">
													</div>
												</div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">可住人数：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="num" name="num">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">面积：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="area" name="area">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">价格：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="price" name="price">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">楼层：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="floor" name="floor">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">库存：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="stock" name="stock">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">设施：</label>
			                                        <div class="col-md-8">
			                                            <textarea class="form-control ke" id="facilities" name="facilities" rows="10"></textarea>
			                                        </div>
			                                    </div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn green" onclick="trip.add()">确定</button>
											<button type="button" class="btn" data-dismiss="modal">关闭</button>
										</div>
									</div>
									</form>
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