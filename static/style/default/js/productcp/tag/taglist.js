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
//删除
$('#all_delete').click(function(){
	var cBoxs=$("input.checkbox:checked");
	var arr=new Array();
	var ar = new Array();
	if(cBoxs.length>0){
		$.each(cBoxs,function(i,d){
			var id=$(d).closest('tr').attr('dataid');
			var tag = $(d).closest('tr').data('tagid');
			arr[i]=id;
			ar[i]=tag;
		})
		if (confirm('确定删除勾选项？')){
			$.post('/productcp/tag/ajax?op=delete',{id:arr,tag:ar},function(data){
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

//清空下架
$('#delete_down').click(function(){
	if (confirm('确认清空？'))
	{
		$.post('/productcp/tag/ajax?op=deletedown',function(json)
		{
			if (json.errno == 0)
			{
				window.location.reload();
			}
			if (json.errno == 1)
			{
				showMessage(json.msg);
			}
		})
	}
})

$('.delete').click(function()
	{
		var tr = $(this).closest('tr');
		var id = $(this).closest('tr').attr('dataid');
		var tag = $(this).closest('tr').data('tagid');
		if (confirm('确定删除？'))
		{
			$.post('/productcp/tag/ajax?op=delete',{id:id,tag:tag},function(json)
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
	}
)
/**
 * 显示排序填写框，并赋值原有序号
 */
$(".set_sort").click(function(){
		var _this=$(this),
				sortNum=_this.parents('tr').data('sortid'),
				tagId=_this.parents('tr').data('tagid'),
				prodId=_this.parents('tr').attr('dataid');
		$("#prodSortBox").modal('show').on('shown.bs.modal',function(){
			var modal = $(this);
			modal.find('.modal-body input').eq(0).val(prodId);
			modal.find('.modal-body input').eq(1).val(tagId);
			modal.find('.modal-body input').eq(2).val(sortNum);
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
	tagId=_this.parents('tr').data('tagid'),
			prodId=_this.parents('tr').attr('dataid');
	var data={"id":prodId,"tag":tagId,"order":1};
	setSort(data);
})
/**
 * 异步数据刷新界面
 * @param {[type]} data ID&序号
 */
function setSort(data){
		$.post('/productcp/tag/ajax?op=order',data,function(json){
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
	tagIds=[],
	sortNums=[],
	n=min;
trs.each(function(){
	var _this=$(this),
	tagId=_this.data('tagid'),
			prodId=_this.attr('dataid');
	prodIds.push(prodId);
	tagIds.push(tagId);
	sortNums.push(n);
	n++;
});

	$.post('/productcp/tag/ajax?op=orderlist',{'id':prodIds,'order':sortNums,'tag':tagIds},function(json){
			if(json.errno==0){
				window.location.reload();
			}
	},'json')
}
 