{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
			
			<div class="row table-scrollable">
			<form action="/usercp/member/list" method="get">
			<table id="datatable_members" class="table table-striped table-bordered table-hover dataTable no-footer" aria-describedby="datatable_members_info" role="grid">
			<thead>
			<tr class="heading" role="row">
					<th width="1%" class="sorting_disabled" rowspan="1" colspan="1">
					<div class="checker"><span><input type="checkbox" class="group-checkable"></span></div>
					</th>
					<th width="30%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 帐号
					</th>
					<th width="25%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 角色
					</th>
					<th width="25%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 状态
					</th>
					<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
					 </th>
			</tr>
			<tr class="filter" role="row">
					<td rowspan="1" colspan="1">
					</td>
					<td rowspan="1" colspan="1">
						<input type="text" name="account" class="form-control form-filter input-sm" value="{$params.account}">
					</td>
					<td rowspan="1" colspan="1">
							<select class="form-control form-filter input-sm" name="role">
									<option value="">全部</option>
									<option {if $params.role == 'member'}selected="selected"{/if} value="0">普通用户</option>
									<option {if $params.role == 'salesman'}selected="selected"{/if} value="salesman">销售</option>
									<option {if $params.role == 'supplier'}selected="selected"{/if} value="supplier">供货商</option>
									<option {if $params.role == 'merchant'}selected="selected"{/if} value="merchant">批发商</option>
									<option {if $params.role == 'agent'}selected="selected"{/if} value="agent">代理商</option>
									<option {if $params.role == 'admin'}selected="selected"{/if} value="admin">管理员</option>
							</select>
					</td>
					<td rowspan="1" colspan="1">
							<select class="form-control form-filter input-sm" name="status">
									<option value="">全部</option>
									<option {if $parmas.status == 1}selected="selected"{/if} value="1">可用</option>
									<option {if $params.status == -1}selected="selected"{/if} value="-1">冻结</option>
							</select>
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
			</form>
			</div>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								会员认证列表
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
										<!--	<a class="btn green" href="/usercp/member/add">
											新增 <i class="fa fa-plus"></i>-->
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
								<th>帐号</th>
								<th>姓名</th>
								<th>身份证号</th>
								<th>银行</th>
								<th>银行卡号</th>
								<th>注册时间</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
							</thead>
							<tbody>
							{foreach $memberList as $i => $auth}
							<tr dataid="{$member.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									<input type="checkbox" class="checkboxes" value="1"/>
								</td>
								<td>
									{$auth.member_id}
								</td>
								<td>
									{$auth.name}
								</td>
								<td>
									{$auth.idcard_no}
								</td>
								<td>
   							        {if $auth.bank_type==0}
 									      支付宝	
							        {elseif $auth.bank_type==1}
									        微信
								 
									{/if} 
									
								</td>
								<td>
									{$auth.card_no} 
								</td>
						 
								<td>
									{date('Y-m-d H:i:s',$auth.dateline)}
								</td>
								<td> 
							        {if $auth.status==1}
 									         待审核	
							        {elseif $auth.status==2}
									        已认证
									{elseif $auth.status=='-1'}
									       被驳回 
									{else}       
							   		      未提交
									{/if}  
								</td>
			                	<td>
	<a href="/usercp/auth/edit?member_id={$auth.member_id} " class="btn green">审核 <i class="glyphicon glyphicon-edit"></i></a>
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

<!-- 设置编码 -->
<div id="code_form" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">设置编码</h4>
						</div>
						
						<div class="modal-body">
						
						<form class="form-horizontal form-row-seperated" action="/ordercp/order/send" method="post">
								<!-- form-body -->
								<div class="form-body">
										
										<div class="form-group">
											<label class="col-md-3 control-label">编码</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="code" placeholder="" value="">
											</div>
										</div>
								
								</div>
								<!-- form-body /-->
								
								<!-- form-action -->
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-offset-2 col-md-9">
												<input type="hidden" name="id" value="" />
												<button id="code_submit" class="btn yellow" type="button">提交</button>
										</div>
									</div>
								</div>
								<!-- form-action /-->
						</form>
						
						</div>
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- 设置编码 /-->

<!-- 银行卡 -->
<div id="bankcard_form" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">设置银行卡</h4>
						</div>
						
						<div class="modal-body">
						
						<form class="form-horizontal form-row-seperated" action="/ordercp/order/send" method="post">
								<!-- form-body -->
								<div class="form-body">
										
										<div class="form-group">
											<label class="col-md-3 control-label">银行信息</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="bank" placeholder="" value="">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-3 control-label">卡号</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="bankcard" placeholder="" value="">
											</div>
										</div>
								
								</div>
								<!-- form-body /-->
								
								<!-- form-action -->
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-offset-2 col-md-9">
												<input type="hidden" name="id" value="" />
												<button id="bankcard_submit" class="btn yellow" type="button">提交</button>
										</div>
									</div>
								</div>
								<!-- form-action /-->
						</form>
						
						</div>
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- 银行卡 /-->