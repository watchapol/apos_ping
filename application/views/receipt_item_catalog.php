<div class="control-group "> 
<?php 
$i=0;

$int_count =  count($item_catalog)-1;
foreach($item_catalog as $value){
	if($i%3==0){?>
		<div class='row'>
	<?PHP } ?>
		<span class='col-xs-4 span_menu'>
			<button   onclick='select_item(<?php echo $value['itemid'];?>);' type="button" class="btn btn-primary btn-lg sub_menu">
				<span class="glyphicon"></span><?php echo $value['name'];?>
			</button>								
		</span>
	<?PHP if($i%3==2||$i==$int_count){ ?>
		</div>				
	<?PHP 		
		} 
	$i++;
	}
?>
</div>