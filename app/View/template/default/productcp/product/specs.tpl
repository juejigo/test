{if !empty($specs)}

		<a id="addspec" class="btn green" href="javascript:;">增加规格</a> <a id="multispec" data-toggle="modal" data-target="#spec_form" class="btn green" href="javascript:;">批量增加规格</a>
		
		<table id="spec-table">
				<tr>
						<th width="10%">货号</th>
						<th width="20%">产品名</th>
						{foreach $specs as $spec}
						<th>{$spec.spec_name}</th>
						{/foreach}
						<th width="10%">销售价</th>
						<th width="10%">成本价</th>
						<th width="10%">市场价</th>
						<th width="10%">库存</th>
						<th width="10%">删除</th>
				</tr>
				
				{if !empty($data.items) && count($data.items) > 1}
				{foreach $data.items as $i => $item}
				<tr class="item" index="{$i}">
						<td colspan="1"><input class="form-control" type="text" name="items[{$i}][art]" value="{$item.art}" /></td>
						<td colspan="1"><input class="form-control" type="text" name="items[{$i}][item_name]" value="{$item.item_name}" /></td>
						
						{foreach $specs as $j => $spec}
						<td colspan="1">
								<select class="form-control" name="items[{$i}][specval_{$j}]">
										<option value="">{$spec.spec_name}</optio>
										{foreach $spec.values as $val}
										<option {if $val == $item["specval_{$j}"]}selected="selected"{/if} value="{$val}">{$val}</option>
										{/foreach}
								</select>
						</td>
						{/foreach}
						
						<td colspan="1"><input class="form-control" type="text" name="items[{$i}][price]" value="{$item.price}" /></td>
						<td colspan="1"><input class="form-control" type="text" name="items[{$i}][cost_price]" value="{$item.cost_price}" /></td>
						<td colspan="1"><input class="form-control" type="text" name="items[{$i}][mktprice]" value="{$item.mktprice}" /></td>
						<td colspan="1"><input class="form-control" type="text" name="items[{$i}][stock]" value="{$item.stock}" /></td>
						<td colspan="1"><a class="delspec glyphicon glyphicon-remove" href="javascript:;"><a></td>
				</tr>
				{/foreach}
				{/if}
				
		</table>

{/if}