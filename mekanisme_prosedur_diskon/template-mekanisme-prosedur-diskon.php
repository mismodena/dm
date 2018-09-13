<?

include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";

class mekanisme_prosedur_diskon_"[diskon_id]" extends prosedur_khusus_tambahan_diskon{
	private $mekanisme_prosedur_diskon = "";
	
	function __construct( $arr_parameter ){
		
	}
	
	function mekanisme_prosedur_diskon(){
		return $this->mekanisme_prosedur_diskon;
	}
	
}

?>