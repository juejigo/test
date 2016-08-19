{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">商品列表 <small>查看和管理商品信息</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/list">商品</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/list">商品管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/list">商品列表</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="/productcp/product/list" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="5%">ID</th>
								<th class="sorting_asc" width="5%">货号</th>
								<th class="sorting_asc" width="15%">产品标题</th>
								<th class="sorting_asc" width="25%">出行日期</th>
								<th class="sorting_asc" width="10%">状态</th>
								<th class="sorting_asc" width="25%">出发地</th>								
								<th class="sorting_asc">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input type="search" class="form-control" name="id" value="{$params.id}">
								</td>
								<td>
									<input type="search" class="form-control" name="sn" value="{$params.sn}">
								</td>
								<td>
									<input type="search" class="form-control" name="product_name" value="{$params.product_name}">
								</td>
								<td>
									<div data-date-format="yyyy-mm-dd" class="input-group input-large date-picker input-daterange">
										<input type="text" name="up_time" class="form-control" value="{$params.up_time}" placeholder="开始时间">
										<span class="input-group-addon">至</span>
										<input type="text" name="down_time" class="form-control" value="{$params.down_time}" placeholder="结束时间">
									</div>
								</td>
								<td>
									<select class="form-control" name="status">
										<option value="0">全部</option>
										<option value="2" {if $params.status==2} selected="selected" {/if}>上架中</option>
										<option value="3" {if $params.status==3} selected="selected" {/if}>已下架</option>
									</select>
								</td>
								<td>
									<div class="select_region" rel='["province":{if $params.province_id}{$params.province_id}{else}0{/if},"city":{if $params.city_id}{$params.city_id}{else}0{/if}]'>
                                        <select name="province_id" id="province_id" class="form-control input-small input-inline"><option value="0">请选择</option></select>
                                        <select name="city_id" id="city_id" class="form-control input-small input-inline"><option value="0">请选择</option></select>
                                    </div>
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
						<!--工具-->
						<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<a class="btn green" href="/productcp/product/add">新增 <i class="fa fa-plus"></i></a>
								<div class="btn-group">
										<button class="btn yellow dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
											批量操作 <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li><a href="javascript:;" class="all_up">上架</a></li>
											<li><a href="javascript:;" class="all_down">下架</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!--表格-->
						<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
							<div class="table-scrollable" style="overflow-x:inherit;overflow-y:inherit;">
								<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
									<thead>
										<tr role="row">
											<th width="80"><input type="checkbox" class="group-checkable" id="allCheck">ID</th>
											<th width="80">序号</th>
											<th width="100">货号</th>
											<th width="100">商品封面</th>
											<th width="200">商品标题</th>
											<th width="150">出发城市</th>
											<th width="150">价格</th>
											<th width="150">出行日期</th>
											<th width="50">状态</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody id="prodList">
									{foreach $productList as $i => $product}
										<tr dataid="{$product.id}" class="gradeX odd" data-sortid="{$product.order}"> 
											<td><input type="checkbox" class="group-checkable checkbox">{$product.id}</td>
											<td>{$product.order}</td>
											<td>{$product.sn}</td>
											<td><img src="{$product.image}" width="100" height="100px"></td>
											<td><a target="_blank" href="/product/product/detail?id={$product.id}">{$product.product_name}</a></td>
											<td>{$product.origin_id}</td>
											<td>{$product.price}</td>
											<td>{$product.travel_date}</td>
											<td>
						                    {if $product.status == 0}
						                    <span class="label label-warning">待上架</span>
					                        {else if $product.status == 2}
					                        <span class="label label-success">上架中</span>
					                        {else if $product.status == 3}
					                        <span class="label label-danger">已下架</span>
					                        {/if}
						                    </td>
											<td>
												<div class="btn-group">
													<button class="btn green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
														完善商品 <i class="glyphicon glyphicon-edit"></i> <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu" role="menu">
														<li><a href="/productcp/product/edit?id={$product.id}">基本信息</a></li>
														<li><a href="/productcp/image/add?id={$product.id}">主图</a></li>
														<li><a href="/productcp/product/trip?id={$product.id}">行程</a></li>
														<li><a href="/productcp/product/ticket?id={$product.id}">机票信息</a></li>
														<li><a href="/productcp/product/contract?id={$product.id}">合同</a></li>
														<li><a href="/productcp/product/addon?id={$product.id}">保险</a></li>
														<li><a href="/productcp/product/visa?id={$product.id}">签证</a></li>
														<li><a href="/productcp/product/room?id={$product.id}">房间</a>
													</ul>
												</div>
												<div class="btn-group">
													<button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
														排序 <i class="fa fa-navicon"></i> <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu" role="menu">
														<li><a href="javascript:;" class="to_top">置顶</a></li>
														<li><a href="javascript:;" class="set_sort">排序</a></li>
													</ul>
												</div>
												{if $product.status != -1  && $product.status != 2 }
												<a href="javascript:;" class="up btn yellow">上架 <i class="glyphicon glyphicon-edit"></i></a>
												{else if $product.status == 2}
												<a href="javascript:;" class="down btn yellow">下架 <i class="glyphicon glyphicon-edit"></i></a>
												{/if}
												<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>	
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
								<div class="dataTables_paginate paging_bootstrap_full_number pull-right" id="sample_1_paginate">
							{$pagebar}
								</div>
							</div>
						</div>
						<!--分页 /-->
						<!--排序填写框-->
						<div class="modal fade bs-modal-sm" id="prodSortBox" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-sm">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title">排序</h4>
									</div>
									<div class="modal-body">
										<form>
											<div class="form-group">
												<input type="hidden" name="id" placeholder="商品ID" value="">
											 	<input class="form-control" name="order" type="text" placeholder="序号" vlaue="">
											 </div>
										 </form>
									</div>
									<div class="modal-footer">
										<button type="button" data-dismiss="modal" class="btn btn-default">关闭</button>
										<button type="button" class="btn btn-primary">确定</button>
									</div>
								</div>
							</div>
						</div>
						<!--排序填写框-->
					</div>
				</div>
			</div>
			<!--表格结束 /-->
		</div>
	</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}

<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{$smarty.const.URL_JS}productcp/product/regionnew.js" ></script>
{literal}
<script>
$(function() {    
	region.init($(".select_region"));
});
</script>
{/literal}