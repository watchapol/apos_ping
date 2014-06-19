<tr>        
   <td>
		<span >
			<button onclick='javascript:item_del(this);' type="button" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-trash"></span> 
			</button>
		</span>
		<input class='item_id' name='item_id[]' type='hidden' value='<?php echo $id;?>'>	 <!-- รหัสสินค้า -->	
		<span class='item_name'><?php echo $name; ?></span><!-- ขื่อสินค้า -->	
		<span style='float:right'>							
		<?php 
		if(count($count)==0){?>		<!-- count-->	
			<input class='item_count' name='count[]' type='hidden' value='1'>		
		<?php
		}else{?>
			<select onchange='javascript:item_change(this);' class='item_count'  name='count[]'  >
				<option value='1'>1</option>
				<?php foreach($count as $value){
				echo '<option value="'.$value['count'].'">'.$value['name_count'].'</option>';
				}?>				
			</select>		
		<?php } ?>
		</span>
	</td>
    <td  align='center'>
		<input name='qty[]' style='width:30px;' onchange='javascript:item_change(this);' class='item_qty'  value='1' type='number' onkeypress='return isNumberKey(event);'   >
	</td>
	<td   align='center'>
		<input  name='price[]' class='item_price' type='hidden' value='<?php echo $price;?>'>
		<span class='item_total' ><?php echo number_format($price, 2, '.', ''); ?></span> <!-- ราคา -->	
	</td>
</tr>