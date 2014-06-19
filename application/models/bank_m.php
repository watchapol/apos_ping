<?php
class bank_m extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function pubGetAllBank(){
		$querySQL = 'SELECT bankaccount,bank_name,brandbank
		from bank';
		
		//echo $querySQL;
		 $result = $this->adodb->GetAll($querySQL);
		//pre($result);
		return $result;		
	}
}
?>