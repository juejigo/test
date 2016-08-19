{if !empty($attrs)}

{foreach $attrs as $i => $attr}
{assign var="a" value="a_{$i}"}
<div class="form-group">
	<label class="col-md-2 control-label">{$attr.name}</label>
	<div class="col-md-10">
			<select class="form-control" name="attrs[{$a}]">
					<option value="">请选择</option>
			{foreach $attr.options as $j => $option}
					<option value="{$j}" {if $data.$a != '' && $data.$a == $j}selected="true"{/if}>{$option}</option>
			{/foreach}
			</select>
	</div>
</div>
{/foreach}

{/if}