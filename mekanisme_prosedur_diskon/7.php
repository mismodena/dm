<?

include_once "lib/cls_prosedur_khusus_tambahan_diskon.php";

class mekanisme_prosedur_diskon_7 extends prosedur_khusus_tambahan_diskon{
	private $mekanisme_prosedur_diskon = "";
	
	function __construct( $arr_parameter ){
		
		// cek dealer modern+professional atau bukan, apabila bukan dealer modern+professional, maka diteruskan ke mekanisme diskon display reguler
		$dealer_grup = sqlsrv_fetch_array( sql::execute("select ltrim(rtrim(idgrp)) idgrp, upper(rtrim(ltrim(email1))) email1 from ". $GLOBALS["database_accpac"] ."..arcus where  idcust = ". $arr_parameter["a3.dealer_id"][1] .";") );
		if( 
			!in_array( $dealer_grup["idgrp"], explode( ",", str_replace( "'", "", $GLOBALS["arr_dealer_modern"] ) ) )  && 
			!in_array( $dealer_grup["email1"],  explode( ",", str_replace( "'", "", $GLOBALS["arr_dealer_professional"] ) ) ) 
			) return false;
		
		$js_parameter = "dealer_id=". str_replace("'", "", $arr_parameter["a3.dealer_id"][1]) ."&order_id=". str_replace("'", "", $arr_parameter["a.order_id"][1] ) ."&diskonid=7";
		
		echo "
		<script>		
		window.onload=function (){
			document.getElementById('b_item_7').setAttribute('onclick', 'location.href=\'diskon-pengajuan-pilihitemorder-display-modern.php?$js_parameter\'');
		};
		</script>";
		
	}
	
	function mekanisme_prosedur_diskon(){
		return $this->mekanisme_prosedur_diskon;
	}
	
	function saldo_tersedia_akhir(){return "";}
	function saldo_tersedia_awal(){return "";}
	function prefiks_identifikasi_bqtq(){return "";}
	function ada_yg_blm_dialokasikan(){return "";}
	function sufiks_identifikasi_bqtq(){return "";}
	
}

?>