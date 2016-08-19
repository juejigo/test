$(function()
{
	// 时间
	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true,
    });
    
	// 上传图片
	$('.upload').click(function()
	{
		imageUploader.show('product',callback);
	})
	
	// 删除图片
	$(document).on('click','.image-delete',function(event)
	{
		var id = $(this).closest('.thumbnail').attr('dataid');
		var e = $(this);
		
		$.post('/productcp/image/ajax?op=delete',{'image_id':id},function(json)
			{
				if (json.flag == 'success')
				{
					// 如果删除的是默认图片
					if (e.find('defaultimage'))
					{
						$('.thumbnail').eq(0).addClass('defaultimage');
					}
					
					e.closest('.col-md-2').remove();
				}
				else if (json.flag == 'error')
				{
					showMessage(json.msg);
				}
			})
		
		 event.stopPropagation();
	})
	
	// 设置默认图片
	$(document).on('click','.image-default',function()
	{
		var id = $(this).closest('.thumbnail').attr('dataid');
		var e = $(this).closest('.thumbnail');
		
		$.post('/productcp/image/ajax?op=setdefault',{'product_id':pagevar.product_id,'image_id':id},function(json)
		{
			if (json.flag == 'success')
			{
				$('.thumbnail').removeClass('defaultimage');
				e.addClass('defaultimage');
			}
			else if (json.flag == 'error')
			{
				showMessage(json.msg); 
			}
		})
	})
	
	// 排序
	$(document).on('change','input[name="order"]',function()
	{
		var id = $(this).closest('.thumbnail').attr('dataid');
		var order = $(this).val();
		var e = $(this);
		
		$.post('/productcp/image/ajax?op=setorder',{'image_id':id,'order':order},function(json)
		{
			if (json.flag == 'error')
			{
				showMessage(json.msg);
			}
		})
	})
	
	// 分类选择加载商品类型选项
	$(document).on('change','select[name="cate_id"]',function()
	{
		var id = $(this).val();
		
		if (id > 0)
		{
			$.post('/productcp/product/ajax?op=catechanged',{'cate_id':id},function(json)
			{
				if (json.errno == 0)
				{
					$('#attrs').html(json.attrs);
					
					if (json.specs != '')
					{
						if ($('.item').length == 0)
						{
							$('#specs').find('#spec-bd').html(json.specs);
						}
						
						if ($('input[name="open_spec"]:checked').val() == '1')
						{
							$('#specs').find('#spec-bd').show();
						}
					}
					else
					{
						$('input[name="open_spec"][value=0]').attr("checked",true).closest('span').addClass('checked');
						$('input[name="open_spec"][value=1]').attr("checked",false).closest('span').removeClass('checked');
						$('#specs').find('#spec-bd').html(json.specs).hide();
					}
				}
				else if (json.errno > 0)
				{
					showMessage(json.errmsg);
				}
			})
		}
		else
		{
			$('#attrs').html('');
			$('#specs').find('#spec-bd').html('').hide();
		}
	})
	
	// 打开规格
	$(document).on('change','input[name="open_spec"]',function()
	{
		var value = $(this).val();
		
		if (value == '1')
		{
			$('#spec-bd').show();
		}
		else
		{
			$('#spec-bd').hide();
		}
	})
	
	// 添加规格
	$(document).on('click','#addspec',function()
	{
		var cateid = $('select[name="cate_id"]').val();
		var art = $('input[name="art"]').val();
		var product_name = $('input[name="product_name"]').val();
		var price = $('input[name="price"]').val();
		var cost_price = $('input[name="cost_price"]').val();
		var mktprice = $('input[name="mktprice"]').val();
		var stock = $('input[name="stock"]').val();
		var index = 0;
		
		$('.item').each(function(i,e)
		{
			var i = parseInt($(e).attr('index'));
			if (index < i)
			{
				index = i;
			}
		})
		index += 1;
		
		if (cateid > 0)
		{
			$.post('/productcp/product/ajax?op=addspec',{'cate_id':cateid,'art':art,'product_name':product_name,'price':price,'cost_price':cost_price,'mktprice':mktprice,'stock':stock,'index':index},function(json)
			{
				if (json.errno == 0)
				{
					if (json.specs != '')
					{
						$('#specs').find('#spec-table').append(json.html);
					}
				}
				else if (json.errno > 0)
				{
					showMessage(json.errmsg);
				}
			})
		}
	})
	
	// 删除规格
	$(document).on('click','.delspec',function()
	{
		$(this).closest('tr').remove();
	})
	
	// kindeditor
	KindEditor.ready(function(K) {
	    window.editor = K.create('#detail',{
	        //cssPath:'/public/plugin/editor/plugins/code/prettify.css',
	        uploadJson:'/imageuc/kindeditor/upload?',
	        extraFileUploadParams : {
	            cookie : $.cookie('session_id')
            },
	        resizeType :1,
	        allowPreviewEmoticons : true,
	        allowImageUpload : true,
	      });	
	});
	
	// 把价格应用到所有规格中去
	$(document).on('click','#app_name',function()
	{
		var value = $(this).closest('.input-group').find('input').val();
		
		$('input[name*="][item_name]"]').val(value);
	});
	$(document).on('click','#app_price',function()
	{
		var value = $(this).closest('.input-group').find('input').val();
		
		$('input[name*="][price]"]').val(value);
	});
	$(document).on('click','#app_cost',function()
	{
		var value = $(this).closest('.input-group').find('input').val();
		
		$('input[name*="][cost_price]"]').val(value);
	});
	$(document).on('click','#app_mkt',function()
	{
		var value = $(this).closest('.input-group').find('input').val();
		
		$('input[name*="][mktprice]"]').val(value);
	});
	
	// 批量增加规格
	$(document).on('click','#spec_submit',function()
	{
		var cateid = $('select[name="cate_id"]').val();
		var art = $('input[name="art"]').val();
		var product_name = $('input[name="product_name"]').val();
		var price = $('input[name="price"]').val();
		var cost_price = $('input[name="cost_price"]').val();
		var mktprice = $('input[name="mktprice"]').val();
		var stock = $('input[name="stock"]').val();
		var from = 0;
		var multispecNum = parseInt($('input[name=multispec_num]').val());
		
		$('.item').each(function(i,e)
		{
			var i = parseInt($(e).attr('index'));
			if (from < i)
			{
				from = i;
			}
		})
		from += 1;
		
		var to = from + multispecNum;
		
		if (cateid > 0)
		{
			$.post('/productcp/product/ajax?op=addmultispec',{'cate_id':cateid,'art':art,'product_name':product_name,'price':price,'cost_price':cost_price,'mktprice':mktprice,'stock':stock,'from':from,'to':to},function(json)
			{
				if (json.errno == 0)
				{
					if (json.specs != '')
					{
						$('#specs').find('#spec-table').append(json.html);
					}
				}
				else if (json.errno > 0)
				{
					showMessage(json.errmsg);
				}
				
				$('#spec_form').modal('hide')
			})
		}
	});
	
	// 审核通过
	$(document).on('click','#audit',function()
	{
		$.post('/productcp/product/audit',{ 'id':pagevar.product_id },function(json)
		{
			if (json.errno == 1)
			{
				showMessage(json.msg);
			}
			else if (json.errno == 0)
			{
				window.location.reload();
			}
		})
	});
})

function callback(responseObject)
{
	json = $.parseJSON(responseObject.response);
			
	if (json.flag == 'error')
	{
		showMessage(json.msg);
	}
	else if (json.flag == 'success')
	{
		$.post('/productcp/image/ajax?op=insert',{ 'product_id':pagevar.product_id,'image':json.url },function(json2)
		{
			if (json2.flag == 'error')
			{
				showMessage(json2.msg);
			}
			else if (json2.flag == 'success')
			{
				$('#thumbnails').append(json2.html);
			}
		})
	}
}