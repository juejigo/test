"use strict";
var SETTIME=1;
/**
 * 弹出提示框
 * @param  {[type]} msg      提示信息
 * @param  {[type]} callBack 回调函数
 * @return {[type]}          [description]
 */
function nAlert(msg,callBack){
    var box=$(".alert_box");
    var str='<div class="alert_box"><span class="alert_msg">'+msg+'</span></div>';
    if(box) box.remove();
    $("body").append(str);
    setTimeout(function(){
        $(".alert_box").animate({opacity : 0},500,function(){
            $(".alert_box").remove();
            if(callBack) callBack();
        })
    },1000)
}
$(".go_back,.prod_go_back").click(function(){
  window.history.back();
})
;(function(){
	/**
	 * 计算时间并赋值
	 * @param  {[type]} dom 对象
	 */
	function countDown(dom,callback) {
			var _this = this,
	    		time = dom.data('time');
			if(time<=0){
				dom.html('已经结束');
			}else if(time>0){
				dom.html(_this.toStr(time));
				time--;
        		_this.timing(time,dom,callback);
			}
	};
  countDown.prototype.timing=function(time,d,callback){
    var _this = this;
    var t=window.setInterval(function() {
        if(time==0){
          clearInterval(t);
          d.html('已经结束');
          if(callback){
          	callback();
          }
          return false;
        }
        d.html(_this.toStr(time));
        time--;
    }, 1000)
  }
	/**
	 * 把时间戳转换成天小时分秒
	 * @param  {[type]} time 时间戳
	 * @return {[type]}      [description]
	 */
	countDown.prototype.toStr=function(time){
		var day = 0,
				hour = 0,
				minute = 0,
				second = 0;
		if (time > 0) {
				day = Math.floor(time / (60 * 60 * 24));
				hour = Math.floor(time / (60 * 60)) - (day * 24);
				minute = Math.floor(time / 60) - (day * 24 * 60) - (hour * 60);
				second = Math.floor(time) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
		}
		if (minute <= 9) minute = '0' + minute;
		if (second <= 9) second = '0' + second;
		//var str = day + '天' + hour + '小时' + minute + '分' + second + '秒';+
		var str = hour + ' : ' + minute + ' : ' + second;
		return str;
	};
	/**
	 * 初始化倒计时，遍历new
	 * @param  {[type]} dom dom对象
	 */
	countDown.init=function(dom,callback){
			var _this=this;
			dom.each(function(){
				new _this($(this),callback);
			})
	};
	window["countDown"]=countDown;
})();
/**
 * 获取地址栏参数信息
 * @param  {[type]} name 参数名
 * @return {[type]}      参数值
 */
function getUrl(name){
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  decodeURI(r[2]); return null;
}
