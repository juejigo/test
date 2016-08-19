'use strict';
/**
* 首页轮播图
*/
var slide = $(".slideBox"), //轮播图的dom对象
  prev = $(".prev"), //上一页
  next = $(".next"); //下一页
slide.slide({
  mainCell: ".bd ul",
  autoPlay: true,
  effect: "fold"
});
$(window).resize(function() {
  var w = $(window).width();
  slide.find("ul,li").width(w);
})
$(".banner").hover(function() {
      prev.animate({
          'left': 100,
          'opacity': 1
      }, 300);
      next.animate({
          'right': 100,
          'opacity': 1
      }, 300);
  },function() {
      prev.animate({
          'left': -50,
          'opacity': 0
      }, 300);
      next.animate({
          'right': -50,
          'opacity': 0
      }, 300);
  });
  /**
   * 绑定时间
   */
$(".go_time input").datetimepicker({
      language: 'zh',
      autoclose: true,
      minView: "month",
      format: "yyyy-mm-dd",
      pickerPosition: "bottom-right"
  })
  /**
   * 首页商品
   */
$(".choice_right li:nth-child(1),.choice_right li:nth-child(2)").css("margin-bottom", "20px");
$(".choice_right li").mouseenter(function() {
  var _this = $(this);
  _this.find('.choice_info').animate({
      'height': 78,
  }, 300);
  _this.find('.t').animate({
      'height': 54,
  }, 300);
}).mouseleave(function() {
  var _this = $(this);
  _this.find('.choice_info').animate({
      'height': 50,
  }, 300);
  _this.find('.t').animate({
      'height': 26,
  }, 300);
})
$(".prod_list li:last-child").css("margin-right", "0px");
$(".prod_end_time span").each(function(i, dom) {
  var _this = $(dom),
      time = _this.data("time");
  window.setInterval(function() {
      countdown(_this, time);
      time--;
  }, 1000)
})


// var banner = new Swiper('.swiper-container', {
//  autoplay : 5000,//可选选项，自动滑动
//  loop:true,
//  //pagination : '.swiper-page',   //小标点容器
//  //paginationClickable :true,   //小标点
// })
// $('.swiper-prev').click(function(){
//   banner.swipePrev();
// })
// $('.swiper-next').click(function(){
//   banner.swipeNext();
// })
