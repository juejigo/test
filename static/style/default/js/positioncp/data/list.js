$(function()
{
	$('.delete').click(function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定删除？'))
		{
			$.get('/positioncp/data/delete?id=' + id,function(json)
			{
				if (json.errno == 0)
				{
					tr.remove();
					return;
				}
				
				if (json.errno == 1)
				{
					showMessage(json.msg);
				}
			})
		}
	})
	
	// 下架
	$(document).on('click','.down',function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		
		if (confirm('确定下架此商品'))
		{
			$.post('/positioncp/data/ajax?op=down',{ 'id':id },function(json)
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
			$.post('/positioncp/data/ajax?op=up',{ 'id':id },function(json)
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
		$.post('/positioncp/data/ajax?op=order',data,function(json){
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

	$.post('/positioncp/data/ajax?op=orderlist',{'id':prodIds,'order':sortNums},function(json){
			if(json.errno==0){
				window.location.reload();
			}
	},'json')
}

