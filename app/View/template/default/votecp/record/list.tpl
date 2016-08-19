 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
  <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_WEB}js/public/region.js" charset="UTF-8" p="{$province_id}" c1="{$city_id}" c2="{$county_id}"  id="js_region">
 </script>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">	
			<h3 class="page-title">记录列表</h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/vote">投票</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/record/list?vr_id={$vr_id}">记录列表</a></li>
				</ul>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								记录列表（{$count}）
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
										<!--<div class="btn-group">
											<a class="btn green" href="/productcp/product/add?area={$params.area}">
											新增 <i class="fa fa-plus"></i>
											</a>
										</div>-->
									</div>
									<!-- <div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn dropdown-toggle" data-toggle="dropdown">导出 <i class="fa fa-angle-down"></i>
											</button>
											<ul class="dropdown-menu pull-right">
												<li>
													<a target="_blank" href="{str_replace('/ordercp/order/list?page={page}','/ordercp/order/exportorder?',$query)}">导出物流EXCEL</a>
												</li>
												<li>
													<a target="_blank" href="{str_replace('/ordercp/order/list?page={page}','/ordercp/order/exportitem?',$query)}">导出商品EXCEL</a>
												</li>
												<li class="divider"></li>
												<li>
													<a target="_blank" href="/ordercp/order/importsend">导入发货单</a>
												</li>
											</ul>
										</div>
									</div> -->
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<!-- <th class="table-checkbox">
									<input type="checkbox" class="group-checkable"/>
								</th> -->
								<th>
									 活动名称
								</th>
								<th>
									 参赛选手
								</th>
								<th>
									 投票者
								</th>
								<th>
									 手机号
								</th>
								<th>
									投票时间
								</th>
								<!-- <th>
									操作
								</th> -->
																			
							</tr>
							</thead>
							<tbody>
							
							{foreach $idList as $i => $id}
							<tr dataid="{$id.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<!-- <td>
									<input type="checkbox" class="checkboxes" value="1"/>
								</td> -->
								<td>
										{$id.vote_name}										
								</td>
								<td>
										{$id.name}
								</td>
								<td>
										{$id.member_name}
								</td>
								<td>
										{$id.phone}
								</td>
								<td>    
										{date('Y-m-d H:i:s',$id.dateline)}				
								</td>
								<!-- <td>              
								 	<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a> 
								</td> -->
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
