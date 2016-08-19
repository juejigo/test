var EcommerceIndex = function () {

    function showTooltip(x, y, labelX, labelY) {
        $('<div id="tooltip" class="chart-tooltip">' + (labelY.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')) + 'USD<\/div>').css({
            position: 'absolute',
            display: 'none',
            top: y - 40,
            left: x - 60,
            border: '0px solid #ccc',
            padding: '2px 6px',
            'background-color': '#fff'
        }).appendTo("body").fadeIn(200);
    }

    var initChart1 = function () {

        var data = [
            ['01/2013', 4],
            ['02/2013', 8],
            ['03/2013', 10],
            ['04/2013', 12],
            ['05/2013', 2125],
            ['06/2013', 324],
            ['07/2013', 1223],
            ['08/2013', 1365],
            ['09/2013', 250],
            ['10/2013', 999],
            ['11/2013', 390]
        ];

            var plot_statistics = $.plot(
                $("#statistics_1"), 
                [
                    {
                        data:data,
                        lines: {
                            fill: 0.6,
                            lineWidth: 0
                        },
                        color: ['#f89f9f']
                    },
                    {
                        data: data,
                        points: {
                            show: true,
                            fill: true,
                            radius: 5,
                            fillColor: "#f89f9f",
                            lineWidth: 3
                        },
                        color: '#fff',
                        shadowSize: 0
                    }
                ], 
                {

                    xaxis: {
                        tickLength: 0,
                        tickDecimals: 0,                        
                        mode: "categories",
                        min: 2,
                        font: {
                            lineHeight: 15,
                            style: "normal",
                            variant: "small-caps",
                            color: "#6F7B8A"
                        }
                    },
                    yaxis: {
                        ticks: 3,
                        tickDecimals: 0,
                        tickColor: "#f0f0f0",
                        font: {
                            lineHeight: 15,
                            style: "normal",
                            variant: "small-caps",
                            color: "#6F7B8A"
                        }
                    },
                    grid: {
                        backgroundColor: {
                            colors: ["#fff", "#fff"]
                        },
                        borderWidth: 1,
                        borderColor: "#f0f0f0",
                        margin: 0,
                        minBorderMargin: 0,
                        labelMargin: 20,
                        hoverable: true,
                        clickable: true,
                        mouseActiveRadius: 6
                    },
                    legend: {
                        show: false
                    }
                }
            );

            var previousPoint = null;

            $("#statistics_1").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                            y = item.datapoint[1].toFixed(2);

                        showTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1]);
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });

    }

    var initChart2 = function() {

        var data = [
            ['01/2013', 10],
            ['02/2013', 0],
            ['03/2013', 10],
            ['04/2013', 12],
            ['05/2013', 212],
            ['06/2013', 324],
            ['07/2013', 122],
            ['08/2013', 136],
            ['09/2013', 250],
            ['10/2013', 99],
            ['11/2013', 190]
        ];

            var plot_statistics = $.plot(
                $("#statistics_2"), 
                [
                    {
                        data:data,
                        lines: {
                            fill: 0.6,
                            lineWidth: 0
                        },
                        color: ['#BAD9F5']
                    },
                    {
                        data: data,
                        points: {
                            show: true,
                            fill: true,
                            radius: 5,
                            fillColor: "#BAD9F5",
                            lineWidth: 3
                        },
                        color: '#fff',
                        shadowSize: 0
                    }
                ], 
                {

                    xaxis: {
                        tickLength: 0,
                        tickDecimals: 0,                        
                        mode: "categories",
                        min: 2,
                        font: {
                            lineHeight: 14,
                            style: "normal",
                            variant: "small-caps",
                            color: "#6F7B8A"
                        }
                    },
                    yaxis: {
                        ticks: 3,
                        tickDecimals: 0,
                        tickColor: "#f0f0f0",
                        font: {
                            lineHeight: 14,
                            style: "normal",
                            variant: "small-caps",
                            color: "#6F7B8A"
                        }
                    },
                    grid: {
                        backgroundColor: {
                            colors: ["#fff", "#fff"]
                        },
                        borderWidth: 1,
                        borderColor: "#f0f0f0",
                        margin: 0,
                        minBorderMargin: 0,
                        labelMargin: 20,
                        hoverable: true,
                        clickable: true,
                        mouseActiveRadius: 6
                    },
                    legend: {
                        show: false
                    }
                }
            );

            var previousPoint = null;

            $("#statistics_2").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                            y = item.datapoint[1].toFixed(2);

                       showTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1]);
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });

    }

    return {

        //main function
        init: function () {
            initChart1();

            $('#statistics_amounts_tab').on('shown.bs.tab', function (e) {
                initChart2();
            });
        }

    };

}();

$(function()
{
    EcommerceIndex.init();
})



/**********************
 * 	会员图表
 **********************/
userCharts('yesterday');
//图表数据
var userChartOptions={
   chart:{
       renderTo:'userChart',
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
   series:null,
   exporting:{
     enabled: false
   }
}
 /*数据格式
  * name:注册量
  * data:月份数据
  * {"datas":[{"name":"注册量","data":[['年月',数量],['年月',数量],['年月',数量],['年月',数量]]}],"errno":0}
  * */
function userCharts(datetime){
 $.post('/admincp/index/ajax?op=member',{data:datetime},function(data){
	if(!(typeof(data.memberCount) == "undefined") && (typeof(data.memberCountMonth) == "undefined"))
	{
		document.getElementById("dayMemberRegDiv").style.display="";
		document.getElementById('monthMemberRegDiv').style.display = "none";
		document.getElementById("dayMemberReg"). innerHTML = data.memberCount;
	}
	if(!(typeof(data.memberCountMonth) == "undefined") && (typeof(data.memberCount) == "undefined"))
	{
	   document.getElementById("monthMemberRegDiv").style.display="";
	   document.getElementById('dayMemberRegDiv').style.display = "none";
	   document.getElementById("monthMemberReg"). innerHTML = data.memberCountMonth;
	}
   if(data.errno==0){
       userChartOptions.series=data.datas;
       var cate=[];
       $.each(data.datas[0].data,function(i,t){
           cate[i]=t[0];
       })
       userChartOptions.xAxis.categories=cate;
       chart=new Highcharts.Chart(userChartOptions);
   }
 },'json');
}
/**********************
 * 	订单图表
 **********************/
orderCharts('yesterday');
//图表数据
var orderChartOptions={
   chart:{
       renderTo:'orderChart',
       type:'column'
   },
   colors:["#E26A6A"],
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
   series:null,
   exporting:{
     enabled: false
   }
}
 /*数据格式
  * name:注册量
  * data:月份数据
  * {"datas":[{"name":"注册量","data":[['年月',数量],['年月',数量],['年月',数量],['年月',数量]]}],"errno":0}
  * */
function orderCharts(datetime){
 $.post('/admincp/index/ajax?op=order',{data:datetime},function(data){
	if(!(typeof(data.orderCount) == "undefined") && (typeof(data.orderCountMonth) == "undefined"))
	{
		document.getElementById("dayOrderDiv").style.display="";
		document.getElementById('monthOrderDiv').style.display = "none";
		document.getElementById("dayOrder"). innerHTML = data.orderCount;
		
		document.getElementById("dayTradeDiv").style.display="";
		document.getElementById('monthTradeDiv').style.display = "none";
		document.getElementById("dayTrade"). innerHTML = data.summeryDay;
	}
	if(!(typeof(data.orderCountMonth) == "undefined") && (typeof(data.orderCount) == "undefined"))
	{
	   document.getElementById("monthOrderDiv").style.display="";
	   document.getElementById('dayOrderDiv').style.display = "none";
	   document.getElementById("monthOrder"). innerHTML = data.orderCountMonth;
	   
	   document.getElementById("monthTradeDiv").style.display="";
	   document.getElementById('dayTradeDiv').style.display = "none";
	   document.getElementById("monthTrade"). innerHTML = data.summeryMonth;
	}
   if(data.errno==0){
       orderChartOptions.series=data.datas;
       var cate=[];
       $.each(data.datas[0].data,function(i,t){
           cate[i]=t[0];
       })
       orderChartOptions.xAxis.categories=cate;
       chart=new Highcharts.Chart(orderChartOptions);
   }
 },'json');
}


 /**
  * 获取日期
  * @param {[type]} AddDayCount [description]
  */
function getday(AddDayCount) {
  var dd = new Date();
  dd.setDate(dd.getDate()+AddDayCount);//获取AddDayCount天后的日期
  var y = dd.getFullYear();
  var m = dd.getMonth() + 1 < 10 ? '0' + (dd.getMonth() + 1) : dd.getMonth() + 1
  var d = dd.getDate() < 10 ? '0' + (dd.getDate()) : dd.getDate();
  return y+"-"+m+"-"+d;
}




