<?

class kuantitas_item_campuran_pengambilan_item_wajib_atau extends sql_dm{
	
	public $kuantitas_item_campuran_pengambilan_item_wajib_atau;
	
	function __construct( $obyek_dm, $arr_parameter ){
	
		$arr_paket_parameter_item = $arr_item_paket = array();
		
		unset( $arr_sql );
		$arr_sql["b.order_id"] = array("=", "'". main::formatting_query_string( $arr_parameter["order_id"] ) ."'");
		$arr_sql["b.paketid"] = array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'");
		$rs_item_paket = self::browse_cart( $arr_sql );
		while( $item_paket = sqlsrv_fetch_array( $rs_item_paket ) )
			$arr_item_paket[ $item_paket["item_id"] ] = $item_paket["kuantitas"];

		unset( $arr_sql );
		$arr_sql["f.paketid"] = array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'");
		$arr_sql["f.urutan_parameter"] = array("=", "'". main::formatting_query_string( $arr_parameter["urutan_parameter"] ) ."'");
		$rs_paket_parameter_item = self::browse_paket_parameter_item( $arr_sql );
		while( $paket_parameter_item = sqlsrv_fetch_array( $rs_paket_parameter_item ) ){
			if( array_key_exists( trim($paket_parameter_item["item"]), $arr_item_paket ) )
				$arr_paket_parameter_item[ $paket_parameter_item["item"] ] = $arr_item_paket[ $paket_parameter_item["item"] ];
			else
				$arr_paket_parameter_item[ $paket_parameter_item["item"] ] = 0;
		}

		$this->kuantitas_item_campuran_pengambilan_item_wajib_atau = array_sum( array_values( $arr_paket_parameter_item ) );

	}
	
}

?>