{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}


<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jstree/dist/themes/default/style.min.css"/>
<link href="../static/style/default/mix/metronic3.6/theme/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.css">
<link href="{$smarty.const.URL_MIX}metronic3.6/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/theme/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/theme/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/theme/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/theme/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
 <!--content-wrapper-->
    <div class="page-content-wrapper">
        <div class="page-content" style="min-height:812px">
            <!--标题栏-->
            <h3 class="page-title">修改商品 <small>商品名：{$data.product_name}</small></h3>
           		<!-- 错误提醒 -->
            <div class="error_msg"></div>
            <!-- 错误提醒  /-->
            <!--路径导航-->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品管理</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/edit?id={$data.id}">修改商品</a></li>
                </ul>
            </div>
            <!--表单-->
            <div class="row">
                <div class="col-md-12">
                    <form method="post" class="form-horizontal form-row-seperated form" id="form-horizontal" action="ajax?op=edit_product">
                        <input type="hidden" name="product_id" id="pordId" value="{$product_id}">
                        <div class="portlet">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-edit"></i>内容设置
                                </div>
                                <div class="pull-right">
                                    <a href="/productcp/product/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
                                    <button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
                                    <button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#basePane" aria-controls="basePane" role="tab" data-toggle="tab">基本信息</a></li>
                            <li role="presentation"><a href="#scPane" aria-controls="scPane" role="tab" data-toggle="tab">产品特色</a></li>
                            <li role="presentation"><a href="#costPane" aria-controls="costPane" role="tab" data-toggle="tab">须知费用</a></li>
                            <li role="presentation"><a href="#searchPane" aria-controls="searchPane" role="tab" data-toggle="tab">搜索相关</a></li>
                        	<li role="presentation"><a href="#tagPane" aria-controls="tagPane" role="tab" data-toggle="tab">标签</a></li>
                        </ul>
                        <div class="tab-content">
                            <!--基本信息form-->
                            <div role="tabpanel" class="tab-pane active" id="basePane">
                                <div class="form-body">
                                    <!--商品分类-->
                                    <input type="hidden" name="cate_id" id="prodClass" value="" placeholder="已经添加的商品分类">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">分类：</label>
                                        <div class="col-md-6">
                                            <div class="form-control-static prod_class_box" id="prodClassBox"></div>
                                            <div class="pord_add_class" data-toggle="modal" href="#prodClassModal">选择分类 <i class="fa fa-plus"></i></div>
                                            <span class="help-block text-primary">通过选择分类按钮来添加或修改需要的分类。</span>
                                        </div>
                                    </div>
                                    <div class="modal fade bs-modal-sm" id="prodClassModal" tabindex="-1" role="basic" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">选择分类</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="prodClassTree"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn green" data-dismiss="modal">确定</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--商品分类 /-->
                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">供应商：</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="supplier_id" >
                                                <option value="">请选择</option>
                                                  {foreach $supplier as $row}
                                                <option  {if $data.supplier_id == $row.id} selected="selected" {/if}value="{$row.id}">{$row.supplier_name}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">旅游方式：</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="tourism_type" id="">
                                                <option value="">请选择</option>
                                                {foreach $tourism_type as $row}
                                        <option  {if $data.tourism_type == $row.id} selected="selected" {/if}value="{$row.id}">{$row.tourism_type}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    
                                        <div class="form-group">
                                        <label class="col-md-2 control-label">类型：</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="type" id="typeSelect">
                                                <option value="0">请选择</option>
                                                  {foreach $type as $row}
                                                <option  {if $data.type_id == $row.id} selected="selected" {/if}value="{$row.id}">{$row.type_name}</option>
                                         
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">标题：</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="product_name"  value="{$data.product_name}" placeholder="商品标题">
                                        </div>
                                    </div>
                                   <div class="form-group">
                                        <label class="col-md-2 control-label">副标题：</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="brief"  value="{$data.brief}" placeholder="商品标题">
                                        </div>
                                    </div>
                                     <div class="form-group">
                                    	<label class="col-md-2 control-label">货号：</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="{$data.sn}"  name="sn" placeholder="商品货号">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">出发城市：</label>
                                        <div class="col-md-10">
                                        
                                            <div class="select_region"  rel='["province":{$region.province_id},"city":{$region.city_id}]'>
                                                <select name="province_id" id="province_id" class="form-control input-small input-inline"><option value="0">请选择</option></select>
                                                <select name="city_id" id="city_id" class="form-control input-small input-inline"><option value="0">请选择</option></select>
                                               <!-- <select name="county_id" id="county_id" class="form-control input-small input-inline"><option value="0">请选择</option></select>  -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">成本价：</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="mktprice" value="{$data.mktprice}" placeholder="成本价">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">销售价：</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="price" value="{$data.price}" placeholder="销售价">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">市场价：</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="cost_price"  value="{$data.cost_price}" placeholder="市场价">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    	<label class="col-md-2 control-label">儿童价：</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="child_price"  value="{$data.child_price}" placeholder="儿童价">
                                        </div>
                                    </div>
                                    <!--<div class="form-group">
                                        <label class="col-md-2 control-label">库存：</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="stock" value="{$data.stock}"  placeholder="库存">
                                        </div>
                                    </div>-->
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">限购人数：</label>
                                        <div class="col-md-3">
                                                <input type="text" class="form-control" name="stock" value="{$data.stock}"placeholder="限购人数">
                                        </div>
                                    </div>

								<div class="form-group">
									<label class="col-md-2 control-label">出发日期：</label>
									<div class="col-md-4">
										<input type="text" class="form-control" name="travel_date"id="goTime" value="{date('Y-m-d',$data.travel_date)}">
										 <span class="help-block text-primary">出发日期不能大于规格日期。</span>
									</div>
								</div>
                                <!--类型搭配勾选-->
                                    <div id="typesCheck">
                                    </div>
                                    <!--类型搭配勾选--> 
								<!--规格-->
                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">规格：</label>
                                        <div class="col-md-10">
                                            <div class="form-control-static spec_class_box">
                                                <ul>
                                                 	{foreach $data.item_s as $i=>$val}
                                                    <li class="spec_class">
                                                    	<a href="#tab{$i+1}" data-toggle="tab" aria-expanded="true">
                                                    	<span class="time{$i+1}">{$val.time}</span>
                                                    	<button type="button" class="btn btn-default btn-xs" onclick="specDel(this,'{$i+1}')">
                                                    	<i class="fa fa-times"></i> 删除</button>
                                                    	<button type="button" class="btn btn-default btn-xs time_change">
                                                    	<i class="fa fa-pencil"></i> 选择时间</button>
                                                    	<input type="hidden" name="item_s[{$i+1}][time]" value="{$val.time}"></a>
                                                    	<input type="hidden"  name="item_s[{$i+1}][product_id]" value="{$val.product_id}" ></td>
                                                    	</li>
                                                    	
                                                    	
                                                    {/foreach}
                                                </ul>
                                            </div>
                                            <div class="spec_add_class">新增规格 <i class="fa fa-plus"></i></div>
                                          <!--   <div class="spec_copy_class">复制规格 <i class="fa fa-copy"></i></div>-->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label"></label>
                                        <div class="col-md-10">
                                            <div class="tab-content spec_table_box">
                                              {foreach $data.item_s as $i=>$val}
                                                <div class="tab-pane fade" id="tab{$i+1}" data-tableid="{$i+1}">
                                                  <table class="table table-bordered" data-mc="{$val.data_mc}">
                                                    <thead>
                                                        <tr role="row">
															 	 	{if $val.row.0.specval_1 != ""}
                                                            		<th class="sorting_asc" width="150px">{$val.row.0.spec_name_1}</th>
                                                            		{/if}
                                                            			{if $val.row.0.specval_2 != ""}
                                                            		<th class="sorting_asc" width="150px">{$val.row.0.spec_name_2}</th>
                                                            		{/if}
                                                            			{if $val.row.0.specval_3 != ""}
                                                            		<th class="sorting_asc" width="150px">{$val.row.0.spec_name_3}</th>
                                                            		{/if}
                                                            <th class="sorting_asc" width="200px">货号</th>
                                                            <th class="sorting_asc" width="200px">产品名 </th>
                                                            <th class="sorting_asc" width="100px">售价</th>
                                                            <th class="sorting_asc" width="100px">成本价</th>
                                                            <th class="sorting_asc" width="100px">市场价</th>
                                                            <th class="sorting_asc" width="100px">儿童价</th>
                                                            <th class="sorting_asc" width="100px">库存</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
														{foreach $val.row as $j=>$item}
														<tr>
														{if $item.specval_1 != ""}
															<td>
																<input type="hidden"name="item_s[{$i+1}][row][{$j}][spec_1]" value="{$item.specval_1}"><span>{$item.specval_1_val}</span></td>
															{/if}
															{if $item.specval_2 != ""}
															<td>
																<input type="hidden"name="item_s[{$i+1}][row][{$j}][spec_2]" value="{$item.specval_2}"><span>{$item.specval_2_val}</span></td>
															{/if}
															{if $item.specval_3 != ""}
															<td>
																<input type="hidden"name="item_s[{$i+1}][row][{$j}][spec_3]" value="{$item.specval_3}"><span>{$item.specval_3_val}</span></td>
															{/if}
															<td>
																<input type="text" class="form-control input-sm"placeholder="货号" name="item_s[{$i+1}][row][{$j}][fn]"  value="{$item.fn}" ></td>
															<td>
																<input type="text" class="form-control input-sm"placeholder="产品名" name="item_s[{$i+1}][row][{$j}][item_name]" value="{$item.item_name}" ></td>
															<td>
																<input type="text" class="form-control input-sm"placeholder="售价" name="item_s[{$i+1}][row][{$j}][price]" value="{$item.price}"></td>
															<td>
																<input type="text" class="form-control input-sm"placeholder="成本价" name="item_s[{$i+1}][row][{$j}][cost_price]" value="{$item.cost_price}"></td>
															<td>
																<input type="text" class="form-control input-sm"placeholder="市场价" name="item_s[{$i+1}][row][{$j}][mktprice]"  value="{$item.mktprice}" ></td>
															<td>
																<input type="text" class="form-control input-sm"placeholder="儿童价" name="item_s[{$i+1}][row][{$j}][child_price]"  value="{$item.child_price}" ></td>
															
															<td>
																<input type="text" class="form-control input-sm"placeholder="库存" name="item_s[{$i+1}][row][{$j}][stock]" value="{$item.stock}" ></td>
																<input type="hidden" class="form-control input-sm"placeholder="库存" name="item_s[{$i+1}][row][{$j}][item_id]" value="{$item.id}" ></td>
														</tr>

														{/foreach}
													</tbody>
                                                  </table>
                                                </div>
                                                           {/foreach}
                                            </div>
                                        </div>
                                    </div>

                                    <!--规格 /-->
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">上架时间：</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form_datetime" name="up_time" value="{$data.up_time}" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">下架时间：</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control form_datetime" name="down_time" value="{$data.down_time}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">补差价提示：</label>
                                        <div class="col-md-6">
                                            <textarea class="form-control" name="information" placeholder="补差价提示信息" rows="4">{$data.information}</textarea>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-2 control-label">出行人数限制：</label>
                                        <div class="col-md-6">
                                            <textarea class="form-control" name="travel_restrictions" placeholder="出行人数限制" rows="4">{$data.travel_restrictions}</textarea>
                                        </div>
                                    </div>
                                                                         <div class="form-group">
                                        <label class="col-md-2 control-label">优惠信息：</label>
                                        <div class="col-md-6">
                                            <textarea class="form-control" name="discount_information" placeholder="优惠信息" rows="4">{$data.discount_information}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--基本信息form /-->
                            <!--产品特色-->
                            	
                            
                            <div role="tabpanel" class="tab-pane" id="scPane">
	                            <div class="form-group">
	                             	<label class="col-md-2 control-label">产品特色摘要：</label>
	                                 <div class="col-md-6">
	                                     <textarea class="form-control" id="explain" name="features_info"  placeholder="产品特色摘要" rows="4"> {$data.features_info}</textarea>
	                                 </div>
	                             </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">产品特色：</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control ke" name="features_content"> {html content=$data.features_content}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!--产品特色 /-->
                            <!--须知费用-->
                            <div role="tabpanel" class="tab-pane" id="costPane">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">须知费用：</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control ke" name="cost_need">{html content=$data.cost_need}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!--须知费用 /-->
                            <!--搜索相关-->
                            <div role="tabpanel" class="tab-pane" id="searchPane">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">标题：</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="seo_title"  value="{$data.seo_title}" placeholder="标题：">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">关键字：</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="seo_keywords" value="{$data.seo_keywords}"  placeholder="关键字">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">描述：</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="seo_description" placeholder="描述"  rows="4">{$data.seo_description}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!--搜索相关 /-->
                            <!--标签相关-->
                           	<div role="tabpanel" class="tab-pane" id="tagPane">
                           	  <div class="note note-success">请勾选需要的标签，对啊</div>
                              <div class="row margin-bottom-20">
                              {foreach $tags as $tag}
                                <div class="col-md-2 col-sm-4"><label><input type="checkbox" class="icheck" data-checkbox="icheckbox_square-grey" value="{$tag.id}" {if !empty($tagIds) && in_array($tag.id,$tagIds)}checked="checked"{/if} name="tags[]">{$tag.tag_name}</label></div>
                              {/foreach}
                              </div>
                           	</div>
                           	<!--标签相关 /-->
                        </div>
                        <!--下一步-->
                        <div class="form-actions">
                            
                        </div>
                        <!--下一步-->
                    </form>
                </div>
            </div>
            <!--表单 /-->
        </div>
    </div>
    <!--content-wrapper /-->

{include file='public/admincp/footer.tpl'}
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jstree/dist/jstree.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/plupload/js/plupload.full.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/icheck.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="{$smarty.const.URL_JS}productcp/product/regionnew.js" ></script>
<script src="{$smarty.const.URL_JS}productcp/product/product.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>

