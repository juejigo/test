{for $i = $from; $i < $to; $i++}
<tr class="item" index="{$i}">
		<td colspan="1"><input class="form-control" type="text" name="items[{$i}][art]" value="{$art}" /></td>
		<td colspan="1"><input class="form-control" type="text" name="items[{$i}][item_name]" value="{$product_name}" /></td>
		
		{foreach $specs as $j => $spec}
		<td colspan="1">
				<select class="form-control" name="items[{$i}][specval_{$j}]">
						<option value="">{$spec.spec_name}</optio>
						{foreach $spec.values as $val}
						<option value="{$val}">{$val}</option>
						{/foreach}
				</select>
		</td>
		{/foreach}
		
		<td colspan="1"><input class="form-control" type="text" name="items[{$i}][price]" value="{$price}" /></td>
		<td colspan="1"><input class="form-control" type="text" name="items[{$i}][cost_price]" value="{$cost_price}" /></td>
		<td colspan="1"><input class="form-control" type="text" name="items[{$i}][mktprice]" value="{$mktprice}" /></td>
		<td colspan="1"><input class="form-control" type="text" name="items[{$i}][stock]" value="{$stock}" /></td>
		<td colspan="1"><a class="delspec glyphicon glyphicon-remove" href="javascript:;"><a></td>
</tr>
{/for}