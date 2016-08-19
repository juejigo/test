{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">

<!-- BEGIN PAGE CONTENT-->
<div class="row">
		<div class="col-md-12">
				<!-- 错误提醒 -->
				{if $error->hasError()}
				{foreach $error->getAll() as $e}
				<div class="alert alert-error">
						<button type="button" class="close">&times;</button>
						<strong>错误！</strong> {array_shift($e)}
				</div>
				{/foreach}
				{/if}
				<!-- 错误提醒 /-->
				
				<form method="post" class="form-horizontal form-row-seperated" action="/usercp/member/add">
						<div class="portlet">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										会员
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
										
										<!-- 标签 -->
										<ul class="nav nav-tabs" role="tablist">
												<li role="presentation" class="active"><a href="#base_pane" aria-controls="base_pane" role="tab" data-toggle="tab">基本信息</a></li>
												<li role="presentation"><a href="#profile_pane" aria-controls="image_pane" role="tab" data-toggle="tab">用户资料</a></li>
										</ul>
										<!-- 标签 /-->
										
										<!-- Tab panes -->
										<div class="tab-content">
										
												<div role="tabpanel" class="tab-pane active" id="base_pane">
														<!-- form-body -->
														<div class="form-body">
														
																<div class="form-group">
																	<label class="col-md-2 control-label">帐号</label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="account" placeholder="" value="{$data.account}">
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">密码</label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="password" placeholder="" value="">
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">角色</label>
																	<div class="col-md-10">
																			<select name="role" class="form-control">
																					<option value="member">会员</option>
																					<option {if $data.role == 'seller'}selected="selected"{/if} value="seller">门店</option>
																					<option {if $data.role == 'partner'}selected="selected"{/if} value="partner">城市合伙人</option>
																					<option {if $data.role == 'supplier'}selected="selected"{/if} value="supplier">供货商</option>
																					<option {if $data.role == 'admin'}selected="selected"{/if} value="admin">管理员</option>
																			</select>
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">等级</label>
																	<div class="col-md-10">
																			<select id="group" name="group" class="form-control">
																			{if $data.role == 'member'}
																					<option value="0">普通会员</option>
																					<option {if $data.group == 1}selected="selected"{/if} value="1">口袋主人</option>
																					<option {if $data.group == 2}selected="selected"{/if} value="2">分销商</option>
																					<option {if $data.group == 3}selected="selected"{/if} value="3">代理商</option>
																					<option {if $data.group == 4}selected="selected"{/if} value="4">渠道商</option>
																					<option {if $data.group == 5}selected="selected"{/if} value="5">股东商</option>
																			{else}
																					<option value="0">普通会员</option>
																			{/if}
																			</select>
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">状态</label>
																	<div class="col-md-10">
																			<select id="status" class="form-control" name="status">
																					<option value="1">有效</option>
																					<option {if $data.status == '-1'}selected="selected"{/if} value="-1">冻结</option>
																			</select>
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">有效期</label>
																	<div class="col-md-10">
																			<div data-date-format="yyyy-mm-dd" class="input-group date date-picker margin-bottom-5">
																				<input type="text" name="deadline" readonly="" class="form-control form-filter input-sm" value="{$params.deadline}">
																				<span class="input-group-btn">
																				<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
																				</span>
																			</div>
																	</div>
																</div>
														
														</div>
														<!-- form-body /-->
												</div>
												
												<div role="tabpanel" class="tab-pane" id="profile_pane">
												
														<!-- form-body -->
														<div class="form-body">
														
																<div class="form-group">
																	<label class="col-md-2 control-label">昵称</label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="member_name" placeholder="" value="{$data.member_name}">
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">别名</label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="alias" placeholder="" value="{$data.alias}">
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">性别</label>
																	<div class="col-md-10">
																			<select class="form-control" name="sex">
																					<option value="0">女</option>
																					<option {if $data.sex == 1}selected="selected"{/if} value="1">男</option>
																			</select>
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">手机</label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="mobile" placeholder="" value="{$data.mobile}">
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">座机</label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="telephone" placeholder="" value="{$data.telephone}">
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">地区</label>
																	<div id="select_region" class="col-md-10">
																			
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">地址</label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="address" placeholder="" value="{$data.address}">
																	</div>
																</div>
																
																<div class="form-group">
																	<label class="col-md-2 control-label">备注</label>
																	<div class="col-md-10">
																			<textarea class="form-control" name="memo"></textarea>
																	</div>
																</div>
														
														</div>
														<!-- form-body /-->
												
												</div>
										</div>
										<!-- Tab panes /-->
										
										
										
										<!-- form-action -->
										<div class="form-actions fluid">
											<div class="row">
												<div class="col-md-offset-2 col-md-9">
													<button class="btn yellow" type="submit">提交</button>
												</div>
											</div>
										</div>
										<!-- form-action /-->
								</div>
								<!-- portlet-body /-->
						</div>
				</form>
		</div>
</div>
<!-- END PAGE CONTENT-->

{include file='public/admincp/footer.tpl'}

<script id="js_region" type="text/javascript" src="{$smarty.const.URL_JS}public/region.js" p="{$data.province_id}" c1="{$data.city_id}" c2="{$data.county_id}"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>