'use strick';
/**
 * 侧边
 */
var wHeight=$(window).height();
var panelRight=$("#panelRight");
panelRight.height(wHeight);
 $("#openRightb").click(function(){
   $('body').css("position","fixed").addClass("mui-off-canvas-wrap mui-active")
   panelRight.show();
 })
$(document).on('touchstart','#zz',function(e){
  e.preventDefault();
  $('body').css("position","relative").removeClass("mui-off-canvas-wrap mui-active")
  panelRight.hide();
})
/**
 * 搜索
 */
if($("#search_submit").length>0){
  sjsearchInit();
}
$("#search_submit").click(function(){
  var keyWord=$("#keyWord").val();
  if(!keyWord){
    mui.toast('请输入关键字');
    return false;
  }else{
		var da={"key":keyWord};
		save(da,"prod");//把搜索关键字缓存起来
		$('form').submit();
	}
})
/**
 * 将值保存到本地数据
 * @param  {[type]} singleData       关键字
 * @param  {[type]} localStorageName 关键字name
 * @return {[type]}                  [description]
 */
function save(singleData,localStorageName){
	var data=[];
	var temp;
	var oldsData;
	var olddata;
	//保存的数据类型必须为对象
	if(typeof singleData == 'object'){
		//如果已经存在该条数据，直接退出
		if(!isExist(singleData.key,localStorageName)){
			//删除多余数据
			_removeExceedPost(localStorageName);
			if(localStorage.getItem(localStorageName)){
				oldsData = localStorage.getItem(localStorageName);//取得缓存
				olddata = JSON.parse(oldsData);
				olddata.unshift(singleData);
				temp = JSON.stringify(olddata);
				//调用本地存储类，保存数据
				localStorage.setItem(localStorageName,temp);
			}else{
				data.unshift(singleData);
				temp=JSON.stringify(data);
				localStorage.setItem(localStorageName,temp);
			}
		}
	}
	return true;
}
//已经存在指定的key
function isExist(para,localStorageName){
	var exist=false;
	if(localStorage.getItem(localStorageName))
	{
		var sData = localStorage.getItem(localStorageName);
		var data = JSON.parse(sData);
		if(data.length>0)
		{
			for(var i=0;i<data.length;i++)
			{
				if(para==data[i].key)
				{
					exist=true;
					break;
				}
			}
		}
	}
	return exist;
}
//删除超过10的数据
function _removeExceedPost(localStorageName){
	if(localStorage.getItem(localStorageName))
    {
		var sData = localStorage.getItem(localStorageName);
		var data = JSON.parse(sData);
		if(data.length < 10) return false;
		data.splice(9,data.length - 10+1);
		var temp = JSON.stringify(data);
		//调用本地存储类，保存数据
		localStorage.setItem(localStorageName,temp);
	}
}
//初始化关键字
function sjsearchInit()
{
	if(localStorage.getItem("prod"))
    {
		var html="";
		var sData = localStorage.getItem("prod");
		var data = JSON.parse(sData);
		for(var i=0;i<data.length;i++)
		{
			if(data[i].key.length != 0) {
				html+='<a href="/product/product/list?keyWord='+data[i].key+'">'+data[i].key+'</a>';
			}
		}
		$("#prodkey").html(html);
	}

}
//清空数据
function _removeall(localStorageName){
	if(localStorage.getItem(localStorageName))
    {
		var sData = localStorage.getItem(localStorageName);
		var data = JSON.parse(sData);
		data.splice(0,20);
		var temp = JSON.stringify(data);
		//调用本地存储类，保存数据
		localStorage.setItem(localStorageName,temp);
		mui.toast('清楚成功');
    $("#prodkey").html('');
	}
}
