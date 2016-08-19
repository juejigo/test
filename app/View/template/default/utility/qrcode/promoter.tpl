<!doctype html>
<html>
<head>
</head>
<body>
{if !empty($qrcodeUrl)}
		<img src="{$qrcodeUrl}" />
{else}
		<form action="/utility/qrcode/promoter" method="post">
				<input name="account" value="" />
				<input type="submit" value="生成二维码" />
		</form>
{/if}
</body>
</html>