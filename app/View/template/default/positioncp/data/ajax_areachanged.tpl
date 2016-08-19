<select class="form-control" name="params[cate_id]">
		<option value="0">分类</option>
		{foreach $list as $cate}
		<option style="text-indent:{($cateList.{$cate.id}.level - 1) * 10}px;" {if $cate.id == $data.cate_id}selected="selected"{/if} value="{$cate.id}">{$cate.value}</option>
		{/foreach}
</select>