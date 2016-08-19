'use strict';
/**
 *	数据格式 {"errno":0,"items":[{"year_month":"2016-05","year_month_days":[{"year_month_day":"2016-05-05","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]},{"year_month_day":"2016-05-13","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]},{"year_month_day":"2016-05-15","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]}]},{"year_month":"2016-06","year_month_days":[{"year_month_day":"2016-06-05","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]},{"year_month_day":"2016-06-13","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]},{"year_month_day":"2016-06-15","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]}]},{"year_month":"2016-07","year_month_days":[{"year_month_day":"2016-07-05","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]},{"year_month_day":"2016-07-13","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]},{"year_month_day":"2016-07-15","price":"\uffe5123","info":[{"item_id":"47984","item_name":"\u6210\u4eba,\u6210\u4eba","price":"123.00","stock":1}]}]}]}
 */
(function() {
    /**
     * 日历方法
     * @param  {[type]} dom   [dom对象]
     * @param  {[type]} datas [日历数据]
     * @return {[type]}       [description]
     */
    function calendar(box, datas) {
      this.dom=$('<div class="calendar_box"></div>');   //用于放置日历的容器
      this.w=box.width();   //每个日历的宽度
      this.prevBtn=$('<div class="calendar_prev"></div>');   //上一页按钮
      this.nextBtn=$('<div class="calendar_next"></div>');   //下一页按钮
      this.page=0;    //页码
      this.data=datas;
      //执行处理数据
      this.handle();
      box.append(this.dom).append(this.prevBtn).append(this.nextBtn);
      var cLens=$(".calendar").length,
          _this=this;
      this.dom.width(this.w * cLens);
      if (cLens < 2) {
          this.prevBtn.addClass("none");
          this.nextBtn.addClass("none");
      } else if (cLens >= 2) {
          this.prevBtn.addClass("none");
      }
      this.prevBtn.click(function(){
        if($(this).hasClass("none")){
            return false;
        }
        _this.prev();
      })
      this.nextBtn.click(function(){
        if($(this).hasClass("none")){
            return false;
        }
        _this.next(cLens);
      })
    }
    /**
     * 处理数据，并去执行创建日历动作
     */
    calendar.prototype.handle=function(){
      var d=this.data,
          _this=this;
      for (var i in d) {
          var temp = d[i].year_month.split('-');   //对月份进行分割
          var year = parseInt(temp[0], 10);   //获取年份
          var month = parseInt(temp[1], 10) - 1;    //获取月份
          var monthFirst = new Date(year,month,1,0,0,0);
          var monthLast = new Date(year,month + 1,0,0,0,0);
          var now = new Date();
          var today = now.getFullYear() + '-' + (now.getMonth() < 9 ? '0' + (now.getMonth() + 1) : now.getMonth() + 1) + '-' + (now.getDate() < 10 ? '0' + now.getDate() : now.getDate());
          _this.create(d[i].year_month_days,today,monthFirst.getDay(),monthLast.getDate(),d[i].year_month);
      }
    }
    /**
     * 创建日历
     * @param  {[type]} data            [该月有的天数事件]
     * @param  {[type]} today            [今天]
     * @param  {[type]} beforeBlankCount [该月是从第几天开始的]
     * @param  {[type]} monthDayCount    [一共多少天]
     * @param  {[type]} monthLabel       [月份]
     */
    calendar.prototype.create=function(data, today, beforeBlankCount, monthDayCount, monthLabel){
          var _this=this;
          //日历一个月的容器
          var calendarBox = $('<div class="calendar" data-month=' + monthLabel + '></div>');
          //日历头部月份
          var head = $('<div class="title">' + monthLabel + '</div>');
          //日历星期
          var weekUl = $('<ul class="week_ul"><li>日</li><li>一</li><li>二</li><li>三</li><li>四</li><li>五</li><li>六</li></ul>');
          // 日
          var dayUl = $('<ul class="day_ul"></ul>');
          var dayLi = '';
          for (var i = 1; i <= 42; i++) {
              if (i <= beforeBlankCount) {
                  dayLi += '<li></li>';
              } else if (i >= beforeBlankCount && i <= monthDayCount + beforeBlankCount) {
                  var thisDay = i - beforeBlankCount, //当前值，当前日
                      thisTime = monthLabel + '-' + _this.rDay(thisDay), //每格的日期
                      hasDay = '';
                  $.each(data, function(i, t) {
                      if (t.year_month_day == thisTime) {
                          hasDay = '<li class="has on" data-info='+thisTime+','+t.price+','+t.child_price+','+t.id+','+t.stock+'><p class="text">' + thisDay + '</p><p class="stock">剩余：' + t.stock + '</p><p class="price">￥' + t.price + '</p></li>';
                      }
                  })
                  if (hasDay) {
                      dayLi += hasDay;
                      hasDay = '';
                  } else {
                      dayLi += '<li class="has"><p class="text">' + thisDay + '</p></li>';
                  }
              } else {
                  dayLi += '<li></li>';
              }
          }
          dayUl.html(dayLi);
          calendarBox.append(head).append(weekUl).append(dayUl);
          _this.dom.append(calendarBox);
    }
    /**
     * 处理天，小于10的话就在前面加个0
     * @param  {[type]} day [天]
     * @return {[type]}     [处理后的天]
     */
    calendar.prototype.rDay=function(day){
      return day <= 9 ? '0' + day : '' + day;
    }
    /**
     * 点击上一页的事件
     * @return {[type]} [description]
     */
    calendar.prototype.prev=function(){
          this.page--;
          this.dom.css("left", "-" + this.w * this.page + "px");
          if (this.nextBtn.hasClass('none')) this.nextBtn.removeClass("none");
          if (this.page <= 0) this.prevBtn.addClass("none");
    }
    /**
     * 点击下一页的事件
     * @param  {[type]} cLens [日历长度]
     * @return {[type]} [description]
     */
    calendar.prototype.next=function(cLens){
          this.page++;
          this.dom.css("left", "-" + this.w * this.page + "px");
          if (this.prevBtn.hasClass('none')) this.prevBtn.removeClass("none");
          if ((this.page + 1) >= cLens) this.nextBtn.addClass("none");
    }
    window["calendar"] = calendar;
})();
(function(){
    /**
     *	商品成人儿童增减数量
     * @param  {[type]} dom 对象
     * @param  {[type]} type 类型
     */
    function numFun(dom, type) {
        var _this = this;
        this.addBtn = dom.find(".add"); //增加按钮
        this.minBtn = dom.find(".min"); //减少按钮
        this.text = dom.find("span"); //改变之后的数量dom
        this.zNum = dom.find("input"); //自身数量
        this.oNum = $("#" + type + ""); //另外的数量
        this.stockVal = $("#prodStock").val(); //总库存
        this.addBtn.click(function() {
            var zNum = parseInt(_this.zNum.val()),
                oNum = parseInt(_this.oNum.val());
            if (zNum + oNum < _this.stockVal) {
                _this.add();
            }
        })
        this.minBtn.click(function() {
            var zNum = parseInt(_this.zNum.val());
            if (type == "child") {
                if (zNum > 1) {
                    _this.min();
                }
            } else {
                if (zNum > 0) {
                    _this.min();
                }
            }
        })
    }
    /**
     * 增加操作
     */
    numFun.prototype.add = function() {
            var s = this.zNum.val();
            s++;
            this.zNum.val(s);
            this.text.text(s);
        }
        /**
         * 减少操作
         */
    numFun.prototype.min = function() {
            var s = this.zNum.val();
            s--;
            this.zNum.val(s);
            this.text.text(s);
        }
        /**
         * 初始化控件，遍历对象
         * @param  {[type]} dom 对象
         */
    numFun.init = function(dom) {
        var fun = this;
        dom.each(function() {
            var type = $(this).data("type")
            new fun($(this), type);
        });
    }
    window["numFun"] = numFun;
})();
/**
 * 轮播图
 */
$(".prod_slide .bd li").first().before($(".prod_slide .bd li").last());
$(".prod_slide").slide({
    mainCell: ".bd ul",
    effect:"leftLoop",
    autoPlay:true,
    vis:3,
});
/**
 * 加载日历
 */
 $.ajax({
   url:$("#calendarUrl").val(),
   type: 'post',
   dataType: 'json',
   data:{
     id:$("#id").val()
   }
 }).done(function(json){
   if (json.errno==0) {
     var datas = json.items;
     new calendar($('.prod_calenda'),datas);
   }
 });
//解析行程方式,并替换内容
$(".daywayback").each(function(){
  var reg = new RegExp('\\[(.+?)\\]',"g"),
      text=$(this).html(),
      after=text.replace(reg,'<i class="tripicon $1"></i>');
  $(this).html(after);
})
/**
* 数量增加减少控件
*/
numFun.init($(".num_view"));
/**
 * 点击日历上的事件
 */
$(".prod_calenda").on("click",".on",function(){
  var _this=$(this);
  if(_this.hasClass('active')){
      return false;
  }
  $(".prod_calenda li.active").removeClass('active');
  _this.addClass('active');
  var info=$(this).data("info").split(",");   //获取信息并转成数组
  var prodTime=info[0],
      prodAdultPrice=info[1],
      prodChildPrice=info[2],
      prodId=info[3],
      prodStock=info[4];
  //给input赋值
  $("#prodTime").val(prodTime);
  $("#prodAdultPrice").val(prodAdultPrice);
  $("#prodChildPrice").val(prodChildPrice);
  $("#prodId").val(prodId);
  $("#prodStock").val(prodStock);
  //改变价格
  $(".prod_detail").find("h1").html('￥'+prodAdultPrice);
  //改变右侧信息
  changeOrder($("#orderInfo"),prodTime,prodAdultPrice,prodChildPrice)
  //改变航班信息
  changeplane(prodId);
})
/**
 * 改变右侧信息
 * @param  {[type]} dom  对象dom
 * @param  {[type]} tiem  出行时间
 * @param  {[type]} adult 成人价格
 * @param  {[type]} child 儿童价格
 */
function changeOrder(dom,tiem,adult,child){
    var html=
      '<dd><label>出发日期：</label>'+tiem+'</dd>'+
      '<dd><label>成人价：</label><span class="ycolor">￥'+adult+'</span>/人</dd>'+
      '<dd><label>儿童价：</label><span class="ycolor">￥'+child+'</span>/儿童</dd>'+
      '<dd><label>成人数：</label> <div class="num_view" data-type="child"><a href="javascript:;" class="min">-</a><span>1</span><a href="javascript:;" class="add">+</a><input type="hidden" id="adult" value="1"></div></dd>'+
      '<dd><label>儿童数：</label> <div class="num_view" data-type="adult"><a href="javascript:;" class="min">-</a><span>0</span><a href="javascript:;" class="add">+</a><input type="hidden" id="child" value="0"></div></dd>';
    dom.html(html);
    numFun.init($(".num_view"));
}
/**
 * 改变航班信息
 * @param  {[type]} id 商品ID
 */
function changeplane(id){
   var planeBox=$(".prod_plane_info");
    $.ajax({
      url:$("#planeUrl").val(),
      type: 'post',
      dataType: 'json',
      data:{id:id}
    }).done(function(data){
      if(data.errno==0){
        var html='';
        $.each(data.ticket,function(i,t){
          html+='<div class="list"><ul>';
          if(t.type	==0) html+='<li class="plane_type">去程</li>';
          else if(t.type	==1) html+='<li class="plane_type">返程</li>';
          html+='<li class="plane_name"><p class="f14 mt5">'+t.company+'</p><p>'+t.time+'</p></li>';
          html+='<li class="plane_time"><p class="f14 b mt10">'+t.go_area+'</p><p class="f24 b mt10">'+t.go_time+'</p><p class="f14">'+t.go_airport+'</p></li>';
          html+='<li class="plane_img"></li>';
          html+='<li class="plane_time"><p class="f14 b mt10">'+t.return_area+'</p><p class="f24 b mt10">'+t.return_time+'</p><p class="f14">'+t.return_airport+'</p></li>';
          html+='<li class="plane_total"><p class="time_c mt5">航行时间</p><p>'+t.spend_time+'</p></li>';
          html+='</ul></div>';
        })
        planeBox.html(html);
      }
    })
}
/**
 * 随滚动给dom添加fixed
 * tag_fixed
 */
var order = $(".order"),
    orderTop = order.offset().top,
    prodTabTitle = $(".prod_tab_title"),
    prodTabTitleTop = prodTabTitle.offset().top,
    dayList =$(".day_list"),
    dayListTop =dayList.offset().top;
$(window).scroll(function() {
    var sTop = $(document).scrollTop();
    if (sTop >= orderTop) {
        order.addClass("tag_fixed");
    } else {
        order.removeClass("tag_fixed");
    }
    if (sTop >= prodTabTitleTop) {
        prodTabTitle.addClass("tag_fixed");
    } else {
        prodTabTitle.removeClass("tag_fixed");
    }
    if (sTop >= dayListTop-100 && sTop<=dayListTop+$(".prod_tab_trip").outerHeight()-500) {
        dayList.find('div').addClass("tag_fixed");
    } else {
        dayList.find('div').removeClass("tag_fixed");
    }
    //判断滚动到内容的哪个分类高度
    var num = 0;
    prodTabTitle.find("li").each(function(i) {
        if (sTop >= $('.prod_tab_info>div').eq(i).offset().top - 45) {
            num = i;
        }
    });
    prodTabTitle.find("li").eq(num).addClass('select').siblings().removeClass('select');
    var numb = 0;
    dayList.find("a").each(function(i) {
        if (sTop >= $('.trip_list').eq(i).offset().top - 60) {
            numb = i;
        }
    });
    dayList.find("a").eq(numb).addClass('current').siblings().removeClass('current');
})
/**
 * 点击table，滚动到对应页
 */
prodTabTitle.find("li").click(function() {
    var i = $(this).index();
    $('html,body').animate({
        scrollTop: $('.prod_tab_info>div').eq(i).offset().top - 45
    }, 500);
});
dayList.find("a").click(function() {
    var i = $(this).index();
    $('html,body').animate({
        scrollTop: $('.trip_list').eq(i).offset().top - 60
    }, 500);
    layer.alert(data.errmsg);
});
/**
 * 收藏
 */
$("#collect").click(function() {
    var _this = $(this);
    if (_this.hasClass("has")) {
        $.post("/favorite/favorite/cancle", {
            dataid: $("#id").val()
        }, function(data) {
            _this.removeClass("has");
            //layer.alert(data.errmsg);
        }, 'json')
    } else {
        $.post("/favorite/favorite/add", {
            dataid: $("#id").val()
        }, function(data) {
            _this.addClass("has");
            //layer.alert(data.errmsg);
        }, 'json')
    }
})
/**
 * 签证信息切换
 */
$(".visa_tab_title").on("click","li",function(){
  var _this=$(this);
  var id=_this.data("tab");
  if(!_this.hasClass("active")){
    _this.addClass("active").siblings().removeClass('active');
    $(".view").eq(id).addClass('active').siblings().removeClass('active')
  }
})
/**
 * 展开房间
 * @return {[type]}                         [description]
 */
$(".prod_boat_list li.row").on("click",".name",function(){
  var details=$(this).parents("li").next();
  details.slideToggle("fast");
})
$(".prod_boat_list").on("click",".room",function(){
  var _this=$(this);
 if(_this.hasClass("checked")){
   _this.removeClass("checked").html("选择");
 }else{
   _this.addClass("checked").html("已选择");
 }
 var arr=[];
 $(".room.checked").each(function(i,t){
   arr.push($(t).data("id"));
 })
 $("#roomId").val(arr)
})
/**
 * 预订
 */
$("#reserve").click(function(){
  var itemsId=$("#prodId").val(),
      adult=$("#adult").val(),
      child=$("#child").val();
  var url='/order/order/confirm?';
  var items='items[0][id]='+itemsId+'&items[0][num]='+adult+'&items[0][is_adult]=1&items[1][id]='+itemsId+'&items[1][num]='+child+'&items[1][is_adult]=0';
  if($(".prod_boat").length>0){
    var roomId=$("#roomId").val();
    if(!roomId){
      layer.msg("请选择房间");
      return
    }
    roomId=roomId.split(",");
    $.each(roomId,function(i,t){
      items+='&room_ids['+i+'][id]='+t;
    })
    url='/order/linerorder/confirm?';
  }

   window.location.href=url+items;

})
var jiathis_config={
  siteNum:3,
	sm:"weixin,qzone,tsina",
	summary:"",
	boldNum:3,
	shortUrl:false,
	hideMore:true
}
