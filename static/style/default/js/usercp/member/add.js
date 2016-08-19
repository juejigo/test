$(function()
{
	// 时间
	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true,
    });
    
    // 角色选择
    $(document).on('change','select[name="role"]',function()
    {
    	var role = $(this).val();
    	
    	var memberGroup = new Array();
    	memberGroup[0] = new Array("1","美义士");
    	memberGroup[1] = new Array("2","美英雄");
    	memberGroup[2] = new Array("3","美堂主");
    	memberGroup[3] = new Array("4","美舵主");
    	memberGroup[4] = new Array("5","美盟主");
    	
    	var agentGroup = new Array();
    	agentGroup[0] = new Array("1","市代理");
    	agentGroup[1] = new Array("2","省代理");
    	
    	var adminGroup = new Array();
    	adminGroup[0] = new Array("1","编辑");
    	adminGroup[1] = new Array("2","客服");
    	adminGroup[2] = new Array("11","编辑组长");
    	adminGroup[3] = new Array("22","客服组长");
    	
    	if (role == 'member')
    	{
    		document.getElementById('group').length = 1;
    		var len = memberGroup.length;
	        for (i = 0; i < len; i++) 
	        {
				document.getElementById('group').options[document.getElementById('group').length] = new Option(memberGroup[i][1],memberGroup[i][0]);
	        }
    	}
    	else if (role == 'agent')
    	{
    		document.getElementById('group').length = 1;
	        var len = agentGroup.length;
	        for (i = 0; i < len; i++) 
	        {
				document.getElementById('group').options[document.getElementById('group').length] = new Option(agentGroup[i][1],agentGroup[i][0]);
	        }
    	}
		else if (role == 'admin')
		{
			document.getElementById('group').length = 1;
	        var len = adminGroup.length;
	        for (i = 0; i < len; i++) 
	        {
				document.getElementById('group').options[document.getElementById('group').length] = new Option(adminGroup[i][1],adminGroup[i][0]);
	        }
		}
		else
		{
			document.getElementById('group').length = 1;
		}
    });
})