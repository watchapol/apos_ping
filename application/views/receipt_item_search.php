<fieldset> 
<?php 
$i=0;  // ใช้ สำหรับ วนloop  check ว่า หาร 3 ได้เศษเท่าไหร่ ใช้สำหรับ ขึ้นบรรทัดใหม่
$line =1; //ใช้สำหรับ แยกว่าเป็นแถวบรรทัดที่เท่าไหร่  ใช้สำหรับ กดปุ่มโชว์ สินค้าในกลุ่ม
$int_count =  count($catalog)-1;  // หาจำนวน สุดท้ายของข้อมูล    : ใช้ในบรรทัด 15
foreach($catalog as $value){  // แสดงกลุ่มสินค้า
	if($i%3==0){ // หาร3  หากได้ เศษ 0 ---> เริ่มบรรทัดใหม่?>
		<div class="control-group">
	<?PHP } ?>
		<span class='col-xs-4 span_menu' >
			<button  onclick='javascript:show_list(<?php echo $line; ?>,"<?php echo $value['catalog_name']; ?>");' type="button" class="btn btn-default btn-lg sub_menu">
				<span class="glyphicon"></span><?php echo $value['catalog_name']; ?>
			</button>								
		</span>
	<?PHP if($i%3==2||$i==$int_count){// หาร3  หากได้ เศษ 2 หรือเป็นข้อมูลสุดท้าย ให้ ----> เริ่มบรรทัดใหม่ ?>
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