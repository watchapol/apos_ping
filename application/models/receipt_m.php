<?php 
class receipt_m extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function pubGetReceipt($recid){
		$querySQL = 'SELECT recid,totalprice
		from receipt		
		where recid ='.$recid;		
		//echo $querySQL;
		 $result = $this->adodb->GetRow($querySQL);
		//pre($result);
		return $result;		
	}
	
}
?>