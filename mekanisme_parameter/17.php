<?

class kuantitas_item_dengan_tambahan_diskon_apabila_ada_item_tertentu extends sql_dm{
	
	public $kuantitas_item_dengan_tambahan_diskon_apabila_ada_item_tertentu;
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		// daftar item order dengan paket
		$arr_sql = array(
			"a.order_id" => array("=", "'". main::formatting_query_string( $arr_parameter["order_id"] ) ."'"),
			"b.paketid" => array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'"),
		);
		$rs_order = self::browse_cart( $arr_sql );
		while( $order = sqlsrv_fetch_array( $rs_order ) )
			@$order_item[ trim($order["item_id"]) ] += $order["kuantitas"];

		// daftar item dalam paket parameter
		$this->kuantitas_item_dengan_tambahan_diskon_apabila_ada_item_tertentu = 0;
		unset( $arr_sql );
		$arr_sql = array(
			"f.paketid" => array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'"),
			"f.urutan_parameter" => array("=", "'". main::formatting_query_string( $arr_parameter["urutan_parameter"] ) ."'"),
		);
		$rs_paket_parameter_item = self::browse_paket_parameter_item( $arr_sql );
		while( $paket_parameter_item = sqlsrv_fetch_array( $rs_paket_parameter_item ) ){
			if( array_key_exists( trim($paket_parameter_item["item"]), $order_item ) ){
				
				$this->kuantitas_item_dengan_tambahan_diskon_apabila_ada_item_tertentu += $order_item[ $paket_parameter_item["item"] ];
				//break;
			}
		}


		
	}
	
}

?>