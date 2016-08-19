"use strict";
sjsearchInit();
var key='search';

$("#submit").click(function(){
	var value=$.trim($("input[name='search']").val());
	if(!value){
		alert('请输入关键字!');
	}else{
		var da={"key":value};
		save(da,"prod");//把搜索关键字缓存起来
		window.location.href = "prodList.html?search="+ value;
	}
})

//将值保存到本地数据
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
			}
			else{
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
//删除超过19的数据 
function _removeExceedPost(localStorageName){
	if(localStorage.getItem(localStorageName))
    {
		var sData = localStorage.getItem(localStorageName);
		var data = JSON.parse(sData);
		if(data.length < 20) return false;
		data.splice(19,data.length - 20+1);
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
				html+='<a href="prodList.html?search='+data[i].key+'">'+data[i].key+'</a>';
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
		alert('清楚成功',function(){
			window.location.reload();
		});
	}
}
