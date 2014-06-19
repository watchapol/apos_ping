<?php 
class item_m extends CI_Model{

	public function __construct()
	{
		parent::__construct();
	}
	

	public function pubGetCount($itemid){
		$querySQL = 'SELECT name_count,count
		from count_name		
		where itmid ='.$itemid;		

		 $result = $this->adodb->GetAll($querySQL);

		return $result;		
	}
	public function pubGetItem($itemid,$cutid){ //สำหรับ ดึงข้อมูล database customer ,customertype	
		$querySQL = 'SELECT item.itemid,name,price,discount,percent,cutid,total,store.supid
		from item
		left join price on price.itemid = item.itemid
		left join store on store.itemid = item.itemid
		where (item.itemid ='.$itemid.'|| item.barcode ='.$itemid.') and cutid='.$cutid;		

		 $result = $this->adodb->GetRow($querySQL);

		return $result;		
	}
	public function pubGetItemCatalog($catalog_id){
		$querySQL = 'SELECT name,catalog_item.itemid
		from catalog_item
		left join item on catalog_item.itemid = item.itemid
		where catalog_item.catalog_name="'.$catalog_id.'"';	
		
		 $result = $this->adodb->GetAll($querySQL);

		return $result;		
	}
	public function pubSearchGroupItem($itemid){
		$querySQL = 'SELECT catalog_name
		from catalog_item
		left join item on catalog_item.itemid = item.itemid
		where item.itemid like "%'.$itemid.'%" || item.barcode like "%'.$itemid.'%"
		group by catalog_name';	
		 $result = $this->adodb->GetAll($querySQL);

		return $result;		
	}
		public function pubGetAllCatalog(){
		$querySQL = 'SELECT catalog_name
		from catalog_item
		group by catalog_name';			
		$result = $this->adodb->GetAll($querySQL);

		return $result;		
	}
}
?>