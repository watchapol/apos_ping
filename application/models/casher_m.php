<?php 
class casher_m extends CI_Model{

var $table ='casher';

	public function __construct()
	{
		parent::__construct();
	}
	public function pubGetcasher() 
	{
			$querySQL = 'SELECT end_money
		from '.$this->table.'		
		order by rec_time DESC';
		$result = $this->adodb->GetRow($querySQL);
		return $result;		
	}
	
}
?>