{if !empty($params)}

<div id="goods-params" class="goods-division">
	{foreach $params.group as $index => $groupName}
	<div class="param-groupname"><b>{$groupName}</b></div>
	{foreach $params.name.$index as $name}
		<div class="form-row"><label class="form-label-3">{$name}</label><input class="input-text" type="text" name="goods[params][{$groupName}][{$name}]" value="{$data.params.$groupName.$name}" /></div>
	{/foreach}{/foreach}
</div>

{/if}