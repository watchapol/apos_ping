<div class="control-group "> 
<?php 
$i=0;  // ใช้ สำหรับ วนloop  check ว่า หาร 3 ได้เศษเท่าไหร่ ใช้สำหรับ ขึ้นบรรทัดใหม่

$int_count =  count($item_catalog)-1;  // หาจำนวน สุดท้ายของข้อมูล    : ใช้ในบรรทัด 15
foreach($item_catalog as $value){
	if($i%3==0){// หาร3  หากได้ เศษ 0 ---> เริ่มบรรทัดใหม่?>
		<div class='row'>
	<?PHP } ?>
		<span class='col-xs-4 span_menu'>
			<button   onclick='select_item(<?php echo $value['itemid'];?>);' type="button" class="btn btn-primary btn-lg sub_menu">
				<span class="glyphicon"></span><?php echo $value['name'];?>
			</button>								
		</span>
	<?PHP if($i%3==2||$i==$int_count){ // หาร3  หากได้ เศษ 2 หรือเป็นข้อมูลสุดท้าย ให้ ----> เริ่มบรรทัดใหม่?>
		</div>				
	<?PHP 		
		} 
	$i++;
	}
?>
</div>