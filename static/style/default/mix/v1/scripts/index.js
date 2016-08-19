"use srtict";

$(function(){
	$(".guess_like img").lazyload({effect: "fadeIn",placeholder : "../img/zw.gif",});
})

var recomIndexs = document.getElementById('recomIndex').getElementsByTagName('li');
recomIndexs[0].className="on"; 	
var recom =new Swipe(document.getElementById('recom'),{
	callback: function(pos) {  
        var i = recomIndexs.length;  
        while(i--){  
            recomIndexs[i].className = ' ';  
        }  
        recomIndexs[pos].className = 'on';  
    }  
}); 
//获取当前坐标，写入cookie
// var geolocation = new BMap.Geolocation();
// geolocation.getCurrentPosition(function(r) {
//     if (this.getStatus() == BMAP_STATUS_SUCCESS) {
//         $.cookie("la", r.point.lng, 1000*60*60);//1小时
//         $.cookie("lo", r.point.lat, 1000*60*60);//1小时
//     } else {
//         log('failed' + this.getStatus());
//     }
// }, {
//     enableHighAccuracy : true
// });
