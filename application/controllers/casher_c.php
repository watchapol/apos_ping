<?php 
class casher_c extends CI_Controller {
	var $controller ='casher_c';	
	var $Mobile_Detect;
	public function __construct()
	{
		parent::__construct();			
		$this->load->library('adodb_loader');
		$this->load->model('casher_m');
		$this->load->library('Mobile_Detect'); // ใช้สำหรับตรวจสอบ ว่าเป็นมือถือหรือคอมพิวเตอร์  // application/libraries/Mobile_Detect.php
		$this->Mobile_Detect = new Mobile_Detect();
	} 
	
	
	public function pubAjaxCast(){		//function สำหรับการ submit จ่ายเงิน
	


		$a_casher = $this->casher_m->pubGetcasher();
		if(count($a_casher)==0){
			$int_start_money =0;
		}else{
			$int_start_money =floatval($a_casher['end_money']);
		}		
		$data['userid']=1;
		$data['recid']=$_POST['recid'];
		 $data['receive']=floatval($_POST['receive']);	
		
		$data['rec_time']=date('Y-m-d H:M:S');
		$data['commute']= $_POST['receive']-$_POST['totalprice'];
		$data['receipt']=$_POST['totalprice'];
		$data['start_money']=$int_start_money;
		$data['end_money']=$int_start_money+$_POST['totalprice'];	
		$error = $this->validateCasher($data['userid'],$data['recid'],$data['receive'],$data['rec_time'],$data['commute'],$data['receipt'],$data['start_money'],$data['end_money']);// ตรวจสอบก่อน insert

		
	
		if (is_int($error)){
			$this->db->trans_start();
			$this->db->insert('casher',$data); // insert ข้อมูลการจ่ายเงิน
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) 
			{
				$return['status']= 2;
				$return['error']= "trans error"; // Error
			}
			else{
				$return['status']= 1;
				$return['commute']= $data['commute']; // return เป็น เงินทอน
			}
		}
		echo json_encode($return);
	}
	private function check_mobile(){ //ตรวจสอบว่าเป็นมือถือรึเปล่า
		if( $this->Mobile_Detect->isMobile() ){
			return true;		
		}else{
			return false;
		}				
	}
	private function validateCasher($userid,$recid,$receive,$rec_time,$commute,$receipt,$start_money,$end_money){
		$erroract=0;
				if(strlen($userid)>=15){ $erroract = 1;$error['userid_error'] = "userid ID Length More";}
				if(strlen($userid)==0){$erroract = 1;$error['userid_notnull'] =  "userid ID Require";}
		
				if(strlen($recid)>=15){ $erroract = 1;$error['recid_error'] = "Receipt ID Length More";}
				if(strlen($recid)==0){$erroract = 1;$error['recid_notnull'] =  "Receipt ID Require";}
				
				if(!is_int($receive)&&!is_float($receive)){$erroract = 1;$error['receive_float'] =  "receive is not float ";}							
				if(strlen($receive)==0){$erroract = 1;$error['receive_notnull'] =  "receive  Require";}
				if(!is_int($start_money)&&!is_float($start_money)){$erroract = 1;$error['start_money_float'] =  "start_money is not float ";}							
				if(strlen($start_money)==0){$erroract = 1;$error['start_money_notnull'] =  "start_money  Require";}
				if(!is_int($end_money	)&&!is_float($end_money)){$erroract = 1;$error['end_money	_float'] =  "end_money	 is not float ";}							
				if(strlen($end_money)==0){$erroract = 1;$error['end_money_notnull'] =  "end_money  Require";}
				if(!is_int($commute)&&!is_float($commute)){$erroract = 1;$error['commute_float'] =  "commute is not float ";}															
								
				if($erroract==1){			
					$return['status']= 2;
						$return['error']=$error ; // Error
					echo json_encode($return);
					exit();
				}else{

					return 0;	
				}		
	}	
	public function pubMain($recid='') //function สำหรับ แสดงหน้าหลัง
	{						
		if($recid==''){
			redirect(base_url().'receipt_c');
		}		
		$this->load->model('receipt_m');
		$a_rec =$this->receipt_m->pubGetReceipt($recid);		
		if(count($a_rec)==0){
			redirect(base_url().'receipt_c');
		}		
		$check_mobile=$this->check_mobile(); //ตรวจสอบว่าเป็นมือถือรึเปล่า		
		$a_rang =$this->priRang($a_rec['totalprice']);		
		$this->load->view('head_v',array('str_title'=>'เลขที่ใบเสร็จ')); 	
		$this->load->view('casher_v',array('check_mobile'=>$check_mobile,'receipt'=>$a_rec,'a_rang'=>$a_rang ));		
		$this->load->view('foot_v');
	}
	private function priRang($paid){ //function สำหรับคำนวณ ขอบเขตการจ่ายเงิน หรือ สร้างปุ่มจ่ายเงิน แบบปปัดเศษ 
		$rang_pay = 4; // กำหนดให้หลักเริ่มต้นในการจ่ายเงิน เป็น 4  คือ หลัก พัน
		if(strpos( $paid, "." )!=0){
			if(strpos($paid,".")>$rang_pay){
				$rang_pay=strpos( $paid, "." );
			}
		}else if(strlen($paid)>$rang_pay){
		$rang_pay = strlen($paid);
		}		
		$a_rang[0]=$this->prrCeiling($paid,pow(10,($rang_pay-1)));    
		$a_rang[1]=$this->prrCeiling($paid,pow(10,($rang_pay-2)));    
		$a_rang[2]=$this->prrCeiling($paid,pow(10,($rang_pay-3)));  
		$a_rang[3]=$paid;
		return $a_rang;
	}
	private function prrCeiling($number, $significance = 1){ // function สำหรับการปรับเศษ  ตามหลักของเงิน
		return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
	}
}