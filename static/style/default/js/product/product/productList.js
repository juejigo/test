'use strict';
/**
 * 商品条件选择
 */
var path = window.location.origin, //网站origin
    startTime = $(".start_time"), //商品列表开始时间
    endTime = $(".end_time"), //结束时间
    firstPrice = $("#first_price"), //第一个价格
    secondPrice = $("#second_price"); //第二个价格
var condition = $('.condition');
condition.on("click", ".more", function() {
    var _this = $(this);
    _this.parents("tr").find(".list_a").css("height", "auto");
    _this.text("收起").removeClass("more").addClass("up");
})
condition.on("click", " .up", function() {
        var _this = $(this);
        _this.parents("tr").find(".list_a").css("height", "45px");
        _this.text("更多").removeClass("up").addClass("more");
    })
    //先执行样式变化
$(".list_a").on("click", "a", function() {
    var _this = $(this);
    _this.addClass("active").siblings("a.active").removeClass("active");
})
$(".sort").on("click", ".sort_a", function() {
    var _this = $(this);
    _this.addClass("active").siblings("a.active").removeClass("active");
})
var priceForm = $(".price_form");
priceForm.mouseenter(function(e) {
    priceForm.addClass('focus');
}).mouseleave(function(e) {
    priceForm.removeClass('focus');
})

$(".select_price").click(function() {
        window.location.href = path + '/youquyou_pc/views/productList.html?startTime=' + startTime.val() + '&endTime=' + endTime.val() + '&firstPrice=' + firstPrice.val() + '&secondPrice=' + secondPrice.val();
    })
    //商品相关
$(".prod_end_time span").each(function(i, dom) {
    var _this = $(dom),
        time = _this.data("time");
    window.setInterval(function() {
        countdown(_this, time);
        time--;
    }, 1000)
})
$(".prod_list a:nth-child(2n)").css("margin-right", "0px");
$(".prod_top_photo").slide({
    mainCell: ".bd ul",
    effect: "fold",
    delayTime: "300",
    endFun: function() {
        $("img.slide_lazy").lazyload();
    },
    startFun: function(i) {
        prodTopTable(i);
    }
}).find(".hd li:last-child").css("margin-right", "0px");

function prodTopTable(id) {
    var table = $(".prod_top_table");
    table.fadeOut(200).eq(id).fadeIn(200);
}
//绑定时间
$(".go_time input").datetimepicker({
    language: 'zh',
    autoclose: true,
    minView: "month",
    format: "yyyy-mm-dd",
    pickerPosition: "bottom-right"
})

startTime.datetimepicker({
    language: 'zh',
    autoclose: true,
    minView: "month",
    format: "yyyy-mm-dd",
    pickerPosition: "bottom-right"
}).on("click", function(ev) {
    startTime.datetimepicker("setEndDate", endTime.val());
}).on('changeDate', function(ev) {
    if (endTime.val()) {

    }
});
endTime.datetimepicker({
    language: 'zh',
    autoclose: true,
    minView: "month",
    format: "yyyy-mm-dd",
    pickerPosition: "bottom-right"
}).on("click", function(ev) {
    endTime.datetimepicker("setStartDate", startTime.val());
}).on('changeDate', function(ev) {
    if (startTime.val()) {

    }
});
