"use strict";

//ajax动画
$.ajaxSetup({
    dataType: 'json',
    type: 'post',
	beforeSend : function() {
		loading.show();
	},
	complete : function() {
		loading.hide();
	},
    error:function(){
        alert("网络错误");
    }
});
//loading
var loading={
    dom: '<div class="loadingbox"><div class="loadecenter"><div class="loadrgb"><div class="loader"></div><p>正在加载，请稍等...</p></div></div></div>',
	show:function(){
		$('body').append(loading.dom);
	},
	hide:function(){
		$('.loadingbox').remove();
	}
}
//返回上一页
$(".go_left").click(function(){
	window.history.back();
})
//更多功能
$("#moreFun").click(function(){
	$('.more_fun').slideToggle('fast');
})
//替换alert
var time=0;
function alert(msg,callBack){
    var box=$(".alert_box");
    var str='<div class="alert_box"><span class="alert_msg">'+msg+'</span></div>';
    if(box) box.remove();
    $("body").append(str);
    time=setTimeout(function(){
        if(time!=0) clearInterval(time);
        $(".alert_box").animate({opacity : 0},500,function(){
            $(".alert_box").remove();
            if(callBack) callBack();
        })
    },1000)
}

//获取地址栏值
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
//radio样式选择
$(document).on('change',"input[type='radio']",function(e){
    var $checkbox= $(e.currentTarget);
    var $container = $checkbox.prev();
    var update=function($checkbox){
        var $container = $checkbox.prev();
        if($checkbox.prop("checked")) $container.addClass("check")
        else $container.removeClass("check")
    }
    if($container.hasClass("input-radio")) {
      update($checkbox);
    }
    if($checkbox.attr('type').toLowerCase() === 'radio') {
      var name = $checkbox.attr("name");
      $("input[name='"+name+"']").each(function() {
        update($(this));
      });
    }
})
//check样式选择
$(document).on('change',"input[type='checkbox']",function(){
    var parent=$(this).parent();
    if(parent.hasClass('check')){
        parent.removeClass('check')
    }else{
        parent.addClass('check');
    }
})
//写入cookie
$.cookie = function(key, value, options) {
    if (arguments.length > 1 && (value === null || typeof value !== "object")) {
        options = jQuery.extend({}, options);

        if (value === null) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        return (document.cookie = [
                encodeURIComponent(key),
                '=',
                options.raw ? String(value) : encodeURIComponent(String(value)),
                options.expires ? '; expires=' + options.expires.toUTCString()
                        : '', options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : '' ].join(''));
    }

    options = value || {};
    var result, decode = options.raw ? function(s) {
        return s;
    } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key)
            + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

//省市区三级联动
var provinceList=function(dom){
    var self=this;
    this.dom=dom;
    this.json=cityJson;
    this.pro=$(dom.find('select')[0]);
    this.city=$(dom.find('select')[1]);
    this.county=$(dom.find('select')[2]);
    this.set=this.getSetting();
    if(this.set!=null){
        this.loadPro(this.set.province);
        this.LoadCity(this.set.city);
        this.LoadCounty(this.set.county);
    }else{
       //加载省
       this.loadPro(); 
    }
    //选择当前，加载下一项
    this.pro.change(function(){
        self.LoadCity();
        self.LoadCounty();
    });
    this.city.change(function(){
        self.LoadCounty();
    });
};
provinceList.prototype={
    //加载省
    loadPro:function(proId){
        this.pro.append(this.readData(this.json));
        if(proId){this.pro.val(proId);}
    },
    //根据省加载市
    LoadCity:function(cityId){
        var proId=this.pro.get(0).selectedIndex,citys=this.json[proId-1];
        if(proId===0){
            this.city.html('<option value="0">--请选择--</option>');
            return false;
        }
        this.city.html(this.readData(citys.cities));
        if(cityId){this.city.val(cityId);}
    },
    //根据市加载区
    LoadCounty:function(countyId){
        var proId=this.pro.get(0).selectedIndex,cityId=this.city.get(0).selectedIndex,citys=this.json[proId-1];
        this.county.html(this.readData(citys.cities[cityId].counties));
        if(countyId){this.county.val(countyId);}
    },
    //渲染数据
    readData:function(data){
        var html='';
        $.each(data,function(index,el){
            html+='<option value="'+el.id+'">'+el.region_name+'</option>';
        });
        return html;
    },
    //获取默认参数
    getSetting:function(){
        var setting=JSON.stringify(this.dom.data("config"));
        if(setting&&setting!=="")return $.parseJSON(setting);
        else return null;
    }
};
provinceList.init=function(dom){
    new this(dom);
};