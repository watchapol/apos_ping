<?php 
class recid_m extends CI_Model{
	public function __construct()
	{
		parent::__construct();
	}
	public function pubinsertReceiptdetail($data){
		return $this->db->insert('receipt_detail',$data);
	}
	public function pubinsertReceipt($data){
		$this->db->insert('receipt',$data);
	}
	public function pubGetReceipt_id(){
		$querySQL = 'SELECT recid
		from receipt
		order by rec_date DESC
		limit 0,1
		';
		
		 $result = $this->adodb->GetRow($querySQL);

		return $result;		
	}
}
?>