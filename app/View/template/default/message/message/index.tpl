{include file='public/header_uc_iframe.tpl'}

<script type="text/javascript">
$(function()
{
	$('.delete').click(function()
	{
		var tr = $(this).closest('tr');
		var mid = $(this).closest('tr').attr('mid');
		
		$.get('/message/message/delete/id/' + mid,function(json)
		{
			if (json.flag == 'success')
			{
				tr.remove();
				return;
			}
			
			if (json.flag == 'error')
			{
				showMessage(json.msg);
			}
		})
	})
})
</script>

<div id="member-center-bg"><div id="member-center-main" class="wp clearfix">

		
		<div class="box">
				<div class="box-title">留言列表</div>
				<div class="box-content">
						<table>
								<tr>
										<th>姓名</th>
										<th>手机</th>
										<th>座机</th>
										<th>留言</th>
										<th>回复</th>
										<th>操作</th>
								</tr>
								{foreach $messageList as $message}
								<tr mid="{$message.id}">
										<td>{$message.name}</td>
										<td>{$message.mobile}</td>
										<td>{$message.telephone}</td>
										<td>{$message.content}</td>
										<td>{$message.reply}</td>
										<td>
												<a href="/message/message/detail/id/{$message.id}">详情</a>
												<a class="delete" href="javascript:;">删除</a>
										</td>
								</tr>
								{/foreach}
						</table>
						
						<div>{$pagebar}</div>
				</div>
		</div>

</div></div>


{include file='public/footer_uc_iframe.tpl'}