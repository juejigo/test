{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
			
			<div class="row table-scrollable">
			<form action="/usercp/member/list" method="get">
			<table id="datatable_members" class="table table-striped table-bordered table-hover dataTable no-footer" aria-describedby="datatable_members_info" role="grid">
			<thead>
			<tr class="heading" role="row">
					<th width="10%" class="sorting_disabled" rowspan="1" colspan="1">
					ID
					</th>
					<th width="15%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 帐号
					</th>
					<th width="15%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 推荐人
					</th>
					<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 角色
					</th>
					<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 等级
					</th>
					<th width="10%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 下载
					</th>
					<th width="15%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
						 状态
					</th>
					<th width="15%" class="sorting" tabindex="0" aria-controls="datatable_members" rowspan="1" colspan="1">
					 </th>
			</tr>
			<tr class="filter" role="row">
					<td rowspan="1" colspan="1">
						<input type="text" name="id" class="form-control form-filter input-sm" value="{$params.id}">
					</td>
					<td rowspan="1" colspan="1">
						<input type="text" name="account" class="form-control form-filter input-sm" value="{$params.account}">
					</td>
					<td rowspan="1" colspan="1">
						<input type="text" name="referee" class="form-control form-filter input-sm" value="{$params.referee}">
					</td>
					<td rowspan="1" colspan="1">
							<select class="form-control form-filter input-sm" name="role">
									<option value="">全部</option>
									<option {if $params.role == 'member'}selected="selected"{/if} value="member">用户</option>
									<option {if $params.role == 'admin'}selected="selected"{/if} value="admin">管理员</option>
							</select>
					</td>
					<td rowspan="1" colspan="1">
							<select id="group" name="group" class="form-control form-filter input-sm">
									<option value="">请选择等级</option>
							</select>
					</td>
					<td rowspan="1" colspan="1">
							<select id="imei" name="imei" class="form-control form-filter input-sm">
									<option value="">全部</option>
									<option {if $params.imei == '1'}selected="selected"{/if} value="1">是</option>
									<option {if $params.imei === '0'}selected="selected"{/if} value="0">否</option>
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
								会员列表（{$count}）
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
											<a class="btn green" href="/usercp/member/add">
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
								<th>ID</th>
								<th>帐号</th>
								<th>姓名</th>
								<th>角色</th>
								<th>等级</th>
								<th>app</th>
								<th>注册时间</th>
								<th>状态</th>
							</tr>
							</thead>
							<tbody>
							{foreach $memberList as $i => $member}
							<tr dataid="{$member.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									<input type="checkbox" class="checkboxes" value="1"/>
								</td>
								<td>
									{$member.id}
								</td>
								<td>
									{$member.account}
								</td>
								<td>
									{$member.member_name}
								</td>
								<td>
									{fieldvalue field='memberRole' value=$member.role}
								</td>
								<td>
									{$member.group_name}
								</td>
								<td>
									{if $member.imei_id > 0}<font color="green">已下载</font>{else}未下载{/if}
								</td>
								<td>
									{date('Y-m-d H:i:s',$member.register_time)}
								</td>
								<td>
									<a href="/usercp/member/edit?id={$member.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
									<a href="/usercp/privilege/authlist?id={$member.id}" class="btn gray">添加权限 <i class="glyphicon glyphicon-add"></i></a>
									<span class="pull-right btn btn-sm text-muted look_tr"><i class="fa fa-angle-double-down"></i> 展开</span>
									{if $member.role == 'supplier' || $member.role == 'salesman'}
									<a code="{$member.code}" href="javascript:;" data-toggle="modal" data-target="#code_form" class="code btn green">设置编码 <i class="glyphicon glyphicon-edit"></i></a>
									<a bankcard="{$member.bankcard}" href="javascript:;" data-toggle="modal" data-target="#bankcard_form" class="bankcard btn green">银行卡 <i class="glyphicon glyphicon-edit"></i></a>
									{/if}
								</td>
							</tr>
							<tr id="look{$member.id}" style="display:none;" >
								<td colspan="10">
									<div class="col-md-4 pull-right user_info">
										<div class="user_info_left">
											<div class="img_box"><img class="user_img" src="{$member.avatar}"></div>
											<p>{$member.member_name}</p>
											<div class="left_btn">
												<a href="javascript:;" onclick="reloadWx({$member.id},this)">更新微信资料</a>
											</div>
											<div class="img_box"><img src="{$member.url}"></div>
											<div class="left_btn">
												<a target="_blank" href="{$member.url}">下载二维码</a>
											</div>
										</div>
										<div class="user_info_right">
										<dl>
											<dt><span>基本信息</span></dt>
											<dd><div class="name">id:</div><div class="info">{$member.id}</div></dd>
											<dd><div class="name">账号:</div><div class="info">{$member.account}</div></dd>
											<dd><div class="name">注册时间:</div><div class="info">{date('Y-m-d',$member.register_time)}</div></dd>
											<dd><div class="name">推荐人ID:</div><div class="info">{$member.referee_id}</div></dd>
											<dd><div class="name">注册来源:</div><div class="info">
											{if $member.register_from == 0}未知
											{else if $member.register_from == 10}app（APP直接注册）
											{else if $member.register_from == 20}移动端（从公众号直接进入）
											{else if $member.register_from == 21}私聊（微信分享给朋友）
											{else if $member.register_from == 22}朋友圈（微信分享朋友圈）
											{else if $member.register_from == 23}二维码扫描（二维码扫描）
											{else if $member.register_from == 30}PC（PC直接注册）
											{/if}
											</div></dd>
											<dd><div class="name">角色:</div><div class="info">
											{if $member.role == 'member'}用户
											{else if $member.role == 'supplier'}供货商
											{else if $member.role == 'admin'}管理员
											{/if}
											</div></dd>
											<dd><div class="name">等级:</div><div class="info">{$member.group_name}</div></dd>
											<dd><div class="name">状态:</div><div class="info">{if $member.status == 1}有效{else}冻结{/if}</div></dd>
											<dd><div class="name">消费金额:</div><div class="info">{$member.consumption}</div></dd>
											<dd><div class="name">下单数:</div><div class="info">{$member.order_count}</div></dd>
										</dl>
										</div>
									</div>
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