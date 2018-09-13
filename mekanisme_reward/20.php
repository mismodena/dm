<?

class reward_porsi_nilai_order_pembelian  extends sql_dm{	
	
	function __construct( $obyek_dm, $arr_parameter ){
		
		// cari semua paket item
		$sql = "select * from paket_item where paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."' and mode = 1 ";
		$rs_semua_paket_item = sql::execute( $sql );
		while( $semua_paket_item = sqlsrv_fetch_array( $rs_semua_paket_item ) )
			$arr_semua_paket_item[] = $semua_paket_item["item"];
		
		// cari nilai item dan kategori item di dalam order_item yang mempunyai paketid = $arr_parameter["paketid"], dan harus berada di list array $arr_semua_paket_item
		$arr_set["b.order_id"] = array("=", "'". main::formatting_query_string( $arr_parameter["order_id"] ) ."'");
		$arr_set["b.paketid"] = array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'");
		$rs_item_paket = self::browse_cart( $arr_set );
		$data_kategori_item = sqlsrv_fetch_array( $rs_item_paket );
		
		$sql = "select * from sub_kategori where kode_prefiks like '%". substr($data_kategori_item["item_id"], 0, 2) ."%'";
		$rs_kategori = sql::execute( $sql );
		$rs_kategori_item = sqlsrv_fetch_array( $rs_kategori );		
		$kategori_item = $rs_kategori_item["kode_prefiks"];
		
		$arr_item_paket = array();
		$rs_item_paket = self::browse_cart( $arr_set );
		while( $item_paket = sqlsrv_fetch_array( $rs_item_paket ) )
			if( in_array( $item_paket["item_id"], $arr_semua_paket_item ) )
				//@$arr_item_paket[ $item_paket["item_id"] ] += ( $item_paket["kuantitas"] * $item_paket["harga"] );
					$obyek_dm->arr_item_diskon[  $item_paket["item_seq"] ][ $arr_parameter["paketid"] ] = ( $arr_parameter["nilai_reward"] / 100 ) * $item_paket["kuantitas"] * $item_paket["harga"];			
		
		// cari nilai item di dalam order_item yang mempunyai paketid is null (tidak ambil paket), ataupun mempunyai paketid = $arr_parameter[paketid] namun tidak berada di list array $arr_semua_paket_item
		$arr_item_non_paket = array();	
		unset( $arr_set );
		$arr_set["b.order_id"] = array("=", "'". main::formatting_query_string( $arr_parameter["order_id"] ) ."'");
		$arr_set["substring( b.item_id, 1, 2 )"] = array(" in ", " ( '". str_replace( ",", "','", $kategori_item ) ."' ) ");
		$arr_set["/*b.paketid*/"] = array("", "( b.paketid is NULL or b.paketid = '' or b.paketid = '". main::formatting_query_string( $arr_parameter["paketid"] ) ."' )");
		$rs_item_non_paket = self::browse_cart( $arr_set );

		while( $item_non_paket = sqlsrv_fetch_array( $rs_item_non_paket ) ){
			
			// Yang diberikan diskon hanya khusus untuk item-non campaign (tidak masuk di paket_item). 
			// Sedangkan untuk item campaign (masuk dalam paket_item) harus dipilih campaign-nya, agar diberikan diskon (apabila tidak dipilih campaign, maka tidak diberikan diskon (diskon = 0) -> asumsinya akan diajukan diskon tambahan, misalnya diskon display dll)
			if( !in_array( $item_non_paket["item_id"], $arr_semua_paket_item ) ){
				//@$arr_item_non_paket[ $item_non_paket["item_id"] ] += ( $item_non_paket["kuantitas"] * $item_non_paket["harga"] );
				$obyek_dm->arr_item_diskon[  $item_non_paket["item_seq"] ][ $arr_parameter["paketid"] ] = ( $arr_parameter["nilai_reward"] / 100 ) * $item_non_paket["kuantitas"] * $item_non_paket["harga"];			
				$arr_item_seq_non_paket[] = $item_non_paket["item_seq"];
				
				unset( $arr_set, $arr_parameter_update );
				$arr_set["diskon"] = array("=", "". $obyek_dm->arr_item_diskon[  $item_non_paket["item_seq"] ][ $arr_parameter["paketid"] ] ."");
				$arr_set["diskon_default"] = array("=", "". $obyek_dm->arr_item_diskon[  $item_non_paket["item_seq"] ][ $arr_parameter["paketid"] ] ."");
				$arr_set["paketid"] = array("=", "'". main::formatting_query_string( $arr_parameter["paketid"] ) ."'");
				$arr_parameter_update["order_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["order_id"] ) . "'");
				$arr_parameter_update["user_id"] = array("=", "'" . main::formatting_query_string( $arr_parameter["user_id"] ) . "'");
				$arr_parameter_update["item_seq"] = array("=", "'" . main::formatting_query_string( $item_non_paket["item_seq"] ) . "'");
				self::update_order_item( $arr_set, $arr_parameter_update );
					
			}
			
		}
		
		// reset object item yang non paket
		foreach( $obyek_dm->arr_item_non_paket as $index=>$arr_data_item ){
			if( in_array( $arr_data_item["item_seq"], $arr_item_seq_non_paket ) ){
					
					unset( $obyek_dm->arr_item_non_paket[ $index ]["saran_paket"] );
					$obyek_dm->arr_item_dengan_paket[] = $obyek_dm->arr_item_non_paket[ $index ] + array("paketid" => $arr_parameter["paketid"]);
					
					unset( $obyek_dm->arr_item_non_paket[ $index ] );
				
			}
		}
		
		
	}
	
}

?>