<?php 
class customer_m extends CI_Model{

var $table ='customer';

	public function __construct()
	{
		parent::__construct();
	}
	public function pubGetCustomer($cusid) //สำหรับ ดึงข้อมูล database customer ,customertype
	{
		$querySQL = 'SELECT name,surname,tel1,address1,address2,province,post,cutid
		from '.$this->table.'
		where cusid ='.$cusid;
		//echo $querySQL;
		$result = $this->adodb->GetRow($querySQL);
		//pre($result);
		return $result;		
	}
	public function pubGetNoSearchCustomer(){
		$querySQL = 'SELECT cusid,name,surname
		from '.$this->table.'		
		limit 0,5';
		$result = $this->adodb->GetAll($querySQL);
		//pre($result);
		return $result;		
	}
	public function pubGetNearCustomer($cusid){
		$querySQL = 'SELECT cusid,name,surname
		from '.$this->table.'
		where cusid like "%'.$cusid.'%" limit 0,5';		
		$result = $this->adodb->GetAll($querySQL);
		//pre($result);
		return $result;		
	}
	
}
?>