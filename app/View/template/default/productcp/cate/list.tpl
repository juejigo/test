{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
			
			<h3 class="page-title">分类列表</h3>
			
			<div class="page-bar">
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button data-close-others="true" data-delay="1000" data-hover="dropdown" data-toggle="dropdown" class="btn btn-fit-height grey-salt dropdown-toggle" type="button" aria-expanded="false">
						{if $params.area == 0}产品区{else if $params.area == 1}活动区{/if} <i class="fa fa-angle-down"></i>
						</button>
						<ul role="menu" class="dropdown-menu pull-right">
							<li>
								<a href="/productcp/cate/list">产品区</a>
								<a href="/productcp/cate/list?area=1">活动区</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			
			<!-- row -->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								分类列表
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
											<a class="btn green" href="/productcp/cate/add?area={$params.area}">
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
								<th class="table-checkbox">
									<input type="checkbox" class="group-checkable"/>
								</th>
								<th>
									 分类名
								</th>
								<th>
									 操作
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach $list as $cate}
							<tr dataid="{$cate.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									<input type="checkbox" class="checkboxes" value="1"/>
								</td>
								<td>
									<span style="padding-left:{($cateList.{$cate.id}.level - 1) * 50}px;display:block;">{$cate.value}</span>
								</td>
								<td>
									<a href="/productcp/cate/edit?id={$cate.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
									<a href="/productcp/cate/add?parent_id={$cate.id}" class="btn yellow">添加子分类 <i class="glyphicon glyphicon-edit"></i></a>
									<a href="/positioncp/data/list?cate_id={$cate.id}" class="btn green">推荐位设置 <i class="glyphicon glyphicon-edit"></i></a>
									<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>
								</td>
							</tr>
							{/foreach}
							</tbody>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			<!-- row /-->
			
	</div>
</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}