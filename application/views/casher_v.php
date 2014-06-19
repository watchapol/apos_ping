<script type='text/javascript'>
$( document ).ready(function(){
	if('<?php echo $check_mobile;?>'==0){ // หากมีค่าเป็น 0 แปลว่าเป็น PC
				$('#container_2').css('-webkit-transform','rotate(0deg)');
				$('#container_2').css('-moz-transform','rotate(0deg)');
				$('#container_2').css('-o-transform','rotate(0deg)');
				$('#container_2').css('-ms-transform','rotate(0deg)');
				$('#container_2').css('transform','rotate(0deg)');
			}
});
	function range_click(cast){
		$('#cast').val(cast);
	}
	function isNumberKeyCast(evt) // funciton สำหรับตรวจสอบว่า เป็นตัวเลข หรือ . ทดศนิยมรึเปล่า
    {	
		var c = (evt.which) ? evt.which : event.keyCode;		 		 
        if(c >= 48 && c <= 57){
			return true;
		}
		else if(c==46){
			return true;
		}
			
        return false;
	}
	function casher_form(){
		var int_cash = parseFloat($('#cast').val());
		if(int_cash>=parseFloat(<?php echo $receipt['totalprice'];?>)){ // หากจำนวนที่จ่ายน้อยกว่า รายจ่ายสุทธิ ไม่ทำงาน
			$.ajax({
				url:'<?php echo base_url().$this->controller?>/pubAjaxCast',
				type:'POST',
				data:{
					recid:'<?php echo $receipt['recid']; ?>',
					receive:int_cash,
					totalprice:'<?php echo $receipt['totalprice'];?>'
				},
				success:function(j){	
						//console.log(j);
						var result = jQuery.parseJSON(j);
						if(result.status==1){
							if(result.commute!=0){
								$( "#confirm_submit" ).scrollTop(0);			
								$('#confirm_total').html(ReadableNumber(parseFloat(result.commute).toFixed(2))); //js/myfunction.js																
								$('#confirm_submit').show();
								$('#nav_main').hide();
								$('#cbp-spmenu-s1').hide();
								$('#cbp-spmenu-s2').hide();
								$('#cbp-spmenu-s3').hide();
							}
						}
						else{
							alert('ไม่สามารถ upload ข้อมูลได้' );								
						}					
				}
			});
		}else{
			alert('เงินไม่พอ');
		}
		return false;
	}
</script>
<div id='confirm_submit' class="cbp-spmenu-push" style='display:none;position: absolute;width:100%;z-index:999;background-color: #EEEEEE;padding:15px;'>
	<div class="control-group" id='container_2' align='center'>		
		<span style='font-size: 55px;'>เงินทอน</span><br/>
		<span id='confirm_total' style='font-size: 100px;'>100</span><br/>		
		<br/>
		<br/>
	</div>	
	<br/>	
	<br style='clear:both;'/>  <br style='clear:both;'/>
	<hr></hr>
	<span class='col-xs-12' >
		<button onclick="javascript:alert('end');"  style='padding: 20px 16px;' class="btn btn-default btn-lg col-xs-12">ตกลง</button>
	</span>	
	<br/> 			
</div>
<div class="main col-sm-9 col-md-9 col-lg-10">	
	<div class='set_center'>
		<div class="control-group">	
			<input maxlength="15" value='<?php echo $receipt['recid']; ?>' onkeydown='return false;' class="form-control input-lg" placeholder="เลขที่ใบเสร็จ" id="fileInput" />
		</div>	
		<br/>
		<form type='post' onsubmit='return casher_form();'>
			<div class="control-group">
				<div class="input-group input-group-lg">  
					<input id='totalprice' value='<?php echo number_format($receipt['totalprice'],2); ?>' onkeydown='return false;' placeholder="เป็นเงิน" type="text" class="form-control">
					<span class="input-group-addon">บาท</span>
				</div>		
			</div>		 
			<br/>
			<div class="control-group">
				<div class="input-group input-group-lg">  
					<input  id='cast' onkeypress="return isNumberKeyCast(event);" placeholder="รับเงิน" step="0.01" type="number" class="form-control">
					<span class="input-group-addon">บาท</span>
				</div>		
			</div>	
			<div class="control-group">         							 	
			</div>
			<br style='clear:both;'/>
			<div>		
				<span class='col-xs-6 span_menu' >
					<button onclick='range_click(<?php echo $a_rang[0];?>);' type="button" class="btn btn-default btn-lg sub_menu">
						<span class="glyphicon"></span><?php echo number_format($a_rang[0],2); //แสดงปุ่มกดจ่ายเงิน?>
					</button>
				</span>				
				<span class='col-xs-6 span_menu' >
					<button onclick='range_click(<?php echo $a_rang[1];?>);' type="button" class="btn btn-default btn-lg sub_menu">
						<span class="glyphicon"></span><?php echo number_format($a_rang[1],2);//แสดงปุ่มกดจ่ายเงิน?>
					</button>
				</span>		
			</div>
			<div >
				<span class='col-xs-6 span_menu' >
					<button onclick='range_click(<?php echo $a_rang[2];?>);' type="button" class="btn btn-default btn-lg sub_menu">
						<span class="glyphicon"></span><?php echo number_format($a_rang[2],2);//แสดงปุ่มกดจ่ายเงิน?>
					</button>
				</span>								
				<span class='col-xs-6 span_menu' >
					<button onclick='range_click(<?php echo $a_rang[3];?>);' type="button" class="btn btn-default btn-lg sub_menu">
						<span class="glyphicon"></span><?php echo number_format($a_rang[3],2);//แสดงปุ่มกดจ่ายเงิน?>
					</button>
				</span>			
			</div>	 	
			<br style='clear:both;'/>  <br style='clear:both;'/>
			<div align='center'>
				<button style='width:80%;' type="submit" class="btn btn-primary">Save</button>	
			</div>
		</form>
	</div>
</div>
		