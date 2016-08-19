{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
	
			<h3 class="page-title">推荐列表</h3>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								推荐列表
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<div class="btn-group">
											<a class="btn green" href="/positioncp/data/add?position_id={$params.position_id}&cate_id={$params.cate_id}">
											新增 <i class="fa fa-plus"></i>
											</a>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
											</button>
											<ul class="dropdown-menu pull-right">
												<li>
													<a href="#">
													Print </a>
												</li>
												<li>
													<a href="#">
													Save as PDF </a>
												</li>
												<li>
													<a href="#">
													Export to Excel </a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<th>
									序号
								</th>
								<th>
									 标题
								</th>
								<th>
									 图片
								</th>
								<th>
									 关联
								</th>
								<th>
									 推荐时间
								</th>
								<th>
									状态
								</th>
								<th>
									 操作
								</th>
							</tr>
							</thead>
							<tbody id="prodList">
							{foreach $dataList as $i => $data}
							<tr dataid="{$data.id}" data-sortid="{$data.order}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									{$data.order}
								</td>
								<td>
									{$data.title}
								</td>
								<td>
									 <img width="50" height="50" src="{$data.image}" />
								</td>
								<td>
									{if $data.data_type == 'product'}商品{else if $data.data_type == 'product_list'}商品列表{else if $data.data_type == 'link'}链接{/if}
								</td>
								<td class="center">
									 {date('Y-m-d',$data.dateline)}
								</td>
								<td>
									{if $data.status == 0}
				                    <span class="label label-warning">待上架</span>
			                        {else if $data.status == 1}
			                        <span class="label label-success">上架中</span>
			                        {/if}
								</td>
								<td>
									<a href="/positioncp/data/edit?id={$data.id}" class="btn btn-sm green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
									<div class="btn-group">
										<button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
											排序 <i class="fa fa-navicon"></i> <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li><a href="javasccript:;" class="to_top">置顶</a></li>
											<li><a href="javascript:;" class="set_sort">排序</a></li>
										</ul>
									</div>
									{if $data.status == 0 }
									<a href="javascript:;" class="up btn btn-sm blue">上架 <i class="glyphicon glyphicon-edit"></i></a>
									{else if $data.status == 1}
									<a href="javascript:;" class="down btn btn-sm yellow">下架 <i class="glyphicon glyphicon-edit"></i></a>
									{/if}
									<a href="javascript:;" class="delete btn btn-sm red">删除 <i class="glyphicon glyphicon-remove"></i></a>
								</td>
							</tr>
							{/foreach}
							</tbody>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
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
</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}

<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>