<?

class kuantitas_sub_kategori extends sql_dm{
	
	public 		$kuantitas_sub_kategori
				, $arr_sub_kategori_order // [sub kategori] => item dengan kuantitas terkecil dalam sub kategori 
				; 
	
	function __construct( $obyek_dm, $arr_parameter ){

		$arr_sql = array(
				"paketid" => array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'")
			);
		$arr_sub_kategori = self::paket_sub_kategori( $arr_sql );
		
		// cek shopping cart
		$arr_sql = array(
				"a.order_id" => array("=", "'". main::formatting_query_string( $arr_parameter["order_id"] ) ."'"),
				"a.user_id" => array("=", "'". main::formatting_query_string( $arr_parameter["user_id"] ) ."'"),
				"b.paketid" => array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'"),
				"b.kuantitas" => array(">", 0),
				"b.harga" => array(">", 0)				
			);
		$rs_order_item = self::browse_cart( $arr_sql );
		
		while( $order_item = sqlsrv_fetch_array( $rs_order_item ) ){
			
			foreach( $arr_sub_kategori as $sub_kategori => $arr_prefiks ){
				
				if( in_array( substr($order_item["item_id"], 0, 2), $arr_prefiks ) && @$this->arr_sub_kategori_order[ $sub_kategori ] < $order_item["kuantitas"] ){
					
					$this->arr_sub_kategori_order[ $sub_kategori ] = $order_item["kuantitas"];
				}
				
			}
			
		}				

		$this->kuantitas_sub_kategori = count( @array_keys( $this->arr_sub_kategori_order ) );
	}
	
}

?>