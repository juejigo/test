$(function()
{
	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true,
    });
    
	$('.delete').click(function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定删除？'))
		{
			$.get('/productcp/product/delete?id=' + id,function(json)
			{
				if (json.flag == 'success')
				{
					tr.remove();
					return;
				}
				
				if (json.flag == 'error')
				{
					showMessage(json.msg);
				}
			})
		}
	})
	
	// 设置权重
	$(document).on('click','.weight',function()
	{
		$(this).hide();
		$(this).next('input[name="weight"]').show();
	})
	$(document).on('blur','input[name="weight"]',function()
	{
		var input = $(this);
		var id = $(this).closest('tr').attr('dataid');
		var weight = $(this).val();
		
		$.post('/productcp/product/weight',{ 'id':id,'weight':weight },function(json)
		{
			if (json.errno == 1)
			{
				showMessage(json.msg);
			}
			else if (json.errno == 0)
			{
				input.hide();
				input.prev('.weight').html(weight).show();
			}
		})
	})
	
	// 下架
	$(document).on('click','.down',function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定下架此商品'))
		{
			$.post('/productcp/product/down',{ 'id':id },function(json)
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
		}
	});
	
	// 上架
	$(document).on('click','.up',function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定上架此商品'))
		{
			$.post('/productcp/product/up',{ 'id':id },function(json)
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
		}
	});
	// 下架成待上架
	$(document).on('click','.wdown',function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定下架此商品'))
		{
			$.post('/productcp/product/wdown',{ 'id':id },function(json)
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
		}
	});
})
//勾选
$("#allCheck").click(function(){
	var _this=$(this),
			cb=$(".checkbox");
	if(_this.attr("checked") == "checked"){
		cb.attr("checked","checked");
		cb.parent().addClass("checked");
		cb.parent().parent().addClass("checked");
	}else{
		cb.removeAttr("checked","checked");
		cb.parent().removeClass("checked");
		cb.parent().parent().removeClass("checked");
	}
})

//下架
$('.all_down').click(function(){
	var cBoxs=$("input.checkbox:checked");
	var arr=new Array();
	if(cBoxs.length>0){
		$.each(cBoxs,function(i,d){
			var id=$(d).closest('tr').attr('dataid');
			arr[i]=id;
		})
		if (confirm('确定下架勾选项？')){
			$.post('/productcp/product/down',{id:arr},function(data){
				if (data.errno == 0){
					window.location.reload();
				}else{
					alert(data.msg);
				}
			})
		}
	}else{
		alert("至少勾选一项");
	}
})
// 上架
$('.all_up').click(function(){
	var cBoxs=$("input.checkbox:checked");
	var arr=new Array();
	if(cBoxs.length>0){
		$.each(cBoxs,function(i,d){
			var id=$(d).closest('tr').attr('dataid');
			arr[i]=id;
		})
		if (confirm('确定上架勾选项？')){
			$.post('/productcp/product/up',{id:arr},function(data){
				if (data.errno == 0){
					window.location.reload();
				}else{
					alert(data.msg);
				}
			})
		}
	}else{
		alert("至少勾选一项");
	}
})
/**
 * 显示排序填写框，并赋值原有序号
 */
$(".set_sort").click(function(){
		var _this=$(this),
				sortNum=_this.parents('tr').data('sortid'),
				prodId=_this.parents('tr').attr('dataid');
		$("#prodSortBox").modal('show').on('shown.bs.modal',function(){
			var modal = $(this);
			modal.find('.modal-body input').eq(0).val(prodId);
			modal.find('.modal-body input').eq(1).val(sortNum);
		})
})
/**
 * 点击排序框确定按钮，提交数据
 */
var prodSortBox=$("#prodSortBox");
prodSortBox.find('button').eq(1).click(function(){
	 var data=prodSortBox.find('form').serialize();
	 setSort(data);
})
/**
 * 点击置顶
 */
$(".to_top").click(function(){
	var _this=$(this),
			prodId=_this.parents('tr').attr('dataid');
	var data={"id":prodId,"order":1};
	setSort(data);
})
/**
 * 异步数据刷新界面
 * @param {[type]} data ID&序号
 */
function setSort(data){
		$.post('/productcp/product/ajax?op=order',data,function(json){
				if(json.errno == 0){
					window.location.reload();
				}
		},'json')
}
/**
 * 获取当前页面TR 拖拽排序
 */
var prodList=$("#prodList"),
		min=prodList.find('tr').eq(0).data('sortid');
prodList.disableSelection();
prodList.sortable({
		delay: 200,
		distance: 30,
		opacity: 0.8, //设置拖动时候的透明度
    revert: true, //缓冲效果
    cursor: 'move', //拖动的时候鼠标样式
		update:getAllTr
});
function getAllTr(){
	var trs=prodList.find("tr"),
	prodIds=[],
	sortNums=[],
	n=min;
trs.each(function(){
	var _this=$(this),
			prodId=_this.attr('dataid');
	prodIds.push(prodId);
	sortNums.push(n);
	n++;
});

	$.post('/productcp/product/ajax?op=orderlist',{'id':prodIds,'order':sortNums},function(json){
			if(json.errno==0){
				window.location.reload();
			}
	},'json')
}

