<?php 
class receipt_c extends CI_Controller {
	var $controller ='receipt_c';
	var $customer_id_default =1; // ลูกค้าเงินสด
	var $Mobile_Detect;
	public function __construct()
	{
		parent::__construct();				
			$this->load->library('adodb_loader');
		
			$this->load->model('item_m'); 
			$this->load->model('customer_m');
			$this->load->model('bank_m');
			$this->load->model('recid_m');
			$this->load->model('store_m');
		
			date_default_timezone_set("Asia/Bangkok");		
			$this->load->library('Mobile_Detect'); // ใช้สำหรับตรวจสอบ ว่าเป็นมือถือหรือคอมพิวเตอร์  // application/libraries/Mobile_Detect.php
			$this->Mobile_Detect = new Mobile_Detect();
	} 	
	public function pubReceipt_submit(){ //  สำหรับ submit หน้า Receipt  เก็บข้อมูลเข้าระบบ	

		$recid= $this->recid_m->pubGetReceipt_id();		
		$recid= $recid['recid']+1;//get recid ล่าสุดออกมา									
		$count_index =  count($_POST['item_id']);
		$totalprice =0;	
		$this->db->trans_start();		
		for($i=0;$i<$count_index;$i++){ 
		$check_receipt_detail=0; // กำหนดให้เป็นค่า 0  หาก insert ข้อมูลได้จะปรับเป็น 1 
			$rec['recid']=$recid;
			$rec['itemid']=trim($_POST['item_id'][$i]);
			$rec['total']=$_POST['count'][$i]*$_POST['qty'][$i];
			$rec['supid']=$_POST['supid'][$i];
			$rec['price']=floatval($_POST['price'][$i]);
			
			$rec['col']=$i+1;
			$rec['totalprice']=trim($rec['total'])*trim($_POST['price'][$i]);
			$totalprice =$totalprice+$rec['totalprice'];
			$error = $this->validateReceipt_detail($rec['recid'],$rec['itemid'],$rec['total'],$rec['supid'],$rec['price'],$rec['col'],$rec['totalprice']);// ตรวจสอบก่อน insert
			if (is_int($error)){
				$check_receipt_detail =  $this->recid_m->pubinsertReceiptdetail($rec); //  insert item เข้า database	 receipt_detail						
				
			}			
			if($check_receipt_detail){  // หาก inser ผ่านให้หักลบ stock สินค้าได้
				$stock = $this->store_m->pubGetStock($_POST['item_id'][$i]); //ดึงข้อมูล ตัวเลขstock เพิ่อมาหักลบ
				$stock['total'] =$stock['total']-$rec['total'];
				$stock['chang_date'] =date('Y-m-d H:i:s');
				$this->store_m->pubUpdateStock($_POST['item_id'][$i],$stock);
				
			}	
			else{
				echo 'Error : ไม่สามารถ ลงทะเบียนสั่งซื้อสินค้าได้';			
			} 
		}		
		$data['recid']=trim($recid);
		$data['rec_date']=date('Y-m-d H:i:s');
		$data['bankaccount']=trim($_POST['parmant']);
		$data['cusid']=trim($_POST['cusid_input']);	
		$data['orderid']=trim($_POST['orderid']);
		$data['trans_date']=trim($_POST['trans_date']);
		$data['totalprice']=$totalprice;
		$data['pay_date']=trim($_POST['pay_date']);
		if($_POST['parmant']==''){
			$pay_type= 'cash';
		}else{
			$pay_type= 'bank';
		}
		$data['paytype']=trim($pay_type);
		$data['commentr']=trim($_POST['commentr']);
		$error = $this->validateReceipt($data['recid'],$data['bankaccount'],$data['cusid'],$data['orderid'],$data['rec_date'],$data['pay_date'],$data['totalprice'],$data['paytype']);								
		if (is_int($error)){// ตรวจสอบก่อน insert
			$this->recid_m->pubinsertReceipt($data);			
		}
		$this->db->trans_complete();	
		if ($this->db->trans_status() === FALSE) 
			{
				echo  "error"; // Error
				exit();
			}
		if($_POST['parmant']==''){
			 redirect(base_url().'casher_c/pubMain/'.$recid);
		}else{
			echo 'finish bank';
		}
	}	
	private function validateReceipt($recid,$bankaccount,$cusid,$orderid,$rec_date,$pay_date,$totalprice,$paytype){
				// ฟังก์ชันการตรวจสอบความถูกต้องของการกรอก form โดยจะแจ้ง error 			
				$erroract=0;
				if(strlen($recid)>=15){ $erroract = 1;$error['recid_error'] = "Receipt ID Length More";}
				if(strlen($recid)==0){$erroract = 1;$error['recid_notnull'] =  "Receipt ID Require";}
				if($bankaccount!=''){
					if(strlen($bankaccount)>=15){$erroract = 1;$error['itemid_error'] =  "Item ID Length More";}				
				}
				if(strlen($cusid)>=15){$erroract = 1;$error['cusid_error'] = "cusid  Length More";}
				if(strlen($cusid)==0){$erroract = 1;$error['cusid_notnull'] = "cusid  Require";}
				
				if(strlen($orderid)>=15){$erroract = 1;$error['orderid_error'] = "orderid  Length More";}
				if(strlen($orderid)==0){$erroract = 1;$error['orderid_notnull'] = "orderid  Require";}
								
				if(strlen($rec_date)==0){$erroract = 1;$error['rec_date_notnull'] = "rec_date  Require";}										
				if($paytype!='cash'&&$paytype!='bank'){$erroract = 1;$error['paytype_type'] = "paytype is not type";}				
				if(strlen($paytype)==0){$erroract = 1;$error['paytype_notnull'] = "paytype  Require";}							
				
				if(!is_int($totalprice)&&!is_float($totalprice)){$erroract = 1;$error['totalprice_float'] =  "totalprice is not float ";}							
				if(strlen($totalprice)==0){$erroract = 1;$error['totalprice_notnull'] =  "totalprice Require";}								
				if($erroract==1){							
					pre($error);					
				}else{

					return 0;	
				}
	}
	private function validateReceipt_detail($recid,$itemid,$total,$supid,$price,$col,$totalprice)
	{	
				// ฟังก์ชันการตรวจสอบความถูกต้องของการกรอก form โดยจะแจ้ง error 		
				$erroract=0;
				if(strlen($recid)>=15){ $erroract = 1;$error['recid_error'] = "Receipt ID Length More";}
				if(strlen($recid)==0){$erroract = 1;$error['recid_notnull'] =  "Receipt ID Require";}
				if(strlen($itemid)>=15){$erroract = 1;$error['itemid_error'] =  "Item ID Length More";}
				if(strlen($itemid)==0){$erroract = 1;$error['itemid_notnull'] =  "Item ID Require";}				
				if(strlen($supid)>=15){$erroract = 1;$error['supid_error'] = "Supplier ID Length More";}
				if(strlen($supid)==0){$erroract = 1;$error['supid_notnull'] = "Supplier ID Require";}
				
				if(!is_int($total)){$erroract = 1;$error['total_int'] =  "total is not int ";}	//เป็นจำนวนนับ ไม่มีทศนิยม
				if(strlen($total)==0){$erroract = 1;$error['total_notnull'] =  "total Require";}

				if(!is_int($price)&&!is_float($price)){$erroract = 1;$error['price_float'] =  "price is not float ";}			
				if(strlen($price)==0){$erroract = 1;$error['price_notnull'] =  "price Require";}
				
				if(!is_int($col)){$erroract = 1;$error['col_float'] =  "col is not int ";}	//เป็นจำนวนนับ ไม่มีทศนิยม
				if(strlen($col)==0){$erroract = 1;$error['col_notnull'] =  "col Require";}

				if(!is_int($totalprice)&&!is_float($totalprice)){$erroract = 1;$error['totalprice_float'] =  "totalprice is not float ";}							
				if(strlen($totalprice)==0){$erroract = 1;$error['totalprice_notnull'] =  "totalprice Require";}				
				if($erroract==1){					
					
					pre($error);
					
				}else{

					return 0;	
				}
			}
	private function check_mobile(){ //ตรวจสอบว่าเป็นมือถือรึเปล่า
		if( $this->Mobile_Detect->isMobile() ){
			return true;		
		}else{
			return false;
		}				
	}
	public function index() //  แสดง หน้า Receipt_V
	{					 

		$a_bank = $this->bank_m->pubGetAllBank(); //ส่งค่าไป database  เรียกธนาคาร
		$a_customer = $this->customer_m->pubGetCustomer($this->customer_id_default); // เรียกข้อมูลลูกค้าเงินสด
		
		$data['check_mobile']=$this->check_mobile(); //ตรวจสอบว่าเป็นมือถือรึเปล่า
		$data['a_bank']=$a_bank;
		$data['customer_default']=json_encode($a_customer);
		$data['customer_id_default']=$this->customer_id_default;
		
		$this->load->view('head_v',array('str_title'=>'ใบเสร็จ')); 	
		$this->load->view('receipt_v',$data);		
		$this->load->view('foot_v');
	}
	public function pubAjaxItem_catalog(){	// function สำหรับ ดึงสินค้า ในกลุ่มสินค้า 
		$a_item_catalog = $this->item_m->pubGetItemCatalog($_POST['catalog_id']); 
		$data['item_catalog']=$a_item_catalog;
		echo $this->load->view('receipt_item_catalog',$data, true);  // ดึง html receipt_item_catalog จาก สร้าง แถวตารางสินค้า
	}
	public function pubAjaxItem(){  // function สำหรับการค้นหา สินค้า
		$a_near_item = $this->item_m->pubSearchGroupItem($_POST['itemid']); 
		if(count($a_near_item)==0){// ไม่มีตัวเลขใกล้เคียง	
			$a_near_item = $this->item_m->pubGetAllCatalog($_POST['cutid']); //
		}
		$return['status']=2; //			
		$data['catalog'] =$a_near_item;
		$return['html'] = $this->load->view('receipt_item_search',$data, true);  //สร้าง แถวตาราง			
		echo json_encode($return);
	}
	public function pubAjaxitemStock(){	//function สำหรับการตรวจสอบ จำนวนสินค้า
		$a_stock = $this->store_m->pubGetStock($_POST['itemid']); 
		echo $a_stock['total']-$_POST['qty'];
	}
	public function pubAjaxitemAdd(){ //funciton สำหรับการ  เพิ่มสินค้า
		$a_item = $this->item_m->pubGetItem($_POST['itemid'],$_POST['cutid']); 
		if(count($a_item)!=0){
			if($a_item['total']>0){			 // เตรียมจำนวน stock 
			//-----------คิดราคา โดย หากมี ส่วนลด ให้ ลบ ก่อน  หากไม่มีให้ใช่ percent	
				if($a_item['discount']!=0){
					$int_price = $a_item['price']-$a_item['discount'];
				}
				else if($a_item['percent']!=0){
					$int_price = $a_item['price']-($a_item['price']*($a_item['percent'])/100);
				}else{
					$int_price = $a_item['price'];		
				}
			//-----------คิดราคา จบ---------------------
			//-----------ดึง database count -----------------------
				$a_count = $this->item_m->pubGetCount($_POST['itemid']); // get count
			//-----------ดึง database count 							
				$data['id'] =$_POST['itemid'];
				$data['supid'] =$a_item['supid'];
				$data['name'] =$a_item['name'];
				$data['price'] =$int_price;
				$data['count'] =$a_count;
			$return['html'] = $this->load->view('receipt_tr',$data, true);  //สร้าง แถวตาราง
			$return['status']=1; 
			}
			else{
				$return['status']=3; 
			}
			echo json_encode($return);
		}else{ 
			$this->pubAjaxItem(); //หากค้นหาไม่เจอ ให้เรียก fucntion pubAjaxItem ซึ่ง ใน pubAjaxItem มี คำสั่ง return json_encode ด้วยตัวเอง
		}		
	}
	
	public function pubAjaxCustomer(){  //ค้นหาข้อมูล ลูกค้า
		
		$a_customer = $this->customer_m->pubGetCustomer($_POST['cusid']); //ส่งค่าไป database 
		if(count($a_customer)==0){ //ไม่มีข้อมูลให้  
			$a_near_customer = $this->customer_m->pubGetNearCustomer($_POST['cusid']); //ส่งค่าไป database 			
			if(count($a_near_customer)!=0){// ไม่มีตัวเลขใกล้เคียง							
				$return['status']=2; //  ใกล้เคียง 5 คนแรก
			}else{
				$a_near_customer = $this->customer_m->pubGetNoSearchCustomer($_POST['cusid']); //ส่งค่าไป database 
				$return['status']=3; // ไม่พบข้อมูล ให้โชว์ 5 ข้อมูล
			}			
			$html = '<table class="table table-bordered table-hover">';
			foreach($a_near_customer as $value){				
				$html= $html."<tr style='cursor:pointer;' onclick='javascript:customer_search(".$value['cusid'].");'><td><span class='glyphicon glyphicon-user' style='padding-right:10px;'></span>".$value['name']." ".$value['surname']."</td></tr>";
			}
			$html =$html.'</table>';
			$return['html'] =$html;			
		}
		else{
			$return = $a_customer;
			$return['status']=1; // ค้นหาเจอ
			
		}
		echo json_encode($return);
	}		
}