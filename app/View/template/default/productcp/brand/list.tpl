{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
			
			<div class="row table-scrollable">
				<table id="datatable_brands" class="table table-striped table-bordered table-hover dataTable no-footer" aria-describedby="datatable_brands_info" role="grid">
				<thead>
				<tr class="heading" role="row">
						<th width="1%" class="sorting_disabled" rowspan="1" colspan="1">
						<div class="checker"><span><input type="checkbox" class="group-checkable"></span></div>
						</th>
						<th width="40%" class="sorting" tabindex="0" aria-controls="datatable_brands" rowspan="1" colspan="1">
							 ID
						</th>
						<th width="40%" class="sorting" tabindex="0" aria-controls="datatable_brands" rowspan="1" colspan="1">
							 品牌名
						</th>
						<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_brands" rowspan="1" colspan="1">
						 </th>
				</tr>
				<tr class="filter" role="row">
						<td rowspan="1" colspan="1">
						</td>
						<td rowspan="1" colspan="1">
							<input type="text" name="brand_id" class="form-control form-filter input-sm">
						</td>
						<td rowspan="1" colspan="1">
							<input type="text" name="brand_name" class="form-control form-filter input-sm">
						</td>
						<td rowspan="1" colspan="1">
							<div class="margin-bottom-5">
								<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> 搜索</button>
							</div>
						</td>
				</tr>
				</thead>
				<tbody>
				</tbody>
				</table>
			</div>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								品牌列表
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
											<a class="btn green" href="/productcp/brand/add">
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
									 图片
								</th>
								<th>
									 品牌名
								</th>
								<th>
									 操作
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach $brandList as $i => $brand}
							<tr dataid="{$brand.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									<input type="checkbox" class="checkboxes" value="1"/>
								</td>
								<td>
									 <img width="50" src="{thumbpath source=$brand.image width=220}" />
								</td>
								<td>
									{$brand.brand_name}
								</td>
								<td>
									<a href="/productcp/brand/edit?id={$brand.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
									<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>
								</td>
							</tr>
							{/foreach}
							
							<tr>
								<td colspan="4">{$pagebar}</td>
							</tr>
							</tbody>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			
	</div>
</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}