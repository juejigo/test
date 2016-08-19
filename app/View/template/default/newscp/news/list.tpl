{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
	
			<h3 class="page-title">新闻列表</h3>
			
			<div class="row table-scrollable">
			<form action="/newscp/news/list" method="GET">
				<table id="datatable_newss" class="table table-striped table-bordered table-hover dataTable no-footer" aria-describedby="datatable_newss_info" role="grid">
				<thead>
				<tr class="heading" role="row">
						<th width="1%" class="sorting_disabled" rowspan="1" colspan="1">
							<div class="checker"><span><input type="checkbox" class="group-checkable"></span></div>
						</th>
						<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_newss" rowspan="1" colspan="1">
							 ID
						</th>
						<th width="40%" class="sorting" tabindex="0" aria-controls="datatable_newss" rowspan="1" colspan="1">
							 新闻名
						</th>
						<th width="40%" class="sorting" tabindex="0" aria-controls="datatable_newss" rowspan="1" colspan="1">
							 分类
						</th>
						<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_newss" rowspan="1" colspan="1">
							 状态
						</th>
						<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_newss" rowspan="1" colspan="1">
							 
						</th>
				</tr>
				<tr class="filter" role="row">
						<td rowspan="1" colspan="1">
						</td>
						<td rowspan="1" colspan="1">
							<input type="text" name="id" class="form-control form-filter input-sm" value="{$params.id}">
						</td>
						<td rowspan="1" colspan="1">
							<input type="text" name="title" class="form-control form-filter input-sm" value="{$params.title}">
						</td>
						<td rowspan="1" colspan="1">
							<select class="form-control form-filter input-sm" name="cate_id">
									<option value="0">请选择分类</option>
									{foreach $list as $cate}
									<option style="text-indent:{($cateList.{$cate.id}.level - 1) * 10}px;" {if $cate.id == $params.cate_id}selected="selected"{/if} value="{$cate.id}">						 {str_repeat('&nbsp;',($cateList.{$cate.id}.level)-1)}	{$cate.value}</option>
									{/foreach}
							</select>
						</td>
						<td rowspan="1" colspan="1">
							<div class="margin-bottom-5">
								<input type="hidden" name="area" value="{$params.area}" />
								<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> 搜索</button>
							</div>
						</td>
				</tr>
				</thead>
				<tbody>
				</tbody>
				</table>
			</form>
			</div>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								新闻列表
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
											<a class="btn green" href="/newscp/news/add">
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
									 标题
								</th>
								<th>
									 发布时间
								</th>
								<th>
									 操作
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach $newsList as $i => $news}
							<tr dataid="{$news.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									<input type="checkbox" class="checkboxes" value="1"/>{$news.id}
								</td>
								<td>
									 <img width="50" src="{thumbpath source=$news.image width=220}" />
								</td>
								<td>
									{$news.title}
								</td>
								<td class="center">
									 {date('Y-m-d',$news.dateline)}
								</td>
								<td>
									<a href="/newscp/news/edit?id={$news.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
									<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>
								</td>
							</tr>
							{/foreach}
							
							<tr>
								<td colspan="6">{$pagebar}</td>
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

<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>