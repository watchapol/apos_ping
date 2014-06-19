<?php 
class store_m extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	public function pubUpdateStock($item_id,$stock){
		$this->db->where('itemid',$item_id);
			$this->db->update('store',$stock); //  update stock เข้า database	 store		
	}
	public function pubGetStock($itemid){
		$querySQL = 'SELECT total
		from store		
		where itemid ='.$itemid;		

		 $result = $this->adodb->GetRow($querySQL);

		return $result;		
	}

	
}
?>