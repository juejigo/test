"use srtict";
var code={};
var btn=$("button");

//验证消费码
code.validate=function(){
	var code=$("#code").val();
	if(!code){
		alert('请输入验证码');
		return false;
	}
	btn.attr("disabled","disabled");
    $.ajax({
        url: "/orderuc/sn/verify",
        data: {
        	'sn':code
        }
    }).done(function(data){
        if (data.errno == 0) {
            window.location.href = '/orderuc/sn/use?sn=' + code
        } else {
        	btn.removeAttr("disabled","disabled");
            alert(data.Msg);
        }
    });
}
//使用消费码
code.use=function(){
	btn.attr("disabled","disabled");
	
	
	
	$.ajax({
        url: "../testApi/success.php",
        data: {
        	amout:$("#uAmount").val()
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg,function () {
            		btn.removeAttr("disabled","disabled");
                	window.location.href = "enterCode.html"; 
            });
        } else {
        	btn.removeAttr("disabled","disabled");
            alert(data.Msg);
        }
    });
}
var aAmount=parseInt($("#aAmount").text()),	//总得消费券数量
	uAmount=$("#uAmount"),
	add=$(".add"),					//增加
	min=$(".min");					//减少
//减少
min.click(function(){
	var num=$("#uAmount").val();
	if(num==1){
		alert('数量必须大于1');
		return false;
	}
	num--;
	compute(num);
});
//增加
add.click(function(){
	var num=$("#uAmount").val();
	if(num>=aAmount){
		alert('不能超过消费券总数');
		return false;
	}
	num++;
	compute(num);
});
//通过input更改数量
uAmount.change(function(){
	var num=$("#uAmount").val();
	if(num>=aAmount){
		alert("不能超过消费券总数");
		uAmount.val(1);
		return false;
	}
	compute(num);
})
function compute(num){
	uAmount.val(num);
}