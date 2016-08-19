{include file='public/header_uc_iframe.tpl'}

<div id="member-center-bg"><div id="member-center-main" class="wp clearfix">

		<div class="box">
				<div class="box-title">留言详情</div>
				<div class="box-content">
						{if $error->hasError()}
						<div id="immediate">
								<h3>请处理以下错误：</h3>
								{foreach $error->getAll() as $e}
								<p>{array_shift($e)}</p>
								{/foreach}
						</div>
						{/if}
						
						<form data-ajax="false" method="post" action="/message/message/reply" enctype="multipart/form-data">
						<table>
								<tr>
										<td class="td-label"><label for="title">姓名</label></td>
										<td>{$data.name}</td>
								</tr>
								<tr>
										<td class="td-label"><label for="mobile">手机</label></td>
										<td>{$data.mobile}</td>
								</tr>
								<tr>
										<td class="td-label"><label for="telephone">座机</label></td>
										<td>{$data.telephone}</td>
								</tr>
								<tr>
										<td class="td-label"><label for="content">内容</label></td>
										<td>{$data.content}</td>
								</tr>
								<tr>
										<td class="td-label"><label for="reply">回复</label></td>
										<td><textarea name="reply">{$data.reply}</textarea></td>
								</tr>
								<tr>
										<td class="td-label"></td>
										<td>
												<input class="button green" type="submit" value="回复" />
										</td>
								</tr>
						</table>
						</form>
				</div>
		</div>

</div></div>


{include file='public/footer_uc_iframe.tpl'}