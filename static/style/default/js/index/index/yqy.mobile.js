'use strick';
;(function(){
	/**
	 * 计算时间并赋值
	 * @param  {[type]} dom 对象
	 */
	function countDown(dom) {
			var _this = this,
	    		time = dom.data('time');
			if(time<=0){
				dom.html('已经结束');
			}else if(time>0){
				dom.html(_this.toStr(time));
				time--;
        _this.timing(time,dom)
			}
	};
  countDown.prototype.timing=function(time,d){
    var _this = this;
    var t=window.setInterval(function() {
        if(time==0){
          clearInterval(t);
          d.html('已经结束');
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
		var str = day + '天' + hour + '小时' + minute + '分' + second + '秒';
		return str;
	};
	/**
	 * 初始化倒计时，遍历new
	 * @param  {[type]} dom dom对象
	 */
	countDown.init=function(dom){
			var _this=this;
			dom.each(function(){
				new _this($(this));
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
/**
 * 通用的绑定
 */
if($(".count_time").length>0){
	//倒计时时间
	countDown.init($(".count_time"));
}
