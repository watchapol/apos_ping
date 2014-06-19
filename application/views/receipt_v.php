    <script type='text/javascript'>
	var now_line =0;  //  สำหรับตรวจสอบว่า กลุ่มสินค้า แสดงสินค้าในกลุ่ม
	var now_catalog_id =0; // สำหรับตรวจสอบว่า กลุ่มสินค้า บรรทัดไหนเปิดอยู่
	var a_customer =jQuery.parseJSON('<?php echo $customer_default;?>'); 	 // ข้อมูลลูกค้าเงินสด
		$( document ).ready(function(){
			$('#str_customer_name').val(a_customer.name+' '+a_customer.surname);
			$('#str_customer_tel').val(a_customer.tel1);
			$('#str_customer_add').val(a_customer.address1+' '+a_customer.address2+' '+a_customer.province+' '+a_customer.post);			
			$('#itemid').focus();
			if('<?php echo $check_mobile;?>'==0){ // หากมีค่าเป็น 0 แปลว่าเป็น PC
				$('#container_2').css('-webkit-transform','rotate(0deg)');
				$('#container_2').css('-moz-transform','rotate(0deg)');
				$('#container_2').css('-o-transform','rotate(0deg)');
				$('#container_2').css('-ms-transform','rotate(0deg)');
				$('#container_2').css('transform','rotate(0deg)');
			}
		});
	function confirm_submit(){ // แสดง div : receipt_total ยืนยันก่อนซื้อ
		var receipt_total = $('#receipt_total').html();			
		if(receipt_total!=0){
			$( "#confirm_submit" ).scrollTop(0);			
			$('#confirm_total').html(receipt_total); 
			$('#total_qty').html($('#myTable >tbody >tr ').length); 
			$('#confirm_submit').show();
			$('#nav_main').hide();
			$('#cbp-spmenu-s1').hide();
			$('#cbp-spmenu-s2').hide();
			$('#cbp-spmenu-s3').hide();
		}
	}
	function confirm_edit(){ //   ในหน้า div : receipt_total กดปุ่มแก้ไขสินค้า
		$('#confirm_submit').hide();
		$('#nav_main').show();
		$('#cbp-spmenu-s1').show();
		$('#cbp-spmenu-s2').show();
		$('#cbp-spmenu-s3').show();
	}	
	function item_change(item){		// funciton สำหรับการเปลี่ยนจำนวน สินค้า ทำงานเหมือนมีการเปลี่ยนจำนวนสินค้า และ จำนวน count
		var total =0;
		$(item).parents( "tr" ).each(function() {			
		var item_total =$(this).find("td .item_total");
		var item_count =$(this).find("td .item_count");
		var item_qty =$(this).find("td .item_qty");
		var item_price =$(this).find("td .item_price");		
		if(item_qty.val()<1){//หากจำนวนสินค้า น้อยกว่า -1 ให้ไป funciton ลบสินค้า
			if(item_del(item)){
				return false;
			}
			else{
				item_qty.val('1');//หากปฏิเสธการลบสินค้า ให้ปรับจำนวนสินค้าเป็น 1 
			}
		}								
		$.ajax({
			url:'<?php echo base_url().$this->controller.'/pubAjaxitemStock';?>',
			type:'POST',
			data:{
				itemid:$(this).find("td .item_id").val(),
				qty:parseInt(item_count.val())*parseInt(item_qty.val()) // คำนวณ qty ทั้งหมด จาก item_count x item_qty
			},
			success:function(j){		
				if(j<0){						 							
					alert('สินค้าจำนวนไม่พอ');	
					item_count.val('1');
					item_qty.val('1');							
				}
				total =(parseInt(item_count.val())*parseInt(item_qty.val())*parseFloat(item_price.val()));
					item_total.html(ReadableNumber(total.toFixed(2)));
					update_total();
				}				
			});							
		});		
	}
	function update_total(){	 // funciton สำหรับปรับยอดชำระรวมของสินค้า ทำงานเมื่อมีการแก้ไข จำนวนสินค้า	
		var total =0;
		$('#myTable >tbody >tr ').each(function() {				
		var str_total = $(this).find("td .item_total").html();
			total =total+(parseFloat(str_total.replace(",", "")));					
		});
		$('#receipt_total').html(ReadableNumber(total.toFixed(2)));			
	}
	function item_del(item){ // funciton สำหรับการลบ สินค้า ทำงานโดยกดปุ่ม ถังขยะ หรือปรับจำนวนสินค้าเป็น 0
		if(confirm("ต้องการลบสินค้าออก")){			
			$(item).parents( "tr" ).remove();
			update_total();
			return true;
		}else{
			return false;
		}
	}
	function item_add(itemid){		//  function สำหรับเพิ่มจำนวน สินค้า
		if(itemid!=''){			
			$.ajax({
				url:'<?php echo base_url().$this->controller.'/pubAjaxitemAdd';?>',
				type:'POST',
				data:{
					itemid:itemid,
					cutid:a_customer.cutid
				},
				success:function(j){					
					 var result = jQuery.parseJSON(j);		
					switch(result.status) {
						case 1:
							$('#myTable > tbody:last').append(result.html);
							update_total();
							break;
						case 2:		
							$('#catalog_seach').html(result.html);
							$('#catalog_seach').slideDown();
							break;
						case 3:
							alert('สินค้าจำนวนไม่พอ');
							break;
						default:							
					}						 						
					$('#itemid').val('');
				}				
			});
		} 
		else{
			item_search('');
		}
		return false;
	}
	function item_search(itemid){		 //  function สำหรับเค้นหากลุ่ม สินค้า 
		var itemid=$('#itemid').val();
		
			$.ajax({
				url:'<?php echo base_url().$this->controller.'/pubAjaxItem';?>',
				type:'POST',
				data:{
					itemid:itemid,
					cutid:a_customer.cutid
				},
				success:function(j){
					var result = jQuery.parseJSON(j);	
					$('#catalog_seach').html(result.html);
					$('#catalog_seach').slideDown();
				}				
			});
		
		return false;
	}		
	function show_list(line,catalog_id){	  //  function เกี่ยวกับการแสดงสินค้าในกลุ่มสินค้า					
		$.ajax({
			url:'<?php echo base_url().$this->controller.'/pubAjaxItem_catalog';?>',
			type:'POST',
			data:{
				catalog_id:catalog_id					
			},
			success:function(j){
				$('#line'+line).html(j);					
				if(now_line !=line){ 	//หากกดแล้วเป็น ไม่ใช่line เดิม ให้ปิด แล้วเปิดใหม่
					$('.list-group').slideUp('fast');				
					now_line =line;	
				}												
				if(now_catalog_id==catalog_id){	//เปิดได้ตัวมันเอง ให้ปิด					
					$('#line'+line).slideToggle('fast');			
				}else{					
					$('#line'+line).slideDown('fast');
				}
					now_catalog_id=catalog_id; 
			}				
		});							
	}	
	function select_item(itemid){	 // fucntion สำหรับการ เลือก สินค้า	
		 item_add(itemid);
		$('#catalog_seach').slideUp('fast'); 
	}	
	function customer_key_serach(){ // fucntion สำหรับการ เลือก customer จาก input 
		var cusid=$('#cusid').val();
		if(cusid==''){cusid=<?php echo $customer_id_default;?>;}	
			customer_search(cusid);
			return false;
		}
	function customer_search(cusid){ // fucntion สำหรับการ เลือก customer หาก serach ข้อมูลไม่เจอให้โชว์รายชื่อให้เลือก
		$('#cusid').val(cusid);
		$.ajax({
			url:'<?php echo base_url().$this->controller.'/pubAjaxCustomer';?>',
			type:'POST',
			data:{
				cusid:cusid
			},
			success:function(j){					
				var result = jQuery.parseJSON(j);					
				if(result.status==1){	
					$('#myTable tbody').html('');
					$('#receipt_total').html(0);
					if(cusid==<?php echo $customer_id_default;?>){//หาก เป็นเงินสด  ไม่สามารถเลือก bank ได้						
							$('#type_parmant').hide();									
							$('#type_parmant').val('');
					}else{
						$('#type_parmant').show();
						}
					a_customer =jQuery.parseJSON(j);							
					$('#cusid_input').val(cusid);
					$('#str_customer_name').val(a_customer.name+' '+a_customer.surname);
					$('#str_customer_tel').val(a_customer.tel1);
					$('#str_customer_add').val(a_customer.address1+' '+a_customer.address2+' '+a_customer.province+' '+a_customer.post);								
					$('#customer_near').hide();
					$('#customer_detail').slideDown('fast', function() {										
						$('#dettail_button').html("&nbsp;Hide&nbsp;");								
					});    
				}else{
					$('#customer_detail').hide();
					$('#customer_near').html(result.html);
					$('#customer_near').slideDown('fast');
				}												
			}
		});		
	}
	function customer_dettail(){  // ไว้สำหรับ กดแล้วโชว์ div : customer_detail		 
		$('#customer_detail').slideToggle('slow', function() {
			if ($(this).is(':visible')) {
				$('#dettail_button').html("&nbsp;Hide&nbsp;");
			} else {
				$('#dettail_button').html("Detail");
			}        
		});       
	}	
	
	</script>
	<div id='confirm_submit' class="cbp-spmenu-push" style='display:none;position: absolute;width:100%;z-index:999;background-color: #EEEEEE;padding:15px;'>
		<div class="control-group" id='container_2' align='center'>		
			<span style='font-size: 55px;'>ยอดชำระ</span><br/>
			<span id='confirm_total' style='font-size: 100px;'></span><br/>
			<span style='font-size: 30px;'>รวม <span id='total_qty'></span> รายการ</span>
			<br/>
			<br/>
		</div>	
		<br/>	
		<br style='clear:both;'/>  <br style='clear:both;'/>
		<hr></hr>
		<span class='col-xs-6' style='padding-left: 0px;padding-right: 7px;'>
			<button onclick="javascript:confirm_edit();"  style='padding: 20px 16px;' class="btn btn-default btn-lg col-xs-12">แก้ไข</button>
		</span>
		<span class='col-xs-6' style='padding-left: 7px;padding-right: 0px;'>
			<button onclick='document.getElementById("name_3").submit();'  href="parmant.html" style='padding: 20px 16px;' class="btn btn-success btn-lg col-xs-12">รับเงิน</button>
		</span>
		<br/> 			
	</div>
	<div class="main col-xs-12 col-sm-9 col-md-9 col-lg-10">	
		<div class='set_center'>
			<div class="control-group">
				<form name="form_1" style='margin-bottom: 0px;' onsubmit='return customer_key_serach();'>			
					<div class="input-group input-group-lg">
						<input maxlength='15' type='number' onkeypress="return isNumberKey(event);" style='padding: 10px 8px;' id='cusid' placeholder="1 : เงินสด" type="text" class="form-control">
						<span class="input-group-btn">
							<button onclick='javascript:customer_key_serach();'  style='padding: 10px 14px;' type="button" class="btn btn-default"><span style='padding-top: 3px;padding-bottom: 3px;' class="glyphicon glyphicon-search"></span></button>
							<button id='dettail_button' onclick='customer_dettail();' style='padding: 10px 8px;' class="btn btn-default" type="button">Detail</button>						
						</span>
					</div><!-- /input-group -->				
				</form>
				<div id='customer_near' class="form-horizontal well" style='display:none;'>
				</div>
				<div id='customer_detail' class="form-horizontal well" style='display:none;'>
					<fieldset>         
					<div class="control-group">            
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon"><span class='glyphicon glyphicon-user'></span></span>
								<input id='str_customer_name' value='' type="text" onKeyDown='return false;' class="form-control" placeholder="name"/>
							</div>
						</div>
						<label class="control-label" for="input01"></label>
						<div class="controls">
							<div class="input-group">
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-phone-alt"></span>
								</span>
								<input id='str_customer_tel' type="text" onKeyDown='return false;' class="form-control" placeholder="Tel"/>
							</div>
						</div>						
						<div class="controls" style=''>										
							<label class="control-label" for="input01"></label> 
							<textarea id='str_customer_add' onKeyDown='return false;' id='customer_textarea' class="form-control" placeholder="Adress" rows="3"></textarea>
						</div>
					</div>					 
					</fieldset>
				</div>
			</div>			
			<br/>
			<div class="control-group">
				<form name="form_2" style='margin-bottom: 0px;' onsubmit="return item_add($('#itemid').val());">				
				   <div class="input-group input-group-lg" style=''>			    
						<input maxlength='15' id='itemid'  onkeypress="return isNumberKey(event);"  style='padding: 10px 8px;' placeholder="Barcode" type='number' class="form-control">				
						<div class="input-group-btn">						
							<button onclick="javascript:item_search();" style='padding: 10px 14px;' type="button" class="btn btn-default"><span style='padding-top: 3px;padding-bottom: 3px;' class="glyphicon glyphicon-search"></span></button>
							<button onclick="javascript:item_add($('#itemid').val());" style='padding: 10px 8px;' type="button" class="btn btn-default">Add</button>            
						</div>				
					</div>
				</form>
				<div id='catalog_seach' class="form-horizontal well" style='display:none; padding:5px;'>					
				</div>
			</div>
			<br style='clear:both;'/>
			<form id='name_3' name='name_3' method='POST' action='<?php echo base_url().$this->controller.'/pubReceipt_submit';?>'>
				<input type='hidden' id='orderid' name='orderid' value='1'>
				<input type='hidden' id='pay_date' name='pay_date' value='2014-06-05'>
				<input type='hidden' id='trans_date' name='trans_date' value='2014-06-05'>
				<input type='hidden' id='commentr' name='commentr' value=''>
				<input type='hidden' id='supid' name='supid' value='1'>
				<input type='hidden' id='cusid_input' name='cusid_input' value='<?php echo $customer_id_default;?>'>
				<table id='myTable' class="table table-bordered table-hover">
					<thead>
						<tr>        
							<th>รายการ</th>
							<th class='col-xs-1'>จำนวน</th>
							<th class='col-xs-1'>ราคา</th>
						</tr>
					</thead>  
					<tbody>     	
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" align='right' ><b>รวม</b></td>
							<td id='receipt_total' align='center'>0</td>
					    </tr>
					</tfoot>
				</table>	
				<div class="control-group">
					<select id='type_parmant' style='display:none;' name='parmant' class='form-control'>
						<option value=''>เงินสด</option>				
						<?php foreach($a_bank as $value){ // โชว์ บัญชี bank 
							echo  "<option value='".$value['bankaccount']."'>".$value['brandbank']."</option>";
						}?>
					</select>
				</div>
				<br style='clear:both;'/>
			</form>
			<div align='center'>
				<button  onclick='confirm_submit();' style='width:80%;' class="btn btn-primary">Save</a>
			</div>  
		</div>
	</div>
		