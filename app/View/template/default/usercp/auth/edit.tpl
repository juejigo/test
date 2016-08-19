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
 
				<form method="post" class="form-horizontal form-row-seperated" action="/usercp/auth/edit/?member_id={$data.member_id}"> 
						<div class="portlet">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										用户认证信息 
									</div>
								</div>
 
								<!-- portlet-body -->
								<div class="portlet-body form">
										
										<!-- form-body -->
										<div class="form-body">
									  
				 
													
													<div class="form-group">
														<label class="col-md-2 control-label">名称 <span class="required">* </span></label>
														<div class="col-md-10">
																<input type="text" class="form-control" name="name" placeholder="" disabled value="{$data.name}">
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-md-2 control-label">电话 <span class="required">* </span></label>
														<div class="col-md-10">
																<input type="text" class="form-control" name="mobile" placeholder="" disabled value="{$data.mobile}">
														</div>
													</div>															
															
													<div class="form-group">
														<label class="col-md-2 control-label">身份证号码 <span class="required">* </span></label>
														<div class="col-md-10">
																<input type="text" class="form-control" name="idcard_no" placeholder=""  disabled value="{$data.idcard_no}">
														</div>
													</div>		

 
													
													<div class="form-group">
														<label class="col-md-2 control-label">身份证正反面 <span class="required">* </span></label>
														<div class="col-md-10">
									
{if !empty({$data.img_1})} <img src="{$data.img_1}"  width="130" > {else} 正面照未上传{/if}				 
														
{if !empty({$data.img_2})} <img src="{$data.img_2}"  width="130" > {else} &nbsp;&nbsp;&nbsp;&nbsp;反面照未上传 {/if}	
														</div>
													</div>														
													

															
													<div class="form-group">
														<label class="col-md-2 control-label">银行 <span class="required">* </span></label>
														<div class="col-md-10">
																<input type="text" class="form-control" name="bank_type" placeholder="" disabled value="{if $auth.bank_type==0}支付宝 {elseif $auth.bank_type==1}微信{/if} ">
														</div>
													</div>		
														
													<div class="form-group">
														<label class="col-md-2 control-label">银行卡号 <span class="required">* </span></label>
														<div class="col-md-10">
																<input type="text" class="form-control" name="card_no"   disabled  value="{$data.card_no}">
														</div>
													</div>														
														
													<div class="form-group">
														<label class="col-md-2 control-label">状态 <span class="required">* </span></label>
														<div class="col-md-10">
 <select  class="select_input" {if $data.status=="2"}selected disabled{/if} name="status">
    
	 <option value="-1" {if $data.status==="-1"}selected{/if} >驳回 </option>
	 <option value="1" {if $data.status=="1"}selected{/if} >待审核</option>
	 <option value="2" {if $data.status=="2"}selected {/if}>审核通过</option>
	
 </select>
														</div>
													</div>														
																									
														<div class="form-group">
														<label class="col-md-2 control-label">备注 <span class="required">* </span></label>
														<div class="col-md-10">
 <textarea name="memo" class="form-control" rows="">{$data.memo}</textarea>
														</div>
													</div>											
												

															
										</div>
										<!-- form-body /-->
										
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

<script type="text/javascript">var pagevar = { 'news_id' : 0 }</script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/themes/default/default.css"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>