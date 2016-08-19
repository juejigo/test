{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<!-- 主体 -->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">注册统计 <small>每月新增客户的数量</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">统计</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">会员统计</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">注册统计</a></li>
				</ul>
				<!--日期选择-->
				<div class="page-toolbar">
					<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-salt" data-placement="top" data-original-title="请选择日期">
						<i class="icon-calendar"></i>&nbsp; <span class="thin uppercase visible-lg-inline-block">选择日期</span>&nbsp; <i class="fa fa-angle-down"></i>
					</div>
				</div>
			</div>
			<!--图表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box grey-cascade">
						<!--表格标题-->
						<div class="portlet-title">
							<div class="caption">注册统计</div>
							<div class="tools">
								<a href="javascript:;" class="collapse" data-original-title="" title="" aria-describedby="tooltip977656"></a>
							</div>
						</div>
						<div class="portlet-body">
							<div id="orderRegister" style="width:100%;height:400px;"></div>
						</div>
					</div>
				</div>
			</div>
			<!--图表 /-->			
		</div>
	</div>
<!-- 主体 /-->

{include file='public/admincp/footer.tpl'}

<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/highcharts/highcharts.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/highcharts/modules/exporting.js" type="text/javascript"></script>

{literal}
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features   
   var changeTime=$('#dashboard-report-range').daterangepicker({
   	showDropdowns: true,
   },function (start, end) {
        $('#dashboard-report-range span').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
    }); 
   changeTime.on('apply.daterangepicker', function(ev, picker) { 
		charts(picker.startDate.format('YYYY-MM-DD'),picker.endDate.format('YYYY-MM-DD')); 
	});
   charts();
});
//加载图表
var options={
    chart:{
        renderTo:'orderRegister',                
        type:'column'
    },
    credits:{enabled:false},
    title:{text:null},    
    legend:{enabled:false},
    tooltip:{valueSuffix:'个'},
    xAxis:{
    	categories:null
    },
    yAxis:{
        title:{text:null},
    },
    series:null
}
/*数据格式
 * name:注册量
 * data:月份数据
 * {"datas":[{"name":"注册量","data":[['年月',数量],['年月',数量],['年月',数量],['年月',数量]]}],"success":true}
 * */
function charts(startDate,endDate){
	var start=startDate?startDate:null,
		end=endDate?endDate:null;
    $.ajax({
        url:'/statisticcp/user/ajax?op=register',
        type:'post',
        data:{
        	dateline_from:start,
        	dateline_to:end
        }, 
        dataType:'json'
    }).done(function(data){
        if(data.success){
            options.series=data.datas;
            var cate=[];
            $.each(data.datas[0].data,function(i,t){
                cate[i]=t[0];
            }) 
            options.xAxis.categories=cate;
            chart=new Highcharts.Chart(options);
        }else{       	
        	$("#orderRegister").html("<div class='alert alert-warning'>没有数据</div>");
        }
    })
}

</script>
{/literal}

