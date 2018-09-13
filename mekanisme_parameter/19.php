<?

class kuantitas_item_kombinasi_total_order_net extends sql_dm{
	
	public $kuantitas_item_kombinasi_total_order_net;
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		// dapatkan total item paket dulu
		$arr["#order_id#"] = $arr_parameter["order_id"];
		$arr["#paketid#"] = $arr_parameter["paketid"];
		$arr["#user_id#"] = $arr_parameter["user_id"];
		$sql = str_replace( array_keys($arr), array_values($arr), file_get_contents(__DIR__ . "/3.php") );
		$rs =  sqlsrv_fetch_array( sql::execute( $sql ) );
		$kuantitas = $rs[0];
		
		// dapatkan total order (nominal rupiah) paket
		$sql = str_replace( array_keys($arr), array_values($arr), file_get_contents(__DIR__ . "/12.php") );
		$rs =  sqlsrv_fetch_array( sql::execute( $sql ) );
		$nominal = $rs[0];
		
		$this->kuantitas_item_kombinasi_total_order_net = $kuantitas . ";" . $nominal;

	}
	
}

?>