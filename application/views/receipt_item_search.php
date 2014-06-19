<fieldset> 
<?php 
$i=0;
$line =1;
$int_count =  count($catalog)-1;
foreach($catalog as $value){  // แสดงกลุ่มสินค้า
	if($i%3==0){?>
		<div class="control-group">
	<?PHP } ?>
		<span class='col-xs-4 span_menu' >
			<button  onclick='javascript:show_list(<?php echo $line; ?>,"<?php echo $value['catalog_name']; ?>");' type="button" class="btn btn-default btn-lg sub_menu">
				<span class="glyphicon"></span><?php echo $value['catalog_name']; ?>
			</button>								
		</span>
	<?PHP if($i%3==2||$i==$int_count){ ?>
		</div>
		<br style='clear:both;'/>
		<div id="line<?php echo $line;?>" class="list-group form-horizontal well sub_menu" style='display:none;'>			
		</div>
	<?PHP 
		$line++;
		} 
	$i++;
	}
?>
</fieldset>