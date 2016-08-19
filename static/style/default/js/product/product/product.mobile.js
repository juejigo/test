'use strick';
/********************************************
 * 		商品详情
 ********************************************/
if($(".content_block").length>0){
  var wHeight=$(window).height()-74;
  $(".content_block").height(wHeight);
}
//解析行程方式,并替换内容
$(".daywayback").each(function(){
  var reg = new RegExp('\\[(.+?)\\]',"g"),
      text=$(this).html(),
      after=text.replace(reg,'<i class="tripicon $1"></i>');
  $(this).html(after);
})

//显示预定二维码
$(".prod_reserve_btn").click(function(){
    var dom=$('.ewm_box');
    if(dom.css('display')=='none'){
      dom.show();
    }else{
      dom.hide();
    }
})
/**
* 打开view
*/
function open_view(id){
  $('body').css("position","fixed").addClass("mui-off-canvas-wrap mui-active")
  $('#'+id).show();
  //$(window).scrollTop(0);
}
/**
* 关闭view
*/
function close_view(e){
  $('body').css("position","relative").removeClass("mui-off-canvas-wrap mui-active");
  $(e).parents(".page").hide();
}
/********************************************
 * 		商品列表
 ********************************************/

var loading = false;
//无限滚动
if($("#prodList").length>0){
  var tourism_type=getUrl("tourism_type"),   //列表分类
      keyWord=getUrl("keyWord"),   //列表分类
      pageDom=$("#page"),   //页码
      classType=$("#classType"),    //筛选类型
      over=$(".prod_list_over"),   //最底
      load=$(".prod_list_load");    //加载
  var ajaxSearch={
      tourism_type:tourism_type,
      page:1,
      keyWord:keyWord,
      classType:classType.val(),
      classId:1
  }
  getProdList(ajaxSearch);
  $(window).scroll(function(){
    if($(".prod_list_load").length>0){
      if(!loading){
        var scrollTop = $(this).scrollTop(),
            listHeight=$(document).height(),
            windowHeight = $(this).height();
         //console.log(scrollTop,windowHeight,listHeight)
        if(scrollTop+windowHeight==listHeight){
          loading=true;
          getProdList(ajaxSearch);
        }
      }
    }
  })
  //筛选类型
  var classSpan=$(".prod_class span");
  classSpan.click(function(){
    var _this = $(this),
        type=$(this).data('class');
    if(type=='price'){
      if(_this.hasClass('p_u')){
        _this.removeClass('p_u').addClass("p_d active").siblings("span.active").removeClass("active");
        ajaxSearch.classId=0;
      }else if(_this.hasClass('p_d')){
        _this.removeClass('p_d').addClass("p_u active").siblings("span.active").removeClass("active");
        ajaxSearch.classId=1;
      }else{
        _this.addClass("p_u active").siblings("span.active").removeClass("active");
      }
    }else{
      _this.siblings("span[data-class='price']").removeClass('p_u p_d');
      if(_this.hasClass('active')) return false;
      _this.addClass("active").siblings("span.active").removeClass("active");
      ajaxSearch.classId=1;
    }
    $(window).scrollTop(0);
    $(".mui-content").html('<div class="prod_list" id="prodList"><ul></ul></div><div class="prod_list_load"><span class="mui-spinner"></span></div><div class="prod_list_over">已经到最底了</div>');
    classType.val(type);
    ajaxSearch.classType=classType.val();
    ajaxSearch.page=1;
    loading=true;
    getProdList(ajaxSearch);
  })
  /**
   * 加载商品列表
   * @param  {[type]} tourism_type 商品列表类型
   * @param  {[type]} page   页码
   * @return {[type]}        [description]
   * ajax?op=productlist
   */
  function getProdList(ajaxSearch){
      var box=$('#prodList ul');    //列表容器
      var html=$("<div></div>");
      $.ajax({
        type:'post',
        url:'ajax?op=productlist',
        dataType: "json",
        data:ajaxSearch,
        success:function(data){
          if(data.errno==0){
            ajaxSearch.page=data.page+1;
            var dataList=data.product_list,
                str='';
            $.each(dataList,function(i,d){
              str += '<li class="bd_b bd_t"><a href="/product/product/detail?id='+d.id+'"><img class="lazy" src="'+d.image+'"><div class="pord_list_info"><div class="pord_list_title">'+d.product_name+'</div><div class="pord_list_price bd_b"><div class="price">￥<strong>'+d.price+'</strong></div></div><div class="pord_list_time">距结束：<span class="count_time" data-time="'+d.clock+'">获取中...</span></div></div></a></li>';
            })
            html.html(str);
            //倒计时
            countDown.init(html.find(".count_time"));
            box.append(html);
            loading = false;
            if(dataList.length<10){
                // 删除加载提示符
                $(".prod_list_load").remove();
                $(".prod_list_over").show();
            }
          }
        }
      })
  }
}
