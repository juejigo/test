<div class="col-md-2">
		<div dataid="{$image.id}" data="{$image.image}" class="{if $image.main == 1}defaultimage{/if} thumbnail">
				<img src="{thumbpath source=$image.image width=220}">
				<div class="caption">
				<p><a href="javascript:;" class="image-default btn btn-default" role="button">默认</a> <a href="javascript:;" class="image-delete btn btn-primary" role="button">删除</a> <input style="display:inline-block;width:40px" type="text" name="order" class="form-control" value="{$image.order}"></p>
				</div>
		</div>
</div>